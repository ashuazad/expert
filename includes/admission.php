<?php
class admission extends db{
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
         function getStudent( $aId = NULL , $regNo = NULL){
                       $sDtl= $this->getData( array('*')  ,  "admission" , "regno='".$regNo."' AND a_id='".$aId."'");
                      return   $sDtl[1];
             }    
		 public function getRegistration( $regNo = NULL ){
		 		return  $this->getData( array('a_id', 'roll_no', 'regno', 'course_fee', 
										'total_fee', 'due_fee', 'next_due_date', 'doj', 
										'name', 'email_id', 'phone', 'course', 'permanent_address','status')   ,  "admission" , "regno='".$regNo."'")[1];
		 }
}