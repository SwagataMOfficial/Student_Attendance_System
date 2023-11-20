<?php
require('classes/ContactUsClass.php');
$contact = new ContactUs();

if (isset($_POST['sent']) && $_POST['sent'] == "sent") {
	$sent = $contact->submitQuery($pdo, $_POST);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Contact Us</title>
	<link rel="stylesheet" href="css/contact_us.css" />
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>

<body>
	<header>
		<nav>
			<a href="/Minor_Project/Student_Attendance_System/" class="nav-logo">Classified.in</a>
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
				</form>
			</div>
		</div>
	</main>
	<script>
		const loginBtn = document.getElementById('loginBtn');
		const signUpBtn = document.getElementById('signUpBtn');
		const closeBtn = document.getElementById('closeBtn');

		closeBtn.addEventListener('click', () => {
			document.querySelector('.login-signup').style.display = 'none';
		});

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

	<script>
		<?php
		if (isset($sent)) {
			switch ($sent) {
				case 1:
					echo "alert('Your Concern has been submitted!');";
					break;
				case -1:
					echo "alert('Failed to submit your concern!');";
				break;
				default:
					break;
			}
		}
		?>
	</script>
</body>

</html>