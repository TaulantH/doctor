<?php
session_start();

require_once "../db_connect.php";
require_once "../userSession.php";
require_once "../file_upload.php";

function cleanInput($input)
{
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}

if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $sql = "SELECT * FROM users WHERE id = $id";
    $result = mysqli_query($connect, $sql);

    if (!$result) {
        echo "Query Error: " . mysqli_error($connect);
        exit();
    }

    $row = mysqli_fetch_assoc($result);
} else {
    echo "User ID not provided.";
    exit();
}

$backBtn = "index.php";

if (isset($_SESSION["adm"])) {
    $backBtn = "dashboard.php";
}

if (isset($_POST["sign-up"])) {
    $fname = cleanInput($_POST["first_name"]);
    $lname = cleanInput($_POST["last_name"]);
    $email = cleanInput($_POST["email"]);
    $username = cleanInput($_POST["username"]);
    $date_of_birth = cleanInput($_POST["date_of_birth"]);
    $gender = cleanInput($_POST["gender"]);
    $phone_number = $_POST["phone_number"];
    $picture = fileUpload($_FILES["picture"]);

    if ($picture !== null && $_FILES["picture"]["error"] == 0) {
        if ($row["picture"] != "avatar.png") {
            unlink("pictures/{$row["picture"]}");
        }
        $sql = "UPDATE users SET fname = '$fname', lname = '$lname', username = '$username', email = '$email', date_of_birth = '$date_of_birth', phone_number = '$phone_number', gender = '$gender', picture = '$picture[0]' WHERE id = {$id}";
    } else {
        $sql = "UPDATE users SET fname = '$fname', lname = '$lname', username = '$username', email = '$email', date_of_birth = '$date_of_birth', phone_number = '$phone_number', gender = '$gender' WHERE id = {$id}";
    }

    if (mysqli_query($connect, $sql)) {
        echo "<div class='alert alert-success' role='alert'>
            User has been updated, {$picture[1]}
        </div>";
        header("refresh: 3; url=$backBtn");
    } else {
        echo "<div class='alert alert-danger' role='alert'>
            Error found, {$picture[1]}
        </div>";
    }
} elseif (isset($_POST["delete-account"])) {
    // Handle account deletion here
    $deleteQuery = "DELETE FROM users WHERE id = ?";
    $deleteStatement = mysqli_prepare($connect, $deleteQuery);
    mysqli_stmt_bind_param($deleteStatement, "i", $id);

    if (mysqli_stmt_execute($deleteStatement)) {
        // If the deletion is successful, log the user out and redirect to deleted.php
        session_destroy(); // Destroy the session
        header("refresh: 2; url=login.php");
        echo "<div class='alert alert-danger' role='alert'>
        Your account is delete
    </div>"; // Redirect to a confirmation or logout page
        exit();
    } else {
        echo "<div class='alert alert-danger' role='alert'>
            Error deleting account: " . mysqli_error($connect) . "
        </div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= include_once "brand.php"; ?></title>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <style>
        body{
            background-color: #FFFCF2;
        }
        .updateBtn{
            width: 100%;
            max-width: 350px;
            float: right;
            background-color: #EB5E28;
            border:none;
            border-radius: 2px;
            height: 2rem;
            color: #FFFCF2;
        }
        .updateBtn:hover{
            background-color: rgba(235, 94, 40, 0.7);
            transition: 0.5s;
        }
        .deleteBtn{
            width: 100%;
            max-width: 350px;
            float: right;
            background-color: red;
            border:none;
            border-radius: 2px;
            height: 2rem;
            color: #FFFCF2;
        } 
        .backBtn{
            width: 100%;
            max-width: 150px;
            float: right;
            background-color: black;
            border:none;
            border-radius: 2px;
            height: 2rem;
            color: #FFFCF2;
            text-decoration: none;
            padding: 10px 20px;
            display: flex; 
            align-items: center; 
            justify-content: center;
            margin-top: 20px;
        }
        
    </style>
</head>
<body>
    <?php include_once "components/navbar.php"
        ?>
    <div class="container">
        <h1 class="text-center" style="color: white;">Edit profile </h1>

        <section class="vh-100 gradient-custom">
            <div class="container py-3 " style="opacity: 0.9;">
                <div class="row justify-content-center align-items-center h-100">
                    <div class="col-12 col-lg-9 col-xl-7">
                        <div class="card shadow-2-strong card-registration" style="border-radius: 15px;">
                            <div class="card-body p-md-4">
                                <h3 class="mb-0 pb-2 pb-md-0 mb-md-2">Update your information</h3>
                                <form method="post" autocomplete="off" enctype="multipart/form-data">
                                    <div class="mb-3 mt-3">
                                        <label for="first_name" class="form-label">First name</label>
                                        <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First name" value="<?= $row["fname"] ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="last_name" class="form-label">Last name</label>
                                        <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last name" value="<?= $row["lname"] ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" class="form-control" id="username" name="username" placeholder="Username" value="<?= $row["username"] ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="date" class="form-label">Date of birth </label>
                                        <input type="date" class="form-control" id="date" name="date_of_birth" value="<?= $row["date_of_birth"] ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="picture" class="form-label">Profile picture </label>
                                        <input type="file" class="form-control" id="picture" name="picture">
                                    </div>
                                    <div class="col-md-6 mb-4">

                                        <h6 class="mb-2 pb-1">Gender: </h6>

                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="gender" id="femaleGender" value="female" checked />
                                            <label class="form-check-label" for="femaleGender">Female</label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="gender" id="maleGender" value="male" />
                                            <label class="form-check-label" for="maleGender">Male</label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="gender" id="otherGender" value="other" />
                                            <label class="form-check-label" for="otherGender">Other</label>
                                        </div>

                                    </div>
                                    <div class="form-outline">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email address </label>
                                            <input type="email" class="form-control" id="email" name="email" placeholder="Email address" value="<?= $row["email"] ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="phone_number" class="form-label">Phone number</label>
                                            <input type="phone_number" class="form-control" id="phone_number" name="phone_number" placeholder="Phone number" value="<?= $row["phone_number"] ?>">
                                        </div>

                                        <div class="d-flex flex-column-reverse flex-sm-row justify-content-md-between align-items-center">
                                            
                                                <a href="<?= $backBtn ?>" class="backBtn sm">Back</a>
                                            
                                            <div>
                                                <button name="sign-up" type="submit" class="updateBtn my-3">Update profile</button>
                                                <button type="submit" name="delete-account"href="delete.php" class="deleteBtn"  onclick='return confirmDelete()'>Delete My Account</button>
                                            </div>
                                              
                                        </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete your account?");
        }
    </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    </div>
</body>

</html>