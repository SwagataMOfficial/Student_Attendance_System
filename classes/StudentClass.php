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
            $this->details['gender'] = $student['student_gender'];
            $this->details['stream'] = $student['student_stream'];
            $this->details['semester'] = $student['student_semester'];
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
            $this->details['is_sem_unlocked'] = $studentData['sem_unlocked'];
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

    private function _grade($value)
    {
       $attendance = (100 / 15) * $value;
       if ($attendance >= 90) {
          return "O";
       } elseif ($attendance >= 80) {
          return "E";
       } elseif ($attendance >= 70) {
          return "A";
       } elseif ($attendance >= 60) {
          return "B";
       } elseif ($attendance >= 50) {
          return "C";
       } elseif ($attendance >= 40) {
          return "D";
       } elseif ($attendance < 40) {
          return "F";
       }
    }


    private function _remarks($grade)
    {
       $remarks = "";
       switch ($grade) {
          case 'O':
             $remarks = "Outstanding";
             break;
          case 'E':
             $remarks = "Excellent";
             break;
          case 'A':
             $remarks = "Very Good";
             break;
          case 'B':
             $remarks = "Good";
             break;
          case 'C':
             $remarks = "Fair";
             break;
          case 'D':
             $remarks = "Below Average";
             break;
          case 'F':
             $remarks = "Failed";
             break;
          default:
             $remarks = "Incomplete";
             break;
       }
    
       return $remarks;
    }

    private function _calculateGrade_Remarks($attendance)
    {
        $this->details['grade'] = $this->_grade($attendance);
        $this->details['remarks'] = $this->_remarks($this->details['grade']);
    }

    public function update_grade_remarks($pdo){
        $this->_calculateGrade_Remarks($this->details['attendance']);

        // echo $this->details['remarks'] . '<br>';
        // echo $this->details['grade'];
        // exit();

        $remarks = $this->details['remarks'];
        $grade = $this->details['grade'];

        $remarksUpdate = "UPDATE `student_attendance` SET `remarks`='$remarks',`grade`='$grade' WHERE `student_id`='$this->id';";
        $stmt = $pdo->prepare($remarksUpdate);
        if($stmt->execute()){
            return true;
        }
        else{
            return false;
        }
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

    public function updateSemester($pdo, $post){
        $semUpdate = "UPDATE `student_profile` SET `student_semester`='$post[new_sem]' WHERE `student_id`='". $this->id ."';";
        $query = $pdo->prepare($semUpdate);
        if($query->execute()){
            $resetSemUnlock = "UPDATE `student_attendance` SET `sem_unlocked` = '0' WHERE `student_id` = '". $this->id ."';";
            $query = $pdo->prepare($semUpdate);
            if($query->execute()){
                return 1;
            }
            else{
                return 0;
            }
        }
        else{
            return 0;
        }
    }

}

?>