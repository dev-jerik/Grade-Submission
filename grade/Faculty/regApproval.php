<?php
    include "../Common/header.php";
    include "../Model/CommonModel.php";
    include "../Model/StaffModel.php";
    $common = new CommonModel($DB_con);
    $staff = new StaffModel($DB_con);

    $period = $_SESSION["period"];
    $syr = intval($period/10);
    $sem = $period % 10;
    $term=$_SESSION['approvalTerm'];
    $arrPeriod = array(1 => "First Semester", 2 =>"Second Semester", 
                       3 => "Summer");  
    $sysem = $syr." - ".($syr + 1)." ".$arrPeriod[$sem];
    $department=$staff->getDept($_SESSION['deptID']);

    $instList = $staff->getInstructorListByDept($_SESSION["deptID"], $period);
    $miniStatus = "<span class='pull-right'>0-0 Approved   0-0 Not Approved<span>";
    $approvalButton = "<h2 style='border-bottom: 3px solid green; margin-top: 2px;'><small style='color:green;'>APPROVAL for ".$term."</small><button class='pull-right btn btn-default btn-sm' id='btn-headApproval'><i class=''></i>APPROVED</button> </h2>
      <p>".$department['deptDesc']."
      School Year: ".$sysem."</p>";

    $tblCount = 0;
?>
   <div id="body">
       <div class="container">
			<div class="row">     
				<div class='col-sm-12' id='main' style="display: ;">
					<?php echo $approvalButton; ?>
            
            <div style="clear: both;" class="space-2"></div>
            <?php if($instList != 0): ?>
            <?php foreach ($instList as $instructor): ?>
              <div class='box-window'>
                      <div class='box-header'>
                        <?php echo $instructor['fName'].' '.$instructor['mName'].' '.$instructor['lName']; 
                          $tblCount++;
                        ?>
                      </div> 
                      <div class='box-body' id='box-body1'>
                        <div class="space-2"></div>
                        <div class="box-widget border-solid-all" >
                            <table id=<?php echo "tbl-approval".$tblCount; ?> class="table table-condensed table-hover table-bordered" style="margin-bottom: 0">
                              <thead>
                                <tr>
                                  <th style="display: none">offerID</th>
                                  <th style="width: 15%;">Offering No.</th>
                                  <th style="width: 35%;">Subject</th>
                                  <th>Gradesheet Status</th>
                                  <th style="text-align: center">Action</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php  $subjectList = $staff->getSubjectListByStaff($instructor['instID'], $period);?>
                                <?php foreach ($subjectList as $subject): 
                                      $approvalStatus = $staff->getSubjectApprovalStatus($subject['offerID'], $period, $term);
                                      $status="<td class='text-danger'>Not yet submitted by the Instructor.</td>";
                                      $action="<td><input type='checkbox'  hidden /></td>";
                                      $showSubjectLink = false;

                                      if(count($approvalStatus)!=1){
                                          $showSubjectLink = true;  

                                          if($approvalStatus['appRegistrar'] === '1'){
                                            $status = "<td class='text-success'>Approved</td>";
                                            
                                          }else if($approvalStatus['appDean']=== '1' && $approvalStatus['appRegistrar'] === '0'){
                                            $status = "<td class='text-warning'>Pending</td>";
                                            $action="<td style='text-align: center'><input type='checkbox' /></td>";             
                                          }else{
                                            $status = "<td class='text-danger'>Not yet approved by the Dean</td>";
                                            $showSubjectLink = false;  
                                          }
                                                        
                                      }
                                      
                                ?>
                                  <tr>
                                    <td style="display: none"><?php echo $subject['offerID']; ?></td>
                                    <td>
                                      <?php if($showSubjectLink): ?>
                                      <a href='#'  data-target='#viewGrades-Modal' data-toggle='modal' id='headApproval-viewGrades' data-offer=<?php echo $subject['offerID']; ?> data-title='<?php echo $subject['subCode'].' - Grades'; ?>' style='text-decoration: none'>
                                      <?php echo $subject['offerNum']; ?>
                                      </a>
                                      <?php else: ?>
                                        <small><?php echo $subject['offerNum']; ?></small>
                                     <?php endif; ?>
                                    </td>
                                    <td><?php echo $subject['subCode'].' - '.$subject['subDesc']; ?></td>
                                    <?php echo $status; ?>
                                    <?php echo $action; ?>
                                  </tr>
                                <?php endforeach;
                                  
                                ?>
                              </tbody>
                            </table>
                          </div>
                      </div>
              </div>
              <div class="space-6"></div>
            <?php endforeach;?>
          <?php endif; ?>
            <?php echo $approvalButton; ?>
				</div>
			</div><!--end of main row-->
       </div>
       <!--HIDDEN ELEMENTS HERE-->
       <div style='display:none'>
           <input id='hidden_tblCount' value=<?php echo $tblCount; ?> hidden />
           <input id='hidden_approvalTerm' value=<?php echo $term; ?> />
           <input id='hidden_year' value=<?php echo $syr; ?> />
           <input id='hidden_sem' value=<?php echo $sem; ?> />
           <form><button id='refresh'>Refresh Page</button></form>
      </div>
       <!---------- Include Modal Here -------------------->
      <?php require_once ('viewGrades-Modal.php'); ?>
   </div>
   <script>
        $(document).ready(function(){  

            $("ul.link li").removeClass("active");
            $("#f-headApproval").addClass("active");

            function getSeason(){
                var year=$("#hidden_year").val();
                var sem=$("#hidden_sem").val();
                var season = year+""+sem;
                return season;
            }

            $(document).on("click", "#headApproval-viewGrades", function(e){

              e.preventDefault();
              var subjectTitle = $(this).data('title');
              var offerID = $(this).data('offer');
              var season=getSeason();
              $('#subject-title').html(subjectTitle);                    
              $('#subject-grades').html("");
              $.post("../Model/Service.php",
                  {season:season, offerID:offerID,action: "getGradeByOfferNo"},
                  function(data){
                    //alert(data)
                      $('#subject-grades').html(data);                  
                  }
              ); 
            });

            $(document).on("click", "#btn-headApproval", function(){
              var count = $('#hidden_tblCount').val();
              var TableData = new Array();
              var index = 0;
              for(var i=1; i <= count; i++){
                var tableName = "#tbl-approval"+i+" tbody tr";
                
                $(tableName).each(function(row, tr){
                  var value = $(tr).find('td:eq(4)').find('input:checked').val();
                  if(value !== undefined){
                     TableData[index]={
                        "offerID" : $(tr).find('td:eq(0)').text()
                        , "approved" : 1
                     }
                     index++;
                  }
                  
                }); 
              }  //end of for loop
              if(TableData.length == 0){
                return;
              }
              objJSON = JSON.stringify(TableData);   
              var season=getSeason();
              var term = $("#hidden_approvalTerm").val();
              $.post("../Model/Service.php",
                      {season: season, term:term, objJSON, objJSON, action: "regApproval"},
                      function(data){
                        alert(data);
                        $("#refresh").click();
                      }
                  );          
            });
        });
    </script>
  <?php include "../Common/footer.php"    ?>


