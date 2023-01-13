<?php
date_default_timezone_set ( "Asia/Kolkata");


require_once '../includes/categoryDatabase.php';

require_once '../includes/userqueryDatabase.php';

require_once '../includes/managebranchDatabase.php';



 session_start();

if(!$_SESSION['id']){

   header('Location: ' . constant('BASE_URL'));

   exit;

}

$id = $_SESSION['id'];

$categoryObj = new categoryDatabase();

$user_query = new userqueryDatabase();

$manage_branchObj = new managebranchDatabase();





if($_POST['type'] == 'delete_record'){

    $delete_sql = "delete from {$_POST['table']} where id = {$_POST['id']}";

        $result_sql = mysql_query($delete_sql);

        if($result_sql){

            echo "1";

        }else {

            echo '0';

        }

}







if($_POST['type'] == 'delete_category'){

    $delete = $categoryObj->delete($_POST['id']);

    echo $delete;

}

if($_POST['type'] == 'add_category'){

   if($_POST['action'] == 'save'){

   $insert = $categoryObj->insert($_POST['data']);

   if($insert){

       echo '1';

   } else {

   echo '0';    

   } 

   } elseif($_POST['action'] == 'update'){

       $update = $categoryObj->update($_POST['data'],$_POST['id']);

   }

}

    

   if($_POST['type'] == 'add_query'){ 

       $firstname = mysql_escape_string($_POST['firstname']);

       $lastname = mysql_escape_string($_POST['lastname']);

       $email = mysql_escape_string($_POST['email']);

       $phone = mysql_escape_string($_POST['phone']);

       $category = $_POST['category'];

       $follow_date = $_POST['follow_date'];

       $follow_date=$follow_date." "."00:00";

       $message = mysql_escape_string($_POST['message']);

       $branch = $_POST['branch'];

       $date = date('Y-m-d H:i:s');

       $check_email_id = mysql_query("select email_id from user_query where category='$category' and branch_id='$branch' and email_id='$email'");

       if(mysql_num_rows($check_email_id) == 0){

      $add_query = "insert into user_query (firstname,lastname,email_id,category,message,followup_date,next_followup_date,phone,status,pid,branch_id)

           values ('$firstname','$lastname','$email','$category','$message','$date','$follow_date','$phone','start','0','$branch')";

       

       $add_result = mysql_query($add_query);

       if($add_result){

       echo '1';

   } else {

       echo '0';

   }

       } else {

           echo '2';

       }



}



if($_POST['type'] == 'message_follow'){

 //'type':type,'id':id,'message':message,'status':status,'followupD':followupD,'timeFollup':timeFollup,

 //'nextFollowRemark':nextFollowRemark,

 //'dateFollupNxt':dateFollupNxt,'timeFollupNxt':timeFollupNxt   

    $id_f = $_POST['id'];

    $message_follow = $_POST['message'];

    //$followup = $_POST['followupD'];

   // $timeFollup = $_POST['timeFollup'];

    //$followup=$followup." ".$timeFollup;
	$followup= date('Y-m-d H:i:s');
    $followType = $_POST['followType'];

    $nextFollowRemark = $_POST['nextFollowRemark'];

    $dateFollupNxt = $_POST['dateFollupNxt']." ".$_POST['timeFollupNxt'];

    $status = $_POST['status'];

    //$date = date('Y-m-d H:i:s');

    $data = $user_query->getRecordById($id_f);

    

    $insert_follow_up = "insert into user_query (firstname,lastname,email_id,category,message,followup_date,followup_type,next_followup_msg,next_followup_date,phone,status,pid)

        values ('".$data['firstname']."','".$data['lastname']."','".$data['email_id']."','".$data['category']."','".$message_follow."',

            '".$followup."','".$followType."','".$nextFollowRemark."','".$dateFollupNxt."','".$data['phone']."','".$status."','".$id_f."')";

    $insert = mysql_query($insert_follow_up);    

    

    

}



if($_POST['type'] == 'add_branch'){

    $first_name = mysql_escape_string($_POST['first_name']);

    $last_name = mysql_escape_string($_POST['last_name']);

    if(array_key_exists('branch_name', $_POST)){

    $branch_name = mysql_escape_string($_POST['branch_name']);

    }

    $email = mysql_escape_string($_POST['email']);

    $phone = $_POST['phone'];

    $password = mysql_escape_string($_POST['password']);

    $username = mysql_escape_string($_POST['username']);

    $city = mysql_escape_string($_POST['city']);

    $address = mysql_escape_string($_POST['address']);

    $date = date('Y-m-d');

    $role = $_POST['role'];

    $id= $_POST['id'];

  

    $check = mysql_query("select * from login_accounts where username = '$username' and id != '$id' "); 

    $record = mysql_fetch_assoc($check);

       // var_dump($record);

    if(isset($record['id'])){

        echo "2";

        exit;

    }

  

    $check_cat = mysql_query("select * from login_accounts where branch_id='".$_POST['branch_id']."' and category='".$_POST['category']."'");

    if(mysql_num_rows($check_cat) <= 1){ 

    

    if($_POST['action'] == 'insert'){

        if($_POST['branch_id'] != ''){

         $insert_manage_branch = "insert into login_accounts (first_name,last_name,branch_name,address,city,username,password,email_id,phone_no,role,date_added,branch_id,category)

        values ('$first_name','$last_name','$branch_name','$address','$city','$username','$password','$email','$phone','$role','$date','".$_POST['branch_id']."','".$_POST['category']."')";

    } else {

   $insert_manage_branch = "insert into login_accounts (first_name,last_name,branch_name,address,city,username,password,email_id,phone_no,role,date_added)

        values ('$first_name','$last_name','$branch_name','$address','$city','$username','$password','$email','$phone','$role','$date')";

    }

    $insert = mysql_query($insert_manage_branch);

    if($insert){

        echo "1";

    } else {

        echo "0";

    }

    } else if($_POST['action'] =='update')  {

        $id= $_POST['id'];

         if($_POST['branch_id'] != ''){

             

           $update_manage_branch = "update login_accounts set first_name = '$first_name',last_name = '$last_name',branch_name = '',

        address = '$address',city = '$city',username = '$username',password = '$password',

            email_id = '$email',phone_no = '$phone',branch_id = '".$_POST['branch_id']."',category='".$_POST['category']."' where id = $id"; 

         } else {

          $update_manage_branch = "update login_accounts set first_name = '$first_name',last_name = '$last_name',branch_name = '$branch_name',

        address = '$address',city = '$city',username = '$username',password = '$password', email_id = '$email',phone_no = '$phone'

            ,category='".$_POST['category']."' where id = $id";  

         }

        

    $insert = mysql_query($update_manage_branch);

    if($insert){

        echo "1";

    } else {

        echo "0";

    }

    }

    //} 

    

    }else {

        if(mysql_num_rows($check_cat) >= 0){

            echo "3";

        } else {

        echo "2";    

        }

        

    }

    

    

    

}

?>

