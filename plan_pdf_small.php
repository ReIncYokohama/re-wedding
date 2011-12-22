<?php
include_once("admin/inc/dbcon.inc.php");
require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');
include_once("admin/inc/class_data.dbo.php");
include_once("admin/inc/class_information.dbo.php");
include_once("inc/gaiji.image.wedding.php");

$obj = new DataClass();
$objInfo = new InformationClass();
$user_id = (int)$_SESSION['userid'];
if($user_id=="")
  $user_id = (int)$_GET['user_id'];


function get_center_table($max_width,$width,$html){
  $margin = floor((100*(($max_width-$width)/$max_width))*10/2)/10;
  $main_margin = floor((100-$margin*2)*10)/10;
  return "<table><tr><td width=\"".$margin."%\"></td><td width=\"".$main_margin."%\">".$html."</td><td width=\"".$margin."%\"></td></tr></table>";
}

$plan_id = $obj->GetSingleData("spssp_plan", "id","user_id=".$user_id);

$plan_row = $obj->GetSingleRow("spssp_plan"," id =".$plan_id);	

$PDF_PAGE_FORMAT_USER=PDF_PAGE_FORMAT;
$PDF_PAGE_ORIENTATION_USER=PDF_PAGE_ORIENTATION;
$PDF_PAGE_FORMAT_USER="A3";

if($plan_row['print_type'] == 1){
  $PDF_PAGE_ORIENTATION_USER="L";
  $max_width = 1500;
  $max_width_num = 39;
  $flag_horizon = true;
}else if($plan_row['print_type'] == 2){{}
  $PDF_PAGE_ORIENTATION_USER="P";
  $max_width = 900;
  $flag_horizon = false;
}

if($PDF_PAGE_ORIENTATION_USER=="P" && $PDF_PAGE_FORMAT_USER=="B4")
  {
    $main_font_size="20px";
    $main_font_size2="20px";
  }
if($PDF_PAGE_ORIENTATION_USER=="L" && $PDF_PAGE_FORMAT_USER=="B4")
  {
    $main_font_size="30px";
    $main_font_size2="30px";
  }
if($PDF_PAGE_ORIENTATION_USER=="L" && $PDF_PAGE_FORMAT_USER=="A3")
  {
    $main_font_size="40px";
    $main_font_size2="40px";
  }
if($PDF_PAGE_ORIENTATION_USER=="P" && $PDF_PAGE_FORMAT_USER=="A3")
  {
    $main_font_size="30px";
    $main_font_size2="30px";
  }
	
$pdf = new TCPDF($PDF_PAGE_ORIENTATION_USER, PDF_UNIT, $PDF_PAGE_FORMAT_USER, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 006');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 006', PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
//$pdf->SetMargins(PDF_MARGIN_LEFT, 15, PDF_MARGIN_RIGHT);
//$pdf->SetHeaderMargin(0);
//$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks
//$pdf->SetAutoPageBreak(True, PDF_MARGIN_BOTTOM);
$pdf->SetAutoPageBreak( false, 0);
$pdf->SetHeaderMargin(0);
$pdf->SetMargins(8,8,8);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// ---------------------------------------------------------

// set font
//$pdf->SetFont('dejavusans', '', 10);
//$pdf->SetFont('arialunicid0', '', 12);
$pdf->SetFont('arialunicid0', '', 9);
// add a page
$pdf->AddPage();
/////////////////end of for pdf///////////////////
	
	
	
	
//include_once("inc/header.inc.php");

$get = $obj->protectXSS($_GET);
	
	
$user_layout = $obj->GetNumRows("spssp_table_layout"," user_id= $user_id");
if($user_layout <= 0)
	{
		redirect('table_layout.php?err=13');
	}
$user_guest = $obj->GetNumRows("spssp_guest"," user_id= $user_id");
if($user_guest <= 0)
	{
		redirect('my_guests.php?err=14');
	}
	
$plan_criteria = $obj->GetNumRows("spssp_plan"," user_id= $user_id");
if($plan_criteria <= 0)
	{
		redirect('table_layout.php?err=15');
	}
	
	
$cats =	$obj->GetAllRowsByCondition('spssp_guest_category',' user_id='.$user_id);	
	
$user_info = $obj->GetSingleRow("spssp_user"," id=".$user_id);
	
$room_info=$obj->GetSingleRow("spssp_room"," id =".$user_info['room_id']);
$party_room_info=$obj->GetSingleRow("spssp_party_room"," id =".$user_info['party_room_id']);
	

$staff_name = $obj->GetSingleData("spssp_admin", "name"," id=".$user_info['stuff_id']);

	
$room_rows = $plan_row['row_number'];

$row_width = $row_width-6;
	
$table_width = (int)($row_width/2);
$table_width = $table_width-6;
	
$room_tables = $plan_row['column_number'];
$room_width = (int)(184*(int)$room_tables)."px";

	
$row_width = (int)(182*$room_tables);
$content_width = ($row_width+235).'px';
	
$room_seats = $plan_row['seat_number'];
	
$num_tables = $room_rows * $room_tables;
	
$tblrows = $obj->getRowsByQuery("select distinct row_order from spssp_table_layout where user_id = ".(int)$user_id);

unset($_SESSION['cart']);
$itemids = array();
if(isset($_SESSION['cart']))
	{
		
	}
else
	{
		$plan_details_row = $obj->GetAllRow("spssp_plan_details"," plan_id=".$plan_id);
		if(!empty($plan_details_row))
      {
        foreach($plan_details_row as $pdr)
          {
            $skey= $pdr['seat_id'].'_input';
            $sval = '#'.$pdr['seat_id'].'_'.$pdr['guest_id'];
            $_SESSION['cart'][$skey]=$sval;
          }
      }
	}
if(isset($_SESSION['cart']))
	{
		foreach($_SESSION['cart'] as $item)
      {
        if($item)
          {
            $itemArr = explode("_",$item);
            $itemids[] = $itemArr[1];
          }
      }

	}
include("admin/inc/main_dbcon.inc.php");
$respects = $obj->GetAllRow("spssp_main.spssp_respect");
include("admin/inc/return_dbcon.inc.php");


$html.='<table style="font-size:'.$main_font_size_top.';"><tr>';

/* 引出物　商品数　開始 */
$html.='<td width="35%">';
$html.='</td>';
	
$male_guest_num = $obj->GetNumRows("spssp_guest","user_id=".(int)$user_id." and sex='Male'");
$female_guest_num = $obj->GetNumRows("spssp_guest","user_id=".(int)$user_id." and sex='Female'");
$total_guest=$male_guest_num+$female_guest_num;
$total_guest_with_bride=$total_guest+2;
	
$woman_lastname=$user_info['woman_lastname'];
$man_lastname=$user_info['man_lastname'];

$party_day_for_confirm=$user_info['party_day'];
$party_date_array=explode("-",$party_day_for_confirm);
$day = $party_date_array[2];
$month = $party_date_array[1];
$year = $party_date_array[0];
$confirm_date= mktime(0, 0, 0, $month, $day-7, $year);
$confirm_date_main=date("Y-m-d", $confirm_date);
$query_string = "SELECT * FROM spssp_gaizi_detail_for_user WHERE gu_id = $user_id";

//$man_firstname_gaijis = getGaijiPathArray(get_gaiji_arr($obj->getRowsByQuery($query_string." and gu_trgt_type=0")));

$man_lastname_gaijis = getGaijiPathArray(get_gaiji_arr($obj->getRowsByQuery($query_string." and gu_trgt_type=1")));
//$woman_firstname_gaijis = getGaijiPathArray(get_gaiji_arr($obj->getRowsByQuery($query_string." and gu_trgt_type=2")));

$woman_lastname_gaijis = getGaijiPathArray(get_gaiji_arr($obj->getRowsByQuery($query_string." and gu_trgt_type=3")));

function get_gaiji_arr($gaijis){
  $returnArray = array();
  for($i=0;$i<count($gaijis);++$i){
    array_push($returnArray,$gaijis[$i]["gu_char_img"]);
  }
  return $returnArray;
}

$man_lastname_gaiji_pathArray = array();
$woman_lastname_gaiji_pathArray = array();

make_pdf_guest_info($user_id,$man_lastname,$man_lastname_gaijis,$woman_lastname,$woman_lastname_gaijis,$male_guest_num,$female_guest_num);

$marriage_day = "";
$marriage_day_with_time = "";
if($user_info['marriage_day'] &&  $user_info['marriage_day'] != "0000-00-00"){
  $marriage_day = strftime('%Y年%m月%d日',strtotime(jp_decode($user_info['marriage_day'])));
  $marriage_day_with_time = date("H時i分",strtotime($user_info['marriage_day_with_time']));
}

$marrige_day_text = '<tr style="text-align:left;font-size:35px;">
					<td align="left" width="80"  valign="middle">挙式日時</td><td width="160" >'.$marriage_day.'  '.$marriage_day_with_time.'</td><td width="300">会場'.$party_room_info[name].' </td>
                                                                                                                                                                              </tr>';


$html.='<td width="40%">
	<table>
				<tr>
					<td align="left"  valign="middle" style="text-align:center;" colspan="3">
		
'.$objInfo->get_user_name_image_or_src_from_user_side($user_id ,$hotel_id=1, $name="pdf_hikidemono_head.png",$extra="/").'
			</td>
				</tr>
        <tr><td colspan="3"></td></tr>
				'.$marrige_day_text.'
				<tr style="text-align:left;font-size:35px;">
					<td width="80" align="left"  valign="middle">披露宴日時</td><td width="160">'.strftime('%Y年%m月%d日',strtotime(jp_decode($user_info['party_day']))).'  '.date("H時i分",strtotime($user_info['party_day_with_time'])).'</td><td width="300">会場'.$room_info[name].' </td>
				</tr>
        <tr><td colspan="3"></td></tr>
				<tr style="text-align:left;font-size:25px;">
					<td align="left"  valign="middle" style="text-align:center;">作成日時</td><td>'.date('Y年n月j日  H時i分').'</td><td>
					スタッフ名 '.$staff_name.'
					</td>
				</tr>

				<tr style="text-align:left;font-size:25px;">
					<td align="left"  valign="middle" style="text-align:center;">制限開始日</td><td colspan="2">'.strftime('%Y年%m月%d日',strtotime(jp_decode($confirm_date_main))).' 
					</td>
				</tr>
			</table>
		
</td><td width="15%" style="font-size:15px;">';
	
$html.='</td>';
$html.='</tr></table><br> ';

$takasago_guests = $obj->get_guestdata_in_takasago($user_id);
$takasago_num = count($takasago_guests)+2;
$main_guest = $obj->get_guestdata_in_takasago_for_small_pdf($user_id,110);

$userArray = $obj->get_userdata($user_id);
$userGuestArray = $obj->get_guestdata_in_host_for_small_pdf($user_id,110);

$man_image = $userGuestArray[0];
$woman_image = $userGuestArray[1];

$viewSubArray = array($main_guest[3],$main_guest[1],$man_image,$main_guest[5],$woman_image,$main_guest[2],$main_guest[4]);
$viewArray = array();
for($i=0;$i<count($viewSubArray);++$i){
  if($viewSubArray[$i] && $viewSubArray[$i] != "") array_push($viewArray,$viewSubArray[$i]);
}
$width = count($viewArray)*150;

$subhtml='<table style="font-size:'.$main_font_size_top.';border:1px solid black; padding:2px;margin:0px;" width="'.$width.'"><tr>';

for($i=0;$i<count($viewArray);++$i){
  $subhtml .= '<td align="center"  valign="middle">'.$viewArray[$i].'</td>';
}
$subhtml .= '</tr></table><br>';

$html .= get_center_table($max_width,$width,$subhtml);



$table_data = $obj->get_table_data_detail($user_id);

//rows[0]columns[0]seats[0]
function get_table_html($rows,$main_font_size,$seat_num,$seat_row){
  $html='<table cellspacing="0" cellspadding="0" width="100%" style="font-size:'.$main_font_size.';">';
  for($i=0;$i<count($rows);++$i){
    $row = $rows[$i];
    $width = 110*count($row["columns"])*2;
    $html .= "<tr><td width:\"100%\"><table><tr>";
    for($j=0;$j<count($row["columns"]);++$j){
      $column = $row["columns"][$j];
      $table_name = $column["name"];
      $table_id = $column["id"];
      $visible = $column["visible"];
      if($row["ralign"] == "C" && $column["display"] == 0 && !$visible) continue;
      $html .= "<td><table cellspacing=\"0\" cellspadding=\"0\"><tr><td colspan=\"0\" align=\"center\">".$table_name."</td></tr>";

      for($k=0;$k<$seat_row*2;++$k){
        if($k%2==0) $html .= "<tr>";
        $align = ($k%2==0)?"right":"left";
        $seat_detail = $column["seats"][$k];
        $guest_id = $seat_detail["guest_id"];
        $plate = "";
        if($guest_id) $plate = "<img width=\"110\" src=\"".$seat_detail["guest_detail"]["name_plate"]."\" />";
        $html .= "<td style=\"width:50%;\" align=\"".$align."\">".$plate."</td>";
        if($k%2==1) $html .= "</tr>";
      }
      $html .= "</table></td>";
    }
    $html .= "</tr></table></td></tr><tr><td></td></tr>";
  }
  $html .="</table>";
  return $html;
}

$seat_num = $table_data["seat_num"];
$seat_row = $seat_num/2;

function draw_html($plan_id,$html,$pdf){

  $samplefile="sam_".$plan_id."_".rand()."_".time().".txt";
  
  $handle = fopen("cache/".$samplefile, "x");
  
  if(fwrite($handle, $html)==true)
    {
      fclose($handle);
      $utf8text = file_get_contents("cache/".$samplefile, false);
    }
  
  @unlink("cache/".$samplefile);
  
  $pdf->writeHTML($utf8text, true, false, true, false, '');
}

$page_arr = array();
$rows_num = count($table_data["rows"]);
$columns_num = count($table_data["rows"][0]["columns"]);
if($flag_horizon){
  $rows_config_num = 3;
  $columns_config_num = 5;
}else{
  $rows_config_num = 5;
  $columns_config_num = 3;
}
$page_rows_num = ceil($rows_num/$rows_config_num);
$page_columns_num = ceil($columns_num/$columns_config_num);

$index = 0;
for($i=0;$i<$page_rows_num;++$i){
  for($j=0;$j<$page_columns_num;++$j){
    $rows_start = $i*$rows_config_num;
    $rows_end = $rows_num<$rows_start+$rows_config_num?$rows_num:$rows_start+$rows_config_num;
    $columns_start = $j*$columns_config_num;
    $columns_end = $columns_num<$columns_start+$columns_config_num?$columns_num:$columns_start+$columns_config_num;
    $page_arr[$index] = array();
    for($k=$rows_start;$k<$rows_end;++$k){
      $row = $table_data["rows"];
      $row["columns"] = array();
      for($l=$columns_start;$l<$columns_end;++$l){
        array_push($row["columns"],$table_data["rows"][$k]["columns"][$l]);
      }
      array_push($page_arr[$index],$row);
    }
    $index+=1;
  }
}


draw_html($plan_id,$html,$pdf);
for($i=0;$i<count($page_arr);++$i){
  $html = get_table_html($page_arr[$i],$main_font_size,$seat_num,$seat_row);
  draw_html($plan_id,$html,$pdf);
  if($i+1==count($page_arr)) break;
  $pdf->addPage();
}

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$date = date("His");
//$pdf->Output('sekijihyou'.$date.'.pdf', 'D');
$pdf->Output('example_001.pdf', 'I');
?> 
