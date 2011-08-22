<?php
	include_once("inc/class.dbo.php");
	
	
	//include_once("inc/header.inc.php");
	
	
	
$obj = new DBO();
	$get = $obj->protectXSS($_GET);
	$user_id = (int)$_SESSION['userid'];
	
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
	
	$plan_id = $obj->GetSingleData("spssp_plan", "id","user_id=".$user_id);

	$plan_row = $obj->GetSingleRow("spssp_plan"," id =".$plan_id);
	
	$user_info = $obj->GetSingleRow("spssp_user"," id=".$user_id);
	
	$room_info=$obj->GetSingleRow("spssp_room"," id =".$plan_id);
	
	
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
	include("inc/main_dbcon.inc.php");
	$respects = $obj->GetAllRow( "spssp_respect");
	include("inc/return_dbcon.inc.php");
	
	
	
/*	$get = $obj->protectXSS($_GET);

	$plan_id = $get['plan_id'];
	$plan_row = $obj->GetSingleRow("spssp_default_plan", " id =".$plan_id);
	
	$room_info=$obj->GetSingleRow("spssp_room", " id =".$plan_row['room_id']);
	
	$room_rows = $plan_row['row_number'];
	$room_tables = $plan_row['column_number'];
	$room_seats = $plan_row['seat_number'];
	
	$num_tables = $room_rows * $room_tables;
	
	$table_rows = $obj->getRowsByQuery("select * from spssp_default_plan_table where room_id = ".$plan_row['room_id']." order by id asc limit 0, $num_tables");
	for($i_rows=0;$i_rows<$room_tables;$i_rows++)
	{
		for($rows_num=0;$rows_num<count($table_rows);)
		{
			
			$key=$i_rows+$rows_num;	
			$table_new_rows[$i_rows][]=$table_rows[$key];
			$rows_num=$rows_num+$room_tables;
		
		}
	}
	

		
	
	$details =	$obj->getRowsByQuery("select * from spssp_default_plan_details where plan_id=".$plan_id );
	
	$arr = array();
	foreach($details as $dt)
	{
		$seatid = $dt['seat_id'];
		$guestid = $dt['guest_id'];
		$arr[$seatid]= $guestid;
	}



*/

/*$html='<div style="margin:0;padding:0;">
	<div style="margin:0 auto;padding:0;">
  		<div style="padding:0;float:left;width:auto;">
    		<h1 style="margin:0;padding:29px 24px;float:left;color:#e4e5e5;font:bold 36px/1.2em Arial, Helvetica, sans-serif;letter-spacing:-3px;text-transform:uppercase;"><a href="#">SPSSP <span style="color:#00c6ff;">Desk</span></a> <small style="font:normal 12px/1.2em Arial, Helvetica, sans-serif;letter-spacing:normal;padding-left:32px;">Your Wedding Partner			</small></h1>
  		</div>
	</div>
</div>';*/

$td_width=(int)(72/$room_rows)*4;
$font_size_guest=(int)(25/$room_rows)*4;

$font_size_table_text=(int)(15/$room_rows)*4;



$html.='<table><tr>';
$html.='<td><table><tr><td >商品名</td>';
 
           
$group_rows = $obj->GetAllRowsByCondition("spssp_gift_group"," user_id=".$user_id);
$gift_rows = $obj->GetAllRowsByCondition("spssp_gift"," user_id=".$user_id);
	foreach($group_rows as $grp)
	{
	
	
		$html.='<td >'.$grp['name'].'</td>';
	
	}

            
	$html.='<td >×</td>
	<td >予備</td>
	<td  >合計</td>
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
				$htm = "yes";
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
			
            $html.='<td  >'.$htm.'</td>';
		}
          $html.='<td >&nbsp;</td>
            <td  >&nbsp;</td>
            <td  >'.$num_gifts.'</td>
          </tr>';
	}	  
		  
		    $html.='<tr>
            <td >グループ数</td>';
           
				$total = 0;
            	foreach($group_rows as $grp)
				{
					$num_guests_groups = $obj->GetNumRows(" spssp_guest_gift "," user_id = $user_id and group_id = ".$grp['id']);
					$total += $num_guests_groups;
					$html.="<td > $num_guests_groups </td>";
				}
			
            $html.='<td >';
				
                	$num_guests = $obj->GetNumRows(" spssp_guest "," user_id = $user_id ");
					$not_gifted = $num_guests - $total;
			$html.=$not_gifted;
					 
			
             $html.='</td>
            <td >&nbsp;</td>
            <td >'.$num_guests.'</td>
          </tr>';
         

 $html.='</table></td>';

$html.='<td><table style="border:1px solid black;"><tr><td align="center"  valign="middle" style="text-align:center;">Man name<br/>'.$user_info['man_firstname'].' '.$user_info['	man_lastname'].'</td><td>Woman Name<br/>'.$user_info['woman_firstname'].' '.$user_info['woman_lastname'].' </td></tr></table></td><td></td>';

$html.='</tr></table>';



$html.='<table  cellspacing="2" width="100%" >';		
			


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
		
		$html.="<tr ><td width='100%'><table align='".$pos."'  width='100%' cellspacing='5' cellpadding='5'><tr>";
		
		$table_rows = $obj->getRowsByQuery("select * from spssp_table_layout where user_id = ".(int)$user_id." and row_order=".$tblrow['row_order']." order by  column_order asc");
		
		$table_rows_hidden = $obj->getRowsByQuery("select count(id) as countvalue from spssp_table_layout where user_id = ".(int)$user_id." and row_order=".$tblrow['row_order']." and display='0' order by  column_order asc");
		
		
		if($table_rows_hidden[0][countvalue]>0 && $pos=='right')
		for($i=0;$i<$table_rows_hidden[0][countvalue];$i++)
		{
		$html.='<td><table><tr><td colspan="2"></td></tr><tr><td width="'.$td_width.'" >&nbsp;</td><td width="'.$td_width.'" >&nbsp;</td></tr></table></td>';
		}

		foreach($table_rows as $table_row)
		{
			$new_name_row = $obj->GetSingleRow("spssp_user_table", "user_id = ".(int)$user_id." and default_table_id=".$table_row['id']);
	
			if(isset($new_name_row) && $new_name_row['id'] !='')
			{
				$tblname_row = $obj->GetSingleRow("spssp_tables_name","id=".$new_name_row['table_name_id']);
				$tblname = $tblname_row['name'];
				$tblname .= " &nbsp; <span style = 'display:none;'>".$tblname_row['id']." </span>";
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
				$html.="<td><table>";
				
				if($disp=='1')
				$tblname="&nbsp;";
				$html.='<tr><td colspan="2" align="center">'.$tblname.'</td></tr>';
				
								   
				$seats = $obj->getRowsByQuery("select * from spssp_default_plan_seat where table_id =".$table_row['table_id']." order by id asc limit 0,$room_seats");
				$seats_nums=0;
				foreach($seats as $seat)
				{
					$key = $seat['id']."_input";
					
						$itemArray = explode("_", $_SESSION['cart'][$key]);
						$item_info=array();
						$edited_nums="";
						$item="";
						$item = $itemArray[1];
						if($item!='')
						{
							$item_info =  $obj->GetSingleRow("spssp_guest", " id=".$item);
							
							include("inc/main_dbcon.inc.php");
							$rspct = $obj->GetSingleData( "spssp_respect", "title"," id=".$item_info['respect_id']);
							include("inc/return_dbcon.inc.php");
						
							$edited_nums = $obj->GetNumRows("spssp_guest", "edit_item_id=".$item_info['id']." and user_id=".(int)$user_id);
						}
						
						
						if($edited_nums > 0)
						{
							$guest_editeds = $obj->GetSingleRow("spssp_guest", "edit_item_id=".$item_info['id']." and user_id=".(int)$user_id);
							$item_info['id']=$guest_editeds['id'];
							$item_info['sub_category_id']=$guest_editeds['sub_category_id'];
							$item_info['name']=$guest_editeds['name'];
																					
						}
						
						if($disp=='1')
						$item_info['first_name']=$item_info['last_name']=$rspct="&nbsp;";
						
						if($seats_nums==0)
						$html.="<tr>";
						
						
						if($item_info['first_name'])
						$html.="<td width='".$td_width."'><span style='font-size:".$font_size_guest."px;'>".$item_info['first_name']."<br/>".$item_info['last_name']."</span></td>";
						else
						$html.="<td width='".$td_width."'>&nbsp;</td>";
						
						
						if($seats_nums==1)
						$html.="</tr>";
						
					
						$seats_nums++;
						
						if($seats_nums==2)
							$seats_nums=0; 
					
						
						
						
				
				
				}
				if($seats_num==1)
				$html.="<td></td></tr>";
			
				
			
			
			$html.="</table></td>";
           }                    
			
			
		}
	if($table_rows_hidden[0][countvalue]>0 && $pos=='left')
		for($i=0;$i<$table_rows_hidden[0][countvalue];$i++)
		{
		
		$html.='<td><table><tr><td colspan="2" align="center"></td></tr><tr><td width="'.$td_width.'" >&nbsp;</td><td width="'.$td_width.'" >&nbsp;</td></tr></table></td>';
		}	
		
	$html.="</tr></table></td></tr>";
		
	}







echo $html.="</table>";



?>
