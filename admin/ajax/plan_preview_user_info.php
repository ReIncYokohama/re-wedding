<?php
@session_start();
include_once('../inc/dbcon.inc.php');
include_once('../inc/class.dbo.php');
$obj = new DBO();
$post = $obj->protectXSS($_POST);
$plan_row = $obj->GetSingleRow("spssp_default_plan"," id=".(int)$post['id']);
echo $plan_row['row_number'].','.$plan_row['column_number'].','.$plan_row['seat_number'];
?>
