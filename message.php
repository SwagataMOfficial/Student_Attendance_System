<?php
require("partitions/_dbconnect.php");
session_start();

// checking if a student or teacher has logged in
// if not then redirect to index page
if (!isset($_SESSION['student_loggedin']) && !isset($_SESSION['teacher_loggedin'])) {
    // var_dump($_SESSION['student_loggedin']);
    // var_dump($_SESSION['teacher_loggedin']);
    // echo "yes";
    header("Location: /Minor_Project/Student_Attendance_System/");
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
            header("Location: message.php");
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
</head>
<link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
<link rel="stylesheet" href="css/message.css">

<body>
    <div class="mainContainer">
        <nav>
            <img class="logo" src="https://i.pinimg.com/474x/96/04/48/96044861e221c692bd6d29ecc360bbca.jpg"
                alt="chat logo">
            <h1>Messages</h1>
        </nav>
        <div class="container">
            <!-- <div class="message right"><b>Rahul:</b> How are you ??</div>
            <div class="message left"><b>Voda:</b> I am fine.</div>
            <div class="message right"><b>Rahul:</b> How are you ??</div>
            <div class="message left"><b>Voda:</b> I am fine.</div>
            <div class="message right"><b>Rahul:</b> How are you ??</div>
            <div class="message left"><b>Voda:</b> I am fine.</div>
            <div class="message right"><b>Rahul:</b> How are you ??</div>
            <div class="message left"><b>Voda:</b> I am fine.</div> -->
            <?php
            if ($result) {
                // initial variable that checks if messages exists or not
                $messageExists = false;
                while($row = $query->fetch(PDO::FETCH_ASSOC)){
                    $messageExists = true;
                    // print_r($row);
                    if (isset($row["student_message"])) {
                        // echo 'yes';
                        echo '<div class="message right"><b>Student: </b>' . $row["student_message"] . '</div>';
                    }
                    if (isset($row["teacher_message"])) {
                        // echo 'no';
                        echo '<div class="message left"><b>Teacher: </b>' . $row["teacher_message"] . '</div>';
                    }
                }
                // var_dump($messageExists);
                if(!$messageExists){
                    echo "<h3>No Messages Found!</h3>";
                }
            }
            ?>
        </div>
        <div class="send">
            <form id="sendcontainer" method="post">
                <input type="text" name="message" id="messageipt" placeholder="Type a message">
                <button class="btn" type="submit" name="messagebtn" id="messagebtn" value="messagebtn">
                    <span class="material-symbols-outlined">
                        send
                    </span>
                </button>
            </form>
        </div>
    </div>
    <script>
        document.getElementById('messageipt').focus();
        document.getElementById('messagebtn').addEventListener("click",()=>{
            document.getElementById('messageipt').focus();
        });
    </script>
</body>

</html>