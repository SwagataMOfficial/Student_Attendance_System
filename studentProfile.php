<?php
require("partitions/_dbconnect.php");
include('classes/Authenticate.php');
session_start();

if(isset($_SESSION['student_register'])){
    // if account is created then
    if (isset($_POST["createAccount"]) && $_POST["createAccount"] == "createAccount") {
        $result = $_SESSION['student_register']->set_student_profile($pdo, $_POST);
    }
}
else{
    header("Location: /Student_Attendance_System/");
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Student Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-3">
        <h2 class="text-center fw-bold mb-2">Create Your Profile</h2>
        <form method="post" id="account_create" enctype="multipart/form-data">
            <div class="mb-4">
                <label for="name" class="form-label">Student Name</label>
                <input type="text" name="student_name" class="form-control" id="name" aria-describedby="emailHelp" placeholder="Enter Your Name" required <?php if(isset($_POST["createAccount"])){echo "value=$_POST[student_name]";} ?> >
            </div>
            <div class="mb-4">
                <label for="phone" class="form-label">Student Phone</label>
                <input type="tel" pattern="[0-9]{10}" max="9999999999" name="student_phone" class="form-control" id="phone" aria-describedby="emailHelp" placeholder="Enter Your Phone Number (10 digits)" required <?php if(isset($_POST["createAccount"])){echo "value=$_POST[student_phone]";} ?> >
            </div>
            <div class="mb-2">
                <label class="form-check-label mb-2" for="gender">Select Your Gender</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" id="male" value="M" required <?php if(isset($_POST["createAccount"])){if($_POST['gender'] == "M"){echo ' checked ';}} ?> >
                    <label class="form-check-label" for="male">Male</label>
                </div>
                <div class="form-check form-check-inline mb-2">
                    <input class="form-check-input" type="radio" name="gender" id="female" value="F" required <?php if(isset($_POST["createAccount"])){if($_POST['gender'] == "F"){echo ' checked ';}} ?> >
                    <label class="form-check-label" for="female">Female</label>
                </div>
                <div class="form-check form-check-inline mb-2">
                    <input class="form-check-input" type="radio" name="gender" id="other" value="O" required <?php if(isset($_POST["createAccount"])){if($_POST['gender'] == "O"){echo ' checked ';}} ?> >
                    <label class="form-check-label" for="other">Other</label>
                </div>
            </div>
            <div class="mb-2">
                <label class="form-check-label my-2" for="stream">Select Your Stream</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="stream" id="bca" value="BCA" required <?php if(isset($_POST["createAccount"])){if($_POST['stream'] == "BCA"){echo ' checked ';}} ?> >
                    <label class="form-check-label" for="bca">BCA</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="stream" id="bba" value="BBA" required <?php if(isset($_POST["createAccount"])){if($_POST['stream'] == "BBA"){echo ' checked ';}} ?> >
                    <label class="form-check-label" for="bba">BBA</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="stream" id="mca" value="MCA" required <?php if(isset($_POST["createAccount"])){if($_POST['stream'] == "MCA"){echo ' checked ';}} ?> >
                    <label class="form-check-label" for="mca">MCA</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="stream" id="mba" value="MBA" required <?php if(isset($_POST["createAccount"])){if($_POST['stream'] == "MBA"){echo ' checked ';}} ?> >
                    <label class="form-check-label" for="mba">MBA</label>
                </div>
            </div>
            <div class="mb-3">
                <select class="form-select mt-3" name="student_semester" aria-label="Default select example" required>
                    <option>Select Your Semester..</option>
                    <option value="1" <?php if(isset($_POST["createAccount"])){if($_POST['student_semester'] == "1"){echo ' selected ';}} ?> >First</option>
                    <option value="2" <?php if(isset($_POST["createAccount"])){if($_POST['student_semester'] == "2"){echo ' selected ';}} ?> >Second</option>
                    <option value="3" <?php if(isset($_POST["createAccount"])){if($_POST['student_semester'] == "3"){echo ' selected ';}} ?> >Third</option>
                    <option value="4" <?php if(isset($_POST["createAccount"])){if($_POST['student_semester'] == "4"){echo ' selected ';}} ?> >Fourth</option>
                    <option value="5" <?php if(isset($_POST["createAccount"])){if($_POST['student_semester'] == "5"){echo ' selected ';}} ?> >Fifth</option>
                    <option value="6" <?php if(isset($_POST["createAccount"])){if($_POST['student_semester'] == "6"){echo ' selected ';}} ?> >Sixth</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="student_profile_pic" class="form-label">Upload Profile Picture</label>
                <input class="form-control" type="file" name="student_profile_pic" id="student_profile_pic" accept=".jpg , .jpeg , .png" required>
            </div>
            <button type="submit" form="account_create" name="createAccount" value="createAccount" class="btn btn-primary">Create Account</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <script>
        <?php
        if (isset($result)) {
            switch ($result) {
                case 1:
                    echo "alert('Profile Successfully Created! Now you can login....');
                          document.location.href='/Student_Attendance_System/';";
                    break;
                case 2:
                    echo "alert('Image size too big');
                            document.location.href='studentProfile.php';";
                    break;
                case 3:
                    echo "alert('Please select an image');
                        document.location.href='studentProfile.php';";
                    break;
                default:
                    break;
            }
        }
        ?>
    </script>

</body>

</html>