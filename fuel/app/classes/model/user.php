<?php
class Model_User extends Model_Crud{
  static $_table_name = "spssp_user";
  static public function past_deadline_sekijihyo($user_id){
    $user = static::find_by_pk($user_id);
    $date = Core_Date::create_from_string($user->party_day,"%Y-%m-%d");
    if($date->past_date($user->limitation_ranking*-1)) return false;
    return true;
  }
}
