<?php
	require_once("../inc/class.dbo.php");
	include_once("../inc/checklogin.inc.php");

	$obj = new DBO();
	
	$data=$obj->GetSingleRow("spssp_menu_group"," id=".$_POST['group_id']);
	echo $comma_separated = implode("#", $data);
	
?>
