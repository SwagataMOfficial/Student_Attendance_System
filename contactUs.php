<?php
require("partitions/_dbconnect.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// function to send a mail
function sendQuery($name, $phone, $email, $message)
{
    // including all necessary files
    require("PHPMailer/PHPMailer.php");
    require("PHPMailer/SMTP.php");
    require("PHPMailer/Exception.php");

    $mail = new PHPMailer(true);

	$current_date = (string)date('d-M-Y');

    // setting the mail
    try {
        //Server settings
        $mail->isSMTP(); //Send using SMTP
        $mail->Host = 'smtp.gmail.com'; //Set the SMTP server to send through
        $mail->SMTPAuth = true; //Enable SMTP authentication
        $mail->Username = 'attendancesystem24x7@gmail.com'; //SMTP username
        $mail->Password = 'ymfu zmou nlvr dwjx'; //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
        $mail->Port = 465; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('attendancesystem24x7@gmail.com', 'Student Attendance');
        $mail->addAddress('attendancesystem24x7@gmail.com'); // receiver's email address

        //Content
        $mail->isHTML(true); //Set email format to HTML
        $mail->Subject = 'Concern from ' . $name . ' | ' . $current_date;
        $mail->Body = 
		"
			<h4>Date: $current_date</h4>
			<h4>Dear Student Attendance Team,</h4>
			<p style='padding-left: 20px;'>One of your user, who's name is $name, phone number is $phone and email is $email submitted a concern. That is, </p>
			<p style='padding-left: 20px;'>$message</p>
			<h4 style='padding-left: 20px;'>Kindly respond to this email and solve user's problem as soon as possible.</h4>
			<p>With Regards,</p>
			<p>$name</p>
			<p>$phone</p>
			<p>$email</p>
		";

        $mail->send();
        return true;
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        return false;
    }
}

if (isset($_POST['sent']) && $_POST['sent'] == "sent") {
	$name = filter_var($_POST["name"], FILTER_SANITIZE_SPECIAL_CHARS);
	$phone = filter_var($_POST["phone"], FILTER_SANITIZE_SPECIAL_CHARS);
	$email = filter_var($_POST["email"], FILTER_SANITIZE_SPECIAL_CHARS);
	$message = filter_var($_POST["message"], FILTER_SANITIZE_SPECIAL_CHARS);
	if(sendQuery($name, $phone, $email, $message)){
		$sql = "INSERT INTO `contact_us` (`name`, `phone_number`, `email`, `user_concern`) VALUES ('$name', '$phone', '$email', '$message')";
		$query = $pdo->prepare($sql);
		$sent = $query->execute();
	}
	else{
		$notsend = true;
	}
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Contact Us</title>
	<link rel="stylesheet" href="css/contact_us.css" />
	<link rel="stylesheet"
		href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>

<body>
	<header>
		<nav>
			<div><a href="/Minor_Project/Student_Attendance_System/" class="nav-logo">Classified.in</a></div>
			<div class="nav-lists">
				<ul>
					<li><a href="/Minor_Project/Student_Attendance_System/">Home</a></li>
					<li><a href="#">Docs</a></li>
					<li><a href="#">Blog</a></li>
					<li><a href="contactUs.php">Contact Us</a></li>
				</ul>
			</div>
			<div class="buttons">
				<button class="btn" id="loginBtn">Login</button>
				<button class="btn" id="signUpBtn">Sign Up</button>
			</div>
		</nav>
	</header>
	<main>
		<div class="login-signup">
			<span role="button" class="material-symbols-outlined" id="closeBtn">close</span>
			<div class="login-options">
				<a href="loginStudent.php">Login as Student</a>
				<a href="loginTeacher.php">Login as Teacher</a>
			</div>
			<div class="signup-options">
				<a href="registerStudent.php">Sign up as Student</a>
				<a href="registerTeacher.php">Sign up as Teacher</a>
			</div>
		</div>
		<div class="container">
			<div class="content">
				<div class="image-box">
					<img src="assets/contact.png" alt="background image" />
				</div>
				<form method="post" autocomplete="off">
					<div class="topic">Enter Your Concern Below</div>
					<div class="input-box">
						<input type="text" name="name" id="name" required />
						<label for="name">Enter your Full Name</label>
					</div>
					<div class="input-box">
						<input type="tel" name="phone" id="phone" required />
						<label for="phone">Enter your Phone Number</label>
					</div>
					<div class="input-box">
						<input type="email" name="email" id="email" required />
						<label for="email">Enter your Email ID</label>
					</div>
					<div class="message-box">
						<textarea name="message" id="message" required></textarea>
						<label for="message">Enter your Message</label>
					</div>
					<div class="input-box">
						<button type="submit" name="sent" value="sent">Submit</button>
					</div>
					<?php
					if(isset($sent) && $sent) {
						echo 
						'
							<p class="confirm-submit">
							<span class="confirm-text">Your Concern has been submitted</span>
							<span role="button" class="material-symbols-outlined" id="confirmCloseBtn">close</span>
							</p>
						';
					}
					if(isset($notsend) && $notsend) {
						echo 
						'
							<p class="non-confirm-submit">
							<span class="confirm-text">Failed to submit your concern! Please Try Again</span>
							<span role="button" class="material-symbols-outlined" id="nonConfirmCloseBtn">close</span>
							</p>
						';
					}
					?>
				</form>
			</div>
		</div>
	</main>
	<script>
		const loginBtn = document.getElementById('loginBtn');
		const signUpBtn = document.getElementById('signUpBtn');
		const closeBtn = document.getElementById('closeBtn');
		const confirmCloseBtn = document.getElementById('confirmCloseBtn');
		const nonConfirmCloseBtn = document.getElementById('nonConfirmCloseBtn');

		closeBtn.addEventListener('click', () => {
			document.querySelector('.login-signup').style.display = 'none';
		});

		<?php
			if(isset($sent) && $sent){
				echo "confirmCloseBtn.addEventListener('click', () => {
					document.querySelector('.confirm-submit').style.display = 'none';
				});";
			}
			if(isset($notsend) && $notsend){
				echo "
				nonConfirmCloseBtn.addEventListener('click', () => {
					document.querySelector('.non-confirm-submit').style.display = 'none';
				});
				";
			}
		?>		

		loginBtn.addEventListener('click', () => {
			document.querySelector('.login-signup .signup-options').style.display = 'none';
			document.querySelector('.login-signup').style.display = 'block';
			document.querySelector('.login-signup .login-options').style.display = 'flex';

		});

		signUpBtn.addEventListener('click', () => {
			document.querySelector('.login-signup .login-options').style.display = 'none';
			document.querySelector('.login-signup').style.display = 'block';
			document.querySelector('.login-signup .signup-options').style.display = 'flex';
		});
	</script>
</body>

</html>