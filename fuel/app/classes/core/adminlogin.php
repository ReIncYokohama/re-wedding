<?php
class Core_Adminlogin extends Core_Commonlogin{
  static public function get_staff_file($staff_id){
    return dirname(__file__)."/../../../../admin/_staff_log/".$staff_id.".log";
  }
  static public function get_admin_file(){
    return dirname(__file__)."/../../../../admin/_staff_login.log";
  }
  static public function get_file(){
    if(Core_Session::is_super()){
      $file = static::get_admin_file();
    }else{
      $staff_id = Core_Session::get_staff_id();
      $file = static::get_staff_file($staff_id);
    }
    return $file;
  }
}
