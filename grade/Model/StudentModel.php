<?php 
	include_once 'Helper.php';
	class StudentModel extends Helper{
		public function __construct($DB_CON){
            parent::__construct($DB_CON);
        }

        public function getStudentEnroll($studID, $offerID, $season){
        	$sql = "Select enroll.studLevelID, stud.StudID, stud.FirstName, stud.MiddleName, stud.LastName FROM students as stud
				INNER JOIN p".$season."enroll enroll ON stud.StudID=enroll.StudID
                INNER JOIN p".$season."class class ON class.studLevelID=enroll.studLevelID
				WHERE stud.StudID=? and class.offerID=?";
                    
            $arrayParam = array(0=>$studID, 1=>$offerID);
            return $this->executeQuery($sql, $arrayParam);
        }
    }