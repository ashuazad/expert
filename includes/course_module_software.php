<?php
class course_module_software extends db{
         function  getModules( $course ){               
     return $this->getData( array('*')  ,  "course_module_software" , "course = '".$course."'");
               }            
         function  getModule( $id ){               
     return $this->getData( array('*')  ,  "course_module_software" , "module_id = '".$id."'");
               }            

}