<?php
session_start();
require_once "db_connect.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= include_once "brand.php"; ?></title>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">

    <style>
        /* about style  */

        body {
            background-color: #dfe3f4;
        }

        .about-box {
            background-color: #403D39;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .about-box h2 {
            color: #EB5E28;
            margin-top: 0;
            margin-bottom: 20px;
        }

        .about-box p {
            color: #FFFCF2;
        }

        .about-image {
            width: 100%;
            height: auto;
            border-radius: 8px;
            margin-left: 0;
        }

        .btn {
            background-color: #EB5E28 !important;
            border: none;
        }

        a {
            text-decoration: none;
        }
    </style>
</head>

<body>
    <?php include_once "components/navbar.php"
    ?>


    <div class="container my-4">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-lg-6 col-md-12 col-sm-12">
                <div class="about-box">
                    <h2>About Us</h2>
                    <p>Welcome to Hospital Kosovo, your trusted healthcare partner since 1990. We are dedicated to providing exceptional medical services and compassionate care, enhancing the well-being of our community.</p>
                    <p>From our humble beginnings, we've become a leading hospital known for excellence in patient care and advanced technology. Our mission is to set new standards in healthcare, ensuring every patient is treated with dignity and respect.</p>
                    <p>Whether you need routine check-ups, specialized treatments, or emergency care, we're here for you. Reach out with any health concerns or questions. Together, let's build a healthier future.</p>
                    <p>Email: contact@hospitalkosovo.com</p>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 mt-3 d-flex flex-column">
                <img src="https://img.freepik.com/free-vector/people-walking-sitting-hospital-building-city-clinic-glass-exterior-flat-vector-illustration-medical-help-emergency-architecture-healthcare-concept_74855-10130.jpg" class="about-image">
                <a class="btn btn-success mt-4" href="index.php">Add Appointment</a>
            </div>
        </div>
    </div>

    <div class="mt-5">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d47273.28652308653!2d20.693312180582254!3d42.223411697645126!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1353950a12f4301f%3A0xda0e2e9b8d3d5850!2sPrizren!5e0!3m2!1sen!2s!4v1707758983080!5m2!1sen!2s" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>


    <?php include_once "components/footer.php"
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>

</body>

</html>