<?php
class Model_Guest extends Model_Crud{
  static $_table_name = "spssp_guest";

  static public function exist($user_id = null){
    if($user_id == null) $user_id = Core_Session::get_user_id();
    
    $guests = static::find_by_user_id($user_id);
    if(!$guests){
      return false;
    }
    return true;
  }
  

}
