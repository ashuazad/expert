<?php
date_default_timezone_set("Asia/Kolkata");
require_once '../includes/userqueryDatabase.php';
require_once '../includes/categoryDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once '../includes/useraccountDatabase.php';
require_once '../includes/db.php';
 session_start();
if(!$_SESSION['id']){
   header('Location: ' . constant('BASE_URL'));
   exit;
}
$id = $_SESSION['id'];
//$id = 34;
$userquery = new userqueryDatabase();
$category = new categoryDatabase();
$branchData = new managebranchDatabase();
$userAccDb = new useraccountDatabase();
$dbObj = new db();
$today = date('Y-m-d');
$where = " emp_id = ".$id;
$todaydoneWhere = "(SELECT lead_id FROM user_query WHERE emp_id = ".$id." AND date(followup_date) = '".date('Y-m-d')."')";
$query = "SELECT 
            (
                SELECT 
                    count(*) 
                FROM
                    leads
                WHERE 
                    ".$where." AND ((status not in('Start','Complete','Dead')  and date(next_followup_date)='".$today."' and emp_id=".$id." ) 
                    OR (DATE(assingment_data) = '".$today."' AND Message IS NULL AND emp_id=".$id." ))
            ) AS TODAY_PENDING,
            (
                SELECT 
                    count(*) 
                FROM
                    leads
                WHERE
                    ".$where." AND ((status not in('Start','Complete','Dead')  and date(next_followup_date)<='".$today."' and emp_id=".$id." ) 
                  OR (DATE(assingment_data) <= '".$today."' AND Message IS NULL AND emp_id=".$id."))    
            ) AS ALL_PENDING,
            (
                SELECT 
                    count(*) 
                FROM
                    leads
                WHERE
                    id IN(".$todaydoneWhere.")
            ) AS TODAY_DONE,
            (
                SELECT 
                    count(*) 
                FROM
                    leads
                WHERE
                (status != 'Start' AND emp_id=".$id.") 
            ) AS ALL_STATUS,
            (
                SELECT 
                    count(*) 
                FROM
                    leads
                WHERE
                    ".$where." AND (frwId > 0 AND message IS NULL) AND DATE(assingment_data) ='".$today."'
            ) AS TODAY_NEW
          FROM  
            DUAL";
//echo $query;
echo json_encode(mysql_fetch_assoc(mysql_query($query)));