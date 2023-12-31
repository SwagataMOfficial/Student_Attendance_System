<?php
    // users will get into that page only if they are exiting the website or redirected for some reason.
    require('partitions/_dbconnect.php');
    session_start();
    session_destroy();

    date_default_timezone_set('Asia/Kolkata');

    if(date('d')>15){
        $unlockSem = "UPDATE `student_attendance` SET `sem_unlocked`='0';";
        $stmt = $pdo->prepare($unlockSem);
        $stmt->execute();
    }
?>

<!doctype html>
<html lang="en" style="scroll-behavior: smooth;">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome to Classified.in</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="css/landing.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>

<body class="bg-dark" style="background: url('https://e0.pxfuel.com/wallpapers/696/496/desktop-wallpaper-purple-technology-background-png-stickers-background.jpg') no-repeat; background-size: cover; background-attachment: fixed;">
<!-- <body class="bg-dark"> -->
    <div class="my_loader">
        <div class="my_loader-container">
            <div class="my_ring"></div>
            <div class="my_ring"></div>
            <div class="my_ring"></div>
            <span class="my_loading">Loading...</span>
        </div>
    </div>
    <!-- <nav class="navbar navbar-expand-lg bg-body-tertiary"> -->
    <nav class="navbar navbar-expand-lg fixed-top" data-bs-theme="dark" style="backdrop-filter: blur(7px); border-bottom: 1px solid cadetblue;">
        <div class="container-fluid">
            <a class="navbar-brand text-warning" href="/Student_Attendance_System/">Classified.in</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item mx-2">
                        <a class="nav-link text-light" aria-current="page" href="http://localhost/Student_Attendance_System/">Home</a>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="nav-link text-light" href="assets/17_User Manual.pdf" target="_blank">Docs</a>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="nav-link text-light" href="contactUs.php">Contact Us</a>
                    </li>
                </ul>
                <!-- <button class="btn btn-primary mx-2">Login</button> -->
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-warning mx-2" data-bs-toggle="modal" data-bs-target="#loginOptions">Login</button>
                <button type="button" class="btn btn-warning mx-2" data-bs-toggle="modal" data-bs-target="#signupOptions">Sign Up</button>
            </div>
        </div>
    </nav>

    <!-- Vertically centered modal for login options -->
    <div class="modal fade" id="loginOptions" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Choose Login Type</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex justify-content-center align-items-center gap-4">
                    <a href="loginStudent.php" class="btn btn-success p-4">Login as Student</a>
                    <a href="loginTeacher.php" class="btn btn-success p-4">Login as Teacher</a>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Vertically centered modal for login options -->
    <div class="modal fade" id="signupOptions" tabindex="-1" aria-labelledby="exampleModalLabe2" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabe2">Choose Sign Up Type</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex justify-content-center align-items-center gap-4">
                    <a href="registerStudent.php" class="btn btn-success p-4">Sign Up as Student</a>
                    <a href="registerTeacher.php" class="btn btn-success p-4">Sign Up as Teacher</a>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid position-relative" style="min-height: 100vh;">
        <div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
            <div class="card text-center bg-transparent" id="intro">
                <div class="card-header fs-3 text-decoration-underline link-offset-2">Introducing</div>
                <div class="card-body">
                    <h5 class="card-title mb-4 my-animated-text">Student Attendance System</h5>
                    <p class="card-text">Student Attendance System is the website in which students and teachers data is managed automatically. Student can scan specific QR code provided by their institution through this website to get attendance on daily basis. Teachers can view, search, analyze, edit, delete Students data and they can also lock/unlock student scanner for any reason. There is a message portal for both students and teachers. They can communicate through this message portal. Students can analyze there performance in their dashboard using different chart option, they can generate their performance report card and if they want to download their report card then they can click download report and the report card will be sent to their registered email. Teachers can set minimum attendance goal for students to achieve and low scoring students will get nofification to remind about their low performance. There is a contact page for user support. Register to explore more features. Thank You!</p>
                    <a class="link-light link-offset-0 link-offset-2-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="#features">
                        Features
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <section class="container w-75 min-vh-100 d-flex justify-content-center align-items-center flex-column gap-3" id="features">
        <h3 class="z-1 fw-bold" style="color: lime !important; text-shadow: 4px 3px 4px black !important;">Top Features</h3>
        <div class="container gap-4 d-flex justify-content-center align-items-center flex-wrap">
            <!-- card-1 -->
            <div class="card text-light border-info rounded-4">
                <div class="card-body">
                    <h5 class="card-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="#00ffde" style="margin: 5px 15px;" class="bi bi-qr-code-scan" viewBox="0 0 16 16">
                            <path d="M0 .5A.5.5 0 0 1 .5 0h3a.5.5 0 0 1 0 1H1v2.5a.5.5 0 0 1-1 0v-3Zm12 0a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0V1h-2.5a.5.5 0 0 1-.5-.5ZM.5 12a.5.5 0 0 1 .5.5V15h2.5a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5v-3a.5.5 0 0 1 .5-.5Zm15 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1 0-1H15v-2.5a.5.5 0 0 1 .5-.5ZM4 4h1v1H4V4Z" />
                            <path d="M7 2H2v5h5V2ZM3 3h3v3H3V3Zm2 8H4v1h1v-1Z" />
                            <path d="M7 9H2v5h5V9Zm-4 1h3v3H3v-3Zm8-6h1v1h-1V4Z" />
                            <path d="M9 2h5v5H9V2Zm1 1v3h3V3h-3ZM8 8v2h1v1H8v1h2v-2h1v2h1v-1h2v-1h-3V8H8Zm2 2H9V9h1v1Zm4 2h-1v1h-2v1h3v-2Zm-4 2v-1H8v1h2Z" />
                            <path d="M12 9h2V8h-2v1Z" />
                        </svg>
                    </h5>
                    <h4 class="card-subtitle mb-2 mt-2 text-light text-center text-uppercase">qr scanner</h4>
                    <ul>
                        <li><span>Fastest QR Code Scanning system</span></li>
                        <li><span>Easy Attendance Counting</span></li>
                    </ul>
                    <div class="card-footer text-light text-center">
                        <span>
                            <a class="link-light link-offset-0 link-offset-2-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="#qr_scanner">
                                Read More
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z" />
                                </svg>
                            </a>
                        </span>
                    </div>
                </div>
            </div>
            <!-- card-2 -->
            <div class="card text-light border-info rounded-4">
                <div class="card-body">
                    <h5 class="card-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="#00ffde" style="margin: 5px 15px;" class="bi bi-person-workspace" viewBox="0 0 16 16">
                            <path d="M4 16s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H4Zm4-5.95a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z" />
                            <path d="M2 1a2 2 0 0 0-2 2v9.5A1.5 1.5 0 0 0 1.5 14h.653a5.373 5.373 0 0 1 1.066-2H1V3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v9h-2.219c.554.654.89 1.373 1.066 2h.653a1.5 1.5 0 0 0 1.5-1.5V3a2 2 0 0 0-2-2H2Z" />
                        </svg>
                    </h5>
                    <h4 class="card-subtitle mb-2 mt-2 text-light text-center text-uppercase">Admin Dashboard</h4>
                    <ul>
                        <li><span>Shortcut Buttons for easy actions</span></li>
                        <li><span>CRUD actions Simplified</span></li>
                    </ul>
                    <div class="card-footer text-light text-center">
                        <span>
                            <a class="link-light link-offset-0 link-offset-2-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="#admin_dashboard">
                                Read More
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z" />
                                </svg>
                            </a>
                        </span>
                    </div>
                </div>
            </div>
            <!-- card-3 -->
            <div class="card text-light border-info rounded-4">
                <div class="card-body">
                    <h5 class="card-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="#00ffde" style="margin: 5px 15px;" class="bi bi-shield-check" viewBox="0 0 16 16">
                            <path d="M5.338 1.59a61.44 61.44 0 0 0-2.837.856.481.481 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.725 10.725 0 0 0 2.287 2.233c.346.244.652.42.893.533.12.057.218.095.293.118a.55.55 0 0 0 .101.025.615.615 0 0 0 .1-.025c.076-.023.174-.061.294-.118.24-.113.547-.29.893-.533a10.726 10.726 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.775 11.775 0 0 1-2.517 2.453 7.159 7.159 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7.158 7.158 0 0 1-1.048-.625 11.777 11.777 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 62.456 62.456 0 0 1 5.072.56z" />
                            <path d="M10.854 5.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 7.793l2.646-2.647a.5.5 0 0 1 .708 0z" />
                        </svg>
                    </h5>
                    <h4 class="card-subtitle mb-2 mt-2 text-light text-center text-uppercase">highly secure</h4>
                    <ul>
                        <li><span>Unauthorized Access Blocked</span></li>
                        <li><span>Verified Login-Register System</span></li>
                    </ul>
                    <div class="card-footer text-light text-center">
                        <span>
                            <a class="link-light link-offset-0 link-offset-2-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="#highly_secure">
                                Read More
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z" />
                                </svg>
                            </a>
                        </span>
                    </div>
                </div>
            </div>
            <!-- card-4 -->
            <div class="card text-light border-info rounded-4">
                <div class="card-body">
                    <h5 class="card-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="#00ffde" style="margin: 5px 15px;" class="bi bi-window-sidebar" viewBox="0 0 16 16">
                            <path d="M2.5 4a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1zm2-.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zm1 .5a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z" />
                            <path d="M2 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2H2zm12 1a1 1 0 0 1 1 1v2H1V3a1 1 0 0 1 1-1h12zM1 13V6h4v8H2a1 1 0 0 1-1-1zm5 1V6h9v7a1 1 0 0 1-1 1H6z" />
                        </svg>
                    </h5>
                    <h4 class="card-subtitle mb-2 mt-2 text-light text-center text-uppercase">all device support</h4>
                    <ul>
                        <li><span>Fully Responsive for Every Device</span></li>
                        <li><span>Easy Layout Optimized</span></li>
                    </ul>
                    <div class="card-footer text-light text-center">
                        <span>
                            <a class="link-light link-offset-0 link-offset-2-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="#all_device_support">
                                Read More
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z" />
                                </svg>
                            </a>
                        </span>
                    </div>
                </div>
            </div>
            <!-- card-5 -->
            <div class="card text-light border-info rounded-4">
                <div class="card-body">
                    <h5 class="card-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="#00ffde" style="margin: 5px 15px;" class="bi bi-graph-up-arrow" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M0 0h1v15h15v1H0V0Zm10 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-1 0V4.9l-3.613 4.417a.5.5 0 0 1-.74.037L7.06 6.767l-3.656 5.027a.5.5 0 0 1-.808-.588l4-5.5a.5.5 0 0 1 .758-.06l2.609 2.61L13.445 4H10.5a.5.5 0 0 1-.5-.5Z" />
                        </svg>
                    </h5>
                    <h4 class="card-subtitle mb-2 mt-2 text-light text-center text-uppercase">track your progress</h4>
                    <ul>
                        <li><span>Set Goal to Achieve</span></li>
                        <li><span>Generate Monthly Report Card</span></li>
                    </ul>
                    <div class="card-footer text-light text-center">
                        <span>
                            <a class="link-light link-offset-0 link-offset-2-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="#track_your_progress">
                                Read More
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z" />
                                </svg>
                            </a>
                        </span>
                    </div>
                </div>
            </div>
            <!-- card-6 -->
            <div class="card text-light border-info rounded-4">
                <div class="card-body">
                    <h5 class="card-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="#00ffde" style="margin: 5px 15px;" class="bi bi-chat-text" viewBox="0 0 16 16">
                            <path d="M2.678 11.894a1 1 0 0 1 .287.801 10.97 10.97 0 0 1-.398 2c1.395-.323 2.247-.697 2.634-.893a1 1 0 0 1 .71-.074A8.06 8.06 0 0 0 8 14c3.996 0 7-2.807 7-6 0-3.192-3.004-6-7-6S1 4.808 1 8c0 1.468.617 2.83 1.678 3.894zm-.493 3.905a21.682 21.682 0 0 1-.713.129c-.2.032-.352-.176-.273-.362a9.68 9.68 0 0 0 .244-.637l.003-.01c.248-.72.45-1.548.524-2.319C.743 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7-3.582 7-8 7a9.06 9.06 0 0 1-2.347-.306c-.52.263-1.639.742-3.468 1.105z" />
                            <path d="M4 5.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zM4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8zm0 2.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5z" />
                        </svg>
                    </h5>
                    <h4 class="card-subtitle mb-2 mt-2 text-light text-center text-uppercase">message portal</h4>
                    <ul>
                        <li><span>Message to everyone</span></li>
                        <li><span>Clean chatting interface</span></li>
                    </ul>
                    <div class="card-footer text-light text-center">
                        <span>
                            <a class="link-light link-offset-0 link-offset-2-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="#message_portal">
                                Read More
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z" />
                                </svg>
                            </a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>






    <!-- detailed card 1 starts here-->
    <div class="container p-5 min-vh-100 d-flex justify-content-center align-items-center" id="qr_scanner">
        <div class="card text-bg-dark text-center w-75" style="background-color: rgba(17, 17, 17, 0.85) !important; box-shadow: 0 0 5px 1px #088aa4 !important; color: white !important;">
            <div class="card-header text-uppercase">
                Feature 1
            </div>
            <div class="card-body">
                <h5 class="card-title">QR Code Scanner</h5>
                <p class="card-text">
                <ul class="list-group list-group-flush" data-bs-theme="dark">
                    <li class="list-group-item">Highly Secure QR Code Scanner</li>
                    <li class="list-group-item">Accurate Scanning System</li>
                    <li class="list-group-item">Very Efficient Scanning System</li>
                    <li class="list-group-item">Bounded with-in time limit</li>
                    <li class="list-group-item">Works on every device</li>
                    <li class="list-group-item">Attendance taking made very easy</li>
                </ul>
                </p>
            </div>
            <div class="card-footer text-body-secondary">
                <a class="link-light link-offset-0 link-offset-2-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="#features">
                    Back to features
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
    <!-- detailed card 1 ends here -->

    <!-- detailed card 2 starts here-->
    <div class="container p-5 min-vh-100 d-flex justify-content-center align-items-center" id="admin_dashboard">
        <div class="card text-bg-dark text-center w-75" style="background-color: rgba(17, 17, 17, 0.85) !important; box-shadow: 0 0 5px 1px #088aa4 !important; color: white !important;">
            <div class="card-header text-uppercase">
                Feature 2
            </div>
            <div class="card-body">
                <h5 class="card-title">Admin Dashboard</h5>
                <p class="card-text">
                <ul class="list-group list-group-flush" data-bs-theme="dark">
                    <li class="list-group-item">Shortcut Buttons for easy actions</li>
                    <li class="list-group-item">Crud Actions Simplified</li>
                    <li class="list-group-item">Very Efficient Scanning System</li>
                    <li class="list-group-item">Bounded with-in time limit</li>
                    <li class="list-group-item">Works on every device</li>
                    <li class="list-group-item">Attendance taking made very easy</li>
                </ul>
                </p>
            </div>
            <div class="card-footer text-body-secondary">
                <a class="link-light link-offset-0 link-offset-2-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="#features">
                    Back to features
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
    <!-- detailed card 2 ends here -->

    <!-- detailed card 3 starts here-->
    <div class="container p-5 min-vh-100 d-flex justify-content-center align-items-center" id="highly_secure">
        <div class="card text-bg-dark text-center w-75" style="background-color: rgba(17, 17, 17, 0.85) !important; box-shadow: 0 0 5px 1px #088aa4 !important; color: white !important;">
            <div class="card-header text-uppercase">
                Feature 3
            </div>
            <div class="card-body">
                <h5 class="card-title">Highly Secure</h5>
                <p class="card-text">
                <ul class="list-group list-group-flush" data-bs-theme="dark">
                    <li class="list-group-item">Unauthorized Access Blocked </li>
                    <li class="list-group-item">Verified Login-Register System</li>
                    <li class="list-group-item">Very Efficient Scanning System</li>
                    <li class="list-group-item">Bounded with-in time limit</li>
                    <li class="list-group-item">Works on every device</li>
                    <li class="list-group-item">Attendance taking made very easy</li>
                </ul>
                </p>
            </div>
            <div class="card-footer text-body-secondary">
                <a class="link-light link-offset-0 link-offset-2-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="#features">
                    Back to features
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
    <!-- detailed card 3 ends here -->

    <!-- detailed card 4 starts here-->
    <div class="container p-5 min-vh-100 d-flex justify-content-center align-items-center" id="all_device_support">
        <div class="card text-bg-dark text-center w-75" style="background-color: rgba(17, 17, 17, 0.85) !important; box-shadow: 0 0 5px 1px #088aa4 !important; color: white !important;">
            <div class="card-header text-uppercase">
                Feature 4
            </div>
            <div class="card-body">
                <h5 class="card-title">All Device Support</h5>
                <p class="card-text">
                <ul class="list-group list-group-flush" data-bs-theme="dark">
                    <li class="list-group-item">Fully Responsive for Every Device</li>
                    <li class="list-group-item">Easy Layout Optimized</li>
                    <li class="list-group-item">Very Efficient Scanning System</li>
                    <li class="list-group-item">Bounded with-in time limit</li>
                    <li class="list-group-item">Works on every device</li>
                    <li class="list-group-item">Attendance taking made very easy</li>
                </ul>
                </p>
            </div>
            <div class="card-footer text-body-secondary">
                <a class="link-light link-offset-0 link-offset-2-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="#features">
                    Back to features
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
    <!-- detailed card 4 ends here -->

    <!-- detailed card 5 starts here-->
    <div class="container p-5 min-vh-100 d-flex justify-content-center align-items-center" id="track_your_progress">
        <div class="card text-bg-dark text-center w-75" style="background-color: rgba(17, 17, 17, 0.85) !important; box-shadow: 0 0 5px 1px #088aa4 !important; color: white !important;">
            <div class="card-header text-uppercase">
                Feature 5
            </div>
            <div class="card-body">
                <h5 class="card-title">Track Your Progress</h5>
                <p class="card-text">
                <ul class="list-group list-group-flush" data-bs-theme="dark">
                    <li class="list-group-item">Set Goal to Achieve </li>
                    <li class="list-group-item">Generate Monthly Report Card </li>
                    <li class="list-group-item">Very Efficient Scanning System</li>
                    <li class="list-group-item">Bounded with-in time limit</li>
                    <li class="list-group-item">Works on every device</li>
                    <li class="list-group-item">Attendance taking made very easy</li>
                </ul>
                </p>
            </div>
            <div class="card-footer text-body-secondary">
                <a class="link-light link-offset-0 link-offset-2-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="#features">
                    Back to features
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
    <!-- detailed card 5 ends here -->

    <!-- detailed card 6 starts here-->
    <div class="container p-5 min-vh-100 d-flex justify-content-center align-items-center" id="message_portal">
        <div class="card text-bg-dark text-center w-75" style="background-color: rgba(17, 17, 17, 0.85) !important; box-shadow: 0 0 5px 1px #088aa4 !important; color: white !important;">
            <div class="card-header text-uppercase">
                Feature 6
            </div>
            <div class="card-body">
                <h5 class="card-title">Message Portal</h5>
                <p class="card-text">
                <ul class="list-group list-group-flush" data-bs-theme="dark">
                    <li class="list-group-item">Message to Everyone</li>
                    <li class="list-group-item">Clean Chatting Interface</li>
                    <li class="list-group-item">Very Efficient Scanning System</li>
                    <li class="list-group-item">Bounded with-in time limit</li>
                    <li class="list-group-item">Works on every device</li>
                    <li class="list-group-item">Attendance taking made very easy</li>
                </ul>
                </p>
            </div>
            <div class="card-footer text-body-secondary">
                <a class="link-light link-offset-0 link-offset-2-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="#features">
                    Back to features
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
    <!-- detailed card 6 ends here -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script>
        $(window).on('load', function() {
            $('.my_loader').css("z-index", "1100");
            $('.my_loader').fadeOut(1000);
            $('body').fadeIn(1000);
        });
    </script>
</body>

</html>