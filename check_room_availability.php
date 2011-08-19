<?php
@session_start();
include_once("admin/inc/class.dbo.php");
$obj = new DBO();
$post = $obj->protectXSS($_POST);
if(isset($post['uname']) && $post['uname']!="")
{
	$nr = $obj->GetNumRows("spssp_user"," user_id ='".$post['uname']."'");
	
	if($nr > 0)
	{
		echo "1";
	}
	else
	{
		echo "0";
	}
}
else
{
	$room_id = $post['room_id'];
	$marriage_day = $post['marriage_day'];
	$party_room_id = $post['party_room_id'];
	$party_day = $post['party_day'];
	
	$nr = $obj->GetNumRows("spssp_user"," (room_id =".$room_id." and marriage_day='".$marriage_day."') or (party_room_id =".$room_id." and party_day='".$marriage_day."') or (party_room_id =".$party_room_id." and party_day='".$party_day."') or (room_id =".$party_room_id." and marriage_day='".$party_day."')");
	
	if($nr > 0)
	{
		echo "1";
	}
	else
	{
		echo "0";
	}
}
?>