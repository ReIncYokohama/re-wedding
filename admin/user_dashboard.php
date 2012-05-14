<?php
include_once("inc/dbcon.inc.php");
include_once("inc/checklogin.inc.php");
require_once("inc/class.dbo.php");
require_once("../fuel/load_classes.php");

$obj = new DBO();
$get = $obj->protectXSS($_GET);

$user_id = (int)$get['user_id'];
$user = Model_User::find_by_pk($user_id);
$user_info = $user->to_array();
	
$party_day = $user_info['party_day'];		
$ab = strtotime($party_day);
$limit_date = strtotime("+7 day",$ab);

if($party_day=="" || time()>$limit_date)
{
	redirect("manage.php");
}

if($_SESSION["userid"] && $_SESSION["userid"]!=$user_id && !$_GET["reload"]){
  print "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" /><script>if(confirm(\"同時に２名のお客様画面を操作できません。\\n２人目のお客様画面を開きますか？\\n\\n※ブラウザによりお客様画面が２つ開きます。\\n※１人目の画面が開いている場合はログアウトして画面を閉じてください。\")){ location.href = \"?reload=true&user_id=".$user_id."\";}else{ location.href = \"../dashboard.php\"}</script></head></html>";
  exit;
}else{
  //すでにログイン中のユーザをログアウトさせる
  $user_log = Model_Userlog::find_by_pk($_SESSION['user_log_id']);
  if($user_log){
    $user_log->logout_time = date("Y-m-d H:i:s");
    $user_log->save();
  }
  Core_Session::user_unlink();
}
$_SESSION['userid'] = $user_id;

$user_log['user_id']=(int)$user_id;
if(!Core_Login::check_login_time()){
  $_SESSION['userid'] = null;
  echo "<html><head><script> alert('既に同じ権限のユーザがログインされています');window.close(); </script></head></body>";
  exit;
}

$_SESSION['userid_admin']=$user_id;
$user_log['login_time'] = date("Y-m-d H:i:s");
$user_log['date'] = date("Y-m-d");
$user_log['admin_id'] = $_SESSION['adminid'];
if ($_SESSION["super_user"]==true) $user_log['admin_id']="10000".$user_log['admin_id'];

$id = $obj->InsertData("spssp_user_log", $user_log);

$_SESSION['user_log_id'] = $id;

//ここにログインするまでの処理書く
//ユーザがすでにログインしているかどうかチェックして大丈夫なら
if($_GET["src"]=="my_guests"){
  Response::redirect("../my_guests.php");
}else{
  Response::redirect("../dashboard.php");
}
	
?>