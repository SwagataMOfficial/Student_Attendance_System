<header>
    <nav class="header__navbar">
        <button type="button" class="nav__hamburger">
            <span class="material-symbols-outlined">menu</span>
        </button>
        <div class="nav__left">Dashboard</div>
        <div class="nav__right">
            <!-- title is used to show a pop up message like below -->
            <span class="right__themeToggle util-icons" title="Switch the theme.">
                <span class="material-symbols-outlined">
                    dark_mode
                </span>
            </span>
            <span class="right__notification util-icons" title="Notifications">
                <span class="material-symbols-outlined">
                    <?php
                        if(isset($_SESSION['sem_unlocked']) && $_SESSION['sem_unlocked']){
                            echo 'notification_important';
                        }
                        else{
                            echo 'notifications';
                        }
                    ?>
                </span>
                <?php
                    if(isset($_SESSION['sem_unlocked']) && $_SESSION['sem_unlocked']){
                        echo '<div class="notification">
                            <a href="profile_student.php">Semester Change Unlocked! <strong>Click here to edit your semester.</strong></a>
                        </div>';
                    }
                ?>
            </span>
            <a
            <?php
                if (isset($teacher['profile_picture'])) {
                    echo ' href="profile_teacher.php" ';
                } else if (isset($student['profile_picture'])) {
                    echo ' href="profile_student.php" ';
                } else {
                    echo '';
                }
            ?>
            class="right__imageLogo" title="Your profile">
                <?php
                if (isset($teacher['profile_picture'])) {
                    $image = $teacher['profile_picture'];
                    echo '<img src="assets/profile_pictures/' . $image . '" alt="profile_image">';
                } else if (isset($student['profile_picture'])) {
                    $image = $student['profile_picture'];
                    echo '<img src="assets/profile_pictures/' . $image . '" alt="profile_image">';
                } else {
                    echo '<span class="material-symbols-outlined">account_circle</span>';
                }
                ?>
            </a>
        </div>
    </nav>
</header>