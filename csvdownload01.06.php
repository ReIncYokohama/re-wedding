<?php
@session_start();
include_once("admin/inc/class.dbo.php");
include_once("inc/checklogin.inc.php");

$obj = new DBO();
$this_name = time();
$get = $obj->protectXSS($_GET);
$user_id = (int)$_SESSION['userid'];

/*$entityArray2 = array(" HotelName , WeddingDate , WeddingTime , WeddingVenues , ReceptionDate, ReceptionTime , ReceptionHall , GroomName,  fullPhoneticGroom , BrideFullName , BrideFullPhonetic , Categories, ProductName ,  Printsize,tableArrangement , DataOutputTime , PlannerName , LayoutColumns , TableLayoutStages , Colortable , Max , NumberAttendance ");*/ 

$entityArray2 = array(" ホテル名 , 挙式日 , 挙式時間 , 挙式会場 , 披露宴日, 披露宴時間 , 披露宴会場 , 新郎姓名,  新郎姓名ふりがな , 新婦姓名 , 新婦姓名ふりがな , 商品区分, 商品名 ,  席次表サイズ,席次表配置 , データ出力日時 , プランナー名 , 卓レイアウト列数 , 卓レイアウト段数 , 卓色 , 一卓最大人数 , 列席者数 "); 

$entity=implode(",",$entityArray2); 
$entity = mb_convert_encoding("$entity", "SJIS", "UTF8");
$lines .= <<<html
$entity
html;

$user_info =  $obj->GetSingleRow("spssp_user", " id=".$user_id);
$stuff_info =  $obj->GetSingleRow("spssp_admin", " id=".$user_info['stuff_id']);
$room_info =  $obj->GetSingleRow("spssp_room", " id=".$user_info['room_id']);
$party_room_info =  $obj->GetSingleRow("spssp_party_room", " id=".$user_info['party_room_id']);
$plan_info =  $obj->GetSingleRow("spssp_plan", " user_id=".$user_id);


if($plan_info['print_size']==1)
{
	$print_size ="A3";
	$tableArrangement ="横";
}
else if($plan_info['print_size']==2)
{
	$print_size ="A3";
	$tableArrangement ="縦";
}
else if($plan_info['print_size']==3)
{
	$print_size ="A3";
	$tableArrangement ="横";
}
else if($plan_info['print_size']==4)
{
	$print_size ="A3";
	$tableArrangement ="縦";
}
////////////////////////////////
if($plan_info['dowload_options']==1)
{
	$dowload_options ="席次表";
	
}
else if($plan_info['dowload_options']==2)
{
	$dowload_options ="席札";
	
}
else if($plan_info['dowload_options']==3)
{
	$dowload_options ="席次表・席札";
	
}

$entityArray['HotelName']			="SPSSP";
$entityArray['WeddingDate']			=$user_info['marriage_day'];
$entityArray['WeddingTime']			=$user_info['marriage_day_with_time'];
$entityArray['WeddingVenues']		=$room_info['name'];
$entityArray['ReceptionDate']		=$user_info['party_day'];
$entityArray['ReceptionTime']		=$user_info['party_day_with_time'];
$entityArray['ReceptionHall']		=$party_room_info['name'];
$entityArray['GroomName']			=$user_info['man_firstname']." ".$user_info['man_lastname'];
$entityArray['fullPhoneticGroom']	="PhoneticGroom";
$entityArray['BrideFullName']		=$user_info['woman_firstname']." ".$user_info['woman_lastname'];
$entityArray['BrideFullPhonetic']	="BrideFullPhonetic";
$entityArray['Categories']			=$dowload_options;
$entityArray['ProductName']			=$plan_info['product_name'];
$entityArray['Printsize']			=$print_size;
$entityArray['tableArrangement']	=$tableArrangement;
$entityArray['DataOutputTime']		=date("Y/m/d");
$entityArray['PlannerName']			=$stuff_info['name'];
$entityArray['LayoutColumns']		=$plan_info['column_number'];
$entityArray['TableLayoutStages']	=$plan_info['row_number'];
$entityArray['Colortable']	        ="";
$entityArray['Max']					=$plan_info['seat_number'];
$entityArray['NumberAttendance']	=$plan_info['column_number']*$plan_info['row_number']*$plan_info['seat_number'];

$count = count($entityArray);
foreach($entityArray as $key=>$values)
{
	$cl[] = "\"$values\"";
}


	$cl2 = implode(",",$cl);
	$cl2 = $cl2."\n";
	$line = mb_convert_encoding("$cl2", "SJIS", "UTF8");
	$lines .= "\n";
	$lines .= $line;
//echo "<pre>";
//print_r($entityArray);
//exit;
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=${this_name}.csv");
echo $lines;
?>