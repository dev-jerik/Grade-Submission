<?php
    include "../Common/header.php";
    $period = $_SESSION["period"];
    $syr = intval($period/10);
    $sem = $period % 10;
    $arrPeriod = array(1 => "First Semester", 2 =>"Second Semester", 
                       3 => "Summer");  
    $sysem = $syr." - ".($syr + 1)." ".$arrPeriod[$sem];
    if(isset($_POST['submit'])){
        $_SESSION['gradingPeriod'] = $_POST['gradingPeriod'];
        header('Location: gradeSubmission.php');
    }
?>
<div id="body">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <form method="POST">                    
                    <h2 style='border-bottom: 3px solid green; margin-top: 2px;'><small style='color:green;'>School Year: <?php echo $sysem; ?></small></h2>
                    <div class="space-8"></div>

                    <div style="width: 300px; margin: auto; background-color: lightgray; border-radius: 4px; min-height: 100px; padding: 0; text-align: center; ">

                        <h2 style="border-bottom: 1px solid gray; background-color: #32d666; "><small style="color: #fff">GRADING PERIOD</small></h2>

                        <label class="radio-inline">
                            <input type="radio" name="gradingPeriod" <?php echo $sem==1? 'checked': ''?> value="Midterm" />Midterm
                        </label> 
                        <label class="radio-inline">
                            <input type="radio" name="gradingPeriod" <?php echo $sem==2? 'checked': ''?> value="Final" />Final
                        </label> 
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
    	$("#f-gradeSubmission").addClass("active");
    });
</script>

<?php  include "../Common/footer.php";    ?>
