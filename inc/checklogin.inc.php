<?php
define('TIMEOUTLENGTH', '1200000'); // タイムアウト時間を２０分(単位：ミリセカンド)
define('USER_LOGIN_FILENAME','../_uaLog/');
define('USER_LOGIN_TIMEOUT',300000); // 更新時間を５分(単位：ミリセカンド)
@session_start();
//echo $_SESSION['adminid'];exit;
	if(trim($_SESSION['userid'])=='')
		{
			//@session_destroy();
			//redirect("index.php?action=required");
			redirect("logout.php");
		}
	if($_SESSION["hotel_id"]!=$HOTELID)
		{
			echo "<script> alert('他のホテルからの移動はできません'); </script>";
			redirect("logout.php");
		}
?>
