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
  
  static public function find_by_not_takasago($user_id,$order_by=array()){
    $guests = Model_Guest::find(array(
      "where" => array(
        array("user_id","=",$user_id),
        array("self","!=",1),
        array("stage_guest","=",0)
      ),
      "order_by" => $order_by
    ));
    return $guests;
  }
  //sortby id,sex,guest_type
  //direction asc,desc
  static public function get_sort_property(){
    $return_arr = array();
    
    $sortby = Input::get("sortby");

    if(!in_array($sortby,array("id","sex","guest_type"))) $sortby = "id";
    
    $direction = Input::get("direction");
    if($direction!="desc") $direction = "asc";
    
    foreach(array("sex","guest_type") as $property){
      if($sortby==$property && $direction=="asc") $return_arr[$property."_direction"] = "desc";
      else $return_arr[$property."_direction"] = "asc";
    }
    
    foreach(array("direction","sortby") as $value){
      $return_arr[$value] = ${$value};
    }
    
    return $return_arr;
  }
  
  static public function find_by_takasago($user_id){
    $guests_takasago = Model_Guest::find(array(
      "where" => array(
        array("user_id","=",$user_id),
        array("stage_guest",">",0)
      ),
      "order_by" => "stage_guest"
    ));
    $guests_self = Model_Guest::find(array(
      "where" => array(
        array("user_id","=",$user_id),
        array("self", "=","1" )
      )
    ));
    return array_merge($guests_takasago,$guests_self);
  }
  
  
}
