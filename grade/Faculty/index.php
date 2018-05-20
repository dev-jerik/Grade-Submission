<?php
	include "../Common/header.php";

	$period = $_SESSION["period"];
	$syr = intval($period/10);
	$sem = $period % 10;
	$arrPeriod = array(1 => "First Semester", 2 =>"Second Semester", 
		               3 => "Summer"); 	
	$sysem = $syr." - ".($syr + 1)." ".$arrPeriod[$sem];
	
?>
   <div id="body">
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
        	</div><!--end of main row-->
       </div>
   </div>
   <?php include "../Common/footer.php" ?>