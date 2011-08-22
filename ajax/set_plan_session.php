<?php
@session_start();
$plan_id = (int)$_POST['plan_id'];
if(isset($_POST['unset']) && $_POST['unset'] != '')
{
	unset($_SESSION['cart']);
}

include_once("../admin/inc/class.dbo.php");
$obj = new DBO();

$details = $obj->getRowsByQuery("SELECT * FROM `spssp_plan_details` WHERE plan_id =".$plan_id);
if(isset($_SESSION['cart']))
{
unset($_SESSION['cart']);
}
foreach($details as $dt)
{
	$seatid = $dt['seat_id'];
	$key = $seatid."_input";
	$val = "#".$seatid."_".$dt['guest_id'];
	
	$_SESSION['cart'][$key] = $val;
}

?>
