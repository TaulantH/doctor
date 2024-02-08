<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Include PHPMailer autoload file

// Create a new PHPMailer instance
$mail = new PHPMailer();

// SMTP configuration (replace with your SMTP settings)
$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
$mail->isSMTP();                                            //Send using SMTP
$mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
$mail->Username   = 'htaulant0@gmail.com';                     //SMTP username
$mail->Password   = 'hyvcfiptogxgbzhj';                               //SMTP password
$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
$mail->Port = 587;

// Sender and recipient settings
$mail->setFrom('hospital@example.com', 'Hospital');
$mail->addAddress($userEmail, $userName); // $userEmail is the recipient's email address, $userName is the recipient's name

// Email subject and body
$mail->Subject = 'Appointment Confirmation';
$mail->Body = 'Dear ' . $userName . ', Your appointment has been confirmed.';

// Send the email
if ($mail->send()) {
    echo 'Email sent successfully!';
} else {
    echo 'Error sending email: ' . $mail->ErrorInfo;
}
?>
