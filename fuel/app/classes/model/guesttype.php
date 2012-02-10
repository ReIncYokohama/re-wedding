<?php
class Model_Guesttype extends Model_Crud{
  static $_table_name = "spssp_guest_type";
  static $_connection = "madmin";
  static $_result;

  static public function get_models(){
    if(static::$_result){
      return static::$_result;
    }
    static::$_result = static::find_all();
    return static::$_result;
  }

  static public function hash($key,$value_key){
    $guest_type_models = static::get_models();
    $arr = Core_Arr::func($guest_type_models,"to_array");
    $returnArr = array();
    foreach($arr as $obj)
      {
        $returnArr[$obj['id']]=$obj['name'];
      }
    return $returnArr;
  }
  
}
