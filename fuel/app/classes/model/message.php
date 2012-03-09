<?php
class Model_Message extends Model_Crud{
  static $_table_name = "spssp_message";
  static $_fields = array( "id","user_id","title","description","msg_type","message_no","field_1",
                           "field_2","creation_date","display_order","admin_viewed","admin_id","attach","attach_file");
  static public function get_by_admin($whereArray=array()){
    array_push($whereArray,array("admin_viewed","=",0));
    return static::find(array("where"=>$whereArray));
  }
}
