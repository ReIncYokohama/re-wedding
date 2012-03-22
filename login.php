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

if(!isset($_SESSION['userid'])) {
	$query_string = "SELECT * from spssp_user WHERE BINARY user_id= '".$userID."' and BINARY password = '".$password."'";
	$result = mysql_query( $query_string );
	$row = mysql_fetch_array($result);
	//ｒログインID,Passなしの挙動を追加
	if(!$row){
		echo '<script type="text/javascript"> alert("ログインIDかパスワードが間違っています。\n正しいログインIDとパスワードを入力してください。"); </script>';
		redirect("logout.php");exit;
	}
  $user = Model_User::find_by_pk($row["id"]);
  if($user->past_deadline_access()){
    print_r($user);
    echo '<script type="text/javascript"> alert("お客様のＩＤ利用期限日が過ぎております\\nログインが必要な場合は、ホテル担当者にお問い合わせください"); </script>';
		redirect("logout.php");exit;
  }
}

include_once("inc/user_login_check.php");

if ($_SESSION['regenerate_user_id']=="") {
	redirect("logout.php");
}
else if ($_GET['src'] == "my_guests") {
	redirect("my_guests.php");
}
else if ($_GET['src'] == "admin") {
	redirect("dashboard.php");
}
else {
$_SESSION["hotel_id"] =$HOTELID;

$query_string = "SELECT * from spssp_user WHERE BINARY user_id= '".$userID."' and BINARY password = '".$password."'";
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
      $_SESSION['adminid'] = 0;

			include_once("admin/inc/class.dbo.php");
			$obj = new DBO();

			$user_log['user_id']=(int)$_SESSION['userid'];
			$user_log['login_time'] = date("Y-m-d H:i:s");
			$user_log['logout_time'] = "";
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
}

?>
