<?php
    ob_start();
    include_once '../Model/Config.php';
    include_once '../Model/UserModel.php';
    $user = new UserModel($DB_con);
    if(isset($_GET['logout'])){
        if($user->doLogout()){
            header("Location: ../../index.php");  
        }
    }
    if (!isset($_SESSION["user_id"])) {
          session_destroy();
          header("Location: ../../");  
    }
    $appPersonel = "FACULTY";
    if($user->isDean($_SESSION['user_id'])){
        $appPersonel="DEAN";
    }else if($user->isReg($_SESSION['user_id'])){
        $appPersonel="REGISTRAR";
    }
?>
<!Doctype html>
<html>
<head>
    <title>VSU-Villaba SRMS</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="../Assets/images/vsu_logo2.png">
    <link rel="stylesheet" href="../Assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../Assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="../Assets/css/customCss.css">
    <script src="../Assets/js/jquery.min.js"></script>
    <script src="../Assets/js/bootstrap.min.js"></script>

</head>
<body>
<!--Wrapper-->
<div id="container">
    <nav class="navbar navbar-inverse linkFont">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header" >
                <button type="button" data-target="#navbarCollapse" data-toggle="collapse" class="navbar-toggle">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="../Faculty/" style="color: #fff; " class="navbar-brand">Grade Submission</a>
            </div>
            <!-- Collection of nav links, forms, and other content for toggling -->
            <div id="navbarCollapse" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li id='home'><a href="../../dept/"><span class="glyphicon glyphicon-home"></span> Home</a></li>
                    <?php if(!$user->isReg($_SESSION["user_id"])): ?>
                    <li id='f-courses'><a href="../Faculty/subject.php" ><span class="glyphicon glyphicon-book"></span> Subjects</a></li>
                    <li id='f-gradeSubmission'><a href="../Faculty/gradingTerm.php" ><i class="fa fa-file-text-o"></i> Submit Grades</a></li>
                    <?php endif;?>

                    <?php if($user->isReg($_SESSION["user_id"])): ?>
                        <li id='f-headApproval'><a href="../Faculty/approval.php" ><i class="fa fa-check"></i>  Registrar Approval </a></li>
                        <li id='f-changeGrade'><a href="../Faculty/changeGrade.php" ><i class="fa fa-file-text-o"></i> Change Grade</a></li>
                        <li class="dropdown" id='f-gradesheetGeneration'>
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#"> 
                                <i class="glyphicon glyphicon-print"></i>  
                                Print Report <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu">
                                 <li >
                                    <a href="../report/" >
                                        <i class="fa fa-file-text-o"></i> &nbsp; GradeSheet by Instructor</b>
                                    </a>
                                </li>
                                <li >
                                    <a href="../report/studentGradeSheetIndex.php" >
                                        <i class="fa fa-file-text-o"></i> &nbsp; GradeSheet by Student</b>
                                    </a>
                                </li>
                                <li >
                                    <a href="../report/classRoosterIndex.php" >
                                        <i class="fa fa-file-text-o"></i> &nbsp; Class Rooster</b>
                                    </a>
                                </li>
                                <li >
                                    <a href="../report/studentGrades.php" >
                                        <i class="fa fa-file-text-o"></i> &nbsp; Student Grades</b>
                                    </a>
                                </li>
                            </ul>
                        </li>
                       
                    <?php endif;?>

                    <?php if($user->isDean($_SESSION["user_id"])): ?>
                        <li id='f-headApproval'><a href="../Faculty/approval.php" ><i class="fa fa-check"></i>  Dean Approval </a></li>
                    <?php endif;?>

                    <?php if($user->isDeptHead($_SESSION["user_id"])): ?>                  
                        <li id='f-headApproval'><a href="../Faculty/ApprovalTerm.php" ><i class="fa fa-check"></i>  Head Approval </a></li>
                    <?php endif;?> 
                    
                    <li id='f-manageAccount' ><a  href="../Faculty/manageAccount.php"><span class="glyphicon glyphicon-cog"></span> Manage Account</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <b><?php echo $appPersonel; ?>:</b> <?php  echo isset($_SESSION['fullName'])?$_SESSION['fullName']: "";?> 
                        </a>
                        <ul class="dropdown-menu dropdown-caret dropdown-menu-right ">
                            <li><a href="../../controller/UserLogout.php"><i class="fa fa-sign-out"></i> Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
