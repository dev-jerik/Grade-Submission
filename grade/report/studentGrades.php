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
        $_SESSION['studLevelID'] = $_POST['studLevelID'];
        header("Location: studentGradeSheet1.php");
    }
?>
<div id="body">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <form  method="POST" id="form-search" target="_blank">                    
                    <h2 style='border-bottom: 3px solid green; margin-top: 2px;'><small style='color:green;'>School Year: <?php echo $sysem; ?></small></h2>
                    <div style="width: 500px; margin: auto; background-color: lightgray; border-radius: 4px; min-height: 100px; padding: 0; text-align: center; ">

                        <h2 style="border-bottom: 1px solid gray; background-color: #32d666; ">
                            <small style="color: #fff"> Search Student</small>
                        </h2>
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Type your Student ID" id="search">
                            <span class="input-group-btn">
                            <button class="btn btn-secondary" type="button" id="btn-search">Search</button>
                            </span>
                        </div>
                        <select name='lstStud' id='lstStudents' value='' size="18" style='width:500px;'>
                        </select>
                        <h2 style="border-top: 1px solid gray; padding: 4px"><button class="btn btn-success" style="width: 80px;" type="submit" name='submit' id="view">View</button></h2>
                        
                    </div>
                    <input id="studLevelID" name="studLevelID" hidden />
                </form>
            </div>
        </div>
    </div>
</div>
<!--Script Here-->
<script>
    $(document).ready(function(){
        $("#f-gradesheetGeneration").addClass("active");
        $("#btn-search").click(function(){
            var search = $("#search").val();
            $.post("../Model/Service.php",
                    {search: search,action: "searchStudent"},
                    function(data){
                        $("#lstStudents").html(data);
                    }
                );
        });
        $("#view").click(function(){
            var studLevelID = $("#lstStudents").val();
            $("#studLevelID").val(studLevelID);
        });
    });
</script>

<?php  include "../Common/footer.php";    ?>
