<?php
include("admin/inc/dbcon.inc.php");
include("admin/inc/class.dbo.php");
$obj = new DBO();
$post = $obj->protectXSS($_POST);
$roomid = $obj->GetSingleData("spssp_user", "room_id"," id = ".(int)$_SESSION['userid']);
$post['room_id'] = $roomid;
$post['user_id'] = (int)$_SESSION['userid'];
$post['creation_date'] = date("Y-m-d H:i:s");

$id = $obj->InsertData("spssp_plan",$post);

redirect("dashboard.php");
?>
