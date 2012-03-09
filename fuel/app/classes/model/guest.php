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
  
  //get変数のsortbyおよびdirectionを調査して返す。
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
    if(!$guests_takasago) return $guests_self;
    return array_merge($guests_takasago,$guests_self);
  }
  
  public function get_image($image_name){
    return Core_Image::get_guest_image_dir_relative($this->user_id,$this->id).$image_name;
  }
  public function delete_seat(){
    $seats = Model_Userseat::find_by_guest_id($this->id);
    foreach($seats as $seat){
      $seat->delete();
    }
  }
}
