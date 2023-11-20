<?php
include('partitions/_dbconnect.php');
include('classes/StudentClass.php');
session_start();

// checking if a student has logged in
if (isset($_SESSION['student_loggedin']) && $_SESSION['student_loggedin'] == true) {
    $student = $_SESSION['student_obj']->getStudentDetails();
}
// if no one has logged in then don't allow anyone to enter the student home page
else {
    header("Location: /Student_Attendance_System/");
}
?>
<html>

<head>
    <script type="text/javascript" src="assets/js/adapter.min.js"></script>
    <script type="text/javascript" src="assets/js/vue.min.js"></script>
    <script type="text/javascript" src="assets/js/instascan.min.js"></script>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <style>
        html {
            font-size: unset;
        }

        body {
            color: var(--clr-btn-text-logo);
            background-color: var(--clr-bgcolor);
            transition: 0.6s;
        }

        .container {
            margin-top: 2rem;
        }

        .table-bordered {
            border: 1px solid var(--clr-border);
        }

        .table-bordered>tbody>tr>td,
        .table-bordered>tbody>tr>th,
        .table-bordered>tfoot>tr>td,
        .table-bordered>tfoot>tr>th,
        .table-bordered>thead>tr>td,
        .table-bordered>thead>tr>th {
            border: 1px solid var(--clr-border);
        }

        .table-bordered>tbody>tr>td,
        .table-bordered>tbody>tr>th,
        .table-bordered>tfoot>tr>td,
        .table-bordered>tfoot>tr>th,
        .table-bordered>thead>tr>td,
        .table-bordered>thead>tr>th {
            border: 1px solid var(--clr-border);
        }

        #videoContainer {
            padding: 10px !important;
            border: 5px solid var(--clr-border);
            border-radius: 1rem;
        }
    </style>
    <link rel="stylesheet" href="css/headers.css">
    <link rel="stylesheet" href="css/footers.css">
    <link rel="stylesheet" href="css/leftNav.css">
    <link rel="stylesheet" href="css/responsive.css">
</head>

<body>
    <?php require("partitions/_headers.php") ?>
    <div class="container">

        <div class="row mt-3">
            <div class="col-md-6" id="videoContainer">
                <video id="preview" width="100%"></video>

            </div>

            <div class="col-md-6">
                <form action="student_home.php" id="attendanceUpdate" method="post" class="form-horizontal">
                    <label>SCAN QR CODE</label>
                    <input type="hidden" name="secret_key" id="secret_key" readonly placeholder="scan qrcode" class="form-control">
                    <input type="hidden" name="scan" value="scan">
                </form>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td>STUDENT Name</td>
                            <td>STUDENT ATTENDANCE COUNT</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <!-- Student Name -->
                                <?php
                                if (isset($student['name'])) {
                                    echo $student['name'];
                                }
                                ?>
                            </td>
                            <td>
                                <!-- Student Attendance -->
                                <?php
                                if (isset($student["attendance"])) {
                                    echo $student["attendance"];
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
        <?php
        if (isset($student['is_locked']) && $student['is_locked']) {
            echo 'alert("Cannot Scan! Your HOD has locked your scanner!!");
    document.location.href = "/Student_Attendance_System/student_home.php";';
        }
        ?>
        let scanner = new Instascan.Scanner({
            video: document.getElementById('preview')
        });
        Instascan.Camera.getCameras().then(function(cameras) {
            if (cameras.length > 0) {
                scanner.start(cameras[0]);
            } else {
                alert('No cameras found');
            }

        }).catch(function(e) {
            console.error(e);
        });

        scanner.addListener('scan', function(c) {
            document.getElementById('secret_key').value = c;
            document.getElementById('attendanceUpdate').submit(); // this line can submit a form without any submit button click.
        });
    </script>

    <script src="js/sc_themeToggle.js"></script>
</body>

</html>