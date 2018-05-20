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
   <div id="body">
       <div class="container">
        <div class="row">     
            <div class="col-sm-4">
                <form id="search">
                <label>Subject: &nbsp;&nbsp;</label>
                <?php //$common->getSubjectListByStaffID(5638); ?>
                    <select id="year"><?php echo $common->generateYear($syr); ?></select>
                &nbsp;&nbsp;
                <label class="radio-inline">
                  <input type="radio" name="sem" value="1"  
                  <?php echo $sem==1? 'checked': ''?> />1st Sem
                </label>
                <label class="radio-inline">
                  <input type="radio" name="sem" value="2"
                  <?php echo $sem==2? 'checked': ''?> />2nd Sem
                </label>
                <label class="radio-inline">
                  <input type="radio" name="sem" value="3" 
                  <?php echo $sem==3? 'checked': ''?> />Summer
                </label>
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
    
