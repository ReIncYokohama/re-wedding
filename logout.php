<<<<<<< HEAD
<?php
 @session_start();
 include_once("admin/inc/class.dbo.php");
 $obj = new DBO();

 $user_log['logout_time'] = date("Y-m-d H:i:s");

 $obj->UpdateData("spssp_user_log", $user_log, " id=".(int)$_SESSION['user_log_id']);

$is_stuff = $_SESSION['userid_admin'];

if ($_SESSION['regenerate_user_id']!="") {
	$fileName = USER_LOGIN_DIRNAME.$_SESSION['userid'].".log";
	unlink($fileName);
}

 unset($_SESSION['userid']);
 unset($_SESSION['useremail']);
 unset($_SESSION['user_log_id']);
 unset($_SESSION['cart']);
 unset($_SESSION['lastlogintime']);
 unset($_SESSION['userid_admin']);
 unset($_SESSION['regenerate_user_id']);
 
// UCHIDA EDIT 11/08/16 スタッフならログオフで画面を閉じる
	if ($is_stuff == "") {
 		header("Location:index.php");
 		exit;
	}
	else {
		echo "<script type='text/javascript'>";
		echo "window.close();";
		echo "</script>";
	}
?>
=======
<?php
 @session_start();
 require_once("inc/class.dbo.php");
 $obj = new DBO();

	unset($_SESSION['super_adminid']);
	redirect("index.php");
	exit;
?>
>>>>>>> 64382511c28799ae195af7dca3a4bb2a83b38419
