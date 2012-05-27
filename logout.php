<?php
@session_start();
include_once("fuel/load_classes.php");

$user_log = Model_Userlog::find_by_pk($_SESSION['user_log_id']);
if($user_log){
  $user_log->logout_time = date("Y-m-d H:i:s");
  $user_log->save();
}
Core_Session::user_unlink();
if(Core_Session::get_staff_id() > 0 || Core_Session::is_super()){
  echo "<script type='text/javascript'>";
  echo "if(!window.close()){location.replace('index.php');}";
  echo "</script>";
}else{
  header("Location:index.php");
  exit;
}