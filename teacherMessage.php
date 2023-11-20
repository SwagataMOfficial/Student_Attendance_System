<?php
require("partitions/_dbconnect.php");
// require('classes/StudentClass.php');
require('classes/TeacherClass.php');
require('classes/MessageClass.php');
session_start();

// checking if a student or teacher has logged in
// if not then redirect to index page
if (!isset($_SESSION['teacher_loggedin']) && !isset($_SESSION['teacher_obj'])) {
    header("Location: /Student_Attendance_System/");
}

// getting all the messages
$message = new Message();
$query = $message->getMessages($pdo);
$query->execute();

// handling message posting request for teacher
if (isset($_SESSION['teacher_obj']) && isset($_SESSION['teacher_obj'])) {
    $teacher = $_SESSION['teacher_obj']->getTeacherDetails();
    if (isset($_POST["messagebtn"]) && $_POST["messagebtn"] === "messagebtn") {
        $sent = $_SESSION['teacher_obj']->sendMessage($pdo, $_POST);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message Portal</title>
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
            document.querySelector(':root').style.setProperty('--clr-input', '#0f121f');
            document.querySelector(':root').style.setProperty('--clr-message', '#1e2436');
            document.querySelector(':root').style.setProperty('--clr-sender-left', '#00ff8c');
            document.querySelector(':root').style.setProperty('--clr-sender-right', '#ff6c00');
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
            document.querySelector(':root').style.setProperty('--clr-input', '#e8e8e8');
            document.querySelector(':root').style.setProperty('--clr-message', '#e2e2e2');
            document.querySelector(':root').style.setProperty('--clr-sender-left', '#0040ff');
            document.querySelector(':root').style.setProperty('--clr-sender-right', '#e000d7');
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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="css/headers.css">
    <link rel="stylesheet" href="css/footers.css">
    <link rel="stylesheet" href="css/leftNav.css">
    <link rel="stylesheet" href="css/message.css">
    <link rel="stylesheet" href="css/responsive.css">
</head>

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
                            }
                            if (isset($row["teacher_message"])) {
                                $msg2 = $message->getTeacherMessage($pdo, $row['teacher_id'], $row["teacher_message"]);
                                echo $msg2;
                            }
                        }
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
        $('.messageContainer').scrollTop(1000 * position);
    </script>
    <script src="js/msg_themeToggle.js"></script>
    <script src="js/collapse.js"></script>
    <script>
        <?php
        if (isset($sent)) {
            switch ($sent) {
                case 1:
                    echo 'window.location.href="/Student_Attendance_System/teacherMessage.php"';
                    break;
                case -1:
                    echo "alert('Failed to Send Message');
                        window.location.href='/Student_Attendance_System/teacherMessage.php'";
                default:
                    break;
            }
        }
        ?>
    </script>
        <script>
        document.querySelector('.header__navbar .nav__hamburger').addEventListener('click', ()=>{
            console.log('clicked');
            // if(document.querySelector('.container .left-nav').style.display === "none"){
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