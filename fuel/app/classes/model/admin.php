<?php
class Model_Admin extends Model_Crud{
  static $_table_name = "spssp_admin";
  static $_fields = array( "id","name","username","email","password","permission",
                           "subcription_mail","display_order","sessionid","logintime","updatetime",
                           "limitation_ranking","stype");
  static public function get_staffs(){
    $staffs = static::find(array("where" => array(array("permission","!=","111"))));
    return Core_Arr::func($staffs,"to_array");
  }
}

