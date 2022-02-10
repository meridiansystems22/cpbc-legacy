<?php
session_start();
if (!isset($_SESSION['user'])) { header('Location: login.php'); exit; }
// R14: flag matters affected by a limitation period. Meant to check the real
// limitation rules per area of law. Returns the same answer for everything.
// P.Menon: "placeholder till we get the real dates in". They never went in.
// A safety-critical feature that looks implemented and does nothing.
echo 'This matter MAY be affected by a limitation period. Please check.';
