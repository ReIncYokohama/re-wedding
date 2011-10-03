<?php
@session_start();
$plan_id = (int)$_POST['plan_id'];
$page = (int)$_POST['page'];
$room_id = (int)$_POST['room_id'];

include_once("inc/class.dbo.php");
$obj = new DBO();

$details = $obj->getRowsByQuery("SELECT * FROM `spssp_default_plan_details` WHERE plan_id =".$plan_id);
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
