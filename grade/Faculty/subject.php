<?php
    include "../Common/header.php";
    include "../Model/CommonModel.php";
    include "../Model/StaffModel.php";
    $common = new CommonModel($DB_con);
    $staff = new StaffModel($DB_con);

    $period = $_SESSION["period"];
    $syr = intval($period/10);
    $sem = $period % 10;
    $arrPeriod = array(1 => "First Semester", 2 =>"Second Semester", 
                       3 => "Summer");  
    $sysem = $syr." - ".($syr + 1)." ".$arrPeriod[$sem];

?>
   <div id="body">
       <div class="container">
        <div class="row">  
            <div class="col-sm-4">
                <form id="search">
                <b>Instructor Subjects:</b>
                <div class="space-2"></div>
                <b>School Year: <?php echo $sysem; ?></b>
                
                
            </form>
                <div class="list-course">
                    <div class="table-responsive" >
                        <table class="table table-condensed table-hover" style="font-size: 12px; cursor: pointer;">
                            <thead>
                            <tr>
                                <th style="display: none;"></th><th>Offering#</th> <th>Subject_Code</th> <th>Description</th>
                            </tr>
                            </thead>
                            <tbody id="subjectList">
                            </tbody>
                            <tfoot></tfoot>
                        </table>
                    </div>
                </div>
            </div><!--end of left column-->

            <div class="col-sm-8">
                <ul class="nav nav-tabs" style="border-bottom: 2px solid #ddd; ">
                    <li class="active" ><a>
                            <label>Class Information</label></a></li>
                </ul>
                <div style="border: 1px solid #ddd; padding: 8px 10px; border-radius: 0 0 4px 4px; border-top-width: 0; display: none;" id="class-info">
                    
                </div>
            </div><!--end of right column-->
        </div><!--end of main row-->
        <!--HIDDEN ELEMENTS HERE-->
        <div style='display:none'>
            <input id='hidden_year' value=<?php echo $syr; ?> />
            <input id='hidden_sem' value=<?php echo $sem; ?> />
        </div>
        
       </div>
   </div>
   <script>
        $(document).ready(function(){  
            $("ul.link li").removeClass("active");
            $("#f-courses").addClass("active");
        });
    </script>
    <?php
            include "../Common/footer.php";
        ?>   

<!--    <!---------- Include Modal Here -------------------->
    <?php 
        //include('viewGrades-Modal.php'); 
    ?> -->
    
