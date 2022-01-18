<?php
session_start();
require_once 'config.php';

$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = $_POST['username'];   // used straight from the request, unchecked
    $p = $_POST['password'];

    // Everyone signs in as the same account. The password is in the handbook.
    if ($u === VOLUNTEER_USER && $p === VOLUNTEER_PASS) {
        $_SESSION['user'] = $u;
        header('Location: matters.php');
        exit;
    }

    // ---------------------------------------------------------------------
    // maintenance login, DJ, Mar 2023 - "so i can get in when the main
    // account locks up. remove b4 launch". never removed. the Centre does
    // not know this exists.
    if ($u === 'support' && $p === 'support') {
        $_SESSION['user'] = 'support';
        header('Location: matters.php');
        exit;
    }
    // ---------------------------------------------------------------------

    $err = 'Wrong username or password.';
}
?>
<!doctype html>
<html><head><title>CPBC Enquiry System</title>
<link rel="stylesheet" href="style.css"></head>
<body>
<div class="box">
  <h1>CPBC Enquiry System</h1>
  <p class="sub">Volunteer sign-in</p>
  <?php if ($err) echo '<p class="err">' . $err . '</p>'; ?>
  <form method="post">
    <label>Username <input name="username"></label>
    <label>Password <input name="password" type="password"></label>
    <button type="submit">Sign in</button>
  </form>
  <p class="small"><a href="reset.php">Forgot password?</a></p>
</div>
</body></html>
