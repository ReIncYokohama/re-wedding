<?php
@session_start();
include_once('../inc/dbcon.inc.php');
include_once('../inc/class.dbo.php');
$obj = new DBO();

$arr['permission'] = 222;


$obj->UpdateData("spssp_admin", $arr, " permission = 333");

?>

