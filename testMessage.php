<?php
require("partitions/_dbconnect.php");
session_start();

// checking if a student or teacher has logged in
// if not then redirect to index page
if (!isset($_SESSION['student_loggedin']) && !isset($_SESSION['teacher_loggedin'])) {
    // var_dump($_SESSION['student_loggedin']);
    // var_dump($_SESSION['teacher_loggedin']);
    // echo "yes";
    // header("Location: /Minor_Project/Student_Attendance_System/");
}

// getting all the messages
$getMessages = "SELECT * FROM `messages`";
// $result = mysqli_query($conn, $getMessages);
$query = $pdo->prepare($getMessages);
$result = $query->execute();

// handling message posting request for student
if (isset($_SESSION["student_id"]) && !isset($_SESSION["teacher_id"])) {
    if (isset($_POST["messagebtn"]) && $_POST["messagebtn"] === "messagebtn") {
        $id = $_SESSION["student_id"];
        $message = $_POST['message'];
        $sendMessage = "INSERT INTO `messages` (`student_id`,`student_message`) VALUES ('$id','$message')";
        // $result = mysqli_query($conn, $sendMessage);
        $query = $pdo->prepare($sendMessage);
        $result = $query->execute();
        if ($result) {
            header("Location: testMessage.php");
        }
    }
}

// handling message posting request for teacher
if (isset($_SESSION["teacher_id"]) && !isset($_SESSION["student_id"])) {
    if (isset($_POST["messagebtn"]) && $_POST["messagebtn"] === "messagebtn") {
        $id = $_SESSION["teacher_id"];
        $message = $_POST['message'];
        $sendMessage = "INSERT INTO `messages` (`teacher_id`,`teacher_message`) VALUES ('$id','$message')";
        // $result = mysqli_query($conn, $sendMessage);
        $query = $pdo->prepare($sendMessage);
        $result = $query->execute();
        if ($result) {
            header("Location: message.php");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            /*outline: 1px solid red; */
        }

        .mainContainer {
            max-height: 100dvh;
            overflow: hidden;
        }

        .mainContainer nav {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            margin: 10px 0px;
        }

        .logo {
            width: 70px;
            height: 60px;
            border-radius: 20px 30px;
            transform: rotate(-4deg);
        }

        .container {
            display: flex;
            min-height: 91vh;
            max-width: 100vw;
            background-color: var(--clr-bgcolor);
        }

        .container__rightMain {
            /* background: rgb(86, 64, 64); */
            width: calc(100vw - 16.5vw);
            /* height: 100vh; */
        }

        .messageContainer {
            max-width: 90%;
            border: 2px solid var(--clr-border);
            margin: auto;
            height: 69vh;
            padding: 20px;
            overflow-y: auto;
            margin-bottom: 15px;
            border-radius: 15px;
        }

        .messageContainer p {
            width: 35%;
            margin: auto;
            padding: 4px 0px;
            border-radius: 15px;
            color: #ffd600;
            background: #22273c;
        }

        .alert-text {
            text-align: center;
        }

        .messageContainer::-webkit-scrollbar {
            background-color: transparent;
            width: 2px;
        }

        .messageContainer::-webkit-scrollbar-thumb {
            background-color: var(--clr-border);
        }

        .messageContainer h3 {
            text-align: center;
            font-size: 3rem;
            margin-top: 3rem;
            font-family: sans-serif;
        }

        .message {
            background-color: #151a30;
            width: 26%;
            padding: 10px;
            margin: 10px;
            border-radius: 20px;
            color: white;
            font-family: sans-serif;
        }

        .left span {
            margin-left: 5px;
            font-size: 1.03rem;
        }

        .right span {
            margin-left: 5px;
            font-size: 1.03rem;
        }

        .left {
            float: left;
            clear: both;
        }

        .left b {
            color: #00e5ff;
            font-size: 1.05rem;
            padding-left: 10px;
            font-family: sans-serif;
            letter-spacing: 1px;
        }

        .right {
            float: right;
            clear: both;
        }

        .right b {
            padding-left: 10px;
            color: darkorange;
            letter-spacing: 1px;
        }

        #sendcontainer {
            display: block;
            margin: auto;
            text-align: center;
            max-width: 985px;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;

        }

        #messageipt {
            width: 92%;
            border: 2px solid var(--clr-border);
            background: #0f121f;
            color: var(--clr-btn-text-logo);
            border-radius: 20px;
            height: 45px;
            font-size: 22px;
            padding-left: 22px;
            margin-right: 2rem;
            outline: none;
        }

        #messageipt:hover {
            background-color: #131728;
        }

        #messageipt:focus {
            border: 2px solid var(--clr-border);
        }

        .btn {
            cursor: pointer;
            border: 1px solid var(--clr-border);
            border-radius: 10px;
            height: 43px;
            width: 54px;
            background: #0f121f;
        }

        .btn:hover {
            background-color: #131728;
        }

        .btn span {
            transform: rotate(330deg);
            font-size: 2rem;
            padding-left: 4px;
            color: var(--clr-btn-text-logo);
        }

        .mainContainer h1 {
            font-size: 2rem;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--clr-btn-text-logo);
        }
    </style>
</head>
<link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

<body>
    <?php require("partitions/_headers.php") ?>
    <div class="container">
        <?php require("partitions/_leftNavOptions.php") ?>
        <div class="container__rightMain">


            <div class="mainContainer">
                <nav>
                    <img class="logo" src="https://i.pinimg.com/474x/96/04/48/96044861e221c692bd6d29ecc360bbca.jpg"
                        alt="chat logo">
                    <h1>Messages</h1>
                </nav>
                <div class="messageContainer">
                    <p class="alert-text">Welcome to Student_Attendance_System Chat Portal</p>
                    <?php
                    if ($result) {
                        // initial variable that checks if messages exists or not
                        $messageExists = false;
                        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                            $messageExists = true;
                            // print_r($row);
                            if (isset($row["student_message"])) {
                                // echo 'yes';
                                echo '<div class="message right"><b>Student: </b><span>' . $row["student_message"] . '</span></div>';
                            }
                            if (isset($row["teacher_message"])) {
                                // echo 'no';
                                echo '<div class="message left"><b>Teacher: </b><span>' . $row["teacher_message"] . '</span></div>';
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
    <script>
        document.getElementById('messageipt').focus();
        document.getElementById('messagebtn').addEventListener("click",()=>{
            document.getElementById('messageipt').focus();
        });
    </script>
</body>

</html>