<?php

class Core_Session{
  static public function __get($key){
    return $_SESSION[$key];
  }
  static public function __set($key,$data){
    $_SESSION[$key] = $data;
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
  static public function get_seat_data(){
    if(isset($_SESSION['cart'])){
      return $_SESSION["cart"];
    }
    return false;
  }
  static public function set_seat_data($arr){
    $_SESSION["cart"] = $arr;
    
  }

}

