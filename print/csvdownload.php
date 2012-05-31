<?php

@session_start();
include_once("../admin/inc/class_information.dbo.php");
include_once("../admin/inc/class_data.dbo.php");
include_once("../fuel/load_classes.php");

if($_SESSION['printid'] =='')
{
  //redirect("index.php");exit;
}

$obj = new DataClass();
$objInfo = new InformationClass();
$this_name = $HOTELID;
$get = $obj->protectXSS($_GET);

$user_id = $objInfo->get_user_id_md5( $_GET['user_id']);
//test script
//$user_id = $_GET["user_id"];

if($user_id>0)
{
	//OK
}
else
{
	exit;
}
function s($text){
  $text = chop($text);
  if($text=="") return "";
  return mb_convert_encoding($text,"SJIS","UTF8");
}
/*$entityArray2 = array(" HotelName , WeddingDate , WeddingTime , WeddingVenues , ReceptionDate, ReceptionTime , ReceptionHall , GroomName,  fullPhoneticGroom , BrideFullName , BrideFullPhonetic , Categories, ProductName ,  Printsize,tableArrangement , JIs_num, DataOutputTime , PlannerName , LayoutColumns , TableLayoutStages , Colortable , Max , NumberAttendance "); */

$lines = s("header", "SJIS", "UTF8");
$lines .= "\n";
$entityArray2 = array("ホテル名,挙式日,挙式時間,挙式会場,披露宴日,披露宴時間,披露宴会場,新郎姓名,新郎姓名ふりがな,新婦姓名,新婦姓名ふりがな,商品区分,商品名,席次表サイズ,席次表配置,字形,データ出力日時,プランナー名,高砂卓名,卓レイアウト列数,卓レイアウト段数,卓色,一卓最大人数,合計人数");

$entity=implode(",",$entityArray2);
$entity = s($entity);
$lines .= <<<html
$entity
html;

$user = Model_User::find_by_pk($user_id);
$user_info = $user->to_array();

$stuff_info =  $obj->GetSingleRow("spssp_admin", " id=".$user_info['stuff_id']);
$room_info =  $obj->GetSingleRow("spssp_room", " id=".$user_info['room_id']);
$party_room_info =  $obj->GetSingleRow("spssp_party_room", " id=".$user_info['party_room_id']);
$plan_info =  $obj->GetSingleRow("spssp_plan", " user_id=".$user_id);
$default_layout_title = $obj->GetSingleData("spssp_options" ,"option_value" ," option_name='default_layout_title'");
$table_data = $obj->get_table_data_detail_with_hikidemono($user_id);

$ver = $user_info['zip1'];
$ver++;

$sql = "update spssp_user set zip1 = ".$ver." where id = $user_id";
$result = mysql_query($sql);

$print_size ="";
if($plan_info['print_size']==2)
{
	$print_size ="B4";
}
else if($plan_info['print_size']==1)
{
	$print_size ="A3";
}
$tableArrangement ="";
if($plan_info['print_type']==1)
{
	$tableArrangement ="横";
}
else if($plan_info['print_type']==2)
{
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

include("../admin/inc/main_dbcon.inc.php");
$hcode=$HOTELID;
$hotel_name = $obj->GetSingleData("super_spssp_hotel ", " hotel_name ", " hotel_code=".$hcode);
$data =  $obj->GetSingleRow("super_spssp_hotel", " 1=1");
include("../admin/inc/return_dbcon.inc.php");

$layoutname = $obj->getSingleData("spssp_plan", "layoutname"," user_id= $user_id");
$default_layout_title = $obj->GetSingleData("spssp_options" ,"option_value" ," option_name='default_layout_title'");

$entityArray['HotelName']			= $hotel_name;
$entityArray['WeddingDate']			= ($user_info['marriage_day']=="0000-00-00")?"":strftime('%Y年%m月%d日',strtotime($user_info['marriage_day']));
$entityArray['WeddingTime']			= ($user_info['marriage_day_with_time']=="00:00:00")?"":mb_substr($user_info['marriage_day_with_time'],0,5);
$entityArray['WeddingVenues']		= $party_room_info['name'];
$entityArray['ReceptionDate']		= strftime('%Y年%m月%d日',strtotime($user_info['party_day']));
$entityArray['ReceptionTime']		= mb_substr($user_info['party_day_with_time'],0,5);
$entityArray['ReceptionHall']		= $room_info['name'];
$entityArray['GroomName']			= $user_info['man_lastname']." ".$user_info['man_firstname'];
$entityArray['fullPhoneticGroom']	= $user_info['man_furi_lastname']." ".$user_info['man_furi_firstname'];
$entityArray['BrideFullName']		= $user_info['woman_lastname']." ".$user_info['woman_firstname'];
$entityArray['BrideFullPhonetic']	= $user_info['woman_furi_lastname']." ".$user_info['woman_furi_firstname'];
$entityArray['Categories']			= $dowload_options;
$entityArray['ProductName']			= $plan_info['product_name'];
$entityArray['Printsize']			= $print_size;
$entityArray['tableArrangement']	= $tableArrangement;
$entityArray['JIs_num']				= strtoupper($user_info['user_code']);
$entityArray['DataOutputTime']		= date("Y年m月d日 g:i");
$entityArray['PlannerName']			= $stuff_info['name'];

$entityArray['TakasagoName']		= ($layoutname)?($layoutname=="null")?"":$layoutname:$default_layout_title;
$entityArray['LayoutColumns']		= $plan_info['column_number'];
$entityArray['TableLayoutStages']	= $plan_info['row_number'];
$entityArray['Colortable']	        = "";
$entityArray['Max']					= $plan_info['seat_number'];

$total_guest=count($table_data["attend_guests"]);
$guest_models_takasago = Model_Guest::find_by_takasago($user_id);
$takasago_guests = Core_Arr::func($guest_models_takasago,"to_array");
$entityArray['NumberAttendance']	= $total_guest+count($takasago_guests);

$count = count($entityArray);
foreach($entityArray as $key=>$values)
{
	$cl[] = "$values";
}

$cl2 = implode(",",$cl);
$cl2 = $cl2."\n";
$line = s($cl2);
$lines .= "\n";
$lines .= $line."\n";

$plan_id = $obj->GetSingleData("spssp_plan", "id","user_id=".$user_id);
$plan_row = $obj->GetSingleRow("spssp_plan", " id =".$plan_id);

$room_rows = $plan_row['row_number'];
$room_tables = $plan_row['column_number'];
$room_seats = $plan_row['seat_number'];
$num_tables = $room_rows * $room_tables;

$tableNumber=0;
for($i=0;$i<($room_tables*3);$i++)
{
	if($i%3==0)
	{
		$tableNumber++;
		$entityArraytable[$i] = "列".$tableNumber."卓テーブル番号";

	}
	if($i%3==1)
	{
		$entityArraytable[$i] = "列".$tableNumber." 卓名";

	}
	if($i%3==2)
	{
		$entityArraytable[$i] = "列".$tableNumber." 卓型";

	}
}

array_unshift($entityArraytable, "レイアウト");
$tblrows = $obj->getRowsByQuery("select distinct row_order from spssp_table_layout where user_id = ".(int)$user_id." order by id ASC");

$entityArraytable=implode(",",$entityArraytable);
$entitytable = mb_convert_encoding("$entityArraytable", "SJIS", "UTF8");
$lines .= "\n";
$lines .= mb_convert_encoding("tables\n", "SJIS", "UTF8");

$lines .= <<<html
$entitytable
html;

$z=1;
foreach($tblrows as $tblrow)
{
	$cl = "";
	$ralign = $obj->GetSingleData("spssp_table_layout", "align"," row_order=".$tblrow['row_order']." and user_id=".$user_id." limit 1");

	if($ralign == 'C')
	{$pos = "センタリング";/*$pos = "中央配置";*/}
	else if($ralign=='R'){$pos = "右寄せ";}
	else if($ralign=='L'){$pos = "左寄せ";}
	else if($ralign=='N'){$pos = "そのまま";}
	//CENTER//RIGHT//LEFT
	//$value = s($tblrow['row_order']);
	//$cl[] = "\"$value\"";

	$value = s($pos);
	$cl[] = "$value";

	$table_rows = $obj->getRowsByQuery("select * from spssp_table_layout where user_id = ".(int)$user_id." and row_order=".$tblrow['row_order']." order by  column_order asc");

	foreach($table_rows as $table_row)
	{
		$new_name_row = $obj->GetSingleRow("spssp_user_table", "user_id = ".(int)$user_id." and default_table_id=".$table_row['id']);

    if($table_row['name']!='')
      {
        $tblname = $table_row['name'];
      }
    elseif(is_array($new_name_row) && $new_name_row['id'] !='')
      {
        $tblname_row = $obj->GetSingleRow("spssp_tables_name","id=".$new_name_row['table_name_id']);
        $tblname = $tblname_row['name'];
      }

		//echo $table_row['table_id']."<br>";

		//$sql_check_guest ="select count(*) as count from spssp_plan_details as spd join spssp_default_plan_seat as sdps on spd.seat_id =  sdps.id where sdps.table_id = ".$table_row['table_id'];
		//$tbl_guest_num = $obj->getRowsByQuery($sql_check_guest);
		//print_r($tbl_guest_num[0]);
    if($table_row["display"]==1 and $table_row["visibility"] == 1)
		{

			$value = s($z);
			$cl[] = "$value";

			$value = s($tblname);
			$cl[] = "$value";

			$value = s("");
			$cl[] = "$value";
		}
		else
		{
			$value = s("-1");
			$cl[] = "$value";

			$value = s("");
			$cl[] = "$value";

			$value = s("");
			$cl[] = "$value";
		}

	$z++;
	}
//echo "<pre>";
//print_r($c11);
	for($k=0;$k<$x;$k++)
	{
		for($y=0;$y<$z;$y++)
		{
			$value = s($c11[$y][$k]);
			$c3[$y] = "$value";

		}
		$list = implode(",",$c3);
		if($k==0)
			$now[$k] =" ,".$list;
		else
			$now[$k] = "\n"." ,".$list;
	}


	$cl2 = implode(",",$cl);
	$cl2 = "\n".$cl2;

	for($r=0;$r<$x;$r++)
	{

		$cl2 .= $now[$r];
	}


	$line = $cl2;

	$lines .= $line;

}
$lines .= "\n\n";
/*$entityArrayGuests = array(" TableNumber , TableName , SeatNumber , LastName , FirstName, FullName , EUDC7 , Title, Address9 , EUDC10 , Groups , GuestType, Last EUDC13 , Private Character14,EUDC full name15 , EUDC title16 ");*/

$entityArrayGuests = array("テーブル番号,テーブル名,座席番号,姓,名,姓名,姓名外字番号,敬称,肩書,肩書外字番号,グループ,区分,外字姓,外字名,外字姓名,外字肩書");


$entityArrayGuests=implode(",",$entityArrayGuests);
$entityGuests = mb_convert_encoding($entityArrayGuests, "SJIS", "UTF8");

$lines .= mb_convert_encoding("guests\n", "SJIS", "UTF8");
$lines .= <<<html
$entityGuests
html;

$usertblrows = $obj->getRowsByQuery("select * from spssp_table_layout where user_id = ".(int)$user_id." order by id ASC");
$num_tables;

define("GAIJI_SQLHOST",$hotel_gaiji_sqlhost);
define("GAIJI_SQLUSER",$hotel_gaiji_sqluser);
define("GAIJI_SQLPASSWORD",$hotel_gaiji_sqlpassword);
define("GAIJI_SQLDATABASE",$hotel_gaiji_sqldatabase);

define("SQLHOST",$hotel_sqlhost);
define("SQLUSER",$hotel_sqluser);
define("SQLPASSWORD",$hotel_sqlpassword);
define("SQLDATABASE",$hotel_sqldatabase);


//gaiji 関連の関数
function getGaijis($gaiji_objs){
  $returnArray = array();
  mysql_connected(GAIJI_SQLHOST,GAIJI_SQLUSER,GAIJI_SQLPASSWORD,GAIJI_SQLDATABASE);
  $obj = new DataClass();
  for($i=0;$i<count($gaiji_objs);++$i){
    //preg_match("/(.*?)\.(.*?)/",$gaiji_objs[$i]["gu_char_img"],$matches);
    $data = $obj->GetSingleRow("spssp_gaizi_char_file", " gr_fname = \"".$gaiji_objs[$i]["gu_char_img"]."\"");
    array_push($returnArray,$data["gr_managed_code"]);
    //array_push($returnArray,$matches[1]);
  }
  mysql_connected(SQLHOST,SQLUSER,SQLPASSWORD,SQLDATABASE);
  return implode(" ",$returnArray);
}

//gaiji 関連の関数
function setStrGaijis($str,$gaiji_objs){  
  $strArray = explode(s("＊"),$str);
  $returnStr = "";
  for($i=0;$i<count($gaiji_objs);++$i){
    preg_match("/(.*?)\.(.*?)/",$gaiji_objs[$i]["gu_char_img"],$matches);
    $first = substr($matches[1],0,2);
    $second = substr($matches[1],2,2);
    $data = pack("c*",hexdec($first),hexdec($second));
    $returnStr .= $strArray[$i].$data;
  }
  $returnStr .= $strArray[count($strArray)-1];
  return $returnStr;
}



$o=1;$cl22 = "";
foreach($usertblrows as $tblRows)
{	//echo "<br>".$tblRows['table_id']."<br>";
  if(!$tblRows["display"]==1 or !$tblRows["visibility"] == 1){
    $o++;
    continue;
  }
	$usertblrows = $obj->getRowsByQuery("select * from spssp_default_plan_seat where table_id = ".(int)$tblRows['table_id']." order by id ASC");
	//echo "<pre>";print_r($usertblrows);
	$new_name_row = $obj->GetSingleRow("spssp_user_table", "user_id = ".(int)$user_id." and default_table_id=".$tblRows['id']);
    if($tblRows['name']!='')
      {
        $tblname = $tblRows['name'];
      }
    elseif(is_array($new_name_row) && $new_name_row['id'] !='')
      {
        $tblname_row = $obj->GetSingleRow("spssp_tables_name","id=".$new_name_row['table_name_id']);
        $tblname = $tblname_row['name'];
      }	
  $z=1;
  $sort_usettblrows = array();
  foreach($usertblrows as $data){
    $index = ($z%2==1)?($z+1)/2:count($usertblrows)/2+$z/2;
    $sort_usettblrows[(int)$index] = $data;
    $z+=1;
  }
  $z=1;
	for($i=1;$i<=count($sort_usettblrows);++$i)
	{
    $usertbldata = $sort_usettblrows[$i];
    
		//echo "<pre>";print_r($usertbldata);
		$guesttblrows = $obj->getRowsByQuery("select * from spssp_plan_details where seat_id = ".(int)$usertbldata['id']." and plan_id = ".$plan_id." order by id ASC");
    //$guesttblrows = $obj->getRowsByQuery("select * from spssp_plan_details where seat_id = ".(int)$usertbldata['id']." order by id ASC");
		if($guesttblrows[0]['guest_id'])
		{
      $guest_info = $obj->GetSingleRow("spssp_guest","id=".$guesttblrows[0]['guest_id']." and user_id=".(int)$user_id);
      
      if($guest_info["stage_guest"]!=0){
        $z+=1;
        continue;
      }
    }
		//echo "<pre>";print_r($guest_info);
		//TableNumber
		if(!empty($guest_info))
			$value = s($o);
		else{
      $z+=1;
      continue;
    }
			//$value = s("-1");

    $query_string = "SELECT * FROM spssp_gaizi_detail_for_guest WHERE guest_id = '".$guesttblrows[0]['guest_id']."'";
    $firstname_gaijis = $obj->getRowsByQuery($query_string." and gu_trgt_type=0 order by gu_char_position");
    $lastname_gaijis = $obj->getRowsByQuery($query_string." and gu_trgt_type=1 order by gu_char_position");
    $comment1_gaijis = $obj->getRowsByQuery($query_string." and gu_trgt_type=2 order by gu_char_position");
    $comment2_gaijis = $obj->getRowsByQuery($query_string." and gu_trgt_type=3 order by gu_char_position");

		$cl22[] = "\n$value";
		//TableName
		$value = s($tblname);
		//$value = s("table".$o);
		$cl22[] = "$value";

		//SeatNumber

		$value = s($z);//////"seat ".
		$cl22[] = "$value";
		if($z%$room_seats==0)
		{
			$z=0;
		}

		//LastName
		$value = s($guest_info['last_name']);
		$cl22[] = "$value";

		//FirstName
		$value = s($guest_info['first_name']);
		$cl22[] = "$value";

		//FullName
		$value = s($guest_info['last_name']." ".$guest_info['first_name']);
		$cl22[] = "$value";

		//FullName gaiji
    $gaiji_name_arr = array_merge($lastname_gaijis,$firstname_gaijis);
		$value = s(getGaijis($gaiji_name_arr));
		$cl22[] = "$value";

		//respect
    $respectname = $obj->get_respect($guest_info["respect_id"]);
		$value = s($respectname);
		$cl22[] = "$value";

		//com1 com2
		if($guest_info['comment1']&&$guest_info['comment2'])
			$value = s($guest_info['comment1'].'△'.$guest_info['comment2']);
		elseif($guest_info['comment1'])
			$value = s($guest_info['comment1']);
		elseif($guest_info['comment2'])
			$value = s($guest_info['comment2']);
    else $value = "";

		$cl22[] = "$value";

		//com1 com2
    $gaiji_comment_arr = array_merge($comment1_gaijis,$comment2_gaijis);
		$value = s(getGaijis($gaiji_comment_arr));
		$cl22[] = "$value";

		// sex グループ
		if($guest_info['sex']=="Male")
			$value = s("新郎側");
		elseif($guest_info['sex']=="Female")
			$value = s("新婦側");
    else $value = "";

		$cl22[] = "$value";

		//guest-type 区分
    include("../admin/inc/main_dbcon.inc.php");
    $guest_type_obj = Model_Guesttype::find_by_pk($guest_info['guest_type']);
    $guest_type = $guest_type_obj["name"];
		$value = s($guest_type);
		$cl22[] = "$value";
    include("../admin/inc/return_dbcon.inc.php");

		//LastName	外字
		$value = s($guest_info['last_name']);
		$cl22[] = setStrGaijis($value,$lastname_gaijis);

		//FirstName	 外字名
		$value = s($guest_info['first_name']);
		$cl22[] = setStrGaijis($value,$firstname_gaijis);


		//外字姓名FullName
		$value = s($guest_info['last_name']." ".$guest_info['first_name']);
		$cl22[] = setStrGaijis($value,$gaiji_name_arr);

		//com1 com2
		if($guest_info['comment1']&&$guest_info['comment2']){
      $comment_gaijis = array_merge($comment1_gaijis,$comment2_gaijis);
			$value = s($guest_info['comment1'].'△'.$guest_info['comment2']);
      $cl22[] = setStrGaijis($value,$comment_gaijis);
		}elseif($guest_info['comment1']){
			$value = s($guest_info['comment1']);
      $cl22[] = setStrGaijis($value,$comment1_gaijis);
		}elseif($guest_info['comment2']){
			$value = s($guest_info['comment2']);
      $cl22[] = setStrGaijis($value,$comment2_gaijis);
    }else $cl22[] = "";
		$guest_info="";
		$z++;
	}
	$o++;
}
$order = 'desc';
if($user->mukoyoshi) $order = '';
$self_arr = $obj->GetAllRowsByCondition("spssp_guest","self=1  and user_id=".(int)$user_id.' order by sex '.$order);
$takasago_arr = $obj->GetAllRowsByCondition("spssp_guest","self!=1 and stage=1 and user_id=".(int)$user_id." and stage_guest = 5");
if(!$takasago_arr) $takasago_arr = array();
$guest_own_info = array_merge($self_arr,$takasago_arr);
$takasago_arr = $obj->GetAllRowsByCondition("spssp_guest","self!=1 and stage=1 and user_id=".(int)$user_id." and stage_guest < 5 order by stage_guest");
if(!$takasago_arr) $takasago_arr = array();
$guest_own_info = array_merge($guest_own_info,$takasago_arr);
  
	$xxx=1;
	foreach($guest_own_info as $own_info)
	{
		//TableNumber
		$value = s(0);
		$own_array[] = "\n$value";

    if($own_info["self"] == 1){
     $query_string = "SELECT * FROM spssp_gaizi_detail_for_user WHERE gu_id = '$user_id'";
     if($own_info["sex"]=="Male"){
       //men
       $firstname_gaijis = $obj->getRowsByQuery($query_string." and gu_trgt_type=0");
       $lastname_gaijis = $obj->getRowsByQuery($query_string." and gu_trgt_type=1");
       $comment1_gaijis = array();
       $comment2_gaijis = array();
     }else{
       $firstname_gaijis = $obj->getRowsByQuery($query_string." and gu_trgt_type=2");
       $lastname_gaijis = $obj->getRowsByQuery($query_string." and gu_trgt_type=3");
       $comment1_gaijis = array();
       $comment2_gaijis = array();
     }
    }else{
      $query_string = "SELECT * FROM spssp_gaizi_detail_for_guest WHERE guest_id = '".$own_info['id']."'";
      $firstname_gaijis = $obj->getRowsByQuery($query_string." and gu_trgt_type=0 order by gu_char_position");
      $lastname_gaijis = $obj->getRowsByQuery($query_string." and gu_trgt_type=1 order by gu_char_position");
      $comment1_gaijis = $obj->getRowsByQuery($query_string." and gu_trgt_type=2 order by gu_char_position");
      $comment2_gaijis = $obj->getRowsByQuery($query_string." and gu_trgt_type=3 order by gu_char_position");
    }

		//TableName
		//$value = s($tblname);
		if(!empty($plan_info['layoutname']))
			$value = s($plan_info['layoutname']);
		else
			$value = s($default_layout_title);
    if($value=="null") $value = "";
		$own_array[] = "$value";

		//SeatNumber
		$value = s($xxx);/////////"seat ".
    if($own_info["self"]!=1){
      switch($own_info["stage_guest"]){
        case "1":
          $value = s(4);
          break;
        case "2":
          $value = s(5);
          break;
        case "3":
          $value = s(6);
          break;
        case "4":
          $value = s(7);
          break;
        case "5":
          $value = s(3);
          break;
      }
    }

		$own_array[] = "$value";

		//LastName
		$value = s($own_info['last_name']);
		$own_array[] = "$value";

		//FirstName
		$value = s($own_info['first_name']);
		$own_array[] = "$value";


		//FullName
		$value = s($own_info['last_name']." ".$own_info['first_name']);
		$own_array[] = "$value";

		//FullName gaiji
    $gaiji_name_arr = array_merge($lastname_gaijis,$firstname_gaijis);
		$value = s(getGaijis($gaiji_name_arr));
		$own_array[] = "$value";

		//respect
    if($own_info["self"]==1){
      $respectname = "様";
    }else{
      $respectname = $obj->get_respect($own_info["respect_id"]);
    }
		$value = s($respectname);
		$own_array[] = "$value";

		//com1 com2
		if($own_info['comment1']&&$own_info['comment2'])
			$value = s($own_info['comment1'].'△'.$own_info['comment2']);
		elseif($own_info['comment1'])
			$value = s($own_info['comment1']);
		elseif($own_info['comment2'])
			$value = s($own_info['comment2']);
    else $value = "";

		$own_array[] = "$value";

		//com1 com2
    $gaiji_comment_arr = array_merge($comment1_gaijis,$comment2_gaijis);
		$value = s(getGaijis($gaiji_comment_arr));
		$own_array[] = "$value";

		// sex グループ
		if($own_info['sex']=="Male")
			$value = s("新郎側");
		elseif($own_info['sex']=="Female")
			$value = s("新婦側");
    else $value = "";

		$own_array[] = "$value";

		//guest-type 区分
    include("../admin/inc/main_dbcon.inc.php");
    $guest_type_obj = Model_Guesttype::find_by_pk((int)$own_info['guest_type']);
    $guest_type = $guest_type_obj["name"];
		$value = s($guest_type);
		$own_array[] = "$value";
    include("../admin/inc/return_dbcon.inc.php");

		//LastName	外字姓
		$value = s($own_info['last_name']);
		//$own_array[] = "$value";
		$own_array[] = setStrGaijis($value,$lastname_gaijis);

		//FirstName	 外字名
		$value = s($own_info['first_name']);
		$own_array[] = setStrGaijis($value,$firstname_gaijis);


		//外字姓名FullName
		$value = s($own_info['last_name']." ".$own_info['first_name']);
		$own_array[] = setStrGaijis($value,$gaiji_name_arr);

    
		//com1 com2
		if($own_info['comment1']&&$own_info['comment2']){
      $comment_gaijis = array_merge($comment1_gaijis,$comment2_gaijis);
			$value = s($own_info['comment1'].'△'
                 .$own_info['comment2']);
      $own_array[] = setStrGaijis($value,$comment_gaijis);
		}elseif($own_info['comment1']){
			$value = s($own_info['comment1']);
      $own_array[] = setStrGaijis($value,$comment1_gaijis);
		}elseif($own_info['comment2']){
			$value = s($own_info['comment2']);
      $own_array[] = setStrGaijis($value,$comment2_gaijis);
    }else $own_array[] = "";

		$xxx++;
	}

	$cl23_2 = implode(",",$own_array);
	$cl23 = implode(",",$cl22);
	$cl21 =$cl23_2.",".$cl23;
$line = $cl21;
	$lines .= $line;

$date_array = explode('-', $user_info['party_day']);

if($user_info['id']<10)
$user_id_name="000".$user_info['id'];
else if($user_info['id']<100)
$user_id_name="00".$user_info['id'];
else if($user_info['id']<1000)
$user_id_name="0".$user_info['id'];
//test script
//print $lines;exit;
header("Content-Type: application/octet-stream");
header("Cache-Control: public");
header("Pragma: public");

//csvのダウンロードの際のカウント方法はプラス1000で
$version = $obj->get_download_num($user_id,Core_Session::get_print_id()+1000);
$this_name = $HOTELID."_".$date_array[0].$date_array[1].$date_array[2]."_".$user_id_name."_".$version;
$this_name = mb_convert_encoding($this_name, "SJIS", "UTF-8");


header("Content-Disposition: attachment; filename=${this_name}.csv");


echo $lines;