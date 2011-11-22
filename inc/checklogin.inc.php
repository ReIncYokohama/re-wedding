<?php

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
//include_once("user_login_check.php");

	if(trim($_SESSION['super_adminid'])=='')
		{
			
			@session_destroy();
			redirect("index.php?action=required");
		}
?>

