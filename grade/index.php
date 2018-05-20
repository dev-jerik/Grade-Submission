<?php
	session_start();

	ini_set('max_execution_time', 0);

	if (!isset($_SESSION["user_id"])) {
	    session_destroy();
	    header("Location: ../");	
	}
	if($_SESSION["user_id"] < 1) {
	    session_destroy();
	    header("Location: ../");
	}
	$staffID = $_SESSION["user_id"];
	$staffname = $_SESSION["fullName"] ;
	$period = $_SESSION["period"];
	$syr = intval($period/10);
	$sem = $period % 10;
	$arrPeriod = array(1 => "First Semester", 2 =>"Second Semester", 
		               3 => "Summer"); 	
	$sysem = $syr." - ".($syr + 1)." ".$arrPeriod[$sem];
?>

<?php include "../layouts/grade_header.php";?>

    <div class="container">
        <div class="row" style="margin-top: 50px">
			<div class="col-md-12">        
	        <div class="well">
	        <div style="text-align: center;" >
				<h1 style="font-size:30px; margin-top: 10px; color: #0046bd">Online Grade Submission Component</h1>
				<h2 style="font-size:25px; margin-top: 60px; color: #0051db"><?php echo "School Year $sysem"; ?></h2>	        
	        </div>
	        </div>			
		</div>        
    </div>
<?php include "../layouts/dept_footer.php";?>