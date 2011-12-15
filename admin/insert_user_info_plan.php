<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<?php
@session_start();
/*error_reporting(E_ALL);
ini_set('display_errors', 1);*/

require_once("inc/include_class_files.php");
include_once("inc/dbcon.inc.php");
include_once("inc/checklogin.inc.php");

$obj = new DBO();
$objInfo = new InformationClass(); // UCHIDA EDIT 11/09/02

$post = $obj->protectXSS($_POST);

$plan_product_name = $post['product_name'];
$plan_dowload_options = $post['dowload_options'];
$plan_print_size = $post['print_size'];
$plan_print_type = $post['print_type'];
$plan_party_day_for_confirm = $post['party_day_for_confirm'];
$plan_print_company = $post['print_company'];
$room_id = $post['room_id'];
unset($post['room_id']);
$current_room_id = $post['current_room_id'];
unset($post['current_room_id']);

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

	$party_day_for_confirm=$post['party_day_for_confirm'];
	$party_date_array=explode("-",$party_day_for_confirm);
	$day = $party_date_array[2];
	$month = $party_date_array[1];
	$year = $party_date_array[0];


	$confirm_date= mktime(0, 0, 0, $month, $day-$post['final_proof'], $year);
	unset($post['party_day_for_confirm']);

	$post['confirm_date'] = date("Y-m-d", $confirm_date);
	$obj->UpdateData("spssp_plan",$post," user_id=".$user_plan['user_id']);

	echo "<script> alert('お客様挙式情報が更新されました'); </script>";
  redirect("load_user_image.php?user_id=".(int)$_GET['user_id']);
	//redirect("user_info_allentry.php?user_id=".(int)$_GET['user_id']);
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

	$query_string="SELECT * FROM spssp_room where (status=1 and id=".$roomid.");";
	$rooms = $obj->getRowsByQuery($query_string);
	$post['row_number'] = (int)$rooms[0]['max_rows'];
	$post['column_number'] = (int)$rooms[0]['max_columns'];
	$post['seat_number'] = (int)$rooms[0]['max_seats'];
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
		
		$sql = "delete from spssp_user_table where user_id= ".(int)$_GET['user_id'].";";
		mysql_query($sql);
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
	if ($current_room_id == 0) {
		// 印刷会社へメール送信
		include("inc/main_dbcon.inc.php");
		$hcode=$HOTELID;
		$hotel_name = $obj->GetSingleData(" super_spssp_hotel ", " hotel_name ", " hotel_code=".$hcode);
		include("inc/return_dbcon.inc.php");
		$objMail = new MailClass();
		$r=$objMail->process_mail_user_newentry((int)$_GET['user_id'], $plan_print_company, $plan_product_name, $plan_dowload_options, $plan_print_size, $plan_print_type, $hotel_name, $room_id);
		
		echo "<script> alert('新しいお客様挙式情報が登録されました'); </script>";
	}
	else {
		echo "<script> alert('お客様挙式情報が更新されました'); </script>";
	}
	//redirect("user_info_allentry.php?user_id=".(int)$_GET['user_id']);
  redirect("load_user_image.php?user_id=".(int)$_GET['user_id']);
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
	$arr['rename_table']=$post['rename_table'];
	$arr['print_size']=$post['print_size'];
	$arr['print_type']=$post['print_type'];
	$arr['final_proof'] = $post['final_proof'];
	$obj->UpdateData("spssp_plan",$arr," user_id=".$user_plan['user_id']);
	echo "<script> alert('お客様挙式情報が更新されました'); </script>";
	//redirect("user_info_allentry.php?user_id=".(int)$_GET['user_id']);
  redirect("load_user_image.php?user_id=".(int)$_GET['user_id']);
}

?>
</head>
</html>