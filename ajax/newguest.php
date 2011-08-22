<?php
include_once("../admin/inc/dbcon.inc.php");
if($_GET['first_name'] !='' )
{	
  $query_string="INSERT INTO spssp_guest (user_id,respect_id,sex, display_order,creation_date,last_name,first_name,guest_type,comment1,comment2,memo) VALUES ('".$user_id."','".$_GET['respect_id']."','".$_GET['sex']."','".time()."','".date("Y-m-d H:i:s")."','".jp_encode($_GET['last_name'])."','".jp_encode($_GET['first_name'])."','".$_GET['guest_type']."','".jp_encode($_GET['comment1'])."','".jp_encode($_GET['comment2'])."','".jp_encode($_GET['memo'])."');";
	mysql_query($query_string);
	redirect("../my_guests.php");
	exit;
}
?>
