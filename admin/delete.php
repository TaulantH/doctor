<?php
require_once "../db_connect.php";
session_start();

if (!isset($_SESSION['adm']) && !isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}

if (isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit();
}

$doctor_id = $_GET["doctor_id"];

// Set doctor_id to null in appointments table
$updateAppointments = "UPDATE appointments SET doctor_id = 'NULL' WHERE doctor_id = $doctor_id";
if (!mysqli_query($connect, $updateAppointments)) {
    echo "Error updating appointments: " . mysqli_error($connect);
    exit(); 
}

// Set doctor_id to null in public_appointments table
$updatePublicAppointments = "UPDATE public_appointments SET doctor_id = 'NULL' WHERE doctor_id = $doctor_id";
if (!mysqli_query($connect, $updatePublicAppointments)) {
    echo "Error updating public_appointments: " . mysqli_error($connect);
    exit(); 
}

$delete = "DELETE FROM doctors WHERE doctor_id = $doctor_id";

if (mysqli_query($connect, $delete)) {
    header("Location: ../dashboard.php");
    exit();
} else {
    echo "Error: " . mysqli_error($connect);
}

mysqli_close($connect);
?>
