<?php
include('partitions/_dbconnect.php');
require('partitions/_otp_generator.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// function to send a mail
function sendMail($email, $newOTP)
{
    // including all necessary files
    require("PHPMailer/PHPMailer.php");
    require("PHPMailer/SMTP.php");
    require("PHPMailer/Exception.php");

    $mail = new PHPMailer(true);

    // setting the mail
    try {
        //Server settings
        $mail->isSMTP(); //Send using SMTP
        $mail->Host = 'smtp.gmail.com'; //Set the SMTP server to send through
        $mail->SMTPAuth = true; //Enable SMTP authentication
        $mail->Username = 'attendancesystem24x7@gmail.com'; //SMTP username
        $mail->Password = 'ymfu zmou nlvr dwjx'; //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
        $mail->Port = 465; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('attendancesystem365@gmail.com', 'Student Attendance');
        $mail->addAddress($email); // receiver's email address

        //Content
        $mail->isHTML(true); //Set email format to HTML
        $mail->Subject = 'OTP for verification for Student Attendance';
        $mail->Body = ' Thanks for registering in our website<br>
                        Your OTP is ' . $newOTP . '<br>
                        <strong>Do not share OTP with Anyone else.</strong>';

        $mail->send();
        return true;
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        return false;
    }
}



if (isset($_POST["register"]) && $_POST["register"] == "register") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    if ($password == $cpassword) {

        // generating otp here
        $otp = new OTP();
        $newOTP = $otp->generateOTP();
        if(sendMail($email, $newOTP)){
            session_start();
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $_SESSION['validation_email'] = $email;
            $_SESSION['validation_password'] = $hashedPassword;
            $_SESSION['validation_otp'] = $newOTP;
            $validate = true;
        }
    }
    else{
        $passNotMatched = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up to Classified</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/login_register.css" type="text/css">
</head>

<body>
    <div class="wrapper active">
        <span class="bg-animate"></span>
        <span class="bg-animate2"></span>
        <!-- this is login part -->
        <div class="form-box login">
            <h2 class="animation" style="--i:0; --j:21;">Login</h2>
            <form action="loginStudent.php" method="post" autocomplete="off">
                <div class="input-box animation" style="--i:1;  --j:22;">
                    <input type="email" name="email" required>
                    <label for="email">Email</label>
                    <i class='bx bxs-envelope'></i>
                </div>
                <div class="input-box animation" style="--i:2; --j:23;">
                    <input type="password" name="password" required>
                    <label for="password">Password</label>
                    <i class='bx bxs-lock-alt'></i>
                </div>
                <button type="submit" name="login" value="login" class="btn animation"
                    style="--i:3; --j:24;">Login</button>
                <div class="logreg-link animation" style="--i:4; --j:25;">
                    <p>Don't have an account? <span class="register-link" title="Click Here to Sign Up">Sign
                            Up</span></p>
                </div>
            </form>
        </div>
        <div class="info-text login">
            <h2 class="animation" style="--i:0; --j:20;">Welcome Back!</h2>
            <p class="animation" style="--i:1; --j:21;">Enter your correct email and password to login as a Student.</p>
        </div>

        <!-- this is register part -->
        <div class="form-box register">
            <h2 class="animation" style="--i:17; --j:0;">Sign Up</h2>
            <form method="post" autocomplete="off" onsubmit="showAnimation()">
                <div class="input-box animation" style="--i:19; --j:2;">
                    <input type="email" name="email" required>
                    <label for="email">Email</label>
                    <i class='bx bxs-envelope'></i>
                </div>
                <div class="input-box animation" style="--i:20; --j:3;">
                    <input type="password" name="password" required>
                    <label for="password">Password</label>
                    <i class='bx bxs-lock-alt'></i>
                </div>
                <div class="input-box animation" style="--i:20; --j:3;">
                    <input type="password" name="cpassword" required>
                    <label for="password">Confirm Password</label>
                    <i class='bx bxs-lock'></i>
                </div>
                <button type="submit" name="register" value="register" class="btn animation" style="--i:21; --j:4;">Sign Up</button>
                <div class="logreg-link animation" style="--i:22; --j:5;">
                    <p>Already have an account? <span class="login-link" title="Click Here to Login">Login</span></p>
                </div>
            </form>
        </div>
        <div class="info-text register">
            <h2 class="animation" style="--i:17; --j:0;">Welcome!</h2>
            <p class="animation" style="--i:18; --j:1;">You can safely create your account as Student.</p>
        </div>
    </div>

    <script>
        const wrapper = document.querySelector('.wrapper');
        const registerLink = document.querySelector('.register-link');
        const loginLink = document.querySelector('.login-link');

        <?php
            if(isset($validate) && $validate){
                echo '  alert("Click Ok to Validate OTP and Register....");
                        window.location.href = "/Minor_Project/Student_Attendance_System/verify_OTP.php";';
            }
            if(isset($passNotMatched) && $passNotMatched) {
                echo 'alert("Passwords Do Not Matched!");';
            }
        ?>

        registerLink.addEventListener('click', () => {
            wrapper.classList.add('active');
        });

        loginLink.addEventListener('click', () => {
            wrapper.classList.remove('active');
        });
    </script>
</body>

</html>