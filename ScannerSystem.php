<?php
session_start();
include('partitions/_dbconnect.php');
// checking if a student has logged in
if (isset($_SESSION['student_loggedin']) && $_SESSION['student_loggedin'] == true) {
    $student_email = $_SESSION["student_email"];
    $getStudentData = "SELECT * FROM `student_profile` WHERE `student_email` ='$student_email'";
    // $profileExist = mysqli_query($conn, $getStudentData);
    $query = $pdo->prepare($getStudentData);
    $profileExist = $query->execute();

    // checking if student has created a profile or not
    // $numRows = mysqli_num_rows($profileExist);
    $numRows = $query->rowCount();
    if ($numRows == 1) {
        $student = $query->fetch(PDO::FETCH_ASSOC);

        // setting up session variables
        $_SESSION["student_id"] = $student['student_id'];
        $_SESSION["student_name"] = $student['student_name'];

        // getting student's attendance count
        $getStudent = "SELECT * FROM `student_attendance` WHERE student_id='" . $_SESSION["student_id"] . "'";
        // $result = mysqli_query($conn, $getStudent);
        $query = $pdo->prepare($getStudent);
        $result = $query->execute();
        $studentData = $query->fetch(PDO::FETCH_ASSOC);
        $_SESSION["student_attendance"] = $studentData[strtolower(date('F'))];
        
    } else {
        unset($_SESSION['student_loggedin']);
        header("Location: /Minor_Project/Student_Attendance_System/?student_email=$student_email");
    }
}
// if no one has logged in then don't allow anyone to enter the student home page
else {
    header("Location: /Minor_Project/Student_Attendance_System/");
}
?>
<html>

<head>
    <script type="text/javascript" src="assets/js/adapter.min.js"></script>
    <script type="text/javascript" src="assets/js/vue.min.js"></script>
    <script type="text/javascript" src="assets/js/instascan.min.js"></script>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <style>
        html {
            font-size: unset;
        }

        .container {
            margin-top: 2rem;
        }
    </style>
</head>

<body>
    <?php require("partitions/_headers.php") ?>
    <div class="container">

        <div class="row mt-3">
            <div class="col-md-6">
                <video id="preview" width="100%"></video>

            </div>

            <div class="col-md-6">
                <form action="student_home.php" id="attendanceUpdate" method="post" class="form-horizontal">
                    <label>SCAN QR CODE</label>
                    <input type="hidden" name="secret_key" id="secret_key" readonly placeholder="scan qrcode" class="form-control">
                    <input type="hidden" name="student_id" <?php
                    if (isset($_SESSION["student_id"])) {
                        echo "Value = " . $_SESSION["student_id"];
                    }
                    ?>>
                    <input type="hidden" name="student_attendance" <?php
                    if (isset($_SESSION["student_attendance"])) {
                        echo "Value = " . $_SESSION["student_attendance"] + 1;
                    }
                    ?>>
                    <input type="hidden" name="scan" value="scan">
                </form>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td>STUDENT ID</td>
                            <td>STUDENT Name</td>
                            <td>STUDENT ATTENDANCE COUNT</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <!-- Student ID -->
                                <?php
                                if (isset($_SESSION['student_id'])) {
                                    echo $_SESSION['student_id'];
                                }
                                ?>
                            </td>
                            <td>
                                <!-- Student Name -->
                                <?php
                                if (isset($_SESSION['student_name'])) {
                                    echo $_SESSION['student_name'];
                                }
                                ?>
                            </td>
                            <td>
                                <!-- Student Attendance -->
                                <?php
                                if (isset($_SESSION["student_attendance"])) {
                                    echo $_SESSION["student_attendance"];
                                }
                                ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
        Instascan.Camera.getCameras().then(function (cameras) {
            if (cameras.length > 0) {
                scanner.start(cameras[0]);
            } else {
                alert('No cameras found');
            }

        }).catch(function (e) {
            console.error(e);
        });

        scanner.addListener('scan', function (c) {
            document.getElementById('secret_key').value = c;
            document.getElementById('attendanceUpdate').submit(); // this line can submit a form without any submit button click.
        });
    </script>
</body>

</html>