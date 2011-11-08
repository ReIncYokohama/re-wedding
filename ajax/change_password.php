<?php
session_start();
include_once("../admin/inc/dbcon.inc.php");
include_once("../admin/inc/class.dbo.php");
$user_id = $_SESSION['userid'];
$obj = new DBO();

$post = $obj->protectXSS($_POST);

$action = $post['action'];

$cur_pass = $obj->GetSingleData("spssp_user", "password"," id=".$user_id);

if($action == 'check_user')
{
	
	if($cur_pass == $post['cur_pass'])
	{
		echo "1";
	}
	else
	{
		echo "0";
	}
	exit;
}
else if($action == 'change_pass')
{
	if($cur_pass == $post['cur_pass'])
	{
		$arr['password'] = $post['password'];
		$obj->UpdateData("spssp_user", $arr, " id=".$user_id);
		echo "1";
	}
	else
	{
		echo "0";
	}
	exit;
} 


?>
