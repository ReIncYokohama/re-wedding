<?php
class Model_Room extends Model_Crud{
  static $_table_name = "spssp_room";
  static $_fields = array("id","name","max_rows","max_columns","max_seats","hotel_room_title",
                          "hotel_room_date","hotel_free_text","display_order","creation_date","status");
  
  
  //pre_deleteや、post_deleteを使う
  public function delete(){
    $adminplan = Model_Adminplan::find_one_by_room_id($this->id);
    $plan = Model_Plan::find_one_by_room_id($this->id);
    if(!$plan){
      $adminplan->delete();
      // REFERENCESにより、spssp_default_plan_tableも同時に削除される。
      //同様にspssp_default_plan_seatも削除される
      parent::delete();
      return true;
    }else{
      return false;
    }
  }
}
