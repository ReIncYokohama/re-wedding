<?php
class Model_Guesttype extends Model_Crud{
  static $_table_name = "spssp_guest_type";
  static $_connection = "madmin";
  static $_result;

  static public function find_all_to_array(){
    if(static::$_result){
      return static::$_result;
    }
    /*
    $guest_types = static::find_all();
    $returnArray = array();
    foreach($guest_types as $guest_type){
      array_push($returnArray,$guest_type->to_array());
    }
    return $returnArray;
    */
    static::$_result = Db::query("select * from ".static::$_table_name)->execute(static::$_connection)->as_array();
    return static::$_result;
  }
}
