<?php
	include_once("inc/dbcon.inc.php");
	
	include_once("inc/checklogin.inc.php");

	require_once("inc/class.dbo.php");
	$obj = new DBO();
	
	$room_id = $_POST['id'];
	$room = $obj->GetSingleRow('spssp_room', 'id='.(int)$room_id);
	echo $room['max_rows'].','.$room['max_columns'].','.$room['max_seats'];
	
	/*$row_number = (int)$_POST['row_number'];
	$column_number = (int)$_POST['column_number'];
	$seat_number = (int)$_POST['seat_number'];
	$total = $row_number * $column_number * $seat_number;
	
	$room = $obj->GetSingleRow('spssp_room', 'id='.(int)$room_id);
	
		
	if($row_number > (int)$room['max_rows'])
	{
		echo "overrows";
		exit;
	}
	else if($column_number > (int)$room['max_columns'])
	{
		echo "overcolumns";
		exit;
	}
	else if($total > (int)$room['max_seats'])
	{
		echo "overtotals";
		exit;
	}*/

?>
