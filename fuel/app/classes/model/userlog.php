<?php
class Model_Userlog extends Model_Crud{
  static $_table_name = "spssp_user_log";
  static $_fields = array("id","user_id","admin_id","login_time","logout_time","date");

  protected function get_date($type){
    $timestamp = strtotime($this->$type);
    return $timestamp;
  }
  public function get_login_timestamp(){
    return $this->get_date("login_time");
  }
  public function get_logout_timestamp(){
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
    $login_timestamp = $this->get_login_timestamp();
    $now_timestamp = mktime();
    //ログアウトをせず、次の日になった場合、ログイン中のフラグを消す。
    //ログインして12時間のみログイン中と表示される
    if(($this->logout_time == "0000-00-00 00:00:00" && $this->logout_time != $this->login_time) && 
       ($login_timestamp < $now_timestamp && $login_timestamp+60*60*12 > $now_timestamp)) {
      return "ログイン中";
    }else {
      $login = $this->get_login_timestamp();
      $logout = $this->get_logout_timestamp();
      if($logout > 1000) 
        return date("m月d日", $logout);
      if($login) 
        return date("m月d日", $login);
    }
  }
}

