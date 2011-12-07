<?php
session_start();
include_once("admin/inc/dbcon.inc.php");
include_once("admin/inc/class.dbo.php");

$obj = new dbo();

$post = $obj->protectXSS($_POST);
$userID = $post['userID'];
$password = $post['password'];

if(!isset($_SESSION['userid'])) {
	$query_string = "SELECT * from spssp_user WHERE BINARY user_id= '".$userID."' and BINARY password = '".$password."'";
	$result = mysql_query( $query_string );
	$row = mysql_fetch_array($result);
	
	// お客様ID利用期限日によるログイン制限
	$_limit = $obj->GetSingleData("spssp_options" ,"option_value" ," option_name='user_id_limit'");
	$_lm = (int)$_limit;
	$_pday = strtotime($row['party_day']);
	$_limitDate = strtotime("+".$_lm." day",$_pday);
//	echo $_lm." : ".$_pday." : ".$_limitDate." : ".strtotime(date("Y-m-d"));exit;
	if ($_limitDate<strtotime(date("Y-m-d"))) { // お客様ID利用期限日を過ぎた
		echo '<script type="text/javascript"> alert("お客様のＩＤ利用期限日が過ぎております\\nログインが必要な場合は、ホテル担当者にお問い合わせください"); </script>';
		redirect("logout.php");exit;
	}
	$_SESSION['userid'] = $row['id'];
}

include_once("inc/user_login_check.php");

if ($_SESSION['regenerate_user_id']=="") {
	redirect("logout.php");
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
