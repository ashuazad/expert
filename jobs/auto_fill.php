<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
echo dirname(__FILE__) ;
date_default_timezone_set("Asia/Kolkata");
$baseDir = dirname(__FILE__) . '/';
require_once $baseDir . '../includes/userqueryDatabase.php';
require_once $baseDir . '../includes/categoryDatabase.php';
require_once $baseDir . '../includes/managebranchDatabase.php';
require_once $baseDir . '../includes/db.php';
require_once $baseDir . '../includes/student.php';
require_once $baseDir . '../includes/communication.php';
require_once $baseDir . '../includes/functions.php';

$userquery = new userqueryDatabase();
$category = new categoryDatabase();
$branchData = new managebranchDatabase();
$dbObj=new db();
$studentObj = new student();
$commObj = new communication();
$today=date('ymd');

//Stop the Job If current time is not in between 8:00 AM to 8:00 PM
$currentHours = intval(date('H'));

if (!($currentHours >= 8 && $currentHours <= 19)){
    echo $currentHours;
    echo "\nRUNNING ONLY FROM 08:00 TO 20:00 " . date('Y-m-d H:i:s') . "\n";
    exit();
    die();
}

//Courses
/*
$courses = [];
$courseResult = mysql_query("SELECT REPLACE(course,'-',' ') AS course FROM course_fee");
while($courses[] = trim(mysql_fetch_assoc($courseResult)['course'])){

}*/
$courses = ['Mobile Repairing course','iPhone Repairing Course','Laptop Repairing Course','Printer Repairing Course'];
//array_pop($courses);
//Cites
$cites = json_decode(file_get_contents('/home/advanceexstitute/public_html/jobs/indian-city-list.json'), true);
$apiDetails = $dbObj->getData(array('api'),"auto_fill_apis"," status=1");

$delayTime = null;
if ($apiDetails[0] >= 1) {
    //API 
    $ivrCallAPI = $apiDetails[1]['api'];
    $ivrCallMsg = "Hello %s, This is Expert";
    // Get View and Delay Details 
    $jobStatus = $dbObj->getData(array('id','object_type','created_date','view_name','delay_time','no_of_records','no_of_completed','status'),"delayed_auto_fill_jobs");
    
    // Check If already running
    if ($jobStatus[1]['status'] == '2') {
        echo "\nALL READY RUNNNING\n";
        exit();
        die();
    }
    // Check if all records are done
    // If all records are done then empty the job table and delete the view.
    $totalRecords = 0;
    $doneRecords = 0;
    $totalRecords = intval($jobStatus[1]['no_of_records']);
    $doneRecords = intval($jobStatus[1]['no_of_completed']);
    if ($doneRecords>=$totalRecords) {
        mysql_query('DROP VIEW IF EXISTS ' . $jobStatus[1]['view_name']);
        mysql_query('DELETE FROM delayed_jobs');   
    }

    if ($jobStatus[0]>0) {
        $delayTime = trim($jobStatus[1]['delay_time'])*60; // Converting minute to seconds

        $limitStart = ($jobStatus[1]['no_of_completed'])?(intval($jobStatus[1]['no_of_completed'])-1):0;

        $sqlSelectObj = 'SELECT id, phone, name, email, phone_w FROM ' . $jobStatus[1]['view_name'] . " ORDER BY id ASC LIMIT " . $limitStart . ", 500";
        $resultView = mysql_query($sqlSelectObj);

        // API call through Curl with delay
        if (mysql_num_rows($resultView)>0) {
            
            //Update Current Runinning Status
            $updateStartedSql = "UPDATE `delayed_auto_fill_jobs` SET `started_at` = '".date('Y-m-d H:i:s')."' WHERE `delayed_jobs`.`id` = ".$jobStatus[1]['id'];
            mysql_query($updateStartedSql);

            $count = intval($jobStatus[1]['no_of_completed'])+1;

            while($eachData = mysql_fetch_assoc($resultView)) {

                //Check Job is Stop from front end
                $currentJobStatus = $dbObj->getData(array('id'),"delayed_auto_fill_jobs");
                if (!intval($currentJobStatus[0])) {
                    echo "\nJOB IS STOPPED\n";
                    exit();
                    die();
                }

                //Stop the Job If current time is not in between 8:00 AM to 8:00 PM
                $currentHours = intval(date('H'));
                
                if (!($currentHours >= 8 && $currentHours <= 19)) {
                    //Check If no of done is less than total then change the status to 1
                    $jobStatusNow = $dbObj->getData(array('id','no_of_records','no_of_completed','status'),"delayed_auto_fill_jobs");
                    $jobStatusNow_Done = intval($jobStatusNow[1]['no_of_completed']);
                    $jobStatusNow_Total = intval($jobStatusNow[1]['no_of_records']);
                    //If the job was running then change the status=1 
                    if ($jobStatusNow_Done < $jobStatusNow_Total) {  
                        $updateRunningStatus = "UPDATE delayed_auto_fill_jobs SET status=1 WHERE id = ".$jobStatus[1]['id'];
                        mysql_query($updateRunningStatus);
                    }  
                    echo $currentHours;
                    echo "\nRUNNING ONLY FROM 08:00 TO 20:00 " . date('Y-m-d H:i:s') . "\n";
                    exit();
                    die();
                }
                
                $enabledApis = $dbObj -> getData(array('api'), 'auto_fill_apis', ' status = 1 ');
                array_shift($enabledApis);
                //Start Calling API
                foreach($enabledApis as $eachApi){
                    echo "\n";
                    echo $apiMessage = urlencode(sprintf($ivrCallMsg, $eachData['name']));
                    echo "\n";
                    $apiUrl = str_replace(array('#wrongPhone','#phone','#name','#email','#course','#city','#message'), array($eachData['phone'],$eachData['phone_w'],$eachData['name'],$eachData['email'], $courses[rand(0,(count($courses)-1))], $cites[rand(0,1216)],$apiMessage), $eachApi['api']);
                  
                    $apiUrl = str_replace(" ","%20",$apiUrl);
                    echo $apiUrl;
                    echo "\n";
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => $apiUrl,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'GET'
                    ));
                    $response = curl_exec($curl);
                    curl_close($curl);
                    echo $response;
                }
                
                //Start Calling API                
                //Updating count of completed 
                $updateSql = "UPDATE `delayed_auto_fill_jobs` SET `last_done_id` = '".$eachData['id']."', no_of_completed=".$count.", status=2 WHERE `delayed_auto_fill_jobs`.`id` = ".$jobStatus[1]['id'];
                mysql_query($updateSql);
                echo mysql_error();

                $count++;
                
                // Add Delay Time
                sleep($delayTime);
            }
            $currentJobStatus = $dbObj->getData(array('id','created_date','view_name','no_of_records','no_of_completed','status'),"delayed_auto_fill_jobs");
            
            // Updating the end time and deleting the job info and view
            $crrentDoneRecords = intval($currentJobStatus[1]['no_of_completed']);
            $crrentNoOfRecords = intval($currentJobStatus[1]['no_of_records']);
            if ($crrentNoOfRecords >= $crrentDoneRecords) {
                $updateEndedSql = "UPDATE `delayed_auto_fill_jobs` SET `ended_at` = '".date('Y-m-d H:i:s')."', status=1 WHERE `delayed_auto_fill_jobs`.`id` = ".$jobStatus[1]['id'];
                mysql_query($updateEndedSql);
                mysql_query('DROP VIEW IF EXISTS ' . $jobStatus[1]['view_name']);
                mysql_query('DELETE FROM delayed_auto_fill_jobs');    
            } else {
                $updateEndedSql = "UPDATE `delayed_auto_fill_jobs` SET status=1 WHERE `delayed_auto_fill_jobs`.`id` = ".$jobStatus[1]['id'];
                mysql_query($updateEndedSql);
            }
        }
    } else {
        echo "NOO JOB TO RUN.. !";
    }
}

?>