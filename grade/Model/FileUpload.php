<?php 
	//if(isset($_POST["submit"])){
		if ( isset($_FILES["file"])){
			//if there was an error uploading the file
	        if ($_FILES["file"]["error"] > 0) {
	            echo "Return Code: " . $_FILES["file"]["error"] . "<br />";

	        } else {
		        //Print file details
		        // echo "<tr>
		        // 			<td>Upload: ".$_FILES["file"]["name"]."</td>
		        // 			<td>Type: ".$_FILES["file"]["type"]."</td>
		        // 			<td>Size: ".($_FILES["file"]["size"] / 1024)." Kb</td>
		        // 	</tr>";
		        include_once 'Config.php';
				include_once 'StudentModel.php';
				$student = new StudentModel($DB_con);
				$period = $_SESSION["period"];
				$syr = intval($period/10);
    			$sem = $period % 10;
    			$season = $syr."".$sem;
				$tmpName = $_FILES["file"]["tmp_name"];
				$csvAsArray = array_map('str_getcsv', file($tmpName));
				$ctr = 0;
				$studentCount=1;
				$output = "";
				$errors = "";
				foreach ($csvAsArray as $value) {
					if($ctr === 0 ) {
						$ctr++;
						continue;
					}
					$stud = $student->getStudentEnroll(trim($value[0]), $_POST['offerID'], $season);
					if($stud && trim($value[1]) != ""){
						$output .= "<tr>
								<td style='display: none'>".$stud['studLevelID']."</td>
								<td>".$studentCount."</td>
								<td>".trim($value[0])."</td>
								<td>".getFullName($stud['LastName'],$stud['FirstName'],$stud['MiddleName'])."</td>
								<td>".trim($value[1])."</td>
						</tr>";
						$studentCount++;
					}else{
						if(count($stud) == 1 ){
							$errors .="<span class='text-danger'>Line number ".$ctr.": Student not belong in this class.</span><br />";
						}else{
							$errors .="<span class='text-danger'>Line number ".$ctr.": Empty grade. </span><br />";
						}
						
					}
					$ctr++;

				}

				if($errors !== ""){
					$errors = "<div class='bg-danger' style='padding: 8px'><h4 class='text-danger' style='border-bottom: 1px solid red;' >Errors!</h4>".$errors."</div>";
				}
				$data = array('output'=>$output,'errors'=>$errors);
				//convert array to json object, and return it back to ajax
				echo json_encode($data);
	        }
		}
	//}
	function getFullName($lastName, $firstName, $middleName){
		return $lastName.", ".$firstName." ".(isset($middleName)?substr($middleName,0,1).".": "");
	}
 ?>