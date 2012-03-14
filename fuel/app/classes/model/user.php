<?php
class Model_User extends Model_Crud{
  static $_table_name = "spssp_user";
  private $_room;
  //state 仮発注時に日付を登録
  static $_fields = array("id","marriage_day","man_firstname","man_lastname","woman_firstname","woman_lastname","man_firstname_eng","man_lastname_eng","woman_firstname_eng","woman_lastname_eng","marriage_day_with_time","room_id","room_name","party_day_with_time","party_room_id","religion","contact_name","zip","address","fax","mail","confirm_day_num","limitation_ranking","order_deadline","user_id","password","stuff_id","user_code","creation_date","status","mail_check_number","man_respect_id","woman_respect_id","subcription_mail","is_activated","man_furi_lastname","man_furi_firstname","woman_furi_lastname","woman_furi_firstname","man_furi_firstname_eng","man_furi_lastname_eng","woman_furi_firstname_eng","woman_furi_lastname_eng","party_day","zip1","zip2","state","city","street","buildings","tel","mukoyoshi");
  
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
