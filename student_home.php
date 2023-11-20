<?php
include('partitions/_dbconnect.php');
include('classes/StudentClass.php');
session_start();

// checking if a student has logged in
if (isset($_SESSION['student_loggedin']) && $_SESSION['student_loggedin'] == true) {
    $student = $_SESSION['student_obj']->getStudentDetails();
    if($student['is_sem_unlocked'] == 1){
        $_SESSION['sem_unlocked'] = true;
    }
    $attendanceDetails = $_SESSION['student_obj']->getAttendanceDetails();
    
    // post request handle
    if (isset($_POST["scan"]) && $_POST["scan"] == "scan") {
        $scanResult = $_SESSION['student_obj']->getAttendance($pdo, $_POST);
        if($scanResult == 2){
            setcookie('current_time', strtotime('today'), );
            setcookie('next_scan_time', strtotime('tomorrow'),);
        }
        $_SESSION['student_obj']->setAttendanceDetails();
        $_SESSION['student_obj']->update_grade_remarks($pdo);
        
        $student = $_SESSION['student_obj']->getStudentDetails();
        $attendanceDetails = $_SESSION['student_obj']->getAttendanceDetails();
    }
}
// if no one has logged in then don't allow anyone to enter the student home page
else {
    header("Location: /Student_Attendance_System/");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page (Student)</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <script>
        function darkTheme(){
            document.querySelector(':root').style.setProperty('--clr-nav', '#141824');
            document.querySelector(':root').style.setProperty('--clr-btn-text-logo', '#9fa6bc');
            document.querySelector(':root').style.setProperty('--clr-heading-mark', '#6e7891');
            document.querySelector(':root').style.setProperty('--clr-hover-color', '#31374a');
            document.querySelector(':root').style.setProperty('--clr-hover-p-color', 'white');
            document.querySelector(':root').style.setProperty('--clr-bgcolor', '#0f111a');
            document.querySelector(':root').style.setProperty('--clr-theme-bg', '#131386');
            document.querySelector(':root').style.setProperty('--clr-essential-btn', '#1e2436');
        }
        function lightTheme(){
            document.querySelector(':root').style.setProperty('--clr-nav', '#f0f0f0');
            document.querySelector(':root').style.setProperty('--clr-btn-text-logo', '#0b1b4c');
            document.querySelector(':root').style.setProperty('--clr-heading-mark', '#6e6e6e');
            document.querySelector(':root').style.setProperty('--clr-hover-color', '#cecece');
            document.querySelector(':root').style.setProperty('--clr-hover-p-color', 'black');
            document.querySelector(':root').style.setProperty('--clr-bgcolor', 'white');
            document.querySelector(':root').style.setProperty('--clr-theme-bg', 'darkorange');
            document.querySelector(':root').style.setProperty('--clr-essential-btn', '#a8a8a8');
        }
    </script>
    <script>
        let initializeTheme = () => {
            if (localStorage.getItem('theme') === 'dark') {
                darkTheme();
            } else {
                lightTheme();
            }
        };
        </script>
    <script>
        initializeTheme();
    </script>
    <link rel="stylesheet" href="css/headers.css">
    <link rel="stylesheet" href="css/footers.css">
    <link rel="stylesheet" href="css/leftNav.css">
    <link rel="stylesheet" href="css/student_home.css">
    <link rel="stylesheet" href="css/responsive.css">
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
                    }
                    elseif(date('d')>=15 && (isset($student['grade']) && ($student['grade'] == 'F' || $student['grade'] == 'D'))){
                        echo '<b style="font-size: 0.9em; color: #ffff20;">Your Attendance is Low!</b>';
                    }
                    else {
                        echo '0 Warnings';
                    }
                    ?>
                </span>
                <a href="ScannerSystem.php" class="scanner-button">
                    <span class="material-symbols-outlined">qr_code_scanner</span>
                    <span class="scanner-text">Scanner</span>
                </a>
                <?php
                    if(isset($_SESSION['sem_unlocked']) && $_SESSION['sem_unlocked']){
                        echo '<a href="profile_student.php" style="font-size: 0.9em; text-decoration:none; color: #74ff20;"><b>Change your semester!</b></a>';
                    }
                    else {
                        echo  '<span class="unread-messages">No unread messages</span>';
                    }
                    
                ?>
            </section>
            <section class="charts" id="progress">
                <p class="bar-chart-heading">Your Monthly Attendance Report</p>
                <div class="chart">
                    <div id="chartContainer" style=" width: 95%;"></div>
                </div>
            </section>
            <section class="chart-options" id="charts">
                <p>Select a Chart to Analyze</p>
                <div class="btn-container">
                    <button type="button" class="chart-btns" id="column">
                        <div class="material-symbols-outlined chart-btn-logo">bar_chart</div>
                        <div class="chart-btn-text">Column Chart</div>
                    </button>
                    <button type="button" class="chart-btns" id="pie">
                        <div class="material-symbols-outlined chart-btn-logo">incomplete_circle</div>
                        <div class="chart-btn-text">Pie Chart</div>
                    </button>
                    <button type="button" class="chart-btns" id="bar">
                        <div class="material-symbols-outlined chart-btn-logo">signal_cellular_alt</div>
                        <div class="chart-btn-text">Bar Chart</div>
                    </button>
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
                            document.location.href='/Student_Attendance_System/ScannerSystem.php'";
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

    <script>
        document.querySelectorAll('.right-main .chart-options .chart-btns').forEach((e)=>{
            e.addEventListener('click', ()=>{
                generateChartWithType(e.id);
            });
        });

        document.querySelector('.header__navbar .nav__hamburger').addEventListener('click', ()=>{
            console.log('clicked');
            if(getComputedStyle(document.querySelector('.container .left-nav')).getPropertyValue('display') === "none"){
                console.log('yes');
                document.querySelector('.header__navbar .nav__hamburger span').innerText = "close";
                document.querySelector('.container .left-nav').style.display = "flex";
            }
            else{
                console.log('no');
                document.querySelector('.header__navbar .nav__hamburger span').innerText = "menu";
                document.querySelector('.container .left-nav').style.display = "none";
            }
        });
    </script>
    <script>
        <?php
            if(isset($_SESSION['sem_unlocked']) && $_SESSION['sem_unlocked']){
                echo 'document.querySelector(".nav__right .right__notification").addEventListener("click", ()=>{
                    if(getComputedStyle(document.querySelector(".nav__right .right__notification .notification")).getPropertyValue("display") === "none"){
                        document.querySelector(".nav__right .right__notification .notification").style.display = "flex";
                    }
                    else{
                        document.querySelector(".nav__right .right__notification .notification").style.display = "none";
                    }
                });';
            }
        ?>
    </script>

</body>

</html>