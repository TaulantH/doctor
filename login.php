<?php
session_start();
require_once "db_connect.php";

if (isset($_SESSION["adm"])) {
    header("Location: dashboard.php");
    exit;
}

if (isset($_SESSION["user"])) {
    header("Location: index.php");
    exit;
}

$identifier = $passError = $emailError = $banError = "";
$error = false;

function cleanInputs($input)
{
    $data = trim($input);
    $data = strip_tags($data);
    $data = htmlspecialchars($data);

    return $data;
}

if (isset($_POST["login"])) {
    $identifier = cleanInputs($_POST["identifier"]);
    $password = $_POST["password"];

    if (!filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
        $emailError = "Please enter a valid email address";
    }

    if (empty($password)) {
        $error = true;
        $passError = "Password can't be empty!";
    }

    $checkBan = "SELECT expiration_time AS exTime, banlist.ID AS ID FROM banlist JOIN users ON banlist.fk_users = users.id WHERE (email = '$identifier' OR username = '$identifier')";
    $banResult = $connect->query($checkBan);
    
    // if(mysqli_num_rows($banResult) == 1){
    //     $rowBan = $banResult->fetch_assoc();
    //     $banID = $rowBan['ID'];

    //     $checktime = "SELECT TIMESTAMPDIFF(MINUTE, NOW(), expiration_time) AS time_difference FROM banlist JOIN users ON banlist.fk_users = users.id WHERE (email = '$identifier' OR username = '$identifier')";
    //     $resultTime = $connect->query($checktime);
    //     $remainTime = $resultTime->fetch_assoc();

    //     if($remainTime['time_difference'] <= 1){
    //         $delBan = "DELETE FROM `banlist` WHERE ID = $banID";
    //         $connect->query($delBan);
    //     }else{
    //         $error = true;
    //         $banError = "Sorry you are banned for {$remainTime['time_difference']} more minutes";
    //     }
      
    // }

    if (!$error) {
        $password = hash("sha256", $password);

        $sql = "SELECT * FROM users WHERE (email = '$identifier' OR username = '$identifier') AND password = '$password'";
        $result = mysqli_query($connect, $sql);

        if (!$result) {
            die('Error: ' . mysqli_error($connect));
        }

        $row = mysqli_fetch_assoc($result);

        if (mysqli_num_rows($result) == 1) {
            if ($row["role"] == "user") {
                $_SESSION["user"] = $row["id"];
                header("Location: index.php");
                exit;
            } else {
                $_SESSION["adm"] = $row["id"];
                header("Location: dashboard.php");
                exit;
            }
        } else {
            echo "<div class='alert alert-danger'>
                      <p>Wrong credentials, please try again ...</p>
                  </div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= include_once "brand.php"; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <style>
        body {
            background-image: url(https://wallpapers.com/images/hd/blue-earth-medical-doctor-k0v1mf9ac0z2fo5b.jpg);
            background-repeat: no-repeat;
            background-size: cover;
            background-color: rgb(255, 255, 255);       
            margin: 0; /* Remove default margin */
            align-items: center; /* Center vertically */
            justify-content: center; /* Center horizontally */
            overflow: hidden;

        }
        section{
            opacity: 0.85;
            color: black;

        }

        .background {
            backdrop-filter: blur(30px);
            background-color: rgb(255, 255, 255);

        }

        .color {
            color: #EB5E28 !important;
        }

        .loginBtn {
            background-color: #EB5E28;
            border: none;
        }

        .card-body {
            height: 600px;
            backdrop-filter: blur(30px);
            border: 1px solid rgba(58, 161, 75, 0.468);
            color: black;
            border-radius: 10px;
            position: relative;
        }
    </style>
</head>

<body>
    <section class="gradient-custom">
        <div class="container">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card background text-black" style="border-radius: 1rem;">
                        <div class="card-body p-2 text-center">

                            <div class="mb-md-5 mt-md-4 pb-5">
                                <form method="post">
                                    <div class="alert alert-info">
                                        This website is for testing purposes.
                                    </div>
                                    <h2 class="fw-bold mb-2 text-uppercase text-white color">Login</h2>
                                    <p class="text-white-50 mb-5">Please enter your login and password!</p>
                                    <span class="text-danger"><?= $banError ?></span>
                                    <div class="form-outline form-white mb-4">
                                        <label for="identifier" class="form-label text-white color">Email address or Username</label>
                                        <input type="text" class="form-control" id="identifier" name="identifier" placeholder="Email address or Username" value="<?= $identifier ?>">
                                        <span class="text-danger"><?= $emailError ?></span>
                                    </div>

                                    <div class="form-outline form-white mb-4">
                                        <label for="password" class="form-label text-white color">Password</label>
                                        <input type="password" class="form-control" id="password" name="password">
                                        <span class="text-danger"><?= $passError ?></span>
                                    </div>

                                    <button class="btn btn-outline-dark btn-lg px-5 mt-3 loginBtn" name="login" type="submit">Log in
                                    </button>

                                    <p class="mt-4 mb-0 text-black">Don't have an account? <a href="registration.php" class="text-black-50 fw-bold color">Sign Up</a></p>
                                    <a href="reset_password_request.php" class="text-black-50 fw-bold color">Forgot</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js" integrity="sha384-Rx+T1VzGupg4BHQYs2gCW9It+akI2MM/mndMCy36UVfodzcJcF0GGLxZIzObiEfa" crossorigin="anonymous"></script>

</body>

</html>