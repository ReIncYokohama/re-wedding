<?php
include_once(dirname(__FILE__)."/../../fuel/load_classes.php");
if(!Core_Adminlogin::check_login_time()){
  echo "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" /><script> alert('既に同じIDでログインされています。');location.replace('index.php'); </script></head></body>";
  exit;
}else{
  Core_Adminlogin::write();
}
