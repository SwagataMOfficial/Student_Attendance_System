<?php
include('partitions/_dbconnect.php');
include('classes/Authenticate.php');
session_start();

if (isset($_POST["register"]) && $_POST["register"] == "register") {
    if(strlen($_POST['password']) < 10){
        $validation = 4;    // setting the variable to show the minimum requirement character checking.
    }
    else{
        $registration = new Register($_POST['email']);
        $_SESSION['student_register'] = $registration;
        $validation = $registration->validate_student($pdo, $_POST);
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

    <script>
        // handling mouse right clicks
        window.addEventListener('mousedown', (e) => {
            // e.button returns a number where 2 means right clicks.
            if (e.button === 2) {
                alert("Right click is disabled for security reasons!!!");
            }
        });
    </script>
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
                    <i class='bx bx-show show-hide-btn' style="right: 23px; top: 54%; cursor: pointer;"></i>
                    <i class='bx bxs-lock-alt'></i>
                </div>
                <button type="submit" name="login" value="login" class="btn animation" style="--i:3; --j:24;">Login</button>
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
            <form method="post" autocomplete="off">
                <div class="input-box animation" style="--i:19; --j:2;">
                    <input type="email" name="email" required <?php if (isset($_POST["register"])) {echo "value=$_POST[email]";} ?> >
                    <label for="email">Email</label>
                    <i class='bx bxs-envelope'></i>
                </div>
                <div class="input-box animation" style="--i:20; --j:3;">
                    <input type="password" name="password" placeholder="Mimimum 10 characters!" class="password" required <?php if (isset($_POST["register"])) {echo "value=$_POST[password]";} ?> >
                    <label for="password">Password</label>
                    <i class='bx bx-show show-hide-btn' style="right: 23px; top: 54%; cursor: pointer;"></i>
                    <i class='bx bxs-lock-alt'></i>
                </div>
                <div class="input-box animation" style="--i:20; --j:3;">
                    <input type="password" name="cpassword" required <?php if (isset($_POST["register"])) {echo "value=$_POST[cpassword]";} ?> >
                    <label for="password">Confirm Password</label>
                    <i class='bx bx-show show-hide-btn' style="right: 23px; top: 54%; cursor: pointer;"></i>
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
        const show_hide_btn = document.querySelectorAll('.show-hide-btn');

        registerLink.addEventListener('click', () => {
            wrapper.classList.add('active');
        });

        loginLink.addEventListener('click', () => {
            wrapper.classList.remove('active');
        });

        show_hide_btn.forEach(element => {
            element.addEventListener('click', (e) => {
                let password = e.target.parentNode.getElementsByTagName('input')[0];
                if (password.type === "text") {
                    password.type = "password";
                    element.classList.add('bx-show');
                    element.classList.remove('bx-hide');
                } else {
                    password.type = "text";
                    element.classList.remove('bx-show');
                    element.classList.add('bx-hide');
                }
            });
        });
    </script>
    <script>
        <?php
        if (isset($validation)) {
            switch ($validation) {
                case 1:
                    echo 'alert("Passwords Do Not Matched!");';
                    break;
                case 2:
                    echo 'alert("Failed to send OTP! Try Again...");';
                    break;
                case 3:
                    echo "alert('Email Already Registered!');";
                    break;
                case 4:
                    echo "alert('Minimum 10 characters required!');";
                    break;
                default:
                    break;
            }
        }
        ?>
    </script>
</body>

</html>