<?php
	require_once("../inc/class.dbo.php");
	include_once("../inc/checklogin.inc.php");

	$obj = new DBO();
	
	$data=$obj->GetSingleRow("spssp_guest_category"," id=".$_POST['cat_id']);
	echo $comma_separated = implode("#", $data);
	
?>
