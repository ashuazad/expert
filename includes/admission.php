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
                $columns = array('a_id', 'roll_no', 'regno', 'course_fee', 
                'total_fee', 'due_fee', 'next_due_date', 'doj', 
                'name', 'email_id', 'phone', 'course', 'permanent_address','status',
                'IF(credit_amt >= total_fee, 0, 1) AS bill_pending', 'last_receipt_date') ;
		 		return  $this->getData(  $columns ,  "admission" , "regno='".$regNo."'")[1];
		 }

          public function getRegistrationRegnRollnoPhone( $data = array() ){
              $columns = array('a_id', 'roll_no', 'regno', 'course_fee', 
              'total_fee', 'due_fee', 'next_due_date', 'doj', 
              'name', 'email_id', 'phone', 'course', 'permanent_address','status',
              'IF(credit_amt >= total_fee, 0, 1) AS bill_pending', 'last_receipt_date', 'credit_amt',
              "IF(last_receipt_date='0000-00-00 00:00:00' OR last_receipt_date IS NULL, (SELECT recipt_date FROM `fee_detail` where  reg_no = regno order by recipt_date DESC limit 1), last_receipt_date) AS last_receipt_date"
              ) ;
              $whereStr = ''; 
              if (isset($data['regno']) && !empty($data['regno'])) {
                $whereStr .= " regno='".$data['regno']."' AND";
              }
              if (isset($data['rollNo']) && !empty($data['rollNo'])) {
                $whereStr .= " roll_no = '" .$data['rollNo']. "' AND";
              }
              if (isset($data['phoneNo']) && !empty($data['phoneNo'])) {
                $whereStr .= " phone = '".$data['phoneNo']."' AND";
              }
              $whereStr = rtrim($whereStr, 'AND');
            return  $this->getData(  $columns ,  "admission" , $whereStr)[1];
          }

         public function getRegistrationDetails($data = array())
         {
           $regData = $this->getRegistrationRegnRollnoPhone($data);
           if (empty($regData)) {
            return false;
           }
           $rows = $this->getData(array('course','full_name'),  "course_fee");
           array_shift($rows);
           $listOfCourses = array();
           foreach($rows as $row) {
            $listOfCourses[$row['course']] = $row['full_name'];
           }
           $regCourses = explode('+',$regData['course']);
           $regData['course'] = array();
           foreach($regCourses as $regCourse) {
            $regData['course'][] =  $listOfCourses[$regCourse];
           }
           //print_r($courseList);
           //$regData['course'] = ;
           return $regData;
         }
}