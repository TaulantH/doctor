<?php

    $localhost = "localhost";
    $username = "root";
    $password = "";
    $dbname = "doctor_appointments";

    $connect = mysqli_connect($localhost, $username, $password, $dbname);

    if (!$connect) {
    die ("Connection failed");
    }
