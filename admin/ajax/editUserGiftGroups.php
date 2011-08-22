<?php
	require_once("../inc/class.dbo.php");
	include_once("../inc/checklogin.inc.php");
	$user_id=$_GET['user_id'];
	$obj = new DBO();
	//echo "<pre>";
	//print_r($_POST);
	
	if($_POST['editUserGiftGroupsUpdate']=='editUserGiftGroupsUpdate')
	{
		unset($_POST['editUserGiftGroupsUpdate']);
		$number = count($_POST);
		$number = ($number/2);
		for($i=1;$i<=$number;$i++)
		{
			$array['name'] = $_POST['name'.$i];
			$obj->UpdateData("spssp_gift_group", $array," user_id=".$user_id." and id=".(int)$_POST['fieldId'.$i]);
		}
	
	}
	redirect("../gift_user.php?user_id=".$user_id);
	
	

?>
