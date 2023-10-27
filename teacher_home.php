<?php
session_start();
include('partitions/_dbconnect.php');

// checking if a teacher has logged in
if (isset($_SESSION['teacher_loggedin']) && $_SESSION['teacher_loggedin'] == true) {
    $teacher_email = $_SESSION["teacher_email"];
    $getTeacherData = "SELECT * FROM `teacher_profile` WHERE `teacher_email`='$teacher_email'";
    // $result = mysqli_query($conn, $getTeacherData);
    $query = $pdo->prepare($getTeacherData);
    $result = $query->execute();

    // checking if teacher has created a profile or not
    // $numRows = mysqli_num_rows($result);
    $numRows = $query->rowCount();
    if ($numRows == 1) {
        // $teacher = mysqli_fetch_assoc($result);
        $teacher = $query->fetch(PDO::FETCH_ASSOC);
        $_SESSION["teacher_id"] = $teacher['teacher_id'];
        $_SESSION["teacher_name"] = $teacher['teacher_name'];
        if (isset($teacher['hod_department'])) {
            $_SESSION["teacher_dept"] = $teacher['hod_department'];
        }
        if (isset($teacher['profile_picture'])) {
            $_SESSION["teacher_picture"] = $teacher['profile_picture'];
        }
    } else {
        unset($_SESSION['teacher_loggedin']);
        $_SESSION['teacher_profile_email'] = $teacher_email;
        header("Location: /Minor_Project/Student_Attendance_System/teacherProfile.php");
    }

    // handling student record edit request
    if (isset($_POST['save_changes']) && $_POST['save_changes'] == "save_changes") {
        $id = $_POST['s_id'];
        $name = $_POST['s_name'];
        $email = $_POST['s_email'];
        $phone = $_POST['s_phone'];
        $attendance = $_POST['s_attendance'];
        $stream = $_POST['s_stream'];
        $sem = $_POST['s_sem'];
        $month = strtolower(date('F'));
        $profileUpdate = "UPDATE `student_profile` SET `student_name`='$name',`student_phone`='$phone',`student_email`='$email',`student_stream`='$stream',`student_semester`='$sem' WHERE `student_id`='$id'";
        $update = $pdo->prepare($profileUpdate);
        $result = $update->execute();
        if ($result) {
            $attendanceUpdate = "UPDATE `student_attendance` SET `student_name`='$name',`student_stream`='$stream',`$month`='$attendance' WHERE `student_id`='$id'";
            $updatation = $pdo->prepare($attendanceUpdate);
            $updated = $updatation->execute();
        }
    }

    // checking for deletion request
    if (isset($_POST['d_id']) && isset($_POST['d_email'])) {
        $delete_id = $_POST['d_id'];
        $delete_email = $_POST['d_email'];
        $delete_query1 = "DELETE FROM `student_registration` WHERE `student_email` = '$delete_email'";
        // $deletion = mysqli_query($conn, $delete_query1);
        $query = $pdo->prepare($delete_query1);
        $deletion = $query->execute();
        if ($deletion) {
            $delete_query2 = "DELETE FROM `student_attendance` WHERE `student_id` = '$delete_id'";
            // $deletion = mysqli_query($conn, $delete_query2);
            $query = $pdo->prepare($delete_query2);
            $deletion = $query->execute();
            if ($deletion) {
                $delete_query3 = "DELETE FROM `student_profile` WHERE `student_id` = '$delete_id'";
                // $deletion = mysqli_query($conn, $delete_query3);
                $query = $pdo->prepare($delete_query3);
                $deletion = $query->execute();
                if ($deletion) {
                    $delete_query4 = "DELETE FROM `messages` WHERE `student_id` = '$delete_id'";
                    // $deletion = mysqli_query($conn, $delete_query3);
                    $query = $pdo->prepare($delete_query4);
                    $deletion = $query->execute();
                }
            }
        }
    }

    if (isset($_POST['lock_unlock_id'])) {
        $id = $_POST['lock_unlock_id'];
        if ($_POST['lock_unlock'] == 'lock') {
            $query = "UPDATE `student_attendance` SET `is_locked` = '1' WHERE `student_id` = '$id'";
            $stmt = $pdo->prepare($query);
            $locked = $stmt->execute();
            // header("Location: /Minor_Project/Student_Attendance_System/teacher_home.php");
        } else if ($_POST['lock_unlock'] == 'unlock') {
            $query = "UPDATE `student_attendance` SET `is_locked` = '0' WHERE `student_id` = '$id'";
            $stmt = $pdo->prepare($query);
            $unlocked = $stmt->execute();
            // header("Location: /Minor_Project/Student_Attendance_System/teacher_home.php");
        }
    }
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
    <title>Home Page (Teacher)</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <link rel="stylesheet" href="css/teacher_home.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto&display=swap');

        .top-students {
            padding-bottom: 30px;
            border-bottom: 1px solid var(--clr-border);
        }

        .top-students .heading-text {
            margin: 15px;
            text-align: center;
            font-size: 1.5rem;
            color: var(--clr-btn-text-logo);
        }

        .top-students .students-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 3.5rem;
            padding-top: 5px;
        }

        .top-students .student {
            width: 16vw;
            height: 36vh;
            border-radius: 1.3rem;
            border: 3px solid var(--clr-border);
            box-shadow: 0 0 8px 1px var(--clr-border);
            /* background-color: red; */
        }

        .top-students .student .profile-image {
            width: 100px;
            height: 100px;
            margin: auto;
            margin-top: 20px;
            border-radius: 50%;
            overflow: hidden;
            background-color: red;
        }

        .top-students .student .profile-image img {
            width: 100%;
        }

        .top-students .student .student-name {
            color: var(--clr-btn-text-logo);
            font-size: 1.5rem;
            text-align: center;
            margin-top: 1rem;
        }

        .top-students .student .attendance-count {
            color: var(--clr-btn-text-logo);
            font-size: 1.5rem;
            text-align: center;
            margin-top: 1rem;
        }

        .more-students-data {
            color: white;
            border-bottom: 1px solid var(--clr-border);
            /* background: #b49f9f; */
            /* display: flex;
            justify-content: center;
            align-items: center; */
            /* margin-top: 1%; */
            /* padding-bottom: 1%; */
        }

        .more-students-data .more-data-heading {
            margin: 15px;
            text-align: center;
            font-size: 1.5rem;
            color: var(--clr-btn-text-logo);
        }

        .more-students-data .dataTables_wrapper {
            position: relative;
            clear: both;
            width: 90%;
            margin: auto;
            padding-bottom: 3vh;
        }

        .more-students-data .dataTables_wrapper .dataTables_length label select option {
            color: black;
        }

        .more-students-data .dataTables_wrapper .dataTables_filter input {
            border: 1px solid white;
            margin-left: 10px;
            border-radius: 10px;
            padding-left: 10px;
        }

        .more-students-data #action-td {
            display: flex;
            align-items: center;
        }

        .more-students-data #students_table .table-btn-edit,
        #students_table .table-btn-delete {
            border-radius: 10px;
            font-size: 16px;
            margin-left: 10px;
            padding: 5px 8px;
            outline: none;
            border: 2px solid var(--clr-border);
            background: transparent;
            color: var(--clr-btn-text-logo);
            cursor: pointer;
            transition: 0.3s ease-in;
            overflow: hidden;
        }

        #students_table .table-btn-edit:hover,
        #students_table .table-btn-delete:hover {
            color: white;
            background: var(--clr-hover-color);
            box-shadow: 0 0 8px 0px var(--clr-border);
        }

        #students_table .table-btn-edit:active,
        #students_table .table-btn-delete:active {
            transform: scale(0.8);
        }

        table.dataTable.no-footer {
            border-bottom: 1px solid var(--clr-border);
        }

        .edit-modal-container {
            position: fixed;
            width: 98.8vw;
            height: 100vh;
            background: #000000c2;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: -100;
            opacity: 0;
            transition: 0.6s;
        }

        .edit-modal-container .edit-modal-wrapper {
            width: 40%;
            height: 80%;
            background: white;
            border: 2px solid rgba(255, 255, 255, .5);
            border-radius: 40px;
            border: 2px solid black;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            transition: 0.5s;
            transform: scale(0);
        }

        .edit-modal-container .edit-modal-wrapper::-webkit-scrollbar {
            width: 5px;
        }

        .modal-edit {
            width: 80%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .modal-edit h2 {
            font-size: 2rem;
            font-family: 'Roboto', sans-serif;
            margin-bottom: 7px;
        }

        .modal-edit form {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            width: 100%;
        }

        .modal-edit form label {
            font-size: 1.5rem;
            font-family: 'Roboto', sans-serif;
            ;
            margin-left: 1vw;
            margin-bottom: 7px;
        }

        .modal-edit .inputs {
            width: 100%;
            height: 30px;
            margin-bottom: 5px;
            outline: none;
            font-size: 1.3rem;
            border: 1px solid black;
            border-radius: 10px;
            text-align: center;
        }

        .btn-container {
            width: 100%;
            display: flex;
            justify-content: space-around;
            align-items: center;
        }

        #btn-submit,
        #btn-close {
            margin: 10px 0px;
            width: 45%;
            height: 45px;
            background-color: #003e70;
            border: none;
            outline: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 1.4rem;
            color: #fff;
            font-weight: 500;
            transition: 0.5s;
        }


        #btn-submit:hover,
        #btn-close:hover {
            background-color: #00325a;
        }

        .set-goal-section {
            border-bottom: 1px solid var(--clr-border);
        }

        .set-goal-text {
            margin: 15px;
            text-align: center;
            font-size: 1.5rem;
            color: var(--clr-btn-text-logo);
        }

        .set-goal-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 3vh;
        }

        .set-goal-input {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 1.2rem;
        }

        .set-goal-input input {
            width: 17vw;
            height: 8vh;
            border: 3px solid var(--clr-border);
            border-radius: 15px;
            color: var(--clr-btn-text-logo);
            outline: none;
            font-size: 1.7em;
            text-align: center;
            background: transparent;
        }

        .set-goal-input input::-webkit-outer-spin-button,
        .set-goal-input input::-webkit-inner-spin-button {
            appearance: none;
            -webkit-appearance: none;
        }

        .set-goal-input button {
            background: transparent;
            outline: none;
            border: 2px solid var(--clr-border);
            color: var(--clr-btn-text-logo);
            width: 4vw;
            height: 6vh;
            border-radius: 1rem;
            cursor: pointer;
        }

        .set-goal-input button:hover {
            background-color: var(--clr-hover-color);
            color: white;
            box-shadow: 0 0 8px 0px var(--clr-border);
        }

        .set-goal-input button:active {
            transform: scale(0.95);
        }

        .set-goal-input span {
            font-size: 2rem;
        }

        .search-input {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 1.2rem;
        }

        .search-input input {
            width: 17vw;
            height: 8vh;
            border: 3px solid var(--clr-border);
            border-radius: 15px;
            color: var(--clr-btn-text-logo);
            outline: none;
            font-size: 1.7em;
            text-align: center;
            background: transparent;
        }

        .search-input button {
            background: transparent;
            outline: none;
            border: 2px solid var(--clr-border);
            font-size: 1.2rem;
            color: var(--clr-btn-text-logo);
            width: 8vw;
            height: 6vh;
            border-radius: 1rem;
            cursor: pointer;
        }

        .search-input button:hover {
            background-color: var(--clr-hover-color);
            color: white;
            box-shadow: 0 0 8px 0px var(--clr-border);
        }

        .search-input button:active {
            transform: scale(0.95);
        }

        #search_result_length,
        #search_result_filter,
        #search_result_paginate {
            display: none;
        }

        #lock-unlock {
            display: flex;
            align-items: center;
        }

        #lock-unlock .table-btn-lock,
        #lock-unlock .table-btn-unlock {
            border-radius: 10px;
            font-size: 16px;
            margin-left: 10px;
            padding: 5px 8px;
            outline: none;
            border: 2px solid var(--clr-border);
            background: transparent;
            color: var(--clr-btn-text-logo);
            cursor: pointer;
            transition: 0.3s ease-in;
            overflow: hidden;
        }

        #lock-unlock .table-btn-lock:hover,
        #lock-unlock .table-btn-unlock:hover {
            color: white;
            background: var(--clr-hover-color);
            box-shadow: 0 0 8px 0px var(--clr-border);
        }

        #lock-unlock .table-btn-lock:active,
        #lock-unlock .table-btn-unlock:active {
            transform: scale(0.8);
        }
    </style>
</head>

<body>
    <!-- edit modal structure starts here -->
    <div class="edit-modal-container">
        <div class="edit-modal-wrapper">
            <div class="modal-edit">
                <h2>Edit Student Details</h2>
                <form autocomplete="off" id="edit-form" method="post">
                    <input type="hidden" name="s_id" required readonly>
                    <label for="name">Student Name</label>
                    <input class="inputs" type="text" name="s_name" id="name" placeholder="Edit Student Name" required>
                    <label for="email">Student Email</label>
                    <input class="inputs" type="email" name="s_email" id="email" placeholder="Edit Student Email" required>
                    <label for="phone">Student Phone</label>
                    <input class="inputs" type="tel" name="s_phone" id="phone" placeholder="Edit Student Phone Number" required>
                    <label for="attendance">Student Attendance</label>
                    <input type="number" class="inputs" name="s_attendance" id="attendance" placeholder="Edit Student Attendance" required>
                    <label for="s_stream">Student Stream</label>
                    <input type="text" class="inputs" name="s_stream" id="s_stream" placeholder="Edit Student Stream" required>
                    <label for="s_sem">Student Semester</label>
                    <input type="number" class="inputs" name="s_sem" id="s_sem" placeholder="Edit Student Semester" required>
                    <div class="btn-container">
                        <button type="button" id="btn-close">Close</button>
                        <button type="submit" name="save_changes" value="save_changes" id="btn-submit">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- edit modal structure ends here -->

    <?php require("partitions/_headers.php") ?>
    <div class="container">
        <?php require("partitions/_leftNavOptions.php") ?>
        <div class="container__rightMain">
            <div class="welcome-heading">
                <span class="welcome-text">
                    Welcome <em>'<?php
                                    if (isset($_SESSION["teacher_name"])) {
                                        echo $_SESSION["teacher_name"];
                                    } else {
                                        echo "[Teacher Name]";
                                    }
                                    ?>'
                    </em>
                </span>
                <a href="logout.php" class="logout" id="logout">
                    <span class="material-symbols-outlined">
                        power_settings_new
                    </span>
                    <p>Logout</p>
                </a>
            </div>
            <section class="nofifications">
                <span class="warnings">No Unqualified Students</span>
                <span class="unread-messages">No unread messages</span>
            </section>

            <?php
            // only admin gets access to the essential buttons
            if (isset($_SESSION['teacher_dept'])) {
                echo
                '
                        <section class="essential_buttons">
                            <a class="essential-button" href="#more_students_table">
                                <span class="material-symbols-outlined">
                                    edit_note
                                </span>
                                <span class="essential-btn-text">Edit Record</span>
                            </a>
                            <a class="essential-button" href="#set_attendance_goal">
                                <span class="material-symbols-outlined">
                                    add_task
                                </span>
                                <span class="essential-btn-text">Set Goal</span>
                            </a>
                            <a href="#lock_attendance" class="essential-button">
                                <span class="material-symbols-outlined">
                                    encrypted
                                </span>
                                <span class="essential-btn-text">Lock Attendance</span>
                            </a>
                            <button class="essential-button" style="width: 120px;">
                                <span class="material-symbols-outlined">
                                    lock_open_right
                                </span>
                                <span class="essential-btn-text">Unlock Semester Selection</span>
                            </button>
                        </section>
                    ';
            }
            ?>

            <section class="top-students">
                <p class="heading-text">Top Students in this Month</p>
                <div class="students-container">
                    <?php
                    if (isset($_SESSION['teacher_dept'])) {
                        $topStudentsSql = "SELECT `student_id`, `student_name`, `" . strtolower(date('F')) . "` FROM `student_attendance` WHERE `student_stream`='$_SESSION[teacher_dept]' ORDER BY `" . strtolower(date('F')) . "` DESC LIMIT 4";
                    } else {
                        $topStudentsSql = "SELECT `student_id`, `student_name`, `" . strtolower(date('F')) . "` FROM `student_attendance` ORDER BY `" . strtolower(date('F')) . "` DESC LIMIT 4";
                    }
                    // $top_students = mysqli_query($conn, $topStudentsSql);
                    $query = $pdo->prepare($topStudentsSql);
                    $top_students = $query->execute();
                    // while ($top = mysqli_fetch_assoc($top_students)) {
                    while ($top = $query->fetch(PDO::FETCH_ASSOC)) {
                        $getStudentPic = "SELECT `profile_picture` FROM `student_profile` WHERE `student_id`='$top[student_id]'";
                        $getPic = $pdo->prepare($getStudentPic);
                        $getPic->execute();
                        $picture = $getPic->fetch(PDO::FETCH_ASSOC);
                        echo
                        '  <div class="student">
                                    <div class="profile-image">
                                        <img src="../profile_pictures/' . $picture['profile_picture'] . '" alt="profile image">
                                    </div>
                                    <p class="student-name">' . $top['student_name'] . '</p>
                                    <p class="attendance-count">Attendance Count: <span>' . $top[strtolower(date('F'))] . '</span></p>
                                </div>
                            ';
                    }
                    ?>
                </div>
            </section>
            <section class="more-students-data" id="more_students_table">
                <p class="more-data-heading">All Students Data</p>
                <table id="students_table" class="display">
                    <thead>
                        <tr>
                            <th>Sno</th>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Attendance</th>
                            <th>Stream</th>
                            <th>Semester</th>
                            <?php
                            if (isset($_SESSION['teacher_dept'])) {
                                echo '<th>Actions</th>';
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // generating top student table according to hod dept or regular teacher
                        if (isset($_SESSION["teacher_dept"])) {
                            $getStudentData = "SELECT * FROM `student_profile` WHERE `student_stream`='$_SESSION[teacher_dept]'";
                        } else {
                            $getStudentData = "SELECT * FROM `student_profile`";
                        }
                        // $getResult = mysqli_query($conn, $getStudentData);
                        $query1 = $pdo->prepare($getStudentData);
                        $getResult = $query1->execute();
                        $serial_no = 1;
                        $month = strtolower(date("F"));
                        while ($student_profile = $query1->fetch(PDO::FETCH_ASSOC)) {
                            $getStudentAttendance = "SELECT `$month` FROM `student_attendance` WHERE `student_id` = '$student_profile[student_id]'";
                            $query2 = $pdo->prepare($getStudentAttendance);
                            $attendanceResult = $query2->execute();
                            $attendanceData = $query2->fetch(PDO::FETCH_ASSOC);
                            if (isset($_SESSION["teacher_dept"])) {
                                echo
                                "   <tr>
                                            <td>" . $serial_no . "</td>
                                            <td>" . $student_profile['student_id'] . "</td>
                                            <td>" . $student_profile['student_name'] . "</td>
                                            <td>" . $student_profile['student_phone'] . "</td>
                                            <td>" . $student_profile['student_email'] . "</td>
                                            <td>" . $attendanceData[$month] . "</td>
                                            <td>" . $student_profile['student_stream'] . "</td>
                                            <td>" . $student_profile['student_semester'] . "</td>
                                            <td id='action-td'>
                                                <button class='table-btn-edit' type='button' title='Edit'>
                                                    Edit
                                                </button>
                                                <button class='table-btn-delete' type='button' title='Delete'>
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>
                                    ";
                            } else {
                                echo
                                "   <tr>
                                            <td>" . $serial_no . "</td>
                                            <td>" . $student_profile['student_id'] . "</td>
                                            <td>" . $student_profile['student_name'] . "</td>
                                            <td>" . $student_profile['student_phone'] . "</td>
                                            <td>" . $student_profile['student_email'] . "</td>
                                            <td>" . $attendanceData[$month] . "</td>
                                            <td>" . $student_profile['student_stream'] . "</td>
                                            <td>" . $student_profile['student_semester'] . "</td>
                                        </tr>
                                    ";
                            }
                            $serial_no += 1;
                        }
                        ?>
                    </tbody>
                </table>
                <form method="post" style="transform: scale(0);" id="delete_form">
                    <input type="hidden" name="d_id" id="d_id">
                    <input type="hidden" name="d_email" id="d_email">
                </form>
            </section>
            <?php
            if (isset($_SESSION['teacher_dept'])) {
                echo
                '
                <section class="set-goal-section" id="set_attendance_goal">
                    <p class="set-goal-text">Set Attendance Goal</p>
                    <div class="set-goal-container">
                        <div class="set-goal">
                            <form method="post" class="set-goal-input">
                                <input type="number" name="attendance_goal" placeholder="Enter Goal (%)" required>
                                <button type="submit" name="goal_set" value="goal_set">
                                    <span class="material-symbols-outlined">done</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </section>
            ';
            }
            ?>

            <?php
            if (isset($_SESSION['teacher_dept'])) {
                echo
                '
                    <section class="set-goal-section" id="lock_attendance">
                <p class="set-goal-text">Lock Attendance of a Student</p>
                <div class="set-goal-container">
                    <div class="set-goal">
                        <form method="get" class="search-input">
                            <input type="search" name="lock_student_id" ';

                if (isset($_GET['lock_student_search'])) {
                    echo "value='$_GET[lock_student_id]'";
                }
                echo 'placeholder="Enter Student ID" required>
                            <button type="submit" name="lock_student_search">Search</button>
                        </form>
                    </div>
                </div>
                <div class="search-result-container" style="width: 90%; margin: auto; margin-bottom: 1rem; color: white;">';

                if (isset($_GET["lock_student_search"])) {
                    echo
                    "
                            <table id='search_result' class='display'>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Attendance</th>
                                        <th>Stream</th>
                                        <th>Semester</th>
                                        <th>Lock/Unlock</th>
                                    </tr>
                                </thead>
                                <tbody>
                        ";

                    $search = $_GET['lock_student_id'];
                    $getStudent = "SELECT * FROM `student_profile` WHERE `student_id` LIKE '%$search%' AND `student_stream` = '$_SESSION[teacher_dept]'";
                    $stmt = $pdo->prepare($getStudent);
                    $stmt->execute();
                    $month = strtolower(date("F"));
                    while ($student = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $getAttendance = "SELECT `$month`,`is_locked` FROM `student_attendance` WHERE `student_id` = '$student[student_id]'";
                        $attendance = $pdo->prepare($getAttendance);
                        $attendanceResult = $attendance->execute();
                        $attendanceRecord = $attendance->fetch(PDO::FETCH_ASSOC);
                        if ($attendanceRecord['is_locked']) {
                            echo
                            "
                                    <tr>
                                        <td>$student[student_id]</td>
                                        <td>$student[student_name]</td>
                                        <td>$student[student_phone]</td>
                                        <td>$student[student_email]</td>
                                        <td>$attendanceRecord[$month]</td>
                                        <td>$student[student_stream]</td>
                                        <td>$student[student_semester]</td>
                                        <td id='lock-unlock'>
                                            <button class='table-btn-unlock' type='button' title='Unlock'>
                                                Unlock
                                            </button>
                                        </td>
                                    </tr>
                                    ";
                        } else {
                            echo
                            "
                                    <tr>
                                        <td>$student[student_id]</td>
                                        <td>$student[student_name]</td>
                                        <td>$student[student_phone]</td>
                                        <td>$student[student_email]</td>
                                        <td>$attendanceRecord[$month]</td>
                                        <td>$student[student_stream]</td>
                                        <td>$student[student_semester]</td>
                                        <td id='lock-unlock'>
                                            <button class='table-btn-lock' type='button' title='Lock'>
                                                Lock
                                            </button>
                                        </td>
                                    </tr>
                                    ";
                        }
                    }

                    echo
                    "
                                </tbody>
                            </table>
                        ";
                }
                echo '</div>
                <form method="post" style="display: none;" id="lock_unlock_form">
                    <input type="hidden" name="lock_unlock_id">
                    <input type="hidden" name="lock_unlock">
                </form>
            </section>
                    ';
            }
            ?>
        </div>
    </div>

    <?php require("partitions/_footers.php") ?>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>
    <script>
        const data = document.getElementById("students_table");
        const search = document.getElementById("search_result");
        $(document).ready(function() {
            $(data).DataTable();
        });

        $(document).ready(function() {
            $(search).DataTable();
        });
    </script>

    <script>
        const edit_buttons = document.querySelectorAll('.table-btn-edit');
        const delete_buttons = document.querySelectorAll('.table-btn-delete');
        const lock_buttons = document.querySelectorAll('.table-btn-lock');
        const unlock_buttons = document.querySelectorAll('.table-btn-unlock');
        const editForm = document.getElementById("edit-form");

        <?php
        if (isset($updated) && $updated) {
            echo 'alert("Profile Updated Successfully!");
                window.location.href = "/Minor_Project/Student_Attendance_System/teacher_home.php";';
        }
        if (isset($locked) && $locked) {
            echo 'alert("Attendance Locked Successfully!");
            window.location.href = "/Minor_Project/Student_Attendance_System/teacher_home.php";';
        }
        if (isset($unlocked) && $unlocked) {
            echo 'alert("Attendance Unlocked Successfully!");
            window.location.href = "/Minor_Project/Student_Attendance_System/teacher_home.php";';
        }
        ?>

        edit_buttons.forEach(element => {
            element.addEventListener('click', (e) => {
                document.querySelector(".edit-modal-container").style.position = "fixed";
                document.querySelector(".edit-modal-container").style.opacity = "1";
                document.querySelector(".edit-modal-container .edit-modal-wrapper").style.transform = "scale(1)";
                document.querySelector(".edit-modal-container").style.zIndex = "100";

                // adding the values to the edit form
                const targetElement = e.target.parentNode.parentNode;
                const id = targetElement.getElementsByTagName('td')[1].innerText;
                const name = targetElement.getElementsByTagName('td')[2].innerText;
                const phone = targetElement.getElementsByTagName('td')[3].innerText;
                const email = targetElement.getElementsByTagName('td')[4].innerText;
                const attendance = targetElement.getElementsByTagName('td')[5].innerText;
                const stream = targetElement.getElementsByTagName('td')[6].innerText;
                const sem = targetElement.getElementsByTagName('td')[7].innerText;
                editForm.getElementsByTagName('input')[0].value = id;
                editForm.getElementsByTagName('input')[1].value = name;
                editForm.getElementsByTagName('input')[2].value = email;
                editForm.getElementsByTagName('input')[3].value = phone;
                editForm.getElementsByTagName('input')[4].value = attendance;
                editForm.getElementsByTagName('input')[5].value = stream;
                editForm.getElementsByTagName('input')[6].value = sem;
            });
        });

        delete_buttons.forEach(element => {
            element.addEventListener('click', (e) => {
                if (confirm("Click Ok to confirm")) {
                    const targetElement = e.target.parentNode.parentNode;
                    const id = targetElement.getElementsByTagName('td')[1].innerText;
                    const email = targetElement.getElementsByTagName('td')[4].innerText;
                    document.getElementById('delete_form').getElementsByTagName('input')[0].value = id;
                    document.getElementById('delete_form').getElementsByTagName('input')[1].value = email;
                    document.getElementById('delete_form').submit();
                }
            });
        });

        lock_buttons.forEach(element => {
            element.addEventListener('click', (e) => {
                if (confirm("Click Ok to confirm")) {
                    const targetElement = e.target.parentNode.parentNode;
                    const id = targetElement.getElementsByTagName('td')[0].innerText;
                    document.getElementById('lock_unlock_form').getElementsByTagName('input')[0].value = id;
                    document.getElementById('lock_unlock_form').getElementsByTagName('input')[1].value = 'lock';
                    document.getElementById('lock_unlock_form').submit();
                }
            })
        });

        unlock_buttons.forEach(element => {
            element.addEventListener('click', (e) => {
                if (confirm("Click Ok to confirm")) {
                    const targetElement = e.target.parentNode.parentNode;
                    const id = targetElement.getElementsByTagName('td')[0].innerText;
                    document.getElementById('lock_unlock_form').getElementsByTagName('input')[0].value = id;
                    document.getElementById('lock_unlock_form').getElementsByTagName('input')[1].value = 'unlock';
                    document.getElementById('lock_unlock_form').submit();
                }
            })
        });

        document.getElementById("btn-close").addEventListener("click", () => {
            document.querySelector(".edit-modal-container .edit-modal-wrapper").style.transform = "scale(0)";
            document.querySelector(".edit-modal-container").style.opacity = "0";
            document.querySelector(".edit-modal-container").style.zIndex = "-100";
        });
    </script>
</body>

</html>