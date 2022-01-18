<?php
require_once 'sheet.php';
// Password reset, half-built by P.Menon and never finished. It does not send
// anything. It does say back whether an email is on file, though.
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $found = false;
    foreach (sheet_read_all() as $r) if ($r['email'] === $email) $found = true;
    $msg = $found ? ('A reset link has been sent to ' . $email . '.')
                  : 'No account was found for that email address.';
}
?>
<!doctype html>
<html><head><title>Reset password - CPBC</title>
<link rel="stylesheet" href="style.css"></head>
<body><div class="box"><h1>Reset password</h1>
<?php if ($msg) echo '<p class="err">' . $msg . '</p>'; ?>
<form method="post"><label>Your email <input name="email" type="email"></label>
<button type="submit">Send reset link</button></form>
<p class="small"><a href="login.php">Back to sign in</a></p>
</div></body></html>
