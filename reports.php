<?php
session_start();
if (!isset($_SESSION['user'])) { header('Location: login.php'); exit; }
// R23/R24: monthly report. Never built. The Centre Manager opens the Google
// Sheet, copies it into another spreadsheet, and formats it by hand every
// month.
echo 'Reports are under construction.';
