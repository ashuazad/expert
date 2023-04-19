<?php
class course_module extends db{
         function  getModules( $course ){               
     return $this->getData( array('*')  ,  "course_module" , "course = '".$course."'");
               }            
         function  getModule( $id ){               
     return $this->getData( array('*')  ,  "course_module" , "module_id = '".$id."'");
               }            

}