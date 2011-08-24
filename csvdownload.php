<?php
@session_start();
include_once("admin/inc/class.dbo.php");
include_once("inc/checklogin.inc.php");

$obj = new DBO();
$get = $obj->protectXSS($_GET);
$user_id = (int)$_SESSION['userid'];

/*$entityArray2 = array(" HotelName , WeddingDate , WeddingTime , WeddingVenues , ReceptionDate, ReceptionTime , ReceptionHall , GroomName,  fullPhoneticGroom , BrideFullName , BrideFullPhonetic , Categories, ProductName ,  Printsize,tableArrangement , JIs_num, DataOutputTime , PlannerName , LayoutColumns , TableLayoutStages , Colortable , Max , NumberAttendance "); */

/*$entityArray2 = array(" ホテル名 , 挙式日 , 挙式時間 , 挙式会場 , 披露宴日, 披露宴時間 , 披露宴会場 , 新郎姓名,  新郎姓名ふりがな , 新婦姓名 , 新婦姓名ふりがな , 商品区分, 商品名 ,  席次表サイズ,席次表配置 ,字形 , データ出力日時 , プランナー名 , layout高砂卓名 ,卓レイアウト列数 , 卓レイアウト段数 , 卓色 , 一卓最大人数 , 列席者数 "); 

$entity=implode(",",$entityArray2); 
$entity = mb_convert_encoding("$entity", "SJIS", "UTF8");
$lines .= <<<html
$entity
html;*/

$lines .="Header";

$user_info =  $obj->GetSingleRow("spssp_user", " id=".$user_id);
$stuff_info =  $obj->GetSingleRow("spssp_admin", " id=".$user_info['stuff_id']);
$room_info =  $obj->GetSingleRow("spssp_room", " id=".$user_info['room_id']);
$party_room_info =  $obj->GetSingleRow("spssp_party_room", " id=".$user_info['party_room_id']);
$plan_info =  $obj->GetSingleRow("spssp_plan", " user_id=".$user_id);
$default_layout_title = $obj->GetSingleData("spssp_options" ,"option_value" ," option_name='default_layout_title'");
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

$entityArray['HotelName']			= "横浜ロイヤルパークホテル";
$entityArray['WeddingDate']			= strftime('%Y年%m月%d日',strtotime($user_info['marriage_day']));
$entityArray['WeddingTime']			= date("H:i",strtotime($user_info['marriage_day_with_time']));  



$entityArray['ReceptionHall']		= $party_room_info['name'];

$entityArray['ReceptionDate']		= strftime('%Y年%m月%d日',strtotime($user_info['party_day']));
$entityArray['ReceptionTime']		= date("H:i",strtotime($user_info['party_day_with_time']));  


$entityArray['WeddingVenues']		= $room_info['name'];
$entityArray['GroomName']			= $user_info['man_lastname']." ".$user_info['man_firstname'];
$entityArray['fullPhoneticGroom']	= $user_info['man_furi_lastname']." ".$user_info['man_furi_firstname'];
$entityArray['BrideFullName']		= $user_info['woman_lastname']." ".$user_info['woman_firstname'];
$entityArray['BrideFullPhonetic']	= $user_info['woman_furi_lastname']." ".$user_info['woman_furi_firstname'];
$entityArray['Categories']			= $dowload_options;
$entityArray['ProductName']			= $plan_info['product_name'];
$entityArray['Printsize']			= $print_size;
$entityArray['tableArrangement']	= $tableArrangement;
$entityArray['JIs_num']				= $user_info['user_code'];
//$entityArray['DataOutputTime']		= strftime('%Y年%m月%d日',strtotime(date("Y/m/d ")));
$entityArray['DataOutputTime']		= date('Y年m月d日 H:i',strtotime("now"));

$entityArray['PlannerName']			= $stuff_info['name'];
$entityArray['layout_title']		= $plan_info['layoutname'];
$entityArray['LayoutColumns']		= $plan_info['column_number'];
$entityArray['TableLayoutStages']	= $plan_info['row_number'];
$entityArray['Colortable']	        = "";
$entityArray['Max']					= $plan_info['seat_number'];
$entityArray['NumberAttendance']	= $plan_info['column_number']*$plan_info['row_number']*$plan_info['seat_number'];

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

array_unshift($entityArraytable, "レイアウトalign");
$tblrows = $obj->getRowsByQuery("select distinct row_order from spssp_table_layout where user_id = ".(int)$user_id." order by id ASC");



 function getGaijiCode($gaijis){
   $textArray = array();
   for($i=0;$i<count($gaijis);++$i){
     for($j=0;$j<count($gaijis[$i]);++$j){
       array_push($textArray,$gaijis[$i][$j]["gu_char_setcode"]); 
     }
   }
   return join(" ",$textArray);
 }

	
//echo "<pre>";
//print_r($tblrows);

/*$entityArraytable=implode(",",$entityArraytable); 
$entitytable = mb_convert_encoding("$entityArraytable", "SJIS", "UTF8");
$lines .= <<<html
$entitytable
html;*/
$lines .="Tables";

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
	//$value = chop($tblrow['row_order']);
	//$cl[] = "\"$value\"";
	
	$value = chop($pos);
	$cl[] = "\"$value\"";
	
	$table_rows = $obj->getRowsByQuery("select * from spssp_table_layout where user_id = ".(int)$user_id." and row_order=".$tblrow['row_order']." order by  column_order asc");
	
	foreach($table_rows as $table_row)
	{
		$new_name_row = $obj->GetSingleRow("spssp_user_table", "user_id = ".(int)$user_id." and default_table_id=".$table_row['id']);
						
		if(isset($new_name_row) && $new_name_row['id'] !='')
		{
			$tblname_row = $obj->GetSingleRow("spssp_tables_name","id=".$new_name_row['table_name_id']);
			$tblname = $tblname_row['name'];
						
		}
		else
		{
			$tblname = $table_row['name'];
		}
		//echo $table_row['table_id']."<br>";
		$sql_check_guest ="select count(*) as count from spssp_plan_details as spd join spssp_default_plan_seat as sdps on spd.seat_id =  sdps.id where sdps.table_id = ".$table_row['table_id'];
		$tbl_guest_num = $obj->getRowsByQuery($sql_check_guest);
		//print_r($tbl_guest_num[0]);
		if($tbl_guest_num[0]['count'] > 0)
		{
			
			$value = chop($z);
			$cl[] = "\"$value\"";
			
			$value = chop($tblname);
			$cl[] = "\"$value\"";
			
			$value = chop("");
			$cl[] = "\"$value\"";
		}
		else
		{
			$value = chop("-1");
			$cl[] = "\"$value\"";
			
			$value = chop("");
			$cl[] = "\"$value\"";
			
			$value = chop("");
			$cl[] = "\"$value\"";
		}		
		
	$z++;	
	}
//echo "<pre>";
//print_r($c11);
	for($k=0;$k<$x;$k++)
	{
		for($y=0;$y<$z;$y++)
		{
			$value = chop($c11[$y][$k]);
			$c3[$y] = "\"$value\"";
			
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

	
	$line = mb_convert_encoding("$cl2", "SJIS", "UTF8");
	
	$lines .= $line;

}
$lines .= "\n";
/*$entityArrayGuests = array(" TableNumber , TableName , SeatNumber , LastName , FirstName, FullName , EUDC7 , Title, Address9 , EUDC10 , Groups , GuestType, Last EUDC13 , Private Character14,EUDC full name15 , EUDC title16 ");*/

/*$entityArrayGuests = array(" テーブル番号 , テーブル名 , 座席番号 , 姓 , 名, 姓名 , 姓名外字番号 , 敬称, 肩書 , 肩書外字番号 , グループ , 区分, 外字姓 , 外字名, 外字姓名 , 外字肩書 ");


$entityArrayGuests=implode(",",$entityArrayGuests); 
$entityGuests = mb_convert_encoding("$entityArrayGuests", "SJIS", "UTF8");
$lines .= <<<html
$entityGuests
html;*/

$lines .="Guests";


$usertblrows = $obj->getRowsByQuery("select * from spssp_table_layout where user_id = ".(int)$user_id." order by id ASC");
$num_tables;

$o=1;$cl22 = "";
foreach($usertblrows as $tblRows)
{	//echo "<br>".$tblRows['table_id']."<br>";
	$usertblrows = $obj->getRowsByQuery("select * from spssp_default_plan_seat where table_id = ".(int)$tblRows['table_id']." order by id ASC");
	//echo "<pre>";print_r($usertblrows);
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
		if(!empty($guest_info))
			$value = chop($o);
		else
			$value = chop("-1");
			
		$cl22[] = "\n\"$value\"";
		//TableName
		$value = chop($tblname);
		//$value = chop("table".$o);
		$cl22[] = "\"$value\"";
		//SeatNumber
		$value = chop($z);//////"seat ".
		$cl22[] = "\"$value\"";
		if($z%$room_seats==0)
		{
			$z=1;
		}
		//gaiji
    $query_string = "SELECT * FROM spssp_gaizi_detail_for_guest WHERE guest_id = ".$guest_info["id"];
    $firstname_gaijis = $obj->getRowsByQuery($query_string." and gu_trgt_type=0");
    $lastname_gaijis = $obj->getRowsByQuery($query_string." and gu_trgt_type=1");
    $comment1_gaijis = $obj->getRowsByQuery($query_string." and gu_trgt_type=2");
    $comment2_gaijis = $obj->getRowsByQuery($query_string." and gu_trgt_type=3");


		//LastName		
		$value = chop($guest_info['last_name']);
		$cl22[] = "\"$value\"";
		
		//FirstName		
		$value = chop($guest_info['first_name']);		
		$cl22[] = "\"$value\"";
		
		
		//FullName		
		$value = chop($guest_info['last_name']." ".$guest_info['first_name']);
    $cl22[] = "\"$value\"";
		
		//FullName gaiji
    
		$value = chop($guest_info['last_name']." ".$guest_info['first_name']);		
		
		$cl22[] = "\"".getGaijiCode(array($lastname_gaijis,$firstname_gaijis))."\"";
		//respect
		
		include("admin/inc/main_dbcon.inc.php");
		$respect = $obj->GetSingleData(" spssp_respect", "title","id=".$guest_info['respect_id']);
		include("admin/inc/return_dbcon.inc.php");
		$value = chop($respect);		
		$cl22[] = "\"$value\"";
		//com1 com2 
		if($guest_info['comment1']&&$guest_info['comment2'])
			$value = chop($guest_info['comment1']."△".$guest_info['comment2']);	
		elseif($guest_info['comment1'])
			$value = chop($guest_info['comment1']);
		elseif($guest_info['comment2'])
			$value = chop($guest_info['comment2']);	
		
		$cl22[] = "\"$value\"";
		
		//com1 com2 
		
		if($guest_info['comment1']&&$guest_info['comment2'])
			$value = chop($guest_info['comment1']."△"  .$guest_info['comment2']);	
		elseif($guest_info['comment1'])
			$value = chop($guest_info['comment1']);
		elseif($guest_info['comment2'])
			$value = chop($guest_info['comment2']);		
		//$cl22[] = "\"$value\"";
		$cl22[] = getGaijiCode($comment1_gaijis,$comment2_gaijis);
		
		
		// sex グループ 
		if($guest_info['sex']=="Male")
			$value = chop("新郎側");	
		elseif($guest_info['sex']=="Female")
			$value = chop("新婦側");	
		
		$cl22[] = "\"$value\"";
		
		//guest-type 区分
		include("admin/inc/main_dbcon.inc.php");
		$guest_type = $obj->GetSingleData( "spssp_guest_type", "name","id=".$guest_info['guest_type']);
		include("admin/inc/return_dbcon.inc.php");
		$value = chop($guest_type);		
		$cl22[] = "\"$value\"";
		
		//LastName	外字姓	
		$value = chop($guest_info['first_name']);
		$cl22[] = "\"$value\"";
		
		//FirstName	 外字名	
		$value = chop($guest_info['last_name']);		
		$cl22[] = "\"$value\"";
		
		
		//外字姓名FullName		
		$value = chop($guest_info['first_name']." ".$guest_info['last_name']);		
		$cl22[] = "\"$value\"";
		
		//com1 com2 
		if($guest_info['comment1']&&$guest_info['comment2'])
			$value = chop($guest_info['comment1']."△".$guest_info['comment2']);	
		elseif($guest_info['comment1'])
			$value = chop($guest_info['comment1']);
		elseif($guest_info['comment2'])
			$value = chop($guest_info['comment2']);		
		
		$cl22[] = "\"$value\"";
		
		$guest_info="";
		$z++;	
	}
	$o++;
}
	$guest_own_info = $obj->GetAllRowsByCondition("spssp_guest","(self=1 or guest_type!=0) and stage=1 and user_id=".(int)$user_id);

	//echo "<pre>";print_r($guest_own_info);
	$xxx=1;
	foreach($guest_own_info as $own_info)
	{
    $guest_id = $guest_info["id"];
    
		//TableNumber
		$value = chop(0);
		/*if($xxx==2)
			$own_array[] = "\n\"$value\"";
		else
			$own_array[] = "\"$value\"";*/
		$own_array[] = "\n\"$value\"";
		
		//TableName
		//$value = chop($tblname);
		if(!empty($plan_info['layoutname']))
			$value = chop($plan_info['layoutname']);
		else
			$value = chop($default_layout_title);
		$own_array[] = "\"$value\"";
		
		//SeatNumber
		$value = chop($xxx);/////////"seat ".
		$own_array[] = "\"$value\"";
		
		//LastName
		
		$value = chop($own_info['last_name']);
		$own_array[] = "\"$value\"";
		
		//FirstName
		$value = chop($own_info['first_name']);
		$own_array[] = "\"$value\"";
		
		
		//FullName		
		$value = chop($own_info['last_name']." ".$own_info['first_name']);
		$own_array[] = "\"$value\"";
		
		//FullName		
		$value = chop($own_info['last_name']." ".$own_info['first_name']);
		//$own_array[] = "\"$value\"";
		$own_array[] = " ";
		
		//respect
		
		include("admin/inc/main_dbcon.inc.php");
		$respect = $obj->GetSingleData(" spssp_respect", "title","id=".$own_info['respect_id']);
		include("admin/inc/return_dbcon.inc.php");
		$value = chop($respect);		
		$own_array[] = "\"$value\"";
		//com1 com2 
		if($own_info['comment1']&&$own_info['comment2'])
			$value = chop($own_info['comment1']."△".$own_info['comment2']);	
		elseif($own_info['comment1'])
			$value = chop($own_info['comment1']);
		elseif($own_info['comment2'])
			$value = chop($own_info['comment2']);	
		
		$own_array[] = "\"$value\"";
	
		//com1 com2 
		if($own_info['comment1']&&$own_info['comment2'])
			$value = chop($own_info['comment1']."△".$own_info['comment2']);	
		elseif($own_info['comment1'])
			$value = chop($own_info['comment1']);
		elseif($own_info['comment2'])
			$value = chop($own_info['comment2']);
			
		//$own_array[] = "\"$value\"";
		
		$own_array[] = " ";
	
	
		// sex グループ 
		if($own_info['sex']=="Male")
			$value = chop("新郎側");	
		elseif($own_info['sex']=="Female")
			$value = chop("新婦側");	
		
		$own_array[] = "\"$value\"";
		
		//guest-type 区分
		
		include("admin/inc/main_dbcon.inc.php");
		$guest_type = $obj->GetSingleData( "spssp_guest_type", "name","id=".$own_info['guest_type']);
		include("admin/inc/return_dbcon.inc.php");
		$value = chop($guest_type);		
		$own_array[] = "\"$value\"";
		
		//LastName	外字姓	
		$value = chop($own_info['last_name']);
		$own_array[] = "\"$value\"";
		
		//FirstName	 外字名	
		$value = chop($own_info['first_name']);		
		$own_array[] = "\"$value\"";
		
		
		//外字姓名FullName		
		$value = chop($own_info['last_name']." ".$own_info['first_name']);		
		$own_array[] = "\"$value\"";
		
		//com1 com2 
		if($own_info['comment1']&&$own_info['comment2'])
			$value = chop($own_info['comment1']."△".$own_info['comment2']);	
		elseif($own_info['comment1'])
			$value = chop($own_info['comment1']);
		elseif($own_info['comment2'])
			$value = chop($own_info['comment2']);
		
		$own_array[] = "\"$value\"";
		
		
		
		$xxx++;
	}
	
	$cl23_2 = implode(",",$own_array);
	$cl23 = implode(",",$cl22);
	$cl21 =$cl23_2.",".$cl23;
	$line = mb_convert_encoding("$cl21", "SJIS", "UTF8");
	$lines .= $line;



//exit;	
	
//echo "<pre>";
//print_r($entityArrayGuests);
//exit;
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


//header("Content-Type: application/octet-stream");
//header("Content-Disposition: attachment; filename=${this_name}.csv");
echo $lines;
?>
