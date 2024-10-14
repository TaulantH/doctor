<?php
session_start();
require_once "db_connect.php";
require 'vendor/autoload.php'; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Clean inputs function
function cleanInput($input) { 
    $data = trim($input);
    $data = strip_tags($data);
    $data = htmlspecialchars($data);
    return $data;
}

$error = false;
$emailError = $nameError = $messageError = "";
$form_name = $form_phone = $form_email = $form_website = $form_subject = $form_message = "";

if (isset($_POST['sendMail'])) {
    $form_name = cleanInput($_POST['form_name']);
    $form_phone = cleanInput($_POST['form_phone']);
    $form_email = cleanInput($_POST['form_email']);
    $form_website = cleanInput($_POST['form_website']);
    $form_subject = cleanInput($_POST['form_subject']);
    $form_message = cleanInput($_POST['form_message']);

    if (empty($form_name)) {
        $error = true;
        $nameError = "Please enter a Name";
    }
    if (empty($form_email)) {
        $error = true;
        $emailError = "Please enter an Email";
    } elseif (!filter_var($form_email, FILTER_VALIDATE_EMAIL)) {
        $emailError = "Invalid email format.";
    }
    if (empty($form_message)) {
        $error = true;
        $messageError = "Please leave a message";
    }

    if (!$error) {
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.example.com'; // Specify your mail server
            $mail->SMTPAuth = true;
            $mail->Username   = 'htaulant0@gmail.com'; // SMTP username (Your Gmail address)
            $mail->Password   = 'hyvcfiptogxgbzhj';  // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587; // TCP port to connect to

            // Recipients
            $mail->setFrom('htaulant0@gmail.com', 'Mailer');
            $mail->addAddress('htaulant0@gmail.com', 'Recipient Name'); // Add a recipient

            // Content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = $form_subject;
            $mail->Body    = "<p><strong>Name:</strong> {$form_name}</p>
                              <p><strong>Phone:</strong> {$form_phone}</p>
                              <p><strong>Email:</strong> {$form_email}</p>
                              <p><strong>Website:</strong> {$form_website}</p>
                              <p><strong>Message:</strong> {$form_message}</p>";
            $mail->AltBody = strip_tags($mail->Body);

            $mail->send();
            echo "<script>alert('Message has been sent');</script>";
        } catch (Exception $e) {
            echo "<script>alert('Message could not be sent. Mailer Error: {$mail->ErrorInfo}');</script>";
        }
        // Redirect to prevent form resubmission
        header("Location: contact.php");
        exit;
    }
}
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
      body{
        background-color: #dfe3f4;
      }
        a,
        a:hover,
        a:focus,
        a:active {
          text-decoration: none;
          outline: none;
        }

        a,
        a:active,
        a:focus {
          color: #333;
          text-decoration: none;
          transition-timing-function: ease-in-out;
          -ms-transition-timing-function: ease-in-out;
          -moz-transition-timing-function: ease-in-out;
          -webkit-transition-timing-function: ease-in-out;
          -o-transition-timing-function: ease-in-out;
          transition-duration: 0.2s;
          -ms-transition-duration: 0.2s;
          -moz-transition-duration: 0.2s;
          -webkit-transition-duration: 0.2s;
          -o-transition-duration: 0.2s;
        }

        ul {
          margin: 0;
          padding: 0;
          list-style: none;
        }
        img {
          max-width: 100%;
          height: auto;
        }

        .sec-title-style1 {
          position: relative;
          display: block;
          margin-top: -9px;
          padding-bottom: 50px;
        }
        .sec-title-style1.max-width {
          position: relative;
          display: block;
          max-width: 770px;
          margin: -9px auto 0;
          padding-bottom: 52px;
        }
        .sec-title-style1.pabottom50 {
          padding-bottom: 42px;
        }
        .sec-title-style1 .title {
          position: relative;
          display: block;
          color: #131313;
          font-size: 36px;
          line-height: 46px;
          font-weight: 700;
          text-transform: uppercase;
        }
        .sec-title-style1 .title.clr-white {
          color: #ffffff;
        }
        .sec-title-style1 .decor {
          position: relative;
          display: block;
          width: 70px;
          height: 5px;
          margin: 19px 0 0;
        }
        .sec-title-style1 .decor:before {
          position: absolute;
          top: 0;
          right: 0;
          bottom: 0;
          width: 5px;
          height: 5px;
          background: #ffa500;
          border-radius: 50%;
          content: "";
        }
        .sec-title-style1 .decor:after {
          position: absolute;
          top: 0;
          right: 10px;
          bottom: 0;
          width: 5px;
          height: 5px;
          background: #ffa500;
          border-radius: 50%;
          content: "";
        }
        .sec-title-style1 .decor span {
          position: absolute;
          top: 0;
          left: 0;
          bottom: 0;
          width: 50px;
          height: 1px;
          background: #ffa500;
          margin: 2px 0;
        }

        .sec-title-style1 .text {
          position: relative;
          display: block;
          margin: 7px 0 0;
        }
        .sec-title-style1 .text p {
          position: relative;
          display: inline-block;
          padding: 0 15px;
          color: #131313;
          font-size: 14px;
          line-height: 16px;
          font-weight: 700;
          text-transform: uppercase;
          margin: 0;
        }
        .sec-title-style1 .text.clr-yellow p {
          color: #ffa500;
        }
        .sec-title-style1 .text .decor-left {
          position: relative;
          top: -2px;
          display: inline-block;
          width: 70px;
          height: 5px;
          background: transparent;
        }
        .sec-title-style1 .text .decor-left span {
          position: absolute;
          top: 0;
          left: 0;
          bottom: 0;
          width: 50px;
          height: 1px;
          background: #ffa500;
          content: "";
          margin: 2px 0;
        }
        .sec-title-style1 .text .decor-left:before {
          position: absolute;
          top: 0;
          right: 0;
          bottom: 0;
          width: 5px;
          height: 5px;
          background-color: #dfe3f4;
          border-radius: 50%;
          content: "";
        }
        .sec-title-style1 .text .decor-left:after {
          position: absolute;
          top: 0;
          right: 10px;
          bottom: 0;
          width: 5px;
          height: 5px;
          background: #ffa500;
          border-radius: 50%;
          content: "";
        }

        .sec-title-style1 .text .decor-right {
          position: relative;
          top: -2px;
          display: inline-block;
          width: 70px;
          height: 5px;
          background: transparent;
        }
        .sec-title-style1 .text .decor-right span {
          position: absolute;
          top: 0;
          right: 0;
          bottom: 0;
          width: 50px;
          height: 1px;
          background: #ffa500;
          content: "";
          margin: 2px 0;
        }
        .sec-title-style1 .text .decor-right:before {
          position: absolute;
          top: 0;
          left: 0;
          bottom: 0;
          width: 5px;
          height: 5px;
          background: #ffa500;
          border-radius: 50%;
          content: "";
        }
        .sec-title-style1 .text .decor-right:after {
          position: absolute;
          top: 0;
          left: 10px;
          bottom: 0;
          width: 5px;
          height: 5px;
          background: #ffa500;
          border-radius: 50%;
          content: "";
        }

        .sec-title-style1 .bottom-text {
          position: relative;
          display: block;
          padding-top: 16px;
        }
        .sec-title-style1 .bottom-text p {
          color: #848484;
          font-size: 16px;
          line-height: 26px;
          font-weight: 400;
          margin: 0;
        }
        .sec-title-style1 .bottom-text.clr-gray p {
          color: #cdcdcd;
        }
        .contact-address-area {
          position: relative;
          display: block;
          background-color: #dfe3f4;
          padding: 100px 0 120px;
        }
        .contact-address-area .sec-title-style1.max-width {
          padding-bottom: 72px;
        }
        .contact-address-box {
          display: flex;
          justify-content: space-between;
          flex-direction: row;
          flex-wrap: wrap;
          align-items: center;
        }
        .single-contact-address-box {
          position: relative;
          display: block;
          background: #131313;
          padding: 85px 30px 77px;
        }
        .single-contact-address-box .icon-holder {
          position: relative;
          display: block;
          padding-bottom: 24px;
        }
        .single-contact-address-box .icon-holder span:before {
          font-size: 75px;
        }
        .single-contact-address-box h3 {
          color: #ffffff;
          margin: 0px 0 9px;
        }
        .single-contact-address-box h2 {
          color: #EB5E28;
          font-size: 24px;
          font-weight: 600;
          margin: 0 0 19px;
        }
        .single-contact-address-box a {
          color: #ffffff;
        }

        .single-contact-address-box.main-branch {
          background-color: #FFFCF2;;
          padding: 53px 30px 51px;
          margin-top: -20px;
          margin-bottom: -20px;
        }
        .single-contact-address-box.main-branch h3 {
          color: #131313;
          font-size: 18px;
          font-weight: 700;
          margin: 0 0 38px;
          text-transform: uppercase;
          text-align: center;
        }
        .single-contact-address-box.main-branch .inner {
          position: relative;
          display: block;
        }
        .single-contact-address-box.main-branch .inner ul {
          position: relative;
          display: block;
          overflow: hidden;
        }
        .single-contact-address-box.main-branch .inner ul li {
          position: relative;
          display: block;
          padding-left: 110px;
          border-bottom: 1px solid #737373;
          padding-bottom: 23px;
          margin-bottom: 24px;
        }
        .single-contact-address-box.main-branch .inner ul li:last-child {
          border: none;
          margin-bottom: 0;
          padding-bottom: 0;
        }
        .single-contact-address-box.main-branch .inner ul li .title {
          position: absolute;
          top: 2px;
          left: 0;
          display: inline-block;
        }
        .single-contact-address-box.main-branch .inner ul li .title h4 {
          color: #131313;
          font-size: 18px;
          font-weight: 600;
          line-height: 24px;
          text-transform: capitalize;
          border-bottom: 2px solid #a5821e;
        }

        .single-contact-address-box.main-branch .inner ul li .text {
          position: relative;
          display: block;
        }
        .single-contact-address-box.main-branch .inner ul li .text p {
          color: #131313;
          font-size: 16px;
          line-height: 24px;
          font-weight: 600;
          margin: 0;
        }

        .contact-info-area {
          position: relative;
          display: block;
          background-color: #dfe3f4;
        }
        .contact-form {
          position: relative;
          display: block;
          background: #FFFCF2;
          padding: 100px 60px 80px;
          -webkit-box-shadow: 0px 3px 8px 2px #ededed;
          box-shadow: 0px 3px 8px 2px #ededed;
          z-index: 3;
        }
        .contact-form .sec-title-style1 {
          position: relative;
          padding-bottom: 51px;
          width: 50%;
        }
        .contact-form .text-box {
          position: relative;
          display: block;
          margin-top: 19px;
          width: 50%;
        }
        .contact-form .text p {
          color: #848484;
          line-height: 26px;
          margin: 0;
        }

        .contact-form .inner-box {
          position: relative;
          display: block;
          background: #ffffff;
        }
        .contact-form form {
          position: relative;
          display: block;
        }
        .contact-form form .input-box {
          position: relative;
          display: block;
        }

        .contact-form form input[type="text"],
        .contact-form form input[type="email"],
        .contact-form form textarea {
          position: relative;
          display: block;
          background: #ffffff;
          border: 1px solid #eeeeee;
          width: 100%;
          height: 55px;
          font-size: 16px;
          padding-left: 19px;
          padding-right: 15px;
          border-radius: 0px;
          margin-bottom: 20px;
          transition: all 500ms ease;
        }
        .contact-form form textarea {
          height: 130px;
          padding-left: 19px;
          padding-right: 15px;
          padding-top: 14px;
          padding-bottom: 15px;
        }
        .contact-form form input[type="text"]:focus {
          color: #222222;
          border-color: #d4d4d4;
        }
        .contact-form form input[type="email"]:focus {
          color: #222222;
          border-color: #d4d4d4;
        }
        .contact-form form textarea:focus {
          color: #222222;
          border-color: #d4d4d4;
        }
        .contact-form form input[type="text"]::-webkit-input-placeholder {
          color: #848484;
        }
        .contact-form form input[type="text"]:-moz-placeholder {
          color: #848484;
        }
        .contact-form form input[type="text"]::-moz-placeholder {
          color: #848484;
        }
        .contact-form form input[type="text"]:-ms-input-placeholder {
          color: #848484;
        }
        .contact-form form input[type="email"]::-webkit-input-placeholder {
          color: #848484;
        }
        .contact-form form input[type="email"]:-moz-placeholder {
          color: #848484;
        }
        .contact-form form input[type="email"]::-moz-placeholder {
          color: #848484;
        }
        .contact-form form input[type="email"]:-ms-input-placeholder {
          color: #848484;
        }
        .contact-form form button {
          position: relative;
          display: block;
          width: 100%;
          background: #EB5E28;
          border: 1px solid #ffa500;
          color: #FFFCF2;
          font-size: 16px;
          line-height: 55px;
          font-weight: 600;
          text-align: center;
          text-transform: capitalize;
          transition: all 200ms linear;
          transition-delay: 0.1s;
          cursor: pointer;
        }

        .contact-form form button:hover {
          color: #FFFCF2;
          background: #EB5E28;
          opacity: 0.7;
        }

  </style>
</head>
<body>

<?php include_once "components/navbar.php"
?>


<section class="contact-address-area">
    <div class="container">
        <div class="sec-title-style1 text-center max-width">
            <div class="title">Contact Us</div>
            <div class="text">
                <div class="decor-left"><span></span></div>
                <p>Contact Information</p>
                <div class="decor-right"><span></span></div>
            </div>
            <div class="bottom-text">
                <p>Feel free to get in touch with us if you have any questions or inquiries about our Hospital.</p>
            </div>
        </div>
        <div class="contact-address-box row">
            <div class="col-sm-4 single-contact-address-box text-center">
                <div class="icon-holder">
                    <span class="icon-clock-1"></span>
                </div>
                <h3>Working Hours</h3>
                <h2>Mon-Fri: 9:00 AM - 6:00 PM</h2>
            </div>
            <div class="col-sm-4 single-contact-address-box main-branch">
                <h3>Clinic</h3>
                <div class="inner">
                    <ul>
                        <li>
                            <div class="title">
                                <h4>Address:</h4>
                            </div>
                            <div class="text">
                                <p>Prizren, Kosova</p>
                            </div>
                        </li>
                        <li>
                            <div class="title">
                                <h4>Phone:<br> Email:</h4>
                            </div>
                            <div class="text">
                                <p>+383-44-444-444 <br> info@shopx.com</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-4 single-contact-address-box text-center">
                <div class="icon-holder">
                    <span class="icon-question-2"></span>
                </div>
                <h3>Customer Support</h3>
                <h2>We're here to assist you!</h2>
            </div>
        </div>
    </div>
</section>

<section class="contact-info-area mb-5">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="contact-form">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="sec-title-style1 float-left">
                                <div class="title">Get In Touch</div>
                                <div class="text">
                                    <div class="decor-left"><span></span></div>
                                    <p>Contact Form</p>
                                </div>
                            </div>
                            <div class="text-box float-right">
                                <p>If you have any questions, feel free to send us a message. Our dedicated team will get back to you promptly.</p>
                            </div>
                        </div>
                    </div>
                    <div class="inner-box">
                        <form id="contact-form" name="contact_form" class="default-form"  method="post">
                            <div class="row">
                                <div class="col-xl-6 col-lg-12">
                                    <div class="row">
                                        <div class="col-xl-6">
                                            <div class="input-box">
                                                <input type="text" name="form_name" value="<?= $form_name ?>" placeholder="Your Name" required="">
                                                <span class="text-danger"><?= $nameError ?></span>
                                            </div>
                                            <div class="input-box">
                                                <input type="text" name="form_phone" value="<?= $form_phone ?>" placeholder="Phone Number">
                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="input-box">
                                                <input type="email" name="form_email" value="<?= $form_email ?>" placeholder="Your Email" required="">
                                                <span class="text-danger"><?= $emailError ?></span>
                                            </div>
                                            <div class="input-box">
                                                <input type="text" name="form_website" value="<?= $form_website ?>" placeholder="Your Website">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="input-box">
                                                <input type="text" name="form_subject" value="<?= $form_subject ?>" placeholder="Subject">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-12">
                                    <div class="input-box">
                                        <textarea name="form_message" placeholder="Your Message..." required=""><?= $form_message ?></textarea>
                                        <span class="text-danger"><?= $messageError ?></span>
                                    </div>
                                    <div class="button-box">
                                        <input id="form_botcheck" name="form_botcheck" class="form-control" type="hidden" value="">
                                        <button type="submit" data-loading-text="Please wait..." name="sendMail" >Send Message<span class="flaticon-next"></span></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<?php include_once "components/footer.php"
    ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    
</body>
</html>