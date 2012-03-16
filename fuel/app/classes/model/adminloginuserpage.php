<?php
class Model_User extends Model_Crud{
  static $_table_name = "spssp_user_log";
  static $_fields = array("id","user_id","admin_id","login_time","logout_time","date");
  static public function get_log_comment(){
    
  }
}
