<?php

class db{

		 

	static function getDBconnect(){
		$connection = mysql_connect(constant('MYSQL_DATABASE_SERVER'),constant('MYSQL_DATABASE_USER_NAME'),constant('MYSQL_DATABASE_PASSWORD'));
		//Connect to database
		if(!$connection) {
			throw new Exception('Unable to connect to database');
		}
		mysql_select_db(constant('MYSQL_DATABASE_NAME'), $connection);
	} 

	 

	public function dataInsert($data,$tblName){	

	//$data=array('empid'=>'EMp13','ename'=>'sunil','depno'=>20')

	 		$query="insert into ".$tblName." ("; //insert into  emp(

			$intCol=null;

			$intVal=") values ('";

			$indx=1;

			 foreach($data as $colu=>$value){

			 if($indx==count($data)){

			 $intCol.=$colu;//empid,ename,depno

			 $intVal.=$value."'";//) values ('EMp13','sunil','20'

			 }else{

			 $intCol.=$colu.",";//empid,ename,

			 $intVal.=$value."','";//) values ('EMp13','sunil','

			 }

			 $indx++;

			 } 

			  $query.=$intCol.$intVal." )";//insert into  emp(empid,ename,depno) values ('EMp13','sunil','20')

			 //echo $query;

			 $resu=mysql_query($query) or die(mysql_error()) ;

			if($resu){ return mysql_insert_id();}else{return 0;}

	 }	

		

		public function dataupdate($data,$tblName,$colname,$val)
		{
		$query="UPDATE ".$tblName." SET  "; 
		//$intCol=null;
		 $intSet=null;
			$indx=1;
			 foreach($data as $colu=>$value)
			 {
			 if($indx==count($data)){
			 $intSet.=$colu."='".$value."'";
			 //$intVal=$value."'";
			 }else{
			 $intSet.=$colu."='".$value."',";
			 	 }
			 $indx++;
			 }
			 $query.=$intSet." where ".$colname." = '".$val."'" ;
			 //echo $query;
			$resu=mysql_query($query) or die(mysql_error());
			 if($resu){ return 1;}else{return 0;} 
		}

		

		

		public function datadel($tblName,$intCol,$select)

		{

				for($i=0;$i<count($_POST[$select]);$i++)

			{  

	

			$mId=$_POST[$select][$i];

       		$query="delete from ".$tblName." where ".$intCol." = '".$mId."'";	

		  // echo $query;

			 $result=mysql_query($query) ;

	

			}

			 if($result){ return 1;}else{return 0;} 

		}

		public function delOne($tblName,$intCol,$vid)

		{

			$query="delete from ".$tblName." where ".$intCol." = '".$vid."'";	

		  // echo $query;

			 $result=mysql_query($query) ;

	

		

			 if($result){ return 1;}else{return 0;} 

		}

		

		

		

		public function getData($cols,$tblName,$whr=NULL , $debugQuery = false )

		{

		$query="select ";

		$intCol=NULL;

			$indx=1;

			 foreach($cols as $colu)

			 {

					if($indx==count($cols)){

				 		$intCol.=$colu;//empid,ename,depno

				 	} else {

				 		$intCol.=$colu.",";//empid,ename,
                                        }

				 $indx++;		

		 	 }

		

		if($whr===NULL)

		$query.=$intCol." from ".$tblName;

		else	 

		$query.=$intCol." from ".$tblName." where ".$whr;

		if($debugQuery){
                    echo $query;                      
                }
              
		$result=mysql_query($query) or die(mysql_error());

		$dat=array();

		$dat[]=mysql_num_rows($result);

			while($row=mysql_fetch_array($result,MYSQL_ASSOC)){

				$dat[]=$row;	

			}

		 return $dat;	

		}

}

?>