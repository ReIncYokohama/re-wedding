<?php
@session_start();
include_once('../admin/inc/dbcon.inc.php');
include_once('../admin/inc/class.dbo.php');
$obj = new DBO();
$post = $obj->protectXSS($_POST);
$giftid =  $post['giftid'];
$group_id = (int)$post['group_id'];
$guest_id = (int)$post['guest_id'];
$is_edit = (int)$post['is_edit'];

if($is_edit == 1)
{
	$obj->DeleteRow("spssp_guest_gift","  guest_id= $guest_id and user_id=".(int)$_SESSION['userid']);
}
else
{
	if($guest_id > 0)
	{
		$arr['user_id']= (int)$_SESSION['userid'];
		$arr['gift_id']=$giftid;
		$arr['guest_id'] = $guest_id;
		$arr['group_id'] = $group_id;
		
		$lastid = $obj->InsertData("spssp_guest_gift",$arr); 
		
		echo $lastid;
	}
}
?>