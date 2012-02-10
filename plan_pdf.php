<?php
include_once("admin/inc/dbcon.inc.php");
require_once('pdf.php');
include_once("admin/inc/class.dbo.php");
include_once("admin/inc/class_information.dbo.php");
include_once("admin/inc/class_data.dbo.php");
include_once("inc/gaiji.image.wedding.php");

include_once("fuel/load_classes.php");

$obj = new DataClass();
$objInfo = new InformationClass();

$user_id = Core_Session::get_user_id();

if($_GET["user_id"] && Core_Session::is_admin())
  $user_id = (int)$_GET['user_id'];

function get_center_table($max_width,$width,$html){
  $margin = floor((100*(($max_width-$width)/$max_width))*10/2)/10;
  $main_margin = floor((100-$margin*2)*10)/10;
  if($max_width == $width) return $html;
  return "<table><tr><td width=\"".$margin."%\"></td><td width=\"".$main_margin."%\">".$html."</td><td width=\"".$margin."%\"></td></tr></table>";
}

function get_right_table($max_width,$width,$html){
  $margin = floor((100*(($max_width-$width)/$max_width))*10)/10;
  $main_margin = floor((100-$margin)*10)/10;
  return "<table><tr><td width=\"".$margin."%\"></td><td width=\"".$main_margin."%\">".$html."</td></tr></table>";
}

$main_font_size="20px";
$main_font_size_top="20px";
$main_font_size2="20px";


$plan = Model_Plan::find_one_by_user_id($user_id);
$plan_id = $plan->id;
$plan_row = $plan->to_array();

if($plan_row['print_type'] == 1){
  $max_width = 1500;
  $max_width_num = 39;
  $flag_horizon = true;
}else if($plan_row['print_type'] == 2){{}
  $max_width = 900;
  $flag_horizon = false;
}

$pdf = new MyPdf($plan_row['print_type']);

if(!Model_Tablelayout::exist($user_id)){
  Response::redirect("table_layout.php?err=13");
}
if(!Model_Guest::exist($user_id)){
  Response::redirect("table_layout.php?err=14");
}
if(!$plan){
  Response::redirect("table_layout.php?err=15");
}

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

$table_data = $obj->get_table_data_detail_with_hikidemono($user_id);

include("admin/inc/main_dbcon.inc.php");
$respects = $obj->GetAllRow("spssp_main.spssp_respect");
include("admin/inc/return_dbcon.inc.php");

$html.='<table style="font-size:20px;"><tr>';
/* 引出物　商品数　開始 */
$html.='<td width="35%"><table><tr><td><table>';

//$group_rows = Model_Giftgroup::find_by_user_id($user_id);
//$guests = 

$guest_models_takasago = Model_Guest::find_by_takasago($user_id);
$takasago_guests = Core_Arr::func($guest_models_takasago,"to_array");

$attend_guests = array_merge($takasago_guests,$table_data["attend_guests"]);
$gift_table = $obj->get_gift_table($attend_guests,$user_id);

$group_rows = $obj->GetAllRowsByCondition("spssp_gift_group"," user_id=".$user_id);
$gift_rows = $obj->GetAllRowsByCondition("spssp_gift"," user_id=".$user_id);

$tr1 = '<tr><td style="text-align:right;border:1px solid black;" colspan="2" height="10"  width="100" >グループ</td>';
$tr2 = '<tr><td style="text-align:right;border:1px solid black;" colspan="2" height="10"  width="100" >グループ数</td>';

$sum = 0;
foreach($gift_table as $grp){
  $tr1.='<td  style="text-align:center;border:1px solid black;"  width="20">'.mb_convert_kana($grp['name'],"K","utf8").'</td>';
  $tr2.='<td  style="text-align:center;border:1px solid black;"  width="20">'.$grp['num'].'</td>';
  $sum += $grp["num"];
}

$tr1.='<td  style="text-align:center;border:1px solid black;"  width="20">予備</td>
	<td  style="text-align:center;border:1px solid black;"  width="20">合計</td>
  	</tr>';
$tr2.='<td  style="text-align:center;border:1px solid black;"  width="20">-</td>
	<td  style="text-align:center;border:1px solid black;"  width="20">'.$sum.'</td>
  	</tr>';
	
$html.= $tr1.$tr2.'</table></td></tr>';



$subhtml = "";
$start=0;
$have_gift = false;
foreach($gift_rows as $gift)
	{
    if($gift["name"]=="") continue;
    $have_gift = true;
		if($start!=0) $subhtml.='<tr>';
    $subhtml.='<td style="text-align:right;border:1px solid black;" height="10" width="84">'.mb_convert_kana($gift['name'],"K","utf8").'</td>';

    $start = 1;
    $num_gifts = 0;
    foreach($gift_table as $grp){
      $gift_ids = $obj->GetSingleData("spssp_gift_group_relation","gift_id", "user_id= $user_id and group_id = ".$grp['id']);
      $gift_arr = explode("|",$gift_ids);
      if(in_array($gift['id'],$gift_arr))
        {
          $htm = $grp["num"];
          $num_gifts += $grp["num"];
        }
      else
        {
          $htm = '0';
        }
      $subhtml.='<td style="text-align:center;border:1px solid black;" width="20">'.$htm.'</td>';
    }

    $num_reserve = $obj->GetSingleData("spssp_item_value","value", "item_id = ".$gift["id"]);
    $num_gifts += $num_reserve;
    $subhtml.='<td style="text-align:center;border:1px solid black;" width="20">'.$num_reserve.'</td>';
    $subhtml.='<td style="text-align:center;border:1px solid black;" width="20">'.$num_gifts.'</td>';
    $subhtml.='</tr>';
    
  }
	/* 引出物　商品数　終了 */
if($have_gift) $html.='<tr><td style="text-align:center; border:1px solid black;" width="16" rowspan="7" height="10">商品名</td>'.$subhtml;
$html.='</table></td>';

$male_takasago_guest_num = $obj->GetNumRows("spssp_guest","user_id=".(int)$user_id." and sex='Male' and stage=1 and stage_guest>0");
$female_takasago_guest_num = $obj->GetNumRows("spssp_guest","user_id=".(int)$user_id." and sex='Female' and stage=1 and stage_guest>0");
$male_guest_num = $table_data["man_num"];
$female_guest_num = $table_data["woman_num"];
$total_guest=$table_data["attend_num"];
$total_guest_with_bride=$total_guest+count($takasago_guests);

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

make_pdf_guest_info($user_id,$man_lastname,$man_lastname_gaijis,$woman_lastname,$woman_lastname_gaijis,$male_guest_num,$female_guest_num,$total_guest,$total_guest_with_bride);

$marriage_day = "";
$marriage_day_with_time = "";
if($user_info['marriage_day'] &&  $user_info['marriage_day'] != "0000-00-00"){
  $marriage_day = strftime('%Y年%m月%d日',strtotime(jp_decode($user_info['marriage_day'])));
  $marriage_day_with_time = date("H時i分",strtotime($user_info['marriage_day_with_time']));
}
$marrige_day_text = '<tr style="text-align:left;font-size:35px;">
					<td align="left" width="80"  valign="middle">挙式日時</td><td width="160" >'.$marriage_day.'  '.$marriage_day_with_time.'</td><td width="300">会場'.$party_room_info[name].' </td>
                                                                                                                                                                              </tr>';


$html.='<td width="32%">
	<table>
				<tr>
					<td align="left"  valign="middle" style="text-align:center;" colspan="3">
		
'.$objInfo->get_user_name_image_or_src_from_user_side($user_id ,$hotel_id=1, $name="pdf_hikidemono_head.png",$extra="/").'
			</td>
				</tr>
        <!--<tr><td colspan="3"></td></tr>-->
				'.$marrige_day_text.'
				<tr style="text-align:left;font-size:35px;">
					<td width="80" align="left"  valign="middle">披露宴日時</td><td width="160">'.strftime('%Y年%m月%d日',strtotime(jp_decode($user_info['party_day']))).'  '.date("H時i分",strtotime($user_info['party_day_with_time'])).'</td><td width="300">会場'.$room_info[name].' </td>
				</tr>
        <!--<tr><td colspan="3"></td></tr>-->
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
		
</td><td width="15%" style="font-size:15px;">
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ';

$subhtml = '<table  style="font-size:'.($main_font_size_top).';"><tr><td colspan="2" style="text-align:center;border:1px solid black;" width="200" height="10"><b>料理数</b></td></tr>';

$menu_groups = $obj->GetAllRowsByCondition("spssp_menu_group","user_id=".(int)$user_id);
$num_groups = count($menu_groups);

$totalsum=0;
foreach($attend_guests as $guest)
{
  if($guest["menu_grp"]>0){
    ++$totalsum;
  }  
}

$subhtml.='<tr><td style="text-align:center;border:1px solid black;" width="100" height="10">大人</td><td style="text-align:center;border:1px solid black;" width="100">'.($total_guest_with_bride-$totalsum).'</td></tr>';

$guest_without_menu=$total_guest_with_bride;

foreach($menu_groups as $mg)
	{
    $menu_num = 0;
    foreach($attend_guests as $guest)
      {
        if($guest["menu_grp"]==$mg["id"]){
          ++$menu_num;
        }  
      }
		if ($mg['name']!="") {
		    $subhtml.='<tr>
		      <td  align="center" style="text-align:center;border:1px solid black;" height="10">'.mb_convert_kana($mg['name'],"K","utf8").'</td>
		      <td  align="center" style="text-align:center;border:1px solid black;" height="10">'.$menu_num.'</td>
		    </tr>';	
		}
	}
//$html.='<tr>
//     <td  align="center" bgcolor="#FFFFFF" style="text-align:center;border:1px solid black;" >×</td>      
//      <td  align="center" bgcolor="#FFFFFF" style="text-align:center;border:1px solid black;" >'.$guest_without_menu.'</td>
//    </tr>';	
	
$subhtml.='<tr>
      <td  align="center" bgcolor="#FFFFFF" style="text-align:center;border:1px solid black;" height="10">合計</td>
      <td  align="center" bgcolor="#FFFFFF" style="text-align:center;border:1px solid black;" height="10">'.$total_guest_with_bride.'</td>
    </tr>';	
	
$subhtml.='</table>';

$html .= get_right_table(500,200,$subhtml);
$html.='</td></tr></table> <!--<br/>-->';

$takasago_menu_num = 0;
for($i=0;$i<count($takasago_guests);++$i){
  if($takasago_guests[$i]["menu_grp"]>0){
    ++$takasago_menu_num;
  }
}

$takasago_num = count($takasago_guests);
$main_guest = $obj->get_guestdata_in_takasago_for_pdf($user_id,130);
$gift_table = $obj->get_gift_table_html($takasago_guests,$user_id);

$userArray = $obj->get_userdata($user_id);
$userGuestArray = $obj->get_guestdata_in_host_for_pdf($user_id,130);

$man_image = $userGuestArray[0];
$woman_image = $userGuestArray[1];

$viewSubArray = array($main_guest[3],$main_guest[1],$man_image,$main_guest[5],$woman_image,$main_guest[2],$main_guest[4]);
$viewArray = array();
for($i=0;$i<count($viewSubArray);++$i){
  if($viewSubArray[$i] && $viewSubArray[$i] != "") array_push($viewArray,$viewSubArray[$i]);
}
$width = count($viewArray)*150;
$gift_table = get_center_table((count($viewArray)-1)*200,190,$gift_table);

$takasago_num_text = ($takasago_menu_num!=0)?($takasago_num-$takasago_menu_num)."+".$takasago_menu_num:$takasago_num;
$subhtml= '<table style="font-size:15px;border:1px solid black; padding:2px;margin:0px;" width="'.$width.'"><tr><td style="font-size:25px;" align="center">高砂【 '.$takasago_num_text.'名 】</td><td colspan="'.(count($viewArray)-1).'">'.$gift_table.'</td></tr><tr>';

for($i=0;$i<count($viewArray);++$i){
  $subhtml .= '<td align="center"  valign="middle">'.$viewArray[$i].'</td>';
}
$subhtml .= '</tr></table>';
$html .= get_center_table($max_width,$width,$subhtml);

//引き出物画像の表示

//rows[0]columns[0]seats[0]
function get_table_html($rows,$main_font_size,$seat_num,$seat_row,$max_columns_num){
  $html='<table cellspacing="0" cellspadding="0" style="font-size:'.$main_font_size.';">';
  $haveRow = false;
  for($i=0;$i<count($rows);++$i){
    $row = $rows[$i];
    $html .= "<tr><td width:\"100%\">";
    $subhtml = "<table><tr>";
    $active_columns_num = 0;
    for($j=0;$j<count($row["columns"]);++$j){
      $column = $row["columns"][$j];
      $table_name = $column["name"];
      $table_id = $column["id"];
      if($column["display"] == 0 && !$column["visible"]) continue;
      if($column["display"] == 0){
        $subhtml .="<td></td>";
        $active_columns_num += 1;
        continue;
      }
      $haveRow = true;
      $gifts = $column["gifts"];
      $gift_table = '<table>';
      $gift_tr1 = "<tr>";
      $gift_tr2 = "<tr>";
      for($k=0;$k<count($gifts);++$k){
        $gift_tr1 .= '<td height="9" style="text-align:center;border:1px solid black;font-size:17px;" >'.$gifts[$k]["name"]."</td>";
        $gift_tr2 .= '<td height="9" style="text-align:center;border:1px solid black;font-size:17px;" >'.$gifts[$k]["num"]."</td>";
      }
      $gift_tr1 .= '<td height="9" style="text-align:center;border:1px solid black;font-size:17px;" >子</td></tr>';
      $gift_tr2 .= '<td height="9" style="text-align:center;border:1px solid black;font-size:17px;" >'.$column["child_menu_num"]."</td></tr>";
      $gift_table .= $gift_tr1.$gift_tr2.'</table>';
      $numText = ($column["child_menu_num"]==0)?count($column["guests"]):(count($column["guests"])-$column["child_menu_num"])."+".$column["child_menu_num"];
      $subhtml .= "<td><table cellspacing=\"0\" cellspadding=\"0\" width=\"300\"><tr><td align=\"center\" style=\"font-size:25px;\">".$table_name."[".$numText."名]</td><td>".$gift_table."</td></tr><tr style=\"font-size:10px;\"><td></td></tr>";
       
      for($k=0;$k<$seat_row*2;++$k){
        if($k%2==0) $subhtml .= "<tr>";
        $align = ($k%2==0)?"right":"left";
        $seat_detail = $column["seats"][$k];
        $guest_id = $seat_detail["guest_id"];
        $plate = "";
        if(is_file($seat_detail["guest_detail"]["namecard_memo"])){
          $plate = "<img width=\"130\" src=\"".$seat_detail["guest_detail"]["namecard_memo"]."\" />";
        }else{
          $plate = "<div width=\"130\" height = \"33\"></div>";
        }
        //
        //if($guest_id && $k%2==1) $plate = "<img width=\"110\" src=\"".$seat_detail["guest_detail"]["namecard_memo2"]."\" />";
        $subhtml .= "<td colspan=\"2\" style=\"width:50%;\" align=\"".$align."\">".$plate."</td>";
        if($k%2==1) $subhtml .= "</tr>";
      }
      $subhtml .= "</table></td>";
      $active_columns_num += 1;
    }
    $subhtml .= "</tr></table>";
    $max_width = 110*$max_columns_num*2;
    $width = 110*$active_columns_num*2;
    $html .= get_center_table($max_width,$width,$subhtml)."</td></tr><tr><td></td></tr>";
    //$html .= $subhtml."</td></tr><tr><td></td></tr>";
  }
  if(!$haveRow) return "";
  $html .="</table>";
  return $html;
}

$seat_num = $table_data["seat_num"];
$seat_row = $seat_num/2;

function draw_html($plan_id,$html,$pdf,$num,$max_width){
  if($num && $max_width){
    $table_width = 300*$num;
    $html = get_center_table($max_width,$table_width,$html);
  }
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
$page_arr_max_columns_num = array();
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
    $max_columns_num = 0;
    for($k=$rows_start;$k<$rows_end;++$k){
      $row = $table_data["rows"];
      $row["columns"] = array();
      for($l=$columns_start;$l<$columns_end;++$l){
        array_push($row["columns"],$table_data["rows"][$k]["columns"][$l]);
      }
      array_push($page_arr[$index],$row);
      if(count($row["columns"])>$max_columns_num) $max_columns_num = count($row["columns"]);
    }
    $page_arr_max_columns_num[$index] = $max_columns_num;
    $index+=1;
  }
}
draw_html($plan_id,$html,$pdf);
for($i=0;$i<count($page_arr);++$i){
  $html = get_table_html($page_arr[$i],$main_font_size,$seat_num,$seat_row,$page_arr_max_columns_num[$i]);
  if($html != "" && $i != 0) $pdf->addPage();
  draw_html($plan_id,$html,$pdf,$page_arr_max_columns_num[$i],$max_width);
}

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$date = date("His");
//$pdf->Output('sekijihyou'.$date.'.pdf', 'D');

$user_id_name = $user_id;
$date_array = explode('-', $user_info['party_day']);
$this_name = "hikidemono".$HOTELID."_".$date_array[0].$date_array[1].$date_array[2]."_".$user_id_name;
//$pdf->Output($this_name.'.pdf',"D");
$pdf->Output($this_name.'.pdf');
?> 
