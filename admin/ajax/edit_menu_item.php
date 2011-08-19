<?php
	require_once("../inc/class.dbo.php");
	include_once("../inc/checklogin.inc.php");

	$obj = new DBO();
	
	$data=$obj->GetSingleRow("spssp_menu"," id=".$_POST['item_id']);
	echo $comma_separated = implode("#", $data);
	
?>