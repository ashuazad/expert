<?php
require_once 'configuration.php';
require_once 'db.php';
require_once 'userPermissions.php';

class student extends db{
     var $nRolln=null;
         function __construct()
          {
                $lastRollNo=$this->getData(array("max(roll_no) lastroll"),"admission");
                 $this-> nRolln=$lastRollNo[1]['lastroll']+1;
          }
         function  admission( $tbl_name ){               
                   return $this->dataInsert( $_POST , $tbl_name );
               }            
         function getMainList(){
                       return $this->getData( array('a_id', 'regno', 'lead_id', 'due_fee', 'batch_time', 'name', 'email_id', 'phone', 'course')  ,  "admission" );
             }    
         function getStudent( $aId =NULL , $regNo =NULL){
                       $sDtl= $this->getData( array('*')  ,  "admission" , "regno='".$regNo."' AND a_id='".$aId."'");
                      return   $sDtl[1];
             }    
        function getStudentPayDetail( $case , $val ){     
                 $userPermissions = new userPermissions($_SESSION['id']);
                  $permissionArray = null;
                  if($_SESSION['id'] != 1){
                     $permissionArray = $this->getData(array("branch_id"),"login_accounts" ,"id=".$_SESSION['id']);
                     array_shift($permissionArray);
                     }             
                 switch( $case ){
					 case 'regno':                 
                      $sDtl= $this->getData(array('course_fee','total_fee','due_fee','name','phone','roll_no','regno','a_id','father_name','permanent_address','branch_name'),"admission", " regno='".$val."'");
############### Permission Check  ################
                      if($_SESSION['id'] != 1 && ($userPermissions->userPermission['all_fee_pay_pem'] != 1)){
                          if($permissionArray[0]["branch_id"] == $sDtl[1]['branch_name']){
                               return   $sDtl[1];                         
                               }else{
                               return  false;
                               }
                       }else{   
                         return   $sDtl[1];
                         }
############### Permission Check  ################
					  break;
					 case 'roll_no':
                      $sDtl= $this->getData(array('course_fee','total_fee','due_fee','name','phone','roll_no','regno','a_id','father_name','permanent_address','branch_name','emp_id','lead_userId'),"admission","roll_no='".$val."'");
############### Permission Check  ################

                      if($_SESSION['id'] != 1 && ($userPermissions->userPermission['all_fee_pay_pem'] != 1)){
                          if($permissionArray[0]["branch_id"] == $sDtl[1]['branch_name']){
            if((int)$userPermissions->userPermission['fees_view_roll'] === 1 || ($sDtl[1]['lead_userId'] == $_SESSION['id'])){
           
                                  return   $sDtl[1]; 
                                }else{
                              
                                  return  false;   
                                }              
                               }else{
                               return  false;
                               }
                       }else{   
                         return   $sDtl[1];
                         }
############### Permission Check  ################

					 break;					 
					 case 'phone':
                      $sDtl= $this->getData(array('course_fee','total_fee','due_fee','name','phone','roll_no','regno','a_id','father_name','permanent_address','branch_name'),"admission","phone='".$val."'");
############### Permission Check  ################
                      if($_SESSION['id'] != 1 && ($userPermissions->userPermission['all_fee_pay_pem'] != 1)){
                          if($permissionArray[0]["branch_id"] == $sDtl[1]['branch_name']){
                               return   $sDtl[1];    
                               }else{
                               return  false;
                               }
                       }else{   
                         return   $sDtl[1];
                         }
############### Permission Check  ################

					 break;					 
                    }
             }    
          function getStudentFeeDtl( $aId , $regNo , $regFee = null ){
                       $sDtl= $this->getData( array('*')  ,  "fee_detail" , "reg_no='".$regNo."' AND a_id='".$aId."' AND pid='0'");
                      return   $sDtl[1];
             } 
        function getFeeHistory( $reg_no , $a_id, $column=array('*')){
                       $sDtl= $this->getData( $column,  "fee_detail fd, login_accounts la", "(fd.emp_id=la.id) AND (reg_no='".$reg_no."' AND a_id='".$a_id."')");
                       array_shift($sDtl);
                       return $sDtl;
             } 
         function recptDtl( $f_id )
                       {
                             $dtlArry=$this->getData(array("*") ,   "fee_detail" , "f_id='".$f_id."'"); 
                             array_shift($dtlArry) ;
                            $dtlArry[] = $this->getStudent($dtlArry[0]['a_id'],$dtlArry[0]['reg_no']);
                            $crdAmtTotl = $dtlArry[1]['total_fee']-$dtlArry[0]['dueamt']; 
                       /*
                            $creditAmtArry = $this->getData(array("amt") ,   "fee_detail" , "reg_no='".$dtlArry[0]['reg_no']."'" , true); 
                          // print_r($creditAmtArry);
                             array_shift($creditAmtArry);
                            $crdAmtTotl=0;
                            foreach($creditAmtArry  as $crdAmt ) {
                                          $crdAmtTotl=$crdAmtTotl+$crdAmt['amt'];
                                }
                        */
                             $dtlArry[]=$crdAmtTotl;
                             return $dtlArry;
                       }
}