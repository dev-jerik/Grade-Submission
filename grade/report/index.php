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
        $_SESSION['deptID'] = $_POST['deparment'];
        if($user->isReg($_SESSION['user_id'])){
            // echo "<script language='javascript'>window.open('http://localhost/vvsu/grade/report/gradeSheets.php','_blank');</script>";
            header('Location: gradeSheets.php');
        }
        
    }
?>
<div id="body">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <form  method="POST" target="_blank">                    
                    <h2 style='border-bottom: 3px solid green; margin-top: 2px;'><small style='color:green;'>School Year: <?php echo $sysem; ?></small></h2>
                    <div class="space-8"></div>

                    <div style="width: 350px; margin: auto; background-color: lightgray; border-radius: 4px; min-height: 100px; padding: 0; text-align: center; ">

                        <h2 style="border-bottom: 1px solid gray; background-color: #32d666; ">
                            <small style="color: #fff" class="linkFont">
                                Class Gradesheet Generation
                            </small>
                        </h2>
                        <table align="center" class="table">
                            <tr>
                                <td style="width: 5%">
                                    <label>Department: </label>
                                </td>
                                <td style="text-align: left;">
                                    <?php $deptList = $staff->getDeptList(); ?>
                                    <select class="form-control" name='deparment'>
                                    <?php foreach ($deptList as $dept): ?>
                                        <option value=<?php echo $dept['deptID']; ?> >
                                            <?php echo $dept['deptDesc']; ?>
                                        </option>
                                    <?php  endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        
                        
                        <h2 style="border-top: 1px solid gray; padding: 4px"><button class="btn btn-danger" style="width: 80px;" type="submit" name='submit'><i class="glyphicon glyphicon-print"></i> Print</button></h2>
                        
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--Script Here-->
<script>
    $(document).ready(function(){
        $("ul.link li").removeClass("active");
        $("#f-gradesheetGeneration").addClass("active");
    });
</script>
<?php  include "../Common/footer.php";    ?>
