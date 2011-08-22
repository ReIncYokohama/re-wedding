<?php
    require_once("admin/inc/dbcon.inc.php");
	require_once("admin/inc/class.dbo.php");
	$obj = new DBO();
	$id = (int)$_GET['id'];
	$value = $_GET['value'];
	if($id > 0)
	{
		
		$userinfo = $obj->GetSingleRow("spssp_user", "id = ".$id);
		//echo "<pre>";
		//print_r($userinfo);exit;
		$userval = md5($userinfo['mail_check_number']);
		
		//echo $userval;exit;
		
		if($userinfo['is_activated']==1)
		{
			redirect('index.php');
		}
		else if($userval==$value )
		{
			//echo "b";exit;
			$qry = "update spssp_user set is_activated = 1 where id = $id";
			$result = mysql_query($qry);
			$_SESSION['username'] = $userinfo['user_id'];
			$_SESSION['userid'] = $userinfo['id'];
			redirect("registration_2.php?id=$id");
		}
		else
		{
			redirect("index.php");
		}		
	}
	else
	{
		redirect("index.php");
	}

?>
