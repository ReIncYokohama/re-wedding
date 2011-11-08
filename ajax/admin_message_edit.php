<?php
	require_once("../admin/inc/class.dbo.php");

	$obj = new DBO();

	if ($_POST['is_stuff']==0) {
		$post['user_view']=1;
		$obj->UpdateData("spssp_admin_messages", $post, " id=".(int)$_POST['id']);
	}

	$data=$obj->GetSingleRow("spssp_admin_messages"," id=".$_POST['id']);
	echo $comma_separated = implode("#", $data);

?>
