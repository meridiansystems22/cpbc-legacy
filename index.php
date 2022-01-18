<?php
// Apache serves this at "/". The app has no real landing page - the contractor
// just bookmarked login.php - so this sends visitors there. (php -S also uses
// this as the directory index.) Adds no protection and hides nothing.
header('Location: login.php');
