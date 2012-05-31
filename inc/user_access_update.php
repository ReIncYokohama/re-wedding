<?php
include_once("../admin/inc/dbcon.inc.php");
session_start();
include_once("update_user_log.php");
if ($_SESSION['userid'] > 0 &&  isset($_SESSION['userid'])) {
  $fileName = "../".USER_LOGIN_DIRNAME.$_SESSION['userid'].".log";
	update_user_log($fileName);
}
?>
