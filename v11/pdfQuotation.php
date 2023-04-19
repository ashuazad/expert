<?php
require_once '../includes/userqueryDatabase.php';
require_once '../includes/categoryDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once '../includes/db.php';
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
$quotation = $_POST;
function filterCourse(&$item) 
{
    $item = str_replace('-',' ',$item);
}
//print_r($_POST);
array_walk($quotation['courses'], 'filterCourse');
$courseTitle = implode(' + ', $quotation['courses']);
$courseTotalFees = $_POST['course_fee'];
$courseOfferFees = $_POST['total_fee'];
$lead_id = $_POST['lead_id'];
$checkCount = $dbObj->getData(array('id'), 'lead_quotation', "lead_id='".$lead_id."'");
$column = array(
    'lead_id'=>$lead_id,
    'courses'=>$courseTitle,
    'total_price'=>$courseTotalFees,
    'offer_price'=>$courseOfferFees,
    'created_on'=>date('Y-m-d H:i:s'),
    'user_id'=>$id
);
if ($checkCount[0]) {
    unset($column['lead_id']);
    $dbObj->dataUpdate($column,'lead_quotation', 'id', $checkCount[1]['id']);
} else {
    $dbObj->dataInsert($column,'lead_quotation');
}
?>
<!DOCTYPE html>
<html>
    <head>
    <title>Quotation</title>    
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Custom CSS -->
    <link href="dist/css/style.css" rel="stylesheet">
        <style type="text/css">
            
            @page {
                size: A4;
            }
            body{
                background-color:while;
            }
            .header-row{
                margin-top:100px
            }
            .header-c{
                background-color:#c70200;
                height: 110px;
                    }
            .heading-box {
                    color:#c70200;
                    background-color: #f8f9fa;
                    padding-top: 16px;
                    font-weight: 400;
                    font-size: 3rem;
                    -webkit-print-color-adjust: exact;
            }   
            .row{
                padding: 0px 5px 0px 5px;
                } 
            .offer-row{
                height:115px;
                font-weight:400;
            }        
            .offer-cell{
                padding-top:0px;
                background-color: #e6b4b491;
                -webkit-print-color-adjust: exact;
            }
            .pricing-table{
                margin-top:150px;
            }
            .offer-col {
                padding: 0px;
            }
            .offer-price-text{
                font-size: 25px;
            }
            .offer-price-amount{
                font-size: 25px;
                font-weight: 500;
            }
            .total-price-text{
                font-size: 18px;
            }
            .total-price-amount{
                font-size: 20px;
                font-weight: 500;
                text-decoration: line-through;
            }
            .offer-box{
                color: white;
                background-color: #e64039;
                margin: 0px;
            }
            .total-price-box{
                padding-top: 1px;
                margin-top: 4px;
            }
            .addon-row{
                height: 30px;
                font-weight: 500;
                font-size: 15px;
                padding-top: 4px;   
            }
            .addon-row-even{
                background-color: #dde1e5;
            }
            .addon-row-odd{
                background-color: #c3c3f8;
            }
            .addon-row-border{
                border-right: 1px solid #fff;
            }
            .offer-pg-footer-margin{
                margin:500px 0px 0px 0px;
            }
            .page-break{
                page-break-after: always;
            }
            @media print {
                html {
    -webkit-print-color-adjust: exact;
    -webkit-filter: opacity(1);
}
                .row{
                }
                .col-md-6{
                    width: 50%;
                }
                .col-md-12{
                    width: 100%;
                }
                .offset-3{}
                .rounded-top{}
                .header-c{
                        -webkit-print-color-adjust: exact; 
                        }   
                .heading-box {
                }     
                .pricing-table{}
                .offer-row{}
                .offer-cell{
                } 
                .offer-col{}
                .offer-price-text{
                    font-size: 25px;
                }
                .offer-price-amount{
                    font-size: 25px;
                }
                .total-price-text{}
                .total-price-amount{}
                .offer-box{}
                .total-price-box{}
                .addon-row{}
                .addon-row-odd{}
                .addon-row-even{}
                .addon-row-border{}
                .header-row{}
            }
            
        </style>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script> 
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
    </head>
    <body>
        <div class=" printableArea" id="divToExport">
        <button type="button" onclick="generatePDF()" class="btn-sm btn-danger pull-right">Export to PDF</button>
            <div class="header clearfix">
                <img src="../images/logo-main.png" class="logo"/>
            </div>
            <div class="row header-row">
                <div class="col-md-12 text-center header-c">
                    <div class="h-100 heading-box">
                        <?php echo $courseTitle;?>
                    </div>
                </div>
            </div>
            <div class="row pricing-table">
                <div class="col-md-6 offset-3 text-center">
                    <div class="row">
                        <div class="col-md-6 offer-col">
                            <div class="col-md-12 offer-row"></div>
                            <div class="col-md-12 addon-row addon-row-even addon-row-border">Live Traning</div>
                            <div class="col-md-12 addon-row addon-row-odd addon-row-border">Study Material</div>
                            <div class="col-md-12 addon-row addon-row-even addon-row-border">Student Login</div>
                            <div class="col-md-12 addon-row addon-row-odd addon-row-border">Job Placement</div>
                            <div class="col-md-12 addon-row addon-row-even addon-row-border">Lab Assistance</div>
                            <div class="col-md-12 addon-row addon-row-odd addon-row-border">Pan India</div>
                        </div>
                        <div class="col-md-6 rounded-top offer-col">
                            <div class="offer-row offer-cell">
                                <div class="col-md-12 offer-box">
                                    <span class="offer-price-text">Offer Price</span>
                                    <br>
                                    <span class="offer-price-amount">Rs. <?php echo $courseOfferFees;?>/-</span>
                                </div>
                                <div class="col-md-12 total-price-box" >
                                    <span class="total-price-text">Total Price</span>
                                    <span class="total-price-amount">Rs. <?php echo $courseTotalFees;?>/-</span>
                                </div>
                            </div>                                
                            <div class="col-md-12 addon-row addon-row-even">100%</div>
                            <div class="col-md-12 addon-row addon-row-odd">100%</div>
                            <div class="col-md-12 addon-row addon-row-even">YES</div>
                            <div class="col-md-12 addon-row addon-row-odd">100%</div>
                            <div class="col-md-12 addon-row addon-row-even">100%</div>
                            <div class="col-md-12 addon-row addon-row-odd">100%</div>
                        </div>


                    </div>
                    
                </div>    
            </div>
            <div class="footer clearfix offer-pg-footer-margin text-center">
                <h4>Advance Institute of latest Technology. ISO 9001:2008 Certified Institution. All right reserved.</h4>
                <h4>Address : Near GTB Nagar Metro Station Gate No 4. New Delhi 110009.</h4>
                <h4>Phone: +91 920 530 8249 E-mail: support@advanceinstitute.in Timming: Mon- Sat: 09:00 -19:00</h4>
            </div>
            <div class="page-break"></div>
            <div class="header clearfix">
                <img src="../images/logo-main.png" class="logo"/>
            </div>
            <div class="row header-row">
                <div class="col-md-12 text-center header-c">
                    <div class="h-100 heading-box">
                        Let's get Connected
                    </div>
                </div>
            </div>
        </div>    
        <button id="print" class="btn btn-default btn-outline" type="button" style="display:none"> <span><i class="fa fa-print"></i> Print</span> </button>
        <script type="text/javascript">
  function generatePDF() {
        
        // Choose the element id which you want to export.
        var element = document.getElementById('divToExport');
        element.style.width = '700px';
        element.style.height = '900px';
        var opt = {
            margin:       0.5,
            filename:     'myfile.pdf',
            image:        { type: 'jpeg', quality: 1 },
            html2canvas:  { scale: 1 },
            jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait',precision: '12' }
          };
        
        // choose the element and pass it to html2pdf() function and call the save() on it to save as pdf.
        html2pdf().set(opt).from(element).save();
      }
</script>
    </body>
</html>
<script src="assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
<!-- Bootstrap tether Core JavaScript -->
<script src="assets/node_modules/popper/popper.min.js"></script>
<script src="assets/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
<!--Custom JavaScript -->
<script src="dist/js/custom.min.js"></script>