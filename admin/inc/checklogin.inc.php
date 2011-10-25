<?php
	if(trim($_SESSION['adminid'])=='')
		{
			@session_destroy();
			redirect("index.php?action=required");
		}
	if($_SESSION["hotel_id"]!=$HOTELID)
		{
			echo "<script> alert('他のホテルからの移動はできません'); </script>";
			@session_destroy();
			redirect("index.php?action=required");
		}
include_once("staff_login_check.php");
?>
