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
$dbObj = new db();

$userPermissions = new userPermissions($_SESSION['id']);



if ($_POST['type'] == 'delete_record') {

    $delete_sql = "delete from {$_POST['table']} where id = {$_POST['id']}";

    $result_sql = mysql_query($delete_sql);

    if ($result_sql) {

        echo "1";
    } else {

        echo '0';
    }
}







if ($_POST['type'] == 'delete_category') {

    $delete = $categoryObj->delete($_POST['id']);

    echo $delete;
}

if ($_POST['type'] == 'add_category') {

    if ($_POST['action'] == 'save') {

        $insert = $categoryObj->insert($_POST['data']);

        if ($insert) {

            echo '1';
        } else {

            echo '0';
        }
    } elseif ($_POST['action'] == 'update') {

        $update = $categoryObj->update($_POST['data'], $_POST['id']);
    }
}



if ($_POST['type'] == 'add_query') {

    $firstname = mysql_escape_string($_POST['firstname']);

    $lastname = mysql_escape_string($_POST['lastname']);

    $email = mysql_escape_string($_POST['email']);

    $phone = mysql_escape_string($_POST['phone']);

    $category = $_POST['category'];

    $follow_date = $_POST['follow_date'];

    $follow_date = $follow_date . " " . "00:00";

    $message = mysql_escape_string($_POST['message']);

    $branch = $_POST['branch'];

    $date = date('Y-m-d H:i:s');

    $check_email_id = mysql_query("select email_id from user_query where category='$category' and branch_id='$branch' and email_id='$email'");

    if (mysql_num_rows($check_email_id) == 0) {

        $add_query = "insert into user_query (firstname,lastname,email_id,category,message,followup_date,next_followup_date,phone,status,pid,branch_id)

           values ('$firstname','$lastname','$email','$category','$message','$date','$follow_date','$phone','start','0','$branch')";



        $add_result = mysql_query($add_query);

        if ($add_result) {

            echo '1';
        } else {

            echo '0';
        }
    } else {

        echo '2';
    }
}



if ($_POST['type'] == 'message_follow') {
//print_r($_POST);
    //'type':type,'id':id,'message':message,'status':status,'followupD':followupD,'timeFollup':timeFollup,
    //'nextFollowRemark':nextFollowRemark,
    //'dateFollupNxt':dateFollupNxt,'timeFollupNxt':timeFollupNxt   
    if (isset($_POST['id'])) {
        $id_f = $_POST['id'];
    }
    $message_follow = $_POST['message'];

    //$followup = $_POST['followupD'];
    // $timeFollup = $_POST['timeFollup'];
    //$followup=$followup." ".$timeFollup;
    $followup = date('Y-m-d H:i:s');
    $followType = $_POST['followType'];

    $nextFollowRemark = $_POST['nextFollowRemark'];

    $dateFollupNxt = $_POST['dateFollupNxt'] . " " . $_POST['timeFollupNxt'];

    $status = $_POST['status'];
    $leadId = $_POST['leadId'];
    // print_r($_POST);
    //$date = date('Y-m-d H:i:s');
    $leadQuryDtl = $dbObj->getData(array("*"), "user_query", "lead_id='" . $leadId . "' AND pid=0");
    if ($leadQuryDtl[0] > 0) {
        // echo "No";
        $insertArry = array('lead_id' => $leadId, 'message' => $message_follow, 'followup_date' => $followup, 'next_followup_date' => $dateFollupNxt, 'status' => $status, 'pid' => $id_f, 'emp_id' => $id);
		
        // print_r($insertArry);
    } else {
        //echo "Yes";
        $insertArry = array('lead_id' => $leadId, 'message' => $message_follow, 'followup_date' => $followup, 'next_followup_date' => $dateFollupNxt, 'status' => $status, 'pid' => 0, 'emp_id' => $id);
        // print_r($insertArry);
    }
	
    $dbObj->dataInsert($insertArry, "user_query");
	//$updatStatusLeadsTb = array ("status"=>$status,"r_status"=>1,'emp_id' => $id);
	$updatStatusLeadsTb = array ("status"=>$status,"r_status"=>1,'emp_id' => $id,'last_follow_up'=>$followup ,'next_followup_date' => $dateFollupNxt , 'message' => $message_follow,'hits'=>0);
	    $dbObj->dataupdate($updatStatusLeadsTb, "leads","id",$leadId) ;
}


//print_r($_POST);
if ($_POST['type'] == 'add_branch') {
//print_r($_POST);
    $first_name = mysql_real_escape_string($_POST['first_name']);

    $last_name = mysql_real_escape_string($_POST['last_name']);

    if (array_key_exists('branch_name', $_POST)) {

        $branch_name = mysql_real_escape_string($_POST['branch_name']);
    }

    $email = mysql_real_escape_string($_POST['email']);

    $phone = $_POST['phone'];

    $password = mysql_real_escape_string($_POST['password']);

    $username = mysql_real_escape_string($_POST['username']);

    $city = mysql_real_escape_string($_POST['city']);
    $admission_frm_perm = mysql_real_escape_string($_POST['admission_frm_perm']);
    $admission_dashboard_perm = mysql_real_escape_string($_POST['admission_dashboard_perm']);
    $address = mysql_real_escape_string($_POST['address']);
    $all_due_fee_pem = mysql_real_escape_string($_POST['all_due_fee_pem']);
    $all_admission_perm = mysql_real_escape_string($_POST['all_admission_perm']);
    $all_fee_pay_pem = mysql_real_escape_string($_POST['all_fee_pay_pem']);
    $serach_leads_adm_pem = mysql_real_escape_string($_POST['serach_leads_adm']);
    $send_leads_adm_pem = mysql_real_escape_string($_POST['send_leads_adm']);
    $date = date('Y-m-d');
    $role = $_POST['role'];
    $id = $_POST['id'];
    $insentive = $_POST['insentive'];
    $status = $_POST['status'];
    $salary = $_POST['salary'];
    $office_address = $_POST['office_address'];
    $check = mysql_query("select * from login_accounts where username = '$username' and id != '$id' ");

    $record = mysql_fetch_assoc($check);

    // var_dump($record);

    if (isset($record['id'])) {

        echo "2";

        exit;
    }



//    $check_cat = mysql_query("select * from login_accounts where branch_id='" . $_POST['branch_id'] . "' and category='" . $_POST['category'] . "'");

  //  if (mysql_num_rows($check_cat) <= 1) {



        if ($_POST['action'] == 'insert') {

            if ($_POST['branch_id'] != '') {

                $insert_manage_branch = "insert into login_accounts (first_name,last_name,branch_name,address,city,username,password,email_id,phone_no,role,admission_frm_perm,date_added,branch_id,category,all_due_fee_pem,all_admission_perm,insentive)

        values ('$first_name','$last_name','$branch_name','$address','$city','$username','$password','$email','$phone','$role',$admission_frm_perm,'$date','" . $_POST['branch_id'] . "','" . $_POST['category'] . "','$all_due_fee_pem','$all_admission_perm','$insentive')";
                
            } else {

                $insert_manage_branch = "insert into login_accounts (first_name,last_name,branch_name,address,city,username,password,email_id,phone_no,role,admission_frm_perm,date_added,all_admission_perm,insentive)

        values ('$first_name','$last_name','$branch_name','$address','$city','$username','$password','$email','$phone','$role',$admission_frm_perm,'$date','$all_admission_perm','$insentive')";
            }
            $insert = mysql_query($insert_manage_branch) ;
            $empId = mysql_insert_id();
 $sqlPermInsert = "INSERT INTO `emp_permissions` (`id`, `emp_id`, `permission`, `value`) VALUES (NULL, '".$empId."', '".fees_view_roll."', '".$_POST['fees_view_roll']."')";
     mysql_query($sqlPermInsert ) ;

            if ($insert) {

                echo "1";
            } else {

                echo "0";
            }
        } else if ($_POST['action'] == 'update') {

            $id = $_POST['id'];

            if ($_POST['branch_id'] != '') {
             $update_manage_branch = "update login_accounts set first_name = '$first_name',last_name = '$last_name',branch_name = '',

        address = '$address',city = '$city',admission_frm_perm='$admission_frm_perm',admission_dashboard_perm='$admission_dashboard_perm',username = '$username',password = '$password',

            email_id = '$email',phone_no = '$phone',branch_id = '" . $_POST['branch_id'] . "',category='" . $_POST['category'] . "', all_due_fee_pem = '$all_due_fee_pem', all_admission_perm = '$all_admission_perm',insentive = '$insentive',status = '$status', salary = '$salary', office_address='$office_address'  where id = $id";
               
 $sqlPermUpdate = "UPDATE `advancee_crm`.`emp_permissions` SET `value` = '".$_POST['fees_view_roll']."' WHERE `emp_permissions`.`permission`='fees_view_roll' AND `emp_permissions`.`emp_id`='".$id."'";
 		
            } else {

                $update_manage_branch = "update login_accounts set first_name = '$first_name',last_name = '$last_name',branch_name = '$branch_name',

        address = '$address',city = '$city',admission_frm_perm='$admission_frm_perm',admission_dashboard_perm='$admission_dashboard_perm',username = '$username',password = '$password', email_id = '$email',phone_no = '$phone'

            ,category='" . $_POST['category'] . "',insentive = '$insentive', office_address='$office_address' where id = $id";

 $sqlPermUpdate = "UPDATE `advancee_crm`.`emp_permissions` SET `value` = '".$_POST['fees_view_roll']."' WHERE `emp_permissions`.`permission`='fees_view_roll' AND `emp_permissions`.`emp_id`='".$id."'";
 
            }

	$prmArray = array('fees_view_roll' => $_POST['fees_view_roll'] , 
	                    'adm_from_details_phone' => $_POST['adm_from_details_phone'] ,
	                    'emp_set_discount'=> $_POST['emp_set_discount'],
	                    'all_due_fee_pem' => $_POST['all_due_fee_pem'],
	                    'all_admission_perm' => $all_admission_perm,
	                    'all_fee_pay_pem' => $all_fee_pay_pem,
                        'search_leads_admissions' => $serach_leads_adm_pem,
                        'send_leads_admissions' => $send_leads_adm_pem);	

 		$userPermissions->setPermission($prmArray,$id);  
           
            $insert = mysql_query($update_manage_branch);

            if ($insert) {

                echo "1";
            } else {

                echo "0";
            }
        }

        //} 
    
}

if($_POST['action'] == 'update_lead'){
   $update = "update leads set name = '".mysql_escape_string($_POST['name'])."',email = '".mysql_escape_string($_POST['email'])."',
        category = '".mysql_escape_string($_POST['cat'])."',phone = '".mysql_escape_string($_POST['phone'])."',address = '".mysql_escape_string($_POST['address'])."'
        where id = '".$_POST['id']."'";
    mysql_query($update);
}

if($_POST['action'] == 'getemp'){
    $id = $_POST['id'];
      $selectemp = "select * from login_accounts where branch_id = '$id'";
      $resultemp = mysql_query($selectemp);
      if(mysql_num_rows($resultemp) > 0){
          while($row = mysql_fetch_assoc($resultemp)){
              echo "<input type='radio' name='emp' class='empvalue' value='".$row['id']."' style='margin-left:20px;'>".$row['first_name'].' '.$row['last_name'];
          }
      } else {
          echo "No Employee Found.";
      }
}

if($_POST['action'] == 'updatelead'){
$dateSendLd = date('Y-m-d H:i:s');
    $emp = $_POST['emp'];
	$brnchId = mysql_fetch_row(mysql_query("SELECT branch_id FROM `login_accounts` where id = $emp"));
	///print_r($brnchId);
    $select = explode(',',rtrim($_POST['select'],', '));
   //print_r($select );
    for($i = 0; $i <= count($select)-1;$i++){
      $dataEmpId = mysql_fetch_array( mysql_query("select emp_id,status from leads where id = $select[$i]" ) );
      mysql_query("insert into leadfrwdhistory values( NULL , ".$select[$i]." ,'LEAD',".$dataEmpId['emp_id']." , $emp , '$dateSendLd', $id )");
      $lastIntId = mysql_insert_id();
if($dataEmpId['status'] == 'Dead'){
        $update = "update leads set branch_id = '".$brnchId[0]."' ,emp_id = '$emp' ,status='Active', r_status=1 , assingment_data = '".$dateSendLd ."',frwId=$lastIntId,message=NULL ,last_follow_up = '0000-00-00',next_followup_date  ='0000-00-00',hits=0  where id = '".$select[$i]."'";
      }else{
        $update = "update leads set branch_id = '".$brnchId[0]."' ,emp_id = '$emp' ,status='Active', r_status=1 , assingment_data = '".$dateSendLd ."',frwId=$lastIntId ,hits=0 where id = '".$select[$i]."'  ";
	}
      $result = mysql_query($update);
    }
}
?>

