<?php
class Student
{
    private $id;
    private $email;
    private $secret_key = '$2y$10$b1PT6x2LheA3sS7UJjOUEeU1vHp/r1RRFdo/6PqM1ZJooCxHF4lvK';
    public $details;
    public $attendanceDetails = array(
        array("label" => "January"),
        array("label" => "February"),
        array("label" => "March"),
        array("label" => "April"),
        array("label" => "May"),
        array("label" => "June"),
        array("label" => "July"),
        array("label" => "August"),
        array("label" => "September"),
        array("label" => "October"),
        array("label" => "November"),
        array("label" => "December")
    );

    public function __construct($pdo, $email)
    {
        $this->email = $email;

        // setting all details for student
        $getStudentData = "SELECT * FROM `student_profile` WHERE `student_email` ='$this->email'";
        $query = $pdo->prepare($getStudentData);
        $query->execute();

        // checking if student has created a profile or not
        $numRows = $query->rowCount();
        if ($numRows == 1) {
            $student = $query->fetch(PDO::FETCH_ASSOC);
            $this->id = $student['student_id'];
            $this->details['name'] = $student['student_name'];
            $this->details['phone'] = $student['student_phone'];
            $this->details['month'] = strtolower(date("F"));

            if ($student['profile_picture'] != null) {
                $this->details['profile_picture'] = $student['profile_picture'];
            }

            // getting all student attendance related details
            $getStudent = "SELECT * FROM `student_attendance` WHERE `student_id` ='$this->id'";
            $query = $pdo->prepare($getStudent);
            $result = $query->execute();
            $studentData = $query->fetch(PDO::FETCH_ASSOC);
            $this->details['attendance'] = $studentData[$this->details['month']];
            $this->details['goal'] = $studentData['attendance_goal'];
            $this->details['is_locked'] = $studentData['is_locked'];
            $this->details['grade'] = $studentData['grade'];
            $this->details['remarks'] = $studentData['remarks'];
            $i = 0;
            while ($i < 12) {
                $this->attendanceDetails[$i]['y'] = $studentData[strtolower($this->attendanceDetails[$i]['label'])];
                $i += 1;
            }

        }
        else {
            unset($_SESSION['student_loggedin']);
            // student has registered but never created the profile
            $registration = new Register($this->email);
            $_SESSION['student_register'] = $registration;
            header("Location: /Minor_Project/Student_Attendance_System/studentProfile.php");
        }




    }

    public function get_grade_remarks()
    {
        if ($this->details['grade']) {
            #TODO: calculate grade using attendance goal and current attendance
        }


        switch ($this->details['grade']) {
            case 'A':
                $remarks = "Good";
                break;
            case 'B':
                $remarks = "Well";
                break;
            case "C":
                $remarks = "Fine";
                break;
            #TODO: add more cases
            default:
                $remarks = "Initial";
                break;
        }
    }

    public function update_grade_remarks($pdo, $results){
        $remarksUpdate = "";  //TODO: write sql to update remarks and grade
        $stmt = $pdo->prepare($remarksUpdate);
        $stmt->execute();
    }

    public function getAttendanceDetails()
    {
        return $this->attendanceDetails;
    }

    public function setAttendanceDetails(){
        $i = 0;
        while ($i < 12) {
            if(strtolower($this->attendanceDetails[$i]['label']) == $this->details['month']){
                $this->attendanceDetails[$i]['y'] = $this->details['attendance'];
            }
            $i += 1;
        }
    }

    public function getStudentDetails()
    {
        return $this->details;
    }

    public function getAttendance($pdo, $post)
    {
        // checking if correct qr code is scanned or not
        if ($post["secret_key"] == "$this->secret_key") {
            $month = $this->details['month'];
            $this->details['attendance'] = (int)$this->details['attendance'] + 1;
            $newAttendance = $this->details['attendance'];
            $updateQuery = "UPDATE `student_attendance` SET `$month` = $newAttendance WHERE `student_id` = '$this->id';";
            $query = $pdo->prepare($updateQuery);
            $result = $query->execute();
            return 2;  // scanning successful
        }
        else {
            return 1; // wrong qr code is scanned
        }
    }

    public function sendMessage($pdo, $post){
        $message = filter_var($post['message'], FILTER_SANITIZE_SPECIAL_CHARS);
        $sendMessage = "INSERT INTO `messages` (`student_id`,`student_message`) VALUES ('$this->id','$message')";
        $query = $pdo->prepare($sendMessage);
        if($query->execute()){
            return 1;
        }
        else{
            return -1;
        }
    }

}

?>