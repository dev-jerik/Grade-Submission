<?php 
	include_once 'Helper.php';
	class StaffModel extends Helper{
		public function __construct($DB_CON){
            parent::__construct($DB_CON);
        }

        public function getDept($deptID){
            $sql = "SELECT * FROM department where  deptID=".$deptID;
            $data = $this->executeQuery($sql);
            return $data;
        }

        public function getDeptList(){
            $sql = "SELECT * FROM department";
            $data = $this->executeQuery($sql, null, 'fetchAll');
            return $data;
        }

		public function getSubjectListByStaff($instID, $season){
            $sql = "Select o.offerID, o.offerNum, o.subType,s.subCode, s.subDesc from p".$season."offering as o INNER JOIN subject s on s.subID=o.subID where o.subType='Lec' and o.instID=".$instID;
                    
            $data = $this->executeQuery($sql, null, 'fetchAll');
            return $data;
        }

        public function getInstructorListByDept($deptID){
            $sql = "SELECT instructor.instID, instructor.lName, instructor.fName, instructor.mName 
                FROM instructor
                WHERE deptID=".$deptID."   ORDER BY instructor.fName";
            // $sql = "SELECT offer.offerID, offer.instID, instructor.lName, instructor.fName, instructor.mName FROM p".$season."offering as offer
            //     INNER JOIN instructor ON instructor.instID=offer.instID
            //     WHERE offer.deptID=".$deptID." GROUP BY instructor.instID
            //     ORDER BY instructor.fName";
                // $sql = "SELECT offer.offerID, offer.instID, instructor.lName, instructor.fName, instructor.mName FROM p".$season."offering as offer
                // INNER JOIN instructor ON instructor.instID=offer.instID
                // INNER JOIN room ON offer.roomID=room.roomID
                // WHERE room.deptID=".$deptID." GROUP BY instructor.instID
                // ORDER BY instructor.fName";
                    
            $data = $this->executeQuery($sql, null, 'fetchAll');
            return $data;
        }

        public function getSubjectApprovalStatus($offerID, $season, $term){
            $sql = "SELECT * FROM p".$season."approval where offerID=".$offerID." and grading='".$term."'";
                    
            $data = $this->executeQuery($sql);
            return $data;
        }

        public function updateMidtermGrade($offerID, $studLevelID, $grade, $season){
            $sql ="UPDATE p".$season."grade SET `Midterm`=? where offerID=? and StudLevelID=?";
            $arrayParam = array(0=>$grade, 1=>$offerID, 2=>$studLevelID);
            return $this->executeQuery($sql, $arrayParam, null); 
        }
        public function updateFinalGrade($offerID, $studLevelID, $grade, $season){
            $sql ="UPDATE p".$season."grade SET `Final`=? where offerID=? and StudLevelID=?";
            $arrayParam = array(0=>$grade, 1=>$offerID, 2=>$studLevelID);
            return $this->executeQuery($sql, $arrayParam, null); 
        }

        public function getStudentStanding($studLevelID, $season){
            $sql = "SELECT stud.StudID, stud.LastName,stud.FirstName, stud.MiddleName, course.crsCode, major.majorName, enroll.yrLevel
                    FROM p".$season."enroll as enroll
                    INNER JOIN students stud on stud.StudID=enroll.StudID
                    INNER JOIN major ON major.majorID=stud.majorID
                    INNER JOIN course ON course.crsID=major.crsID
                    WHERE enroll.studLevelID=?";
            $arrayParam = array(0=>$studLevelID);
            return $this->executeQuery($sql, $arrayParam); 
        }
        public function getStudentClassRecord($offerID, $studLevelID, $season){
            $sql="Select enroll.StudLevelID, stud.StudID, stud.LastName, stud.FirstName, stud.MiddleName, major.majorName, enroll.yrLevel,
                grade.Midterm, grade.Final, offer.offerNum, subject.subCode, subject.subDesc
                FROM p".$season."class AS class 
                INNER JOIN p".$season."enroll enroll ON class.StudLevelID=enroll.StudLevelID 
                INNER JOIN p".$season."grade grade ON (class.studLevelID=grade.StudLevelID AND grade.offerID=class.offerID)
                INNER JOIN p".$season."offering offer ON class.offerID=offer.offerID
                INNER JOIN subject ON offer.subID=subject.subID
                INNER JOIN students stud ON stud.StudID=enroll.StudID 
                INNER JOIN major ON major.MajorID=stud.MajorID
                WHERE class.offerID=".$offerID." and enroll.StudLevelID=".$studLevelID." ORDER BY `stud`.`LastName` ASC";
                    
            $data = $this->executeQuery($sql);
            return $data;
        }

        public function getClassByOfferNo($offerID, $season){
            //class header
            $sql1 = "Select o.offerNum, s.subCode, s.subDesc, t.lName, t.fName, t.mName from p".$season."offering as o inner join subject s on o.subID=s.subID inner join instructor t on o.instID=t.instID where o.OfferID=".$offerID;

            $sql2 = "Select stud.StudID, stud.LastName, stud.FirstName, stud.MiddleName, course.crsCode, enroll.yrLevel from p".$season."class as class inner join p".$season."enroll enroll on class.StudLevelID=enroll.StudLevelID inner join students stud on stud.StudID=enroll.StudID 
                INNER join major on major.MajorID=stud.MajorID 
                INNER join course on course.crsID=major.crsID
                where class.offerID=".$offerID." ORDER BY `stud`.`LastName` ASC";  

            $header = $this->executeQuery($sql1);
            $body = $this->executeQuery($sql2, null, "fetchAll");
            $flag = $this->hasGrade($offerID, $season);
            $data = array('header'=>$header, 'body'=>$body, 'flag'=>$flag);
            return $data;
        }
        public function getStudentListBySubject($offerID, $season){
            $sql = "Select stud.StudID, stud.LastName, stud.FirstName, stud.MiddleName, major.majorName, enroll.yrLevel, enroll.studLevelID from p".$season."class as class inner join p".$season."enroll enroll on class.StudLevelID=enroll.StudLevelID inner join students stud on stud.StudID=enroll.StudID INNER join major on major.MajorID=stud.MajorID where class.offerID=".$offerID." ORDER BY `stud`.`LastName` ASC";  
            return $this->executeQuery($sql, null, "fetchAll");
        }

        public function getSubjectInfo($offerID, $season){
            // $sql="SELECT o.offerID, o.offerNum, s.units,s.subCode, s.subDesc, o.days, o.strtTime, o.endTime, r.room, d.deptDesc
            //     FROM p".$season."offering AS o
            //     INNER JOIN subject s ON o.subID=s.subID
            //     INNER JOIN room r ON o.roomID=r.roomID
            //     WHERE o.offerID=".$offerID;
            $sql="SELECT o.offerID, o.offerNum, s.units,s.subCode, s.subDesc, o.days, o.strtTime, o.endTime, r.room, d.deptDesc FROM p".$season."offering AS o INNER JOIN subject s ON o.subID=s.subID INNER JOIN room r ON o.roomID=r.roomID INNER JOIN department d ON d.deptID=r.deptID WHERE o.offerID=".$offerID;
            return $this->executeQuery($sql);
        }   

        public function getSubjectGrades($offerID, $season, $term){
            $grades= ($term=="Final")?"g.Midterm, g.Final": "g.Midterm";
            $fiter= ($term=="Final")?" AND g.Final IS NOT NULL ":"";
            $sql="Select stud.StudID, stud.LastName, stud.FirstName, stud.MiddleName, major.majorName, ".$grades.", enroll.studLevelID FROM p".$season."class as class INNER JOIN p".$season."enroll enroll ON class.StudLevelID=enroll.StudLevelID INNER JOIN students stud ON stud.StudID=enroll.StudID INNER JOIN major ON major.MajorID=stud.MajorID INNER JOIN p".$season."grade g on (enroll.studLevelID = g.StudLevelID and class.offerID=g.offerID) WHERE class.offerID=".$offerID.$fiter." ORDER BY `stud`.`LastName` ASC";
            return $this->executeQuery($sql, null, "fetchAll");
        }

        public function getSaveGrades($offerID, $season, $term){
            $sql="Select stud.StudID, stud.LastName, stud.FirstName, major.majorName, tmpGrade.Grade, enroll.studLevelID from tmptablegrade as tmpGrade
                INNER JOIN students stud on tmpGrade.StudID = stud.StudID 
                INNER JOIN major ON major.MajorID=stud.MajorID
                INNER JOIN p".$season."enroll enroll ON stud.StudID=enroll.StudID 
                WHERE tmpGrade.offerID=".$offerID." and tmpGrade.Grading='".$term."' ORDER BY `stud`.`LastName` ASC" ;
            return $this->executeQuery($sql, null, "fetchAll");
        }


        public function hasGrade($offerID, $season){
            $sql1 = "SELECT COUNT(*) as hasGrade FROM p".$season."grade where OfferID=".$offerID;
            return $this->executeQuery($sql1);
        }
       //has save grade for the subject
        public function hasSaveGrade($offerID, $term){
            $sql1 = "SELECT COUNT(*) as hasSaveGrade FROM tmptablegrade where OfferID=".$offerID." 
                    and Grading='".$term."'";
            return $this->executeQuery($sql1);
        }
       // has save grade for particular student
        public function hasStudentGrade($offerID, $term, $studID, $instID){
            $sql = "SELECT COUNT(*) as hasSaveGrade FROM tmptablegrade where OfferID=".$offerID." 
                    and Grading='".$term."' and studID='".$studID."' and instID='".$instID."'";
            return $this->executeQuery($sql);
        }
        
        public function hasOfferIDExist($offerID, $season){
            $sql = "SELECT COUNT(*) as count from p".$season."grade WHERE offerID=".$offerID;
            $data = $this->executeQuery($sql); 
            if($data['count'] > 0){
                return true;
            }else{
                return false;
            }
        }
        public function hasApproval($offerID, $season, $term){
            $sql = "SELECT COUNT(*) as count from p".$season."approval WHERE grading='".$term."' and offerID=".$offerID;
            $data = $this->executeQuery($sql); 
            if($data['count'] > 0){
                return true;
            }else{
                return false;
            }
        }

        public function hasSubmittedGrade($StudLevelID, $offerID, $season, $term){
            $criteriaStudentLevelID= ($StudLevelID == null)?"":" and StudLevelID=".$StudLevelID;
            $sql ="Select Count(Midterm) as count from p".$season."grade where offerID=".$offerID.$criteriaStudentLevelID;
            if ($term === "Final"){
                $sql ="Select Count(Final) as count from p".$season."grade where offerID=".$offerID.$criteriaStudentLevelID;
            }
            return $this->executeQuery($sql);
        }

        public function updateSubmittedGrade($StudLevelID, $offerID, $season, $term, $grade){
            $sql ="UPDATE p".$season."grade SET `StudLevelID`=?,`offerID`=?,`Midterm`=? where offerID=".$offerID." and StudLevelID=".$StudLevelID;

            if($term === "Final"){
                $sql ="UPDATE p".$season."grade SET `StudLevelID`=?,`offerID`=?,`Final`=? where offerID=".$offerID." and StudLevelID=".$StudLevelID;
            }

            $arrayParam = array(0=>$StudLevelID, 1=>$offerID, 2=>$grade);
            return $this->executeQuery($sql, $arrayParam, null); 
        }

        public function insertApproval($offerID, $season, $instID, $term){
            $sql="INSERT INTO p".$season."approval(`offerID`, `grading`, `instID`) VALUES (?,?,?)";
            $arrayParam = array(0=>$offerID, 1=>$term, 2=>$instID);
            return $this->executeQuery($sql, $arrayParam, null); 
        }

        public function updateHeadApproval($offerID, $season, $term, $approved){
            $date = date('Y/m/d h:i:s a', time());
            $sql = "UPDATE p".$season."approval SET appHead=?, headAppDate=? WHERE offerID=? and Grading=?";
             $arrayParam = array(0=>$approved, 1=>$date, 2=>$offerID, 3=>$term);
            return $this->executeQuery($sql, $arrayParam, null);
        }

        public function updateDeanApproval($offerID, $season, $term, $approved){
            $date = date('Y/m/d h:i:s a', time());
            $sql = "UPDATE p".$season."approval SET appDean=?, deanAppDate=? WHERE offerID=? and Grading=?";
             $arrayParam = array(0=>$approved, 1=>$date, 2=>$offerID, 3=>$term);
            return $this->executeQuery($sql, $arrayParam, null);
        }
        public function updateRegApproval($offerID, $season, $term, $approved){
            $date = date('Y/m/d h:i:s a', time());
            $sql = "UPDATE p".$season."approval SET appRegistrar=?, regAppDate=? WHERE offerID=? and Grading=?";
            $arrayParam = array(0=>$approved, 1=>$date, 2=>$offerID, 3=>$term);
            return $this->executeQuery($sql, $arrayParam, null);
        }

        public function insertClassList($offerID, $season){
            $studentList = $this->getStudentListBySubject($offerID, $season);
            foreach ($studentList as $student) {
                $sql = "INSERT INTO p".$season."grade(`StudLevelID`, `offerID`) VALUES (?,?)";
                $arrayParam = array(0=>$student['studLevelID'], 1=>$offerID);
                $this->executeQuery($sql, $arrayParam, null);
            }
        }

        // public function insertSubmittedGrade($StudLevelID, $offerID, $season, $term, $grade){
        //     $sql ="INSERT INTO p".$season."grade(`Midterm`,`StudLevelID`, `offerID`) VALUES (?,?,?)";
        //     if($term === "Final"){
        //         $sql ="UPDATE p".$season."grade SET `Final`=? WHERE StudLevelID=? and offerID=?";
        //     }
        //     $arrayParam = array(0=>$grade, 1=>$StudLevelID, 2=>$offerID);
        //     return $this->executeQuery($sql, $arrayParam, null); 
        // }

        public function deleteTmpGrade($offerID, $instID, $term){
            $sql="DELETE FROM `tmptablegrade` WHERE offerID=? and instID=? and Grading=?";
            $arrayParam = array(0=>$offerID, 1=>$instID, 2=>$term);
            return $this->executeQuery($sql, $arrayParam, null); 
        }

        public function insertTmpGrade($offerID, $term, $instID, $studID, $grade){
             $sql = "INSERT INTO tmptablegrade (`offerID`, `StudID`, `instID`, `Grading`, `Grade`) VALUES (?,?,?,?,?)";
             $arrayParam = array(0=>$offerID, 1=>$studID, 2=>$instID, 3=>$term, 4=>$grade);
            return $this->executeQuery($sql, $arrayParam, null);   
        }

        public function updateTmpGrade($offerID, $term, $instID, $studID, $grade){
            $sql = "UPDATE `tmptablegrade` SET Grade=? WHERE offerID=? and StudID=? and instID=? and Grading=?";
             $arrayParam = array(0=>$grade, 1=>$offerID, 2=>$studID, 3=>$instID, 4=>$term);
            return $this->executeQuery($sql, $arrayParam, null);  
        }


        public function getGradeByOfferNo($offerID, $season){
            $sql = "SELECT stud.studID, stud.LastName, stud.FirstName, course.crsCode, enroll.yrLevel, grade.MidTerm, grade.Final, grade.Completion, grade.Remark FROM p".$season."grade as grade INNER JOIN p".$season."enroll enroll on enroll.StudLevelID=grade.StudLevelID 
                INNER JOIN students stud on enroll.studID=stud.StudID 
                INNER JOIN major on major.majorID=stud.majorID
                INNER JOIN course on course.crsID=major.crsID
                WHERE grade.OfferID=".$offerID." ORDER BY `stud`.`LastName` ASC";

            $data = $this->executeQuery($sql, null, 'fetchAll');
            return $data;
        }

        public function getAllGradesOfStudent($studLevelID, $season){
            $sql = "SELECT enroll.studID,subj.subCode, subj.subDesc, subj.units,grade.Midterm, grade.Final,inst.username
                    FROM p".$season."class class
                    INNER JOIN p".$season."offering offer on offer.offerID=class.offerID
                    INNER JOIN subject subj on subj.subID=offer.subID
                    INNER JOIN p".$season."enroll enroll on enroll.studLevelID=class.StudLevelID
                    INNER JOIN instructor inst on inst.instID=offer.instID
                    LEFT JOIN p".$season."grade grade ON (grade.offerID=class.offerID and grade.StudLevelID=class.StudLevelID)
                    WHERE offer.subType='Lec' and enroll.StudLevelID=? ORDER BY subj.subCode ";
            $arrayParam = array(0=>$studLevelID);
            return $this->executeQuery($sql, $arrayParam,"fetchAll"); 
        }

        public function searchStudent($search, $season){
            $sql = "Select stud.StudID, stud.LastName, stud.FirstName, stud.MiddleName, enroll.studLevelID 
                    FROM p".$season."enroll as enroll
                    INNER JOIN students stud on stud.StudID=enroll.StudID 
                    WHERE stud.StudID Like '%".$search."%' ORDER BY `stud`.`LastName` ASC";  
            return $this->executeQuery($sql, null, "fetchAll");
        }
        public function getCourses(){
            $sql = "SELECT * FROM `course` ORDER BY `course`.`crsCode` ASC";  
            return $this->executeQuery($sql, null, "fetchAll");
        }

        public function getAllStudentByCourse($crsID, $season){
            $sql = "SELECT enroll.studLevelID, stud.StudID, stud.LastName, course.crsCode from students as stud
                    INNER JOIN p".$season."enroll enroll on enroll.StudID=stud.StudID
                    INNER JOIN major ON major.majorID=stud.majorID
                    INNER JOIN course ON course.crsID=major.crsID
                    WHERE course.crsID=? ORDER BY stud.LastName ASC";
            $arrayParam = array(0=>$crsID);  
            return $this->executeQuery($sql, $arrayParam, "fetchAll");
        }
	}
 ?>