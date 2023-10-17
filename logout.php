<?php
session_start();
session_unset();
session_destroy();
    echo '<script>
    alert("Logged out successfully");
    window.location.replace("/Minor_Project/Student_Attendance_System/");
</script>';

// header("Location: student_home.php");
// header("Location: main.php");
?>