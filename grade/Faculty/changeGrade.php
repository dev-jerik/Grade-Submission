<?php
    include "../Common/header.php";
    include "../Model/StaffModel.php";
    $staff = new StaffModel($DB_con);
    $period = $_SESSION["period"];
    $syr = intval($period/10);
    $sem = $period % 10;
    $arrPeriod = array(1 => "First Semester", 2 =>"Second Semester", 
                       3 => "Summer");  
    $sysem = $syr." - ".($syr + 1)." ".$arrPeriod[$sem];

    if(isset($_POST['submit'])){

        $_SESSION["offering"] = $_POST['offering'];
        $_SESSION["class"] = $_POST['class'];
        $_SESSION["term"] = $_POST['term'];
       // die("Entered");
        header('Location: changeGrade1.php');
    }
    

?>
<div id="body">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <form  method="POST">                    
                    <h2 style='border-bottom: 3px solid green; margin-top: 2px;'><small style='color:green;'>School Year: <?php echo $sysem; ?></small></h2>
                    <div class="space-8"></div>

                    <div style="width: 300px; margin: auto; background-color: lightgray; border-radius: 4px; min-height: 100px; padding: 0; text-align: center; ">

                        <h2 style="border-bottom: 1px solid gray; background-color: #32d666; "><small style="color: #fff">Change Grade</small></h2>
                        <div style="text-align: left; padding: 8px;">
                            <div class="form-group">
                                <LABEL>Department </LABEL>
                                <?php $deptList = $staff->getDeptList(); ?>
                                <select class="form-control" id="department"required >
                                    <option value="" ></option>
                                    <?php foreach ($deptList as $dept): ?>
                                        <option value=<?php echo $dept['deptID']; ?> >
                                            <?php echo $dept['deptDesc']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <LABEL>Instructor</LABEL>
                                <select class="form-control" id="instructor" required >
                                </select>
                            </div>
                            <div class="form-group">
                                <LABEL>Offering No.</LABEL>
                                <select class="form-control" id="offering" name="offering" required >
                                </select>
                            </div>
                            <div class="form-group">
                                <LABEL>Class Students</LABEL>
                                <select class="form-control" id="class" name="class" required >
                                </select>
                            </div>
                            <div class="form-group">
                                <LABEL>Grading Period</LABEL>
                                <select class="form-control" name="term" >
                                    <option value="Midterm">Midterm</option>
                                    <option value="Final">Final</option>
                                </select>
                            </div>
                        </div>
                       
                        <h2 style="border-top: 1px solid gray; padding: 4px"><button class="btn btn-success" style="width: 80px;" type="submit" name='submit'>Continue</button></h2>
                        
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--Script Here-->
<script>
    $(document).ready(function(){
        $("#f-changeGrade").addClass("active");
        $(document).on("change", "#department", function(){
            var deptID = $(this).val();
            $.post("../Model/Service.php",
                    {deptID: deptID,action: "getInstructorList"},
                    function(data){
                        $("#instructor").html(data);
                    }
                );
        });
        $(document).on("change", "#instructor", function(){
            var instID = $(this).val();
            $.post("../Model/Service.php",
                    {instID: instID,action: "getOfferingList"},
                    function(data){
                        $("#offering").html(data);
                    }
                );
        });
        $(document).on("change", "#offering", function(){
            var offerID = $(this).val();
            $.post("../Model/Service.php",
                    {offerID: offerID,action: "getStudentList"},
                    function(data){
                        $("#class").html(data);
                    }
                );
        });
    });
</script>

<?php  include "../Common/footer.php";    ?>
