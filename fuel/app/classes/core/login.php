<?php
class Core_Login extends Core_Commonlogin{
  static public function get_file($user_id){
    $user_id = Core_Session::get_user_id();
    return dirname(__FILE__)."/../../../../_user_logs/".$user_id.".log";
  }
}