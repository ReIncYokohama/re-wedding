<?php
//userのメッセージ
class Model_Message extends Model_Crud{
  static $_table_name = "spssp_message";
  static $_fields = array( "id","user_id","title","description","msg_type","message_no","field_1",
                           "field_2","creation_date","display_order","admin_viewed","admin_id","attach","attach_file");
  static public function get_by_admin($whereArray=array()){
    array_push($whereArray,array("admin_viewed","=",0));
    return static::find(array("where"=>$whereArray));
  }
  public function get_message(){
    $user = Model_User::find_by_pk($this->user_id);
    $man_name = $user->get_image_html("thumb2/man_lastname.png");
    $woman_name = $user->get_image_html("thumb2/woman_lastname.png");
    $party_day = Core_Date::convert_month_and_date($user->party_day);
    
    return "<li><a href='message_user.php?stuff_id=0&user_id=".$this->user_id.
      "' >".$party_day." ".$man_name." ".$woman_name." 様よりの未読メッセージがあります。</a></li>";
  }
  public function is_read(){
    if($this->admin_viewed and $this->admin_viewd != "0"){
      return true;
    }
    return false;
  }
}
