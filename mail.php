<?php
// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Assuming Composer's autoload

// Create a new PHPMailer instance
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_OFF; // Disable verbose debug output in production
    $mail->isSMTP(); // Send using SMTP
    $mail->Host       = 'smtp.gmail.com'; // Set the SMTP server
    $mail->SMTPAuth   = true; // Enable SMTP authentication
    $mail->Username   = 'htaulant0@gmail.com'; // SMTP username (Your Gmail address)
    $mail->Password   = 'hyvcfiptogxgbzhj'; // SMTP password (Your Gmail app password)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = 587; // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS`

    // Sender and recipient settings
    $mail->setFrom('your-email@example.com', 'Clinic Name');
    $mail->addAddress($email, $patientName); // Use the email and name from the form

    // Email subject and body
    $mail->Subject = 'Appointment Confirmation';
    $mail->Body    = "Dear $patientName, your appointment on $appointmentDate has been confirmed.";

    $mail->send();
    echo "<p class='text-success'>Confirmation email sent successfully.</p>";
} catch (Exception $e) {
    echo "<p class='text-danger'>Error sending email: {$mail->ErrorInfo}</p>";
}
?>

