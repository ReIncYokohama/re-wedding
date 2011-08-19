<?php
@session_start();
include_once('../admin/inc/dbcon.inc.php');
include_once('../admin/inc/class.dbo.php');
$obj = new DBO();
$post = $obj->protectXSS($_POST);
echo $menuid =  $post['menuid'];
echo $guest_id = (int)$post['guest_id'];
$is_edit = (int)$post['is_edit'];

if($is_edit == 1)
{
	$obj->DeleteRow("spssp_guest_menu","  guest_id= $guest_id and user_id=".(int)$_SESSION['userid']);
}
else
{
	if($guest_id > 0)
	{
		$arr['user_id']= (int)$_SESSION['userid'];
		$arr['menu_id']=$menuid;
		$arr['guest_id'] = $guest_id;
		
		$lastid = $obj->InsertData("spssp_guest_menu",$arr); 
		
		echo $lastid;
	}
}
?>