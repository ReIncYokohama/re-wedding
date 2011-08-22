<?php
@session_start();
include_once('../admin/inc/dbcon.inc.php');
include_once('../admin/inc/class.dbo.php');
$obj = new DBO();

$tablename= "spssp_table_layout";

$arr['row_align'] = (int)$_POST['row_align'];

$obj->UpdateData($tablename, $arr, " user_id=".(int)$_SESSION['userid']." and row_order=".(int)$_POST['row_order']);
?>
