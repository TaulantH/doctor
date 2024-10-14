<?php
session_start();
require_once "../db_connect.php"; 

// Redirect if not logged in as admin
if (!isset($_SESSION['user']) && !isset($_SESSION['adm'])) {
    header("Location: ../login.php");
    exit();
}

$doctorId = isset($_GET['doctor_id']) ? $_GET['doctor_id'] : null;
$name = $specialization = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['doctor_id'])) {
    $doctorId = $_POST['doctor_id'];
    $name = trim($_POST['name']);
    $specialization = trim($_POST['specialization']);

    if (empty($name) || empty($specialization)) {
        $error = "Name and specialization fields cannot be empty.";
    } else {
        $sql = "UPDATE doctors SET name = ?, specialization = ? WHERE doctor_id = ?";
        if ($stmt = mysqli_prepare($connect, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssi", $name, $specialization, $doctorId);

            if (mysqli_stmt_execute($stmt)) {
                header("Location: ../dashboard.php?update=success");
                exit();
            } else {
                $error = "Error updating record: " . mysqli_error($connect);
            }
            mysqli_stmt_close($stmt);
        } else {
            $error = "SQL error: " . mysqli_error($connect);
        }
    }
} else if ($doctorId) {
    $sql = "SELECT name, specialization FROM doctors WHERE doctor_id = ?";
    if ($stmt = mysqli_prepare($connect, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $doctorId);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            if ($row = mysqli_fetch_assoc($result)) {
                $name = $row['name'];
                $specialization = $row['specialization'];
            } else {
                $error = "No doctor found with the specified ID.";
            }
        } else {
            $error = "Error fetching record: " . mysqli_error($connect);
        }
        mysqli_stmt_close($stmt);
    } else {
        $error = "SQL error: " . mysqli_error($connect);
    }
}

mysqli_close($connect);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Doctor</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include_once "../components/navbarAdmin.php"; ?>

    <div class="container mt-5">
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <h2>Edit Doctor</h2>
        <form action="update_doctor.php" method="post">
            <input type="hidden" name="doctor_id" value="<?php echo htmlspecialchars($doctorId); ?>">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
            </div>
            <div class="form-group">
                <label for="specialization">Specialization:</label>
                <input type="text" class="form-control" id="specialization" name="specialization" value="<?php echo htmlspecialchars($specialization); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Doctor</button>
        </form>
    </div>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
