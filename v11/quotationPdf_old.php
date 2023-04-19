<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require('../includes/LibPdf.php');

$name = $_POST['name'];
$phone = $_POST['phone'];
$text = 'Greetings from Advance Institute and thanks for your query';

$pdfObj = new LibPdf();
$pdfObj->AliasNbPages();
$pdfObj->AddPage();
$pdfObj->SetFont('Arial','',11);
$pdfObj->Ln(10);
$pdfObj->Cell(0,5,'Hello '.$name.',',0,1);
$pdfObj->Cell(0,5,'Phone No:'.$phone,0,1);
//$pdfObj->Cell(200,1,'',0,1,'C');
$pdfObj->Ln(5);
$pdfObj->Cell(0,5,$text,0,1);
$pdfObj->Ln(10);
$data = array();
$cellBorder = '0';
$cellFill = true;
$cellWidth = 56.5;
$cellWidthA = 77;
$cellHeight = 7;
$cellHeightTitle = 10;
//$courseArray = array('Course 1','Course 2');
$courseArray = $_POST['courses'];
$totalPrice = $_POST['course_fee'];
$offerPrice = $_POST['total_fee'];
//Table Header
$pdfObj->SetFont('Arial','B',16);
$pdfObj->SetFillColor(247, 233, 233);
$pdfObj->Cell($cellWidthA,$cellHeightTitle,'Courses',0,0,'C',$cellFill);
$pdfObj->SetFillColor(235, 221, 221);
$pdfObj->Cell($cellWidth,$cellHeightTitle,'Total Price',0,0,'C',$cellFill);
$pdfObj->SetFillColor(247, 233, 233);
$pdfObj->Cell($cellWidth,$cellHeightTitle,'Offer Price',0,0,'C',$cellFill);
$pdfObj->Ln(10);
$pdfObj->Cell(190,1,'',0,1,'C');

$middelIndex = (count($courseArray) == 2) ? 1 : (ceil(count($courseArray)/2)-1);
//Table Rows
foreach ($courseArray as $indx => $each) {
    $pdfObj->SetFont('Arial','B',11);
    $pdfObj->SetFillColor(247, 245, 245);
    $each = str_replace('-',' ',$each);
    $pdfObj->Cell($cellWidthA,$cellHeight,$each,$cellBorder,0,'C',$cellFill);
    if ($middelIndex == $indx) {
        $pdfObj->SetFont('Arial','BI',16);
        $pdfObj->SetFillColor(235, 235, 235);
        $pdfObj->Cell($cellWidth,$cellHeight,'Rs. '.$totalPrice,$cellBorder,0,'C',$cellFill);
        $pdfObj->SetFont('Arial','B',20);
        $pdfObj->SetFillColor(247, 245, 245);
        $pdfObj->Cell($cellWidth,$cellHeight,'Rs. '.$offerPrice,$cellBorder,0,'C',$cellFill);
    } else {
        $pdfObj->SetFillColor(235, 235, 235);
        $pdfObj->Cell($cellWidth,$cellHeight,'',$cellBorder,0,'C',$cellFill);
        $pdfObj->SetFillColor(247, 245, 245);
        $pdfObj->Cell($cellWidth,$cellHeight,'',$cellBorder,0,'C',$cellFill);
    }
    $pdfObj->Ln();
}
$pdfObj ->Output();
?>