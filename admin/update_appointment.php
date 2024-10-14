<?php
session_start();
require_once "../db_connect.php"; 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['appointment_id']) && isset($_POST['new_date'])) {
    $appointmentId = $_POST['appointment_id'];
    $newDate = $_POST['new_date'];

    $newDate = mysqli_real_escape_string($connect, $newDate);
    $appointmentId = mysqli_real_escape_string($connect, $appointmentId);

    //appointments table update
    $queryAppointments = "UPDATE appointments SET appointment_date = ? WHERE appointment_id = ?";
    $stmt = mysqli_prepare($connect, $queryAppointments);
    if (!$stmt) {
        echo "Error preparing statement for appointments: " . mysqli_error($connect);
        exit;
    }
    mysqli_stmt_bind_param($stmt, "si", $newDate, $appointmentId);
    $successAppointments = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    //public_appointments table update
    $queryPublicAppointments = "UPDATE public_appointments SET appointment_date = ? WHERE appointment_id = ?";
    $stmtPublic = mysqli_prepare($connect, $queryPublicAppointments);
    if (!$stmtPublic) {
        echo "Error preparing statement for public appointments: " . mysqli_error($connect);
        exit;
    }
    mysqli_stmt_bind_param($stmtPublic, "si", $newDate, $appointmentId);
    $successPublicAppointments = mysqli_stmt_execute($stmtPublic);
    mysqli_stmt_close($stmtPublic);

    // Check if both or either update was successful
    if ($successAppointments || $successPublicAppointments) {
        echo "Appointment updated successfully";
    }
    mysqli_close($connect);
}
else {
    echo "Invalid request method or missing data";
}
?>
