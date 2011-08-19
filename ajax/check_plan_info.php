<?php
@session_start();
include_once("../admin/inc/class.dbo.php");
$obj = new DBO();

$num_plan = $obj-> GetNumRows("spssp_plan","user_id = ".(int)$_SESSION['userid']);
if($num_plan <= 0)
{
echo "0";
}
else
{
	 echo "1";
}