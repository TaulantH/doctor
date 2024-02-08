<?php
session_start();
require_once "db_connect.php";

// Check if the doctor_id is set in the URL
$doctorId = isset($_GET['doctor_id']) ? $_GET['doctor_id'] : null;
$isDashboard = isset($_GET['source']) && $_GET['source'] === 'dashboard';

// Initialize doctorName variable
$doctorName = "";

// Determine the source (dashboard or navbar)
$isDashboard = isset($_GET['source']) && $_GET['source'] === 'dashboard';

// Query the database for upcoming appointments
if ($isDashboard) {
    // Show appointments only for the specified doctor with doctor's name
    $sqlAppointments = "
    SELECT patient_name, appointment_date FROM (
        SELECT patient_name, appointment_date
        FROM appointments
        WHERE doctor_id = $doctorId AND appointment_date >= CURDATE()
        UNION ALL
        SELECT patient_name, appointment_date
        FROM public_appointments
        WHERE doctor_id = $doctorId AND appointment_date >= CURDATE()
    ) AS combinedAppointments
    ORDER BY appointment_date";
    
    // Retrieve the doctor's name from the database or another source
    // Replace 'your_doctor_table' with the actual table name storing doctor information
    $sqlDoctor = "SELECT name FROM doctors WHERE doctor_id = $doctorId";
    $resultDoctor = mysqli_query($connect, $sqlDoctor);

    if ($resultDoctor && mysqli_num_rows($resultDoctor) > 0) {
        $doctorData = mysqli_fetch_assoc($resultDoctor);
        $doctorName = $doctorData['name'];
    } else {
        $doctorName = "Unknown Doctor"; // Default value if doctor name is not found
    }
} else {
    // Show all appointments for all doctors with doctor's name
$sqlAppointments = "
    (SELECT a.patient_name, a.appointment_date, d.name AS doctor_name
     FROM appointments a
     LEFT JOIN doctors d ON a.doctor_id = d.doctor_id
     WHERE a.appointment_date >= CURDATE())
    UNION ALL
    (SELECT pa.patient_name, pa.appointment_date, pd.name AS doctor_name
     FROM public_appointments pa
     LEFT JOIN doctors pd ON pa.doctor_id = pd.doctor_id
     WHERE pa.appointment_date >= CURDATE())
    ORDER BY appointment_date
";


    $doctorName = "All Doctors"; // Default value for all doctors
}

$_SESSION['doctor_name'] = $doctorName; // Store doctor's name in session variable

$resultAppointments = mysqli_query($connect, $sqlAppointments);

if (!$resultAppointments) {
    echo "Query Error: " . mysqli_error($connect);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Add your head content here -->
    <title>Upcoming Appointments</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.17.0/font/bootstrap-icons.css" rel="stylesheet">

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
            margin:20px ; /* Remove bottom margin */
        }

        table {
            width: 100%;
            background-color: #fff;
            border-collapse: collapse;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #333;
            color: #fff;
        }

        tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tbody tr:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>
<?php include_once "components/navbarAdmin.php"; ?>
<div class="container2">
   
 <!-- Display doctor's name based on the context -->
 <?php if ($isDashboard): ?>
    <h1>Upcoming appointments for doctor <?php echo $_SESSION['doctor_name']; ?></h1>
    <table>
        <thead>
            <tr>
                <th>Patient Name</th>
                <th>Appointment Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($resultAppointments && mysqli_num_rows($resultAppointments) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($resultAppointments)): ?>
                    <tr>
                        <td><?php echo $row['patient_name']; ?></td>
                        <td><?php echo $row['appointment_date']; ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan='2'>No upcoming appointments found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
 <?php else: ?>
    <h1>Upcoming appointments for <?php echo $_SESSION['doctor_name']; ?></h1>
    <table>
        <thead>
            <tr>
                <th>Doctor Name</th>
                <th>Patient Name</th>
                <th>Appointment Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($resultAppointments && mysqli_num_rows($resultAppointments) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($resultAppointments)): ?>
                    <tr>
                        <td><?php echo $row['doctor_name']; ?></td>
                        <td><?php echo $row['patient_name']; ?></td>
                        <td><?php echo $row['appointment_date']; ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan='3'>No upcoming appointments found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
 <?php endif; ?>
</div>
</body>
</html>
