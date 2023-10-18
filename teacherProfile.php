<?php
require("partitions/_dbconnect.php");
// if account is created then
if (isset($_POST["teacherAccount"]) && $_POST["teacherAccount"] == "teacherAccount") {
    $teacher_name = $_POST['teacher_name'];
    $teacher_phone = $_POST['teacher_phone'];
    $teacher_email = $_POST['teacher_email'];
    $gender = $_POST['gender'];
    $hod = $_POST['hod_select'];
    if ($hod == "yes") {
        // creating account of hod
        $department = $_POST['department'];
        $sql = "INSERT INTO `teacher_profile` (`teacher_id`, `teacher_name`, `teacher_phone`, `teacher_email`, `teacher_gender`, `hod_department`) VALUES ('0', '$teacher_name', '$teacher_phone', '$teacher_email', '$gender', '$department')";
        // $result = mysqli_query($conn, $sql);
        $query = $pdo->prepare($sql);
        $result = $query->execute();
        if ($result) {
            $accountCreated = true;

            // getting serial number and email for id updation
            $sql = "SELECT `sno` FROM `teacher_profile` WHERE `teacher_email` ='$teacher_email'";
            // $result = mysqli_query($conn, $sql);
            $query = $pdo->prepare($sql);
            $result = $query->execute();
            // $teacher = mysqli_fetch_assoc($result);
            $teacher = $query->fetch(PDO::FETCH_ASSOC);

            //Updating teacher id

            if ($teacher["sno"] < 10) {
                $sno = "00" . $teacher["sno"];
            }
            elseif ($teacher["sno"] >= 10 && $teacher["sno"] < 100) {
                $sno = "0" . $teacher["sno"];
            }
            else {
                $sno = $teacher["sno"];
            }
            $tid = "T" . $department . $sno;

            $sql = "UPDATE `teacher_profile` SET `teacher_id` = '$tid' WHERE `teacher_email` ='$teacher_email'";
            // $result = mysqli_query($conn, $sql);
            $query = $pdo->prepare($sql);
            $result = $query->execute();
            header("Location: index.php");
        }
    }
    else {
        // creating account for non-hod teachers
        $sql = "INSERT INTO `teacher_profile` (`teacher_id`, `teacher_name`, `teacher_phone`, `teacher_email`, `teacher_gender`) VALUES ('0', '$teacher_name', '$teacher_phone', '$teacher_email', '$gender')";
        // $result = mysqli_query($conn, $sql);
        $query = $pdo->prepare($sql);
        $result = $query->execute();
        if ($result) {
            $accountCreated = true;

            // getting serial number and email for id updation
            $sql = "SELECT `sno` FROM `teacher_profile` WHERE `teacher_email` ='$teacher_email'";
            // $result = mysqli_query($conn, $sql);
            $query = $pdo->prepare($sql);
            $result = $query->execute();
            // $teacher = mysqli_fetch_assoc($result);
            $teacher = $query->fetch(PDO::FETCH_ASSOC);

            //Updating teacher id

            if ($teacher["sno"] < 10) {
                $sno = "00" . $teacher["sno"];
            }
            elseif ($teacher["sno"] >= 10 && $teacher["sno"] < 100) {
                $sno = "0" . $teacher["sno"];
            }
            else {
                $sno = $teacher["sno"];
            }
            $tid = "T" . $sno;
            $sql = "UPDATE `teacher_profile` SET `teacher_id` = '$tid' WHERE `teacher_email` ='$teacher_email'";
            // $result = mysqli_query($conn, $sql);
            $query = $pdo->prepare($sql);
            $result = $query->execute();
        }
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Teacher Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-3">
        <h2 class="text-center fw-bold mb-2">Create Your Profile</h2>
        <form method="post" id="teacherAccountCreate">

            <div class="mb-3">
                <label for="t_name" class="form-label">Teacher Name</label>
                <input type="text" name="teacher_name" class="form-control" id="t_name" aria-describedby="emailHelp"
                    placeholder="Enter Your Name" required>
            </div>
            <div class="mb-3">
                <label for="t_phone" class="form-label">Teacher Phone</label>
                <input type="tel" name="teacher_phone" class="form-control" id="t_phone" aria-describedby="emailHelp"
                    placeholder="Enter Your Phone" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label disabled">Teacher Email</label>
                <input type="t_email" name="teacher_email" class="form-control" id="t_email"
                    aria-describedby="emailHelp" placeholder="Enter Your Email" <?php
                    if (isset($_GET["teacher_email"])) {
                        echo "value=" . $_GET["teacher_email"];
                    }
                    ?> readonly required>
            </div>
            <div class="mb-3">
                <label class="form-check-label mb-2">Select Your Gender</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" id="t_male" value="M">
                    <label class="form-check-label" for="t_male">Male</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" id="t_female" value="F">
                    <label class="form-check-label" for="t_female">Female</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" id="t_other" value="O">
                    <label class="form-check-label" for="t_other">Other</label>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-check-label mb-2">Are You HOD?</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="hod_select" id="yes" value="yes">
                    <label class="form-check-label" for="yes">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="hod_select" id="no" value="no">
                    <label class="form-check-label" for="no">No</label>
                </div>
            </div>
            <div class="mb-3" id="department" style="display: none;">
                <label class="form-check-label mb-2">Select Your Department</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="department" id="t_bca" value="BCA">
                    <label class="form-check-label" for="t_bca">BCA</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="department" id="t_bba" value="BBA">
                    <label class="form-check-label" for="t_bba">BBA</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="department" id="t_mca" value="MCA">
                    <label class="form-check-label" for="t_mca">MCA</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="department" id="t_mba" value="MBA">
                    <label class="form-check-label" for="t_mba">MBA</label>
                </div>
            </div>
            <button type="submit" name="teacherAccount" form="teacherAccountCreate" value="teacherAccount"
                class="btn btn-primary">Create Account</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
    <script>
        const hod_yes = document.getElementById("yes");
        const hod_no = document.getElementById("no");
        hod_yes.addEventListener('change', () => {
            document.getElementById("department").style.display = "block";
        });
        hod_no.addEventListener('change', () => {
            document.getElementById("department").style.display = "none";
        });
    </script>
</body>

</html>