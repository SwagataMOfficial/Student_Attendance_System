<?php
require("../credentials.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Register extends Credentials {
    private $email;
    private $password;
    private $cpassword;
    private $otp;

    private function generateOTP() {
        $this->otp = rand(100000, 999999);
    }


    private function sendMail() {
        // including all necessary files
        require("PHPMailer/PHPMailer.php");
        require("PHPMailer/SMTP.php");
        require("PHPMailer/Exception.php");

        $mail = new PHPMailer(true);
        $this->generateOTP();

        // setting the mail
        try {
            //Server settings
            $mail->isSMTP(); //Send using SMTP
            $mail->Host = 'smtp.gmail.com'; //Set the SMTP server to send through
            $mail->SMTPAuth = true; //Enable SMTP authentication
            $mail->Username = $this->getUser(); //SMTP username
            $mail->Password = $this->getPassword(); //SMTP password

            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
            $mail->Port = 465; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom($this->getUser(), 'Student Attendance');
            $mail->addAddress($this->email); // receiver's email address

            //Content
            $mail->isHTML(true); //Set email format to HTML
            $mail->Subject = 'OTP for verification for Student Attendance';
            $mail->Body = ' Thanks for registering in our website<br>
            Your OTP is ' . $this->otp . '<br>
            <strong>Do not share OTP with Anyone else.</strong>';

            $mail->send();
            return true;
        } catch (Exception $e) {
            // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            return false;
        }
    }

    public function registerStudent($pdo, $userOTP) {
        if ($userOTP == $this->otp) {
            $sql = "INSERT INTO `student_registration` (`student_email`, `student_password`) VALUES ('$this->email', '$this->password')";
            $query = $pdo->prepare($sql);
            $query = $pdo->prepare($sql);
            if ($query->execute()) {
                return 1;
            }
        }
        else {
            return 0;
        }
    }

    public function registerTeacher($pdo, $userOTP) {
        if ($userOTP == $this->otp) {
            $sql = "INSERT INTO `teacher_registration` (`teacher_email`, `teacher_password`) VALUES ('$this->email', '$this->password')";
            $query = $pdo->prepare($sql);
            if ($query->execute()) {
                return 1;
            }
        }
        else {
            return 0;
        }
    }

    public function validate_student($post) {
        $this->email = $post['email'];
        $this->password = $post['password'];
        $this->cpassword = $post['cpassword'];

        if ($this->password == $this->cpassword) {
            // sending mail and further work
            if ($this->sendMail()) {
                $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
                $this->password = $hashedPassword;
                header("Location: /Minor_Project/Student_Attendance_System/verify_OTP.php");
            }
            else {
                return 2;
            }
        }
        else {
            return 1;
        }
    }

    public function validate_teacher($post) {
        $this->email = $post['email'];
        $this->password = $post['password'];
        $this->cpassword = $post['cpassword'];


        if ($this->password == $this->cpassword) {
            // sending mail and further work
            if ($this->sendMail()) {
                $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
                $this->password = $hashedPassword;
                header("Location: /Minor_Project/Student_Attendance_System/verify_OTP.php");
            }
            else {
                return 2;
            }
        }
        else {
            return 1;
        }
    }

    public function set_student_profile($pdo, $post) {
        if (isset($_FILES['student_profile_pic']['name'])) {
            $imageName = $_FILES['student_profile_pic']['name'];
            $imageSize = $_FILES['student_profile_pic']['size'];
            $tmpName = $_FILES['student_profile_pic']['tmp_name'];

            //Image validation
            $validImageExtention = ['jpg', 'jpeg', 'png'];
            $imageExtention = explode('.', $imageName);
            $imageExtention = strtolower(end($imageExtention));
            if (!in_array($imageExtention, $validImageExtention)) {
                return 3;
            }
            // if image size is too big then this block will execute
            else if ($imageSize > 1200000) {
                return 2;
            }
            // this block uploads the image and stores into the database
            else {
                $newImageName = $imageName . '-' . date('Y.m.d') . '-' . date('h.i.sa');
                $newImageName .= '.' . $imageExtention;

                $sql = "INSERT INTO `student_profile` ( `student_name`,  `student_phone`, `student_email`, `student_gender`, `student_stream`, `student_semester`, `profile_picture`) VALUES ('$post[student_name]', '$post[student_phone]', '$this->email', '$post[gender]', '$post[stream]', '$post[student_semester]','$newImageName')";
                $query = $pdo->prepare($sql);
                $result = $query->execute();
                if ($result) {

                    // Getting student data
                    $sql = "Select `sno`, `student_stream` from `student_profile` where `student_email`= '$this->email'";
                    $query = $pdo->prepare($sql);
                    $result = $query->execute();
                    $student = $query->fetch(PDO::FETCH_ASSOC);

                    //Updating student id

                    if ($student["sno"] < 10) {
                        $sno = "00" . $student["sno"];
                    }
                    elseif ($student["sno"] >= 10 && $student["sno"] < 100) {
                        $sno = "0" . $student["sno"];
                    }
                    else {
                        $sno = $student["sno"];
                    }
                    $sid = "S" . $student["student_stream"] . $sno;
                    $sql = "UPDATE `student_profile` SET `student_id` = '$sid' WHERE `student_email` ='$this->email'";
                    $query = $pdo->prepare($sql);
                    $result = $query->execute();
                    if ($result) {
                        $sql = "INSERT INTO `student_attendance` (`student_id`,`student_name`, `student_stream`, `remarks`, `grade`) VALUES ('$sid', '$post[student_name]', '$post[stream]', 'Initial', 'None');";
                        $query = $pdo->prepare($sql);
                        $result = $query->execute();
                    }
                }

                move_uploaded_file($tmpName, '../profile_pictures/' . $newImageName);
                return 1;
            }
        }
    }

    public function set_teacher_profile($pdo, $post) {
        if ($post['hod_select'] == "yes") {
            // this if block handles profile pic uploading
            if (isset($_FILES['teacher_profile_pic']['name'])) {
                $imageName = $_FILES['teacher_profile_pic']['name'];
                $imageSize = $_FILES['teacher_profile_pic']['size'];
                $tmpName = $_FILES['teacher_profile_pic']['tmp_name'];

                //Image validation
                $validImageExtention = ['jpg', 'jpeg', 'png'];
                $imageExtention = explode('.', $imageName);
                $imageExtention = strtolower(end($imageExtention));
                if (!in_array($imageExtention, $validImageExtention)) {
                    return 3;
                }
                // if image size is too big then this block will execute
                else if ($imageSize > 1200000) {
                    return 2;
                }
                // this block uploads the image and stores into the database
                else {
                    $newImageName = $imageName . '-' . date('Y.m.d') . '-' . date('h.i.sa');
                    $newImageName .= '.' . $imageExtention;

                    $sql = "INSERT INTO `teacher_profile` (`teacher_id`, `teacher_name`, `teacher_phone`, `teacher_email`, `teacher_gender`, `hod_department`, `profile_picture`) VALUES ('0', '$post[teacher_name]', '$post[teacher_phone]', '$this->email', '$post[gender]', '$post[department]', '$newImageName')";
                    $query = $pdo->prepare($sql);
                    $result = $query->execute();
                    if ($result) {
                        // getting serial number and email for id updation
                        $sql = "SELECT `sno` FROM `teacher_profile` WHERE `teacher_email` ='$this->email'";
                        $query = $pdo->prepare($sql);
                        $result = $query->execute();
                        $teacher = $query->fetch(PDO::FETCH_ASSOC);

                        //Updating teacher id
                        if ($teacher["sno"] < 10) {
                            $sno = "00" . $teacher["sno"];
                        }
                        elseif ($teacher["sno"] >= 10 && $teacher["sno"] < 100) {
                            $sno = "0" . $teacher["sno"];
                        }
                        else {
                            $sno = $teacher["sno"];
                        }
                        $tid = "T" . $post['department'] . $sno;

                        $sql = "UPDATE `teacher_profile` SET `teacher_id` = '$tid' WHERE `teacher_email` ='$this->email'";
                        $query = $pdo->prepare($sql);
                        $result = $query->execute();
                    }

                    move_uploaded_file($tmpName, '../profile_pictures/' . $newImageName);
                    return 1;
                }
            }
        }
        else {
            // this if block handles profile pic uploading
            if (isset($_FILES['teacher_profile_pic']['name'])) {
                $imageName = $_FILES['teacher_profile_pic']['name'];
                $imageSize = $_FILES['teacher_profile_pic']['size'];
                $tmpName = $_FILES['teacher_profile_pic']['tmp_name'];

                //Image validation
                $validImageExtention = ['jpg', 'jpeg', 'png'];
                $imageExtention = explode('.', $imageName);
                $imageExtention = strtolower(end($imageExtention));
                if (!in_array($imageExtention, $validImageExtention)) {
                    return 3;
                }
                // if image size is too big then this block will execute
                else if ($imageSize > 1200000) {
                    return 2;
                }
                // this block uploads the image and stores into the database
                else {
                    $newImageName = $imageName . '-' . date('Y.m.d') . '-' . date('h.i.sa');
                    $newImageName .= '.' . $imageExtention;

                    // creating account for non-hod teachers
                    $sql = "INSERT INTO `teacher_profile` (`teacher_id`, `teacher_name`, `teacher_phone`, `teacher_email`, `teacher_gender`, `profile_picture`) VALUES ('0', '$post[teacher_name]', '$post[teacher_phone]', '$this->email', '$post[gender]', '$newImageName')";
                    $query = $pdo->prepare($sql);
                    $result = $query->execute();
                    if ($result) {

                        // getting serial number and email for id updation
                        $sql = "SELECT `sno` FROM `teacher_profile` WHERE `teacher_email` ='$this->email'";
                        $query = $pdo->prepare($sql);
                        $result = $query->execute();
                        $teacher = $query->fetch(PDO::FETCH_ASSOC);

                        //Updating teacher id

                        if ($teacher["sno"] < 10) {
                            $sno = "00" . $teacher["sno"];
                        }
                        elseif (
                            $teacher["sno"] >= 10 && $teacher["sno"] < 100
                        ) {
                            $sno = "0" . $teacher["sno"];
                        }
                        else {
                            $sno = $teacher["sno"];
                        }
                        $tid = "T" . $sno;
                        $sql = "UPDATE `teacher_profile` SET `teacher_id` = '$tid' WHERE `teacher_email` ='$this->email'";
                        $query = $pdo->prepare($sql);
                        $result = $query->execute();
                    }

                    move_uploaded_file($tmpName, '../profile_pictures/' . $newImageName);
                    return 1;
                }
            }
        }
    }
}

class Login {
    private $email;
    private $password;

    public function __construct($post) {
        $this->email = $post['email'];
        $this->password = $post['password'];
    }
    public function verify_student($pdo) {
        $sql = "SELECT * FROM `student_registration` WHERE `student_email`='$this->email'";

        $query = $pdo->prepare($sql);
        $result = $query->execute();

        if ($result) {
            $num = $query->rowCount();
            if ($num == 1) {
                $credentials = $query->fetch(PDO::FETCH_ASSOC);
                if (password_verify($this->password, $credentials['student_password']))  {
                    return 1;   // when verification is successful
                }
                else {
                    return 2;   // when passwords don't match
                }
            }
            else {
                return 3;   // when user doesn't exists
            }
        }

    }

    public function verify_teacher($pdo){
        $sql = "SELECT * FROM `teacher_registration` WHERE `teacher_email`='$this->email'";

        $query = $pdo->prepare($sql);
        $result = $query->execute();

        if($result){
            $num = $query->rowCount();
            if ($num == 1) {
                $credentials = $query->fetch(PDO::FETCH_ASSOC);
                if (password_verify($this->password, $credentials['teacher_password']))  {
                    return 1;   // when verification is successful
                }
                else {
                    return 2;   // when passwords don't match
                }
            }
            else {
                return 3;   // when user doesn't exists
            }
        }
    }
}
?>