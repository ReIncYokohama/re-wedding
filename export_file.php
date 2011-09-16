<?php
@session_start();
include_once("admin/inc/class.dbo.php");
include_once("inc/checklogin.inc.php");

$obj = new DBO();
$get = $obj->protectXSS($_GET);
$user_id = (int)$_SESSION['userid'];

$user_info =  $obj->GetSingleRow("spssp_user", " id=".$user_id);
$plan_info =  $obj->GetSingleRow("spssp_plan", " user_id=".$user_id);
$default_layout_title = $obj->GetSingleData("spssp_options" ,"option_value" ," option_name='default_layout_title'");

$plan_id = $obj->GetSingleData("spssp_plan", "id","user_id=".$user_id);
$plan_row = $obj->GetSingleRow("spssp_plan", " id =".$plan_id);

$room_rows = $plan_row['row_number'];
$room_tables = $plan_row['column_number'];
$room_seats = $plan_row['seat_number'];
$num_tables = $room_rows * $room_tables;



$html .='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>

<body>';



$html .= '<table border="1">';
$html .= '<tr>
<td>テーブル番号</td>
<td>テーブル名</td>
<td>座席番号</td>
<td>姓</td>
<td>名</td>
<td>姓名</td>
<td>敬称</td>
<td>肩書</td>
<td>グループ</td>
<td> 区分</td>

</tr>';

$usertblrows = $obj->getRowsByQuery("select * from spssp_table_layout where user_id = ".(int)$user_id." order by id ASC");
$num_tables;

$o=1;$cl22 = "";
foreach($usertblrows as $tblRows)
{
	$usertblrows = $obj->getRowsByQuery("select * from spssp_default_plan_seat where table_id = ".(int)$tblRows['table_id']." order by id ASC");

	$new_name_row = $obj->GetSingleRow("spssp_user_table", "user_id = ".(int)$user_id." and default_table_id=".$tblRows['id']);
	if(isset($new_name_row) && $new_name_row['id'] !='')
	{
		$tblname_row = $obj->GetSingleRow("spssp_tables_name","id=".$new_name_row['table_name_id']);
		$tblname = $tblname_row['name'];

	}
	else
	{
		$tblname = $tblRows['name'];
	}
	$z=1;
	foreach($usertblrows as $usertbldata)
	{
		//echo "<pre>";print_r($usertbldata);
		$guesttblrows = $obj->getRowsByQuery("select * from spssp_plan_details where seat_id = ".(int)$usertbldata['id']." order by id ASC");
		if($guesttblrows[0]['guest_id'])
		{	$guest_info = $obj->GetSingleRow("spssp_guest","id=".$guesttblrows[0]['guest_id']." and user_id=".(int)$user_id);}
		//echo "<pre>";print_r($guest_info);
		//TableNumber

		if($guest_info['last_name'])
		{
			$value = chop($o);
			$html2 .= '<tr><td>'.$value.'</td>';
			//TableName
			if($guest_info['last_name'])
			$value = chop($tblname);
			else
			$value=chop("");

			//$value = chop("table".$o);
			$html2 .= '<td>'.$value.'</td>';
			//SeatNumber
			if($guest_info['last_name'])
			$value = chop("seat ".$z);
			else
			$value=chop("");


			$html2 .= '<td>'.$value.'</td>';
			if($z%$room_seats==0)
			{
				$z=1;
			}

			//LastName
			$value = chop($guest_info['last_name']);
			$html2 .= '<td>'.$value.'</td>';

			//FirstName
			$value = chop($guest_info['first_name']);
			$html2 .= '<td>'.$value.'</td>';



			//FullName
			$value = chop($guest_info['last_name'].$guest_info['first_name']);
			$html2 .= '<td>'.$value.'</td>';

			//respect

			include("admin/inc/main_dbcon.inc.php");
			$respect = $obj->GetSingleData("spssp_respect", "title","id=".$guest_info['respect_id']);
			include("admin/inc/return_dbcon.inc.php");
			$value = chop($respect);
			$html2 .= '<td>'.$value.'</td>';
			//com1 com2
			if($guest_info['comment1']&&$guest_info['comment2'])
				$value = chop($guest_info['comment1']."△".$guest_info['comment2']);
			elseif($guest_info['comment1'])
				$value = chop($guest_info['comment1']);
			elseif($guest_info['comment2'])
				$value = chop($guest_info['comment2']);

			$html2 .= '<td>'.$value.'</td>';


			// sex グループ
			if($guest_info['sex']=="Male")
				$value = chop("新郎側");
			elseif($guest_info['sex']=="Female")
				$value = chop("新婦側");

			$html2 .= '<td>'.$value.'</td>';

			//guest-type 区分
			include("admin/inc/main_dbcon.inc.php");
			$guest_type = $obj->GetSingleData("spssp_guest_type", "name","id=".$guest_info['guest_type']);
			include("admin/inc/return_dbcon.inc.php");
			$value = chop($guest_type);
			$html2 .= '<td>'.$value.'</td></tr>';

			$guest_info="";
			$z++;
		}
	}
	$o++;
}
	$guest_own_info = $obj->GetAllRowsByCondition("spssp_guest","(self=1 or guest_type!=0) and stage=1 and user_id=".(int)$user_id);
	//echo "<pre>";print_r($guest_own_info);
	$xxx=1;
	foreach($guest_own_info as $own_info)
	{
		//TableNumber
		$value = chop(0);
		/*if($xxx==2)
			$own_array[] = "\n\"$value\"";
		else
			$own_array[] = "\"$value\"";*/
		$html3 .= '<tr><td>'.$value.'</td>';

		//TableName
		//$value = chop($tblname);
		if(!empty($plan_info['layoutname']))
			$value = chop($plan_info['layoutname']);
		else
			$value = chop($default_layout_title);

		$html3 .= '<td>'.$value.'</td>';

		//SeatNumber
		$value = chop("seat ".$xxx);
		$html3 .= '<td>'.$value.'</td>';

		//LastName

		$value = chop($own_info['last_name']);
		$html3 .= '<td>'.$value.'</td>';

		//FirstName
		$value = chop($own_info['first_name']);
		$html3 .= '<td>'.$value.'</td>';


		//FullName
		$value = chop($own_info['last_name'].$own_info['first_name']);
		$html3 .= '<td>'.$value.'</td>';

		//respect
		include("admin/inc/main_dbcon.inc.php");
		$respect = $obj->GetSingleData(" spssp_respect", "title","id=".$own_info['respect_id']);
		include("admin/inc/return_dbcon.inc.php");
		$value = chop($respect);
		$html3 .= '<td>'.$value.'</td>';

		//com1 com2
		if($own_info['comment1']&&$own_info['comment2'])
			$value = chop($own_info['comment1']."△".$own_info['comment2']);
		elseif($own_info['comment1'])
			$value = chop($own_info['comment1']);
		elseif($own_info['comment2'])
			$value = chop($own_info['comment2']);

		$html3 .= '<td>'.$value.'</td>';

		// sex グループ
		if($own_info['sex']=="Male")
			$value = chop("新郎側");
		elseif($own_info['sex']=="Female")
			$value = chop("新婦側");

		$html3 .= '<td>'.$value.'</td>';

		//guest-type 区分

		include("admin/inc/main_dbcon.inc.php");
		$guest_type = $obj->GetSingleData("spssp_guest_type", "name","id=".$own_info['guest_type']);

		include("admin/inc/return_dbcon.inc.php");
		$value = chop($guest_type);
		$html3 .= '<td>'.$value.'</td></tr>';

		$xxx++;
	}

	$guest_notseat_info = $obj->GetAllRowsByCondition("spssp_guest"," self!=1  and stage!=1 and id NOT IN (select guest_id from spssp_plan_details) and user_id=".(int)$user_id);

	foreach($guest_notseat_info as $notseat_info)
	{
		//TableNumber
		$value = chop("");
		/*if($xxx==2)
			$own_array[] = "\n\"$value\"";
		else
			$own_array[] = "\"$value\"";*/
		$html4 .= '<tr><td>'.$value.'</td>';

		//TableName
		//$value = chop($tblname);
		$value = chop("");

		$html4 .= '<td>'.$value.'</td>';

		//SeatNumber
		$value = chop("");
		$html4 .= '<td>'.$value.'</td>';

		//LastName

		$value = chop($notseat_info['first_name']);
		$html4 .= '<td>'.$value.'</td>';

		//FirstName
		$value = chop($notseat_info['last_name']);
		$html4 .= '<td>'.$value.'</td>';


		//FullName
		$value = chop($notseat_info['first_name'].$notseat_info['last_name']);
		$html4 .= '<td>'.$value.'</td>';

		//respect

		include("admin/inc/main_dbcon.inc.php");
		$respect = $obj->GetSingleData(" spssp_respect", "title","id=".$notseat_info['respect_id']);
		include("admin/inc/return_dbcon.inc.php");
		$value = chop($respect);
		$html4 .= '<td>'.$value.'</td>';
		//com1 com2
		if($notseat_info['comment1']&&$notseat_info['comment2'])
			$value = chop($notseat_info['comment1']."△".$notseat_info['comment2']);
		elseif($notseat_info['comment1'])
			$value = chop($notseat_info['comment1']);
		elseif($notseat_info['comment2'])
			$value = chop($notseat_info['comment2']);

		$html4 .= '<td>'.$value.'</td>';

		// sex グループ
		if($notseat_info['sex']=="Male")
			$value = chop("新郎側");
		elseif($notseat_info['sex']=="Female")
			$value = chop("新婦側");

		$html4 .= '<td>'.$value.'</td>';

		//guest-type 区分

		include("admin/inc/main_dbcon.inc.php");
		$guest_type = $obj->GetSingleData("spssp_guest_type", "name","id=".$notseat_info['guest_type']);

		include("admin/inc/return_dbcon.inc.php");
		$value = chop($guest_type);
		$html4 .= '<td>'.$value.'</td></tr>';

		$xxx++;
	}

	$html .= $html3;
	$html .= $html2;
	$html .= $html4;

   $html .= '</table></body></html>';





//$p='<table style="background:#ccc;"><tr><td>Likhon</td></tr></table>';

// UCHIDA EDIT 11/07/28 ↓
/*
$date_array = explode('-', $user_info['party_day']);

if($user_info['id']<10)
$user_id_name="000".$user_info['id'];
else if($user_info['id']<100)
$user_id_name="00".$user_info['id'];
else if($user_info['id']<1000)
$user_id_name="0".$user_info['id'];

if(SCRIPT_VERSION<10)
$script_version="0".SCRIPT_VERSION;

$this_name = "0001_".$date_array[0].$date_array[1].$date_array[2]."_".$user_id_name."_".$script_version;
*/

	$today = date("Ymd_His"); 			// YYYYMMDD_HHMMSS の形式で現在日付時間を取得する
	$this_name= "Guest_list_$today";	// 最終形式に整形する

// UCHIDA EDIT 11/07/28 ↑

 $File = "cache/Yourexcel.html";
 $Handle = fopen($File, 'w');
 fwrite($Handle, $html);
 fclose($Handle);

 include_once('admin/inc/ExportToExcel.class.php');

	$exp=new ExportToExcel();
	$exp->exportWithPage($File,$this_name.".xls");


?>


