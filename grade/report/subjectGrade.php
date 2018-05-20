<?php
require('../fpdf181/fpdf.php');
include_once '../Model/Config.php';
include_once '../Model/StaffModel.php';
	

class PDF extends FPDF{
	function Header()
	{
		global $title;
	    $this->SetFont('Arial','B',13);
	    $this->Cell(0,5,'VISAYAS STATE UNIVERSITY',0,1,'C');
	    $this->SetFont('Arial','b',8);
	    $this->Cell(0,5,'VISCA, Baybay City, Leyte 6521-A',0,1,'C');
	    $this->SetFont('helvetica','B',13);
	    $this->Cell(0,8,'GRADE SHEET',0,1,'C');
	    $this->SetFont('Arial','b',10);
	    $this->Cell(0,5,$title,0,1,'C');
	    // Line break
	    $this->Ln(2);
	}
	function Footer()	{
	    // Position at 1.5 cm from bottom
	    $this->SetY(-15);
	    // Arial italic 8
	    $this->SetFont('Arial','I',8);
	    // Page number
	    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}

	function SubjectInfo($info){
		$this->SetFont('Times','B',10);
		$this->Cell(55,5,'Offering No: '.$info['offerNum'],0,0);
		$this->Cell(60,5,'Units: '.$info['units'],0,1);
		$this->Cell(0,5,'Subject: '.$info['subDesc'],0,1);
		$this->Cell(0,5,'Instructor: '.$info['instructor'],0,1);
		$this->Cell(0,5,'Class Schedule: '.$info['schedule'],0,1);
		$this->Cell(0,5,'Department: '.$info['department'],0,1);
		$this->Cell(0,5,'Room: '.$info['room'],0,1);
		$this->Ln(2);
	}

	function tableHeader(){
		$hy = 8;
		$this->Cell(10,$hy,'No',1,0,'C');
		$this->Cell(30,$hy,'Student ID',1,0, 'C');
		$this->Cell(80,$hy,'Name',1,0, 'C');
		$this->Cell(20,$hy,'Midterm',1,0, 'C');
		$this->Cell(20,$hy,'Final',1,0,'C');
		$this->Cell(0,$hy,'Remark',1,1,'C');
	}
	function tableBody($ctr, $student){
		$hy = 8;

		$studentName = $this->getFullName($student['LastName'], $student['FirstName'], null);
		$this->SetFont('Times','',9);
		$this->Cell(10,$hy,$ctr,1,0);
		$this->Cell(30,$hy,$student['studID'],1,0);
		$this->Cell(80,$hy,$studentName,1,0);
		$this->Cell(20,$hy,$student['MidTerm'],1,0,'C');
		$this->Cell(20,$hy,$student['Final'],1,0,'C');
		$this->Cell(0,$hy,$student['Remark'],1,1);	
	}

	function getFullName($lastName, $firstName, $middleName){
		return $lastName.", ".$firstName." ".(isset($middleName)?substr($middleName,0,1).".": "");
	}

}

$period = $_SESSION["period"];
$syr = intval($period/10);
$sem = $period % 10;
$arrPeriod = array(1 => "First Semester", 2 =>"Second Semester", 
                   3 => "Summer");

// Instanciation of inherited class



//***** Print DATA*********//
$staff = new StaffModel($DB_con);
$offerID=$_GET['offerID'];
if(!$staff->hasOfferIDExist($offerID, $period)){
	echo "<div style='color:red'>No Gradesheet available to be print.</div>";
	exit();
}
$pdf = new PDF('P','mm','Letter');
$title = $arrPeriod[$sem];
$pdf->SetTitle($title);
$pdf->AliasNbPages();
$pdf->AddPage();

$info = $staff->getSubjectInfo($offerID, $period);
$subjectInfo = array(
	'offerNum' => $info['offerNum'],
	'units' => $info['units'],
	'subDesc' => $info['subCode']." - ".$info['subDesc'],
	'instructor' => $_SESSION['fullName'],
	'schedule' => $info['days']." (".$info['strtTime']." - ".$info['endTime'].")",
	'department' => $info['deptDesc'],
	'room' => $info['room']
);
$grades = $staff->getGradeByOfferNo($offerID, $period);
$pdf->SubjectInfo($subjectInfo);
$pdf->tableHeader();
if(count($grades) > 0 || $grades != 0){
$ctr = 0;
foreach ($grades as $grade) {
	$ctr++;
	$pdf->tableBody($ctr, $grade);
}
}
		
$pdf->Output();
?>