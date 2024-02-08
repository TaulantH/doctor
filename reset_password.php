<?php
// Include necessary files and initialize session if not already done
include_once "db_connect.php";
require_once "mail.php";
session_start();
if (isset($_POST["reset_request"])) {
    $emailOrUsername = $_POST["email_or_username"];

    // Check if the email/username exists in the database
    $query = "SELECT id, email FROM users WHERE email = '$emailOrUsername' OR username = '$emailOrUsername'";
    $result = mysqli_query($connect, $query);

    if (mysqli_num_rows($result) == 1) {
        // Generate a unique token (you can use a library for this)
        $token = bin2hex(random_bytes(32)); // Example token generation

        // Calculate expiration time (e.g., 1 hour from now)
        $expirationTime = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Fetch the user's email from the database (if a row exists)
        $userRow = mysqli_fetch_assoc($result);
        $to = $userRow["email"];

        // Check if the fetched email is not null
        if ($to !== null) {
            // Store the token and expiration time in the database
            $userId = $userRow["id"];
            $insertTokenQuery = "INSERT INTO password_reset_tokens (user_id, token, expiration_time) VALUES ($userId, '$token', '$expirationTime')";
            mysqli_query($connect, $insertTokenQuery);

            // Send an email with a link to reset the password
            $resetLink = "https://taladeveloper.com/reset_pasword_form.php?token=$token";
            $subject = "Password Reset, you have one hour to reset!"; // Initialize the subject variable
            $message = "Click the link below to reset your password:\n\n$resetLink"; // Initialize the message variable

            // Send the email
            $emailResult = sendMail($to, '', $subject, $message); // Use the sendMail function
              header("Location: login.php");
            exit;
 if ($emailResult === true) {
        // Email sent successfully
        // Store confirmation message in session
           header("Location: login.php");
            exit;
    } else {
        // Email sending failed
        $error = "Email sending failed: $emailResult";
    }

        } else {
            // User's email is null (should not happen)
            $error = "User's email is missing in the database.";

        }
    } else {
        // User not found in the database
        $error = "User not found.";
    }
}
if (isset($_POST["reset_password"])) {
    $token = $_POST["token"];
    $password = $_POST["password"];

    // Validate the token and check if it's still valid (not expired)
    $query = "SELECT user_id, expiration_time FROM password_reset_tokens WHERE token = '$token'";
    $result = mysqli_query($connect, $query);

    if (mysqli_num_rows($result) == 1) {
        $tokenData = mysqli_fetch_assoc($result);
        $expirationTime = strtotime($tokenData["expiration_time"]);

        if (time() < $expirationTime) {
            // Token is valid and not expired
            $userId = $tokenData["user_id"];

            // Hash the new password
       $password = hash("sha256", $password);

            // Update the user's password in the database
            $updatePasswordQuery = "UPDATE users SET password = '$password' WHERE id = $userId";
            mysqli_query($connect, $updatePasswordQuery);

            // Delete the used token
            $deleteTokenQuery = "DELETE FROM password_reset_tokens WHERE token = '$token'";
            mysqli_query($connect, $deleteTokenQuery);

            // Redirect the user to a login page or a success page
            header("Location: login.php");
            exit;
        } else {
            // Token has expired
            $error = "Token has expired.";
        }
    } else {
        // Invalid token
        $error = "Invalid token.";
    }
}
?>

