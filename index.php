<?php
require_once "db_connect.php";
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
if (isset($_SESSION['message'])) {
    echo $_SESSION['message'];
    unset($_SESSION['message']); // Clear the message to prevent it from being displayed on refresh
};
if (!$connect) {
    die("Database connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patientName = $_POST["patient_name"];
    $appointmentDate = $_POST["appointment_date"];
    $doctorId = $_POST["doctor_id"];
    // Fields for non-logged-in users
    $birthday = $_POST["birthday"] ?? NULL;
    $phoneNumber = $_POST["phone_number"] ?? NULL;
    $email = $_POST["email"] ?? NULL;
    $gender = $_POST["gender"] ?? NULL;

    if (isset($_SESSION["user"])) {
        $userId = $_SESSION["user"];
        $sqlUser = "SELECT email FROM users WHERE id = ?";
        $stmtUser = mysqli_prepare($connect, $sqlUser);
        mysqli_stmt_bind_param($stmtUser, "i", $userId);
        mysqli_stmt_execute($stmtUser);
        $resultUser = mysqli_stmt_get_result($stmtUser);
        if ($rowUser = mysqli_fetch_assoc($resultUser)) {
            $email = $rowUser['email'];
        } else {
            // Handle error - user not found
            $_SESSION['message'] = "<div class='alert alert-danger' role='alert'>User not found.</div>";
            header('Location: index.php');
            exit();
        }

        $sql = "INSERT INTO appointments (patient_name, appointment_date, doctor_id, appointment_time) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($connect, $sql);
        mysqli_stmt_bind_param($stmt, "ssss", $patientName, $appointmentDate, $doctorId, $appointmentTime);
    } else {
        $sql = "INSERT INTO public_appointments (patient_name, appointment_date, doctor_id, birthday, phone_number, email, gender, appointment_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($connect, $sql);
        mysqli_stmt_bind_param($stmt, "ssssssss", $patientName, $appointmentDate, $doctorId, $birthday, $phoneNumber, $email, $gender, $appointmentTime);
    }

    if (!$stmt) {
        $_SESSION['message'] = "<div class='alert-container'><div class='alert alert-success text-center' role='alert'>Prepare failed: " . mysqli_error($connect) . "</div></div>";
        header('Location: index.php');
        exit();
    }

    if (new DateTime($appointmentDate) < new DateTime()) {
        $_SESSION['message'] = "<div class='alert-container'><div class='alert alert-danger text-center' role='alert'>Cannot select a past date. Please choose a future date.</div></div>";
        header('Location: index.php');
        exit();
    }

    // Calculate the operational hours in 30-minute slots
    $startHour = new DateTime('09:00:00');
    $endHour = new DateTime('17:00:00');
    $interval = new DateInterval('PT30M');
    $timeSlots = new DatePeriod($startHour, $interval, $endHour);

    $sqlAppointments = "SELECT appointment_time FROM appointments WHERE doctor_id = ? AND appointment_date = ? 
                        UNION 
                        SELECT appointment_time FROM public_appointments WHERE doctor_id = ? AND appointment_date = ? 
                        ORDER BY appointment_time ASC";
    $stmtAppointments = mysqli_prepare($connect, $sqlAppointments);
    mysqli_stmt_bind_param($stmtAppointments, "ssss", $doctorId, $appointmentDate, $doctorId, $appointmentDate);
    mysqli_stmt_execute($stmtAppointments);
    $resultTimeSlots = mysqli_stmt_get_result($stmtAppointments);

    $bookedSlots = [];
    while ($row = mysqli_fetch_assoc($resultTimeSlots)) {
        $bookedSlots[] = $row['appointment_time'];
    }

    $appointmentTime = "";
    foreach ($timeSlots as $slot) {
        $slotTime = $slot->format('H:i:s');
        if (!in_array($slotTime, $bookedSlots)) {
            $appointmentTime = $slotTime;
            break;
        }
    }

    if (empty($appointmentTime)) {
        $_SESSION['message'] = "<div class='alert-container'><div class='alert alert-danger text-center' role='alert'>No available slots for this day. Please choose another day.</div></div>";
        header('Location: index.php');
        exit();
    }

    if (mysqli_stmt_execute($stmt)) {

        // Email sending logic
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username   = 'htaulant0@gmail.com'; // SMTP username (Your Gmail address)
            $mail->Password   = 'hyvcfiptogxgbzhj'; // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('HKosova@gmail.com', 'Prizren Clinic');
            $mail->addAddress($email, $patientName); // Add a recipient

            $mail->isHTML(true);
            $mail->Subject = 'Appointment Confirmation';
            $mail->Body = "
                    <html>
                    <head>
                        <title>Appointment Confirmation</title>
                        <style>
                            body { font-family: Arial, sans-serif; }
                            .container { background-color: #f8f9fa; padding: 20px; border-radius: 10px; }
                            .header { color: #333; }
                            .content { margin-top: 20px; }
                            .footer { margin-top: 20px; font-size: 12px; color: #999; }
                        </style>
                    </head>
                    <body>
                        <div class='container'>
                            <h1 class='header'>Appointment Confirmation</h1>
                            <div class='content'>
                            Dear $patientName,<br><br>
                            Your appointment on <strong>$appointmentDate at $appointmentTime</strong> has been successfully confirmed, remember you need to be in time!<br><br>
                            Thank you for choosing our clinic.
                            </div>
                            <div class='footer'>
                                Clinic Address: Kosov, Prizren<br>
                                Phone: +383-44-444-444<br>
                                Email: HKosova@gmail.com
                            </div>
                        </div>
                    </body>
                    </html>";

            $mail->send();
            $_SESSION['message'] = "<div class='alert-container'><div class='alert alert-success text-center' role='alert'>Appointment booked and confirmation email sent.<br>Added successfully!</div></div>";
        } catch (Exception $e) {
            $_SESSION['message'] = "<div class='alert alert-danger' role='alert'>Mailer Error: {$mail->ErrorInfo}</div>";
        }
    } else {
        $_SESSION['message'] = "<div class='alert alert-danger' role='alert'>Error adding appointment: " . mysqli_error($connect) . "</div>";
    }

    header('Location: index.php');
    exit();
}
$patientName = '';
if (isset($_SESSION["user"])) {
    $userId = $_SESSION["user"];
    $sqlUser = "SELECT fname, lname FROM users WHERE id = ?";
    $stmtUser = mysqli_prepare($connect, $sqlUser);
    mysqli_stmt_bind_param($stmtUser, "i", $userId);
    mysqli_stmt_execute($stmtUser);
    $resultUser = mysqli_stmt_get_result($stmtUser);
    if ($rowUser = mysqli_fetch_assoc($resultUser)) {
        $patientName = $rowUser['fname'] . ' ' . $rowUser['lname'];
    }
}

$sqlDoctors = "SELECT doctor_id, name, specialization FROM doctors";
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
        * {
            padding: 0;
            margin: 0;
        }

        a {
            text-decoration: none;
        }

        .checkBtn {
            margin-top: 25px;
        }

        body {
            background-color: #dfe3f4;
        }

        .durchgestrichen {
            text-decoration: line-through;
        }

        .detailsBtn {
            width: 100%;
            color: white;
            background-color: black;
            border: none;
            height: 2rem;

        }

        .searchDiv {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 20px 0;
        }

        .input-group {
            max-width: 300px;
        }

        .form-control {
            border-radius: 20px;
        }

        .btn-dark {
            border-radius: 20px;
            background-color: #343a40;
            color: #fff;
        }

        .btn-dark:hover {
            background-color: #23272b;
            position: fixed;
            top: 20%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80%;
            max-width: 400px;
            z-index: 11111;
        }

        /* Custom Form Styling */
        .form-control,
        .form-select {
            border-radius: 0.5rem;
            border: 1px solid #007bff;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #0056b3;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #0056b3;
            z-index: 1000;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #003875;
        }

        @media (max-width: 768px) {
            .form-label {
                font-size: 1rem;
            }

            .form-control,
            .form-select {
                font-size: 0.9rem;
            }
        }
    </style>
</head>

<body>
    <?php include_once "components/navbar.php"; ?>
    <?php include_once "components/hero.php"; ?>

    <!-- Display the appointment form for all users -->
    <button type="button" class="btn btn-primary position-fixed bottom-0 end-0 mb-5 me-5" data-bs-toggle="modal" data-bs-target="#appointmentModal">
        Add Appointment
    </button>


    <!-- Modal container for the form -->
    <div class="modal fade" id="appointmentModal" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="appointmentModalLabel">Add Appointment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form action="" autocomplete="off" method="POST" class="row g-2 g-lg-3">
                        <div class="col-md-4">
                            <label for="patient_name" class="form-label">Patient Name</label>
                            <input type="text" class="form-control" id="patient_name" name="patient_name" value="<?php echo htmlspecialchars($patientName); ?>" <?php echo isset($_SESSION["user"]) ? 'readonly' : ''; ?> required>
                        </div>
                        <div class="col-md-4">
                            <label for="appointment_date" class="form-label">Appointment Date</label>
                            <input type="date" class="form-control" id="appointment_date" name="appointment_date" required>
                        </div>
                        <div class="col-md-4">
                            <label for="doctor_id" class="form-label">Doctor</label>
                            <select class="form-select" id="doctor_id" name="doctor_id" required>
                                <option value="" selected disabled>Select Doctor</option>
                                <?php while ($rowDoctor = mysqli_fetch_assoc($resultDoctors)) : ?>
                                    <option value="<?= $rowDoctor['doctor_id']; ?>">
                                        <?= $rowDoctor['name']; ?> - "<?= $rowDoctor['specialization']; ?>"
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <!-- Additional fields for public users (non-logged-in users) -->
                        <?php if (!isset($_SESSION["user"])) : ?>
                            <div class="col-md-4">
                                <label for="birthday" class="form-label">Birthday</label>
                                <input type="date" class="form-control" id="birthday" name="birthday" required>
                            </div>
                            <div class="col-md-4">
                                <label for="phone_number" class="form-label">Phone Number</label>
                                <input type="text" class="form-control" id="phone_number" name="phone_number" required>
                            </div>
                            <div class="col-md-4">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="col-md-4">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-select" id="gender" name="gender" required>
                                    <option value="" selected disabled>Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        <?php endif; ?>
                        <div class="col-12 mt-3">
                            <button type="submit" class="btn btn-primary">Add Appointment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php include_once "components/footer.php"; ?>

    <script>
        window.onload = function() {
            setTimeout(function() {
                const alertElement = document.querySelector('.alert-container');
                if (alertElement) {
                    alertElement.style.display = 'none';
                }
            }, 3000);
        };
    </script>


    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js' integrity='sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz' crossorigin='anonymous'></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
</body>

</html>