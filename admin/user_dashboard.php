<?php
	include_once("inc/dbcon.inc.php");
	
	include_once("inc/checklogin.inc.php");
	require_once("inc/class.dbo.php");
	$obj = new DBO();
	$get = $obj->protectXSS($_GET);
	
	$user_id = (int)$get['user_id'];
	
	$user_info = $obj->GetSingleRow("spssp_user"," id=".$user_id);
	
	$party_day = $user_info['party_day'];		
	$ab = strtotime($party_day);
	$limit_date = strtotime("+7 day",$ab);
	
	if($party_day=="" || time()>$limit_date)
	{
		redirect("manage.php");
	}
	
	if(isset($_SESSION['userid']))
	{
		$user_log['logout_time'] = date("Y-m-d H:i:s");
 
 		$obj->UpdateData("spssp_user_log", $user_log, " id=".(int)$_SESSION['user_log_id']);
		unset($_SESSION['userid']);
		unset($_SESSION['useremail']);
		unset($_SESSION['user_log_id']);
		unset($_SESSION['cart']);
		unset($_SESSION['userid_admin']);
	}
	$_SESSION['userid'] = $user_id;
	
	$_SESSION['userid_admin']=$user_id;
	
	$user_log['user_id']=(int)$user_id;
	$user_log['login_time'] = date("Y-m-d H:i:s");
	$user_log['date'] = date("Y-m-d");
	$user_log['admin_id'] = $_SESSION['adminid'];
	$id = $obj->InsertData("spssp_user_log", $user_log);
	$_SESSION['user_log_id'] = $id;
	
	
	redirect("../dashboard.php");

?>
	