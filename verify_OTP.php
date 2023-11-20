<?php
include('partitions/_dbconnect.php');
include('classes/Authenticate.php');
session_start();

// student otp validation.
if (isset($_SESSION['student_register'])) {
    if (isset($_POST['otp_btn']) && $_POST['otp_btn'] == "otp_btn") {
        if(isset($_COOKIE['expiry_time'])){
            $userOTP = $_POST['otp'][0] . $_POST['otp'][1] . $_POST['otp'][2] . $_POST['otp'][3] . $_POST['otp'][4] . $_POST['otp'][5];
            if ($_SESSION['student_register']->registerStudent($pdo, $userOTP)) {
                $st_registered = true;
            } else {
                $otpNotMatched = true;
            }
        }
        else{
            $otpExpired = true;
        }
    }
}

// teacher otp validation
elseif (isset($_SESSION['teacher_register'])) {
    if (isset($_POST['otp_btn']) && $_POST['otp_btn'] == "otp_btn") {
        if(isset($_COOKIE['expiry_time'])){
            $userOTP = $_POST['otp'][0] . $_POST['otp'][1] . $_POST['otp'][2] . $_POST['otp'][3] . $_POST['otp'][4] . $_POST['otp'][5];
            if ($_SESSION['teacher_register']->registerTeacher($pdo, $userOTP)) {
                $t_registered = true;
            } else {
                $otpNotMatched = true;
            }
        }
        else{
            $otpExpired = true;
        }
    }
}
else {
    header("Location: /Minor_Project/Student_Attendance_System/");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <link rel="stylesheet" href="css/verify_otp.css">
</head>

<body>
    <div class="container">
        <h2>Verify Your Account</h2>
        <p>We emailed you the six digit code to your personal@gmail.com<br>Enter the code below to confirm your email
            address</p>

        <form action="verify_OTP.php" class="code-container" id="otp_form" method="post">
            <input type="number" class="code" name="otp[]" placeholder="0" min="0" max="9" required>
            <input type="number" class="code" name="otp[]" placeholder="0" min="0" max="9" required>
            <input type="number" class="code" name="otp[]" placeholder="0" min="0" max="9" required>
            <input type="number" class="code" name="otp[]" placeholder="0" min="0" max="9" required>
            <input type="number" class="code" name="otp[]" placeholder="0" min="0" max="9" required>
            <input type="number" class="code" name="otp[]" placeholder="0" min="0" max="9" required>
        </form>
        <div>
            <button type="submit" name="otp_btn" value="otp_btn" class="btn btn-primary" form="otp_form">Verify</button>
        </div>
        <small>
            OTP will expire in <strong>10 Seconds!!</strong>
        </small>
    </div>

    <script>
        <?php
        if (isset($st_registered) && $st_registered) {
            echo 'alert("Registration Successful! Click Ok to Create Your Profile...");
            window.location.href = "/Minor_Project/Student_Attendance_System/studentProfile.php"; ';
        }
        if (isset($t_registered) && $t_registered) {
            echo 'alert("Registration Successful! Click Ok to Create Your Profile...");
            window.location.href = "/Minor_Project/Student_Attendance_System/teacherProfile.php"; ';
        }
        if (isset($otpNotMatched) && $otpNotMatched) {
            echo 'alert("OTP did not matched! try again....");';
        }

        if (isset($otpExpired) && $otpExpired) {
            echo 'alert("OTP Expired! Register again to get another otp....");
            window.location.href = "/Minor_Project/Student_Attendance_System/"; ';
        }
        ?>
    </script>
    <script>
        const codes = document.querySelectorAll('.code');

        codes[0].focus();

        codes.forEach((code, idx) => {
            code.addEventListener("keydown", (e) => {
                if (e.key >= 0 && e.key <= 9) {
                    codes[idx].value = '';
                    setTimeout(() => codes[idx + 1].focus(), 10);
                } else if (e.key === 'Backspace') {
                    setTimeout(() => codes[idx - 1].focus(), 10);
                }
            });
        });
    </script>
</body>

</html>