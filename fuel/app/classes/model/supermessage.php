<?php
class Model_Supermessage extends Model_Crud{
  static $_connection = "madmin";
  static $_table_name = "super_admin_message";
  static $_fields = array( "id","description","show_it","display_order","attach_file","user_show",
                           "creation_date");
  static public function get_messages(){
    return static::find(array("where"=>array(array("show_it","=",1)),"order"=>array("id","desc")));
  }
}