<?php

//Initializing Session
session_start();

$_SESSION = array();

session_destroy();

// Redirect to login page
header("location: login.php");
exit;
?>