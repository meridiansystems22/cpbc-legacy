<?php
session_start();
require_once 'sheet.php';
if (!isset($_SESSION['user'])) { header('Location: login.php'); exit; }

// Any signed-in volunteer can open any matter by changing the number in the
// address bar. R16 said "volunteers shall be able to access enquiry records"
// and this is what that became. No check that the matter is theirs. No record
// kept of who opened it.
$id = $_GET['id'];
$m = sheet_find($id);
if ($m === null) { http_response_code(404); echo 'Matter not found'; exit; }

// Save a note. Read-modify-write of the whole sheet, no lock (see sheet.php).
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // R20: free-text notes field. Stored exactly as typed. When the Board
    // opens the sheet, a note beginning with = is run as a spreadsheet
    // formula. Stored here, it is also echoed back below without escaping.
    sheet_update($id, 'notes', $_POST['note']);
    header('Location: matter.php?id=' . $id);
    exit;
}
?>
<!doctype html>
<html><head><title>Matter <?php echo $m['id']; ?> - CPBC</title>
<link rel="stylesheet" href="style.css"></head>
<body>
<div class="bar">CPBC Enquiry System
  <span class="right"><a href="matters.php">Back</a> ·
  <a href="logout.php">Sign out</a></span></div>
<div class="wrap">
  <h2>Matter <?php echo $m['id']; ?> - <?php echo $m['name']; ?></h2>
  <?php if (isset($_GET['msg'])) echo '<p class="err">' . $_GET['msg'] . '</p>'; ?>

  <table class="detail">
    <tr><th>NRIC</th><td><?php echo $m['nric']; ?></td></tr>
    <tr><th>Date of birth</th><td><?php echo $m['dob']; ?></td></tr>
    <tr><th>Address</th><td><?php echo $m['address']; ?></td></tr>
    <tr><th>Phone</th><td><?php echo $m['phone']; ?></td></tr>
    <tr><th>Email</th><td><?php echo $m['email']; ?></td></tr>
    <tr><th>Employment</th><td><?php echo $m['employment']; ?></td></tr>
    <tr><th>Monthly income</th><td><?php echo $m['income']; ?></td></tr>
    <tr><th>Area of law</th><td><?php echo $m['area_of_law']; ?></td></tr>
    <tr><th>Status</th><td><?php echo $m['status']; ?></td></tr>
  </table>

  <h3>Notes</h3>
  <!-- echoed straight out. whatever is in the field runs in the browser of
       the next volunteer who opens the matter. -->
  <div class="notes"><?php echo $m['notes']; ?></div>

  <form method="post">
    <textarea name="note" placeholder="Add a note"><?php echo $m['notes']; ?></textarea>
    <button type="submit">Save note</button>
  </form>

  <div class="actions">
    <!-- countersign: the button is here, the workflow behind it is not -->
    <a href="matter.php?id=<?php echo $m['id']; ?>&msg=Countersigning+is+coming+in+a+future+update.">Countersign triage</a>
    · <a href="limitation.php?id=<?php echo $m['id']; ?>">Check limitation period</a>
  </div>
</div>
</body></html>
