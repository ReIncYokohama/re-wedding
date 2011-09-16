<?php

@session_start();
require_once("inc/class.dbo.php");
include_once(dirname(__FILE__)."/../conf/conf.php");

$obj = new DBO();

	$sqlhost=$hotel_sqlhost;
	$sqluser=$hotel_sqluser;
	$sqlpassword=$hotel_sqlpassword;
	$sqldatabase=$hotel_sqldatabase;

	mysql_connected($sqlhost,$sqluser,$sqlpassword,$sqldatabase);

if (!$_SESSION["super_user"]) {
	$sql="update spssp_admin set sessionid='',updatetime='".date("Y-m-d H:i:s")."' WHERE id='".$_SESSION['adminid']."';";
	mysql_query($sql);
}
	unset($_SESSION['adminid']);
	unset($_SESSION['user_type']);

  if(isset($_SESSION['userid']))
	{
		$user_log['logout_time'] = date("Y-m-d H:i:s");

 		$obj->UpdateData("spssp_user_log", $user_log, " id='".(int)$_SESSION['user_log_id']."'");
		unset($_SESSION['userid']);
		unset($_SESSION['useremail']);
		unset($_SESSION['user_log_id']);
		unset($_SESSION['cart']);
	}
  redirect("index.php");
?>
