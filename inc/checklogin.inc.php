<?php
@session_start();
//echo $_SESSION['adminid'];exit;
	if(trim($_SESSION['userid'])=='')
		{
			
			@session_destroy();
			redirect("index.php?action=required");
		}
?>
