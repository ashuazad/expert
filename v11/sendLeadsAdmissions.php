<?php
session_start();
if (!$_SESSION['id']) {
    unset($_SESSION['id']);
    session_destroy();
    header('Location: https://www.advanceinstitute.co.in');
    exit;
}
if ($_SESSION['user_permission']['send_leads_admissions']!='1' && ($_SESSION['USER_TYPE']=='EMPLOYEE')) {
    header('Location: https://www.advanceinstitute.co.in'.'/account/index.php');
    exit;
}
if (!isset($_SESSION['OTP_C']) && empty($_SESSION['OTP_C'])) {
    unset($_SESSION['id']);
    session_destroy();
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
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png">
    <title>Send Leads & Admissions</title>
    <!-- chartist CSS -->
    <link href="./assets/node_modules/chartist-js/dist/chartist.min.css" rel="stylesheet">
    <link href="./assets/node_modules/chartist-js/dist/chartist-init.css" rel="stylesheet">
    <link href="./assets/node_modules/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.css" rel="stylesheet">
    <link href="./assets/node_modules/css-chart/css-chart.css" rel="stylesheet">
    <!-- Editable CSS -->
    <link type="text/css" rel="stylesheet" href="./assets/node_modules/jsgrid/jsgrid.min.css" />
    <link type="text/css" rel="stylesheet" href="./assets/node_modules/jsgrid/jsgrid-theme.min.css" />
    <!-- Custom CSS -->
    <link href="./dist/css/style.css" rel="stylesheet">
    <!-- page css -->
    <link href="./assets/node_modules/switchery/dist/switchery.min.css" rel="stylesheet" />
    <link href="./dist/css/pages/widget-page.css" rel="stylesheet">
    <link href="./dist/css/pages/tab-page.css" rel="stylesheet">
    <link href="./assets/node_modules/toast-master/css/jquery.toast.css" rel="stylesheet">
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
                        <div><img src="../assets/images/users/4.jpg" alt="user-img" class="img-circle"></div>
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
                        <h4 class="text-themecolor">Leads & Admissions</h4>
                    </div>
                    <div class="col-md-7 align-self-center text-right">
                        <div class="d-flex justify-content-end align-items-center">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                                <li class="breadcrumb-item active">Leads & Admissions</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- Quotation Grid Start -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card move-box" style="display:none">
                            <div class="card-body cust-filter-card-body p-b-10 p-t-10">
                                <div class="row">
                                    <div class="col-xs m-r-5">
                                        <div class="form-group cust-filter-form-group">
                                            <label style="font-size: 1rem;"> Select Branch : </label>
                                            <select id="move-branchList" class="form-control custom-select moveBranchList ivr-call-filter-input">
                                                <option value="">Status</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <hr/>
                                <div class="row">
                                    <div class="col-md-12 move-emp-list">
                                    </div>
                                    <div class="m-t-10 send-objects-btn" style="display:none;">
                                        <button type="button" class="btn btn-primary send-objects"> 
                                            <i class="fas fa-check"></i> 
                                            Send 
                                        </button>
                                    </div>
                                </div>
                            </div>    
                        </div>    
                        <div class="card">
                            <div class="card-header">
                                <span style="font-size: 20px;">Send Leads & Admissions</span>
                                <div class="card-actions">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="ti-settings"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right cust-dropdown-menu">
                                            <a class="dropdown-item cust-dropdown-item show-move-box" href="javascript:void(0)"><i class="fas fa-plus"></i>&nbsp;&nbsp;Send</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Filter Start -->
                            <div class="card-body cust-filter-card-body p-l-0 p-r-0">
                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs search-tab" role="tablist">
                                    <li class="nav-item"> <a class="nav-link active tab-leads" data-tab-id="leads" data-toggle="tab" href="#leadsIVRPanel" role="tab"><span class="hidden-sm-up"><i class="icon-user-follow"></i></span> <span class="hidden-xs-down">Leads</span></a> </li>
                                    <li class="nav-item"> <a class="nav-link tab-admission" data-tab-id="adm" data-toggle="tab" href="#dueIVRPanel" role="tab"><span class="hidden-sm-up"><i class="icon-people"></i></span> <span class="hidden-xs-down">Admission</span></a> </li>
                                </ul>
                                <!-- Tab panes -->
                                <div class="tab-content tabcontent-border">
                                    <div class="tab-pane active" id="leadsIVRPanel" role="tabpanel">
                                        <!-- Filter Leads Start -->
                                        <div class="card-body">
                                            <div class="row justify-content-md-left p-b-5 leads-noff-rows-box" style="display: none;">
                                                <div class="col-xs m-r-5 leads-noff-rows-text">

                                                </div>
                                            </div>
                                            <div class="row justify-content-md-left">
                                            <div class="col-xs m-r-5">
                                                    <div class="form-group cust-filter-form-group">
                                                        <input type="date" id="leads-fromDate" class="form-control fromDate custom-date ivr-call-filter-input" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col-xs m-r-5">
                                                    <div class="form-group cust-filter-form-group">
                                                        <input type="date" id="leads-toDate" class="form-control toDate custom-date ivr-call-filter-input" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col-xs m-r-5">
                                                    <div class="form-group cust-filter-form-group">
                                                        <select id="leads-branchList" class="form-control custom-select branchList ivr-call-filter-input">
                                                            <option value="">Status</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xs m-r-5">
                                                    <div class="form-group cust-filter-form-group">
                                                        <select id="leads-empList" class="form-control custom-select empList">
                                                            <option>Filter By Employee</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xs m-r-5">
                                                    <div class="form-group cust-filter-form-group">
                                                        <select id="leads-status" class="form-control custom-select status ivr-call-filter-input">
                                                            <option value="">Status</option>
                                                            <option value="Active">Active</option>
                                                            <option value="Complete">Complete</option>
                                                            <option value="Dead">Dead</option>
                                                            <option value="Important">Important</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xs m-r-5">
                                                    <div class="form-group cust-filter-form-group">
                                                        <input type="phone" id="leads-phone" class="form-control phone ivr-call-filter-input" placeholder="Phone No" autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class="col-xs">
                                                    <button type="button" class="btn waves-effect waves-light btn-primary leads-search">GO</button>
                                                    <button type="button" class="btn waves-effect waves-light btn-danger offer-filter leads-reset-search">
                                                        <i class="fas fa-redo"></i>
                                                    </button> 
                                                </div>
                                            </div>
                                            <hr/>
                                            <div class="row m-t-5 justify-content-md-center leads-filter-result" >
                                                <div class="searchResultLeads" style="position: relative; height: auto; width: 100%;"> </div>
                                            </div>
                                        </div>
                                        <!-- Filter Leads Ends -->
                                    </div>
                                    <div class="tab-pane" id="dueIVRPanel" role="tabpanel">
                                        <!-- Filter Admission Start -->
                                        <div class="card-body">
                                            <div class="row justify-content-md-left p-b-5 adm-noff-rows-box" style="display: none;">
                                                <div class="col-xs m-r-5 adm-noff-rows-text">

                                                </div>
                                            </div>
                                            <div class="row justify-content-md-left">
                                                <div class="col-xs m-r-5">
                                                    <div class="form-group cust-filter-form-group">
                                                        <input type="date" id="adm-fromDate" class="form-control fromDate custom-date" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col-xs m-r-5">
                                                    <div class="form-group cust-filter-form-group">
                                                        <input type="date" id="adm-toDate" class="form-control toDate custom-date" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col-xs m-r-5">
                                                    <div class="form-group cust-filter-form-group">
                                                        <select id="adm-branchList" class="form-control custom-select branchList">                                    
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xs m-r-5">
                                                    <div class="form-group cust-filter-form-group">
                                                        <select id="adm-empList" class="form-control custom-select empList">
                                                            <option>Filter By Employee</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xs m-r-5">
                                                    <div class="form-group cust-filter-form-group">
                                                        <select id="adm-status" class="form-control custom-select status ivr-call-filter-input">
                                                            <option value="">Status</option>
                                                            <option value="Active">Active</option>
                                                            <option value="Complete">Complete</option>
                                                            <option value="Dead">Dead</option>
                                                            <option value="Important">Important</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xs m-r-5">
                                                    <div class="form-group cust-filter-form-group">
                                                        <input type="text" id="adm-credit-amt" class="form-control credit-amt" placeholder="Credit Amt">
                                                    </div>
                                                </div>
                                                <div class="col-xs m-r-5">
                                                    <div class="form-group cust-filter-form-group">
                                                        <input type="text" id="adm-phone" class="form-control phone" placeholder="Phone No" autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class="col-xs">
                                                        <button type="button" class="btn waves-effect waves-light btn-primary adm-offer-filter">GO</button>
                                                        <button type="button" class="btn waves-effect waves-light btn-danger offer-filter adm-reset-filter">
                                                            <i class="fas fa-redo"></i>
                                                        </button>
                                                </div>
                                            </div> 
                                            <hr/>
                                            <div class="row m-t-5 justify-content-md-center adm-filter-result">
                                                <div class="searchResultAdm" style="position: relative; height: auto; width: 100%;"> </div>
                                            </div> 
                                        </div>
                                        <!-- Filter Admission Ends --> 
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ============================================================== -->
                <!-- Fees Riecpt Grid End -->
                <!-- ============================================================== -->

                <!--- Leads Details Start -->
                <div id="grid-modal" class="modal" tabindex="-1" role="dialog" aria-labelledby="grid-title" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header lead-edit">
                                <h4 class="modal-title lead-name lead-edit-row" id="grid-title">Name</h4>
                                <div class="controls">
                                    <input type="text" class="form-control lead-name-field" name="name">
                                </div>
                                <button type="button" class="btn btn-dark quotation-button" data-toggle="tab" href="#quotation" role="tab" style="display:none">Send PDF</button>
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
                                <button type="button" class="close followups-action-button lead-edit-button" style="display:none"><i class="far fa-edit"></i></button>
                                <button type="button" class="close followups-action-button lead-save-button" style="display:none"><i class="fas fa-check"></i></button>
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
                                    <li class="nav-item" style="display:none"> <a class="nav-link quotation-tab" data-toggle="tab" href="#quotation" role="tab"><span class="hidden-sm-up"><i class="ti-notepad"></i></span> <span class="hidden-xs-down">Special Offer Send</span></a> </li>
                                </ul>
                                <!-- Tab panes -->
                                <div class="tab-content tabcontent-border">
                                    <div class="tab-pane active" id="messages" role="tabpanel">
                                        <div class="row">
                                            <!-- Followup Box is hidden -->
                                            <div class="col-md-6" style="display:none">
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
                                            <div class="col-md-12">
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
                                    <div class="tab-pane" id="quotation" role="tabpanel" style="display:none">
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
                                <button type="button" class="btn btn-success waves-effect" id="saveFollowup" style="display:none">Save</button>
                                <button type="button" class="btn btn-info waves-effect" id="closeFollowupPopup" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!--- Leads Details End -->
                <!-- Admission Details Start -->
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
                                                    <div class="col-md-6" style="display:none;">
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
                                                    <div class="col-md-12">
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
                                            <div class="tab-pane p-20" id="receipt" role="tabpanel">
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
                                        <button style="display:none;" type="button" class="btn btn-success waves-effect" id="saveAdmDueFollowup">Save</button>
                                        <button type="button" class="btn btn-info waves-effect" id="closeAdmDueFollowupPopup" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>
                    </div>
                <!-- Admission Details End-->
            </div>
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
    <script src="./assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="./assets/node_modules/popper/popper.min.js"></script>
    <script src="./assets/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="./dist/js/perfect-scrollbar.jquery.min.js"></script>
    <!--Wave Effects -->
    <script src="./dist/js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="./dist/js/sidebarmenu.js"></script>
    <!--stickey kit -->
    <script src="./assets/node_modules/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="./assets/node_modules/sparkline/jquery.sparkline.min.js"></script>
    <!-- Sweet-Alert  -->
    <script src="./assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <!--Custom JavaScript -->
    <script src="./assets/node_modules/switchery/dist/switchery.min.js"></script>
    <script src="./assets/node_modules/jsgrid/db.js"></script>
    <script type="text/javascript" src="./assets/node_modules/jsgrid/jsgrid.min.js"></script>
    <script src="./dist/js/custom.min.js"></script>
    <script src="./assets/node_modules/sparkline/jquery.sparkline.min.js"></script>
    <script src="./assets/node_modules/gauge/gauge.min.js"></script>
    <script src="./dist/js/pages/widget-data-due-fees.js?_=<?php echo time();?>"></script>
    <script src="./assets/node_modules/toast-master/js/jquery.toast.js"></script>
    <script src="./dist/js/accNavDetails.js?_=<?php echo time();?>"></script>
    <script src="./dist/js/common.js?_=<?php echo time();?>"></script>
    <script type="module" src="./dist/js/sendLeadsAdmissions.js?_=<?php echo time();?>"></script>
</body>
</html>