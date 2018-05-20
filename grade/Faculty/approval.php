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
        $_SESSION['approvalTerm'] = $_POST['approvalTerm'];
        $_SESSION['deptID'] = $_POST['deparment'];
        if($user->isDean($_SESSION['user_id'])){
            header('Location: deanApproval.php');
        }else if($user->isReg($_SESSION['user_id'])){
            header('Location: regApproval.php');
        }
    }
?>
<div id="body">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <form method="POST">                    
                    <h2 style='border-bottom: 3px solid green; margin-top: 2px;'><small style='color:green;'>School Year: <?php echo $sysem; ?></small></h2>
                    <div class="space-8"></div>

                    <div style="width: 350px; margin: auto; background-color: lightgray; border-radius: 4px; min-height: 100px; padding: 0; text-align: center; ">

                        <h2 style="border-bottom: 1px solid gray; background-color: #32d666; "><small style="color: #fff">APPROVAL TERM</small></h2>
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
                            <tr>
                                <td colspan="2">
                                    <label class="radio-inline">
                                        <input type="radio" name="approvalTerm" <?php echo $sem==1? 'checked': ''?> value="Midterm" />Midterm
                                    </label> 
                                    <label class="radio-inline">
                                        <input type="radio" name="approvalTerm" <?php echo $sem==2? 'checked': ''?> value="Final" />Final
                                    </label> 
                                </td>
                            </tr>
                        </table>
                        
                        
                        <h2 style="border-top: 1px solid gray; padding: 4px"><button class="btn btn-success" style="width: 80px;" type="submit" name='submit'>Go</button></h2>
                        
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
        $("#f-headApproval").addClass("active");
    });
</script>
<?php include "../Common/footer.php";?>
