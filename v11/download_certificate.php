<?php
session_start();
$stdRegno = '';
if (isset($_SESSION['Zend_Auth']) && !empty($_SESSION['Zend_Auth'])) {
    $stdRegno = $_SESSION['Zend_Auth']['storage']->regno;
}
if (!$_SESSION['id'] && empty($stdRegno)) {
    header('Location: https://www.advanceinstitute.co.in');
    exit;
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
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon.png">
    <title> Download Certificate </title>
    <!-- chartist CSS -->
    <link href="assets/node_modules/chartist-js/dist/chartist.min.css" rel="stylesheet">
    <link href="assets/node_modules/chartist-js/dist/chartist-init.css" rel="stylesheet">
    <link href="assets/node_modules/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.css" rel="stylesheet">
    <link href="assets/node_modules/css-chart/css-chart.css" rel="stylesheet">
    <!-- Editable CSS -->
    <link type="text/css" rel="stylesheet" href="assets/node_modules/jsgrid/jsgrid.min.css" />
    <link type="text/css" rel="stylesheet" href="assets/node_modules/jsgrid/jsgrid-theme.min.css" />
    <!-- Custom CSS -->
    <link href="dist/css/style.css" rel="stylesheet">
    <!-- page css -->
    <link href="dist/css/pages/widget-page.css" rel="stylesheet">
    <link href="dist/css/pages/tab-page.css" rel="stylesheet">
    <link href="assets/node_modules/toast-master/css/jquery.toast.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body class="skin-default-dark fixed-layout">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label">EXPERT</p>
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
                <div class="navbar-header" style="background:#a0aec4;">
                    <a class="navbar-brand" href="index.html">
                        <!-- Logo icon --><b>
                            <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                            <!-- Dark Logo icon -->
                            <img src="logo-main.png" alt="homepage" class="dark-logo" width="200" style="display:none;"/>
                            <!-- Light Logo icon -->
                            <img src="logo-main.png" alt="homepage" class="light-logo" width="200" style="display:none;"/>
                        </b>
                        <!--End Logo icon -->
                        <!-- Logo text --><span>
                         <!-- dark Logo text -->
                         <img src="assets/images/logo-text.png" alt="homepage" class="dark-logo" />
                         <!-- Light Logo text -->    
                         </span> </a>
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <div class="navbar-collapse">
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav mr-auto">
                    
                    </ul>
                    <!-- ============================================================== -->
                    <!-- User profile and search -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav my-lg-0">
                        <!-- ============================================================== -->
                        <!-- Comment -->
                        <!-- ============================================================== -->
                
                        <!-- ============================================================== -->
                        <!-- End Comment -->
                        <!-- ============================================================== -->
                        <!-- ============================================================== -->
                        <!-- Messages -->
                        <!-- ============================================================== -->
                        
                        <!-- ============================================================== -->
                        <!-- End Messages -->
                        <!-- ============================================================== -->
                        <!-- ============================================================== -->
                        <!-- mega menu -->
                        <!-- ============================================================== -->
                        <!-- ============================================================== -->
                        <!-- End mega menu -->
                        <!-- ============================================================== -->
                    </ul>
                </div>
            </nav>
        </header>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
       
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper" style="margin-left:0px;">
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                    <div class="col-md-5 align-self-center">
                        <h4 class="text-themecolor">Download Certificate</h4>
                    </div>

                </div>
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                
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

                <!-- -->
                <div class="row justify-content-center">
                    <div class="col-md-9">
                        <div class="card">
                            <div class="card-body">
                                <div class="row stuDetails" style="display:none;">
                                    <div class="col-md-12 text-center">
                                        <h4>Name</h4>
                                        <h2 class="studName"></h2>
                                        <h4>Registration No</h4>
                                        <h2 class="studRegno"></h2>
                                    </div>
                                </div>
                                <table class="table color-table dark-table table-hover table-certificate" style="display:none;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th class="text-center">Course Name</th>
                                            <th class="text-center">Download Link</th>
                                        </tr>
                                    </thead>
                                    <tbody class="downloadLinkTab">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="student-details" class="modal show" tabindex="-1" role="dialog" aria-labelledby="grid-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                    <div class="modal-dialog modal-dialog-centered" style="max-width: 650px;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <img src="logo-main.png" alt="homepage" class="dark-logo" width="200" style="margin:0 auto;">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="display:none">×</button>
                            </div>
                            <div class="modal-body">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Registration No : </label>
                                        <div class="controls">
                                            <input type="text" name="text" class="form-control regno" autocomplete="off"  required data-validation-required-message="This field is required"> 
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Roll No : </label>
                                        <div class="controls">
                                            <input type="text" name="text" class="form-control rollno" autocomplete="off" required data-validation-required-message="This field is required"> 
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Phone No : </label>
                                        <div class="controls">
                                            <input type="text" name="text" class="form-control phoneno" autocomplete="off" required data-validation-required-message="This field is required"> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-center">
                                <button type="button" class="btn btn-info waves-effect" id="getRegno">Submit</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
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
        <footer class="footer" style="margin-left:0px;">
            © <?php echo date ('Y') ?> EXPERT
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
    <script src="assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="assets/node_modules/popper/popper.min.js"></script>
    <script src="assets/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="dist/js/perfect-scrollbar.jquery.min.js"></script>
    <!--Wave Effects -->
    <script src="dist/js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="dist/js/sidebarmenu.js"></script>
    <!--stickey kit -->
    <script src="assets/node_modules/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="assets/node_modules/sparkline/jquery.sparkline.min.js"></script>
    <!--Custom JavaScript -->
    <!-- Sweet-Alert  -->
    <script src="assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
   <!-- <script src="dist/js/accAdm.js?_=<?php /*echo time();*/?>"></script>-->
    <script src="assets/node_modules/jsgrid/db.js"></script>
    <script type="text/javascript" src="assets/node_modules/jsgrid/jsgrid.min.js"></script>
    <script src="dist/js/custom.min.js"></script>
    <script src="assets/node_modules/sparkline/jquery.sparkline.min.js"></script>
    <script src="assets/node_modules/gauge/gauge.min.js"></script>
    <script src="dist/js/pages/widget-data-due-fees.js?_=<?php echo time();?>"></script>
    <script src="assets/node_modules/toast-master/js/jquery.toast.js"></script>
    <script type="module" src="dist/js/common/index.js?_=<?php echo time();?>"></script>
    <script type="module" id="downloadCert" src="dist/js/downloadCertificate.js?_=<?php echo time();?>" data-studReg="<?php echo $stdRegno;?>"></script>
</body>
</html>