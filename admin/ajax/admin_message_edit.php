<?php
	require_once("../inc/class.dbo.php");
	include_once("../inc/checklogin.inc.php");

	$obj = new DBO();
	
	$data=$obj->GetSingleRow("spssp_admin_messages"," id=".$_POST['msg_id']);
	
	$desc = str_replace("<br />","",$data['description']);
	echo $data['title']."#".$desc;
	
?>