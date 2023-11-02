<?php
include('partitions/_dbconnect.php');
include('classes/StudentClass.php');
session_start();

// checking if a student has logged in
if (isset($_SESSION['student_loggedin']) && $_SESSION['student_loggedin'] == true) {
    $student = $_SESSION['student_obj']->getStudentDetails();
    $attendanceDetails = $_SESSION['student_obj']->getAttendanceDetails();
    
    // post request handle
    if (isset($_POST["scan"]) && $_POST["scan"] == "scan") {
        $scanResult = $_SESSION['student_obj']->getAttendance($pdo, $_POST);
        $_SESSION['student_obj']->setAttendanceDetails();
        $attendanceDetails = $_SESSION['student_obj']->getAttendanceDetails();
    }

    # TODO: add grading system and remarks calculation
    # TODO: write sql query to update the remarks and grade
    // $results = $_SESSION['student_obj']->get_grade_remarks();
    // $_SESSION['student_obj']->update_grade_remarks($pdo, $results);
}
// if no one has logged in then don't allow anyone to enter the student home page
else {
    header("Location: /Minor_Project/Student_Attendance_System/");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page (Student)</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <link rel="stylesheet" href="css/student_home.css">
    <?php
    require('js/chart_generate.php');
    ?>
</head>

<body>
    <!-- including header for our html -->
    <?php require("partitions/_headers.php") ?>
    <div class="container">
        <!-- including left side navigation bar for our html -->
        <?php require("partitions/_leftNavOptions.php") ?>
        <div class="right-main">
            <div class="welcome-heading">
                <span class="welcome-text">
                    <?php
                    if (isset($student["name"])) {
                        echo "Welcome <i>'$student[name]'</i>";
                    } else {
                        echo "Welcome <i>'[Student Name]'</i>";
                    }
                    ?>
                </span>
                <a href="logout.php" class="logout" id="logout">
                    <span class="material-symbols-outlined">
                        power_settings_new
                    </span>
                    <p>Logout</p>
                </a>
            </div>
            <section class="nofifications">
                <span class="warnings">
                    <?php
                    if (isset($student["is_locked"]) && $student["is_locked"]) {
                        echo '<b style="font-size: 0.9em; color: #ffff20;">Your Scanner is Locked!</b>';
                    } else {
                        echo '0 Warnings';
                    }
                    ?>
                </span>
                <a href="ScannerSystem.php" class="scanner-button">
                    <span class="material-symbols-outlined">qr_code_scanner</span>
                    <span class="scanner-text">Scanner</span>
                </a>
                <span class="unread-messages">No unread messages</span>
            </section>
            <section class="charts" id="progress">
                <p class="bar-chart-heading">Your Monthly Attendance Report</p>
                <div class="chart">
                    <div id="chartContainer" style=" width: 95%;"></div>
                </div>
            </section>
        </div>
    </div>
    <!-- including footer for our html -->
    <?php require("partitions/_footers.php") ?>

    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>

    <script>
        <?php
        if (isset($scanResult)) {
            switch ($scanResult) {
                case '1':
                    echo "alert('Wrong QR Code is Scanned!');
                            document.location.href='/Minor_Project/Student_Attendance_System/ScannerSystem.php'";
                    break;
                case '2':
                    echo "alert('Scanning Succesful!');";
                    break;
                default:
                    break;
            }
        }
        ?>
    </script>

    <script>
        const collapse_btn = document.getElementById("collapse-btn");

        collapse_btn.addEventListener("click", () => {
            document.querySelector("#collapse-btn .material-symbols-outlined").classList.toggle("arrow-rotate");
            document.querySelector(".container .left-nav").classList.toggle("nav-active");
            document.querySelector(".container .right-main").classList.toggle("main-stretch");
            document.querySelector("#collapse-btn .collapse-name").classList.toggle("btn-collapse");
            document.querySelectorAll(".links p").forEach((e) => {
                e.classList.toggle("collapsed-sm");
            });
            document.querySelectorAll(".contents .links").forEach((e) => {
                e.classList.toggle("headings-no-link");
            });
            document.querySelectorAll(".link-heading").forEach((e) => {
                e.classList.toggle("link-paras");
            });
            setTimeout(() => {
                generateChart();
            }, 610);            
        });
    </script>

    <script src="js/st_themeToggle.js"></script>

</body>

</html>