<?php
include_once("update_user_log.php");
	$fileName = USER_LOGIN_DIRNAME.$_SESSION['userid'].".log";
	echo "<script> alert('PHP'); </script>";
	update_user_log($fileName);
?>
