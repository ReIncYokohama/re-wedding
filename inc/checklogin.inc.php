<?php
define('TIMEOUTLENGTH', '1200000'); // タイムアウト時間を５分(単位：ミリセカンド)
@session_start();
//echo $_SESSION['adminid'];exit;
	if(trim($_SESSION['userid'])=='')
		{
			//@session_destroy();
			//redirect("index.php?action=required");
			redirect("logout.php");
		}
?>
