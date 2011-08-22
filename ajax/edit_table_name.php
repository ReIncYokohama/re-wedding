<?php
@session_start();
include_once('../admin/inc/dbcon.inc.php');
include_once('../admin/inc/class.dbo.php');
$obj = new DBO();
$post = $obj->protectXSS($_POST);
$tnid = $post['tnid'];
$id  = $post['id'];

$arr['default_table_id']=$id;
$arr['user_id']=(int)$_SESSION['userid'];
$arr['table_name_id'] = $tnid;




$newid = $obj->GetSingleData("spssp_user_table","id", " user_id=".(int)$_SESSION['userid']." and default_table_id = $id");

if(isset($newid) && $newid > 0)
{
$obj->UpdateData("spssp_user_table", $arr, " id = $newid");
}
else
{
$lid =$obj->InsertData("spssp_user_table",$arr);
echo $lid;
}

?>

