<?php
class Model_User extends Model_Crud{
  static $_table_name = "spssp_user";
  private $_room;
 
  static public function past_deadline_sekijihyo($user_id){
    $user = static::find_by_pk($user_id);
    $date = Core_Date::create_from_string($user->party_day,"%Y-%m-%d");
    //締切日を過ぎた日なので１日足している。
    if($date->past_date(($user->limitation_ranking-1))) return false;
    return true;
  }

  public function get_deadline_honhatyu(){
    $date = Core_Date::create_from_string($this->party_day,"%Y-%m-%d");
    return new Core_Date($date->get_timestamp()-$this->confirm_day_num*60*60*24);
  }
  public function output_deadline_honhatyu(){
    $date = $this->get_deadline_honhatyu();
    return $date->format("%Y/%m/%d")."(".$date->get_wday().")";
  }

  public function get_deadline_sekijihyo(){
    $date = Core_Date::create_from_string($this->party_day,"%Y-%m-%d");
    return new Core_Date($date->get_timestamp()-$this->limitation_ranking*60*60*24);
  }
  
  public function output_deadline_sekijihyo(){
    $date = $this->get_deadline_sekijihyo();
    return $date->format("%Y/%m/%d")."(".$date->get_wday().")";
  }
  public function output_deadline_sekijihyo2(){
    $date = $this->get_deadline_sekijihyo();
    return $date->format("%Y年%m月%d日");
  }


  
  public function get_gaiji_arr(){
    return Model_Gaijiuser::get_by_user_id($this->id);
  }
  
  public function get_room_name(){
    $name = $this->room_name;
    if(!$name || $name == ""){
      $room = $this->get_room();
      return $room->name;
    }
    return $name;
  }
  
  public function get_room(){
    if($this->_room) return $this->_room;
    $this->_room = Model_Room::find_by_pk($this->room_id);
    return $this->_room;
  }
  public function get_staffname(){
    $admin = Model_Admin::find_by_pk($this->stuff_id);
    return $admin->name;
  }
}
