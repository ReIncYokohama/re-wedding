<?php
@session_start();
include_once("../inc/class.dbo.php");
$obj = new DBO();

$num_plan = $obj-> GetNumRows("spssp_admin","user_id = ".(int)$_POST['id']);
if($num_plan <= 0)
{
    echo "1";
}
else
{
	echo "0";
}
