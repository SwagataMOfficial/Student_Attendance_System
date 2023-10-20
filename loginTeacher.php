<?php
include('partitions/_dbconnect.php');

if (isset($_POST["teacherLogin"]) && $_POST["teacherLogin"] == "teacherLogin") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $sql = "SELECT * FROM `teacher_registration` WHERE `teacher_email`='$email'";

    $query = $pdo->prepare($sql);
    $result = $query->execute();

    if($result){
        $num = $query->rowCount();
        if ($num == 1) {
            $credentials = $query->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $credentials['teacher_password'])) {
                $login = true;
                session_start();
                $_SESSION["teacher_loggedin"] = true;
                $_SESSION["teacher_email"] = $email;
            }
            else{
                $passNotMatched = true;
            }
        }
        else {
            $userNotExist = true;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login to Classified Teacher Login</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/login_register.css" type="text/css">
</head>

<body>
    <div class="wrapper">
        <span class="bg-animate"></span>
        <span class="bg-animate2"></span>
        <!-- this is login part -->
        <div class="form-box login">
            <h2 class="animation" style="--i:0; --j:21;">Login</h2>
            <form method="post" autocomplete="off">
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
                <button type="submit" name="teacherLogin" value="teacherLogin" class="btn animation"
                    style="--i:3; --j:24;">Login</button>
                <div class="logreg-link animation" style="--i:4; --j:25;">
                    <p>Don't have an account? <span class="register-link" title="Click Here to Sign Up">Sign
                            Up</span></p>
                </div>
            </form>
        </div>
        <div class="info-text login">
            <h2 class="animation" style="--i:0; --j:20;">Welcome Back!</h2>
            <p class="animation" style="--i:1; --j:21;">Enter your correct email and password to login as a Teacher.</p>
        </div>

        <!-- this is register part -->
        <div class="form-box register">
            <h2 class="animation" style="--i:17; --j:0;">Sign Up</h2>
            <form action="registerTeacher.php" method="post" autocomplete="off">
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
                <button type="submit" name="register" value="register" class="btn animation" style="--i:21; --j:4;">Sign
                    Up</button>
                <div class="logreg-link animation" style="--i:22; --j:5;">
                    <p>Already have an account? <span class="login-link" title="Click Here to Login">Login</span></p>
                </div>
            </form>
        </div>
        <div class="info-text register">
            <h2 class="animation" style="--i:17; --j:0;">Welcome!</h2>
            <p class="animation" style="--i:18; --j:1;">You can safely create your account as Teacher.</p>
        </div>
    </div>

    <!-- <script src="js/script.js"></script> -->
    <script>
        const wrapper = document.querySelector('.wrapper');
        const registerLink = document.querySelector('.register-link');
        const loginLink = document.querySelector('.login-link');

        <?php
            if(isset($login) && $login){
                echo 'alert("You are successfully loggedin! Click ok to continue....");
                    window.location.href = "/Minor_Project/Student_Attendance_System/teacher_home.php";
                ';
            }
            if(isset($passNotMatched) && $passNotMatched){
                echo 'alert("Invalid Password!");';
            }
            if(isset($userNotExist) && $userNotExist){
                echo 'alert("Email not Registered!");';
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