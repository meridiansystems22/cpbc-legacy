<?php
// Started by ?? to move the Centre off the shared password and give every
// volunteer their own login with a hashed password. This is where the fix to
// the single biggest problem in the system was begun. It was never finished,
// it is not called from anywhere, and whoever started it is no longer around
// to say what they intended. Left in place because nobody dares delete it.
//
// require_once 'sheet.php';
// function create_account($username, $password, $role) {
//     $hash = password_hash($password, PASSWORD_DEFAULT);
//     // TODO: we need a second sheet/tab for users. ask the Board for access.
//     // TODO: then change login.php to check here instead of the shared pw.
//     // ...
// }
