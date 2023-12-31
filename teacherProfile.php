<?php
require("partitions/_dbconnect.php");
include('classes/Authenticate.php');
session_start();

if(isset($_SESSION['teacher_register'])){
    // if account is created then
    if (isset($_POST["teacherAccount"]) && $_POST["teacherAccount"] == "teacherAccount") {
        $result = $_SESSION['teacher_register']->set_teacher_profile($pdo, $_POST);
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
    <title>Create Teacher Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script>
        window.onload = ()=>{
            if(document.getElementById('yes').checked){
                document.getElementById("department").style.display = "block";
            }
            else{
                document.getElementById("department").style.display = "none";
            }
        }
    </script>
</head>

<body>
    <div class="container mt-3">
        <h2 class="text-center fw-bold mb-2">Create Your Profile</h2>
        <form method="post" id="teacherAccountCreate" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="t_name" class="form-label">Teacher Name</label>
                <input type="text" name="teacher_name" class="form-control" id="t_name" aria-describedby="emailHelp" placeholder="Enter Your Name" required <?php if(isset($_POST["teacherAccount"])){echo "value=$_POST[teacher_name]";} ?> >
            </div>
            <div class="mb-3">
                <label for="t_phone" class="form-label">Teacher Phone</label>
                <input type="tel" pattern="[0-9]{10}" max="9999999999" name="teacher_phone" class="form-control" id="t_phone" aria-describedby="emailHelp" placeholder="Enter Your Phone Number (10 digits)" required <?php if(isset($_POST["teacherAccount"])){echo "value=$_POST[teacher_phone]";} ?> >
            </div>
            <div class="mb-3">
                <label for="t_male" class="form-check-label mb-2">Select Your Gender</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" id="t_male" value="M" required <?php if(isset($_POST["teacherAccount"])){if($_POST['gender'] == "M"){echo ' checked ';}} ?> >
                    <label class="form-check-label" for="t_male">Male</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" id="t_female" value="F" required <?php if(isset($_POST["teacherAccount"])){if($_POST['gender'] == "F"){echo ' checked ';}} ?> >
                    <label class="form-check-label" for="t_female">Female</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" id="t_other" value="O" required <?php if(isset($_POST["teacherAccount"])){if($_POST['gender'] == "O"){echo ' checked ';}} ?> >
                    <label class="form-check-label" for="t_other">Other</label>
                </div>
            </div>
            <div class="mb-3">
                <label for="teacher_profile_pic" class="form-label">Upload Profile Picture</label>
                <input class="form-control" type="file" name="teacher_profile_pic" id="teacher_profile_pic" accept=".jpg , .jpeg , .png" required>
            </div>
            <div class="mb-3">
                <label for="yes" class="form-check-label mb-2">Are You HOD?</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="hod_select" id="yes" value="yes" required <?php if(isset($_POST["teacherAccount"])){if($_POST['hod_select'] == "yes"){echo ' checked ';}} ?> >
                    <label class="form-check-label" for="yes">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="hod_select" id="no" value="no" required <?php if(isset($_POST["teacherAccount"])){if($_POST['hod_select'] == "no"){echo ' checked ';}} ?> >
                    <label class="form-check-label" for="no">No</label>
                </div>
            </div>
            <div class="mb-3" id="department" style="display: none;">
                <label for="t_bca" class="form-check-label mb-2">Select Your Department</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="department" id="t_bca" value="BCA" <?php if(isset($_POST["teacherAccount"])){if($_POST['department'] == "BCA"){echo ' checked ';}} ?> >
                    <label class="form-check-label" for="t_bca">BCA</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="department" id="t_bba" value="BBA" <?php if(isset($_POST["teacherAccount"])){if($_POST['department'] == "BBA"){echo ' checked ';}} ?> >
                    <label class="form-check-label" for="t_bba">BBA</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="department" id="t_mca" value="MCA" <?php if(isset($_POST["teacherAccount"])){if($_POST['department'] == "MCA"){echo ' checked ';}} ?> >
                    <label class="form-check-label" for="t_mca">MCA</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="department" id="t_mba" value="MBA" <?php if(isset($_POST["teacherAccount"])){if($_POST['department'] == "MBA"){echo ' checked ';}} ?> >
                    <label class="form-check-label" for="t_mba">MBA</label>
                </div>
            </div>
            <button type="submit" name="teacherAccount" form="teacherAccountCreate" value="teacherAccount" class="btn btn-primary">Create Account</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
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
                            document.location.href='teacherProfile.php';";
                    break;
                case 3:
                    echo "alert('Please select an image');
                        document.location.href='teacherProfile.php';";
                    break;
                default:
                    break;
            }
        }
        ?>
    </script>
</body>

</html>