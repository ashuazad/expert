<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
date_default_timezone_set("Asia/Kolkata");
require('../includes/fpdf185/rpdf.php');

require_once '../includes/userqueryDatabase.php';
require_once '../includes/categoryDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../includes/admission.php';
session_start();
$stdRegno = '';
if (isset($_SESSION['Zend_Auth']) && !empty($_SESSION['Zend_Auth'])) {
    $stdRegno = $_SESSION['Zend_Auth']['storage']->regno;
}
if(!$_SESSION['id']  && empty($stdRegno)){
   header('Location: ' . constant('BASE_URL'));
   exit;
}
date_default_timezone_set('Asia/Kolkata');
$userquery = new userqueryDatabase();
$category = new categoryDatabase();
$branchData = new managebranchDatabase();
$dbObj = new db();
$admission = new admission();
$id = $_SESSION['id'];
$post_data = $_REQUEST;
$searchData = array(
   'regno'=>$post_data['regno']
   );
$regDetails = $admission->getRegistrationDetails($searchData);

if (!$regDetails) {
   die();
   exit();
}
// Check generate certificate conditions
/*
$currentDate = date_create(date('Y-m-d H:i:s')); 
$lastReceiptDate = date_create($regDetails['last_receipt_date']);
$interval = date_diff($lastReceiptDate, $currentDate);
$nofDays = $interval->format('%a');
//var_dump($nofDays);
if ($nofDays < 30) {
   die();
   exit();
}
*/
//Certificate No
$certificateNo = $regDetails['regno'];
//Course Name
$courseName = $regDetails['course'][array_search($post_data['course_name'], $regDetails['course'])];
// Candidate Name
$candidateName = $regDetails['name'];
// Issue Date

$issueDate = date('d-M-Y', strtotime($post_data['issue_date']));
$pdfObj = new RPDF();
$pdfObj->AddPage();
//$pdfObj->AliasNbPages();
//$pdfObj->AddPage();
$pdfObj->SetMargins(0,0,0);
$pdfObj->Image('certificateLeft.png',0,0,40,300);
//$pdfObj->Image('logo-main.png',2,255,37,17);
$pdfObj->Image('logo-main.png',90,10,70,20);
$pdfObj->Image('certified-log.jpg',100,200,45,40);
// First Signature
$pdfObj->Image('signature_bansraj.png',45,200,45,40);
//$pdfObj->Image('signature.png',100,200,45,40);
// Second Signature
$pdfObj->Image('signature_harshita.png',155,200,45,40);

$pdfObj->Ln(40);
$pdfObj->SetFont('Times','I',37);
$pdfObj->SetTextColor(199, 1, 0);
$pdfObj->SetLeftMargin(43);
$pdfObj->Cell(165, 15, 'Certificate Of Completion', 0, 0, 'C', 0);

$pdfObj->Ln(20);
$pdfObj->SetFont('Times','I',20);
$pdfObj->SetTextColor(0, 0, 0);
$pdfObj->Cell(165, 15, 'This is to certify that', 0, 0, 'C', 0);

$pdfObj->Ln(15);
$pdfObj->SetFont('Times','B',20);
$pdfObj->SetTextColor(0, 0, 0);
$pdfObj->Cell(165, 15, 'Mr. ' . $candidateName, 0, 0, 'C', 0);

$pdfObj->Ln(15);
$pdfObj->SetFont('Times','I',20);
$pdfObj->SetTextColor(0, 0, 0);
$pdfObj->Cell(165, 15, 'Successfully Completed', 0, 0, 'C', 0);

$pdfObj->Ln(15);
$pdfObj->SetFont('Times','B',27);
$pdfObj->SetTextColor(127,120,173);
$pdfObj->Cell(165, 15, $courseName, 0, 0, 'C', 0);

$pdfObj->Ln(15);
$pdfObj->SetFont('Times','B',22);
$pdfObj->SetTextColor(0, 0, 0);
$pdfObj->Cell(165, 15, 'Grade A+', 0, 0, 'C', 0);

/*
$pdfObj->Ln(10);
$pdfObj->SetFont('Times','I',20);
$pdfObj->SetTextColor(0, 0, 0);
$pdfObj->Cell(165, 15, 'Conducted at', 0, 0, 'C', 0);

$pdfObj->Ln(10);
$pdfObj->SetFont('Times','B',20);
$pdfObj->SetTextColor(0, 0, 0);
$pdfObj->Cell(165, 15, 'Kingsway Camp, Delhi-110009', 0, 0, 'C', 0);
*/
$pdfObj->Ln(15);
$pdfObj->SetFont('Helvetica','',14);
$pdfObj->SetTextColor(0, 0, 0);
$pdfObj->Cell(165, 15, 'CERTIFICATE NO : ' . $certificateNo, 0, 0, 'C', 0);

$pdfObj->Ln(10);
$pdfObj->SetFont('Helvetica','',14);
$pdfObj->SetTextColor(0, 0, 0);
$pdfObj->Cell(165, 15, 'ISSUE DATE : ' . $issueDate, 0, 0, 'C', 0);

$pdfObj->Ln(10);
$pdfObj->SetFont('Helvetica','',10);
$pdfObj->SetTextColor(0, 0, 0);
$pdfObj->Cell(165, 15, 'To check the certificate status visit :', 0, 0, 'C', 0);

$pdfObj->Ln(5);
$pdfObj->SetFont('Helvetica','',10);
$pdfObj->SetTextColor(0, 0, 0);
$pdfObj->Cell(165, 15, 'https://www.advanceinstitute.co.in/', 0, 0, 'C', 0);

$pdfObj->Ln(60);
$pdfObj->SetFont('Helvetica','',12);
$pdfObj->SetTextColor(0, 0, 0);
$pdfObj->Cell(55, 15, 'Mr. Banshraj Yadav', 0, 0, 'C', 0);

$pdfObj->SetLeftMargin(150);
$pdfObj->SetFont('Helvetica','',12);
$pdfObj->SetTextColor(0, 0, 0);
$pdfObj->Cell(55, 15, 'Harshita', 0, 0, 'C', 0);

$pdfObj->SetLeftMargin(43);
$pdfObj->Ln(5);
$pdfObj->SetFont('Helvetica','B',12);
$pdfObj->SetTextColor(0, 0, 0);
$pdfObj->Cell(55, 15, 'Director', 0, 0, 'C', 0);

$pdfObj->SetLeftMargin(150);
$pdfObj->SetFont('Helvetica','B',12);
$pdfObj->SetTextColor(0, 0, 0);
$pdfObj->Cell(55, 15, 'Authorized Signature', 0, 0, 'C', 0);

$pdfObj->SetLeftMargin(43);
$pdfObj->Ln(20);
$pdfObj->SetFont('Times','B',10);
$pdfObj->SetTextColor(0, 0, 0);
$pdfObj->Cell(165, 15, 'EXPERT INSTITUTE OF ADVANCE TECHNOLOGIES PVT. LTD.', 0, 0, '', 0);

$pdfObj->Ln(5);
$pdfObj->SetFont('Times','',10);
$pdfObj->SetTextColor(0, 0, 0);
$pdfObj->Cell(165, 15, '107 3rd Floor, near Guru Tegh Bahadur Nagar Metro Station, Gate No 1 New Delhi Pin Code - 110009 India.', 0, 0, '', 0);
//$pdfObj->Output();
$cerficatePDFName = 'Certificate' . '_'. $candidateName .'_'. $courseName  ;
$pdfObj->Output('D',$cerficatePDFName.'.pdf');
?>