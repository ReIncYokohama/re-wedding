<?php
	require_once("../admin/inc/class.dbo.php");

	$obj = new DBO();
	
	$data=$obj->GetSingleRow("spssp_message"," id=".$_POST['msg_id']);
	echo $comma_separated = $data['title'].'#'.str_replace('<br />','',$data['description']);
	
?>
