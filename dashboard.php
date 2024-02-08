<?php
//check session
session_start();

require_once "db_connect.php";

// Redirect if not logged in
if (!isset($_SESSION['user']) && !isset($_SESSION['adm'])) {
    header("Location: login.php");
    exit();
}

// Query to fetch all doctors
$sql = "SELECT doctor_id, name, specialization FROM doctors";
$result = mysqli_query($connect, $sql);

// Check if a search term is set
$searchTerm = isset($_POST['search']) ? $_POST['search'] : '';

// Modify the query to filter by search term
if (!empty($searchTerm)) {
    // This example searches both name and specialization fields for the search term.
    // Adjust the query according to your needs and ensure proper sanitization to prevent SQL injection.
    $sql = "SELECT doctor_id, name, specialization FROM doctors WHERE name LIKE ? OR specialization LIKE ?";
    $stmt = mysqli_prepare($connect, $sql);
    $searchTerm = "%$searchTerm%";
    mysqli_stmt_bind_param($stmt, "ss", $searchTerm, $searchTerm);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    $sql = "SELECT doctor_id, name, specialization FROM doctors";
    $result = mysqli_query($connect, $sql);
}


if (!$result) {
    echo "Query Error: " . mysqli_error($connect);
    exit();
}
// Daily appointments count for the current week
$dailyAppointmentsSQL = "
SELECT appointmentDay, SUM(count) AS count
FROM (
    SELECT DATE(appointment_date) as appointmentDay, COUNT(*) as count
    FROM appointments
    WHERE YEARWEEK(appointment_date, 1) = YEARWEEK(CURDATE(), 1)
    GROUP BY DATE(appointment_date)
    UNION ALL
    SELECT DATE(appointment_date) as appointmentDay, COUNT(*) as count
    FROM public_appointments
    WHERE YEARWEEK(appointment_date, 1) = YEARWEEK(CURDATE(), 1)
    GROUP BY DATE(appointment_date)
) AS combinedDailyCounts
GROUP BY appointmentDay
ORDER BY appointmentDay ASC";

$resultDailyAppointments = mysqli_query($connect, $dailyAppointmentsSQL);
$dailyCounts = [];
while ($row = mysqli_fetch_assoc($resultDailyAppointments)) {
    $dailyCounts[$row['appointmentDay']] = $row['count'];
}

// Yearly appointments count
$yearlyAppointmentsSQL = "
SELECT year, SUM(count) AS count
FROM (
    SELECT YEAR(appointment_date) as year, COUNT(*) as count
    FROM appointments
    GROUP BY YEAR(appointment_date)
    UNION ALL
    SELECT YEAR(appointment_date) as year, COUNT(*) as count
    FROM public_appointments
    GROUP BY YEAR(appointment_date)
) AS combinedYearlyCounts
GROUP BY year
ORDER BY year ASC";

$resultYearlyAppointments = mysqli_query($connect, $yearlyAppointmentsSQL);
$yearlyCounts = [];
while ($row = mysqli_fetch_assoc($resultYearlyAppointments)) {
    $yearlyCounts[$row['year']] = $row['count'];
}

$sqlDoctorAppointments = "
SELECT doctorName, SUM(appointmentCount) AS appointmentCount
FROM (
    SELECT d.name AS doctorName, COUNT(a.doctor_id) AS appointmentCount
    FROM appointments a
    JOIN doctors d ON a.doctor_id = d.doctor_id
    GROUP BY a.doctor_id
    UNION ALL
    SELECT d.name AS doctorName, COUNT(pa.doctor_id) AS appointmentCount
    FROM public_appointments pa
    JOIN doctors d ON pa.doctor_id = d.doctor_id
    GROUP BY pa.doctor_id
) AS combinedAppointments
GROUP BY doctorName
ORDER BY appointmentCount DESC";

$resultDoctorAppointments = mysqli_query($connect, $sqlDoctorAppointments);

$doctorNames = [];
$appointmentCounts = [];
while ($row = mysqli_fetch_assoc($resultDoctorAppointments)) {
    $doctorNames[] = $row['doctorName'];
    $appointmentCounts[] = $row['appointmentCount'];
}


// Example for daily counts
$labelsDaily = array_keys($dailyCounts);
$dataDaily = array_values($dailyCounts);

// Example for yearly counts
$labelsYearly = array_keys($yearlyCounts);
$dataYearly = array_values($yearlyCounts);


// You can add more queries for weekly or other specific charts as needed

// Count the number of doctors
$sqlDoctorsCount = "SELECT COUNT(*) AS doctorsCount FROM doctors";
$resultDoctorsCount = mysqli_query($connect, $sqlDoctorsCount);
$rowDoctorsCount = mysqli_fetch_assoc($resultDoctorsCount);
$doctorsCount = $rowDoctorsCount['doctorsCount'];

// Count the total number of appointments
$sqlAppointmentsCount = "
SELECT SUM(appointmentsCount) AS appointmentsCount FROM (
    SELECT COUNT(*) AS appointmentsCount FROM appointments
    UNION ALL
    SELECT COUNT(*) AS appointmentsCount FROM public_appointments
) AS combinedCounts";
$resultAppointmentsCount = mysqli_query($connect, $sqlAppointmentsCount);
if ($rowAppointmentsCount = mysqli_fetch_assoc($resultAppointmentsCount)) {
    $appointmentsCount = $rowAppointmentsCount['appointmentsCount'];
} else {
    $appointmentsCount = 0; // Default to 0 if the query fails or returns no rows
}


// Count the number of appointments for today
$today = date('Y-m-d');
$sqlTodayAppointmentsCount = "
SELECT SUM(todayAppointmentsCount) AS todayAppointmentsCount FROM (
    SELECT COUNT(*) AS todayAppointmentsCount FROM appointments WHERE appointment_date = '$today'
    UNION ALL
    SELECT COUNT(*) AS todayAppointmentsCount FROM public_appointments WHERE appointment_date = '$today'
) AS combinedTodayCounts";
$resultTodayAppointmentsCount = mysqli_query($connect, $sqlTodayAppointmentsCount);
if ($rowTodayAppointmentsCount = mysqli_fetch_assoc($resultTodayAppointmentsCount)) {
    $todayAppointmentsCount = $rowTodayAppointmentsCount['todayAppointmentsCount'];
} else {
    $todayAppointmentsCount = 0; // Default to 0 if the query fails or returns no rows
}


// Count the number of upcoming appointments (excluding today)
$upcomingAppointmentsSQL = "
SELECT SUM(upcomingAppointmentsCount) AS upcomingAppointmentsCount FROM (
    SELECT COUNT(*) AS upcomingAppointmentsCount FROM appointments WHERE appointment_date > '$today'
    UNION ALL
    SELECT COUNT(*) AS upcomingAppointmentsCount FROM public_appointments WHERE appointment_date > '$today'
) AS combinedUpcomingCounts";
$resultUpcomingAppointments = mysqli_query($connect, $upcomingAppointmentsSQL);
if ($resultUpcomingAppointments) {
    $rowUpcomingAppointments = mysqli_fetch_assoc($resultUpcomingAppointments);
    $upcomingAppointmentsCount = $rowUpcomingAppointments['upcomingAppointmentsCount'];
} else {
    $upcomingAppointmentsCount = 0; // Default to 0 if query fails
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.17.0/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


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
            background-color: #dfe3f4;
        }

        .container2 {
            padding: 20px 20px;
            background-color:  #dfe3f4;

        }
        .mb-3{
            text-align: center;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px; /* Keep the margin for spacing between h1 and table */
            margin-top: 20px; /* Add top margin to h1 for spacing from navbar */
            color: #333;
        }

        table {
            width: 100%;
            background-color: #fff;
            border-collapse: collapse;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 25px;

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

        /*boxes*/
        .wrapper {
  margin: auto;
  padding: 20;
  width: 100%;
  position: relative;
  box-sizing: inherit;
  box-sizing: border-box;
  margin: 20px 0 20px 0;
  border-radius: 20px;
}

.wrapper:before,
.wrapper:after {
  content: " ";
  display: table;
}

.wrapper:after {
  clear: both;
}

.wrapper .box {
  margin: 13px;
  background-color: #fff;
  position: relative;
  float: left;
  width: 23%;
  height: 150px;
  text-align: center;
  box-sizing: border-box;
  border-radius: 20px;
}

.box .content {
  margin: 10px;
  padding: 10px;
  min-height: 100px;
  box-sizing: border-box;
  text-transform: uppercase;
  font-family: 'Gambetta', serif;
  transition: 700ms ease;
  font-variation-settings: "wght" 311;
  margin-bottom: 0.8rem;
  color: black;
  outline: none;
  text-align: center;
  font-size: 18px;
  font-weight: bold;
  border-radius: 25px;

}
.box .content span{
    font-size: 40px;
}
.box .content:hover{
    font-variation-settings: "wght" 582; 
  letter-spacing: .5px;
}
.flex-container {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
        }
        
        .table-container {
    width: 100%; /* Table takes 60% of the available width */
    margin-right: 20px; /* 20px padding to the right */
}

.graph-container {
    width: 40%; /* Graph area takes 40% of the available width */
}
        
        canvas {
            width: 100%;
            max-width: 400px; /* Adjust based on your preference */
            background-color: #fff;
            padding: 20px;
            margin-top: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 25px;
            height: 300px;
        }
    </style>
</head>


<body>
    <!-- Navbar -->
    <?php include_once "components/navbarAdmin.php"; ?>


    <div class="container2">
    <div class="wrapper">
            <div class="box">
                <div class="content"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-people" viewBox="0 0 16 16">
  <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1zm-7.978-1L7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002-.014.002zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0M6.936 9.28a6 6 0 0 0-1.23-.247A7 7 0 0 0 5 9c-4 0-5 3-5 4q0 1 1 1h4.216A2.24 2.24 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816M4.92 10A5.5 5.5 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0m3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4"/>
</svg> Doctors<br><span><?php echo $doctorsCount; ?></span></div>
            </div>

            <div class="box">
                <div class="content"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-graph-up" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M0 0h1v15h15v1H0zm14.817 3.113a.5.5 0 0 1 .07.704l-4.5 5.5a.5.5 0 0 1-.74.037L7.06 6.767l-3.656 5.027a.5.5 0 0 1-.808-.588l4-5.5a.5.5 0 0 1 .758-.06l2.609 2.61 4.15-5.073a.5.5 0 0 1 .704-.07"/>
</svg> Total Appointments<br>
                <span><?php echo $appointmentsCount; ?></div></span>
            </div>
            
            <div class="box">
                <div class="content"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-graph-up" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M0 0h1v15h15v1H0zm14.817 3.113a.5.5 0 0 1 .07.704l-4.5 5.5a.5.5 0 0 1-.74.037L7.06 6.767l-3.656 5.027a.5.5 0 0 1-.808-.588l4-5.5a.5.5 0 0 1 .758-.06l2.609 2.61 4.15-5.073a.5.5 0 0 1 .704-.07"/>
</svg> Upcoming Appointments<br><span><?php echo $upcomingAppointmentsCount; ?></span></div>
            </div>

            <div class="box">
                <div class="content"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-clipboard2-data-fill" viewBox="0 0 16 16">
  <path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5"/>
  <path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585q.084.236.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5q.001-.264.085-.5M10 7a1 1 0 1 1 2 0v5a1 1 0 1 1-2 0zm-6 4a1 1 0 1 1 2 0v1a1 1 0 1 1-2 0zm4-3a1 1 0 0 1 1 1v3a1 1 0 1 1-2 0V9a1 1 0 0 1 1-1"/>
</svg> Appointments for Today<br><span><?php echo $todayAppointmentsCount; ?></span></div>
            </div>

        </div>
        <div class="flex-container">
            <div class="table-container">
        <h1>Doctor List</h1>
        <div class="mb-3">
            <form action="" method="POST" class="mb-4">
        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Search by name or specialization" name="search" aria-label="Search">
            <button class="btn btn-outline-secondary" type="submit">Search</button>
            <a href="admin/create.php" class="btn btn-primary">
                <i class="bi bi-person-plus"></i> Add Doctor
            </a>
        </div>
    </form>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Specialization</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Loop through user records and display them
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>{$row['doctor_id']}</td>";
                    echo "<td>{$row['name']}</td>";
                    echo "<td>{$row['specialization']}</td>";
                    echo "<td>
                    <a href='upComingAppointments.php?doctor_id={$row['doctor_id']}&source=dashboard' class='btn btn-success'>
                    <i class='bi bi-clock'></i> Upcoming Appointments
                </a>
                                <a href='admin/update.php?doctor_id={$row['doctor_id']}' class='btn btn-warning'><i class='bi bi-pencil'></i> Update</a>
            <a href='admin/delete.php?doctor_id={$row['doctor_id']}' class='btn btn-danger'><i class='bi bi-trash'></i> Delete</a>

            </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <div class="graph-container">
    <canvas id="doctorAppointmentChart"></canvas>

    <canvas id="dailyChart"></canvas>
<canvas id="yearlyChart"></canvas>
            </div>
        </div>
    </div>
    <script>
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Doctors', 'Appointments', 'Appointments for Today'],
            datasets: [{
                label: 'Count',
                data: [<?php echo $doctorsCount; ?>, <?php echo $appointmentsCount; ?>, <?php echo $todayAppointmentsCount; ?>],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });


</script>
<script>
    // Daily Chart
    var ctxDaily = document.getElementById('dailyChart').getContext('2d');
    var dailyChart = new Chart(ctxDaily, {
        type: 'line', // Use a line chart for daily counts
        data: {
            labels: <?php echo json_encode($labelsDaily); ?>,
            datasets: [{
                label: 'Daily Appointments',
                data: <?php echo json_encode($dataDaily); ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Yearly Chart
    var ctxYearly = document.getElementById('yearlyChart').getContext('2d');
    var yearlyChart = new Chart(ctxYearly, {
        type: 'bar', // Use a bar chart for yearly counts
        data: {
            labels: <?php echo json_encode($labelsYearly); ?>,
            datasets: [{
                label: 'Yearly Appointments',
                data: <?php echo json_encode($dataYearly); ?>,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
<script>
var doctorNames = <?php echo json_encode($doctorNames); ?>;
var appointmentCounts = <?php echo json_encode($appointmentCounts); ?>;
</script><script>
var ctx = document.getElementById('doctorAppointmentChart').getContext('2d');
var doctorAppointmentChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: doctorNames,
        datasets: [{
            label: 'Number of Appointments for Doctor',
            data: appointmentCounts,
            backgroundColor: 'rgba(153, 102, 255, 0.2)',
            borderColor: 'rgba(153, 102, 255, 1)',
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
</body>

</html>