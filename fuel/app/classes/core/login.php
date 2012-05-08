<?php

class Core_Login{
  static public $file = dirname(__FILE__)."/../../../../../admin/_staff_login.log";
  
  static public function log_update($superuser,$staff_id){
    if(file_exists(static::file)){
      print "test";
    }
    //file_put_contents($this->file,);
  }
  static public function can_log_login($superuser,$staff_id){
    
  }
}
