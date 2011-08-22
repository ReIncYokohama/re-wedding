<?php
	require_once("../inc/class.dbo.php");
	include_once("../inc/checklogin.inc.php");

	$obj = new DBO();
	if ($_POST['modify']==1) { // UCHIDA EDIT 11/08/19 既読をする、しないを確認
		$post['admin_viewed']=1;
		$obj->UpdateData("spssp_message", $post, " id=".(int)$_POST['id']);
	}
?>
