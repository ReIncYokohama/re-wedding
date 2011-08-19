<?php
	require_once("../inc/class.dbo.php");
	include_once("../inc/checklogin.inc.php");

	$obj = new DBO();
	
	$data=$obj->GetSingleRow("spssp_message"," id=".$_POST['msg_id']);
	echo $data['title'].'#'.str_replace("<br />","",$data['description']);
	
?>