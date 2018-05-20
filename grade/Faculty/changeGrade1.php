<?php
    include "../Common/header.php";
    include "../Model/StaffModel.php";
    $staff = new StaffModel($DB_con);
    $period = $_SESSION["period"];
    $offering = $_SESSION["offering"];
    $class = $_SESSION["class"];
    $term = $_SESSION["term"];
    $syr = intval($period/10);
    $sem = $period % 10;
    $arrPeriod = array(1 => "First Semester", 2 =>"Second Semester", 
                       3 => "Summer");  
    $sysem = $syr." - ".($syr + 1)." ".$arrPeriod[$sem];

    if(isset($_POST['submit'])){
        if(isset($_POST['Midterm'])){
            $staff->updateMidtermGrade($offering, $class, $_POST['Midterm'], $period);
        } else {
            $staff->updateFinalGrade($offering, $class, $_POST['Final'], $period);
        }
    }

    $studClassRecord = $staff->getStudentClassRecord($offering, $class, $period);



    function genGradeOption(){
        $options = "<option value=''></option>";
        for ($i = 1.00; $i <= 5.00;  $i += 0.25) {
            $value = number_format($i,2);
            $options .= "<option value={$value} >{$value}</option>";
        }       
        $options .= "<option value='DR' >DR</option>";
        $options .= "<option value='INC' >INC</option>";
        return $options;
    }



?>
<div id="body">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <form method="POST">                    
                    <h2 style='border-bottom: 3px solid green; margin-top: 2px;'><small style='color:green;'>School Year: <?php echo $sysem; ?></small></h2>
                    <div class="space-8"></div>

                    <div style="width: 500px; margin: auto; background-color: lightgray; border-radius: 4px; min-height: 100px; padding: 0; text-align: center; ">

                        <h2 style="border-bottom: 1px solid gray; background-color: #32d666; "><small style="color: #fff">Change Grade</small></h2>
                        <div style="text-align: left; padding: 8px;">
                            <table class="table">
                                <tr>
                                    <th>Name</th>
                                    <td>
                                        <?php
                                            echo $studClassRecord["FirstName"]." ";
                                            echo isset($studClassRecord["MiddleName"])?substr($studClassRecord["MiddleName"], 0, 1).". ": " ";
                                            echo $studClassRecord["LastName"]." ";
                                         ?>
                                             
                                    </td>
                                    <th>Course</th>
                                    <td><?php echo  $studClassRecord["majorName"];?></td>
                                </tr>
                                <tr>
                                    <th>Student No.</th>
                                    <td><?php echo  $studClassRecord["StudID"];?></td>
                                    <th>Year Level</th>
                                    <td><?php echo  $studClassRecord["yrLevel"];?></td>
                                </tr>
                            </table>
                            <table class="table">
                                <tr>
                                    <th style="width: 120px;">Subject</th>
                                    <td >
                                        <?php 
                                            echo  $studClassRecord["subCode"]." - ";
                                            echo  $studClassRecord["subDesc"];
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Offering No.</th>
                                    <td> <?php  echo  $studClassRecord["offerNum"]; ?> </td>
                                </tr>
                                <?php if($term === "Midterm"): ?>
                                <tr>
                                    <th>Midterm Grade</th>
                                    <td>
                                        <?php  echo  $studClassRecord["Midterm"]; ?> 
                                        &emsp;
                                        <select style="width: 80px;" name="Midterm" required >
                                            <?php echo genGradeOption(); ?>
                                        </select>
                                    </td>
                                </tr>
                                <?php else: ?>
                                <tr>
                                    <th>Final Grade</th>
                                    <td>
                                        <?php  echo  $studClassRecord["Final"]; ?> 
                                        &emsp;
                                        <select style="width: 80px;" name="Final" required >
                                            <?php echo genGradeOption(); ?>
                                        </select>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </table>
                        </div>
                       
                        <h2 style="border-top: 1px solid gray; padding: 4px"><button class="btn btn-success" style="width: 80px;" type="submit" name='submit'>SAVE</button></h2>
                        
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
    });
</script>

<?php  include "../Common/footer.php";    ?>
