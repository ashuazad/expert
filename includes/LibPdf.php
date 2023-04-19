<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
require('fpdf185/fpdf.php');

class LibPdf extends FPDF {
    
    public static $HeaderTitle = 'Advance Institute Of Latest Technologies Pvt. Ltd.';
    public static $HeaderLower = 'Near GTB Nagar Metro Station Gate No 4. New Delhi 110009';
    //public static $FooterTitle = date('d-m-Y H:i:s');
    public static $LogoUrl = '../images/logo-main.png';
    public static $phoneIconUrl = '../includes/fpdf185/images/phoneIcon.png';
    public static $emailIconUrl = '../includes/fpdf185/images/e-mailIcon.png';

    //Page Header
    function header()
    {
        $this->Image(self::$LogoUrl,77,10,60,17);
        // Arial bold 15
        $this->SetFont('Arial','B',18);
        $this->SetTextColor(255,0,0);
        // Move to the right
        $this->SetFillColor(247, 245, 245);
        $this->SetLeftMargin(0.0);
        //$this->Cell(0,40,'',0,0,'C',1);
        // Title
        //$this->Cell(50,10,self::$HeaderTitle,0,0,'C');
        // Line break
        /*
        $this->Ln(10);
        $this->SetFont('Arial','',9);
        $this->SetTextColor(0,0,0);
        $this->Cell(190,5,self::$HeaderLower,0,0,'C');
        
        $this->Ln(5);
        $this->SetFont('Arial','',11);
        $this->Image(self::$phoneIconUrl,50,27,10);
        $this->Cell(50,13,'',0);
        $this->Cell(30,13,'9999999999',0,0,'C');
        //$this->SetFont('Arial','',11);
        //$this->Cell(10);
        $this->Image(self::$emailIconUrl,100,27,9);
        $this->Cell(20,13,'',0);
        $this->Cell(55,13,'support@advanceinstitute.in',0,0,'C');
        $this->Ln(10);*/
        //$this->Cell(5);
    }

    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        //$this->Cell(30,10,'Date # '.date('d-m-Y H:i:s'),0,0,'C');
        // Page number
        //$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
}