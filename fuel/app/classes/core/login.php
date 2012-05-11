<?php
class Core_Login{
  
  static public function get_file($user_id){
    return dirname(__FILE__)."/../../../".$user_id.".log";
  }
  
  static public function update($superuser,$staff_id){
    
  }
  static public function can_login($superuser,$staff_id){
    
  }
  static public function check_login_time(){
    $user_id = Core_Session::get_user_id();
    if(!$user_id){
      //loginしていない場合は、アラートが異なるため、ここではtrueで返す。
      return true;
    }
    print static::get_file($user_id);
  }
  
}