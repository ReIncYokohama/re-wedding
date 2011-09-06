<?php
	@session_start();
	include_once("../admin/inc/include_class_files.php");
	require_once('../tcpdf/config/lang/eng.php');
	require_once('../tcpdf/tcpdf.php');
	


if($_SESSION['printid'] =='')
{
  redirect("index.php");exit;
}
$obj = new DBO();
$objInfo = new InformationClass();

$user_id = $objInfo->get_user_id_md5( $_GET['user_id']);

if($user_id>0)
{
	//OK	
}
else
{
	redirect("list.php");exit;
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
}
if($PDF_PAGE_ORIENTATION_USER=="L" && $PDF_PAGE_FORMAT_USER=="B4")
{
	$main_font_size="30px";
}
if($PDF_PAGE_ORIENTATION_USER=="L" && $PDF_PAGE_FORMAT_USER=="A3")
{
	$main_font_size="40px";
}
if($PDF_PAGE_ORIENTATION_USER=="P" && $PDF_PAGE_FORMAT_USER=="A3")
{
	$main_font_size="30px";
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
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
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

	$respects = $obj->GetAllRow("spssp_respect");
	
	
	






$html.='<table style="font-size:'.$main_font_size.';"><tr>';


$html.='<td><table><tr><td colspan="2"><table><tr><td >商品名</td>';
 
           
$group_rows = $obj->GetAllRowsByCondition("spssp_gift_group"," user_id=".$user_id);
$gift_rows = $obj->GetAllRowsByCondition("spssp_gift"," user_id=".$user_id);
	foreach($group_rows as $grp)
	{
	
		$group_menu_array[$grp['name']]=0;
		$html.='<td  style="text-align:center">'.$grp['name'].'</td>';
	
	}

    $group_menu_array[x]=0;        
	$html.='<td  style="text-align:center">×</td>
	<td  style="text-align:center">予備</td>
	<td  style="text-align:center">合計</td>
  	</tr>';
          
	foreach($gift_rows as $gift)
	{
  
  		$html.='<tr><td >'.$gift['name'].'</td>';
            
				
		$num_gifts = 0;
		foreach($group_rows as $grp)
		{
			$gift_ids = $obj->GetSingleData("spssp_gift_group_relation","gift_id", "user_id= $user_id and group_id = ".$grp['id']);	
			$gift_arr = explode("|",$gift_ids);
			$groups = array();
			if(in_array($gift['id'],$gift_arr))
			{
				$htm = "<img src=\"../admin/img/tick.jpg\" border=\"0\" width=\"20\"/>";
				array_push($groups,$grp['id']);
			}
			else
			{
				$htm = '&nbsp;';
				
			}
			
			if(!empty($groups))
			{
				foreach($groups as $grp)
				{
					$num_guests_groups = $obj->GetNumRows(" spssp_guest_gift "," user_id = $user_id and group_id = ".$grp);
					$num_gifts += $num_guests_groups;
				}
				unset($groups);
			}
			
            $html.='<td   style="text-align:center">'.$htm.'</td>';
		}
          $html.='<td  style="text-align:center">&nbsp;</td>
            <td   style="text-align:center">&nbsp;</td>
            <td style="text-align:center">'.$num_gifts.'</td>
          </tr>';
	}	  
		  
		    $html.='<tr>
            <td  style="text-align:center">グループ数</td>';
           
				$total = 0;
            	foreach($group_rows as $grp)
				{
					$num_guests_groups = $obj->GetNumRows(" spssp_guest_gift "," user_id = $user_id and group_id = ".$grp['id']);
					$total += $num_guests_groups;
					$html.="<td style=\"text-align:center\"> $num_guests_groups </td>";
				}
			
            $html.='<td style="text-align:center">';
				
                	$num_guests = $obj->GetNumRows(" spssp_guest "," user_id = $user_id ");
					$not_gifted = $num_guests - $total;
			$html.=$not_gifted;
					 
			
             $html.='</td>
            <td  style="text-align:center">&nbsp;</td>
            <td  style="text-align:center">'.$num_guests.'</td>
          </tr>';
	
 $html.='</table></td></tr>
 
 <tr><td ><table ><tr><td colspan="2"></td></tr><tr><td colspan="2"><b>料理数</b></td></tr>';
 		  
	$menu_groups = $obj->GetAllRowsByCondition("spssp_menu_group","user_id=".(int)$user_id);
	$num_groups = count($menu_groups);
	foreach($menu_groups as $mg)
	{
		$group_menu_array[$mg['name']]=0;
		$num_menu_guest = $obj->GetNumRows("spssp_guest_menu","user_id=$user_id and menu_id=".$mg['id']);
	 $html.='<tr>
      <td  align="center" bgcolor="#FFFFFF">'.$mg['name'].'</td>
      
      <td  align="center" bgcolor="#FFFFFF">'.$num_menu_guest.'</td>
    </tr>';	
	}
	
	$male_guest_num = $obj->GetNumRows("spssp_guest","user_id=".(int)$user_id." and sex='Male'");
	$female_guest_num = $obj->GetNumRows("spssp_guest","user_id=".(int)$user_id." and sex='Female'");
	$total_guest=$male_guest_num+$female_guest_num;
	$total_guest_with_bride=$total_guest+2;
	
	$woman_lastname=$user_info['woman_firstname'];
	$man_lastname=$user_info['man_firstname'];
         

 $html.='</table></td></tr></table></td>';
 
 	$party_day_for_confirm=$user_info['party_day'];
	$party_date_array=explode("-",$party_day_for_confirm);
	 $day = $party_date_array[2];
	 $month = $party_date_array[1];
	 $year = $party_date_array[0];
	$confirm_date= mktime(0, 0, 0, $month, $day-7, $year);
	$confirm_date_main=date("Y-m-d", $confirm_date);

$html.='<td><table style="border:1px solid black; padding:10px;"><tr><td align="center"  valign="middle" style="text-align:center;">新郎<br/>'.$user_info['man_firstname'].' '.$user_info['man_lastname'].'</td><td align="center"  valign="middle" style="text-align:center;">新婦<br/>'.$user_info['woman_firstname'].' '.$user_info['woman_lastname'].' </td></tr></table><br/>

			<table>
				<tr>
					<td align="left"  valign="middle" style="text-align:center;">新郎様側: '.$man_lastname.'家&nbsp;&nbsp;&nbsp;&nbsp;列席者数 :'.$male_guest_num.'名様 </td>
				</tr>
				<tr>
					<td align="left"  valign="middle" style="text-align:center;">新婦様側: '.$woman_lastname.'家&nbsp;&nbsp;&nbsp;&nbsp;列席者数:'.$female_guest_num.'名様 </td>
				</tr>
				<tr>
					<td align="left"  valign="middle" style="text-align:center;">列席者数合計: '.$total_guest.'名様&nbsp;&nbsp;&nbsp;&nbsp;合計人数:'.$total_guest_with_bride.'名様 </td>
				</tr>
				<tr>
					<td align="left"  valign="middle" style="text-align:center;">挙式日 '.strftime('%Y年%m月%d日',strtotime(jp_decode($user_info['marriage_day']))).'  '.date("H時i分",strtotime($user_info['marriage_day_with_time'])).'&nbsp;&nbsp;&nbsp;会場 :'.$room_info[name].' </td>
				</tr>
				<tr>
					<td align="left"  valign="middle" style="text-align:center;">披露宴日 '.strftime('%Y年%m月%d日',strtotime(jp_decode($user_info['party_day']))).'  '.date("H時i分",strtotime($user_info['party_day_with_time'])).'&nbsp;&nbsp;&nbsp;会場 :'.$party_room_info[name].' </td>
				</tr>
				<tr>
					<td align="left"  valign="middle" style="text-align:center;">校了予定日 '.strftime('%Y年%m月%d日',strtotime(jp_decode($confirm_date_main))).' </td>
				</tr>
				<tr>
					<td align="left"  valign="middle" style="text-align:center;">プランナー: '.$staff_name.' </td>
				</tr>
			</table>
		

</td><td width="20%"></td>';

$html.='</tr></table> <br/>';
			
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
				
				$main_guest[$witness_bride[stage_guest]]=$witness_bride['first_name']." ".$witness_bride['last_name']."<br/>".$witness_bride['memo']."<br/>".$menu_name."<br/>".$group_name;
			
			}

$html.='<table style="font-size:'.$main_font_size.';border:1px solid black; width:100%; padding:10px;">';


			$html.='<tr><td align="center"  valign="middle" style="text-align:center;">'.$main_guest[3].'</td><td align="center"  valign="middle" style="text-align:center;">'.$main_guest[1].'</td><td align="center"  valign="middle" style="text-align:center;">'.$user_info['man_firstname']." ".$user_info['man_lastname'].'</td><td align="center"  valign="middle" style="text-align:center;">'.$main_guest[5].'</td><td align="center"  valign="middle" style="text-align:center;">'.$user_info['woman_firstname']." ".$user_info['woman_lastname'].'</td><td align="center"  valign="middle" style="text-align:center;">'.$main_guest[2].'</td><td align="center"  valign="middle" style="text-align:center;">'.$main_guest[4].'</td></tr></table><br/>';

$layoutname = $obj->getSingleData("spssp_plan", "layoutname"," user_id= $user_id");
if($layoutname=="")
$layoutname = $obj->GetSingleData("spssp_options" ,"option_value" ," option_name='default_layout_title'");


$html.='<table style="font-size:'.$main_font_size.';">';


$html.='<tr><td>&nbsp;</td><td>&nbsp;</td><td><table style="border:1px solid black;padding:10px;"><tr><td align="center"  valign="middle" style="text-align:center;">'.$layoutname.'</td></tr></table></td><td>&nbsp;</td><td>&nbsp;</td></tr></table><br/>';


$html.='<table cellspacing="4" cellspadding="4" width="100%" style="font-size:'.$main_font_size.';">';


	
			


$i=1;
 foreach($tblrows as $tblrow)
     {
		$ralign = $obj->GetSingleData("spssp_table_layout", "align"," row_order=".$tblrow['row_order']." and user_id=".$user_id." limit 1");					
		if($ralign == 'C')
		{
			
			
			$pos = 'center';
		}
		else if($ralign=='R')
		{
			
			
			$pos = 'right';
			
		}
		else
		{
			
			$pos = 'left';
		
			
		}
		
		$table_rows = $obj->getRowsByQuery("select * from spssp_table_layout where user_id = ".(int)$user_id." and row_order=".$tblrow['row_order']." order by  column_order asc");
		
		$table_rows_hidden = $obj->getRowsByQuery("select count(id) as countvalue from spssp_table_layout where user_id = ".(int)$user_id." and row_order=".$tblrow['row_order']." and display='0' order by  column_order asc");
		
		if($pos!="right" && $pos!="left")
		{
			$num_of_table_in_row=count($table_rows)-$table_rows_hidden[0][countvalue];
		}
		else
		{
			$num_of_table_in_row=count($table_rows);
		}
		
		
		 $table_width=($num_of_table_in_row/count($table_rows))*100;
		
		if($table_width!=100) 
		 $hidden_table_width=((100-$table_width)/2);
		
		
		$html.="<tr >";
		
		
		if($table_width!=100)
		{
		$html.="<td  width=\"".$hidden_table_width."%\" style=\"\">&nbsp;</td>";
		}
		$html.="<td width=\"".$table_width."%\" ><table align='".$pos."'  width=\"100%\"><tr>";
		
		
		
		if($table_rows_hidden[0][countvalue]>0 && $pos=='right')
		for($i=0;$i<$table_rows_hidden[0][countvalue];$i++)
		{
		$html.='<td><table  cellspacing="4" cellspadding="4" ><tr><td colspan="2"></td></tr><tr><td width="'.$td_width.'" >&nbsp;</td><td width="'.$td_width.'" >&nbsp;</td></tr></table></td>';
		}

	$number=0;
		foreach($table_rows as $table_row)
		{
		$number++;
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
			
			if($table_row['visibility']==1 && $table_row['display']==1)
			{

				$disp = '2';
			

			}
			else if($table_row['visibility']==0 && $table_row['display']==1)
			{
				 $disp = '1';
				 
			}
			else if($table_row['display']==0 && $table_row['visibility']==0)
			{
				$disp = '0';
				
				
			
			}
			
			
			
			if($disp!='0')
			{                   
				$html.="<td ><table  cellspacing=\"4\" cellspadding=\"4\">";
				
				
				
				
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
							
								$rspct = $obj->GetSingleData("spssp_respect", "title"," id=".$item_info['respect_id']);
							
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
							
							
							$memo=$item_info['memo'];
							
							$full_name=$item_info['first_name']." ".$item_info['last_name']." ".$rspct;
							$chr_in_comment=mb_strlen($comment);
							
							
							
							$chr_in_fullname=mb_strlen($full_name);
							
							$chr_in_menu_name=mb_strlen($menu_name);
							
							$chr_in_memo=mb_strlen($memo);
							
							
								if($chr_in_fullname)
								$font_size_fullname=(int)(((1/$num_of_table_in_row)*(1/$chr_in_fullname))*8000);
								
								if($chr_in_menu_name)
								$font_size_menu_name=(int)(((1/$num_of_table_in_row)*(1/$chr_in_menu_name))*3500);
								
								if($chr_in_memo)
								$font_size_memo=(int)(((1/$num_of_table_in_row)*(1/$chr_in_memo))*3500);
								 
								if($chr_in_comment)
								$font_size_comment=(int)(((1/$num_of_table_in_row)*(1/$chr_in_comment))*12000);
								
								
						
								
								if($disp=='1')
								$gift_groups=$menu_name=$memo="";
								
								
							
								if($menu_name=="-")
								$font_size_menu_name=100;
								
								if($comment1=="-")
								$font_size_comment1=100;
							
								if($comment2=="-")
								$font_size_comment2=100;
								
								if($font_size_fullname>100)
								$font_size_fullname=100;
								
								if($font_size_comment>100)
								$font_size_comment=100;
								
								
								
								if($font_size_menu_name>100)
								$font_size_menu_name=100;
								
								if($font_size_memo>100)
								$font_size_memo=100;
								
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
							if(array_key_exists($menu_name,$group_menu_array))
							{
								$group_menu_array[$menu_name]=$group_menu_array[$gift_groups]+1;
							}
							
							if($seats_nums==0)
							{
								$left_string="";
								$middle_string="";
								
								$left_string.="<span style=\"font-size:".$font_size_memo."%;\">".$memo."</span><br/>";
								
								$left_string.="<span style=\"font-size:".$font_size_menu_name."%;\">".$menu_name."</span><br/>";
							
								$left_string.="<span style=\"font-size:".$font_size_fullname."%;\">".$gift_groups."</span><br/>";
								
								
								
								$middle_string.="<span style=\"font-size:".$font_size_comment."%;\" >".$item_info['comment1']."</span><br/><span style=\"font-size:".$font_size_comment."%;\" >".$item_info['comment2']."</span><br/>";
								
								$middle_string.="<b style=\"font-size:".$font_size_fullname."%;\" >".$item_info['first_name']." ".$item_info['last_name']." ".$rspct."</b><br/>";
								
								
								$html2.="<td width=\"52.63%\">
								<table  cellspadding=\"1\" ><tr>
								
								<td valign=\"middle\" style=\"width:27.5%; text-align:center; border:1px solid black;\">".$left_string."</td><td style=\" width:63.5%;text-align:center;   border:1px solid black; \">".$middle_string."</td><td style=\"width:10%;  border:1px solid black;\">&nbsp;</td>
								</tr>
								</table>
								</td>";
							}
							else
							{
								
								$right_string="";
								$middle_string="";
								
								$right_string.="<span style=\"font-size:".$font_size_memo."%;\">".$memo."</span><br/>";
								
								$right_string.="<span style=\"font-size:".$font_size_menu_name."%;\">".$menu_name."</span><br/>";
								
								$left_string.="<span style=\"font-size:".$font_size_fullname."%;\">".$gift_groups."</span><br/>";
								
								if($item_info['comment1']!="" || $item_info['comment2']!="" )
								$middle_string.="<span style=\"font-size:".$font_size_comment."%;\" >".$item_info['comment1']."</span><br/><span style=\"font-size:".$font_size_comment."%;\" >".$item_info['comment2']."</span><br/>";
								
								if($item_info['first_name']!='')
								$middle_string.="<b style=\"font-size:".$font_size_fullname."%;\" >".$item_info['first_name']." ".$item_info['last_name']." ".$rspct."</b><br/>";
								
								$html2.="<td  width=\"47.37%\">
								<table  cellspadding=\"1\" ><tr>
								<td   style=\"width:69.44%;text-align:center;   border:1px solid black; \">".$middle_string."</td><td  valign=\"middle\" style=\"width:31.56%;text-align:center;  border:1px solid black;  \">".$right_string."</td>
								</tr>
								</table>
								</td>";
							}
						
							$guest_num++;
						}
						else
						{
						
						$html2.="<td  >&nbsp;</td>";
						
						
						}
						
						
						if($seats_nums==1)
						$html2.="</tr>";
						
					
						$seats_nums++;
						
						if($seats_nums==2)
							$seats_nums=0; 
					
						
						
						
				
				
				}
				
				
				if($disp=='1')
				$guest_num="&nbsp;";
				else
				$guest_num ='【 '.$guest_num.'名 】';
				
				if($seats_nums==1)
				$html2.="<td></td></tr>";
			
			if($disp!='1')
			{
				$html.="<tr><td colspan=\"2\" align=\"center\"><table><tr>";
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
				$html.="</tr></table></td></tr>";
			
			}
			
				
			$html.='<tr><td colspan="2" align="center">'.$tblname .$guest_num.'</td></tr>';
			$html.=$html2;	
			
			
			$html.="</table></td>";
           }                    
			
			
		}
	
	
	if($table_rows_hidden[0][countvalue]>0 && $pos=='left')
		for($i=0;$i<$table_rows_hidden[0][countvalue];$i++)
		{
		
		$html.='<td><table  cellspacing="4" cellspadding="4" ><tr><td colspan="2" align="center"></td></tr><tr><td width="'.$td_width.'" >&nbsp;</td><td width="'.$td_width.'" >&nbsp;sajdsadjasjdksajdjsajldk</td></tr></table></td>';
		}	
		
	$html.="</tr></table></td></tr>";
		
	}






 $html.="</table>";







$samplefile="sam_".$plan_id."_".rand()."_".time().".txt";
 
 $handle = fopen("../cache/".$samplefile, "x");
 
 if(fwrite($handle, $html)==true)
 {
 	fclose($handle);
	
 	$utf8text = file_get_contents("../cache/".$samplefile, false);
	
 }

@unlink("../cache/".$samplefile);

$pdf->writeHTML($utf8text, true, false, true, false, '');

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('example_001.pdf', 'D');
?> 