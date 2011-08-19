<?php
	require_once("../admin/inc/class.dbo.php");
	include_once("../inc/checklogin.inc.php");
	
	$user_id = (int)$_SESSION['userid'];
	$obj = new DBO();
	$arr['name'] = $_POST['name'];
	$r=$obj->UpdateData("spssp_table_layout", $arr, " user_id=".$user_id." and id = ".$_POST['id']);
	
	if($r)
	{
		echo mb_substr ($arr['name'], 0,1,'UTF-8');
	}
	
?>