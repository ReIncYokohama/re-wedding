<?php
class Model_Guestgroup extends Model_Crud{
  static $_table_name = "spssp_guest_gift";
  static $_fields = array("id","group_id","gift_id","user_id","guest_id");
  
  public static function delete_by_user_id($user_id,$group_id){
    $groups = Model_Guestgroup::find(array("where"=>array(array("user_id","=",$user_id),array("group_id","=",$group_id))));
    foreach($groups as $group){
      $group->delete();
    }
  }
  
}

