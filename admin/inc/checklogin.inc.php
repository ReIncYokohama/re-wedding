<?php
	if(trim($_SESSION['adminid'])=='')
		{
			@session_destroy();
			redirect("index.php?action=required");
		}
	if($_SESSION["hotel_id"]!=$HOTELID)
		{
			echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script> alert("他のホテルからの移動はできません"); </script>
</head>
</heml>
';
			@session_destroy();
			redirect("index.php?action=required");
		}
	if ($_SESSION["user_type"] == 333) include_once("staff_login_check.php");
?>
