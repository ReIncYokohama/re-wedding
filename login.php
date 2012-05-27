<?php
session_start();
header("Content-type: text/html; charset=utf-8");

include_once("admin/inc/dbcon.inc.php");
include_once("admin/inc/class.dbo.php");
include_once("fuel/load_classes.php");

$obj = new dbo();

$post = $obj->protectXSS($_POST);
$userID = $post['userID'];
$password = $post['password'];

$_SESSION["hotel_id"] =$HOTELID;

$query_string = "SELECT * from spssp_user WHERE BINARY user_id= '".$userID."' and BINARY password = '".$password."'";
$result = mysql_query( $query_string );
$row = mysql_fetch_array($result);

if($row['id']){
  $user = Model_User::find_by_pk($row["id"]);
  if(!$user->past_deadline_access()){
			$_SESSION['userid'] = $row['id'];
      //adminidはstaffidのこと。なぜ？
      $_SESSION['adminid'] = 0;
      if(!Core_Login::check_login_time()){
        echo "<html><head><meta http-equiv='Content-Type' content='text/html; charset=UTF-8' /><script> alert('現在ホテルスタッフがログインしております');location.replace('index.php'); </script></head></body>";
        exit;
      }

      $userlog = new Model_Userlog();
      $userlog->user_id = (int)$_SESSION['userid'];
      $userlog->login_time = date("Y-m-d H:i:s");
			$userlog->logout_time = "";
      $userlog->date = date("Y-m-d");
      $userlog->save();
      
			$_SESSION['user_log_id'] = $userlog->id;

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
			redirect("index.php?adminid=$userID&err=2");
	}
}else{
		redirect("index.php?adminid=$userID&err=1");
}


?>
