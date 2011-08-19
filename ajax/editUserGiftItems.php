<?php
	include_once("../admin/inc/dbcon.inc.php");
	require_once("../admin/inc/class.dbo.php");

	$user_id=(int)$_SESSION['userid'];
	$obj = new DBO();
	//echo "<pre>";
	//print_r($_POST);
	
	if($_POST['editUserGiftItemsUpdate']=='editUserGiftItemsUpdate')
	{
		unset($_POST['editUserGiftItemsUpdate']);
		$number = count($_POST);
		$number = ($number/2);
		for($i=1;$i<=$number;$i++)
		{
			$array['name'] = $_POST['name'.$i];
			$obj->UpdateData("spssp_gift", $array," user_id=".$user_id." and id=".(int)$_POST['fieldId'.$i]);
		}
	
	}
	redirect("../hikidemono.php");
?>