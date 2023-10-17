<?php
require("partitions/_dbconnect.php");
// if account is created then
if (isset($_POST["createAccount"]) && $_POST["createAccount"] == "createAccount") {
    $student_name = $_POST['student_name'];
    $student_phone = $_POST['student_phone'];
    $student_email = $_POST['student_email'];
    $gender = $_POST['gender'];
    $stream = $_POST['stream'];
    $student_semester = $_POST['student_semester'];

    $sql = "INSERT INTO `student_profile` ( `student_name`,  `student_phone`, `student_email`, `student_gender`, `student_stream`, `student_semester`) VALUES ( '$student_name', '$student_phone', '$student_email', '$gender', '$stream', '$student_semester')";
    // $result = mysqli_query($conn, $sql);
    $query = $pdo->prepare($sql);
    $result = $query->execute();
    if ($result) {
        $accountCreated = true;


        // Getting student data

        $sql = "Select `sno`, `student_stream` from `student_profile` where `student_email`= '$student_email'";
        // $result = mysqli_query($conn, $sql);
        $query = $pdo->prepare($sql);
        $result = $query->execute();
        // $student = mysqli_fetch_assoc($result);
        $student = $query->fetch(PDO::FETCH_ASSOC);

        //Updating student id

        if ($student["sno"] < 10) {
            $sno = "00" . $student["sno"];
        }
        elseif ($student["sno"] >= 10 && $student["sno"] < 100) {
            $sno = "0" . $student["sno"];
        }
        else {
            $sno = $student["sno"];
        }
        $sid = "S" . $student["student_stream"] . $sno;
        $sql = "UPDATE `student_profile` SET `student_id` = '$sid' WHERE `student_email` ='$student_email'";
        // $result = mysqli_query($conn, $sql);
        $query = $pdo->prepare($sql);
        $result = $query->execute();
        if ($result) {
            $sql = "INSERT INTO `student_attendance` (`student_id`,`student_name`, `remarks`, `grade`) VALUES ('$sid', '$student_name', 'Initial', 'None');";
            // $result = mysqli_query($conn, $sql);
            $query = $pdo->prepare($sql);
            $result = $query->execute();
            header("Location: index.php");
        }
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Student Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-3">
        <h2 class="text-center fw-bold mb-2">Create Your Profile</h2>
        <form method="post" id="account_create">
            <div class="mb-4">
                <label for="exampleInputEmail1" class="form-label">Student Name</label>
                <input type="text" name="student_name" class="form-control" id="exampleInputEmail1"
                    aria-describedby="emailHelp" placeholder="Enter Your Name" required>
            </div>

            <div class="mb-4">
                <label for="exampleInputEmail1" class="form-label">Student Phone</label>
                <input type="tel" name="student_phone" class="form-control" id="exampleInputEmail1"
                    aria-describedby="emailHelp" placeholder="Enter Your Phone" required>
            </div>
            <div class="mb-4">
                <label for="exampleInputEmail1" class="form-label">Student Email</label>
                <input type="email" name="student_email" class="form-control" id="exampleInputEmail1"
                    aria-describedby="emailHelp" placeholder="Enter Your Email" <?php
                    if (isset($_GET["student_email"])) {
                        echo "value=" . $_GET["student_email"];
                    }
                    ?> readonly required>
            </div>
            <div class="mb-2">
                <label class="form-check-label mb-2" for="gender">Select Your Gender</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" id="male" value="M" required>
                    <label class="form-check-label" for="male">Male</label>
                </div>
                <div class="form-check form-check-inline mb-2">
                    <input class="form-check-input" type="radio" name="gender" id="female" value="F" required>
                    <label class="form-check-label" for="female">Female</label>
                </div>
                <div class="form-check form-check-inline mb-2">
                    <input class="form-check-input" type="radio" name="gender" id="other" value="O" required>
                    <label class="form-check-label" for="other">Other</label>
                </div>
            </div>
            <div class="mb-2">
                <label class="form-check-label my-2" for="stream">Select Your Stream</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="stream" id="bca" value="BCA" required>
                    <label class="form-check-label" for="bca">BCA</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="stream" id="bba" value="BBA" required>
                    <label class="form-check-label" for="bba">BBA</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="stream" id="mca" value="MCA" required>
                    <label class="form-check-label" for="mca">MCA</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="stream" id="mba" value="MBA" required>
                    <label class="form-check-label" for="mba">MBA</label>
                </div>
            </div>
            <div class="mb-3">
                <select class="form-select mt-3" name="student_semester" aria-label="Default select example" required>
                    <option>Select Your Semester..</option>
                    <option value="1">First</option>
                    <option value="2">Second</option>
                    <option value="3">Third</option>
                    <option value="4">Fourth</option>
                    <option value="5">Fifth</option>
                    <option value="6">Sixth</option>
                </select>
            </div>
            <button type="submit" form="account_create" name="createAccount" value="createAccount"
                class="btn btn-primary">Create Account</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
</body>

</html>