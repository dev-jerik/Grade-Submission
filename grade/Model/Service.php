<?php 
	include_once 'Config.php';
	include_once 'StaffModel.php';
	$staff = new StaffModel($DB_con);
	$period = $_SESSION["period"];


	$action=isset($_POST['action'])?$_POST['action']:"";
	$userId = $_SESSION['user_id'];

	if($action == "getSubjectListByStaff"){
		$subjectList = $staff->getSubjectListByStaff($userId, $_POST['season']);
		$output = "";

		if($subjectList === 0 || count($subjectList) === 0){
			$output .= "<tr><td class='text-danger	'>No records found.</td></tr>";
		}else {
			foreach ($subjectList as $subject) {
			$output .= "<tr>
							<td style='display: none;'>".$subject['offerID']."</td> 
	                        <td>".$subject['offerNum']."</td> 
	                        <td>".$subject['subCode']."</td> 
	                        <td title='".$subject['subDesc']."'>".
	                        (strlen($subject['subDesc']) < 24 ? 
	                        	$subject['subDesc'] :
	                        	substr($subject['subDesc'],0,24)."..."
	                        )
	                        ."</td> 
                        </tr> ";	
			}
		}		
		echo $output;
	}
	else if($action == "getClassByOfferNo"){
		$data = $staff->getClassByOfferNo($_POST['offerID'], $_POST['season']);
		$header = $data['header'];
		$flag = $data['flag'];
		$studentList = $data['body'];
		
		$teacherName=getFullName($header['lName'],$header['fName'],$header['mName']);

		// $viewButton=($flag['hasGrade'] > 0)?"<button type='button' class='pull-right btn btn-success btn-sm' data-target='#viewGrades-Modal' data-toggle='modal' id='viewGrades' data-subject='".$header['subCode']." - Grades' data-offer=".$_POST['offerID']." >View Grades</button>":" ";
		$viewButton=($flag['hasGrade'] > 0)?"<a href='../report/subjectGrade.php?offerID={$_POST['offerID']}' target='_blank' class='pull-right btn btn-success btn-sm'>View Grades</a>":" ";

		$html='<label class="pull-right">Number of Students: <small>'.($studentList!==0?count($studentList):'0').'</small></label>
			<label>Teacher: <small>'.$teacherName.'</small></label><br />'.$viewButton.'			
			<label>Subject: <small>'.$header['subCode'].' - '.$header['subDesc'].'</small></label><br/>
			<label>Offering #: <small>'.$header['offerNum'].'</small></label><br />
			<table class="table table-condensed table-hover table-bordered" style="font-size: 12px;">
				<thead>
				<tr>
				<th style="width: 8em;">Student ID</th> <th>Name</th> <th>Degree</th><th class="text-center">Year</th>
				</tr>
				</thead>
				<tbody>';
				if($studentList !== 0){
					foreach ($studentList as $student) {
						$studentName = getFullName($student['LastName'], $student['FirstName'], $student['MiddleName']);
						$html  .="<tr>
						<td>".$student['StudID']."</td> <td>".$studentName."</td> <td>".$student['crsCode']."</td><td class='text-center'>".$student['yrLevel']."</td>
						</tr>";
					}
				}
				

		$html .= '</tbody>
				</table>
				'.$viewButton.'
				<br /><br />';
        echo $html;
	} else if($action == "getGradeByOfferNo"){
		$grades = $staff->getGradeByOfferNo($_POST['offerID'], $_POST['season']);
		$output = "";
		foreach ($grades as $grade) {
			$studentName = getFullName($grade['LastName'], $grade['FirstName'], null);
			$output .= "<tr><td>".$grade['studID']."</td><td>".$studentName."</td><td class='text-center'>".$grade['MidTerm']."</td><td class='text-center'>".$grade['Final']."</td><tr/>";			
		}
		echo $output;
	} else if ($action == "getSubjectStatus"){
		$subjectList = $staff->getSubjectListByStaff($userId, $_POST['season']);
		$output = "";
		if($subjectList !== 0 && count($subjectList) != 0){
			foreach ($subjectList as $subject) {
				if($subject['subType'] === "Lec"){
					$approval = $staff->getSubjectApprovalStatus($subject['offerID'], $_POST['season'], $_POST['term']);
					$appDept = "<td><small>N/A</small></td>";
					$appDean = "<td><small>N/A</small></td>";
					$appReg = "<td><small>N/A</small></td>";
					$gradeStatus = "<td class='text-danger'>Not yet submitted</td>";
					//check approval
					if($approval !== null){
						if($approval['appHead'] === '1'){
							$appDept = "<td class='text-success'>Approved</td>";
						}else if($approval['appHead'] === '0'){
							$appDept = "<td class='text-warning'>Pending</td>";
						}
						if($approval['appDean'] === '1'){
							$appDean = "<td class='text-success'>Approved</td>";
						}else if($approval['appDean'] === '0'){
							$appDean = "<td class='text-warning'>Pending</td>";
						}
						if($approval['appRegistrar'] === '1'){
							$appReg = "<td class='text-success'>Approved</td>";
						}else if($approval['appRegistrar'] === '0'){
							$appReg = "<td class='text-warning'>Pending</td>";
						}
					}
					$hasSubmitGrade = $staff->hasSubmittedGrade(null,$subject['offerID'], $_POST['season'], $_POST['term']);
					$hasSaveGrade = $staff->hasSaveGrade($subject['offerID'], $_POST['term']);

					if($hasSubmitGrade['count'] > 0){
						$gradeStatus = "<td class='text-success'>Submitted</td>";
					}else if($hasSaveGrade['hasSaveGrade'] > 0){
						$gradeStatus = "<td class='text-info'>Saved</td>";
					}
					$output .= "<tr>
									<td>".$subject['subCode']."</td> 
			                        <td><a href='#' id='status_offerID' data-offer={$subject['offerID']} style='text-decoration: none'>".$subject['offerNum']."</a></td>"
			                        .$gradeStatus.$appDept.$appDean.$appReg."
		                        </tr> ";
				}//end of if($subject['subType'] === "Lec")
            }
		}else{
            	$output .= "<tr><td class='text-danger'>No Record Found</td></tr>";
        }
		echo $output;
	} else if($action == "getGradeSubmissionView"){
		$subjectInfo = $staff->getSubjectInfo($_POST['offerID'], $_POST['season']);

		$showGradingMethod = true;
		$hasApproval = $staff->hasApproval($_POST['offerID'], $_POST['season'], $_POST['term']);
		if($hasApproval === true){
			$approval = $staff->getSubjectApprovalStatus($_POST['offerID'], $_POST['season'], $_POST['term']);
			if($approval['appHead'] === '1'){
				$showGradingMethod = false;
			}
		}

		$header =
				"<div class='col-md-12'>
					<div class='space-8'></div>	
					<div id='csvFileName' style='display:none'>{$subjectInfo['subCode']}_{$subjectInfo['offerNum']}</div>
					<div class='box-window'>
						<div class='box-header'>
							GRADES <span class='pull-right' id='holder2'><i class='fa fa-chevron-up'></i></span>
						</div> 
                      	<div class='box-body' id='box-body2'>
                      	<div class='space-6'></div>
					<table id='grade-submission-info' class='table table-condensed'>
					<tr>
						<th>Subject:</th>
						<td>{$subjectInfo['subCode']} - {$subjectInfo['subDesc']}</td>
						<th>Department:</th>
						<td>{$subjectInfo['deptDesc']}</td>
					</tr>
					<tr>
						<th>Class Schedule:</th>
						<td>{$subjectInfo['days']}, {$subjectInfo['strtTime']} - {$subjectInfo['endTime']}</td>
						<th>Room:</th>
						<td>{$subjectInfo['room']}</td>
					</tr>
					<tr>
						<th>Offering No:</th>
						<td>{$subjectInfo['offerNum']}</td>"
						.($showGradingMethod ? "
						<th>Grading Method:
						<div class='space-4'></div>
						</th>
						<td>
						<select id='gradingMethod-option'>
						<option value='1'>Electronic grade entry</option>
						<option value='2'>Upload CSV File</option>
						</select>
						<div class='space-4'></div>
						</td>
					":"")
					."
					</tr>
					</table>
					<div style='margin: 0; padding: 0; border-bottom: 1px solid #000'/>
				";
		$columnFinal = ($_POST['term']=='Final')?"<tH>Final</tH>":"";
		$displaySaveButton = $hasApproval ? "":"<button class='btn btn-info btn-sm' id='elec-saveGrade'>Save</button>";
		$button = $showGradingMethod ? $displaySaveButton
	    		." <button class='btn btn-success btn-sm' id='elec-submitGrade'>Submit</button>	":
	    		 "<a href='../report/subjectGrade.php?offerID=".$subjectInfo['offerID']."' target='new' class='btn btn-danger btn-sm'><i class='glyphicon glyphicon-print'></i> Print</a>";

		$body="
                <div class='space-4'></div>
                <div id='gradingMethod-View'>
                ".$button."
                <div class='space-6'></div>
                
                <table class='table table-condensed table-bordered' id='tbl-elecSaveGrade' style='width: 80%;'>
                  <thead>
                    <tr>
                      <th style='display: none'>studLevelID</th>
                      <th>No.</th>
                      <th style='width: 140px;'>Student Number</th>
                      <th>Name</th>
                      <th>Course</th>
                      <th class='text-center'>MidTerm</th>
                      ".$columnFinal."
                    </tr>
                  </thead>
                  <tbody>";

        $hasSavedGrade=true;
		$hasSubmitGrade=true;
		//getSubmitted Grades by term in permanent Table
		$subGrades = $staff->getSubjectGrades($_POST['offerID'], $_POST['season'], $_POST['term']);
		$midtermGrade = $staff->getSubjectGrades($_POST['offerID'], $_POST['season'], "Midterm");
		//print_r($subGrades);
		//getSavedGrades by term		
        if($subGrades==null || $subGrades==0){
        	$hasSubmitGrade=false;
        	$subGrades = $staff->getSaveGrades($_POST['offerID'], $_POST['season'], $_POST['term']);
        	
        	if($subGrades==null || $subGrades==0){
        		$hasSavedGrade=false;
        		$subGrades = $staff->getStudentListBySubject($_POST['offerID'], $_POST['season']);
        	}
        }
        $ctr = 1;
       // die($hasSubmitGrade." ".$_POST['offerID']." ".$_POST['term']);
        foreach ($subGrades as $subGrade) {
        	$studentName = getFullName($subGrade['LastName'], $subGrade['FirstName'], null);
        	$grade='';
        	if($hasSubmitGrade){
        		$grade=$subGrade[$_POST['term']];//from permanent grade p[year]grade
        	}else if($hasSavedGrade){
        		$grade=$subGrade['Grade'];//from tmpTableGrade
        	}
        	$columnFinalGrade=($_POST['term']=='Final') ? 
        				"<td class='text-center'>"
						  .($showGradingMethod ? 
						  	"<select>"
						  		.genGradeOption(isset($grade)?$grade:"").
						  	"</select>" :
						  		(isset($grade)?$grade:"")
						  )."
					   </td>":"";

        	$body .= "<tr>
					  <td style='display: none'>{$subGrade['studLevelID']}</td><td>{$ctr}</td><td>{$subGrade['StudID']}</td><td>{$studentName}</td><td >{$subGrade['majorName']}</td>
					  <td class='text-center'>"
						  .($showGradingMethod &&  ($_POST['term'] !="Final")? 
						  	"<select>"
						  		.genGradeOption($grade).
						  	"</select>" :
						  		(isset($subGrade['Midterm'])?$subGrade['Midterm']
						  		: (isset($midtermGrade) && count($midtermGrade) !=0 )?$midtermGrade[$ctr-1]['Midterm']:'')
						  )."
					   </td>
					   ".$columnFinalGrade."
					</tr>";
			$ctr++;
	    }        
			
	    

        $body .= "</tbody>
		        		</table>".$button."
		        		</div>
                    </div>
                    </div>
		        </div>";
        echo $header.$body;
		//print_r($subjectInfo);
	}
	else if($action == "saveGrades"){
		$studentGrades = stripcslashes($_POST['objJSON']);
		$offerID = $_POST['offerID'];
		$term = $_POST['term'];
		$instID = $userId;
		echo "Saved Grades.";
		// Decode the JSON array
		$studentGrades = json_decode($studentGrades,TRUE);
		foreach ($studentGrades as $studentGrade) {
			$hasGrade = $staff->hasStudentGrade($offerID, $term, $studentGrade['studNo'], $instID);
			if($hasGrade['hasSaveGrade'] > 0){
				$staff->updateTmpGrade($offerID, $term, $instID, $studentGrade['studNo'], $studentGrade['grade']);
			}else {
				$staff->insertTmpGrade($offerID, $term, $instID, $studentGrade['studNo'], $studentGrade['grade']);
			}
		}
		//echo $tableData[0]['studNo'];
	 } else if ($action == "submitGrades"){
	 	$studentGrades = stripcslashes($_POST['objJSON']);
		$offerID = $_POST['offerID'];
		$term = $_POST['term'];
		$season = $_POST['season'];
		$instID = $userId;
		echo "Grades Submitted.";
		// Decode the JSON array
		$studentGrades = json_decode($studentGrades,TRUE);
		$hasSubmitApproval = false;
		if(!$staff->hasOfferIDExist($offerID, $season)){
			$staff->insertClassList($offerID, $season);
			
		}
		if(!$staff->hasApproval($offerID, $season, $term)){
			$staff->insertApproval($offerID, $season, $instID, $term);
		}
		
		
		foreach ($studentGrades as $studentGrade) {
			$staff->updateSubmittedGrade($studentGrade['studLevelID'], $offerID, $season, $term, $studentGrade['grade']);
		}
		$staff->deleteTmpGrade($offerID, $instID, $term);
		
	 }else if($action === "headApproval"){
	 	$approvalData = stripcslashes($_POST['objJSON']);
	 	$term = $_POST['term'];
		$season = $_POST['season'];
		// Decode the JSON array
		$approvalData = json_decode($approvalData,TRUE);
		
		foreach ($approvalData as $approval) {
			$staff->updateHeadApproval($approval['offerID'], $season, $term, $approval['approved']);
		}
	 	echo "Approved successfully.";
	 } else if($action === "deanApproval"){
	 	$approvalData = stripcslashes($_POST['objJSON']);
	 	$term = $_POST['term'];
		$season = $_POST['season'];
		// Decode the JSON array
		$approvalData = json_decode($approvalData,TRUE);
		
		foreach ($approvalData as $approval) {
			$staff->updateDeanApproval($approval['offerID'], $season, $term, $approval['approved']);
		}
	 	echo "Approved successfully.";
	 } else if($action === "regApproval"){
	 	$approvalData = stripcslashes($_POST['objJSON']);
	 	$term = $_POST['term'];
		$season = $_POST['season'];
		// Decode the JSON array
		$approvalData = json_decode($approvalData,TRUE);
		
		foreach ($approvalData as $approval) {
			$staff->updateRegApproval($approval['offerID'], $season, $term, $approval['approved']);
		}
	 	echo "Approved successfully";
	 } else if($action === "getInstructorList"){
	 	$deptID = $_POST['deptID'];	 
	   echo "<option value=''></option>";

	 	$instList = $staff->getInstructorListByDept($deptID, $period);
	 	foreach ($instList as $instructor) {
	 		$name= $instructor['fName']." ".$instructor['lName'];
	 		echo "<option value={$instructor['instID']} >{$name}</option>";
	 	}
	 	
	 } else if($action === "getOfferingList"){
	 	$instID = $_POST['instID'];	 
	    echo "<option value=''></option>";

	 	$subjectList = $staff->getSubjectListByStaff($instID, $period);
	 	foreach ($subjectList as $subject) {
	 		echo "<option value={$subject['offerID']} >{$subject['offerNum']} - {$subject['subCode']}</option>";
	 	}
	 	
	 } else if($action === "getStudentList"){
	 	$offerID = $_POST['offerID'];	 
	    echo "<option value=''></option>";

	 	$studentList = $staff->getStudentListBySubject($offerID, $period);
	 	foreach ($studentList as $stud) {
	 		$name = $stud['LastName'].", ".$stud['FirstName'];
	 		echo "<option value={$stud['studLevelID']} >{$name}</option>";
	 	}
	 	
	 } else if($action === "searchStudent"){
	 	$search = trim($_POST['search']);
	 	$dataList = $staff->searchStudent($search, $period);
	 	if($dataList !=0 && count($dataList) !==0 ){
	 		foreach ($dataList as $data) {
	 			$name = getFullName($data['LastName'], $data['FirstName'], $data['MiddleName']);
	 			echo "<option value={$data['studLevelID']} >".$data['StudID']."  ".$name."</option>";
	 		}
	 	}
	 	
	 }




	function genGradeOption($valueSelected){
		$options = "	<option value=''>select</option>";
		for ($i = 1.00; $i <= 5.00;  $i += 0.25) {
			$value = number_format($i,2);
			$options .= "<option value={$value}
							".($valueSelected == $i ? 'selected': '')
						.">{$value}</option>";
		}
		$options .= "<option value='DR'
							".($valueSelected == 'DR' ? 'selected': '')
						.">DR</option>";
		$options .= "<option value='INC'
							".($valueSelected == 'INC' ? 'selected': '')
						.">INC</option>";				
		return $options;
	}
	function getFullName($lastName, $firstName, $middleName){
		return $lastName.", ".$firstName." ".(isset($middleName)?substr($middleName,0,1).".": "");
	}
 ?>