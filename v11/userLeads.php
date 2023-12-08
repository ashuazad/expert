<?php
session_start();
if (!$_SESSION['id']) {
    header('Location: https://www.advanceinstitute.co.in');
    exit;
}
//print_r($_SESSION);
//exit();
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
    <title>Lead Experience Dashboard</title>
    <!-- chartist CSS -->
    <link href="assets/node_modules/chartist-js/dist/chartist.min.css" rel="stylesheet">
    <link href="assets/node_modules/chartist-js/dist/chartist-init.css" rel="stylesheet">
    <link href="assets/node_modules/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.css" rel="stylesheet">
    <link href="assets/node_modules/css-chart/css-chart.css" rel="stylesheet">
    <!-- Editable CSS -->
    <link type="text/css" rel="stylesheet" href="assets/node_modules/jsgrid/jsgrid.min.css" />
    <link type="text/css" rel="stylesheet" href="assets/node_modules/jsgrid/jsgrid-theme.min.css" />
    <!-- multi select css -->
    <link href="assets/node_modules/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/node_modules/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />
    <!-- Custom CSS -->
    <link href="dist/css/style.css" rel="stylesheet">
    <link href="dist/css/custom.css" rel="stylesheet">
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
    .col-half-offset{
        margin-left:4.166666667%
    }
    .followups-action-button{
        margin-left: 1rem;
    }
    .lead-edit .controls{
        display: none;
    }
    .lead-save-button {
        display: none;
    }
    .gird-cell-text-alignment
    {
        text-align: center;
        padding: 1em;
    }
    .dashboard-count-box-bg {
        background-color: grey;
    }
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
                                <a href="https://www.advanceinstitute.co.in/account/feeHistory.php" class="dropdown-item"><i class="ti-email"></i> fee History</a>
                                <!-- text-->
                                <!-- text-->
                                <a href="https://www.advanceinstitute.co.in/v11/userIncentive.php" class="dropdown-item"><i class="ti-wallet"></i> Incentive </a>
                                <!-- text-->
                                <div class="dropdown-divider"></div>
                                <!-- text-->
                                <a href="https://www.advanceinstitute.co.in/account/index.php" class="dropdown-item"><i class="ti-settings"></i> Old CRM</a>
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
                        <li class="nav-small-cap main-nav">--- MAIN MENU</li>
						<li>
                            <a class="waves-effect waves-dark" href="index.html" aria-expanded="false">
                                <i class="icon-speedometer"></i>
                                <span class="hide-menu">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-small-cap main-support-nav">--- SUPPORTS</li>
                        <li>
                            <a class="waves-effect waves-dark" href="#" aria-expanded="false">
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
                        <h4 class="text-themecolor">Dashboard</h4>
                    </div>
                    <div class="col-md-7 align-self-center text-right">
                        <div class="d-flex justify-content-end align-items-center">
     <button type="button" class="btn btn-info d-none d-lg-block m-l-15 btn-createLead" data-toggle="modal" data-target="#grid-modal-newLead">
         <i class="fa fa-plus-circle"></i> Add New Lead
     </button>
                            <span>All Lead Status</span>
                            <span style="margin-left: 1rem;" class="allStatusCount">0</span>
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
                    <div class="col-md-12"> <h4><span class="badge badge-danger">Leads Status</span></h4></div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body dashboard-count-box-bg">
                                <!-- Row -->
                                <div class="row">
                                    <div class="col-8"><span class="display-6">
                                            <span class="todayPendingCount">0</span>
                                            <i class="ti-angle-up font-14 text-success"></i></span>
                                        <h5><span class="badge badge-danger">Today Pending Lead</span></h5>
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
                            <div class="card-body dashboard-count-box-bg">
                                <!-- Row -->
                                <div class="row">
                                    <div class="col-8"><span class="display-6">
                                            <span class="allPendingCount">0</span>
                                            <i class="ti-angle-up font-14 text-success"></i></span>
                                        <h5><span class="badge badge-warning">All Pending Lead</span></h5>
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
                            <div class="card-body dashboard-count-box-bg">
                                <!-- Row -->
                                <div class="row">
                                    <div class="col-8"><span class="display-6">
                                            <span class="todayNewCount">0</span>
                                            <i class="ti-angle-up font-14 text-success"></i></span>
                                        <h5><span class="badge badge-info">Today New Lead</span></h5>
                                    </div>
                                    <div class="col-4 align-self-center text-right p-l-0">
                                        <div id="sparklinedash3"><canvas width="51" height="50" style="display: inline-block; width: 51px; height: 50px; vertical-align: top;"></canvas></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body dashboard-count-box-bg">
                                <!-- Row -->
                                <div class="row">
                                    <div class="col-8">
                                        <span class="display-6">
                                            <span class="todayDoneCount">0</span>
                                            <i class="ti-angle-up font-14 text-success"></i>
                                        </span>
                                        <h5><span class="badge badge-success">Today Done Lead</span></h5>
                                    </div>
                                    <div class="col-4 align-self-center text-right p-l-0">
                                        <div id="sparklinedash4"><canvas width="51" height="50" style="display: inline-block; width: 51px; height: 50px; vertical-align: top;"></canvas></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                </div>
                <div class="row">
                    <div class="col-md-12"> <h4><span class="badge badge-success">Due Fees Status</span></h4></div>
                </div>
                <div class="row">
                    <!-- Column -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body dashboard-count-box-bg">
                                <!-- Row -->
                                <div class="row">
                                    <div class="col-12">
                                        <h3 class="todayPendingCountLead">0</h3>
                                        <h5><span class="badge badge-danger">Today Pending Fees</span></h5>
                                    </div>
                                    <div class="col-12">
                                        <div class="progress">
                                            <div class="progress-bar bg-danger" role="progressbar" style="width: 49%;height: 6px;" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body dashboard-count-box-bg">
                                <!-- Row -->
                                <div class="row">
                                    <div class="col-12">
                                        <h3 class="allPendingCountLead">0</h3>
                                        <h5><span class="badge badge-warning">All Pending Fees</span></h5>
                                    </div>
                                    <div class="col-12">
                                        <div class="progress">
                                            <div class="progress-bar bg-warning" role="progressbar" style="width: 49%;height: 6px;" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body dashboard-count-box-bg">
                                <!-- Row -->
                                <div class="row">
                                    <div class="col-12">
                                        <h3 class="todayDoneCountLead">0</h3>
                                        <h5><span class="badge badge-info">Today Done</span></h5>
                                    </div>
                                    <div class="col-12">
                                        <div class="progress">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: 49%;height: 6px;" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body dashboard-count-box-bg">
                                <!-- Row -->
                                <div class="row">
                                    <div class="col-12">
                                        <h3 class="allBookingCountLead">0</h3>
                                        <h5><span class="badge badge-success">All Booking</span></h5>
                                    </div>
                                    <div class="col-12">
                                        <div class="progress">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 49%;height: 6px;" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row leads-dashboard-income">
                    <div class="col-lg-4 col-md-6">
                        <div class="card">
                            <div class="d-flex flex-row">
                                <div class="p-10 bg-info">
                                    <h3 class="text-white box m-b-0"><i class="ti-user"></i></h3></div>
                                <div class="align-self-center m-l-20">
                                    <span class="font-20 box-count-margin pending-incentive"></span> <span class="font-20 text-muted m-b-0">  INR Incentive Pending</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="card">
                            <div class="d-flex flex-row">
                                <div class="p-10 bg-success">
                                    <h3 class="text-white box m-b-0"><i class="ti-wallet"></i></h3></div>
                                <div class="align-self-center m-l-20">
                                    <span class="font-20 box-count-margin approve-incentive"></span> <span class="font-20 text-muted m-b-0"> INR Incentive Approved</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="card">
                            <div class="d-flex flex-row">
                                <div class="p-10 bg-primary">
                                    <h3 class="text-white box m-b-0"><i class="ti-wallet"></i></h3></div>
                                <div class="align-self-center m-l-20">
                                    <span class="font-20 box-count-margin total-income"></span> <span class="font-20 text-muted m-b-0">INR Total Monthly income</span>
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
                                <li class="nav-item"> <a class="nav-link todayNewNav" data-toggle="tab" href="#todayNew" role="tab"><span class="hidden-sm-up"><i class="icon-user-following"></i></span> <span class="hidden-xs-down">Today New</span></a> </li>
                                <li class="nav-item"> <a class="nav-link todayDoneNav" data-toggle="tab" href="#todayDone" role="tab"><span class="hidden-sm-up"><i class="icon-user-following"></i></span> <span class="hidden-xs-down">Today Done</span></a> </li>
                                <li class="nav-item"> <a class="nav-link allStatusNav" data-toggle="tab" href="#allStatus" role="tab"><span class="hidden-sm-up"><i class="icon-user"></i></span> <span class="hidden-xs-down">All Status</span></a> </li>
                                <li class="nav-item"> <a class="nav-link allPendingNavDueFee" data-toggle="tab" href="#allPendingDueFee" role="tab"><span class="hidden-sm-up"><i class="icon-people"></i></span> <span class="hidden-xs-down">All Due Fees</span></a> </li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content tabcontent-border">
                                <div class="tab-pane active" id="todayPending" role="tabpanel">
                                    <div id="" class="todayPendingGrid"></div>
                                </div>
                                <div class="tab-pane" id="allPending" role="tabpanel">
                                    <div id="" class="allPendingGrid"></div>
                                </div>
                                <div class="tab-pane" id="todayNew" role="tabpanel">
                                    <div id="" class="todayNewGrid"></div>
                                </div>
                                <div class="tab-pane" id="todayDone" role="tabpanel">
                                    <div id="" class="todayDoneGrid"></div>
                                </div>
                                <div class="tab-pane" id="allStatus" role="tabpanel">
                                    <div id="" class="allStatusGrid"></div>
                                </div>
                                <div class="tab-pane" id="allPendingDueFee" role="tabpanel">
                                    <div id="" class="allPendingDueFeeGrid"></div>
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
                                            <div class="modal-header lead-edit">
                                                <h4 class="modal-title lead-name lead-edit-row m-t-5" id="grid-title">Name</h4>
                                                <div class="controls">
                                                    <input type="text" class="form-control lead-name-field" name="name">
                                                </div>
                                                <button type="button" class="btn btn-success call-now-btn m-l-5" data-phone="" href="javascript:void(0);">Call Now <i class="fas fa-phone m-l-5"></i></button>
                                                <button type="button" class="btn btn-dark quotation-button" data-toggle="tab" href="#quotation" role="tab">Send PDF</button>
                                                <button type="button" class="close" style="margin-left:2rem" data-dismiss="modal" aria-hidden="true">×</button>
                                            </div>
                                            <div class="modal-header">
                                                <div class="col-md">
                                                    <strong>Phone</strong>
                                                    <br>
                                                    <p class="text-muted adm-dtl lead-phone"></p>
                                                </div>
                                                <div class="col-md">	
                                                    <strong>Ip Address</strong>
                                                    <br>
                                                    <p class="text-muted adm-dtl lead-Ip"></p>
                                                </div>
                                                <div class="col-md">
                                                    <strong>Ip Location City</strong>
                                                    <br>
                                                    <p class="text-muted adm-dtl lead-Ip-City"></p>
                                                </div><div class="col-md">
                                                    <strong>Ip Location Country</strong>
                                                    <br>
                                                    <p class="text-muted adm-dtl lead-Ip-Country"></p>
                                                </div>
                                            </div>
                                            <div class="modal-header lead-edit">
                                                <div class="col-md-4 col-xs-6 b-r">
                                                    <strong>Course</strong>
                                                    <br>
                                                    <p class="text-muted adm-dtl lead-edit-row lead-course"></p>
                                                    <div class="controls">
                                                        <input type="text" class="form-control" name="course">
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-xs-6 b-r">
                                                    <strong>Email Id</strong>
                                                    <br>
                                                    <p class="text-muted adm-dtl lead-edit-row lead-emailId"></p>
                                                    <div class="controls">
                                                        <input type="text" class="form-control" name="emailId">
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-xs-6 b-r">
                                                    <strong>Other Details</strong>
                                                    <br>
                                                    <p class="text-muted adm-dtl lead-edit-row lead-address"></p>
                                                    <div class="controls">
                                                        <input type="text" class="form-control" name="address">
                                                    </div>
                                                </div>
                                                <button type="button" class="close followups-action-button lead-edit-button"><i class="far fa-edit"></i></button>
                                                <button type="button" class="close followups-action-button lead-save-button"><i class="fas fa-check"></i></button>
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
                                                    <li class="nav-item"> <a class="nav-link quotation-tab" data-toggle="tab" href="#quotation" role="tab"><span class="hidden-sm-up"><i class="ti-notepad"></i></span> <span class="hidden-xs-down">Special Offer Send</span></a> </li>
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
                                                                                <input type="date" name="text" class="form-control followup-date custom-date" required data-validation-required-message="This field is required"> </div>
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
                                                    <div class="tab-pane" id="quotation" role="tabpanel">
                                                        <div class="card-body">
                                                            <div class="quotation-form">
                                                                <form method="POST" id="quotation-pdf" action="quotationPdf.php" target="_blank">
                                                                    <strong>Courses</strong>
                                                                    <br>     
                                                                    <div class="controls">
                                                                        <select name="courses[]" class="form-control select2 m-b-10 select2-multiple courses-quotation" multiple="multiple" data-placeholder="Choose" required style="width: 100%">
                                                                            <option value="">-Select-</option>
                                                                        </select>
                                                                    </div>
                                                                    <strong>Course Fees</strong>
                                                                    <br>     
                                                                    <div class="controls">
                                                                        <input type="text" class="form-control" id="course_fee" name="course_fee" readonly  value="" Placeholder="Course Packege"/>
                                                                    </div>
                                                                    <strong>Total Fees</strong>
                                                                    <br>     
                                                                    <div class="controls">
                                                                        <input type="text" class="form-control" id="total_fee" name="total_fee" value="" class='inpTxt' Placeholder="Total Fee" readonly  required/>
                                                                    </div>
                                                                    <strong>Discount</strong>
                                                                    <br>     
                                                                    <div class="controls">
                                                                        <input type='text' class="form-control" id="disAmt" name="disAmt" Placeholder="Discount" <?php echo (($_SESSION['user_permission']['emp_set_discount']==1) || ($_SESSION['USER_TYPE'] == 'SUPERADMIN')) ? 'on':'readonly'?>> 
                                                                    </div>
                                                                    <br>
                                                                    <input type="hidden" id="quotation_lead_id" name="lead_id" value="">
                                                                    <input type="hidden" id="quotation_lead_name" name="lead_name" value="">
                                                                    <input type="hidden" id="quotation_lead_phone" name="lead_phone" value="">
                                                                    <input type="hidden" id="quotation_isEdited" name="quotation_isEdited" value="0">
                                                                    <div class="text-center">
                                                                        <button class="btn btn-info" type="submit">Generate PDF</button>
                                                                    </div>
                                                                </form>
                                                            </div>    
                                                                <br>
                                                                <div class="quotation-history">
                                                                    <table class="table full-color-table full-danger-table hover-table">
                                                                        <thead>
                                                                        <tr><th>Date</th><th>Courses</th><th>Price</th><th>Discount</th><th>Offer Price</th><th>User</th><th>Status</th><th></th></tr>
                                                                        </thead>
                                                                        <tbody id="currentQuotationTable">
                                                                            <tr><td colspan="6">NONE</td></tr>
                                                                        </tbody>
                                                                    </table> 
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
                <div class="row">
                    <div class="col-md-12">
                        <div id="grid-modal-newLead" class="modal" tabindex="-1" role="dialog" aria-labelledby="grid-title" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="grid-title">Add New Lead</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    </div>
                                    <form id="createLeadForm" method="post" class="m-t-40" novalidate>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <h5>Name <span class="text-danger">*</span></h5>
                                            <div class="controls">
                                                <input type="text" name="name" class="form-control lead-name" required data-validation-required-message="This field is required">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <h5>Email <span class="text-danger">*</span></h5>
                                            <div class="controls">
                                                <input type="email" name="email" class="form-control lead-email" required data-validation-required-message="This field is required">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <h5>Phone No<span class="text-danger">*</span></h5>
                                            <div class="controls">
                                                <input type="phone" name="phone" class="form-control lead-phone" required data-validation-required-message="This field is required">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <h5>Course <span class="text-danger">*</span></h5>
                                            <div class="controls">
                                                <select name="category" id="select-course" required class="form-control lead-courses">
                                                    <option value="">Select Course</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success waves-effect" id="saveLead">Save</button>
                                        <button type="button" class="btn btn-info waves-effect" data-dismiss="modal">Close</button>
                                    </div>
                                    </form>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>
                    </div>
                </div>
                <!--Due Fees-->
                <div class="row">
                    <div class="col-md-12">
                        <div id="grid-modal-due-fee" class="modal" tabindex="-1" role="dialog" aria-labelledby="grid-title" aria-hidden="true">
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
                                            <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#messages-adm" role="tab"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">Follow Up</span></a> </li>
                                            <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#receipt" role="tab"><span class="hidden-sm-up"><i class="ti-receipt"></i></span> <span class="hidden-xs-down">Fees Receipt</span></a> </li>
                                        </ul>
                                        <!-- Tab panes -->
                                        <div class="tab-content tabcontent-border">
                                            <div class="tab-pane active" id="messages-adm" role="tabpanel">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="card">
                                                            <div class="card-body form-followup">
                                                                <div class="form-group" id="rowRemarkDueFee">
                                                                    <h5> <i class="mdi mdi-border-color" id="addMessageDueFees" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add Message"></i> Remark <span class="text-danger">*</span></h5>
                                                                    <div class="controls">
                                                                        <select name="select" id="select" autocomplete="off" required class="form-control due-fees-followup-remarks">
                                                                            <option value="">Select Remarks</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group" id="rowMessageDueFee" style="display:none;">
                                                                    <h5> <i class="mdi mdi-chevron-double-down" id="addRemarkDueFees" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add Remark"></i> Message <span class="text-danger">*</span></h5>
                                                                    <div class="controls">
                                                                        <input type="text" name="text" class="form-control due-fees-followup-message" required data-validation-required-message="This field is required"> </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <h5>Status <span class="text-danger">*</span></h5>
                                                                    <div class="controls">
                                                                        <select name="select" id="select-status" required class="form-control due-fees-followup-status">
                                                                            <option value="">Select Status</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <h5>Next Followup Date <span class="text-danger">*</span></h5>
                                                                    <div class="controls">
                                                                        <input type="date" name="text" class="form-control due-fees-followup-date custom-date" required data-validation-required-message="This field is required"> </div>
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
                                        <button type="button" class="btn btn-success waves-effect" id="saveAdmDueFollowup">Save</button>
                                        <button type="button" class="btn btn-info waves-effect" id="closeAdmDueFollowupPopup" data-dismiss="modal">Close</button>
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
                        <div id="grid-modal-due-fee-followups" class="modal" tabindex="-1" role="dialog" aria-labelledby="grid-title" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="grid-title">Follow Ups</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    </div>
                                    <div class="modal-body">

                                        <div class="card-body followups">
                                            <div class="message-center due-fee-all-followups">
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
                <!--Due Fees-->
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
    <!-- Sweet-Alert  -->
    <script src="assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <!--Custom JavaScript -->
   <!-- <script src="dist/js/accAdm.js?_=<?php /*echo time();*/?>"></script>-->
    <script src="assets/node_modules/jsgrid/db.js"></script>
    <script type="text/javascript" src="assets/node_modules/jsgrid/jsgrid.min.js"></script>
    <script src="dist/js/custom.min.js"></script>
    <script src="assets/node_modules/sparkline/jquery.sparkline.min.js"></script>
    <script src="assets/node_modules/gauge/gauge.min.js"></script>
    <script src="dist/js/pages/widget-data-due-fees.js?_=<?php echo time();?>"></script>
    <script src="assets/node_modules/toast-master/js/jquery.toast.js"></script>
    <script src="assets/node_modules/select2/dist/js/select2.full.min.js" type="text/javascript"></script>
    <script src="dist/js/accNavDetails.js?_=<?php echo time();?>"></script>
    <script src="dist/js/common.js?_=<?php echo time();?>"></script>
    <script src="dist/js/pages/validation.js"></script>
    <script>
        ! function(window, document, $) {
            "use strict";
            $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
        }(window, document, jQuery);
    </script>
    <script src="dist/js/accLeads.js?_=<?php echo time();?>"></script>
</body>
</html>