<?php

    $localhost = "localhost";
    $username = "root";
    $password = "";
    $dbname = "doctor_appointments";

    // create connection
    $connect = mysqli_connect($localhost, $username, $password, $dbname);

    // check connection
    if (!$connect) {
    die ("Connection failed");
    }
