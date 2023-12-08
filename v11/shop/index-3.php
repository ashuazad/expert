<?php
$status=$_POST["status"];
$firstname=$_POST["firstname"];
$amount=$_POST["amount"];
$txnid=$_POST["txnid"];
$posted_hash=$_POST["hash"];
$key=$_POST["key"];
$productinfo=$_POST["productinfo"];
$email=$_POST["email"];
$paymentMode=$_POST["mode"];
$salt="";
$paymentModeArray = array('NB'=>'Netbanking','DC'=>'Debit Card','CC'=>'Credit Card');
$base_url = 'https://www.advanceinstitute.co.in/';
$loginURL = 'https://www.advanceinstitute.co.in/';
if($status == 'success'){
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $base_url."api_v1/index.php/Registration/get_regno",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "{\"branch_id\":\"21\"}",
      CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "content-type: application/json"
      ),
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    if($err){
        error_log("\n Get RegNo Error : " . $err, 3, 'online_payment.log');
    }
    curl_close($curl);
    error_log("\n RegNo : " . $response, 3, 'online_payment.log');
    $regDtl = json_decode($response,true);
    $_POST['regNo'] = $regDtl['regno'];

    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $base_url . "api_v1/index.php/Registration/add",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => json_encode($_POST),
      CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "content-type: application/json"
      ),
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if($err){
        error_log("\n Reg Error : " . $err, 3, 'online_payment.log');
    }
    error_log("\n Reg Success : " . $response, 3, 'online_payment.log');
    $responseAdd = json_decode($response,true);
    
    //var_dump($responseAdd);
    if($responseAdd['success'] === false){
        header('location:' . $base_url . 'v11/payment');
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png">
    <title>Expert Online</title>
    <link href="../assets/node_modules/wizard/steps.css" rel="stylesheet">
    <link href="../assets/node_modules/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../dist/css/style.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
    <link href="../assets/node_modules/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />
    <link href="../assets/node_modules/multiselect/css/multi-select.css" rel="stylesheet" type="text/css" />
    <link href="../dist/css/choices.min.css" rel="stylesheet" />
    <script src="../dist/js/choices.min.js" type="text/javascript"></script>
</head>

<body class="skin-default-dark fixed-layout">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label">Expert</p>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <header class="topbar">
            <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                <!-- ============================================================== -->
                <!-- Logo -->
                <!-- ============================================================== -->
                <div class="navbar-header">
                    <a class="navbar-brand" href="index.html">
                        <!-- Logo icon --><b>
                            <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                            <!-- Dark Logo icon -->
                            <img src="../assets/images/logo-icon.png" alt="homepage" class="dark-logo" />
                            <!-- Light Logo icon -->
                            <img src="../assets/images/logo-light-icon.png" alt="homepage" class="light-logo" />
                        </b>
                        <!--End Logo icon -->
                        <!-- Logo text --><span>
                         <!-- dark Logo text -->
                         <img src="../assets/images/logo-text.png" alt="homepage" class="dark-logo" />
                         <!-- Light Logo text -->
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <div class="navbar-collapse">
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav mr-auto">
                        <!-- This is  -->
                        <li class="nav-item"> <a class="nav-link nav-toggler d-block d-md-none waves-effect waves-dark" href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
                        <li class="nav-item"> <a class="nav-link sidebartoggler d-none d-lg-block d-md-block waves-effect waves-dark" href="javascript:void(0)"><i class="icon-menu"></i></a> </li>
                        <!-- ============================================================== -->
                        <!-- Search -->
                        <!-- ============================================================== -->
                        <li class="nav-item">
                            <form class="app-search d-none d-md-block d-lg-block">
                                <input type="text" class="form-control" placeholder="Search & enter">
                            </form>
                        </li>
                    </ul>
                    <!-- ============================================================== -->
                    <!-- User profile and search -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav my-lg-0">
                        <!-- ============================================================== -->
                        <!-- Comment -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="ti-email"></i>
                                <div class="notify"> <span class="heartbit"></span> <span class="point"></span> </div>
                            </a>
                            
                        </li>
                        <!-- ============================================================== -->
                        <!-- End Comment -->
                        <!-- ============================================================== -->
                        <!-- ============================================================== -->
                        <!-- Messages -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" id="2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="icon-note"></i>
                                <div class="notify"> <span class="heartbit"></span> <span class="point"></span> </div>
                            </a>
                            
                        </li>
                        <!-- ============================================================== -->
                        <!-- End Messages -->
                        <!-- ============================================================== -->
                        <!-- ============================================================== -->
                        <!-- mega menu -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown mega-dropdown"> <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ti-layout-width-default"></i></a>
                            <div class="dropdown-menu animated bounceInDown">
                                <ul class="mega-dropdown-menu row">
                                    <li class="col-lg-3 col-xlg-2 m-b-30">
                                        <h4 class="m-b-20">CAROUSEL</h4>
                                        <!-- CAROUSEL -->
                                        <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                                            <div class="carousel-inner" role="listbox">
                                                <div class="carousel-item active">
                                                    <div class="container"> <img class="d-block img-fluid" src="../assets/images/big/img1.jpg" alt="First slide"></div>
                                                </div>
                                                <div class="carousel-item">
                                                    <div class="container"><img class="d-block img-fluid" src="../assets/images/big/img2.jpg" alt="Second slide"></div>
                                                </div>
                                                <div class="carousel-item">
                                                    <div class="container"><img class="d-block img-fluid" src="../assets/images/big/img3.jpg" alt="Third slide"></div>
                                                </div>
                                            </div>
                                            <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev"> <span class="carousel-control-prev-icon" aria-hidden="true"></span> <span class="sr-only">Previous</span> </a>
                                            <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next"> <span class="carousel-control-next-icon" aria-hidden="true"></span> <span class="sr-only">Next</span> </a>
                                        </div>
                                        <!-- End CAROUSEL -->
                                    </li>
                                    
                                    <li class="col-lg-3  m-b-30">
                                        <h4 class="m-b-20">CONTACT US</h4>
                                        <!-- Contact -->
                                        <form>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="exampleInputname1" placeholder="Enter Name"> </div>
                                            <div class="form-group">
                                                <input type="email" class="form-control" placeholder="Enter email"> </div>
                                            <div class="form-group">
                                                <textarea class="form-control" id="exampleTextarea" rows="3" placeholder="Message"></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-info">Submit</button>
                                        </form>
                                    </li>
                                    <li class="col-lg-3 col-xlg-4 m-b-30">
                                        <h4 class="m-b-20">List style</h4>
                                        <!-- List style -->
                                        <ul class="list-style-none">
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> You can give link</a></li>
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> Give link</a></li>
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> Another Give link</a></li>
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> Forth link</a></li>
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> Another fifth link</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <!-- ============================================================== -->
                        <!-- End mega menu -->
                        <!-- ============================================================== -->
                        <li class="nav-item right-side-toggle"> <a class="nav-link  waves-effect waves-light" href="javascript:void(0)"><i class="ti-settings"></i></a></li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- User Profile-->
                <div class="user-profile">
                    <div class="user-pro-body">
                        <div><img src="../assets/images/users/4.jpg" alt="user-img" class="img-circle"></div>
                        <div class="dropdown">
                            <a href="javascript:void(0)" class="dropdown-toggle u-dropdown link hide-menu login-user" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Steave Gection <span class="caret"></span></a>
                            
                        </div>
                    </div>
                </div>
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li class="nav-small-cap">--- MAIN MENU</li>
						<li>
                            <a class="waves-effect waves-dark" href="" aria-expanded="false">
                                <i class="icon-speedometer"></i>
                                <span class="hide-menu">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-small-cap">--- SUPPORTS</li>
                        <li>
                            <a class="waves-effect waves-dark" href="../pages-login.html" aria-expanded="false">
                                <i class="icon-logout"></i>
                                <span class="hide-menu">Log Out</span>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                    <div class="col-md-5 align-self-center">
                        <h4 class="text-themecolor">Admission</h4>
                    </div>
                    <div class="col-md-7 align-self-center text-right">
                        <div class="d-flex justify-content-end align-items-center">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                                <li class="breadcrumb-item active">Admission</li>
                            </ol>
                            
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body wizard-content payment-form-wizard">
                                    <div class="row">
                                        <div class="col-md-9 mx-auto">
                                            <div id="previewSelectedCourses">
                                                <div class="animate fadeIn costDetail" id="costDetail-step3" style="margin-top: 10px;margin-left: 0px;">
                                                </div>
                                                <div class="animate fadeIn" id="orderTotal-step3">
            
                                                    <div class="animate fadeIn" id="saveOrder">
                                                        <div class="animate fadeIn"></div>
                                                
                                                    </div>
                                                </div>
                                               </div>     
                                        </div>
                                    </div>
                                <form action="#" class="tab-wizard wizard-circle payment-form-wizard">
                                    <!-- Step 1 -->
                                    <h6>Personel Info</h6>
                                    <section>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="jobTitle1">Name :</label>
                                                    <input type="text" class="form-control personal-info-field" id="inputName"/> 
                                                    <label style="display: none;" class="text-danger payment-error-name">This field is required.</label>
                                                </div>  
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="videoUrl1">E-Mail :</label>
                                                    <input type="text" class="form-control personal-info-field" id="inputEmail">
                                                    <label style="display: none;" class="text-danger payment-error-email">This field is required.</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="shortDescription1">Phone :</label>
                                                    <input name="shortDescription" id="inputPhone" class="form-control personal-info-field" />
                                                    <label style="display: none;" class="text-danger payment-error-phone">This field is required.</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="jobTitle1">Select Courses :</label>
                                                    <select id="inputCourses" placeholder="Please Select Courses" multiple></select>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                    <!-- Step 2 -->
                                    <h6>Payment</h6>
                                    <section>
                                        <div class="row">
                                            <div class="col-md-4 m-b-15 mx-auto">
                                                <button class="btn btn-block text-center" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                                    <h3>Custom Total Amount</h3>
                                                </button>
                                                <div class="form-group row">
                                                    <label class="col-md-2 text-right p-t-10">Rs.</label>
    <div class="col-md-6">
    <input type="text" name="manual" id="manualEntr" class="form-control editTxtFld">
    </div><div class="col-md-4">
<button class="btn btn-info" type="button" id="customAmtPay">Pay Now</button>
    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                    <!-- Step 3 -->
                                    <h6>Payment Done</h6>
                                    <section>
                                        <div class="row">
                                            <div class="col-md-9 mx-auto">
                                                <div class="col-md mx-auto text-center">
                                                    <h2> Your payment has been received successfully </h2>
                                                    <img width="10%" class="payment-successful" src="../images/payment-successful.png">
                                                </div>
                                                <div class="table-responsive m-t-40">
                                                    <table class="table table-hover">
                                                        <tbody>
                                                            <tr>
                                                                <td width="50%" class="text-right  ">Your Transaction ID : </td>
                                                                <td width="50%" class="text-left  "><?php echo $txnid;?></td>
                                                            </tr>
                                                            <tr>
                                                                <td width="50%" class="text-right  ">Payment Mode : </td>
                                                                <td width="50%" class="text-left  "><?php echo $paymentModeArray[$_POST['mode']]."-PayU";?></td>
                                                            </tr>
                                                            <tr>
                                                                <td width="50%" class="text-right  ">Payment Rs : </td>
                                                                <td width="50%" class="text-left  "><?php echo $amount;?></td>
                                                            </tr>
                                                            <tr>
                                                                <td width="50%" class="text-right  ">User ID : </td>
                                                                <td width="50%" class="text-left  "><?php echo $regDtl['regno'];?></td>
                                                            </tr>
                                                            <tr>
                                                                <td width="50%" class="text-right  ">Password : </td>
                                                                <td width="50%" class="text-left  "><?php echo $regDtl['regno'];?></td>
                                                            </tr>
                                                            <tr>
                                                                <td width="50%" class="text-right  ">Login URL : </td>
                                                                <td width="50%" class="text-left  "><a target="_blank" href="https://www.expertinstituteindia.in/" class="inner-elem"><?php echo $loginURL;?></a></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </form>
                            </div>
                            <form action="https://secure.payu.in/_payment" method="post" name="" id="frmPay">

                                <input type="hidden" name="key" value="H9iQZR">
                                <input type="hidden" name="hash" value="40482101505d793127a993e36bc65312c90e8845b6992f8b57263b71071ec64ef45ac3ffefa55ff6913dd7dd64599baec86ac8129a571eb34a5dd35feb73bd6c">
                                <input type="hidden" name="txnid" value="1692894815217">
                                <input type="hidden" usr-data="" name="amount" value="30000">
                                
                                <input type="hidden" name="firstname" id="firstname" value="twerr">
                                <input type="hidden" name="email" id="email" value="rettt@gmail.com">
                                <input type="hidden" name="phone" value="12789999999">
                                <input type="hidden" name="productinfo" value="CCTV-Camera-Course,">
                                
                                <input type="hidden" name="surl" value="https://www.advanceinstitute.co.in/v11/shop/index-3.php?payment=done" size="64">
                                <input type="hidden" name="furl" value="https://www.advanceinstitute.co.in/v11/shop/index-3.php?payment=notdone" size="64">
                                <input type="hidden" name="service_provider" value="payu_paisa" size="64">
                
                                
                            </form>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End PAge Content -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Right sidebar -->
                <!-- ============================================================== -->
                <!-- .right-sidebar -->
            
                <!-- ============================================================== -->
                <!-- End Right sidebar -->
                <!-- ============================================================== -->
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- footer -->
        <!-- ============================================================== -->
        <footer class="footer">
            © 2020 Eliteadmin by themedesigner.in
        </footer>
        <!-- ============================================================== -->
        <!-- End footer -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="../assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="../assets/node_modules/popper/popper.min.js"></script>
    <script src="../assets/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="../dist/js/perfect-scrollbar.jquery.min.js"></script>
    <!--Wave Effects -->
    <script src="../dist/js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="../dist/js/sidebarmenu.js"></script>
    <!--stickey kit -->
    <script src="../assets/node_modules/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="../assets/node_modules/sparkline/jquery.sparkline.min.js"></script>
    <!--Custom JavaScript -->
    <script src="../assets/node_modules/wizard/jquery.steps.min.js"></script>
    <script src="../assets/node_modules/wizard/jquery.validate.min.js"></script>
    <script src="../assets/node_modules/sweetalert2/dist/sweetalert2.min.js"></script>

    <script src="../dist/js/custom.min.js"></script>
    <script type="application/javascript">
        var seletedCourse = '';
        let coursesFees = {};
        var base_url = 'https://www.advanceinstitute.co.in';
        var params = new URL(document.location).searchParams;
        var paymentDone = params.get("payment");
        function reqHash(){ return Date.now() }   
        
        function validateUser(){
              var goForward = true;
                 if($("#inputName").val() == ''){
                   goForward = false;  
                   $(".payment-error-name").show("fast");
                   $(".inputName").addClass("txtError");
                 } else {
                    $(".payment-error-name").hide("fast");
                 }
                 
                 if($("#inputEmail").val() == ''){
                   goForward = false;
                   $(".payment-error-email").show("fast");  
                   $(".inputEmail").addClass("txtError");
                 } else {
                    $(".payment-error-email").hide("fast");  
                 }
                 
                 if($("#inputPhone").val() == ''){
                   goForward = false;
                   $(".payment-error-phone").show("fast");
                   $(".inputPhone").addClass("txtError");
                 } else {
                    $(".payment-error-phone").hide("fast");
                 }
              if(goForward){
                 $("#helpBlock2").fadeOut("fast"); 
              }else{
                  $("#helpBlock2").fadeIn("fast"); 
              }   
              return goForward;
          }
          var hashVal = '';
          function getHash(){
              
              $.ajax({
                async : false,    
                method: "POST",
                url: base_url + "/api_v1/index.php/Payment/get_hash" + "?&_=" + reqHash(),
                /*url: base_url + "/payment/getHash.php" + "?&_=" + reqHash(),*/
                data: $( 'form' ).serializeArray()
              })
                .done(function( msg ) {
                  objMsg = JSON.parse(msg);
                  if(objMsg.success){
                      hashVal = objMsg.data;
                  }
                });
              return hashVal;
          }
          function getFeeDetails( coursesList, isCustom=false){
              var postPayloadStr = '{"courses":"' + coursesList + '"}';
              var settings = {
                        "async": true,
                        "crossDomain": true,
                        "url": base_url + "/api_v1/index.php/Registration/get_fee" + "?&_=" + reqHash(),
                        "method": "POST",
                        "headers": {
                          "content-type": "application/json",  
                          "cache-control": "no-cache"
                        },
                        "data": postPayloadStr
                      }
                      var orderTotalHtml = '<div class="sperationLine"></div>';
                      $.ajax(settings).done(function (orderTotalDetail) {
                         var orderTotal = JSON.parse( orderTotalDetail );
                         if(orderTotal.success == true){
                             var amtUsr;
                             if($('input[name="amount"]').attr("usr-data") == ''){
                                 amtUsr = orderTotal.data.totalFee;
                             }else{
                                 amtUsr = $('input[name="amount"]').attr("usr-data");
                             }
                              $('input[name="amount"]').val(amtUsr);
                              /*$('input[name="amount"]').val(1);*/
                              $('input[name="txnid"]').val(reqHash());
                              /*$('input[name="txnid"]').val('919d488ec97727676e9d');*/
                              $('input[name="firstname"]').val($("#inputName").val());
                              $('input[name="email"]').val($("#inputEmail").val());
                              $('input[name="phone"]').val($("#inputPhone").val());
                              $('input[name="productinfo"]').val(coursesList);
                              
                              orderTotalHtml += '<div class="row order-summary-padding order-total-row"><div class="col-md-8 order-box-header"> Sub Total </div><div class="col-md-4 order-box-header text-right">Rs. ' + orderTotal.data.totalMainFee + '</div></div>';
                              orderTotalHtml += '<div class="row order-summary-padding"><div class="col-md-8 order-box-header"> Discount Amount </div><div class="col-md-4 order-box-header text-right"> - Rs. ' + orderTotal.data.disCountAmt + '</div></div>';
                              orderTotalHtml += '<div class="row order-summary-padding"><div class="col-md-8 order-box-header"> Discount Percentage </div><div class="col-md-4 order-box-header text-right">' + orderTotal.data.disCountPercent.trim() + '% </div></div>';
                              orderTotalHtml += '<hr><div class="row order-summary-padding order-total-row"><div class="col-md-8 order-box-header"> Total </div><div class="col-md-4 order-box-header text-right" id="odrTotal">Rs. ' + amtUsr + '&nbsp; <img src="../images/ic_edit.png" class="editTtlEdit" onclick="editPrice(' + orderTotal.data.totalFee + ',' + "'edit'" + ')"></div></div>';
                              $("#orderTotal-step3").html(orderTotalHtml);
                             
                             /*console.log( 'Hast ' + getHash() );*/
                             if(validateUser()){
                                 var comHash = getHash();
                                 //var comHash = null;
                                 if(comHash){
                                     $('input[name="hash"]').val(comHash);
                                     $("#sendPay").css("display","block");
                                     $("#sendPay").click(function(){
                                         $("#frmPay").submit();
                                     });
                                     $("#proceedToPayment").html('<button class="btn btn-danger" id="proceedToPayBtn" type="submit"> Proceed to payment </button>');
                                     $("#proceedToPayBtn").click(function(){
                                         $("#frmPay").submit();
                                     });
                                     if (isCustom) {
                                        $("#frmPay").submit();
                                     }
                                 }
                                  event.preventDefault();
                             }
                          }
                      });
          }
          function getPaymentBtn(isCustom=false){
              if(validateUser() == false){
                  $('select[id="inputCourses"] option:selected').prop("selected", false);
                  return;
              }
              
              var seletedCourse = '';
              var costTxt = '<div class="row order-summary-padding"><div class="col-xs-12 col-sm-6 col-md-8"></div><div class="col-xs-6 col-md-4"></div></div><div class="row order-summary-padding"><div class="col-xs-12 col-sm-6 col-md-8 order-box-header">Course</div><div class="col-xs-6 col-md-4 order-box-header text-right">Price</div></div>';
              if($( ('select[id="inputCourses"] option:selected').length > 0) ){
                
                  $('select[id="inputCourses"] option:selected').each(function(){
                      seletedCourse += $(this).attr('data-key') + ',';
                      costTxt += '<div class="row order-summary-padding"><div class="col-xs-12 col-sm-6 col-md-8">' + $(this).val().replaceAll('-',' ') + '</div><div class="col-xs-6 col-md-4  text-right">'+ coursesFees[$(this).val()].fee +'</div></div>';
                  });
                  $(".costDetail").html(costTxt);
                  getFeeDetails(seletedCourse,isCustom);
              }
          }
          function editPrice(dataAmt , optCase){
                if(optCase == 'edit'){
                    $("#odrTotal").html('Rs. <input type="text" name="manual" id="manualEntr" class="editTxtFld" value="' + dataAmt + '">  <img src="../images/ic_ok.png" class="editTtlEdit" onclick="editPrice(' + dataAmt + ', '+ "'add'" + ')">' );
                }else{
                    var nVal = $(".editTxtFld").val(); 
                    $("#odrTotal").html('Rs. ' + nVal + ' /- &nbsp; <img src="../images/ic_edit.png" class="editTtlEdit" onclick="editPrice(' + $(".editTxtFld").val() + ', '+ "'edit'" + ')">' );  
                    
                    $('input[name="amount"]').val(nVal);
                    $('input[name="amount"]').attr("usr-data",nVal);
                   // $( 'select[id="inputCourses"]' ).trigger( "change" );
                   getPaymentBtn();
                }
            }
         $(".tab-wizard").steps({
            headerTag: "h6",
            bodyTag: "section",
            transitionEffect: "fade",
            enableFinishButton:false,
            forceMoveForward:true,
            titleTemplate: '<span class="step">#index#</span> #title#',
            labels: {
                finish: "Submit"
            },
            onStepChanging: function (event, currentIndex, newIndex) {
                var returnFlag = true;
                if (paymentDone == 'done') {
                    return returnFlag;
                }
                console.log(currentIndex);
                console.log(newIndex);
                $(".payment-error-name").hide("fast");
                switch (currentIndex) {
                    case 0:
                        returnFlag = (seletedCourse.length>0);
                        if (seletedCourse.length>0 && validateUser()) {
                            getPaymentBtn();
                            returnFlag = true;
                        } else {
                            returnFlag = false;
                        }
                    break;
                    case 1:
                        returnFlag = validateUser();
                        //getPaymentBtn();
                    break;
                 }
                 if (newIndex == 2) {
                    if (!paymentDone) {
                        returnFlag = false;
                    }
                    $('#previewSelectedCourses').hide('fast');
                 } else {
                     $('#previewSelectedCourses').show('fast');
                 }
                return returnFlag;
            },
            onFinished: function (event, currentIndex) {
                //Swal.fire("Form Submitted!", "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed lorem erat eleifend ex semper, lobortis purus sed.");
                $("#frmPay").submit();
            },
            labels: {
                finish: "PAY NOW"
                }
        });
        $( document ).ajaxStart(function() {
             $("#loaderDv").fadeIn("Slow");
          });
          
          $( document ).ajaxStop(function() {
             $("#loaderDv").fadeOut("Slow");
          });    
        $(document).ready(function(){
         $('.personal-info-field').keyup(function(){
            validateUser();
         }); 

          /* Get Courses List */
          var settings = {
              "async": false,
              "crossDomain": true,
              "url": base_url + "/api_v1/index.php/Registration/get_courses" + "?&_=" + reqHash(),
              "method": "GET",
              "headers": {
              "cache-control": "no-cache"
                          }
                      }
  
          $.ajax(settings).done(function (response) {
             var courses = JSON.parse( response );
             var courseSelectBox = '';
             if(courses.success == true){
              $.each( courses.data, function( key, value ) {
                      courseSelectBox += '<option value = "'+ value.course +'" option-data="' + value.fee + '" data-key="' + value.course_key + '">' + value.course.replace( /-/g ,' ' ) + '</option>';
                      coursesFees[value.course]={fee:value.fee,course_key:value.course_key};
                      });
                      console.log(coursesFees);
              $("#inputCourses").html(courseSelectBox);        
             }
          });          
          /* Get Courses Fee */
          
          var selectedCoursesPreview = '';      
          var tdCls = `shop-order-details-cell `;                             
          $('select[id="inputCourses"]').change(function(){
           // getPaymentBtn();
            selectedCoursesPreview = `<h5 class="card-title">Selected Courses</h5><table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center shop-order-details-cell">#</th>
                                                <th class="shop-order-details-cell">Course</th>
                                                <th class="text-right shop-order-details-cell">Price</th>
                                            </tr>
                                        </thead>
                                    <tbody>`;
            //$("#previewSelectedCourses").html('');
            seletedCourse='';
            if ($('select[id="inputCourses"] option:selected').length) {
                $('select[id="inputCourses"] option:selected').each(function(indx){
                    selectedCoursesPreview += `<tr>
                                                <td class="${tdCls} text-center">${indx+1}</td>
                                                <td class="${tdCls}">${$(this).text().replaceAll('-',' ')}</td>
                                                <td class="${tdCls} text-right">${coursesFees[$(this).val()].fee}</td>
                                            </tr>`;
                    if (seletedCourse.indexOf($(this).attr('data-key'))<0) {
                        seletedCourse += $(this).attr('data-key') + ',';
                    }
                });
            }else{
                seletedCourse='';
            }
            
            
            selectedCoursesPreview +='</tbody></table>';
            (seletedCourse.length>0)?getOrderDetails(seletedCourse,selectedCoursesPreview):$("#previewSelectedCourses").html(seletedCourse);
          });
  
          function getOrderDetails(coursesList,coursesListHtml) {
            var postPayloadStr = '{"courses":"' + coursesList + '"}';
              var settings = {
                        "async": true,
                        "crossDomain": true,
                        "url": base_url + "/api_v1/index.php/Registration/get_fee" + "?&_=" + reqHash(),
                        "method": "POST",
                        "headers": {
                          "content-type": "application/json",  
                          "cache-control": "no-cache"
                        },
                        "data": postPayloadStr
                      }
                      coursesListHtml += `<div class="col-md-12 p-r-0">
                        <div class="pull-right m-t-15 text-right">`;
                      var orderTotalHtml = ``;
                      $.ajax(settings).done(function (orderTotalDetail) {
                        
                         var orderTotal = JSON.parse( orderTotalDetail );
                         if(orderTotal.success == true){
                            var amtUsr;
                            amtUsr = orderTotal.data.totalFee; 
                            orderTotalHtml += coursesListHtml;
                            orderTotalHtml += `<p>
                                               Sub Total amount : Rs.${orderTotal.data.totalMainFee}
                                            </p>`;
                            
                            orderTotalHtml += `<p>
                                                Discount Percentage (${orderTotal.data.disCountPercent.trim()}%) : Rs.${orderTotal.data.disCountAmt}</td>
                                            </p>`;                                            
                            orderTotalHtml += `<h5>
                                                <b>Total :</b> Rs. ${amtUsr} /- 
                                            </h5><div id="proceedToPayment"></div>`;
                             orderTotalHtml +=`</div> <div class="clearfix"></div> <hr> </div>`;
                             $("#previewSelectedCourses").html(orderTotalHtml);
                          }
                      });
          }
          
          $("#customAmtPay").click(function(){
            var customVal = $("#manualEntr").val();
            if ((customVal == '') || (customVal == 0)) {
                alert('Please enter amount');
            } else {
                $('input[name="amount"]').val(customVal);
                $('input[name="amount"]').attr("usr-data",customVal);
                getPaymentBtn(true);
            }
          });
          if (paymentDone == 'done') {
            $(".tab-wizard").steps("next");
            $(".tab-wizard").steps("next");
            $(".tab-wizard").steps("next");
          }
        });
      </script>
      <script src="../assets/node_modules/bootstrap-select/bootstrap-select.min.js" type="text/javascript"></script>
      <script type="text/javascript" src="../assets/node_modules/multiselect/js/jquery.multi-select.js"></script>
      <script>
        $(function () {
            $('.selectpicker').selectpicker();
        });
        $(document).ready(function(){
    
    var multipleCancelButton = new Choices('#inputCourses', {
       removeItemButton: true,
       maxItemCount:25,
       searchResultLimit:25,
       renderChoiceLimit:25
     }); 
    
    
});
      </script> 
</body>

</html>