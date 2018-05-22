<?php
    include_once '../Model/Config.php';
    include_once '../Model/UserModel.php';
    include_once '../Model/StaffModel.php';
    $user = new UserModel($DB_con);
    //validate if user has sign in.
    if(!$user->isLoggedIn() || !isset($_GET['fileName'])||!isset($_GET['offerID'])){
        header('Location: ../Faculty/');
        exit();
    }

    $fileName=$_GET['fileName'];
    $offerID=$_GET['offerID'];
    $f = fopen('php://output','w');
    $header = array('StudentID','Name', 'Course', 'Grade');
    fputcsv($f, $header);

    $period = $_SESSION["period"];
    $staff = new StaffModel($DB_con);
    $studentList = $staff->getStudentListBySubject($offerID,$period);

    foreach($studentList as $line){
        $fullName = $line['LastName'].', '. $line['FirstName'];
        $row = array($line['StudID'], $fullName, $line['majorName'], ' ');
        fputcsv($f, $row);
    }
    //reset the file pointer to the beginning.
    //fseek($f,0);
    //tell the browser to download this file
    header('Content-type: application/csv');
    header('Content-Disposition: attachment; filename="'.$fileName.'.csv"');
    fpassthru($f);
?>