<?php
 @session_start();
 require_once("inc/class.dbo.php");
 $obj = new DBO();

	unset($_SESSION['super_adminid']);
	redirect("index.php");
	exit;
?>