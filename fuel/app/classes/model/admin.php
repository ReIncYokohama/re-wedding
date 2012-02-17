<?php
class Model_Admin extends Model_Crud{
  static $_table_name = "spssp_admin";
  static public function get_staffs(){
    $staffs = static::find(array("where" => array(array("permission","!=","111"))));
    return Core_Arr::func($staffs,"to_array");
  }
}

