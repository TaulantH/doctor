<?php
   require_once "../db_connect.php" ;
   session_start();
   if(!isset($_SESSION['adm']) && !isset($_SESSION['user'])){
        header("Location: ../login.php");
    }
    if(isset($_SESSION['user'])){
        header("Location: ../index.php");
    }

    $doctor_id = $_GET["doctor_id"];

    $delete = "DELETE FROM doctors WHERE doctor_id = $doctor_id"; // use the correct variable name

    if(mysqli_query($connect, $delete)){
        header( "Location: ../dashboard.php");
    } else {
        echo "Error: " . mysqli_error($connect);
    }
 
    mysqli_close($connect);
?>
