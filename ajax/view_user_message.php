<?php
	require_once("../admin/inc/class.dbo.php");

	$obj = new DBO();
	$data=$obj->GetSingleRow("spssp_message"," id=".$_POST['id']);
	echo $comma_separated = implode("#", $data);
?>
