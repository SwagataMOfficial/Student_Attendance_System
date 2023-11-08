<?php
class Teacher
{
    private $id;
    private $email;
    public $details;

    public function __construct($pdo, $email)
    {
        $this->email = $email;

        $getTeacherData = "SELECT * FROM `teacher_profile` WHERE `teacher_email`='$this->email'";
        $query = $pdo->prepare($getTeacherData);
        $result = $query->execute();

        // checking if teacher has created a profile or not.
        $numRows = $query->rowCount();
        if ($numRows == 1) {
            $teacher = $query->fetch(PDO::FETCH_ASSOC);
            $this->id = $teacher['teacher_id'];
            $this->details['name'] = $teacher['teacher_name'];
            if (isset($teacher['hod_department'])) {
                $this->details['hod'] = $teacher['hod_department'];
            }
            if (isset($teacher['profile_picture'])) {
                $this->details['profile_picture'] = $teacher['profile_picture'];
            }
        }
        else{
        unset($_SESSION['teacher_loggedin']);
        // teacher has registered but never created the profile
        $registration = new Register($this->email);
        $_SESSION['teacher_register'] = $registration;
        header("Location: /Minor_Project/Student_Attendance_System/teacherProfile.php");
        }
    }

    public function getTeacherDetails()
    {
        return $this->details;
    }

    public function editStudent($pdo, $post){

        // profile update query
        $month = strtolower(date('F'));
        $profileUpdate = "UPDATE `student_profile` SET `student_name`='$post[s_name]',`student_phone`='$post[s_phone]',`student_email`='$post[s_email]',`student_stream`='$post[s_stream]',`student_semester`='$post[s_sem]' WHERE `student_id`='$post[s_id]'";
        $update = $pdo->prepare($profileUpdate);
        $result = $update->execute();
        if ($result) {
            $attendanceUpdate = "UPDATE `student_attendance` SET `student_name`='$post[s_name]',`student_stream`='$post[s_stream]',`$month`='$post[s_attendance]' WHERE `student_id`='$post[s_id]'";
            $updatation = $pdo->prepare($attendanceUpdate);
            if($updatation->execute()){
                return 1;
            }
            else{
                return 2;
            }
        }
        else{
            return 3;
        }
    }

    public function deleteStudent($pdo, $post){
        $delete_query1 = "DELETE FROM `student_registration` WHERE `student_email` = '$_POST[d_email]'";
        $query = $pdo->prepare($delete_query1);
        if ($query->execute()) {
            $delete_query2 = "DELETE FROM `student_attendance` WHERE `student_id` = '$_POST[d_id]'";
            $query = $pdo->prepare($delete_query2);
            if ($query->execute()) {
                $delete_query3 = "DELETE FROM `student_profile` WHERE `student_id` = '$_POST[d_id]'";
                $query = $pdo->prepare($delete_query3);
                if ($query->execute()) {
                    $delete_query4 = "DELETE FROM `messages` WHERE `student_id` = '$_POST[d_id]'";
                    $query = $pdo->prepare($delete_query4);
                    if($query->execute()){
                        return 1;
                    }
                    else{
                        return 2;
                    }
                }
                else{
                    return 3;
                }
            }
            else{
                return 4;
            }
        }
        else{
            return 5;
        }
    }

    public function setAttendanceGoal($pdo, $post){
        $attendance_goal_query = "UPDATE `student_attendance` SET `attendance_goal` = '$post[attendance_goal]';";
        $goalSetting = $pdo->prepare($attendance_goal_query);
        if($goalSetting->execute()){
            return 1;
        }
        else{
            return -1;
        }
    }

    public function lock_unlock_scanner($pdo, $post){
        if ($post['lock_unlock'] == 'lock') {
            $query = "UPDATE `student_attendance` SET `is_locked` = '1' WHERE `student_id` = '$post[lock_unlock_id]'";
            $stmt = $pdo->prepare($query);
            if($stmt->execute()){
                return 1;
            }
            else{
                return 2;
            }
        } else if ($post['lock_unlock'] == 'unlock') {
            $query = "UPDATE `student_attendance` SET `is_locked` = '0' WHERE `student_id` = '$post[lock_unlock_id]'";
            $stmt = $pdo->prepare($query);
            if($stmt->execute()){
                return 3;
            }
            else{
                return 4;
            }
        }
    }

    public function getStudentWithID($pdo, $id){
        $arr = [];
        $getStudentPic = "SELECT * FROM `student_profile` WHERE `student_id`='$id'";
        $query = $pdo->prepare($getStudentPic);
        
        $getAttendance = "SELECT * FROM `student_attendance` WHERE `student_id` = '$id'";
        $attendance = $pdo->prepare($getAttendance);
        $arr['profile'] = $query;
        $arr['attendance'] = $attendance;
        return $arr;
    }

    public function getStudentAttendanceData($pdo){
        $topStudentsSql = "SELECT * FROM `student_attendance` ORDER BY `" . strtolower(date('F')) . "` DESC LIMIT 4";
        $query = $pdo->prepare($topStudentsSql);
        return $query;
    }

    public function getStudentAllData($pdo){
        $getStudentData = "SELECT * FROM `student_profile`";
        $query = $pdo->prepare($getStudentData);
        return $query;
    }

    public function getStudentByHodDepartment($pdo, $dept){
        $arr = [];

        $topStudentsSql = "SELECT * FROM `student_attendance` WHERE `student_stream`='$dept' ORDER BY `" . strtolower(date('F')) . "` DESC LIMIT 4";
        $query = $pdo->prepare($topStudentsSql);

        $getStudentData = "SELECT * FROM `student_profile` WHERE `student_stream`='$dept'";
        $query1 = $pdo->prepare($getStudentData);
        $arr['attendance'] = $query;
        $arr['profile'] = $query1;
        return $arr;
    }

    public function getStudentByLikelyId($pdo, $likelyId, $dept){
        $getStudent = "SELECT * FROM `student_profile` WHERE `student_id` LIKE '%$likelyId%' AND `student_stream` = '$dept'";
        $stmt = $pdo->prepare($getStudent);
        return $stmt;
    }

    public function sendMessage($pdo, $post){
        $message = filter_var($post['message'],FILTER_SANITIZE_SPECIAL_CHARS);
        $sendMessage = "INSERT INTO `messages` (`teacher_id`,`teacher_message`) VALUES ('$this->id','$message')";
        $query = $pdo->prepare($sendMessage);
        if($query->execute()){
            return 1;
        }
        else{
            return -1;
        }
    }

    public function unlockSemester($pdo, $date){
        if($date <= 15){
            $unlockSem = "UPDATE `student_attendance` SET `sem_unlocked`='1';";
            $stmt = $pdo->prepare($unlockSem);
            if($stmt->execute()){
                return 1;   // semester unlocked successfully
            }
            else{
                return -1;  // failed to unlock semester
            }
        }
        else{
            return 2;  // can't unlock because date is over
        }
    }
}
?>