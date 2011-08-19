<?php
	require_once("../admin/inc/class.dbo.php");
	include_once("../inc/checklogin.inc.php");

	$obj = new DBO();
	
	$post['user_view']=1;
	
	$obj->UpdateData("spssp_admin_messages", $post, " id=".(int)$_POST['id']);
	
	$data=$obj->GetSingleRow("spssp_admin_messages"," id=".$_POST['id']);
	echo $comma_separated = implode("#", $data);
	
?>