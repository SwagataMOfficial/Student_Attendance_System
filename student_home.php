<?php
session_start();
include('partitions/_dbconnect.php');

// checking if a student has logged in
if (isset($_SESSION['student_loggedin']) && $_SESSION['student_loggedin'] == true) {
    $student_email = $_SESSION["student_email"];

    // template for chart
    $attendanceDetails = array(
        array("label" => "January"),
        array("label" => "February"),
        array("label" => "March"),
        array("label" => "April"),
        array("label" => "May"),
        array("label" => "June"),
        array("label" => "July"),
        array("label" => "August"),
        array("label" => "September"),
        array("label" => "October"),
        array("label" => "November"),
        array("label" => "December"),
    );
    $getStudentData = "SELECT * FROM `student_profile` WHERE `student_email` ='$student_email'";
    // $result = mysqli_query($conn, $getStudentData);
    $query = $pdo->prepare($getStudentData);
    $result = $query->execute();

    // checking if student has created a profile or not
    // $numRows = mysqli_num_rows($result);
    $numRows = $query->rowCount();
    if ($numRows == 1) {
        // $student = mysqli_fetch_assoc($result);
        $student = $query->fetch(PDO::FETCH_ASSOC);
        $_SESSION["student_id"] = $student['student_id'];
        $_SESSION["student_name"] = $student['student_name'];
        if ($student['profile_picture'] != null) {
            $_SESSION["student_picture"] = $student['profile_picture'];
        }
    } else {
        unset($_SESSION['student_loggedin']);
        $_SESSION['student_profile_email'] = $student_email;
        header("Location: /Minor_Project/Student_Attendance_System/studentProfile.php");
    }

    $month = strtolower(date("F")); # this line of code gets current month

    $getStudent = "SELECT * FROM `student_attendance` WHERE student_id='$_SESSION[student_id]'";
    $query = $pdo->prepare($getStudent);
    $result = $query->execute();
    $studentData = $query->fetch(PDO::FETCH_ASSOC);
    $student_id = $studentData['student_id'];
    $student_name = $studentData['student_name'];
    $attendance = $studentData["$month"];
    $_SESSION['is_locked'] = $studentData['is_locked'];
    $i = 0;
    while ($i < 12) {
        $attendanceDetails[$i]['y'] = $studentData[strtolower($attendanceDetails[$i]['label'])];
        $i += 1;
    }

    // post request handle
    if (isset($_POST["scan"]) && $_POST["scan"] == "scan") {

        // checking if correct qr code is scanned or not
        if ($_POST["secret_key"] == '$2y$10$b1PT6x2LheA3sS7UJjOUEeU1vHp/r1RRFdo/6PqM1ZJooCxHF4lvK') {
            $student_id = $_POST["student_id"];
            $attendanceUpdate = (int) $_POST["student_attendance"];
            $updateQuery = "UPDATE `student_attendance` SET `$month` = $attendanceUpdate WHERE `student_id` = '$student_id'";
            // $result = mysqli_query($conn, $updateQuery);
            $query = $pdo->prepare($updateQuery);
            $result = $query->execute();
            header("refresh: 1; url=student_home.php");
        } else {
            header("Location: ScannerSystem.php");
        }
    }

    # TODO: add grading system and remarks calculation
    # TODO: write sql query to update the remarks and grade
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
    <script>
        window.onload = function() {

            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                // exportEnabled: true,
                // theme: "light1", // "light1", "light2", "dark1", "dark2"
                // theme: "light2", // "light1", "light2", "dark1", "dark2"
                theme: "dark1", // "light1", "light2", "dark1", "dark2"
                // theme: "dark2", // "light1", "light2", "dark1", "dark2"
                backgroundColor: "transparent",
                title: {
                    text: "Attendance"
                },
                axisY: {
                    title: "Attendance Count (in days)",
                    includeZero: true
                },
                data: [{
                    type: "column", //change type to bar, line, area, pie, etc
                    //indexLabel: "{y}", //Shows y value on all Data Points
                    // indexLabelFontColor: "#5A5757",
                    indexLabelFontColor: "#5A5757",
                    indexLabelPlacement: "outside",
                    bevelEnabled: true,
                    dataPoints: <?php echo json_encode($attendanceDetails, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chart.render();

        }
    </script>
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
                    if (isset($_SESSION["student_name"])) {
                        echo "Welcome <i>'$_SESSION[student_name]'</i>";
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
                    if (isset($_SESSION['is_locked']) && $_SESSION['is_locked']) {
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
    <?php
    if (isset($_POST["scan"]) && $_POST["scan"] == "scan") {
        echo '<script>
        alert("Name : ' . $student_name . '\nAttendance Count : ' . $_POST["student_attendance"] . '");
    </script>';
    }
    ?>

    <script src="js/collapse.js"></script>

</body>

</html>