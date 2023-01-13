<?php
date_default_timezone_set('Asia/Kolkata');
$start_time = date('Y-m-d H:i:s');
error_log("\n".'Job Start At : '.$start_time.'->',3,'IVR_JOB.log');
/*error_reporting(E_ALL);
ini_set('display_errors', '1');*/
date_default_timezone_set("Asia/Kolkata");
require_once '../includes/categoryDatabase.php';
require_once '../includes/userqueryDatabase.php';
require_once '../includes/managebranchDatabase.php';
require_once '../includes/db.php';
/*session_start();

if (!$_SESSION['id']) {
    header('Location: ' . constant('BASE_URL'));
    exit;
}

$id = $_SESSION['id'];*/
$categoryObj = new categoryDatabase();
$user_query = new userqueryDatabase();
$manage_branchObj = new managebranchDatabase();
$dbObj = new db();

$today = date("Y-m-d H:i:s");

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL,"https://developers.myoperator.co/search/?token=e12c4ee9098c5e7a60e7ed25f5636097");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,
    "token=e12c4ee9098c5e7a60e7ed25f5636097");

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$ivrLog = curl_exec($ch);

curl_close ($ch);

function getState($code, $dbObj)
{
    $data = $dbObj->getData(array('CONCAT(state, " ", country) AS phone_location'), 'tele_state_code', " state_code = '" .$code. "'");
    return $data[1]['phone_location'];
}

function loadIVRLog($log, $dbObj)
{
    $logData = json_decode($log, 1);
    foreach ($logData['data']['hits'] as $log) {
        $state = explode(',', $log['_source']['state']);
        $stateName = getState($state[0], $dbObj);

        $userName = '';
        $duration = '';
        $call_type = 'missed';
        if (is_array($log['_source']['log_details'])) {
            foreach ($log['_source']['log_details'] as $logDtls) {
                if ($logDtls['action'] == 'received') {
                    $call_type = 'received';
                    $userName = $logDtls['received_by'][0]['name'];
                    $duration = $logDtls['duration'];
                }
            }
        }

        $lead = array(
            'phone' => $log['_source']['caller_number_raw'],
            'phone_location' => $stateName,
            'create_date' => date('Y-m-d H:i:s', $log['_source']['start_time']),
            'branch_id' => '21',
            'source' => 'IVR',
            'name' => 'IVR',
            'email' => 'IVR@gmail.com',
            'category' => 'Mobile-Repairing-Course',
            'emp_id' => '1',
            'domain_url' => 'IVR'
        );
        $leads = $dbObj->getData(array('id', 'phone', 'source'), 'leads', " phone like '%" . $log['_source']['caller_number_raw'] . "%'");
        $ivrLead = array(
            'phone' => $log['_source']['caller_number_raw'],
            'department' => $log['_source']['department_name'],
            'state' => $state[0],
            'call_type' => $call_type,
            'user' => $userName,
            'duration' => $duration
        );
        //print_r($leads);
        //print_r($ivrLead);
        if ($leads[0] == 0) {
            $dbObj->dataInsert($lead, 'leads');
            $ivrLeadsChk = $dbObj->getData(array('phone'), 'ivr_lead', " phone like '%" . $log['_source']['caller_number_raw'] . "%'");
            if($ivrLeadsChk[0] == 0){
                error_log("\nLEAD ADDED#" . $log['_source']['caller_number_raw'],3,'IVR_JOB.log');
                $dbObj->dataInsert($ivrLead, 'ivr_lead');    
            }
        } else {
            if ($leads[1]['source'] != 'IVR') {
                $sql = "UPDATE leads SET create_date = '" . date('Y-m-d H:i:s') . "', hits = hits+1 WHERE id  = " . $leads[1]['id'];
                mysql_query($sql);    
            }
        }
    }
}
loadIVRLog($ivrLog, $dbObj);
$end_time = date('Y-m-d H:i:s');
error_log('Job End At : ' . $end_time,3,'IVR_JOB.log');