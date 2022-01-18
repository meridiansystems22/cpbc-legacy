<?php
// sheet.php - the "Google Sheets API"
// ------------------------------------
// This is the data layer. There is no database. Every record lives in a
// Google Sheet, and this file is the code a volunteer wrote to read and write
// it through a Google Apps Script web app.
//
// In this local build the sheet is a CSV file (see config.php SHEET_CSV) and
// these functions read and write that file the same naive way the Apps Script
// version hit the live sheet: read the WHOLE sheet on every call, write the
// WHOLE sheet back on every change, no locking, everything a string.
//
// The usleep() calls below stand in for the network round-trip to Google.
// On the live system every one of these was a real API call, subject to
// Google's rate limits. When the Centre got busy, the quota ran out and the
// system simply stopped until the next day.

require_once __DIR__ . '/config.php';

$SHEET_COLUMNS = ['id','name','nric','dob','address','phone','email',
                  'employment','income','referral_source','area_of_law',
                  'status','notes'];

// Read the entire sheet. Every time. There is no "get one row" on the real
// API the way it was written, so every lookup pulls everything.
// When USE_LIVE_SHEET is on, these talk to the Apps Script web app instead of
// the CSV. Same naive shape: whole sheet in, whole sheet out, no lock.
function api_get() {
    $raw = file_get_contents(APPS_SCRIPT_URL);   // no timeout, no error handling
    return json_decode($raw, true);
}
function api_post($payload) {
    $ctx = stream_context_create(['http' => [
        'method' => 'POST',
        'header' => "Content-Type: application/json\r\n",
        'content' => json_encode($payload),
    ]]);
    return json_decode(file_get_contents(APPS_SCRIPT_URL, false, $ctx), true);
}

function sheet_read_all() {
    if (USE_LIVE_SHEET) { usleep(300000); return api_get(); }
    global $SHEET_COLUMNS;
    usleep(300000); // 300ms - a Google Sheets API round trip. Every call.
    $rows = [];
    if (!file_exists(SHEET_CSV)) return $rows;
    $fh = fopen(SHEET_CSV, 'r');
    $header = fgetcsv($fh, 0, ',', '"', '');
    while (($line = fgetcsv($fh, 0, ',', '"', '')) !== false) {
        $row = [];
        foreach ($SHEET_COLUMNS as $i => $col) {
            $row[$col] = isset($line[$i]) ? $line[$i] : '';
        }
        $rows[] = $row;
    }
    fclose($fh);
    return $rows;
}

// Write the entire sheet back. No lock. If two volunteers save at the same
// time, one read the sheet, the other read the sheet, and whoever writes last
// wins - the other's change is gone, with nothing to say it ever happened.
// For a note recording a limitation deadline, "gone" means gone.
function sheet_write_all($rows) {
    global $SHEET_COLUMNS;
    usleep(300000);
    // (live-sheet writes go through sheet_update/sheet_append below, not here)
    $fh = fopen(SHEET_CSV, 'w');       // no flock() anywhere in this file
    fputcsv($fh, $SHEET_COLUMNS, ',', '"', '');
    foreach ($rows as $r) {
        $line = [];
        foreach ($SHEET_COLUMNS as $col) $line[] = isset($r[$col]) ? $r[$col] : '';
        fputcsv($fh, $line, ',', '"', '');
    }
    fclose($fh);
}

function sheet_find($id) {
    foreach (sheet_read_all() as $r) {
        if ($r['id'] === (string)$id) return $r;
    }
    return null;
}

// Search reads the whole sheet and filters in PHP. With a few thousand rows
// and a 300ms read, a search takes most of a second before it even starts.
function sheet_search($q) {
    $out = [];
    foreach (sheet_read_all() as $r) {
        if ($q === '' || stripos($r['name'], $q) !== false) $out[] = $r;
    }
    return $out;
}

function sheet_append($row) {
    if (USE_LIVE_SHEET) { $r = api_post(['action'=>'append','row'=>$row]); return $r['id']; }
    $rows = sheet_read_all();               // read whole sheet
    $maxid = 0;
    foreach ($rows as $r) if ((int)$r['id'] > $maxid) $maxid = (int)$r['id'];
    $row['id'] = (string)($maxid + 1);
    $rows[] = $row;
    sheet_write_all($rows);                 // write whole sheet
    return $row['id'];
}

// Update one field on one row. Read-modify-write the entire sheet. The gap
// between the read and the write is where two volunteers collide.
function sheet_update($id, $field, $value) {
    if (USE_LIVE_SHEET) { api_post(['action'=>'update','id'=>$id,'field'=>$field,'value'=>$value]); return; }
    $rows = sheet_read_all();
    foreach ($rows as &$r) {
        if ($r['id'] === (string)$id) { $r[$field] = $value; }
    }
    unset($r);
    sheet_write_all($rows);
}
