<?php
include_once("admin/inc/dbcon.inc.php");
require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');
include_once("admin/inc/class_data.dbo.php");
include_once("admin/inc/class_information.dbo.php");

$obj = new DataClass();
$objInfo = new InformationClass();
$user_id = (int)$_SESSION['userid'];
if($user_id=="")
  $user_id = (int)$_GET['user_id'];

$plan_id = $obj->GetSingleData("spssp_plan", "id","user_id=".$user_id);

$plan_row = $obj->GetSingleRow("spssp_plan"," id =".$plan_id);	

$PDF_PAGE_FORMAT_USER=PDF_PAGE_FORMAT;
$PDF_PAGE_ORIENTATION_USER=PDF_PAGE_ORIENTATION;

if($plan_row['print_size'] == 1)
  $PDF_PAGE_FORMAT_USER="A3";
if($plan_row['print_size'] == 2)
  $PDF_PAGE_FORMAT_USER="B4";

if($plan_row['print_type'] == 1)
  $PDF_PAGE_ORIENTATION_USER="L";
if($plan_row['print_type'] == 2)
  $PDF_PAGE_ORIENTATION_USER="P";


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

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 006', PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, 15, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

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


$group_rows = $obj->GetAllRowsByCondition("spssp_gift_group"," user_id=".$user_id);
$gift_rows = $obj->GetAllRowsByCondition("spssp_gift"," user_id=".$user_id);
foreach($group_rows as $grp)
	{
	
		$group_menu_array[$grp['name']]=0;
		
	
	}
$menu_groups = $obj->GetAllRowsByCondition("spssp_menu_group","user_id=".(int)$user_id);
	
foreach($menu_groups as $mg)
	{
		$group_menu_array[$mg['name']]=0;
	}
include("admin/inc/main_dbcon.inc.php");
$respects = $obj->GetAllRow( "spssp_respect");
include("admin/inc/return_dbcon.inc.php");

$male_guest_num = $obj->GetNumRows("spssp_guest","user_id=".(int)$user_id." and sex='Male'");
$female_guest_num = $obj->GetNumRows("spssp_guest","user_id=".(int)$user_id." and sex='Female'");
$total_guest=$male_guest_num+$female_guest_num;
$total_guest_with_bride=$total_guest+2;

$woman_lastname=$objInfo->get_user_name_image_or_src_from_user_side($user_id ,$hotel_id=1, $name="woman_lastname_respect.png",$extra="thumb1");
	
$man_lastname=$objInfo->get_user_name_image_or_src_from_user_side($user_id ,$hotel_id=1, $name="man_lastname_respect.png",$extra="thumb1");
	
$party_day_for_confirm=$user_info['party_day'];
$party_date_array=explode("-",$party_day_for_confirm);
$day = $party_date_array[2];
$month = $party_date_array[1];
$year = $party_date_array[0];
$confirm_date= mktime(0, 0, 0, $month, $day-7, $year);
$confirm_date_main=date("Y-m-d", $confirm_date);
         
//$html.='<table style="text-align:left; padding:5px;border:1px solid black;">	<tr>
$html.='<table width="100%" cellpadding="5px"><tr>';
$html.='<td width="20%"><table style="border:1px solid black; padding:5px;font-size:'.$main_font_size2.';" width="100%"><tr><td align="left"  valign="middle" style="text-align:left;">新郎様側: '.$man_lastname.'</td></tr><tr><td align="left"  valign="middle" style="text-align:left;">新婦様側: '.$woman_lastname.'</td></tr></table></td>';



$html.='<td width="50%"><table style="padding:5px;font-size:'.$main_font_size2.';"><tr><td align="center"   style="text-align:center;">新郎<br/> '.$man_lastname.'</td><td align="center"    style="text-align:center;">新婦<br/> '.$woman_lastname.'</td></tr></table>
</td>';
$html.='<td>&nbsp;</td></tr></table><br/>';

			
$guests_bride = $obj->getRowsByQuery("SELECT * FROM `spssp_guest` WHERE user_id=".$user_id." and self!=1 and stage_guest!='0' and stage_guest!='' order by display_order DESC");

foreach($guests_bride as $witness_bride)
  {
    $group_name=$menu_name="";
					
				
    $group_id = $obj->GetSingleData("spssp_guest_gift ","group_id"," user_id=".$user_id." and guest_id='".$witness_bride['id']."' limit 1");
							
    if($group_id)
      $group_name= $obj->GetSingleData("spssp_gift_group","name"," id='".$group_id."'");
				
				
    $guest_menu_id = $obj->GetSingleData("spssp_guest_menu ","menu_id"," user_id=".$user_id." and guest_id='".$witness_bride['id']."' limit 1");
				
			
    if($guest_menu_id > 0)
      {
        $menu_name = $obj->GetSingleData(" spssp_menu_group ", "name", " id=".$guest_menu_id." and user_id = ".$user_id);
      }
				
    $main_guest[$witness_bride[stage_guest]]=$objInfo->get_user_name_image_or_src_from_user_side($user_id ,$hotel_id=1, $name="guest_fullname.png",$extra="guest/".$witness_bride['id']."/thumb2")."<br/>".$witness_bride['memo']."<br/>".$menu_name."<br/>".$group_name;;
			
  }


$html.='<table style="font-size:'.$main_font_size2.';border:1px solid black; width:100%; padding:10px;"><tr><td align="center"  valign="middle" style="text-align:center;">'.$main_guest[3].'</td><td align="center"  valign="middle" style="text-align:center;">'.$main_guest[1].'</td><td align="center"  valign="middle" style="text-align:center;">'.$objInfo->get_user_name_image_or_src_from_user_side($user_id ,$hotel_id=1, $name="man_fullname.png",$extra="thumb1").'</td><td align="center"  valign="middle" style="text-align:center;">'.$main_guest[5].'</td><td align="center"  valign="middle" style="text-align:center;">'.$objInfo->get_user_name_image_or_src_from_user_side($user_id ,$hotel_id=1, $name="woman_fullname.png",$extra="thumb1").'</td><td align="center"  valign="middle" style="text-align:center;">'.$main_guest[2].'</td><td align="center"  valign="middle" style="text-align:center;">'.$main_guest[4].'</td></tr></table><br/>';

$layoutname = $obj->getSingleData("spssp_plan", "layoutname"," user_id= $user_id");
if($layoutname=="")
  $layoutname = $obj->GetSingleData("spssp_options" ,"option_value" ," option_name='default_layout_title'");


$html.='<table style="font-size:'.$main_font_size2.';">';


$html.='<tr><td>&nbsp;</td><td>&nbsp;</td><td><table style="border:1px solid black;padding:10px;"><tr><td align="center"  valign="middle" style="text-align:center;">'.$layoutname.'</td></tr></table></td><td>&nbsp;</td><td>&nbsp;</td></tr></table><br/>';


$html.='<table cellspacing="4" cellspadding="4" width="100%" style="font-size:'.$main_font_size.';">';

$table_data = $obj->get_table_data_detail($user_id);

$seat_num = $table_data["seat_num"];
$seat_row = $seat_num/2;
$table_heigth = ($seat_row+1)*20;
for($i=0;$i<count($table_data["rows"]);++$i){
  $row = $table_data["rows"][$i];
  $width = 800/count($row["columns"]);
  $html .= "<tr><td width:\"100%\"><table align=\"left\" width=\"100%\"><tr>";
  for($j=0;$j<count($row["columns"]);++$j){
    $column = $row["columns"][$j];
    $column_num = $row['display_num'];
    $table_name = $column["name"];
    $table_id = $column["id"];
    $visible = $column["visible"];
    if($row["ralign"] == "C" && $column["display"] == 0 && !$visible) continue;
    $html .= "<td><table cellspacing=\"4\" cellspadding=\"4\"><tr><td colspan=\"2\" align=\"center\">".$table_name."</td></tr>";
    for($k=0;$k<$seat_row;++$k){
      if($k%2==0) $html .= "<tr>";
      $seat_detail = $column["seats"][$k];
      $guest_id = $seat_detail["guest_id"];
      $plate = "";
      if($guest_id) $plate = "<img src=\"".$seat_detail["guest_detail"]["name_plate"]."\" />";
      $html .= "<td style=\"width:50%;height:40px;\">".$plate."</td>";
      if($k%2==1) $html .= "</tr>";
    }
    $html .= "</table></td>";
  }
  $html .= "</tr></table></td></tr>";
}

$html .="</table>";

$samplefile="sam_".$plan_id."_".rand()."_".time().".txt";
//print $html;
//exit;


$handle = fopen("cache/".$samplefile, "x");
 
if(fwrite($handle, $html)==true)
  {
    fclose($handle);
	
    $utf8text = file_get_contents("cache/".$samplefile, false);
	
  }

@unlink("cache/".$samplefile);

$pdf->writeHTML($utf8text, true, false, true, false, '');

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$date = date("His");
$pdf->Output('sekijihyou'.$date.'.pdf', 'D');
?> 
