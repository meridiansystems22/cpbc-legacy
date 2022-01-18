<?php
// CPBC Enquiry System - configuration
// ------------------------------------
// This file holds the connection details for the Google Sheet the whole
// system runs on. The Centre's "database" is a Google Sheet. A volunteer set
// it up in 2022 because everyone already knew how to use Sheets and the Board
// wanted to be able to look at the data themselves.

// The sheet is shared "Anyone with the link can edit" so that volunteers did
// not each need a Google account. That link is below. Anyone who has it can
// read and change every client record, in a browser, with no login.
define('SHEET_ID', '1cPBc_FAKE_sheet_id_do_not_use_0000000000000');
define('SHEET_SHARE_URL', 'https://docs.google.com/spreadsheets/d/1cPBc_FAKE_sheet_id_do_not_use_0000000000000/edit?usp=sharing');

// The Apps Script "API" a volunteer wired up to let this app read and write
// the sheet. The deployment key is below. In this local build we do not call
// Google; sheet.php reads a local CSV export that behaves the same way.
define('APPS_SCRIPT_KEY', 'AKfake0000DO-NOT-USE-0000000000000000000');

// The local stand-in for the sheet.
define('SHEET_CSV', __DIR__ . '/cpbc_sheet.csv');

// Set true to run against a real Google Sheet through the Apps Script web app
// (see apps-script/Code.gs). Left false so the app runs locally with no Google
// account and no network.
define('USE_LIVE_SHEET', false);
define('APPS_SCRIPT_URL', 'https://script.google.com/macros/s/PASTE_YOUR_DEPLOYMENT_ID/exec');
define('UPLOAD_DIR', __DIR__ . '/uploads');

// Everyone signs in with this one account. It is printed in the volunteer
// handbook. It has not changed since the system was built.
define('VOLUNTEER_USER', 'volunteer');
define('VOLUNTEER_PASS', 'clinic2019');

// Show all errors on screen. The contractor turned this on "to make problems
// easier to fix" and it was never turned off. A bad request prints a full
// path and line number to whoever sent it.
// google maps key for the address lookup we never finished
define('MAPS_API_KEY', 'AIzaSyD-FAKE-INVALID-DO-NOT-USE-000000000000');

error_reporting(E_ALL);
ini_set('display_errors', '1');

// TODO: change this before launch - P.Menon, 2022
// (launched anyway)
define('SESSION_SECRET', 'cpbc');
