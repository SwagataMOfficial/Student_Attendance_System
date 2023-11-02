<link rel="stylesheet" href="css/headers.css">
<header>
    <nav class="header__navbar">
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
                    notifications
                </span>
            </span>
            <span class="right__moreApps util-icons" title="Other Apps">
                <span class="material-symbols-outlined">
                    apps
                </span>
            </span>
            <div class="right__imageLogo" title="Your profile">
                <?php
                if (isset($teacher['profile_picture'])) {
                    $image = $teacher['profile_picture'];
                    echo '<img src="../profile_pictures/' . $image . '" alt="profile_image">';
                }
                else if (isset($student['profile_picture'])) {
                    $image = $student['profile_picture'];
                    echo '<img src="../profile_pictures/' . $image . '" alt="profile_image">';
                }
                else {
                    echo '<span class="material-symbols-outlined">account_circle</span>';
                }
                ?>

            </div>
        </div>
    </nav>
</header>