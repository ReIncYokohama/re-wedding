<?php

class Core_Session{
  static public function get(){
    return $_SESSION;
  }
  static public function get_user_id(){
    return $_SESSION["userid"];
  }
  static public function is_admin(){
    if((int)$_SESSION["adminid"] > 0){
			return true;
		}else{
			return false;
		}
  }

}

