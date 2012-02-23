<?php
class Model_Gaijiuser extends Model_Crud{
  static $_table_name = "spssp_gaizi_detail_for_user";
  static public function get_by_user_id($user_id){
    $man_firstname_gaijis = static::find(array("where"=>array(array("gu_id","=",$user_id),array("gu_trgt_type","=",0))));
    $man_lastname_gaijis = static::find(array("where"=>array(array("gu_id","=",$user_id),array("gu_trgt_type","=",1))));
    $woman_firstname_gaijis = static::find(array("where"=>array(array("gu_id","=",$user_id),array("gu_trgt_type","=",2))));
    $woman_lastname_gaijis = static::find(array("where"=>array(array("gu_id","=",$user_id),array("gu_trgt_type","=",3))));
    return array(
      Core_Arr::func($man_firstname_gaijis,"to_array"),
      Core_Arr::func($man_lastname_gaijis,"to_array"),
      Core_Arr::func($woman_firstname_gaijis,"to_array"),
      Core_Arr::func($woman_lastname_gaijis,"to_array")
    );
  }
}

