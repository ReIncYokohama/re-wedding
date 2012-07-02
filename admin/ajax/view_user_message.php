<?php
	require_once("../inc/class.dbo.php");

	$obj = new DBO();
	if ($_POST['modify']==1) {
		$post['admin_viewed']=1;
		$obj->UpdateData("spssp_message", $post, " id=".(int)$_POST['id']);
	}