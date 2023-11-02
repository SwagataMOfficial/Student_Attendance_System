<?php
    class Message{
        public function getMessages($pdo){
            $getMessages = "SELECT * FROM `messages` ORDER BY `sno` ASC";
            $query = $pdo->prepare($getMessages);
            return $query;
        }

        public function getStudentMessage($pdo, $id, $msg){
            $student = "SELECT `student_name` FROM `student_profile` WHERE `student_id`='$id'";
            $gettingStudent = $pdo->prepare($student);
            $gettingStudent->execute();
            $student_data = $gettingStudent->fetch(PDO::FETCH_ASSOC);
            $line = '  <div class="message right">
                        <p class="sender-name"><b style="transition: 0.3s ease-in;">' . $student_data["student_name"] . '</b></p>
                        <span>' . $msg . '</span>
                    </div>';

            return $line;
        }

        public function getTeacherMessage($pdo, $id, $msg){
            $teacher = "SELECT `teacher_name` FROM `teacher_profile` WHERE `teacher_id`='$id'";
            $gettingTeacher = $pdo->prepare($teacher);
            $gettingTeacher->execute();
            $teacher_data = $gettingTeacher->fetch(PDO::FETCH_ASSOC);
            $line =  '  <div class="message left">
                        <p class="sender-name">
                        <b style="transition: 0.3s ease-in;">' . $teacher_data["teacher_name"] . '</b>
                        </p>
                        <span>' . $msg . '</span>
                    </div>';
            return $line;
        }
    }
