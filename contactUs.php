<?php
// INSERT INTO `contact_us` (`sno`, `name`, `phone_number`, `email`, `user_concern`, `query_time`) VALUES (NULL, 'Swagata Mukherjee', '1234567890', 'email@email.com', 'your website is not working properly, please fix it as soon as possible.', current_timestamp());
require("partitions/_dbconnect.php");

// utility variables
$sent = false;

if (isset($_POST['sent']) && $_POST['sent'] == "sent") {
    $name = $_POST["name"];
    $phone = $_POST["phone"];
    $email = $_POST["email"];
    $message = $_POST["message"];

    $sql = "INSERT INTO `contact_us` (`name`, `phone_number`, `email`, `user_concern`) VALUES ('$name', '$phone', '$email', '$message')";
    // $result = mysqli_query($conn, $sql);
    $query = $pdo->prepare($sql);
    $result = $query->execute();
    if ($result) {
        $sent = true;
    }
}
?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contact Us</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
</head>

<body>
    <nav class="navbar navbar-dark navbar-expand-lg bg-dark mb-1">
        <div class="container-fluid">
            <a class="navbar-brand" href="https://getbootstrap.com/docs/5.3/getting-started/introduction/">
                <img src="https://getbootstrap.com/docs/5.3/assets/brand/bootstrap-logo.svg" alt="Logo" width="30"
                    height="24" class="d-inline-block align-text-top">
                Bootstrap
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link " aria-current="page" href="http://localhost/Minor_Project/Student_Attendance_System/">Home</a>
                    </li>
                    <li class="nav-item" style="cursor: not-allowed;">
                        <a class="nav-link disabled" aria-disabled="true" href="#">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="contactUs.php">Contact Us</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Other Options
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="loginStudent.php">Login as Student</a></li>
                            <li><a class="dropdown-item" href="loginTeacher.php">Login as Teacher</a></li>
                            <li><a class="dropdown-item" href="registerStudent.php">Sign Up as Student</a></li>
                            <li><a class="dropdown-item" href="registerTeacher.php">Sign Up as Teacher</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="setup.php">Environment Set Up</a></li>
                        </ul>
                    </li>
                </ul>
                <form class="d-flex" role="search">
                    <input class="form-control me-2" type="search" placeholder="Search...." aria-label="Search">
                    <button class="btn btn-success" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>
    <div class="container mt-3">
        <h1 class="text-center fw-bold">Contact Us</h1>
        <form method="post">
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Name</label>
                <input type="text" class="form-control" name="name" placeholder="Enter Your Name Here...."
                    id="exampleInputPassword1">
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Phone</label>
                <input type="tel" class="form-control" name="phone" placeholder="Enter Your Phone Number Here...."
                    id="exampleInputPassword1">
            </div>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Email Address</label>
                <input type="email" class="form-control" name="email" id="exampleInputEmail1"
                    placeholder="Enter Your Email Address Here...." aria-describedby="emailHelp">
                <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
            </div>
            <div class="mb-3">
                <label for="floatingTextarea" class="form-label">Your Concern</label>
                <textarea class="form-control" name="message" placeholder="Enter Your Concern Here...."
                    id="floatingTextarea"></textarea>
            </div>
            <button type="submit" name="sent" value="sent" class="btn btn-primary">Submit</button>
        </form>
        <?php
        if ($sent) {
            echo '<div class="mt-3 alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> Message sent successfully!<br>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        }
        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
        crossorigin="anonymous"></script>
</body>

</html>