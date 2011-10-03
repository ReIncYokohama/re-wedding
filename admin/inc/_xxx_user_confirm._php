<?php
    require_once("admin/inc/dbcon.inc.php");
	require_once("admin/inc/class.dbo.php");
	$obj = new DBO();
	$id = (int)$_GET['id'];
	$value = $_GET['value'];
	if($id > 0)
	{
		$userinfo = $obj->GetSingleRow("spssp_user", "id = $id");
		$userval = md5($userinfo['mail_check_number']);
		if($userinfo['is_activated']==1)
		{
			echo "<script type='text/javascript'>alert('You are already Activated');</script>";
			redirect('index.php');
		}
		else if($value == $userval )
		{
			$qry = "update spssp_user set is_activated = 1 where id = $id";
			$result = mysql_query($qry);
			echo "<script type='text/javascript'>alert('Activation Successful');</script>";
			redirect("registration_2.php?id=$id");
		}		
	}
	else
	{
		redirect("index.php");
	}

?>
