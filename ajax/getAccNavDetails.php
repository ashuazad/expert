<?php
date_default_timezone_set("Asia/Kolkata");
require_once '../includes/categoryDatabase.php';
require_once '../includes/userqueryDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once '../includes/db.php';
require_once '../includes/userPermissions.php';
session_start();
if (!$_SESSION['id']) {
    header('Location: ' . constant('BASE_URL'));
    exit;
}
$id = $_SESSION['id'];
$categoryObj = new categoryDatabase();
$user_query = new userqueryDatabase();
$manage_branchObj = new managebranchDatabase();
$empPermission = new userPermissions($id);
$dbObj = new db();
if ($id == '1') {
    $arr_nav = array(
        array(
            "link" => "/v11/superadmin/userDueFees.php",
            "icon" => "icon-speedometer",
            "name" => "Due Fees"
        ),
        array(
            "link" => "/v11/superadmin/offerStatus.php",
            "icon" => "icon-speedometer",
            "name" => "Special Offers"
        ),
        array(
            "link" => "/v11/superadmin/otpSMSApi.php?type=login",
            "icon" => "icon-user",
            "name" => "LOGIN OTP API"
            ),
        array(
            "link" => "/v11/superadmin/otpSMSApi.php?type=due",
            "icon" => "ti-clipboard",
            "name" => "DUE OTP API"
        ),
        array(
            "link" => "/v11/superadmin/smsTemplate.php?type=due",
            "icon" => "ti-clipboard",
            "name" => "Due SMS Template"
        ),
        array(
            "link" => "/v11/superadmin/userPermission.php",
            "icon" => "icon-user",
            "name" => "User Permission"
        ),
        array(
            "link" => "/v11/searchLeadsAdmissions.php",
            "icon" => "ti-clipboard",
            "name" => "Search"
        )
    );
    $login_user = 'Super Admin';
} else {
    $loginUser = $dbObj->getData(array("CONCAT(first_name, last_name) AS Name"),"login_accounts", "id='".$id."'");
    $login_user = $loginUser[1]['Name'];
$arr_nav = array(
    array(
        "link" => "/v11/userLeads.php",
        "icon" => "icon-speedometer",
        "name" => "Dashboard"
    ),
    array(
        "link" => "/v11/userAdmission.php",
        "icon" => "icon-user",
        "name" => "Admissions"
        ),
    array(
        "link" => "/v11/userDueFees.php",
        "icon" => "ti-clipboard",
        "name" => "Due Fees"
    ),
    array(
        "link" => "/v11/userIncentive.php",
        "icon" => "ti-clipboard",
        "name" => "Incentive"
    )
);
if ($empPermission->userPermission['search_leads_admissions']) {
    $isPermissionEnable = true;
    $where = " 1=1 ";
    $arr_nav[] = array(
        "link" => "/v11/searchLeadsAdmissions.php",
        "icon" => "ti-clipboard",
        "name" => "Search"
    );
}
}
$data = array(
            "login_user" => $login_user,
            "nav" => $arr_nav
            );
echo json_encode($data);