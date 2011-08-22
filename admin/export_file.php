<?php
@session_start();
include_once("admin/inc/class.dbo.php");
include_once("inc/checklogin.inc.php");

$obj = new DBO();
$get = $obj->protectXSS($_GET);
$user_id = (int)$_SESSION['userid'];

	$plan_id = $obj->GetSingleData("spssp_plan", "id","user_id=".$user_id);

	$plan_row = $obj->GetSingleRow("spssp_plan", " id =".$plan_id);
	
	
	$room_rows = $plan_row['row_number'];

	$row_width = $row_width-6;
	
	$table_width = (int)($row_width/2);
	$table_width = $table_width-6;
	
	$room_tables = $plan_row['column_number'];
	$room_tables=$room_tables+2;
	$room_width = (int)(230*(int)$room_tables)."px";
	
	
	$row_width = (int)(200*$room_tables);
	$content_width = ($row_width+235).'px';
	
	$room_seats = $plan_row['seat_number'];
	
	$num_tables = $room_rows * $room_tables;

$tblrows = $obj->getRowsByQuery("select distinct row_order from spssp_table_layout where user_id = ".(int)$user_id);
//echo "<pre>";
//print_r($tblrows);







$html .= '<table id="room" style="float:left; width:'.$room_width.'; ">';    
					$q=1;
                    foreach($tblrows as $tblrow)
                    {
                       $ralign = $obj->GetSingleData("spssp_table_layout", "align"," row_order=".$tblrow['row_order']." and user_id=".$user_id." limit 1");					
						if($ralign == 'C')
						{
							$num_none = $obj->GetNumRows("spssp_table_layout","user_id=".$user_id." and row_order=".$tblrow['row_order']." and display=0");
							if($num_none > 0)
							{
								$con_width = $row_width -((int)($num_none*178));
							}
							else
							{
								$con_width = $row_width;
							}
							
							$pos = 'align="center" ';
						}
						else if($ralign=='R')
						{
							$pos = 'align="right"';
						}
						else
						{
							$pos = 'align="left"';
							
						}
						
						
						/*if($ralign == 0)
						{
							
							if($num_none>0)
							{
								$con_width = $row_width -((int)($num_none*178));
								$con_style = "width:".$con_width."px;margin:0 auto;";
							}
							else
							{
								$con_style="";
							}
						}*/
						
    
                     
             $html .= '<tr>';
			
			$html.=' <td style="float:left;width:100%;" id="row_'.$tblrow['row_order'].'">
                		
                		<table id="rowcon_'.$tblrow['row_order'].'" '.$pos.'><tr>'; 
                    	
						 if($ralign == 'C')
						{
							$html .= '<td width="100px"></td>';
						}
						else if($ralign=='R')
						{
							$html .= '<td width="100px"></td><td width="100px"></td>';
						}
						else
						{
							$html .= '';
							
						}
                  		 	$table_rows = $obj->getRowsByQuery("select * from spssp_table_layout where user_id = ".(int)$user_id." and row_order=".$tblrow['row_order']." order by  column_order asc");
                  
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
		
									$disp = 'display:block;';
									$class = 'style="float:left;background-color:#F5F8E5;border:1px solid #A2A78C;margin:1px 2px;overflow:hidden;width:80px;height:30px;font-size:10px;line-height:10px;"';
									//$class="droppable";
		
								}
								else if($table_row['visibility']==0 && $table_row['display']==1)
								{
									$disp = 'visibility:hidden;';
									 $class = 'seat_droppable';
								}
								else if($table_row['display']==0 && $table_row['visibility']==0)
								{
									$disp = 'display:none;';
									$class = 'seat_droppable';
								}                    
                    														
                    $html .=  '<td><table style="width:200px;float:left;" id="tid_'.$table_row['id'].'" style="'.$disp.'"><tr>
                                <td colspan="3" align="center" style="text-align:center;font-size:12px;margin-top:0;margin-bottom:0;vertical-align:middle;" id="table_'.$table_row['id'].'">
        
                                    <b><a href="#" style="cursor:default; text-decoration:none">'.$tblname.'</a> </b>
                                </td></tr>';
                            	
                                //echo $disp;
                                $seats = $obj->getRowsByQuery("select * from spssp_default_plan_seat where table_id =".$table_row['table_id']." order by id asc limit 0,$room_seats");
                                $p=1;
								foreach($seats as $seat)
                                {
                                	if($p%2==1)
									{
										$html.="<tr>";
									}
									
                                    $html .='<td id="'.$seat['id'].'" '.$class.'>';
                                      
                                        $key = $seat['id']."_input";
                                        if(isset($_SESSION['cart'][$key]) && $_SESSION['cart'][$key] != '')
                                        {
                                            $itemArray = explode("_", $_SESSION['cart'][$key]);
                                           
                                            $item = $itemArray[1];
                                            $item_info =  $obj->GetSingleRow("spssp_guest", " id=".$item);
                                            
											include("inc/main_dbcon.inc.php");
                                            $rspct = $obj->GetSingleData("spssp_respect", "title"," id=".$item_info['respect_id']);
											include("inc/return_dbcon.inc.php");
                                            //echo $item_info['id'].'<br>';
                                            $edited_nums = $obj->GetNumRows("spssp_guest", "edit_item_id=".$item_info['id']." and user_id=".(int)$user_id);
                                            //echo '<p>'.$edited_nums.'</p>';
                                            
                                            if($edited_nums > 0)
                                            {
                                                $guest_editeds = $obj->GetSingleRow("spssp_guest", "edit_item_id=".$item_info['id']." and user_id=".(int)$user_id);
                                                $item_info['id']=$guest_editeds['id'];
                                                $item_info['sub_category_id']=$guest_editeds['sub_category_id'];
                                                $item_info['name']=$guest_editeds['name'];
                                                                                                        
                                            }
                                            
                                            
                                       
                                            $html .= '<ul id="abc" style="float:left; margin:0; padding:0;">
                                                <li style="float:left; padding:0;margin:0;" id="item_'.$item_info['id'].'" 
                                                    title="subul_'.$item_info['sub_category_id'].'" style="width:80px; height:25px;">
                                                    <span style="border:0">'.$item_info['last_name'].' '.$item_info['first_name'].' '.$rspct.'</span>
                                                    
                                                </li>
                                            </ul>';
                                       
                                        }
                                   
                                  $html .= '</td>';
                               if($p%2==0)
							   {$html.="<td>&nbsp;&nbsp;&nbsp;</td></tr>";}
                               $p++; }
                            	
                        	$html .= '</tr></table>';
						 
                           }
                      if($ralign == 'C')
					{
						$html .= '<td width="100px"></td>';
					}
					else if($ralign=='R')
					{
						$html .= '';
					}
					else
					{
						$html .= '<td width="100px"></td><td width="100px"></td>';
						
					}                                     
                	 $html .= '</tr></table>';
                	$html .= '</td>';
					
                }
                        
            	
   $html .= '</table>';





//$p='<table style="background:#ccc;"><tr><td>Likhon</td></tr></table>';

















 $File = "Yourexcel.html"; 
 $Handle = fopen($File, 'w');
 fwrite($Handle, $html); 
 fclose($Handle);
 
 redirect("testclass.php");
 
?>	
	
	
	
