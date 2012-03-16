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
  print "<script>if(confirm(\"一人目を閉じていませんが、強制的にログインしますか??\")){ location.href = \"?reload=true&user_id=".$user_id."\";}else{ location.href = \"../dashboard.php\"}</script>";
  exit;
}else{
  if($user_id){
    $fileName = "../".USER_LOGIN_DIRNAME.$user_id.".log";
    @unlink($fileName);
    $fileName = "../".USER_LOGIN_DIRNAME.$_SESSION["user_id"].".log";
    @unlink($fileName);
  }
}

$_SESSION['userid'] = $user_id;
$_SESSION['userid_admin']=$user_id;

$user_log['user_id']=(int)$user_id;
$user_log['login_time'] = date("Y-m-d H:i:s");
$user_log['date'] = date("Y-m-d");
$user_log['admin_id'] = $_SESSION['adminid'];
if ($_SESSION["super_user"]==true) $user_log['admin_id']="10000".$user_log['admin_id'];

$id = $obj->InsertData("spssp_user_log", $user_log);

$_SESSION['user_log_id'] = $id;

if($_GET["src"]=="my_guests"){
  redirect("../login.php?src=my_guests");
}else{
	redirect("../login.php?src=admin");
}
	
?>
	
