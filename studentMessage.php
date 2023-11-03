<?php
require("partitions/_dbconnect.php");
require('classes/StudentClass.php');
// require('classes/TeacherClass.php');
require('classes/MessageClass.php');
session_start();

// checking if a student or teacher has logged in
// if not then redirect to index page
if (!isset($_SESSION['student_loggedin']) && !isset($_SESSION['student_obj'])) {
    header("Location: /Minor_Project/Student_Attendance_System/");
}

// getting all the messages
$message = new Message();
$query = $message->getMessages($pdo);
$query->execute();


// handling message posting request for student
if (isset($_SESSION['student_obj']) && isset($_SESSION['student_loggedin'])) {
    $student = $_SESSION['student_obj']->getStudentDetails();
    if (isset($_POST["messagebtn"]) && $_POST["messagebtn"] === "messagebtn") {
        $sent = $_SESSION['student_obj']->sendMessage($pdo, $_POST);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message Portal</title>
    <link rel="stylesheet" href="css/message.css">
</head>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

<body>
    <?php require("partitions/_headers.php") ?>
    <div class="container">
        <?php require("partitions/_leftNavOptions.php") ?>
        <div class="right-main">
            <div class="mainContainer">
                <nav>
                    <img class="logo" src="https://i.pinimg.com/474x/96/04/48/96044861e221c692bd6d29ecc360bbca.jpg" alt="chat logo">
                    <h1 style="transition: 0.3s ease-in;">Messages</h1>
                </nav>
                <div class="messageContainer">
                    <p class="alert-text">Welcome to Student_Attendance_System Chat Portal</p>
                    <?php
                    if (isset($query)) {
                        // initial variable that checks if messages exists or not
                        $messageExists = false;
                        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                            $messageExists = true;
                            if (isset($row["student_message"])) {

                                $msg = $message->getStudentMessage($pdo, $row['student_id'], $row["student_message"]);
                                echo $msg;

                                // delete this part
                                // $student = "SELECT `student_name` FROM `student_profile` WHERE `student_id`='$row[student_id]'";
                                // $gettingStudent = $pdo->prepare($student);
                                // $gettingStudent->execute();
                                // $student_data = $gettingStudent->fetch(PDO::FETCH_ASSOC);
                                // echo '  <div class="message right">
                                //             <p class="sender-name"><b style="transition: 0.3s ease-in;">' . $student_data["student_name"] . '</b></p>
                                //             <span>' . $row["student_message"] . '</span>
                                //         </div>';
                            }
                            if (isset($row["teacher_message"])) {
                                $msg2 = $message->getTeacherMessage($pdo, $row['teacher_id'], $row["teacher_message"]);
                                echo $msg2;
                                // $teacher = "SELECT `teacher_name` FROM `teacher_profile` WHERE `teacher_id`='$row[teacher_id]'";
                                // $gettingTeacher = $pdo->prepare($teacher);
                                // $gettingTeacher->execute();
                                // $teacher_data = $gettingTeacher->fetch(PDO::FETCH_ASSOC);
                                // echo '  <div class="message left">
                                //             <p class="sender-name">
                                //             <b style="transition: 0.3s ease-in;">' . $teacher_data["teacher_name"] . '</b>
                                //             </p>
                                //             <span>' . $row["teacher_message"] . '</span>
                                //         </div>';
                            }
                        }
                        // var_dump($messageExists);
                        if (!$messageExists) {
                            echo "<h3>No Messages Found!</h3>";
                        }
                    }
                    ?>
                </div>
                <div class="send">
                    <form id="sendcontainer" method="post">
                        <input type="text" name="message" id="messageipt" placeholder="Type a message" required>
                        <button class="btn" type="submit" name="messagebtn" id="messagebtn" value="messagebtn">
                            <span class="material-symbols-outlined">
                                send
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php require("partitions/_footers.php") ?>

    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script>
        document.getElementById('messageipt').focus();
        document.getElementById('messagebtn').addEventListener("click", () => {
            document.getElementById('messageipt').focus();
        });
    </script>
    <script>
        let position = $(".messageContainer").children().length;
        $('.messageContainer').scrollTop(10 ** position);
    </script>
    <script src="js/msg_themeToggle.js"></script>
    <script src="js/collapse.js"></script>
    <script>
        <?php
        if (isset($sent)) {
            switch ($sent) {
                case 1:
                    echo 'window.location.href="/Minor_Project/Student_Attendance_System/studentMessage.php"';
                    break;
                case -1:
                    echo "alert('Failed to Send Message');
                        window.location.href='/Minor_Project/Student_Attendance_System/studentMessage.php'";
                default:
                    break;
            }
        }
        ?>
    </script>
</body>

</html>