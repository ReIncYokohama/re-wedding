<?php
class Model_Userlog extends Model_Crud{
  static $_table_name = "spssp_user_log";
  static $_fields = array("id","user_id","admin_id","login_time","logout_time","date");

  protected function get_date($type){
    $date = Core_Date::create_from_string(substr($this->$type,0,10),"%Y-%m-%d");
    return $date;
  }
  public function get_login_date(){
    return $this->get_date("login_time");
  }
  public function get_logout_date(){
    return $this->get_date("logout_time");
  }
  static public function get_user_last_login($user_id){
    $user_log_arr = static::find(
                        array(
                              "where"=>array(
                                             array("user_id","=",$user_id),
                                             array("admin_id","=",0)
                              ),
                              "order_by"=>array("login_time"=>"DESC"),
                              "limit"=>1
                              )
                        );
    return $user_log_arr[0];
  }
  public function get_login_text(){
    $login = $this->get_login_date();
    $login_timestamp = $login->get_timestamp();
    $now_timestamp = mktime();
    $date = getdate($login_timestamp);
    //ログアウトをせず、次の日になった場合、ログイン中のフラグを消す。
    if(($this->logout_time == "0000-00-00 00:00:00" && $this->logout_time != $this->login_time) && 
       ($login_timestamp < $now_timestamp && $login_timestamp+24*60*60 > $now_timestamp)) {
      return "ログイン中";
    }else {
      $login = $this->get_login_date();
      $logout = $this->get_logout_date();
      if($logout) 
        return $logout->format("%m月%d日");
      if($login) 
        return $login->format("%m月%d日");
    }
  }
}

