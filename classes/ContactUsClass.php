<?php
require("partitions/_dbconnect.php");
require("../credentials.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class ContactUs extends Credentials
{


    private function sendQuery($name, $phone, $email, $message)
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
            $mail->Username = $this->getUser(); //SMTP username
            $mail->Password = $this->getPassword(); //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
            $mail->Port = 465; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom($this->getUser(), 'Student Attendance');
            $mail->addAddress($this->getUser()); // receiver's email address

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
            // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            return false;
        }
    }

    public function submitQuery($pdo, $post){
        $name = filter_var($post["name"], FILTER_SANITIZE_SPECIAL_CHARS);
        $phone = filter_var($post["phone"], FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_var($post["email"], FILTER_SANITIZE_SPECIAL_CHARS);
        $message = filter_var($post["message"], FILTER_SANITIZE_SPECIAL_CHARS);
        if($this->sendQuery($name, $phone, $email, $message)){
            $sql = "INSERT INTO `contact_us` (`name`, `phone_number`, `email`, `user_concern`) VALUES ('$name', '$phone', '$email', '$message')";
            $query = $pdo->prepare($sql);
            if($query->execute()){
                return 1;
            }
        }
        else{
            return -1;
        }
    }
}
