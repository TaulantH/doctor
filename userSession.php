<?php
session_start();
require_once "db_connect.php";

if (isset($_SESSION["adm"])) {
    header("Location: dashboard.php");
    exit(); 
}
if (!isset($_SESSION["user"]) && !isset($_SESSION["adm"])) {
    header("Location: index.php");
    exit(); 
}

?>
