<?php
session_start();
if (!$_SESSION['id']) {
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
    <title>Due Fees Dashboard</title>
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
<style type="text/css">.jqstooltip { position: absolute;left: 0px;top: 0px;visibility: hidden;background: rgb(0, 0, 0) transparent;background-color: rgba(0,0,0,0.6);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000);-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000)";color: white;font: 10px arial, san serif;text-align: left;white-space: nowrap;padding: 5px;border: 1px solid white;z-index: 10000;}.jqsfield { color: white;font: 10px arial, san serif;text-align: left;}
.followups .message-center {
  height: 317px;
  overflow: auto;
  position: relative; }
  .followups .message-center a {
    border-bottom: 1px solid #e9ecef;
    display: block;
    text-decoration: none;
    padding: 9px 15px; }
    .followups .message-center a:hover {
      background: #e9ecef; }
    .followups .message-center a div {
      white-space: normal; }
    .followups .message-center a .user-img {
      width: 40px;
      position: relative;
      display: inline-block;
      margin: 0 10px 15px 0; }
      .followups .message-center a .user-img img {
        width: 100%; }
      .followups .message-center a .user-img .profile-status {
        border: 2px solid #fff;
        border-radius: 50%;
        display: inline-block;
        height: 10px;
        left: 30px;
        position: absolute;
        top: 1px;
        width: 10px; }
      .followups .message-center a .user-img .online {
        background: #00c292; }
      .followups .message-center a .user-img .busy {
        background: #e46a76; }
      .followups .message-center a .user-img .away {
        background: #fec107; }
      .followups .message-center a .user-img .offline {
        background: #fec107; }
    .followups .message-center a .mail-contnet {
      display: inline-block;
      width: 100%;
      padding-left: 10px;
      vertical-align: middle; }
      .followups .message-center a .mail-contnet h5 {
        margin: 5px 0px 0; }
      .followups .message-center a .mail-contnet .mail-desc,
      .followups .message-center a .mail-contnet .time {
        font-size: 12px;
        display: block;
        margin: 1px 0;
        text-overflow: ellipsis;
        overflow: hidden;
        color: #212529;
        white-space: nowrap; }
</style>
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
                <div class="navbar-header">
                    <a class="navbar-brand" href="index.html">
                        <!-- Logo icon --><b>
                            <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                            <!-- Dark Logo icon -->
                            <img src="assets/images/logo-icon.png" alt="homepage" class="dark-logo" />
                            <!-- Light Logo icon -->
                            <img src="assets/images/logo-light-icon.png" alt="homepage" class="light-logo" />
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
                            <div class="dropdown-menu dropdown-menu-right mailbox animated bounceInDown">
                                <ul>
                                    <li>
                                        <div class="drop-title">Notifications</div>
                                    </li>
                                    <li>
                                        <div class="message-center">

                                        </div>
                                    </li>
                                    <li>
                                        <a class="nav-link text-center link" href="javascript:void(0);"> <strong>Check all notifications</strong> <i class="fa fa-angle-right"></i> </a>
                                    </li>
                                </ul>
                            </div>
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
                            <div class="dropdown-menu mailbox dropdown-menu-right animated bounceInDown" aria-labelledby="2">
                                <ul>
                                    <li>
                                        <div class="drop-title">You have 4 new messages</div>
                                    </li>
                                    <li>
                                        <div class="message-center">

                                        </div>
                                    </li>
                                    <li>
                                        <a class="nav-link text-center link" href="javascript:void(0);"> <strong>See all e-Mails</strong> <i class="fa fa-angle-right"></i> </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
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
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- User Profile-->
                <div class="user-profile">
                    <div class="user-pro-body">
                        <div><img src="assets/images/users/4.jpg" alt="user-img" class="img-circle"></div>
                        <div class="dropdown">
                            <a href="javascript:void(0)" class="dropdown-toggle u-dropdown link hide-menu login-user" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Steave Gection <span class="caret"></span></a>
                            <div class="dropdown-menu animated flipInY">
                                <!-- text-->
                                <a href="https://www.advanceinstitute.co.in/account/studentRegistration.php" class="dropdown-item"><i class="ti-user"></i> Admission Form </a>
                                <!-- text-->
     <a href="https://www.advanceinstitute.co.in/account/feepayment.php" class="dropdown-item"><i class="ti-wallet"></i> Payment Receipt</a>
                                <!-- text-->
                                <a href="https://www.advanceinstitute.co.in/account/index.php" class="dropdown-item"><i class="ti-email"></i> Old CRM</a>
                                <!-- text-->
                                <div class="dropdown-divider"></div>
                                <!-- text-->
                                <a href="https://www.advanceinstitute.co.in/account/feeHistory.php" class="dropdown-item"><i class="ti-settings"></i> fee History</a>
                                <!-- text-->
                                <div class="dropdown-divider"></div>
                                <!-- text-->
                                <a href="https://www.advanceinstitute.co.in/index.php?id=logout" class="dropdown-item"><i class="fas fa-power-off"></i> Logout</a>
                                <!-- text-->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li class="nav-small-cap">--- MAIN MENU</li>
						<li>
                            <a class="waves-effect waves-dark" href="index.html" aria-expanded="false">
                                <i class="icon-speedometer"></i>
                                <span class="hide-menu">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-small-cap">--- SUPPORTS</li>
                        <li>
                            <a class="waves-effect waves-dark" href="pages-login.html" aria-expanded="false">
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
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
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
                <div class="row page-titles">
                    <div class="col-md-5 align-self-center">
                        <h4 class="text-themecolor">Due Fees</h4>
                    </div>
                    <div class="col-md-7 align-self-center text-right">
                        <div class="d-flex justify-content-end align-items-center">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                                <li class="breadcrumb-item active">Due Fees</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <div class="row">
                    <!-- Column -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <!-- Row -->
                                <div class="row">
                                    <div class="col-8"><span class="display-6">
                                            <span class="todayPendingCount">0</span>
                                            <i class="ti-angle-down font-14 text-danger"></i></span>
                                        <h6>Today Pending</h6>
                                    </div>
                                    <div class="col-4 align-self-center text-right  p-l-0">
                                        <div id="sparklinedash3"><canvas width="51" height="50" style="display: inline-block; width: 51px; height: 50px; vertical-align: top;"></canvas></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <!-- Row -->
                                <div class="row">
                                    <div class="col-8"><span class="display-6">
                                            <span class="allPendingCount">0</span>
                                            <i class="ti-angle-up font-14 text-success"></i></span>
                                        <h6>All Pending</h6>
                                    </div>
                                    <div class="col-4 align-self-center text-right p-l-0">
                                        <div id="sparklinedash2"><canvas width="51" height="50" style="display: inline-block; width: 51px; height: 50px; vertical-align: top;"></canvas></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <!-- Row -->
                                <div class="row">
                                    <div class="col-8"><span class="display-6">
                                            <span class="todayDoneCount">0</span>
                                            <i class="ti-angle-up font-14 text-success"></i></span>
                                        <h6>Today Done</h6>
                                    </div>
                                    <div class="col-4 align-self-center text-right p-l-0">
                                        <div id="sparklinedash"><canvas width="51" height="50" style="display: inline-block; width: 51px; height: 50px; vertical-align: top;"></canvas></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <!-- Row -->
                                <div class="row">
                                    <div class="col-8"><span class="display-6">
                                            <span class="allBookingCount">0</span>
                                            <i class="ti-angle-down font-14 text-danger"></i></span>
                                        <h6>All Booking</h6>
                                    </div>
                                    <div class="col-4 align-self-center text-right p-l-0">
                                        <div id="sparklinedash4"><canvas width="51" height="50" style="display: inline-block; width: 51px; height: 50px; vertical-align: top;"></canvas></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body p-b-0"></div>
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item"> <a class="nav-link active todayPendingNav" data-toggle="tab" href="#todayPending" role="tab"><span class="hidden-sm-up"><i class="icon-user-follow"></i></span> <span class="hidden-xs-down">Today Pending</span></a> </li>
                                <li class="nav-item"> <a class="nav-link allPendingNav" data-toggle="tab" href="#allPending" role="tab"><span class="hidden-sm-up"><i class="icon-people"></i></span> <span class="hidden-xs-down">All Pending</span></a> </li>
                                <li class="nav-item"> <a class="nav-link todayDoneNav" data-toggle="tab" href="#todayDone" role="tab"><span class="hidden-sm-up"><i class="icon-user-following"></i></span> <span class="hidden-xs-down">Today Done</span></a> </li>
                                <li class="nav-item"> <a class="nav-link allBookingNav" data-toggle="tab" href="#allBooking" role="tab"><span class="hidden-sm-up"><i class="icon-user"></i></span> <span class="hidden-xs-down">All Booking</span></a> </li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content tabcontent-border">
                                <div class="tab-pane active" id="todayPending" role="tabpanel">
                                    <div id="" class="todayPendingGrid"></div>
                                </div>
                                <div class="tab-pane" id="allPending" role="tabpanel">
                                    <div id="" class="allPendingGrid"></div>
                                </div>
                                <div class="tab-pane" id="todayDone" role="tabpanel">
                                    <div id="" class="todayDoneGrid"></div>
                                </div>
                                <div class="tab-pane" id="allBooking" role="tabpanel">
                                    <div id="" class="allBookingGrid"></div>
                                </div>
                            </div>
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
                <div class="row">
                    <div class="col-md-12">
                         <div id="grid-modal" class="modal" tabindex="-1" role="dialog" aria-labelledby="grid-title" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title adm-name" id="grid-title">Name</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                            </div>
                                            <div class="modal-header">
                                                <div class="col-md">	
                                                    <strong>Course</strong>
                                                    <br>
                                                    <p class="text-muted adm-dtl adm-course"></p>
                                                </div>
                                                <div class="col-md">	
                                                    <strong>Reg Date</strong>
                                                    <br>
                                                    <p class="text-muted adm-dtl adm-reg-date"></p>
                                                </div>
                                                <div class="col-md">	
                                                    <strong>Due Date</strong>
                                                    <br>
                                                    <p class="text-muted adm-dtl adm-due-date"></p>
                                                </div>
                                            </div>
                                            <div class="modal-header">
                                                <div class="col-md-3 col-xs-6 b-r"> 
												<strong>Phone</strong>
                                                <br>
                                                <p class="text-muted adm-dtl adm-phone">0000000000</p>
                                            </div>
                                            <div class="col-md-3 col-xs-6 b-r"> 
												<strong>Total Fees</strong>
                                                <br>
                                                <p class="text-muted adm-dtl adm-total-fees">0000</p>
                                            </div>
                                            <div class="col-md-3 col-xs-6 b-r"> 
												<strong>Credit Fees</strong>
                                                <br>
                                                <p class="text-muted adm-dtl adm-credit-fees">0000</p>
                                            </div>
                                            <div class="col-md-3 col-xs-6"> 
												<strong>Due Fees</strong>
                                                <br>
                                                <p class="text-muted adm-dtl adm-due-fees">0000</p>
                                            </div>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-12 display-errors">
                                                    </div>
                                                    <div class="col-md-12 display-success">
                                                    </div>
                                                </div>
                                                <ul class="nav nav-tabs" role="tablist">
                                                    <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#messages" role="tab"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">Follow Up</span></a> </li>
                                                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#receipt" role="tab"><span class="hidden-sm-up"><i class="ti-receipt"></i></span> <span class="hidden-xs-down">Fees Receipt</span></a> </li>
                                                </ul>
                                                <!-- Tab panes -->
                                                <div class="tab-content tabcontent-border">
                                                    <div class="tab-pane active" id="messages" role="tabpanel">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="card">
                                                                    <div class="card-body form-followup">
                                                                        <div class="form-group" id="rowRemark">
                                                                            <h5> <i class="mdi mdi-border-color" id="addMessage" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add Message"></i> Remark <span class="text-danger">*</span></h5>
                                                                            <div class="controls">
                                                                                <select name="select" id="select" required class="form-control followup-remarks">
                                                                                    <option value="">Select Remarks</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group" id="rowMessage" style="display:none;">
                                                                            <h5> <i class="mdi mdi-chevron-double-down" id="addRemark" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add Remark"></i> Message <span class="text-danger">*</span></h5>
                                                                            <div class="controls">
                                                                                <input type="text" name="text" class="form-control followup-message" required data-validation-required-message="This field is required"> </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <h5>Status <span class="text-danger">*</span></h5>
                                                                            <div class="controls">
                                                                                <select name="select" id="select-status" required class="form-control followup-status">
                                                                                    <option value="">Select Status</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <h5>Next Followup Date <span class="text-danger">*</span></h5>
                                                                            <div class="controls">
                                                                                <input type="date" name="text" class="form-control followup-date" required data-validation-required-message="This field is required"> </div>
                                                                        </div>
                                                                    </div>
                                                                </div>    
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="card">
                                                                    <div class="card-body followups">
                                                                        <h4 class="card-title">Last Messages</h4>
                                                                        <div class="message-center last-followups">
                                                                           <div class="spinner-grow" role="status">
                                                                              <span class="sr-only">Loading...</span>
                                                                            </div>
                                                                            <div class="spinner-grow text-primary" role="status">
                                                                              <span class="sr-only">Loading...</span>
                                                                            </div>
                                                                            <div class="spinner-grow text-secondary" role="status">
                                                                              <span class="sr-only">Loading...</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane  p-20" id="receipt" role="tabpanel">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="table-responsive">
                                                                    <table class="table full-color-table full-danger-table hover-table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Receipt No</th>
                                                                                <th>Date</th>
                                                                                <th>Amount</th>
                                                                                <th>Mode</th>
                                                                                <th>Cheque No</th>
                                                                                <th>User</th>
                                                                                <th></th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody id="feesRecieptTb">

                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-success waves-effect" id="saveFollowup">Save</button>
                                                <button type="button" class="btn btn-info waves-effect" id="closeFollowupPopup" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                         <div id="grid-modal-followups" class="modal" tabindex="-1" role="dialog" aria-labelledby="grid-title" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="grid-title">Follow Ups</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                            </div>
                                            <div class="modal-body">
                                                
                                                                    <div class="card-body followups">
                                                                        <div class="message-center all-followups">
                                                                            <div class="spinner-grow" role="status">
                                                                              <span class="sr-only">Loading...</span>
                                                                            </div>
                                                                            <div class="spinner-grow text-primary" role="status">
                                                                              <span class="sr-only">Loading...</span>
                                                                            </div>
                                                                            <div class="spinner-grow text-secondary" role="status">
                                                                              <span class="sr-only">Loading...</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-info waves-effect" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                    </div>
                </div>
                <div class="right-sidebar">
                    <div class="slimscrollright">
                        <div class="rpanel-title"> Service Panel <span><i class="ti-close right-side-toggle"></i></span> </div>
                        <div class="r-panel-body">
                            <ul id="themecolors" class="m-t-20">
                                <li><b>With Light sidebar</b></li>
                                <li><a href="javascript:void(0)" data-skin="skin-default" class="default-theme">1</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-green" class="green-theme">2</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-red" class="red-theme">3</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-blue" class="blue-theme">4</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-purple" class="purple-theme">5</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-megna" class="megna-theme">6</a></li>
                                <li class="d-block m-t-30"><b>With Dark sidebar</b></li>
                                <li><a href="javascript:void(0)" data-skin="skin-default-dark" class="default-dark-theme working">7</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-green-dark" class="green-dark-theme">8</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-red-dark" class="red-dark-theme">9</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-blue-dark" class="blue-dark-theme">10</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-purple-dark" class="purple-dark-theme">11</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-megna-dark" class="megna-dark-theme ">12</a></li>
                            </ul>
                            <ul class="m-t-20 chatonline">
                                <li><b>Chat option</b></li>
                                <li>
                                    <a href="javascript:void(0)"><img src="assets/images/users/1.jpg" alt="user-img" class="img-circle"> <span>Varun Dhavan <small class="text-success">online</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="assets/images/users/2.jpg" alt="user-img" class="img-circle"> <span>Genelia Deshmukh <small class="text-warning">Away</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="assets/images/users/3.jpg" alt="user-img" class="img-circle"> <span>Ritesh Deshmukh <small class="text-danger">Busy</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="assets/images/users/4.jpg" alt="user-img" class="img-circle"> <span>Arijit Sinh <small class="text-muted">Offline</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="assets/images/users/5.jpg" alt="user-img" class="img-circle"> <span>Govinda Star <small class="text-success">online</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="assets/images/users/6.jpg" alt="user-img" class="img-circle"> <span>John Abraham<small class="text-success">online</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="assets/images/users/7.jpg" alt="user-img" class="img-circle"> <span>Hritik Roshan<small class="text-success">online</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="assets/images/users/8.jpg" alt="user-img" class="img-circle"> <span>Pwandeep rajan <small class="text-success">online</small></span></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
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
   <!-- <script src="dist/js/accAdm.js?_=<?php /*echo time();*/?>"></script>-->

    <script src="assets/node_modules/jsgrid/db.js"></script>
    <script type="text/javascript" src="assets/node_modules/jsgrid/jsgrid.min.js"></script>
    <script src="dist/js/custom.min.js"></script>
    <script src="assets/node_modules/sparkline/jquery.sparkline.min.js"></script>
    <script src="assets/node_modules/gauge/gauge.min.js"></script>
    <script src="dist/js/pages/widget-data-due-fees.js?_=<?php echo time();?>"></script>
    <script src="assets/node_modules/toast-master/js/jquery.toast.js"></script>
    <script src="dist/js/accNavDetails.js?_=<?php echo time();?>"></script>
    <script src="dist/js/common.js?_=<?php echo time();?>"></script>
    <script id="accDueFeesTag" src="dist/js/accDueFees.js?_=<?php echo time();?>" data-acc='<?php echo json_encode($_SESSION['user_permission']); ?>'></script>
</body>

</html>