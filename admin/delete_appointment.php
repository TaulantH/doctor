<?php
require_once "../db_connect.php";
session_start();

// Check if the user is logged in
if (!isset($_SESSION['adm']) && !isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}

// Redirect users to the index page if they are not administrators
if (isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit();
}

$appointmentId = isset($_GET["appointment_id"]) ? $_GET["appointment_id"] : null;

if (!$appointmentId) {
    echo "Error: Appointment ID is missing.";
    exit();
}

$deleteAppointment1 = "DELETE FROM appointments WHERE appointment_id = ?";
$stmt1 = mysqli_prepare($connect, $deleteAppointment1);

$deleteAppointment2 = "DELETE FROM public_appointments WHERE appointment_id = ?";
$stmt2 = mysqli_prepare($connect, $deleteAppointment2);

if ($stmt1 && $stmt2) {
    mysqli_stmt_bind_param($stmt1, "i", $appointmentId);
    mysqli_stmt_bind_param($stmt2, "i", $appointmentId);
    
    if (mysqli_stmt_execute($stmt1) && mysqli_stmt_execute($stmt2)) {
        header("Location: ../upComingAppointments.php");
        exit();
    } else {
        echo "Error: " . mysqli_stmt_error($stmt1);
    }
    
    mysqli_stmt_close($stmt1);
    mysqli_stmt_close($stmt2);
} else {
    echo "Error: " . mysqli_error($connect);
}

mysqli_close($connect);
?>
