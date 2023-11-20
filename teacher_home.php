<?php
include('partitions/_dbconnect.php');
include('classes/TeacherClass.php');
session_start();

// checking if a teacher has logged in
if (isset($_SESSION['teacher_loggedin']) && $_SESSION['teacher_loggedin'] == true) {
    $teacher = $_SESSION['teacher_obj']->getTeacherDetails();

    // handling student record edit request.
    if (isset($_POST['save_changes']) && $_POST['save_changes'] == "save_changes") {
        $editing = $_SESSION['teacher_obj']->editStudent($pdo, $_POST);
    }

    // checking for deletion request.
    if (isset($_POST['d_id']) && isset($_POST['d_email'])) {
        $deletion = $_SESSION['teacher_obj']->deleteStudent($pdo, $_POST);
    }

    // handling attendance goal setting request.
    if (isset($_POST['goal_set']) && $_POST['goal_set'] == 'goal_set') {
        $goalSetting = $_SESSION['teacher_obj']->setAttendanceGoal($pdo, $_POST);
    }

    // handling student's scanner locking-unlocking request.
    if (isset($_POST['lock_unlock_id'])) {
        $locking_unlocking = $_SESSION['teacher_obj']->lock_unlock_scanner($pdo, $_POST);
    }

    // handling semester unlock request.
    if(isset($_POST['unlock_semester'])){
        $sem_unlocked = $_SESSION['teacher_obj']->unlockSemester($pdo, $_POST['unlock_semester']);
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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css" />
    <link rel="stylesheet" href="css/headers.css">
    <link rel="stylesheet" href="css/footers.css">
    <link rel="stylesheet" href="css/leftNav.css">
    <link rel="stylesheet" href="css/teacher_home.css">
    <link rel="stylesheet" href="css/responsive.css">
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
        <div class="right-main">
            <div class="welcome-heading">
                <span class="welcome-text">
                    <?php
                    if (isset($teacher['name'])) {
                        echo "Welcome <em>'$teacher[name]'</em>";
                    } else {
                        echo "Welcome <em>'[Teacher Name]'</em>";
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
                <span class="warnings">No Unqualified Students</span>
                <span class="unread-messages">No unread messages</span>
            </section>

            <?php
            // only admin gets access to the essential buttons
            if (isset($teacher['hod'])) {
                $now = date('d');
                echo
                    '
                        <form action="teacher_home.php" id="unlock_sem" method="post" style="display: none;">
                            <input type="hidden" name="unlock_semester" value="'.$now.'">
                        </form>
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
                            <button type="button" class="essential-button" id="sem_unlock" form="unlock_sem">
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
                    if (isset($teacher['hod'])) {
                        $query = $_SESSION['teacher_obj']->getStudentByHodDepartment($pdo, $teacher['hod'])['attendance'];
                    } else {
                        $query = $_SESSION['teacher_obj']->getStudentAttendanceData($pdo);
                    }
                    $top_students = $query->execute();
                    while ($top = $query->fetch(PDO::FETCH_ASSOC)) {
                        $getPic = $_SESSION['teacher_obj']->getStudentWithID($pdo, $top['student_id'])['profile'];
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
                            if (isset($teacher['hod'])) {
                                echo '<th>Actions</th>';
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // generating top student table according to hod dept or regular teacher
                        if (isset($teacher['hod'])) {
                            $query1 = $_SESSION['teacher_obj']->getStudentByHodDepartment($pdo, $teacher['hod'])['profile'];
                        } else {
                            $query1 = $_SESSION['teacher_obj']->getStudentAllData($pdo);
                        }
                        $getResult = $query1->execute();
                        $serial_no = 1;
                        $month = strtolower(date("F"));
                        while ($student_profile = $query1->fetch(PDO::FETCH_ASSOC)) {

                            $query2 = $_SESSION['teacher_obj']->getStudentWithID($pdo, $student_profile['student_id']);
                            $attendanceResult = $query2['attendance']->execute();
                            $attendanceData = $query2['attendance']->fetch(PDO::FETCH_ASSOC);
                            if (isset($teacher['hod'])) {
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

            <!-- set attendance goal section -->
            <?php
                if (isset($teacher['hod'])) {
                    echo
                    '
                    <section class="set-goal-section" id="set_attendance_goal">
                        <p class="set-goal-text">Set Attendance Goal</p>
                        <div class="set-goal-container">
                            <div class="set-goal">
                                <form method="post" class="set-goal-input">
                                    <input type="number" name="attendance_goal" placeholder="Enter Goal (in numbers)" required>
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
            if (isset($teacher['hod'])) {
                echo
                '
                    <section class="lock_unlock_attendance_section" id="lock_attendance">
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
                <div class="search-result-container">';

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

                    $stmt = $_SESSION['teacher_obj']->getStudentByLikelyId($pdo, $_GET['lock_student_id'], $teacher['hod']);
                    $stmt->execute();
                    $month = strtolower(date("F"));
                    while ($student = $stmt->fetch(PDO::FETCH_ASSOC)) {

                        $attendance = $_SESSION['teacher_obj']->getStudentWithID($pdo, $student['student_id'])['attendance'];
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
        const sem_unlock_btn = document.getElementById('sem_unlock');

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

        sem_unlock_btn.addEventListener('click',()=>{
            if(confirm("Are You Sure? Press ok to continue, cancel to back!")){
                document.getElementById('unlock_sem').submit();
            }
        });

        document.getElementById("btn-close").addEventListener("click", () => {
            document.querySelector(".edit-modal-container .edit-modal-wrapper").style.transform = "scale(0)";
            document.querySelector(".edit-modal-container").style.opacity = "0";
            document.querySelector(".edit-modal-container").style.zIndex = "-100";
        });
    </script>
    <script src="js/collapse.js"></script>
    <script src="js/t_themeToggle.js"></script>

    <script>
        <?php
        if (isset($editing)) {
            switch ($editing) {
                case 1:
                    echo 'alert("Profile Updated Successfully!");
                    window.location.href = "/Minor_Project/Student_Attendance_System/teacher_home.php";';
                    break;
                case 2:
                    echo 'alert("Failed to Edit Student Attendance!");';
                    break;
                case 3:
                    echo 'alert("Failed to Edit Student Profile!");';
                    break;
                default:
                    break;
            }
        }
        if (isset($deletion)) {
            switch ($deletion) {
                case 1:
                    echo 'alert("Student Deleted Successfully!");
                        window.location.href = "/Minor_Project/Student_Attendance_System/teacher_home.php";';
                    break;
                case 2:
                    echo 'alert("Failed to Delete Student\'s Messages!");
                        window.location.href = "/Minor_Project/Student_Attendance_System/teacher_home.php";';
                    break;

                case 3:
                    echo 'alert("Failed to Delete Student\'s Profile!");
                        window.location.href = "/Minor_Project/Student_Attendance_System/teacher_home.php";';
                    break;

                case 4:
                    echo 'alert("Failed to Delete Student\'s Attendance Details!");
                        window.location.href = "/Minor_Project/Student_Attendance_System/teacher_home.php";';
                    break;
                case 5:
                    echo 'alert("Failed to Delete Student\'s Registration Details!");
                        window.location.href = "/Minor_Project/Student_Attendance_System/teacher_home.php";';
                    break;
                default:
                    break;
            }
        }
        if (isset($goalSetting)) {
            switch ($goalSetting) {
                case 1:
                    echo 'alert("Attendance Goal Set Successfully!");
                window.location.href = "/Minor_Project/Student_Attendance_System/teacher_home.php";';
                    break;
                case -1:
                    echo 'alert("Failed to Set Attendance Goal!");
                window.location.href = "/Minor_Project/Student_Attendance_System/teacher_home.php";';
                    break;
                default:
                    break;
            }
        }

        if (isset($locking_unlocking)) {
            switch ($locking_unlocking) {
                case 1:
                    echo 'alert("Scanner Locked Successfully!");
                window.location.href = "/Minor_Project/Student_Attendance_System/teacher_home.php";';
                    break;
                case 2:
                    echo 'alert("Failed to Lock Scanner!");
                window.location.href = "/Minor_Project/Student_Attendance_System/teacher_home.php";';
                    break;
                case 3:
                    echo 'alert("Scanner Unlocked Successfully!");
                window.location.href = "/Minor_Project/Student_Attendance_System/teacher_home.php";';
                    break;
                case 4:
                    echo 'alert("Failed to Unlock Scanner!");
                window.location.href = "/Minor_Project/Student_Attendance_System/teacher_home.php";';
                    break;
                default:
                    break;
            }
        }
        if(isset($sem_unlocked)){
            switch ($sem_unlocked) {
                case 1:
                    echo 'alert("Semester Unlocked Successfully! It will last for 15 days only!");
                    window.location.href = "/Minor_Project/Student_Attendance_System/teacher_home.php";';
                    break;
                case 2:
                    echo 'alert("Date is over! Contact with Dev-teams");';
                    break;
                case -1:
                    echo 'alert("Failed to Unlock Semester! Try again..");';
                    break;
                default:
                    break;
            }
        }
        ?>
    </script>
    <script>
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
</body>

</html>