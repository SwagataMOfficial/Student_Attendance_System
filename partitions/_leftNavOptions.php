<link rel="stylesheet" href="css/leftNav.css">
<div class="left-nav">
    <div class="contents">
        <a <?php
            if (isset($_SESSION['student_loggedin']) && $_SESSION['student_loggedin'] == true) {
                echo 'href="student_home.php"';
            } else if (isset($_SESSION['teacher_loggedin']) && $_SESSION['teacher_loggedin'] == true) {
                echo 'href="teacher_home.php"';
            } else {
                echo '/Minor_Project/Student_Attendance_System/';
            }
            ?> class="links" style="margin-top: 12px;">
            <span class="material-symbols-outlined">
                home
            </span>
            <p>Home</p>
        </a>
        <div class="link-wrapper">
            <p class="link-heading">Progress</p>
            <a href="#progress" class="links">
                <span class="material-symbols-outlined">
                    analytics
                </span>
                <p>Progress</p>
            </a>
            <a href="#crm" class="links">
                <span class="material-symbols-outlined">
                    monitoring
                </span>
                <p>Charts</p>
            </a>
        </div>
        <div class="link-wrapper">
            <p class="link-heading">Report</p>
            <a href="#ecom" class="links">
                <span class="material-symbols-outlined">
                    lab_profile
                </span>
                <p>Report Card</p>
            </a>
            <a href="#crm" class="links">
                <span class="material-symbols-outlined">
                    downloading
                </span>
                <p>Download Report</p>
            </a>
        </div>
        <div class="link-wrapper">
            <p class="link-heading">Message</p>
            <a href="testMessage.php" class="links">
                <span class="material-symbols-outlined">
                    forum
                </span>
                <p>Messages</p>
            </a>
        </div>
        <div class="link-wrapper">
            <p class="link-heading">Connect With Us</p>
            <a href="contactUs.php" class="links">
                <span class="material-symbols-outlined">
                    contact_support
                </span>
                <p>Contact Us</p>
            </a>
        </div>
    </div>
    <button id="collapse-btn">
        <span class="material-symbols-outlined">
            arrow_back
        </span>
        <span class="collapse-name">Collapse</span>
    </button>
</div>