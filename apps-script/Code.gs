/**
 * CPBC Enquiry System - Apps Script.
 * Lets the app read and write the Centre's Google Sheet.
 * SHEET_NAME must match the tab name in the sheet.
 */

var SHEET_NAME = 'Enquiries';

function getSheet_() {
  return SpreadsheetApp.getActiveSpreadsheet().getSheetByName(SHEET_NAME);
}

// Read: returns the whole sheet as JSON. No auth. No paging. Everything.
function doGet(e) {
  var rows = getSheet_().getDataRange().getValues();
  var header = rows.shift();
  var out = [];
  for (var i = 0; i < rows.length; i++) {
    var obj = {};
    for (var j = 0; j < header.length; j++) obj[header[j]] = String(rows[i][j]);
    out.push(obj);
  }
  return ContentService
    .createTextOutput(JSON.stringify(out))
    .setMimeType(ContentService.MimeType.JSON);
}

// Write: append a row, or update one field on one row. No lock. Last write
// wins. This is where two volunteers collide.
function doPost(e) {
  var p = JSON.parse(e.postData.contents);
  var sheet = getSheet_();
  var data = sheet.getDataRange().getValues();
  var header = data[0];

  if (p.action === 'append') {
    var maxid = 1000;
    for (var i = 1; i < data.length; i++)
      if (Number(data[i][0]) > maxid) maxid = Number(data[i][0]);
    var row = [];
    var newid = maxid + 1;
    for (var j = 0; j < header.length; j++)
      row.push(header[j] === 'id' ? newid : (p.row[header[j]] || ''));
    sheet.appendRow(row);
    return json_({ok: true, id: newid});
  }

  if (p.action === 'update') {
    var col = header.indexOf(p.field);
    for (var i = 1; i < data.length; i++) {
      if (String(data[i][0]) === String(p.id)) {
        // read the whole sheet, change one cell, no lock anywhere
        sheet.getRange(i + 1, col + 1).setValue(p.value);
        return json_({ok: true});
      }
    }
    return json_({ok: false, error: 'not found'});
  }

  return json_({ok: false, error: 'unknown action'});
}

function json_(o) {
  return ContentService.createTextOutput(JSON.stringify(o))
    .setMimeType(ContentService.MimeType.JSON);
}
