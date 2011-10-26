<?php
include_once("../admin/inc/dbcon.inc.php");
session_start();
include_once("update_user_log.php");
	$fileName = "../".USER_LOGIN_DIRNAME.$_SESSION['userid'].".log";
//	echo $fileName;
	update_user_log($fileName);
?>
