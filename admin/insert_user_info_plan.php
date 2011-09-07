<?php
@session_start();
/*error_reporting(E_ALL);
ini_set('display_errors', 1);*/

require_once('inc/checklogin.inc.php');
include_once('inc/dbcon.inc.php');
include_once('inc/class.dbo.php');
include_once("inc/class_information.dbo.php");

$obj = new DBO();
$objInfo = new InformationClass(); // UCHIDA EDIT 11/09/02

$post = $obj->protectXSS($_POST);

if($_GET['user_id'])
{
  $values['confirm_day_num'] = $_POST['confirm_day_num'];
  $obj->UpdateData("spssp_user",$values," id=".$_GET['user_id']);

  unset($_POST['confirm_day_num']);
}
//echo'<pre>';
//print_r($_REQUEST);
//exit;
$plan_id = (int)$post['plan_id'];
if($plan_id > 0)
{
  $plan_row = $obj->GetSingleRow("spssp_default_plan"," id=".(int)$post['plan_id']);
  $post['row_number'] = $plan_row['row_number'];
  $post['column_number'] = $plan_row['column_number'];
  $post['seat_number'] = $plan_row['seat_number'];
  $post['default_plan_id'] = (int)$post['plan_id'];

  $post['user_id'] = (int)$_GET['user_id'];

}
else
{

	$post['user_id'] = (int)$_GET['user_id'];
	$post['creation_date'] = date("Y-m-d H:i:s");
}
$roomid = $obj->GetSingleData("spssp_user", "room_id"," id = ".(int)$_GET['user_id']);
$post['room_id'] = $roomid;
unset($post['plan_id']);

//$post['dowload_options']=implode(",",$post['dowload_options']);
$post['creation_date'] = date("Y-m-d");
$post['staff_id'] = (int)$_SESSION['adminid'];

$user_plan =  $obj->GetSingleRow("spssp_plan"," user_id=".(int)$_GET['user_id']);


$plan_dt = $obj->GetSingleRow("spssp_plan_details"," plan_id=".(int)$user_plan['id']." limit 0,1");


if((int)$user_plan['user_id'] > 0 && empty($plan_dt))
{

	unset($post['creation_date']);
	if($post['row_number'] != $user_plan['row_number'] || $post['column_number'] != $user_plan['column_number']  )
	{

		$plan_row = $user_plan;

		$room_rows = (int)$post['row_number'];
		$room_tables = (int)$post['column_number'];
		$room_seats = $plan_row['seat_number'];

		$num_tables = $room_rows * $room_tables;
		$table_rows = $obj->getRowsByQuery("select * from spssp_default_plan_table where room_id = ".$plan_row['room_id']." order by id asc limit 0, $num_tables");
		$num_layouts = $obj->GetNumRows("spssp_table_layout","user_id= ".(int)$_GET['user_id']);

		if($num_layouts > 0)
		{
			$obj->DeleteRow("spssp_table_layout","user_id= ".(int)$_GET['user_id']);
		}

		$row_ord = 1;
		$column_ord = 1;
		$i = 1;
		$j = 1;
		for($i = 1; $i<= (int)$room_rows; $i++)
		{

			for($j=1; $j<= (int)$room_tables; $j++)
			{
				$tr = array_shift($table_rows);

				$lo_arr['plan_id'] = $plan_row['id'];
				$lo_arr['table_id'] = $tr['id'];

				$lo_arr['visibility'] = 1;
				$lo_arr['row_order'] = $i;
				$lo_arr['column_order'] = $j;
				$lo_arr['name'] = $objInfo->get_table_name($tr['name']);// name=id
				if(isset($post['plan_id']) && $post['plan_id'] != '')
				{
					$lo_arr['default_plan_id'] = (int) $post['plan_id'];
				}

				$lo_arr['user_id']= (int)$_GET['user_id'];

				$lid = $obj->InsertData("spssp_table_layout", $lo_arr);

				$sql = "select * from spssp_table_layout where user_id= ".(int)$_GET['user_id']." order by id DESC;";
				$data_rows=mysql_query($sql);
				$row=mysql_fetch_array($data_rows);
				$last_id=(int)$row['id'];

				$arr['default_table_id']=$last_id;
				$arr['user_id']=(int)$_GET['user_id'];
				$arr['table_name_id']=$tr['name']; // name=id
				$obj->InsertData("spssp_user_table", $arr);
			}

		}
	}
	$party_day_for_confirm=$post['party_day_for_confirm'];
	$party_date_array=explode("-",$party_day_for_confirm);
	$day = $party_date_array[2];
	$month = $party_date_array[1];
	$year = $party_date_array[0];


	$confirm_date= mktime(0, 0, 0, $month, $day-$post['final_proof'], $year);
	unset($post['party_day_for_confirm']);

	$post['confirm_date'] = date("Y-m-d", $confirm_date);
	unset($post['confirm_day_num']);
	$obj->UpdateData("spssp_plan",$post," user_id=".$user_plan['user_id']);

	redirect("user_info.php?user_id=".(int)$_GET['user_id']);
}
else if((int)$user_plan['user_id'] <= 0 && empty($plan_dt))
{
	$party_day_for_confirm=$post['party_day_for_confirm'];
	$party_date_array=explode("-",$party_day_for_confirm);
	$day = $party_date_array[2];
	$month = $party_date_array[1];
	$year = $party_date_array[0];


	$confirm_date= mktime(0, 0, 0, $month, $day-$post['final_proof'], $year);
	unset($post['party_day_for_confirm']);

	$post['confirm_date'] = date("Y-m-d", $confirm_date);
	unset($post['confirm_day_num']);

	$id = $obj->InsertData("spssp_plan",$post);


	if($id >0)
	{
		$plan_row = $obj->GetSingleRow("spssp_plan"," user_id=".(int)$_GET['user_id']);

		$room_rows = (int)$plan_row['row_number'];
		$room_tables = (int)$plan_row['column_number'];
		$room_seats = (int)$plan_row['seat_number'];

		$num_tables = $room_rows * $room_tables;
		$table_rows = $obj->getRowsByQuery("select * from spssp_default_plan_table where room_id = ".$plan_row['room_id']." order by id asc limit 0, $num_tables");

		$num_layouts = $obj->GetNumRows("spssp_table_layout","user_id= ".(int)$_GET['user_id']);

		if($num_layouts > 0)
		{
			$obj->DeleteRow("spssp_table_layout","user_id= ".(int)$_GET['user_id']);
		}

		$row_ord = 1;
		$column_ord = 1;
		$i = 1;
		$j = 1;
		for($i = 1; $i<= (int)$room_rows; $i++)
		{

			for($j=1; $j<= (int)$room_tables; $j++)
			{
				$tr = array_shift($table_rows);

				$lo_arr['plan_id'] = $plan_row['id'];
				$lo_arr['table_id'] = $tr['id'];

				$lo_arr['visibility'] = 1;
				$lo_arr['row_order'] = $i;
				$lo_arr['column_order'] = $j;
				$lo_arr['name'] = $objInfo->get_table_name($tr['name']); // name=id
				if(isset($post['plan_id']) && $post['plan_id'] != '')
				{
					$lo_arr['default_plan_id'] = (int) $post['plan_id'];
				}

				$lo_arr['user_id']= (int)$_GET['user_id'];

				$lid = $obj->InsertData("spssp_table_layout", $lo_arr);

				$sql = "select * from spssp_table_layout where user_id= ".(int)$_GET['user_id']." order by id DESC;";
				$data_rows=mysql_query($sql);
				$row=mysql_fetch_array($data_rows);
				$last_id=(int)$row['id'];

				$arr['default_table_id']=$last_id;
				$arr['user_id']=(int)$_GET['user_id'];
				$arr['table_name_id']=$tr['name']; // name=id
				$obj->InsertData("spssp_user_table", $arr);
			}

		}
	}
	redirect("user_info.php?user_id=".(int)$_GET['user_id']);
}
else if((int)$user_plan['user_id'] > 0 && !empty($plan_dt))
{

	$arr['name'] = $post['name'];

	$party_day_for_confirm=$post['party_day_for_confirm'];
	$party_date_array=explode("-",$party_day_for_confirm);
	$day = $party_date_array[2];
	$month = $party_date_array[1];
	$year = $party_date_array[0];


	 $confirm_date= mktime(0, 0, 0, $month, $day-$post['final_proof'], $year);

	$arr['confirm_date'] = date("Y-m-d", $confirm_date);



	$arr['product_name'] = $post['product_name'];
	$arr['dowload_options'] = $post['dowload_options'];
	$arr['print_size']=$post['print_size'];
	$arr['final_proof'] = $post['final_proof'];

	$obj->UpdateData("spssp_plan",$arr," user_id=".$user_plan['user_id']);
	redirect("user_info.php?user_id=".(int)$_GET['user_id']."&err=4");
}

?>
