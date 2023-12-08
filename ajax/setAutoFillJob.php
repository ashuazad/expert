<?php
date_default_timezone_set("Asia/Kolkata");
require_once '../includes/userqueryDatabase.php';
require_once '../includes/categoryDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once '../includes/db.php';
require_once '../includes/student.php';
require_once '../includes/communication.php';
require_once '../includes/functions.php';
session_start();
if(empty($_SESSION['id'])){
   header('Location: ' . constant('BASE_URL'));
   exit;
}
$id = $_SESSION['id'];
$userquery = new userqueryDatabase();
$category = new categoryDatabase();
$branchData = new managebranchDatabase();
$dbObj=new db();
$studentObj = new student();
$commObj = new communication();
$today=date('ymd');

$errors = array();
$result = array();
$result['success'] = false;
$result['errors'] = $errors;
$postedData = json_decode(file_get_contents('php://input'), true);
$objectType = $postedData['object_type'];
$currentTimeViewName = date('YmdHis');
$currentTime = date('Y-m-d H:i:s');
switch($objectType){
    case 'leads':
        // Creating a view
        $where = " `source` != 'IVR' AND";
        $where .= getLeadsFilterSQl($postedData['filter_query']);
        $leadSql = " SELECT id, INSERT(phone, 8, 2, FLOOR(RAND() * 99.99)) AS phone, phone AS phone_w, name, email FROM leads WHERE " . $where . 'ORDER BY id ASC';
        $viewName = 'vw_auto_fill_job_list_' . $currentTimeViewName; 
        $leadsViewSql = "CREATE VIEW " . $viewName . ' AS ' . $leadSql;
        //echo $leadsViewSql;
        //sexit();
        mysql_query($leadsViewSql);
        // Creating a view
        // Adding view info
        $no_of_records = getCountFromObject($viewName);
        if ($no_of_records) {
            $arrViewInfo = array(
                'object_type' => $objectType,
                'view_name' =>  $viewName,
                'filter_query' => json_encode($postedData['filter_query']),
                'created_date' => $currentTime,
                'no_of_records' => getCountFromObject($viewName),
                'no_of_completed' => 0,
                'delay_time' => $postedData['delay_time'],
                'status' => 1
            );    
            $dbObj->dataInsert($arrViewInfo, 'delayed_auto_fill_jobs');    
            $result['success'] = true;
            //echo `/usr/local/bin/php /home/advanceexstitute/public_html/jobs/ivr_call.php`;
        } else {
            $result['errors'] = 'Data Base View is not working';
            $result['success'] = false;
        }
        // Adding view info    
        break;
}
echo json_encode($result);