<?php
@session_start();
include_once('../admin/inc/dbcon.inc.php');
include_once('../admin/inc/class.dbo.php');
$obj = new DBO();

$tablename= "spssp_table_layout";
if(isset($_POST['display']) && $_POST['display'] != '')
{
	$arr['display'] = (int)$_POST['display'];
	if((int)$_POST['display'] == 1)
		$arr['visibility'] = 1;
	else
		$arr['visibility'] = 0;
}
else
{
	$arr['visibility'] = (int)$_POST['visibility'];
}

$obj->UpdateData($tablename, $arr, " id=".(int)$_POST['id']);
?>
