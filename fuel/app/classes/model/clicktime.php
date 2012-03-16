<?php
class Model_Clicktime extends Model_Crud{
  static $_table_name = "spssp_clicktime";
  static $_fields = array("id","table_id", "user_id","kari_hachu","hon_hachu","hikide_zumi","print_irai","print_ok","hikide_irai");
  //kari_hachu,hon_hachu,hikide_zumi,print_irai,print_ok,hikide_irai
  public function get_date($type){
    $date = Core_Date::create_from_string(substr($this->$type,0,10),"%Y-%m-%d");
    return $date;
  }
  public function past_print_upload(){
    $karihachu_date = $this->get_date("kari_hachu");
    if(!$karihachu_date->past_date(-66)){
      //65日過ぎている。
      //切れる当日は過ぎてない
      return true;
    }
    return false;

  }
}
