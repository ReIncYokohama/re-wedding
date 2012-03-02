<?php
class Model_Giftgroup extends Model_Crud{
  static $_table_name = "spssp_gift_group";

  public function get_gift_name(){
    $usergiftgroup = Model_Usergiftgroup::find_one_by(array("where"=>array("user_id","=",$this->user_id),array("group_id","=",$this->id)));
    if($usergiftgroup)
      $names = $usergiftgroup->get_gift_names();
    else $names = array();
    return $names;
  }
}

