<?php
date_default_timezone_set ( "Asia/Kolkata");


require_once '../includes/categoryDatabase.php';

require_once '../includes/userqueryDatabase.php';

require_once '../includes/managebranchDatabase.php';
require_once '../includes/db.php';



 session_start();

if(!$_SESSION['id']){

   header('Location: ' . constant('BASE_URL'));

   exit;

}

$id = $_SESSION['id'];

$categoryObj = new categoryDatabase();

$user_query = new userqueryDatabase();

$manage_branchObj = new managebranchDatabase();
$dbObj = new db();

$deltArry = explode(",",$_POST['deletLds']);
for($dI=0;$dI<count($deltArry);$dI++ ){
	$delLeadId=trim($deltArry[$dI]);
	$dbObj->delOne("user_query","lead_id",$delLeadId);
        $dbObj->delOne("leads","id",$delLeadId);
	}
$getDataFups = $dbObj->getData(array('id','name','email','phone','category',"DATE_FORMAT(create_date,'%D %b %Y %r') cdt"), "leads", "DATE(create_date) = '$today' order by create_date desc");
array_shift($getDataFups);
print_r($getDataFups);

                                        foreach ($getDataFups as $data) {
                                            ?>
                                            <tr>
                                                <td id="c_b"><input name="led[]" class="check" type="checkbox"  value="<?php echo $data['id']; ?>">  </td>
                                                <td><a href="<?php echo constant('BASE_URL'); ?>/superadmin/messagedetail.php?id=<?php echo $data['id']; ?>"><?php echo $data['name']; ?></a></td>
                                                <td><?php echo $data['email']; ?></td>
                                                <td><?php echo $data['category']; ?></td>        
                                                <td><?php echo $data['phone']; ?></td>
                                                <th><?php echo $data['cdt']; ?></th>
                                             	
                                            </tr>
                                            <?php
                                        }
                                        ?>



