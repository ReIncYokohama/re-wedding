<?php
class Model_Usergiftgroup extends Model_Crud{
  static $_table_name = "spssp_gift_group_relation";
  public function add_gift_name(){
    $gift_ids = explode("|",$this->gift_id);
    $gifts = array();
    foreach($gift_ids as $gift_id){
      $gift = Model_Gift::find_by_pk($gift_id);
      array_push($gifts,$gift);
    }    
    $this->gifts = $gifts;
  }
  public function get_gift_names(){
    $this->add_gift_name();
    $names = array();
    foreach($this->gifts as $gift){
      if($gift->name != "") array_push($names,$gift->name);
    }
    return $names;
  }

}

