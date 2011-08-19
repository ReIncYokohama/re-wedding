<?php
	require_once("../inc/class.dbo.php");
	include_once("../inc/checklogin.inc.php");

	$obj = new DBO();
	$num=$obj->GetNumRows("spssp_message", " user_id=".(int)$_POST['id']);
	echo $num+1;	
?>