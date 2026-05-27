<?php
session_start();

/* Remove all session variables */
$_SESSION = [];

/* Destroy session */
session_destroy();

/* Redirect user */
header("Location: index.php");
exit;
?>
