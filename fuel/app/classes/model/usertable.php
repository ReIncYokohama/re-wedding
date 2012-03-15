<?php
//ユーザがテーブル名を変更する
class Model_Usertable extends Model_Crud{
  static $_table_name = "spssp_table_layout";
  static $_fields = array("id","user_id","table_id","name","visibility","display"
                          ,"plan_id","row_order","column_order","align","default_plan_id");
  protected static $_primary_key = 'id';
  static public function exist($user_id = null){
    if($user_id == null) $user_id = Core_Session::get_user_id();
    $data = static::find_by_user_id($user_id);
    if(!$data){
      return false;
    }
    return true;
  }
  
  static public function find_rows_distinct_order($user_id){
    $query = DB::query("select distinct row_order from ".static::$_table_name." where user_id = ".(int)$user_id);
    return $query->execute()->as_array();
  }
  public function get_user_seats(){
    $seats = Model_Seat::find_by_table_id($this->table_id);
    $returnArr = array();
    foreach($seats as $seat){
      $userseats = Model_Userseat::find(array("where"=>array(array("seat_id","=",$seat->id),array("plan_id","=",$this->plan_id))));
      foreach($userseats as $userseat){
        array_push($returnArr,$userseat);
      }
    }
    return $returnArr;
  }
  public function delete_user_seats(){
    $seats = Model_Seat::find_by_table_id($this->table_id);
    $returnArr = array();
    foreach($seats as $seat){
      $userseats = Model_Userseat::find(array("where"=>array(array("seat_id","=",$seat->id),array("plan_id","=",$this->plan_id))));
      foreach($userseats as $userseat){
        $userseat->delete();
      }
    }
  }
  public function change_display($display){
    if((int)$this->display == (int)$display) return;
    if($display){
      $display = 1;
    }else{
      $display = 0;
    }
    $this->display = $display;
    $this->save();
    if($this->display == 0){
      $this->delete_user_seats();
    }
  }
  public function get_table_name(){
    $table = Model_Table::find_by_pk($this->table_id);
    $table_details = $table->to_array();
    $adminusertable = Model_Adminusertable::find_one_by(array(array("default_table_id","=",$this->id),array("user_id","=",$this->user_id)));
    $tblname = "";
    if($this->name != "")
      {
        $tblname = $this->name;
      }
    else if($adminusertable)
      {
        $tablename = Model_Tablename::find_by_pk($adminusertable->table_name_id);
        $tblname = $tablename->name;
      }
    return $tblname;
  }
}
