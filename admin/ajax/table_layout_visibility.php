<?php
@session_start();
include_once('../inc/dbcon.inc.php');
include_once('../inc/class.dbo.php');
$obj = new DBO();
$post = $obj->protectXSS($_POST);

$align=$_POST[ralign];
$tablename= "spssp_table_layout";
if(isset($_POST['display']) && $_POST['display'] != '')
{
	$arr['display'] = (int)$_POST['display'];
}

$obj->UpdateData($tablename, $arr, " id=".(int)$_POST['id']);
?>
