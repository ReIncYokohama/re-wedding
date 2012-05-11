<?php
class Core_Adminlogin {
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
  static public function check_login_time(){
    $data = static::parse_data();
    //違うユーザがログインしている。20分以内に他のユーザがログを更新している
    if($data and session_id()!=$data["session"] and $data["unixtime"]+60*20<mktime()){
      return false;
    }
    //他のユーザがログイン
    if($data or session_id()!=$data["session"]){
      session_regenerate_id();
      static::write();
      return true;
    }
    //同じユーザがログイン
    static::write();
    return true;
  }
  static public function parse_data(){
    $file = static::get_file();
    $cont = file_get_contents($file);
    if(!$cont){
      return false;
    }
    $cont_arr = explode("#",$cont);
    return array(
                 "session" => $cont_arr[0],
                 "unixtime" => $cont_arr[1]
                 );
  }
  static public function write(){
    if(Core_Session::is_super()){
      $file = static::get_admin_file();
    }else{
      $staff_id = Core_Session::get_staff_id();
      $file = static::get_staff_file($staff_id);
    }
    $text = session_id()."#".mktime();
    file_put_contents($file,$text);
  }
}
