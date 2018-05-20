<?php
    include "../Common/header.php";
    include "../Model/CommonModel.php";
    include "../Model/StaffModel.php";
    $common = new CommonModel($DB_con);
    $staff = new StaffModel($DB_con);
    $period = $_SESSION["period"];
    $syr = intval($period/10);
    $sem = $period % 10;
?>
   <div id="body" style="margin-top: 0">
       <div class="container">
          <div class="row">    

              <div class="col-md-12" style="display: none" id="gradeStatus-header">
                  <div class='box-window'>
                      <div class='box-header'>
                        GRADESHEET STATUS <span class='pull-right' id='holder1'><i class='fa fa-chevron-up'></i></span>
                      </div> 
                      <div class='box-body' id='box-body1'>
                          <h5 style="font-weight: bold; color: green;">School Year: <span id="year"></span>, <span id="gradeSubmission-sem"></span></h5>
                          <h5 style="font-weight: bold; color: green;">Term: <span id="gradeSubmission-term"></span></h5>
                          <div class="box-widget border-solid-all" >
                            <table class="table table-condensed " style="margin-bottom: 0">
                              <thead>
                                <tr>
                                  <th>Subject</th>
                                  <th>Offering No.</th>
                                  <th>Status</th>
                                  <th>Dept.Head</th>
                                  <th>College Dean</th>
                                  <th>Registrar</th>
                                </tr>
                              </thead>
                              <tbody id="gradeStatus">
                              </tbody>
                            </table>
                          </div>
                      </div>
                    </div>
              </div><!--end of first row-->
              <div id="gradeSubmissionView"></div>
              
              
          </div><!--end of main row-->
          <input id="hidden_offer" type="text" hidden />
       </div>

       <!--hidden elements -->
       <button type='button' data-target='#gradingPeriod-Modal' data-toggle='modal' id='gradingPeriod' hidden >Grading Period</button>
       <button type='button' data-target='#gradeFileUpload-Modal' data-toggle='modal' id='gradeFileUpload' hidden >CSV FILE UPLOAD</button>
   </div>

    <?php
            include "../Common/footer.php"
     ?>
    
       

   <!---------- Include Modal Here -------------------->
    <?php 
        include('gradeFileUpload-Modal.php');
        include('gradingPeriod-Modal.php'); 
        
    ?>
    <!-- <script src="http://d3js.org/d3.v3.min.js"></script> -->
    <script src="../Assets/js/d3.v3.min.js"></script>
    <script>
    	$(document).ready(function(){
    		$("ul.link li").removeClass("active");
    		$("#f-gradeSubmission").addClass("active");

    		

            function showSubjectStatus(){
                var season=getGradingSeason();
            	  var term = $("#gradingPeriod-term").val();
                $.post("../Model/Service.php",
                    {season:season, term: term, action: "getSubjectStatus"},
                    function(data){
                    	$("#gradeStatus").html(data);
                    }
                ); 
            }

            function tblGradesValues(){
            	var TableData = new Array();
              var term = $("#gradingPeriod-term").val();
              
      				$('#tbl-elecSaveGrade tbody tr').each(function(row, tr){
                if(term === "Midterm"){
                    TableData[row]={
                        "studLevelID" : $(tr).find('td:eq(0)').text()
                        , "studNo" :$(tr).find('td:eq(2)').text()
                        , "grade" : $(tr).find('td:eq(5)').find('select').val()
                    }
                }else {
                  TableData[row]={
                        "studLevelID" : $(tr).find('td:eq(0)').text()
                        , "studNo" :$(tr).find('td:eq(2)').text()
                        , "grade" : $(tr).find('td:eq(6)').find('select').val()
                    }
                }
      				  
      				}); 
				return TableData;
            }

            function hasEmptyGrades(data){
                var arrLength = data.length;
                for(var i=0; i < arrLength; i++){
                    if(data[i].grade === ''){
                        return true;
                    }
                }
                return false;
            }
            $(document).on("click", "#elec-submitGrade", function(e){
            	e.preventDefault();
                var arrGrades = tblGradesValues();
                if(hasEmptyGrades(arrGrades) === true){
                    alert('Error! Empty grades cannot be submitted.');
                }else{
                    var option = confirm("Are you sure you want to submit this grades?");
                    if (option == true) {
                        objJSON = JSON.stringify(arrGrades);              
                        var offerID = $("#hidden_offer").val();
                        var season=getGradingSeason();
                        var term = $("#gradingPeriod-term").val();
                        $.post("../Model/Service.php",
                        {season: season, term:term, offerID: offerID, objJSON, objJSON, action: "submitGrades"},
                            function(data){
                                alert(data);
                                showSubjectStatus();
                                genGradeSubmission(offerID);
                            }
                        );
                    }
                }
				 
              });
            function csvGradeValues(){
              var TableData = new Array();
              
              $('#gradeFileUpload-tableBody tr').each(function(row, tr){
               
                  TableData[row]={
                        "studLevelID" : $(tr).find('td:eq(0)').text()
                        , "studNo" :$(tr).find('td:eq(2)').text()
                        , "grade" : $(tr).find('td:eq(4)').text()
                    }                
                
              }); 
                return TableData;
            }
            $(document).on("click", "#csvSubmit", function(e){
                e.preventDefault();
                objJSON = JSON.stringify(csvGradeValues()); 
                var offerID = $("#hidden_offer").val();
                var season=getGradingSeason();
                var term = $("#gradingPeriod-term").val();
                $.post("../Model/Service.php",
                        {season: season, term:term, offerID: offerID, objJSON, objJSON, action: "submitGrades"},
                        function(data){
                          alert(data);
                          showSubjectStatus();
                          genGradeSubmission(offerID);
                        }
                    ); 
            });
            

            $(document).on("click", "#elec-saveGrade", function(e){
            	e.preventDefault();
				      objJSON = JSON.stringify(tblGradesValues());
            	var term = $("#gradingPeriod-term").val();
            	var offerID = $("#hidden_offer").val();
                $.post("../Model/Service.php",
                    {term:term, offerID: offerID, objJSON, objJSON, action: "saveGrades"},
                    function(data){
                    	alert(data);
                    	showSubjectStatus();
                    }
                ); 
				
            });

            $(document).on("click", "#status_offerID", function(e){
            	e.preventDefault();
            	var offerID = $(this).data('offer');
            	$("#hidden_offer").val(offerID);
            	genGradeSubmission(offerID);
            	
            });
            $('#holder1').click(function(){
              $(this).find('i').toggleClass('fa fa-chevron-up').toggleClass('fa fa-chevron-down');
                $('#box-body1').toggle();
                
            });
            $(document).on("click", "#holder2", function(){
              $(this).find('i').toggleClass('fa fa-chevron-up').toggleClass('fa fa-chevron-down');
                $('#box-body2').toggle();                
            });

            $(document).on("click", "#btnFile-upload", function(){
                $("#csvFile-upload").click();       
                        
            });
            $(document).on("change","#csvFile-upload",function(){
                  var file_data = $(this).prop('files')[0];
                  var form_data = new FormData();
                  form_data.append('file', file_data);
                  form_data.append('offerID', $("#hidden_offer").val());
                  console.log(file_data);
                  $.ajax({
                      url: "../Model/FileUpload.php", // point to server-side PHP script 
                      dataType: 'text',  // what to expect back from the PHP script, if anything
                      cache: false,
                      contentType: false,
                      processData: false,
                      data: form_data,                         
                      type: 'post',
                      success: function(php_script_response){
                         // alert(php_script_response)
                          var data = jQuery.parseJSON(php_script_response);
                          $("#fileName").html("<i>"+file_data.name+"</i>");
                          $("#gradeFileUpload-tableBody").html(data.output);
                          $("#uploadError").html(data.errors);
                          $("#gradeFileUpload").click();
                      }
                  }); 
                  
                  
             });
            
            $(document).on("change", "#gradingMethod-option", function(e){
               e.preventDefault();
               var method = $(this).val();
               var offerID = $("#hidden_offer").val();
               if(method == 1){
                  genGradeSubmission(offerID);
               }else {
                  var html="<div class='fileUpload-area'> "+
                  "<form id='uploadCSV' method='POST' enctype='multipart/form-data'><input type='file' id='csvFile-upload' style='display:none' accept='.csv' />"
                  +"<button class='btn btn-default' id='btnFile-upload' style='margin: 0' type='button' name='submit'>Upload File</button>"+
                  "</form>"
                  +"</div>";
                  $("#gradingMethod-View").html(html);
               }        
            });
            
            //generate the view for grade submission
            function genGradeSubmission(offerID){
            	var season=getGradingSeason();
            	var term = $("#gradingPeriod-term").val();
            	$.post("../Model/Service.php",
                    {season:season, offerID: offerID, term: term, action: "getGradeSubmissionView"},
                    function(data){
                    	$("#gradeSubmissionView").html(data);
                    	//alert(data)
                    }
                ); 
            }

			function getGradingSeason(){
                var year=$("#gradingPeriod_year").val();
                var sem=$("input[name=gradeSubmission-sem]:checked").val();
                var season = year+""+sem;
                return season;
            }
            function initialize(){
            	$("#year").html($("#gradingPeriod_year").val());
            	$("#gradeSubmission-term ").html($("#gradingPeriod-term").val()); 

            	var sem = $("input[name=gradeSubmission-sem]:checked").val();
            	var semDisplay = (sem==1)?"1st Semester":(sem==2)?"2nd Semester":"Summer"
            	$("#gradeSubmission-sem").html(semDisplay); 

            	showSubjectStatus();

            	$("#gradeStatus-header").show();
            }
             //Grading Period
            $("#gradingPeriod").click();
            
            $("#gradingPeriod-Form").submit(function(e){
                e.preventDefault();
                $('#gradingPeriod-Modal').modal('toggle');
                initialize();                
            });



	});
    </script>

    

