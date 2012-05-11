<?php
class Core_Commonlogin{
  static public function check_login_time(){
    $data = static::parse_data();
    //違うユーザがログインしている。20分以内に他のユーザがログを更新している
    if($data and session_id()!=$data["session"] and $data["unixtime"]+60*20>mktime()){
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
    $file = static::get_file();
    $text = session_id()."#".mktime();
    file_put_contents($file,$text);
  }
  static public function destroy(){
    $file = static::get_file();
    unlink($file);
  }
}
