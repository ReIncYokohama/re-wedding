<?php

class Core_Session{
  /*
  static public function __get($key){
    return $_SESSION[$key];
  }
  static public function __set($key,$data){
    $_SESSION[$key] = $data;
  }
  */
  static public function get_user_id(){
    return $_SESSION["userid"];
  }
  //super or admin staff
  static public function is_admin(){
    if((int)$_SESSION["user_type"] == 333){
			return true;
		}
    return false;
  }
  static public function is_super(){
    if($_SESSION["super_user"]){
			return true;
		}
    return false;
  }
  //admin staff
  static public function is_admin_staff(){
    if((int)$_SESSION["user_type"] == 333 and !Core_Session::is_super()){
			return true;
		}
    return false;
  }
  //normal staff
  static public function is_normal_staff(){
    if($_SESSION["user_type"] == 222){
			return true;
		}
    return false;
  }

  //Model_Adminのidでもある
  static public function is_print_company(){
    if($_SESSION["printid"]>0){
      return true;
    }
    return false;
  }

  //normal staff and admin staff
  static public function is_staff(){
    if(Core_Session::is_admin_staff() || Core_Session::is_normal_staff()){
			return true;
		}else{
			return false;
		}
  }
  //新郎新婦がログインしたとき
  static public function is_user(){
    if(Core_Session::get_staff_id_in_user_display() == 0){
      return true;
    }
    return false;
  }

  //Model_Adminのidでもある
  static public function get_staff_id(){
    return (int)$_SESSION['staff_id'];
  }

  static public function get_staff_id_in_user_display(){
    return (int)$_SESSION['adminid'];
  }

  static public function get_print_id(){
    return (int)$_SESSION['printid'];
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
  static public function get_staff_name(){
    if(static::is_super()) return "印刷会社";
    $admin = Model_Admin::find_by_pk(static::get_staff_id());
    return $admin["name"];
  }
  static public function admin_unlink(){
    Core_Adminlogin::destroy();
    unset($_SESSION['adminid']);
    unset($_SESSION['user_type']);
    unset($_SESSION['hotel_id']);
    unset($_SESSION['regenerate_id']);
    unset($_SESSION['super_user']);
		unset($_SESSION['userid']);
		unset($_SESSION['useremail']);
		unset($_SESSION['user_log_id']);
		unset($_SESSION['cart']);
  }
  static public function user_unlink(){
    Core_Login::destroy();
    unset($_SESSION['userid']);
    unset($_SESSION['useremail']);
    unset($_SESSION['user_log_id']);
    unset($_SESSION['cart']);
    unset($_SESSION['lastlogintime']);
    unset($_SESSION['userid_admin']);
    unset($_SESSION['regenerate_user_id']);
  }
}

