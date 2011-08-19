<?php
	require_once("../inc/class.dbo.php");
	include_once("../inc/checklogin.inc.php");

	$user_id=$_POST['user_id'];
	
	if($_SESSION['user_type'] == 222)
	{
		$msg_where = " admin_id=".(int)$_SESSION['adminid']." and  user_id='".$user_id."'";
	}
	else
	{
		$msg_where = "   user_id='".$user_id."'";
	}
	
	$obj = new DBO();
	$num=$obj->GetNumRows("spssp_admin_messages",$msg_where);
	echo $num+1;
?>