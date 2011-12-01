<?php
include_once("admin/inc/dbcon.inc.php");
require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');
include_once("admin/inc/class.dbo.php");
include_once("admin/inc/class_information.dbo.php");
include_once("admin/inc/class_data.dbo.php");
include_once("inc/gaiji.image.wedding.php");

$obj = new DataClass();	
$objInfo = new InformationClass();
$user_id = (int)$_SESSION['userid'];

if($user_id=="")
  $user_id = (int)$_GET['user_id'];


$max_width = 1200;
function get_center_table($max_width,$width,$html){
  $margin = floor((100*(($max_width-$width)/$max_width))*10/2)/10;
  $main_margin = floor((100-$margin*2)*10)/10;
  return "<table><tr><td width=\"".$margin."%\"></td><td width=\"".$main_margin."%\">".$html."</td><td width=\"".$margin."%\"></td></tr></table>";
}
function get_right_table($max_width,$width,$html){
  $margin = floor((100*(($max_width-$width)/$max_width))*10)/10;
  $main_margin = floor((100-$margin)*10)/10;
  return "<table><tr><td width=\"".$margin."%\"></td><td width=\"".$main_margin."%\">".$html."</td></tr></table>";
}


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
    $main_font_size_top="15px";
    $main_font_size_count="9px";
  }
if($PDF_PAGE_ORIENTATION_USER=="L" && $PDF_PAGE_FORMAT_USER=="B4")
  {
    $main_font_size="30px";
    $main_font_size_top="20px";
    $main_font_size_count="13px";
  }
if($PDF_PAGE_ORIENTATION_USER=="L" && $PDF_PAGE_FORMAT_USER=="A3")
  {
    $main_font_size="40px";
    $main_font_size_top="28px";
    $main_font_size_count="18px";
  }
if($PDF_PAGE_ORIENTATION_USER=="P" && $PDF_PAGE_FORMAT_USER=="A3")
  {
    $main_font_size="30px";
    $main_font_size_top="20px";
    $main_font_size_count="13px";
  }
	
$pdf = new TCPDF($PDF_PAGE_ORIENTATION_USER, PDF_UNIT, $PDF_PAGE_FORMAT_USER, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 006');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

//headerとfooterを非表示
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
//$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->SetAutoPageBreak( true, 0);
$pdf->SetHeaderMargin(0);
$pdf->SetMargins(3,2,3);

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
$html.='<td width="31%"><table><tr><td><table><tr><td style="text-align:right;border:1px solid black;" colspan="2" height="12"  width="100" >グループ</td>';

$group_rows = $obj->GetAllRowsByCondition("spssp_gift_group"," user_id=".$user_id);
$gift_rows = $obj->GetAllRowsByCondition("spssp_gift"," user_id=".$user_id);
foreach($group_rows as $grp)
	{
	
		$group_menu_array[$grp['name']]=0;
		if ($grp['name']!="") 
			$html.='<td  style="text-align:center;border:1px solid black;"  width="20">'.$grp['name'].'</td>';
	}

$group_menu_array[x]=0;
$html.='<td  style="text-align:center;border:1px solid black;"  width="20">予備</td>
	<td  style="text-align:center;border:1px solid black;"  width="20">合計</td>
  	</tr>';
          
$html.='<tr>
            <td colspan="2" style="text-align:right;border:1px solid black;"  height="12"  width="100">グループ数</td>';
           
$total = 0;
foreach($group_rows as $grp)
  {
  	if ($grp['name']!="") {
	    $num_guests_groups = $obj->GetNumRows(" spssp_guest_gift "," user_id = $user_id and group_id = ".$grp['id']);
	    $total += $num_guests_groups;
	    $html.='<td style="text-align:center;border:1px solid black;" width="20" height="12">'.$num_guests_groups.'</td>';
  	}
  }
			
$html.='<td  style="text-align:center;border:1px solid black;"  width="20">-</td>
            <td style="text-align:center;border:1px solid black;" width="20">'.$total.'</td>
          </tr>';
	
$html.='</table></td></tr>';

if(count($gift_rows)!=0) $html.='<tr><td style="text-align:center; border:1px solid black;" width="16" rowspan="7" height="12">商品名</td>';
$start=0;
foreach($gift_rows as $gift)
	{
	if ($gift['name']!="") {
		if($start!=0) $html.='<tr>';
		$start=1;
	    $html.='<td style="text-align:right;border:1px solid black;" height="14" width="84">'.$gift['name'].'</td>';
	
			$num_gifts = 0;
			foreach($group_rows as $grp)
	        {
	      	if ($grp['name']!="") {
		        $gift_ids = $obj->GetSingleData("spssp_gift_group_relation","gift_id", "user_id= $user_id and group_id = ".$grp['id']);
		        $guest_gift_num = $obj->GetNumRows("spssp_guest_gift","user_id=".$user_id." and group_id=".$grp["id"]);
		        
		        $gift_arr = explode("|",$gift_ids);
		        $groups = array();
		        if(in_array($gift['id'],$gift_arr))
		          {
		            $htm = $guest_gift_num;
		            array_push($groups,$grp['id']);
		          }
		        else
		          {
		            $htm = '0';
		          }
		        $num_gifts_in_group = 0;
		        if(!empty($groups))
		          {
		            foreach($groups as $grp)
		              {
		                $num_guests_groups = $obj->GetNumRows(" spssp_guest_gift "," user_id = $user_id and group_id = ".$grp);
		                $num_gifts += $num_guests_groups;
		              }
		            unset($groups);
		          }
					
		        $html.='<td style="text-align:center;border:1px solid black;" width="20">'.$htm.'</td>';
	      	}
	      }
	      $num_reserve = $obj->GetSingleData("spssp_item_value","value", "item_id = ".$gift["id"]);
	      $num_gifts += $num_reserve;
	      $html.='<td style="text-align:center;border:1px solid black;" width="20">'.$num_reserve.'</td>';
	      $html.='<td style="text-align:center;border:1px solid black;" width="20">'.$num_gifts.'</td>';
	      $html.='</tr>';
		}
	}
	//$html.='</tr>';
	/* 引出物　商品数　終了 */
	
$male_guest_num = $obj->GetNumRows("spssp_guest","user_id=".(int)$user_id." and sex='Male'");
$female_guest_num = $obj->GetNumRows("spssp_guest","user_id=".(int)$user_id." and sex='Female'");
$total_guest=$male_guest_num+$female_guest_num;
$total_guest_with_bride=$total_guest+2;
	
$woman_lastname=$user_info['woman_lastname'];
$man_lastname=$user_info['man_lastname'];


$html.='</table></td>';
 
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
					<td align="left"  valign="middle">挙式日時</td><td>'.$marriage_day.'  '.$marriage_day_with_time.'</td><td>会場'.$party_room_info[name].' </td>
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
					<td align="left"  valign="middle">披露宴日時</td><td>'.strftime('%Y年%m月%d日',strtotime(jp_decode($user_info['party_day']))).'  '.date("H時i分",strtotime($user_info['party_day_with_time'])).'</td><td>会場'.$room_info[name].' </td>
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
		
</td><td width="19%" style="font-size:15px;">
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ';

$subhtml = '<table  style="font-size:'.($main_font_size_top).';"><tr><td colspan="2" style="text-align:center;border:1px solid black;" width="200" height="12"><b>料理数</b></td></tr>';

$menu_groups = $obj->GetAllRowsByCondition("spssp_menu_group","user_id=".(int)$user_id);
$num_groups = count($menu_groups);

$totalsum='';
$Noofguest = $obj->GetNumRows("spssp_guest","user_id=".(int)$_SESSION['userid']);
foreach($menu_groups as $mg)
{
	$num_menu_guest = $obj->GetNumRows("spssp_guest_menu","user_id=$user_id and menu_id=".$mg['id']." and guest_id<>0");
	$totalsum +=$num_menu_guest;
}
$subhtml.='<tr><td style="text-align:center;border:1px solid black;" width="100" height="12">大人</td><td style="text-align:center;border:1px solid black;" width="100">'.($Noofguest-$totalsum).'</td></tr>';

$guest_without_menu=$total_guest;
$group_menu_array['子']=0;
foreach($menu_groups as $mg)
	{
		$num_menu_guest = $obj->GetNumRows("spssp_guest_menu","user_id=$user_id and menu_id=".$mg['id']);
		$guest_without_menu=$guest_without_menu-$num_menu_guest;
		if ($mg['name']!="") {
		    $subhtml.='<tr>
		      <td  align="center" style="text-align:center;border:1px solid black;" height="12">'.$mg['name'].'</td>
		      <td  align="center" style="text-align:center;border:1px solid black;" height="12">'.$num_menu_guest.'</td>
		    </tr>';	
		}
	}
//$html.='<tr>
//     <td  align="center" bgcolor="#FFFFFF" style="text-align:center;border:1px solid black;" >×</td>      
//      <td  align="center" bgcolor="#FFFFFF" style="text-align:center;border:1px solid black;" >'.$guest_without_menu.'</td>
//    </tr>';	
	
$subhtml.='<tr>
      <td  align="center" bgcolor="#FFFFFF" style="text-align:center;border:1px solid black;" height="12">合計</td>
      <td  align="center" bgcolor="#FFFFFF" style="text-align:center;border:1px solid black;" height="12">'.$total_guest.'</td>
    </tr>';	
	
$subhtml.='</table>';

$html .= get_right_table(500,200,$subhtml);
$html.='</td></tr></table> <br/>';

$takasago_guests = $obj->get_guestdata_in_takasago($user_id);
$takasago_num = count($takasago_guests)+2;
$main_guest = $obj->get_guestdata_in_takasago_for_pdf($user_id);
$gift_table = $obj->get_gift_table_html($takasago_guests,$user_id);


$userArray = $obj->get_userdata($user_id);
$userGuestArray = $obj->get_guestdata_in_host_for_pdf($user_id);

$man_image = $userGuestArray[0];
$woman_image = $userGuestArray[1];


$viewSubArray = array($main_guest[3],$main_guest[1],$man_image,$main_guest[5],$woman_image,$main_guest[2],$main_guest[4]);
$viewArray = array();
for($i=0;$i<count($viewSubArray);++$i){
  if($viewSubArray[$i] && $viewSubArray[$i] != "") array_push($viewArray,$viewSubArray[$i]);
}
$width = count($viewArray)*150;
$gift_table = get_center_table((count($viewArray)-1)*200,190,$gift_table);

$subhtml= '<table style="font-size:15px;border:1px solid black; padding:2px;margin:0px;" width="'.$width.'"><tr><td style="font-size:30px;" align="center">高砂【 '.$takasago_num.'名 】</td><td colspan="'.(count($viewArray)-1).'">'.$gift_table.'</td></tr><tr>';

for($i=0;$i<count($viewArray);++$i){
  $subhtml .= '<td align="center"  valign="middle">'.$viewArray[$i].'</td>';
}
$subhtml .= '</tr></table><br>';

$html .= get_center_table($max_width,$width,$subhtml);



$layoutname = $obj->getSingleData("spssp_plan", "layoutname"," user_id= $user_id");
if($layoutname=="")
  $layoutname = $obj->GetSingleData("spssp_options" ,"option_value" ," option_name='default_layout_title'");


//$html.='<table style="font-size:'.$main_font_size_top.';">';

//$html.='<tr><td>&nbsp;</td><td>&nbsp;</td><td><table style="border:1px solid black;padding:10px;"><tr><td align="center"  valign="middle" style="text-align:center;">'.$layoutname.'</td></tr></table></td><td>&nbsp;</td><td>&nbsp;</td></tr></table><br/>';

$html.='<table width="100%" style="font-size:'.$main_font_size.';">';

$table_data = $obj->get_table_data_detail($user_id);
$tblrows = $table_data["rows"];

$i=1;
foreach($tblrows as $tblrow)
  {
    $ralign = $tblrow["ralign"];
		if($ralign == 'C')
      {
			  $table_width=((count($tblrow["columns"]) - $tblrow["num_none"])/count($tblrow["columns"]))*100;
        $pos = 'center';
        $num_of_table_in_row = $tblrow["display_num"];
      }
		else
      {
			  $num_of_table_in_row = count($tblrow["columns"]);
        $pos = 'left';
        $table_width = 100;
        $num_of_table_in_row = count($tblrow["columns"]);
      }

		if($table_width!=100)
      $hidden_table_width=((100-$table_width)/2);
		
		
		$html.="<tr >";
		
		
		if($table_width!=100)
      {
        $html.="<td  width=\"".$hidden_table_width."%\" style=\"\">&nbsp;</td>";
      }
    
    if($table_width != 100)
		  $html.="<td width=\"".$table_width."%\" ><table align='".$pos."'  width=\"100%\"><tr>";
    else 
      $html.="<td width=\"".$table_width."%\" colspan=\"0\"><table align='".$pos."'  width=\"100%\"><tr>";

    $number=0;
		foreach($tblrow["columns"] as $table_row)
      {
        $number++;
        $new_name_row = $obj->GetSingleRow("spssp_user_table", "user_id = ".(int)$user_id." and default_table_id=".$table_row['id']);
        $tblname = $table_row["name"];
        $table_num = mb_strlen($tblname,"utf-8");
        $font_size = ($table_num>5)?"7":"10";

        if($ralign != "C" || $table_row["display"] != 0 || $table_row["visible"])
          {
            $html.="<td width=\"".round(100/$num_of_table_in_row)."%\"><table width=\"100%\">";
            if($disp=='1')
              $tblname="&nbsp;";
            $seats = $obj->getRowsByQuery("select * from spssp_default_plan_seat where table_id =".$table_row['table_id']." order by id asc limit 0,$room_seats");
            $seats_nums=0;
            $guest_num=0;
            $html2="";
				
            foreach($group_menu_array as $key=>$value)
              {
                $group_menu_array[$key]=0;
              }
				
            foreach($seats as $seat)
              {
                $key = $seat['id']."_input";
					
                $itemArray = explode("_", $_SESSION['cart'][$key]);
                $item_info=array();
                $edited_nums="";
                $item="";
                $submname='';
                $item = $itemArray[1];
                if($item!='')
                  {
						
                    $item_info =  $obj->GetSingleRow("spssp_guest", " id=".$item." and id in(SELECT id FROM `spssp_guest` WHERE user_id=".$user_id." and self!=1 and stage_guest=0)");
                    if($item_info)
                      {
                        $submname = $obj->GetSingleData("spssp_guest_sub_category ", "name"," id=".$item_info['sub_category_id']);
							
                        include("admin/inc/main_dbcon.inc.php");
                        $rspct = $obj->GetSingleData("spssp_main.spssp_respect", "title"," id=".$item_info['respect_id']);
                        include("admin/inc/return_dbcon.inc.php");
                        $edited_nums = $obj->GetNumRows("spssp_guest", "edit_item_id='".$item_info['id']."' and user_id=".(int)$user_id);
                      }
                  }
						
						
                if($edited_nums > 0)
                  {
                    $guest_editeds = $obj->GetSingleRow("spssp_guest", "edit_item_id=".$item_info['id']." and user_id=".(int)$user_id);
                    $item_info['id']=$guest_editeds['id'];
                    $item_info['sub_category_id']=$guest_editeds['sub_category_id'];
                    $item_info['name']=$guest_editeds['name'];
																					
                  }
						
                if($disp=='1')
                  $item_info['first_name']=$item_info['last_name']=$item_info['comment1']=$item_info['comment2']=$rspct="&nbsp;";
						
                if($seats_nums==0)
                  {
                    $html2.="<tr >";
                    $style_table="text-align:left";
                  }
                else
                  {
                    $style_table="text-align:right";
                  }
						
						
                if($item_info['first_name']!='')
                  {
					
							
							
                    $border="1px solid black;";
							
                    $menu_name="";
							
                    $guest_gifts = $obj->GetAllRowsByCondition("spssp_guest_gift "," user_id=".$user_id." and guest_id='".$item_info['id']."'");
							
                    if($guest_gifts)
                      $gift_groups = $obj->GetSingleData("spssp_gift_group","name"," id='".$guest_gifts['0']['group_id']."'");
							
							
                    $guest_menu_id = $obj->GetSingleData("spssp_guest_menu ","menu_id"," user_id=".$user_id." and guest_id='".$item_info['id']."' limit 1");
							
						
                    if($guest_menu_id > 0)
                      {
                        $menu_name = $obj->GetSingleData(" spssp_menu_group ", "name", " id=".$guest_menu_id." and user_id = ".$user_id);
                      }
							
                    if($disp=='1')
                      $border="none";
							
                    /*if($item_info['comment1']=="")
                      $item_info['comment1']="-";
							
                      if($item_info['comment2']=="")
                      $item_info['comment2']="-";*/
							
                    $comment=$item_info['comment1']." ".$item_info['comment2'];
							
                    $comment1=$item_info['comment1'];
							
                    $comment2=$item_info['comment2'];
							
							
                    $memo=$item_info['memo'];
							
                    $full_name=$item_info['first_name']." ".$item_info['last_name']." ".$rspct;
                    $chr_in_comment1=mb_strlen($comment1,"utf-8");
                    $chr_in_comment2=mb_strlen($comment2,"utf-8");
							
                    if($chr_in_comment1>=$chr_in_comment2)
                      $chr_in_comment=$chr_in_comment1;
                    else
                      $chr_in_comment=$chr_in_comment2;
							
							
							
                    $chr_in_fullname=mb_strlen($full_name,"utf-8");
							
                    $chr_in_menu_name=mb_strlen($menu_name,"utf-8");
							
                    $chr_in_memo=mb_strlen($memo,"utf-8");
							
							
                    if($chr_in_fullname){
                      //8000
                      //$font_size_fullname=(int)(((1/$num_of_table_in_row)*(1/$chr_in_fullname))*4000);
                      $font_size_fullname=30;
                    }
                    if($chr_in_menu_name)
                      //$font_size_menu_name=(int)(((1/$num_of_table_in_row)*(1/$chr_in_menu_name))*2000);
                      $font_size_menu_name=30;
                    
                    if($chr_in_memo)
                      //3500
                      //$font_size_memo=(int)(((1/$num_of_table_in_row)*(1/$chr_in_memo))*2000);
                      $font_size_memo=30;
								 
                    if($chr_in_comment)
                      //$font_size_comment=(int)(((1/$num_of_table_in_row)*(1/$chr_in_comment))*4000);
                      $font_size_comment=30;
								
						
								
                    if($disp=='1')
                      $gift_groups=$menu_name=$memo="";
								
								
							
                    if($menu_name=="-")
                      $font_size_menu_name=30;
								
                    if($comment1=="-")
                      $font_size_comment1=30;
							
                    if($comment2=="-")
                      $font_size_comment2=30;
								
                    if($font_size_fullname>30)
                      $font_size_fullname=30;
								
                    if($font_size_comment>30)
                      $font_size_comment=30;
								
								
								
								
								
                    if($font_size_menu_name>30)
                      $font_size_menu_name=30;
								
                    if($font_size_memo>30)
                      $font_size_memo=30;
								
                    if($font_size_comment>$font_size_fullname)
                      $font_size_comment=$font_size_fullname;
								
								
							
								
						
							
							
                    if(array_key_exists($gift_groups,$group_menu_array))
                      {
                        $group_menu_array[$gift_groups]=$group_menu_array[$gift_groups]+1;
                      }
                    else
                      {
                        $group_menu_array[x]=$group_menu_array[x]+1;
                      }
                    if($menu_name)
                      {
                        $group_menu_array['子']=$group_menu_array['子']+1;
                      }
							
                    if($seats_nums==0)
                      {
                        $middle_string="";
								
                        $middle_string .= $objInfo->get_user_name_image_or_src_from_user_side($user_id ,$hotel_id=1, $name="namecard_memo2.png",$extra="guest/".$item_info['id']."/");
                
                        //52.63
                        $html2.="<td width=\"50%\" >".$middle_string."
								</td>";
                      }
                    else
                      {
								
                        $middle_string="";
                        $middle_string .= $objInfo->get_user_name_image_or_src_from_user_side($user_id ,$hotel_id=1, $name="namecard_memo.png",$extra="guest/".$item_info['id']."/");
						
                        //47.37
                        $html2.="<td  width=\"50%\" style=\"height:20px;\">".$middle_string."
								</td>";
                      }
						
                    $guest_num++;
                  }
                else
                  {
						
                    $html2.="<td style=\"width:50%;height:20px;\" >&nbsp;</td>";
						
						
                  }
						
						
                if($seats_nums==1)
                  $html2.="</tr>";
						
					
                $seats_nums++;
						
                if($seats_nums==2)
                  $seats_nums=0;
              }
            if($disp=='1' || $table_row['display'] == 0){
              $guest_num="&nbsp;";
              $tblname = "";
          }else
              $guest_num ='【 '.$guest_num.'名 】';
            
            if($seats_nums==1)
              $html2.="<td></td></tr>";
			
			
			
				
            $html.='<tr><td  align="center" width="50%" style="font-size:'.$font_size.';">'.$tblname .$guest_num.'</td>';
			
            if($disp!='1' and $table_row['display'] != 0)
              {
                
                $html.="<td  align=\"center\"  width=\"50%\"><table style=\"font-size:".$main_font_size_count.";\"><tr>";
                foreach($group_menu_array as $key=>$value)
                  {
                    $keyvalue=mb_substr($key, 0, 1,'UTF-8');
				
                    $html.="<td style=\" border:1px solid black;\">".$keyvalue."</td>";	
                  }
                $html.="</tr><tr>";
                foreach($group_menu_array as $key=>$value)
                  {
                    $html.="<td style=\" border:1px solid black;\">".$value."</td>";	
                  }
                $html.="</tr></table></td>";
			
              }
            else
              $html.="<td  align=\"center\">&nbsp;</td>";
			
            $html.='</tr>';
            $html.=$html2;	
			
			
            $html.="</table></td>";
          }
      }

    if($pos == "center" && $table_width != 100)
		  $html.="</tr></table></td><td width=\"".((100-$table_width)/2)."%\" ></td></tr>";
    else
      $html.="</tr></table></td></tr>";
	}

$html.="</table>";


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
//$pdf->Output('example_001.pdf', 'I');
$date_array = explode('-', $user_info['party_day']);
$this_name = "0001_".$date_array[0].$date_array[1].$date_array[2]."_".$user_id_name;
$pdf->Output($this_name.'.pdf', 'I');
//print $html;
?> 
