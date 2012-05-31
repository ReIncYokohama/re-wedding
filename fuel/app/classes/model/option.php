<?php
class Model_Option extends Model_Crud{
  static $_table_name = "spssp_options";
  //デフォルトの本発注締切日
  static public function get_confirm_day_num(){
    return static::get_deadline_honhatyu();
  }
  static public function get_deadline_honhatyu(){
    $option = static::find_one_by_option_name("confirm_day_num");
    if($option["option_value"]) return $option["option_value"];
    return "0";    
  }
  static public function get_deadline_sekijihyo(){
    $option = static::find_one_by_option_name("limitation_ranking");
    if($option["option_value"]) return $option["option_value"];
    return "0";
  }
  static public function get_deadline_hikidemono(){
    $hikidemono_option = Model_Hikidemonooption::find_by();
    return $hikidemono_option[0]->order_deadline;
  }
  static public function get_deadline_access(){
    $option = static::find_one_by_option_name("user_id_limit");
    if($option["option_value"]) return $option["option_value"];
    return "0";
  }
}