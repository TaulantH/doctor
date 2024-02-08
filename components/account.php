<?php
session_start();
require_once "db_connect.php";

// Retrieve user data from the database based on the user's ID stored in the session
$userID = $_SESSION["user"];
$userQuery = "SELECT * FROM users WHERE id = ?";
$stmt = mysqli_prepare($connect, $userQuery);
mysqli_stmt_bind_param($stmt, "i", $userID);
mysqli_stmt_execute($stmt);
$userResult = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($userResult);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .profile-card {
            max-width: 400px;
            margin: auto;
            margin-top: 50px;
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
        }
        .profile-picture {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 20px;
            display: block;
        }
    </style>
</head>
<body>
    <div class="profile-card">
        <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-profiles/avatar-1.webp" alt="Profile Picture" class="profile-picture">
        <h4 class="text-center"><?= $user['fname'] . ' ' . $user['lname'] ?></h4>
        <p class="text-center"><?= $user['email'] ?></p>
        <hr>
        <p class="text-center"><?= $user['bio'] ?></p>
    </div>
</body>
</html>
