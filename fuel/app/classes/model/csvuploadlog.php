<?php
/*
データベースにテーブルの追加とセッション値の追加

state　1のとき、まだお知らせの出力をする。0のとき、お知らせを表示しない。
hotel  1のとき、ホテルユーザ用のお知らせ。0のとき、ユーザ用のお知らせ。

セッションの値について
管理者333(adminid 1),一般ホテルユーザ 222,(adminid 2),一般ユーザ 222(adminid 2)  
これを
管理者333(adminid 1),一般ホテルユーザ 222,(adminid 2),一般ユーザ 222(adminid 0)
に変更した。  
*/

class Model_Csvuploadlog extends Model_Crud{
  static $_table_name = "guest_csv_upload_log";
  static $_fields = array("id","created_at","updated_at","state","user_id","hotel");
  
  static public function get_for_hotel(){
    $logs = static::find_by(array("hotel"=>1,"state"=>1));
    return $logs;
  }
  static public function get_for_user($user_id){
    $logs = static::find_by(array("hotel"=>0,"state"=>1,"user_id"=>$user_id));
    return $logs;
  }
  static public function get_messages_for_hotel($staff_id){
    $csvuploadlog = static::get_for_hotel();
    $msgs = array();
    foreach($csvuploadlog as $log){
      $msg = $log->get_message_for_hotel($staff_id);
      if($msg){
        array_push($msgs,$msg);
      }
    }
    return $msgs;
  }

  public function get_message_for_hotel($staff_id){
    $user = Model_User::find_by_pk($this->user_id);
    if($user->stuff_id != $staff_id) return false;
    $man_name = $user->get_image_html("thumb2/man_lastname.png");
    $woman_name = $user->get_image_html("thumb2/woman_lastname.png");
    $party_day = Core_Date::convert_month_and_date($user->party_day);
    
    return "<li><a href='user_dashboard.php?src=my_guests&user_id=".$this->user_id."' target='_blank'>".$party_day
      ." ".$man_name."・".$woman_name
      ."様の招待客リストデータがアップロードされました。</a></li>";
  }

  static public function get_messages_for_user($user_id){
    $csvuploadlog = static::get_for_user($user_id);
    $msgs = array();
    foreach($csvuploadlog as $log){
      $msg = $log->get_message_for_user();
      if($msg) array_push($msgs,$msg);
    }
    return $msgs;
  }

  public function get_message_for_user(){
    return "<li><a href='my_guests.php'>招待客リストデータが追加されました。</a></li>";
  }

  static public function log($user_id){
    $csvuploadlog = new Model_Csvuploadlog(array("user_id"=>$user_id,"hotel"=>0));
    $csvuploadlog->save();
    $csvuploadlog = new Model_Csvuploadlog(array("user_id"=>$user_id,"hotel"=>1));
    $csvuploadlog->save();
  } 
  
}
