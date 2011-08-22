<?php
 @session_start();
 require_once("inc/class.dbo.php");
 $obj = new DBO();

if($_SERVER['HTTP_HOST']=='localhost')
	{
		$sqlhost='localhost';
		$sqluser='root';
		$sqlpassword="";
		$sqldatabase="spssp";
	}
	else
	{
		$sqlhost='localhost';
		$sqluser='wplus_hotel1_2';
		$sqlpassword="wph1_123456";
		$sqldatabase="wplus_hotel1_2";
	}
	


	$link = mysql_connect($sqlhost, $sqluser,$sqlpassword)
	or die("COULD NOT CONNECT : " . mysql_error());
	mysql_select_db($sqldatabase) or die("COULD NOT SELECT DATABASE");
	mysql_query("SET CHARACTER SET 'utf8'"); 
	mysql_query("SET NAMES 'utf8'");
	
	$sql="update spssp_admin set sessionid='',updatetime='".date("Y-m-d H:i:s")."' WHERE id='".$_SESSION['adminid']."';";
	mysql_query($sql);
		
	unset($_SESSION['adminid']);
	unset($_SESSION['user_type']);
	if(isset($_SESSION['userid']))
	{
		$user_log['logout_time'] = date("Y-m-d H:i:s");
 
 		$obj->UpdateData("spssp_user_log", $user_log, " id='".(int)$_SESSION['user_log_id']."'");
		unset($_SESSION['userid']);
		unset($_SESSION['useremail']);
		unset($_SESSION['user_log_id']);
		unset($_SESSION['cart']);
	}
	redirect("index.php");
	exit;
?>
