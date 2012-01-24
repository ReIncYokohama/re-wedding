<?php
class Model_Tablelayout extends Model_Crud{
  static $_table_name = "spssp_table_layout";

  static public function exist($user_id = null){
    if($user_id == null) $user_id = Core_Session::get_user_id();
    $data = static::find_by_user_id($user_id);
    if(!$data){
      return false;
    }
    return true;
  }
  
  static public function find_rows_distinct_order($user_id){
    $query = DB::query("select distinct row_order from ".static::$_table_name." where user_id = ".(int)$user_id);
    return $query->execute()->as_array();
  }
}
