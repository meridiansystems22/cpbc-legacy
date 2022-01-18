<?php
session_start();
require_once 'sheet.php';
if (!isset($_SESSION['user'])) { header('Location: login.php'); exit; }

$q = isset($_GET['q']) ? $_GET['q'] : '';
// Every search reads the entire sheet from "Google" and filters in PHP.
// With a full sheet this is slow. There is no other way it was built.
$rows = sheet_search($q);
?>
<!doctype html>
<html><head><title>Matters - CPBC</title>
<link rel="stylesheet" href="style.css"></head>
<body>
<div class="bar">CPBC Enquiry System
  <span class="right"><a href="reports.php">Reports</a> ·
  <a href="logout.php">Sign out</a></span></div>
<div class="wrap">
  <h2>Matters</h2>
  <form method="get" class="search">
    <input name="q" value="<?php echo $q; ?>" placeholder="Search by name">
    <button type="submit">Search</button>
  </form>
  <p class="small"><?php echo count($rows); ?> matters loaded from the sheet.</p>
  <table>
    <tr><th>Ref</th><th>Name</th><th>Area of law</th><th>Status</th></tr>
    <?php foreach ($rows as $r) { ?>
    <tr>
      <td><a href="matter.php?id=<?php echo $r['id']; ?>"><?php echo $r['id']; ?></a></td>
      <td><?php echo $r['name']; ?></td>
      <td><?php echo $r['area_of_law']; ?></td>
      <td><?php echo $r['status']; ?></td>
    </tr>
    <?php } ?>
  </table>
</div>
</body></html>
