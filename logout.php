<?php
require_once 'middleware.php';
Middleware::handleAuth();

session_start();
session_destroy();
header("location: login.php");
exit();
?>