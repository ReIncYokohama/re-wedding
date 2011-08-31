<?php
session_start();
include_once("admin/inc/dbcon.inc.php");
include_once("admin/inc/class.dbo.php");

$obj = new dbo();
$post = $obj->protectXSS($_POST);
$userID = $post['userID'];
$password = $post['password'];
 
$query_string = "SELECT * from spssp_user WHERE user_id= '".$userID."' and password = '".$password."'";

//echo $query_string;exit;
$result = mysql_query( $query_string );

$row = mysql_fetch_array($result);

if($row['id']){
  $party_day = $row['party_day'];
  $ab = strtotime($party_day);
  $limit_date = strtotime("+7 day",$ab);
  
  if(time() <= $limit_date)
		{
			$_SESSION['username'] = $row['user_id'];
			$_SESSION['userid'] = $row['id'];

			include_once("admin/inc/class.dbo.php");
			$obj = new DBO();

			$user_log['user_id']=(int)$_SESSION['userid'];
			$user_log['login_time'] = date("Y-m-d H:i:s");
			$user_log['date'] = date("Y-m-d");
			$id = $obj->InsertData("spssp_user_log", $user_log);
			$_SESSION['user_log_id'] = $id;

			if($row['user_code']!="jis90" && $row['user_code']!="jis04")
			{
				redirect("welcome.php");
			}
			else
			{
				redirect("dashboard.php");
			}
		}
		else
		{
			redirect("index.php?adminid=$userID&err=17"); // UCHIDA EDIT 11/08/17 ＩＤを再表示
		}
	}else{
		redirect("index.php?adminid=$userID&err=16"); // UCHIDA EDIT 11/08/17 ＩＤを再表示
	}
?>
