<?php
session_start();
require_once "db_connect.php";

// Check if the user is an administrator
if (isset($_SESSION["adm"])) {
    header("Location: dashboard.php");
    exit(); // Stop further execution
}

// Check if the user is not logged in
if (!isset($_SESSION["user"]) && !isset($_SESSION["adm"])) {
    // Redirect to a login page or display a message prompting the user to log in
    header("Location: index.php");
    exit(); // Stop further execution
}

// If none of the conditions were met, continue with the rest of your code or display the index page content
?>
