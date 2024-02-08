<?php
session_start();

// Check session and role if needed

require_once "../db_connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_doctor'])) {
    $name = $_POST['name'];
    $specialization = $_POST['specialization'];

    $sql = "INSERT INTO doctors (name, specialization) VALUES ('$name', '$specialization')";
    $result = mysqli_query($connect, $sql);

    if ($result) {
        header("Location: ../dashboard.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($connect);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title><?= include_once "../brand.php"; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
</head>
<style>
        * {
            padding: 0;
            margin: 0;
            text-decoration: none;
            list-style: none;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: white;
        }
        .container2 {
            background-color: #FFFCF2;
            border-radius: 10px;
            padding: 30px;
            max-width: 500px;
            box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.1);
            margin: 0 auto;
        }
        .container2 h2 {
            text-align: center;
        }
        .form-control {
            background-color: #CCC5B9;
            border: none;
            border-radius: 5px;
        }
        .btn-primary {
            text-align: center;
            background-color: #EB5E28;
            border: none;
            color: #FEFAE0;
            border-radius: 5px;
        }
        .btn-primary:hover {
            background-color: #D4A373;
        }
        .alert {
            background-color: #FAEDCD;
            border: 1px solid #D4A373;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
<body>
    <?php include_once "../components/navbarAdmin.php"; ?>

    <div class="container2">
        <h2>Add Doctor</h2>
        <form method="post" action="">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="specialization" class="form-label">Specialization</label>
                <input type="text" class="form-control" id="specialization" name="specialization" required>
            </div>
            <button type="submit" class="btn btn-primary" name="add_doctor">Add Doctor</button>
        </form>
    </div>
</body>
</html>
