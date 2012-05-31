<?php
include_once(dirname(__FILE__)."/../fuel/load_classes.php");
if(!Core_Login::check_login_time()){
  echo "<html><head><meta http-equiv='Content-Type' content='text/html; charset=UTF-8' /><script> alert('現在ホテルスタッフがログインしております');location.replace('index.php'); </script></head></body>";
  exit;
}else{
  Core_Login::write();
}
