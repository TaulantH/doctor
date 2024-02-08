<?php
    require_once "../db_connect.php";
    session_start();

    // Check if the user is logged in
    if(!isset($_SESSION['adm']) && !isset($_SESSION['user'])){
        header("Location: ../login.php");
    }

    // Redirect users to the index page if they are not administrators
    if(isset($_SESSION['user'])){
        header("Location: ../index.php");
    }

    // Check if the appointment_id is set in the URL
   // Check if the appointment_id is set in the URL
$appointmentId = isset($_GET["appointment_id"]) ? $_GET["appointment_id"] : null;

// Check if appointment_id is not provided
if (!$appointmentId) {
    echo "Error: Appointment ID is missing.";
    exit();
}

// Delete the appointment using prepared statement
$deleteAppointment = "DELETE FROM appointments WHERE appointment_id = ?";
$stmt = mysqli_prepare($connect, $deleteAppointment);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $appointmentId);
    if (mysqli_stmt_execute($stmt)) {
        header("Location: ../dashboard.php");
    } else {
        echo "Error: " . mysqli_stmt_error($stmt);
    }
    mysqli_stmt_close($stmt);
} else {
    echo "Error: " . mysqli_error($connect);
}

mysqli_close($connect);

?>
