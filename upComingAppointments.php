<?php
session_start();
require_once "db_connect.php";

$doctorId = isset($_GET['doctor_id']) ? $_GET['doctor_id'] : null;
$isDashboard = isset($_GET['source']) && $_GET['source'] === 'dashboard';

$doctorName = "";

$isDashboard = isset($_GET['source']) && $_GET['source'] === 'dashboard';
$showPastAppointments = $_SESSION['showPastAppointments'] ?? false;

if (isset($_POST['toggleAppointments'])) {
    $showPastAppointments = $_POST['toggleAppointments'] == '1';
    $_SESSION['showPastAppointments'] = $showPastAppointments;
}

if ($isDashboard) {
 
    $sqlAppointments = "
SELECT appointment_id, patient_name, appointment_date, appointment_time FROM (
    SELECT appointment_id, patient_name, appointment_date, appointment_time
    FROM appointments
    WHERE doctor_id = $doctorId AND appointment_date >= CURDATE()
    UNION ALL
    SELECT appointment_id, patient_name, appointment_date, appointment_time
    FROM public_appointments
    WHERE doctor_id = $doctorId AND appointment_date >= CURDATE()
) AS combinedAppointments
ORDER BY appointment_date";


   
    $sqlDoctor = "SELECT name FROM doctors WHERE doctor_id = $doctorId";
    $resultDoctor = mysqli_query($connect, $sqlDoctor);

    if ($resultDoctor && mysqli_num_rows($resultDoctor) > 0) {
        $doctorData = mysqli_fetch_assoc($resultDoctor);
        $doctorName = $doctorData['name'];
    } else {
        $doctorName = "Unknown Doctor"; // Default value if doctor name is not found
    }
} elseif ($showPastAppointments) {
    $sqlAppointments = "
    SELECT appointment_id, patient_name, appointment_date, appointment_time, doctor_name
    FROM (
        SELECT a.appointment_id, a.patient_name, a.appointment_date, a.appointment_time, d.name AS doctor_name
        FROM appointments a
        LEFT JOIN doctors d ON a.doctor_id = d.doctor_id
        WHERE a.appointment_date < CURDATE()
        UNION ALL
        SELECT pa.appointment_id, pa.patient_name, pa.appointment_date, pa.appointment_time, pd.name AS doctor_name
        FROM public_appointments pa
        LEFT JOIN doctors pd ON pa.doctor_id = pd.doctor_id
        WHERE pa.appointment_date < CURDATE()
    ) AS combinedAppointments
    ORDER BY appointment_date DESC";
    $doctorName = "the past";
} else {
    // Show all appointments for all doctors with doctor's name
    $sqlAppointments = "
    (SELECT a.appointment_id, a.patient_name, a.appointment_date, a.appointment_time, d.name AS doctor_name
     FROM appointments a
     LEFT JOIN doctors d ON a.doctor_id = d.doctor_id
     WHERE a.appointment_date >= CURDATE())
    UNION ALL
    (SELECT pa.appointment_id, pa.patient_name, pa.appointment_date, pa.appointment_time, pd.name AS doctor_name
     FROM public_appointments pa
     LEFT JOIN doctors pd ON pa.doctor_id = pd.doctor_id
     WHERE pa.appointment_date >= CURDATE())
    ORDER BY appointment_date
    ";

    $doctorName = "All Doctors"; // Default value for all doctors
}
$appointments = [];
$_SESSION['doctor_name'] = $doctorName; 

$resultAppointments = mysqli_query($connect, $sqlAppointments);

if (!$resultAppointments) {
    echo "Query Error: " . mysqli_error($connect);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Upcoming Appointments</title>
    <script src="//code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />


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
            margin: 20px;
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

        @media (max-width: 650px) {

            .container2 {
                margin: 0px;
                /* Remove bottom margin */
                font-size: 12px;
            }

            .btn i.fas {
                font-size: 10px;
                /* Increase icon size */
            }

            th,
            td {
                padding: 0px;
                margin: 0px;
                font-size: 10px;
                text-align: center;
                border-bottom: 1px solid #ddd;
            }

        }
    </style>
</head>

<body>
    <?php include_once "components/navbarAdmin.php"; ?>
    <div class="container2">

        <?php if ($isDashboard) : ?>
            <h1>Upcoming appointments for doctor <?php echo $_SESSION['doctor_name']; ?></h1>
            <table>
                <thead>
                    <tr>
                        <th>Patient Name</th>
                        <th>Appointment Date</th>
                        <th>Appointment Time</th>
                        <th>Action</th>

                    </tr>
                </thead>
                <tbody>
                    <?php if ($resultAppointments && mysqli_num_rows($resultAppointments) > 0) : ?>
                        <?php while ($row = mysqli_fetch_assoc($resultAppointments)) : ?>
                            <tr>
                                <td><?php echo $row['patient_name']; ?></td>
                                <td><?php echo $row['appointment_date']; ?></td>
                                <td><?php echo isset($row['appointment_time']) ? $row['appointment_time'] : 'Time not specified'; ?></td>
                                <td>
                                    <input type="text" id="newDate<?php echo $row['appointment_id']; ?>" class="datepicker" data-appointment-id="<?php echo $row['appointment_id']; ?>" style="display:none;">


                                    <button class="btn btn-warning" onclick="confirmAndPromptDatePicker(<?php echo $row['appointment_id']; ?>)"><i class='fas fa-calendar'></i> <span class='d-none d-lg-inline'>Change Date</span></button>
                                    <a href="admin/delete_appointment.php?appointment_id=<?php echo $row['appointment_id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this appointment?')"><i class='fas fa-trash'></i> <span class='d-none d-lg-inline'>Cancel</span></a>
                                </td>
                            </tr>
                        <?php endwhile; ?>

                    <?php else : ?>
                        <tr>
                            <td colspan='2'>No upcoming appointments found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        <?php else : ?>
            <form action="" method="POST">
                <input type="hidden" name="toggleAppointments" value="<?= $showPastAppointments ? '0' : '1'; ?>">
                <button type="submit" class="btn btn-primary">
                    <?= $showPastAppointments ? 'Show Upcoming Appointments' : 'Show Past Appointments'; ?>
                </button>
            </form>
            <h1>Upcoming appointments for <?php echo $_SESSION['doctor_name']; ?></h1>
            <table>
                <thead>
                    <tr>
                        <th>Doctor Name</th>
                        <th>Patient Name</th>
                        <th>Appointment Date</th>
                        <th>Appointment Time</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($resultAppointments && mysqli_num_rows($resultAppointments) > 0) : ?>
                        <?php while ($row = mysqli_fetch_assoc($resultAppointments)) : ?>
                            <tr>
                                <td><?php echo $row['doctor_name']; ?></td>
                                <td><?php echo $row['patient_name']; ?></td>
                                <td><?php echo $row['appointment_date']; ?></td>
                                <td><?php echo $row['appointment_time']; ?></td>
                                <td>
                                    <input type="text" id="newDate<?php echo $row['appointment_id']; ?>" class="datepicker" data-appointment-id="<?php echo $row['appointment_id']; ?>" style="display:none;">

                                    <button class="btn btn-warning" onclick="confirmAndPromptDatePicker(<?php echo $row['appointment_id']; ?>)"><i class='fas fa-calendar'></i> <span class='d-none d-lg-inline'>Change Date</span></button>
                                    <a href="admin/delete_appointment.php?appointment_id=<?php echo $row['appointment_id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this appointment?')"><i class='fas fa-trash'></i> <span class='d-none d-lg-inline'>Cancel</span></a>
                                </td>
                            </tr>
                        <?php endwhile; ?>

                    <?php else : ?>
                        <tr>
                            <td colspan='3'>No upcoming appointments found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>

<script>
        $(document).ready(function() {
            $(".datepicker").datepicker({
                dateFormat: "yy-mm-dd",
                onSelect: function(dateText, inst) {
                    const appointmentId = $(this).attr('data-appointment-id');
                    updateAppointmentDate(appointmentId, dateText);
                }
            });
        });

        function promptDatePicker(appointmentId) {
            $("#newDate" + appointmentId).datepicker("show");
        }

        function confirmAndPromptDatePicker(appointmentId) {
            if (confirm('Are you sure you want to change the date?')) {
                promptDatePicker(appointmentId);
            }
        }

        function updateAppointmentDate(appointmentId, newDate) {
            console.log("Updating appointment ID " + appointmentId + " to new date " + newDate); 
            $.ajax({
                url: 'admin/update_appointment.php', 
                type: 'POST',
                data: {
                    'appointment_id': appointmentId,
                    'new_date': newDate
                },
                success: function(response) {
                    console.log('Success response:', response); 
                    alert('Appointment date updated successfully.');
                    location.reload(); 
                },
                error: function(xhr, status, error) {
                    console.log('AJAX Error:', xhr, status, error); 
                    alert('Error updating appointment date. Please check the console for more details.');
                }
            });
        }
    </script>


</html>