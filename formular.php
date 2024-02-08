<?php
require_once "db_connect.php";
require_once "userSession.php";

// Check for database connection error
if (!$connect) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the form
    $patientName = $_POST["patient_name"];
    $appointmentDate = $_POST["appointment_date"];
    $doctorId = $_POST["doctor_id"];

    // Insert the data into the appointments table
    $sqlInsertAppointment = "INSERT INTO appointments (patient_name, appointment_date, doctor_id) VALUES (?, ?, ?)";
    $stmtInsertAppointment = mysqli_prepare($connect, $sqlInsertAppointment);
    mysqli_stmt_bind_param($stmtInsertAppointment, "sss", $patientName, $appointmentDate, $doctorId);

    if (mysqli_stmt_execute($stmtInsertAppointment)) {
        // Appointment added successfully
        echo "<p class='text-success'>Appointment added successfully!</p>";
    } else {
        // Appointment failed to add
        echo "<p class='text-danger'>Error adding appointment: " . mysqli_error($connect) . "</p>";
    }
}

// Retrieve doctors for the dropdown
$sqlDoctors = "SELECT doctor_id, name FROM doctors";
$resultDoctors = mysqli_query($connect, $sqlDoctors);

// Check for query error
if (!$resultDoctors) {
    die("Query failed: " . mysqli_error($connect));
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= require_once "brand.php"; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <style>
        *{
            padding: 0;
            margin: 0;
        }
        a{
            text-decoration: none;
        }
        .checkBtn{
            margin-top: 25px;
        }
        body{
            background-color: #FFFCF2;
        }
   
        .durchgestrichen{
            text-decoration: line-through;
        }
        .detailsBtn{
            width: 100%;
            color: white;
            background-color: black;
            border: none;
            height:2rem;
            
        }/* Style for the search form container */
.searchDiv {
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 20px 0;
}

/* Style for the input group (Bootstrap class) */
.input-group {
    max-width: 300px; /* Adjust the width as needed */
}

/* Style for the input field */
.form-control {
    border-radius: 20px; /* Rounded corners for the input field */
}

/* Style for the search button */
.btn-dark {
    border-radius: 20px; /* Rounded corners for the button */
    background-color: #343a40; /* Dark background color */
    color: #fff; /* Text color */
}

/* Hover effect for the search button */
.btn-dark:hover {
    background-color: #23272b; /* Darken the background color on hover */
}

        
    </style>
</head>
    <?php include_once "components/navbar.php"
    ?>

    <?php include_once "components/hero.php"
    ?>

    <h2 class='text-center mt-5' style='font-family: Georgia, Times, serif'>Formular</h2>
    <div class="container text-center">
        <!-- Add Appointment Form -->
        <h2 class="text-center mt-5" style="font-family: Georgia, Times, serif">Add Appointment</h2>
        <form action="" method="POST" class="row g-2 g-lg-3">
            <div class="col-md-4">
                <label for="patient_name" class="form-label">Patient Name</label>
                <input type="text" class="form-control" id="patient_name" name="patient_name" required>
            </div>
            <div class="col-md-4">
                <label for="appointment_date" class="form-label">Appointment Date</label>
                <input type="date" class="form-control" id="appointment_date" name="appointment_date" required>
            </div>
            <div class="col-md-4">
                <label for="doctor_id" class="form-label">Doctor</label>
                <select class="form-select" id="doctor_id" name="doctor_id" required>
                    <option value="" selected disabled>Select Doctor</option>
                    <?php
                    while ($rowDoctor = mysqli_fetch_assoc($resultDoctors)) {
                        echo "<option value='{$rowDoctor['doctor_id']}'>{$rowDoctor['name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-12 mt-3">
                <button type="submit" class="btn btn-primary">Add Appointment</button>
            </div>
        </form>
    </div>
    <?php include_once "components/footer.php"
    ?>
    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'
        integrity='sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz' crossorigin='anonymous'></script>
         <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>

</body>
</html>
