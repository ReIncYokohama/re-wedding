<?php
@session_start();
include_once("../fuel/load_classes.php");

if(!Core_Session::is_super()){
  $staff_id = Core_Session::get_staff_id();
  $admin = Model_Admin::find_by_pk($staff_id);
  if($amdin){
    $admin->updatetime = date("Y-m-d H:i:s");
    $admin->sessionid = "";
    $admin->save();    
  }
} 

$user_log = Model_Userlog::find_by_pk($_SESSION['user_log_id']);
if($user_log){
  $user_log->logout_time = date("Y-m-d H:i:s");
  $user_log->save();
}

Core_Session::admin_unlink();
Response::redirect("index.php");
?>
