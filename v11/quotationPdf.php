<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
require('../includes/LibPdf.php');
require_once '../includes/userqueryDatabase.php';
require_once '../includes/categoryDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';
 session_start();
if(!$_SESSION['id']){
   header('Location: ' . constant('BASE_URL'));
   exit;
}
date_default_timezone_set('Asia/Kolkata');
$userquery = new userqueryDatabase();
$category = new categoryDatabase();
$branchData = new managebranchDatabase();
$dbObj = new db();
$id = $_SESSION['id'];
$quotation = $_REQUEST;
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $quotationId=$_GET['id']; 
    $columns = array('lq.courses','lq.total_price as course_fee','lq.offer_price as total_fee',
                        "DATE_FORMAT(lq.created_on, ".DATE_TIME_FORMAT.") as created_date", 'lead_id', 
                        'ld.phone as lead_phone', 'ld.name as lead_name', 'lq.status as status');
    $quatationData = $dbObj->getData($columns, 'lead_quotation lq, leads ld', 'lq.lead_id=ld.id AND lq.id = '.$quotationId);
    if ($quatationData[0] == 0) {
        $pdfObj->Output('D','error.pdf');
    }
    $quotation = $quatationData[1];
}

function filterCourse(&$item) 
{
    $item = str_replace('-',' ',$item);
}

if (isset($_GET['id']) && !empty($_GET['id'])) { 
    $quotation['courses'] = explode('+', $quatationData[1]['courses']); 
} else {
    array_walk($quotation['courses'], 'filterCourse');
    $courseTitle = implode('+', $quotation['courses']);
}

$courseTotalFees = $quotation['course_fee'];
$courseOfferFees = $quotation['total_fee'];
$lead_id = $quotation['lead_id'];

if (!isset($_GET['id']) && empty($_GET['id'])) { 
    $checkCount = $dbObj->getData(array('id'), 'lead_quotation', "lead_id='".$lead_id."'");
    $column = array(
        'lead_id'=>$lead_id,
        'courses'=>$courseTitle,
        'total_price'=>$courseTotalFees,
        'offer_price'=>$courseOfferFees,
        'created_on'=>date('Y-m-d H:i:s'),
        'user_id'=>$id,
        'status' => ($quotation['quotation_isEdited'] == '1')?'PENDING':'APPROVED'
    );
    if ($checkCount[0]) {
        unset($column['lead_id']);
        $dbObj->dataUpdate($column,'lead_quotation', 'id', $checkCount[1]['id']);
    } else {
        $dbObj->dataInsert($column,'lead_quotation');
    }
}
$userData = $dbObj->getData(array('first_name','email_id','phone_no'),'login_accounts', "id=".$id);
//$name = $_POST['name'];
//$phone = $_POST['phone'];
$name = 'Test';
$phone = 'Phone';
$text = 'Greetings from Advance Institute and thanks for your query';

$pdfObj = new LibPdf();
$pdfObj->AliasNbPages();
$pdfObj->AddPage();
$data = array();
$cellBorder = '0';
$cellFill = true;
$cellWidth = 80;
$cellWidthA = 77;
$cellHeight = 12;
$cellHeightTitle1 = 10;
$cellHeightTitle2 = 7;

$totalPrice = $quotation['course_fee'];
$offerPrice = $quotation['total_fee'];
$discountPrice = $totalPrice-$offerPrice;
$headerText = 'Special Offer';
$lead_name = $quotation['lead_name'];
$lead_phone = $quotation['lead_phone'];
// Page Header
$pdfObj->Ln(25);

$pdfObj->SetTextColor(122, 121, 121);
$coursesHeight = 12;
$coursesFontSize = 20;
if(count($quotation['courses'])==1){
    $coursesHeight = 20;
    $coursesFontSize = 25;
}
foreach($quotation['courses'] as $indxCr => $eachCourse) {
    $pdfObj->SetFont('Arial','B',$coursesFontSize);
    $pdfObj->SetFillColor(199, 2, 0);
    $pdfObj->Cell(2,$coursesHeight,'',0,0,'C',$cellFill);
   $pdfObj->SetFillColor(250, 250, 250);
    $pdfObj->Cell(206,$coursesHeight,'# '.$eachCourse,0,0,'C',$cellFill);
    $pdfObj->SetFillColor(199, 2, 0);
    $pdfObj->Cell(2,$coursesHeight,'',0,0,'C',$cellFill);
    $pdfObj->Ln();
}
$pdfObj->Ln(25);
//Table Header
$pdfObj->SetFont('Arial','B',20);
$pdfObj->SetTextColor(255, 255, 255);
$pdfObj->SetLeftMargin(25);
$pdfObj->Cell($cellWidth,$cellHeightTitle1,'',0,0,'C',0);
$pdfObj->SetFillColor(242, 116, 117);
$pdfObj->Cell($cellWidth,$cellHeightTitle1,'Exclusive plan for you',0,0,'C',$cellFill);
$pdfObj->Ln(10);
$pdfObj->Cell($cellWidth,$cellHeightTitle1,'',0,0,'C',0);

$pdfObj->SetFont('Arial','I',23);
$pdfObj->Cell($cellWidth,$cellHeightTitle1,'Rs. '.$courseOfferFees.'/-',0,0,'C',$cellFill);
$pdfObj->Ln(10);
$pdfObj->SetFont('Arial','B',16);

$pdfObj->SetFillColor(242, 94, 94);
$pdfObj->Cell($cellWidth,$cellHeightTitle1,'Discount',0,0,'C',$cellFill);

$pdfObj->SetFont('Arial','B',16);
$pdfObj->Cell($cellWidth,$cellHeightTitle1,'Total Fee',0,0,'C',$cellFill);
$pdfObj->Ln();

$pdfObj->SetFont('Arial','B',16);
$pdfObj->Cell($cellWidth,$cellHeightTitle1,'Rs. '.$discountPrice.'/-',0,0,'C',$cellFill);
$pdfObj->SetFont('Arial','B',16);
$pdfObj->Cell($cellWidth,$cellHeightTitle1,'Rs. '.$courseTotalFees.'/-',0,0,'C',$cellFill);
$pdfObj->Ln();

//Table Rows
$rows = array(
    array('Live Training','100%'),
    array('Study Material','100%'),
    array('Student Login', 'YES'),
    array('Job Placement','100%'),
    array('Lab Assistance', '100%'),
    array('Branches', 'All India'),
);
$pdfObj->SetFont('Arial','',12);
$pdfObj->SetTextColor(89, 89, 89);
foreach ($rows as $indx => $each) {
    if ($indx%2 == 0) {
        $pdfObj->SetFillColor(239, 239, 239);
    } else {
        $pdfObj->SetFillColor(248, 248, 248);
    }
    $pdfObj->Cell($cellWidth,$cellHeight,$each[0],0,0,'C',$cellFill);
    $pdfObj->Cell($cellWidth,$cellHeight,$each[1],0,0,'C',$cellFill);
    $pdfObj->Ln();
}

/// Let's Connect page
$pdfObj->AliasNbPages();
$pdfObj->AddPage();
// Header
$headerText = "Let's Connect";
$pdfObj->SetTextColor(0, 0, 0);
$pdfObj->Ln(25);
$pdfObj->SetFillColor(199, 2, 0);
$pdfObj->Cell(2,30,'',0,0,'C',$cellFill);
$pdfObj->SetFillColor(244, 244, 243);
$pdfObj->SetFont('Arial','',40);
$pdfObj->Cell(206,30,$headerText,0,0,'C',$cellFill);
$pdfObj->SetFillColor(199, 2, 0);
$pdfObj->Cell(2,30,'',0,0,'C',$cellFill);
// Header
$userName = $userData[1]['first_name'];
$emailId = $userData[1]['email_id'];
$phoneNo = $userData[1]['phone_no'];
$mobileNo = '+91 9582228777';
$comHeight=20;
$comWidthIcon=15;
$comWidthText=150;
$pdfObj->Ln(40);
$pdfObj->SetFont('Courier','',40);
$pdfObj->SetFillColor(255, 255, 255);
$pdfObj->SetLeftMargin(60);
$pdfObj->Cell($comWidthText,30,$userName,0,0,'L',$cellFill);
$pdfObj->Ln();
$pdfObj->SetFont('zapfdingbats','B',30);
$pdfObj->Cell($comWidthIcon,$comHeight,')',0,0,'C',$cellFill);
$pdfObj->SetFont('Courier','',20);
$pdfObj->Cell($comWidthText,$comHeight,$emailId,0,0,'L',$cellFill);
$pdfObj->Ln();
$pdfObj->SetFont('zapfdingbats','B',30);
$pdfObj->Cell($comWidthIcon,$comHeight,'%',0,0,'C',$cellFill);
$pdfObj->SetFont('Courier','',20);
$pdfObj->Cell($comWidthText,$comHeight,$phoneNo,0,0,'L',$cellFill);
$pdfObj->Ln();
$pdfObj->SetFont('zapfdingbats','B',30);
$pdfObj->Cell($comWidthIcon,$comHeight,'&',0,0,'C',$cellFill);
$pdfObj->SetFont('Courier','',20);
$pdfObj->Cell($comWidthText,$comHeight,$mobileNo,0,0,'L',$cellFill);

//Terms
$pdfObj->SetLeftMargin(2);
$pdfObj->Ln(80);
$pdfObj->SetFont('Arial','',12);
$pdfObj->Cell(100,15,'Date : '.date('d-m-Y H:i:s') ,0,0,'L',false);
$pdfObj->Cell(104,15,'Your exclusive plan code: '.$lead_phone ,0,0,'R',false);
$pdfObj->Ln(15);
$pdfObj->SetFont('Arial','B',12);
$pdfObj->SetTextColor(242, 94, 94);
$pdfObj->Cell(20,5,'Terms : ',0,0,'L',false);
$pdfObj->Ln();
$pdfObj->SetTextColor(0, 0, 0);
$pdfObj->SetFont('Arial','',10);
$pdfObj->SetFillColor(255, 255, 255);
$terms_condition = 'This Proposal and any incorporated applicable documents (Proposal) constitute an offer by Expert Pvt. Ltd. hereinafter referred to as Expert to the client party named on Proposal. Valid till next 10 Days. This proposal shall remain open and valid for a period of 30 days from the date of issuance. This proposal does not constitute any agreement between Expert, client, or any third party. Expert reserves the right to Reject the Proposal with or without any information and it is understood that this Proposal is confidential and proprietary to Expert, and no part thereof or any information concerning may be copied, exhibited, or furnished to others.';
$pdfObj->MultiCell(204,5,$terms_condition,0,'J',false);

//$pdfObj->Output();
$pdfObj->Output('D',$lead_name.'-'.$lead_phone.'.pdf');
?>