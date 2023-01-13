<?php
date_default_timezone_set("Asia/Kolkata");
require_once '../includes/categoryDatabase.php';
require_once '../includes/userqueryDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once '../includes/db.php';
session_start();
if (!$_SESSION['id']) {
    header('Location: ' . constant('BASE_URL'));
    exit;
}
$id = $_SESSION['id'];
$categoryObj = new categoryDatabase();
$user_query = new userqueryDatabase();
$manage_branchObj = new managebranchDatabase();
$dbObj = new db();
$data = array(
            "login_user"=>"None",
            "nav" => array(
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
                        )
            );
$loginUser = $dbObj->getData(array("CONCAT(first_name, last_name) AS Name"),"login_accounts", "id='".$id."'");
$data['login_user'] = $loginUser[1]['Name'];
echo json_encode($data);