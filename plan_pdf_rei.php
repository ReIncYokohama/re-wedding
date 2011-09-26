<?php
include_once("admin/inc/dbcon.inc.php");
require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');
include_once("admin/inc/class.dbo.php");
include_once("admin/inc/class_information.dbo.php");

$obj = new DBO();	
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

$pdf = new TCPDF($PDF_PAGE_ORIENTATION_USER, PDF_UNIT, $PDF_PAGE_FORMAT_USER, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('');
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

$get = $obj->protectXSS($_GET);
	
//tableの席情報	
$user_layout = $obj->GetNumRows("spssp_table_layout"," user_id= $user_id");
if($user_layout <= 0)
	{
		redirect('table_layout.php?err=13');
	}
//ゲストの一覧
$user_guest = $obj->GetNumRows("spssp_guest"," user_id= $user_id");
if($user_guest <= 0)
	{
		redirect('my_guests.php?err=14');
	}
//ホテル情報
$plan_criteria = $obj->GetNumRows("spssp_plan"," user_id= $user_id");
if($plan_criteria <= 0)
	{
		redirect('table_layout.php?err=15');
	}
	
	
$cats =	$obj->GetAllRowsByCondition('spssp_guest_category',' user_id='.$user_id);

//ユーザ情報
$user = $obj->GetSingleRow("spssp_user"," id=$user_id");
//パーティの日時
$party_date = $user["party_day"];
list($year,$month,$day) = explode("-",$party_date);
//会場名
$roomName = $obj->GetSingleData("spssp_room","name"," id = ".$user["room_id"]);

$woman_lastname=$objInfo->get_user_name_image_or_src_from_user_side($user_id ,$hotel_id=1, $name="woman_lastname_respect.png",$extra="thumb1");
$man_lastname=$objInfo->get_user_name_image_or_src_from_user_side($user_id ,$hotel_id=1, $name="man_lastname_respect.png",$extra="thumb1");

/*



*/

$table_info = $objInfo->get_table_info($user_id);

$html = <<<EOT

<style>
  .group_title{
  display:inline;
  border:1px solid black;
  padding:10px;
}
  .tate_gaki{
    writing-mode: tb-rl;
   }
  .f18{
    font-size:50px;
  }
  .space1{
height:1px;
width:100%;
   }
</style>

<table width="100%">
  <tr width="100%">
    <td width="40%" height="100" class="f18">新郎家<br>新婦家<br>御結婚披露宴出席者御席次</td>
  <td width="10%" align="center" rowspan="2" class="tate_gaki">新郎<br><br>{$man_lastname}</td>
  <td width="10%" align="center" rowspan="2">新婦<br><br>{$woman_lastname}</td>
    <td width="40%" align="right" rowspan="2">{$year}年{$month}月{$day}日 於:{$roomName}</td>
  </tr>
  <tr height="10" width="100%">
    <td height="10" width="100%">
      <table cellpadding="2" width="100%">
        <tbody>
        <tr>
          <td width="25%"></td>
          <td width="50%" class="group_title" align="center" >高砂席</td>
          <td></td>
        </tr>
      </tbody>
      </table>
    </td>
  </tr>
</table>

<br>
EOT;



$seat_table_html = "";
for($i=0;$i<count($table_info["lines"]);++$i){
  $line = $table_info["lines"][$i];
  $row_percent = floor(100/$line["row_num"]);
  if($line["align"] == "C" && $line["max_row_num"] != 0){
    $seat_table_html.="<div>";
    $percent = floor(($line["row_num"]/$line["max_row_num"])*100);
    $seat_table_html.="<table width=\"".$percent."%\">";
  }else{
    $seat_table_html.="<div>";
    $seat_table_html.="<table>";
  }

  $seat_table_html.="<tr>";
  $rows = $line["rows"];
  $row_num = count($rows);
  for($j=0;$j<count($rows);++$j){
    if($rows[$j]["display"]>=1){
      $seat_table_html.="<td width=\"{$row_percent}%\">";
    }else{
      continue;
    }
    
    if($rows[$j]["display"] == 2){
      $seat_table_html.="<table><tr><td width='25%'></td><td width='50%' class=\"group_title\" align=\"center\">".$rows[$j]["table_name"]."</td><td></td></tr></table>";
    }else{
      $seat_table_html.="<table><tr><td></td></tr></table>";
    }
    
    $seat_table_html.="<div height=\"10\"></div><table>";
    $guest_id_arr = $rows[$j]["guest_id_arr"];
    for($k=0;$k<count($guest_id_arr);++$k){
      $guest_id = $guest_id_arr[$k];
      if($k%2==0) $seat_table_html .="<tr>";
      if($guest_id){
        $guest_image = $objInfo->get_user_name_image_or_src_from_user_side($user_id ,$hotel_id=1, $name="namecard.png",$extra="guest/".$guest_id."/");
      }else{
        $guest_image = "";
      }
      $seat_table_html.="<td>".$guest_image."</td>";
      if($k%2==1) $seat_table_html .="</tr>";
    }
    $seat_table_html.="</table></td>";
  }
  $seat_table_html.="</tr>";
  $seat_table_html.="</table></div>";
}


$html .= $seat_table_html;

$samplefile="sam_".$plan_id."_".rand()."_".time().".txt";
 
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
$pdf->Output('example_001.pdf', 'I');
//print $html;


?>