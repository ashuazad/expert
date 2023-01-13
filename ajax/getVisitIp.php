<?php
require_once '../includes/userqueryDatabase.php';
require_once '../includes/categoryDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';
 session_start();
if(!$_SESSION['id']){
   header('Location: ' . constant('BASE_URL'));
   exit;
}
date_default_timezone_set('Asia/Kolkata');
$id = $_SESSION['id'];
db::getDBconnect();
$dbObj = new db();
$returnData=array('data'=>array(),'itemsCount'=>0);
//Default Where
$where = ' 1=1 ';
//Paging
$pageIndex = $_REQUEST['pageIndex'];
$pageSize = $_REQUEST['pageSize'];
$startRow = ceil(($pageIndex-1)*$pageSize);
//Shorting
$shortCol = 'hittime';
$shortOrder = 'DESC';
if (!empty($_REQUEST['sortField']) && strlen($_REQUEST['sortField'])>0) {
    $shortCol = trim($_REQUEST['sortField']);
}
if (!empty($_REQUEST['sortOrder']) && strlen($_REQUEST['sortOrder'])>0) {
    $shortOrder = trim($_REQUEST['sortOrder']);
}
//Date Filter
$dateFilter = '';
if (!empty($_REQUEST['fromDate']) && strlen($_REQUEST['fromDate'])>0) {
    $fromDate = strtotime($_REQUEST['fromDate']);
    $dateFilter = " hittime >= '".$fromDate."' ";
}
if (!empty($_REQUEST['toDate']) && strlen($_REQUEST['toDate'])>0) {
    $toDate = strtotime($_REQUEST['toDate']);
    $dateFilter .= " AND hittime <= '".$toDate."' ";
}
$where = (strlen($dateFilter)>0)?$dateFilter:$where;
/*echo $fromDate;
echo "\n";
echo $toDate;*/
//Get Count
$ttCount = $dbObj->getData(array("COUNT(ip) AS TT_COUNT"),"visitIps", $where);
$tableColumns = array('ip','domain',"IF(hittime>0,FROM_UNIXTIME(hittime,".DATE_TIME_FORMAT."),'NONE') AS hit_date_time",'hits','location','region','country_name');
mysql_query("SET time_zone = '+05:30'");
$dtlArray=$dbObj->getData($tableColumns,"visitIps ip LEFT JOIN location_region lr ON ip.location_region_id = lr.id LEFT JOIN location_country lc ON ip.location_country_id = lc.id",$where . " order by " .$shortCol . " " . $shortOrder . " limit " . $startRow . ", " . $pageSize);
array_shift($ttCount);
array_shift($dtlArray);
/*foreach ($dtlArray as $indx=>$eachRow) {
    $domain_url = $dbObj->getData(array('domain_url'), 'leads', "ip = '".$eachRow['ip']."'");
    $dtlArray[$indx]['domain'] = ($domain_url[0]>1)?$domain_url[1]['domain_url']:'NONE';
}*/
$returnData['data'] = $dtlArray;
$returnData['itemsCount'] = $ttCount[0]['TT_COUNT'];
echo json_encode($returnData);