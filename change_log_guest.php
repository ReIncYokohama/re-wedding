<?php
@session_start();

require_once("admin/inc/class.dbo.php");
include_once("admin/inc/dbcon.inc.php");
include_once("inc/checklogin.inc.php");
include("inc/new.header.inc.php");
$obj = new DBO();

	$user_id = (int)$_SESSION['userid'];
	$table='spssp_guest_log';
	$where = " user_id=".$user_id;
	if($_GET['guest_id'])
	{
		$where.= " and guest_id='".(int)$_GET['guest_id']."'";
	}
	
	if($_GET['date'])
	{
		$where.= " and datetime_log like '".$_GET['date']."%'";
	}
	
	
	$where2 = " user_id=".$user_id;
	if($_GET['guest_id'])
	{
		$where2.= " and guest_id='".(int)$_GET['guest_id']."'";
	}
	
	if($_GET['date'])
	{
		$where2.= " and date like '".$_GET['date']."%'";
	}
	
	$data_per_page=10;
	$current_page=(int)$_GET['page'];
	$redirect_url = 'change_log.php?guest_id='.$_GET['guest_id'].'&date='.$_GET['date'];
	
	$pageination = $obj->pagination($table, $where, $data_per_page,$current_page,$redirect_url);
	


	$order="datetime_log DESC";
	//$query_string="SELECT * FROM spssp_guest_log where $where ORDER BY $order LIMIT ".((int)($current_page)*$data_per_page).",".((int)$data_per_page).";";
	$query_string="SELECT * FROM spssp_guest_log where $where ORDER BY $order ;";
	$data_rows = $obj->getRowsByQuery($query_string);
	
	
	$order2="date DESC";
	//$query_string="SELECT * FROM spssp_change_log where $where ORDER BY $order LIMIT ".((int)($current_page)*$data_per_page).",".((int)$data_per_page).";";
	$query_string="SELECT * FROM spssp_change_log where $where2 ORDER BY $order2;";
	$data_rows2 = $obj->getRowsByQuery($query_string);
	
	
	//echo $query_string;
	
	//echo '<pre>';

?>
<script>
		$("ul#menu li").removeClass();
		$("ul#menu li:eq(7)").addClass("active");

</script>

<style>
.datepickerControl table
{
width:200px;
}
.input_text
{
	width:100px;
}
.select
{
	width:122px;
}
.datepicker
{
width:100px;
}
.timepicker
{
width:100px;
}
</style>
<script type="text/javascript" src="admin/calendar/calendar.js"></script>


<script type="text/javascript" language="javascript" src="datepicker/prototype-1.js"></script>

<script type="text/javascript" language="javascript" src="datepicker/prototype-date-extensions.js"></script>
<script type="text/javascript" language="javascript" src="datepicker/behaviour.js"></script>

<script type="text/javascript" language="javascript" src="datepicker/datepicker.js"></script>
<script type="text/javascript">

Control.DatePicker.Locale['ahad'] = { dateTimeFormat: 'yyyy-MM-dd HH:mm', dateFormat: 'yyyy-MM-dd', firstWeekDay: 1, weekend: [0,6], language: 'ahad'};

Control.DatePicker.Language['ahad'] = { months: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'], days:[  '日', '月','火', '水', '木', '金','土'], strings: { 'Now': '今度', 'Today': '今日', 'Time': '時間', 'Exact minutes': '正確な分', 'Select Date and Time': '閉じる', 'Open calendar': 'オープンカレンダー' } };



</script>

<link rel="stylesheet" href="datepicker/datepicker.css">
<script type="text/javascript" language="javascript" src="datepicker/behaviors.js"></script>



<div id="main_contents">
<div class="title_bar">
    	<div class="title_bar_txt_L">席次表データログ</div>
    		<div class="clear"></div></div>


<div style="width:100%; text-align:center">


<form action="change_log_guest.php" method="get">
           <table  cellpadding="5" cellspacing="5" style="width:600px;" >
		   <tr>
				<td  style="width:200px;"  valign="top"><label for="Guest Name">招待者名:</label> 
					
					<select name="guest_id" id="guest_id">
					<option value="">選択</option>
					<?php
					$query_string="SELECT last_name,first_name,id FROM spssp_guest where user_id=".$user_id.";";
					$guest_rows = $obj->getRowsByQuery($query_string);
					foreach($guest_rows as $guest)	
						{
					?>
							<option value="<?=$guest[id]?>" <?php if($_GET['guest_id']==$guest[id]) { ?> selected="selected" <?php } ?>><?=$guest[last_name]." ".$guest[first_name]?></option>
					<?php
						}
					?>
					</select>
			　	</td>
			
				<td  style="width:200px;"  valign="top"><label for="Date">日付</label> 
						<input type="text" name="date" value="<?=$_GET['date']?>" id="date"  style="background: url('img/common/icon_cal.gif') no-repeat scroll right center rgb(255, 255, 255); padding-right: 20px;" class="datepicker"/>
						
				　	</td>
				</td>
			
				<td width="100" valign="top">
				<input type="submit" name="submit" value="検索" />
						
				　</td>
				
				
			</tr>
		
		</table>
		</form>
<div id="contents"> 
	
		<?php $data_user = $obj->GetSingleRow("spssp_user", "id=".$user_id);?>
		
        <div class="box_table">
      		<div><?=$data_user['man_firstname']."-".$data_user['man_lastname']?></div>
      		
			<!--<div style="text-align:right;">< ?=$pageination?></div>-->
            
      		<div class="box4">
                <table border="0" width="875" align="center" cellpadding="1" cellspacing="1">
                    <tr align="center">
                        
                        <!--<td  width="10%">Plan name</td>-->
                        <td  width="15%" bgcolor="#993300" style="color:#FFFFFF">アクセス日時</td>
                        <td  width="15%" bgcolor="#993300" style="color:#FFFFFF">ログイン名</td>
                        <td  width="13%" bgcolor="#993300" style="color:#FFFFFF">アクセス画面名</td>
                        <td  width="13%" bgcolor="#993300" style="color:#FFFFFF">修正対象者</td> 
						<td  width="13%" bgcolor="#993300" style="color:#FFFFFF">修正種別</td>  
						<td  width="10%" bgcolor="#993300" style="color:#FFFFFF">変更項目名</td>
						<td  width="10%" bgcolor="#993300" style="color:#FFFFFF">変更前情報</td>
						<td  width="10%" bgcolor="#993300" style="color:#FFFFFF">変更後情報</td>  
						               
                       
                    </tr>
                </table>
        	</div>
            <?php
				$i=0;$j=$current_page*$data_per_page+1;	
				foreach($data_rows as $row)	
				{
					if($i%2==0)
					{
						$bgcolor="#CEFFFF";
					}
					else
					{
						$bgcolor="#FFFFFF";
					}
					$guest_name=$obj->GetSingleData("spssp_guest","last_name"," id='".$row['guest_id']."'");
					$guest_name2=$obj->GetSingleData("spssp_guest","first_name"," id='".$row['guest_id']."'");
					
					$guest_name.="&nbsp;".$guest_name2;
				
			?>
                    <div  style="background:<?=$bgcolor?>">
                        <table width="875"  border="0" align="center" cellpadding="1" cellspacing="1">
                            <tr align="center">
                           <td  width="15%">
                            	<?=$row[datetime_log]?>
                            </td>
							<td  width="15%">
                            	大久保 和彦（hotel staff）
                            </td>
							<td  width="13%">
                            	招待者リストの作成
                            </td>
                       
                            <td  width="13%"><?=$guest_name?></td>
							<td  width="13%">
                            	変更
                            </td>
                            <td   width="10%">
								<?php
									$before_log_array=explode("|",$row['before_log']);
									$after_log_array=explode("|",$row['after_log']);
									foreach($before_log_array as $key=>$value)
									{
										if($value!=$after_log_array[$key])
										{
											if($key==0)
											{
												echo "新婦側<br/>";
											}
											if($key==1)
											{
												echo "姓<br/>";
											}
											
											if($key==2)
											{
												echo "名<br/>";
											}	
											if($key==3)
											{
											
												echo "敬称<br/>";
											}
											if($key==4)
											{
												
												echo "区分<br/>";
											}
											if($key==5)
											{
												echo "肩書 1<br/>";
											}
											if($key==6)
											{
												echo "肩書 2<br/>";
											}
											if($key==7)
											{
												
												echo "特記<br/>";
											}
											if($key==8)
											{
												
												echo "引出物<br/>";
											}
											if($key==9)
											{
												
												echo "料理<br/>";
											}
											if($key==10)
											{
												
												echo "席種別<br/>";
											}
											if($key==11)
											{
												
												echo "高砂席名<br/>";
											}
										}
									}
								?>
							</td>
							 <td   width="10%">
								<?php
									$before_log_array=explode("|",$row['before_log']);
									$after_log_array=explode("|",$row['after_log']);
									foreach($before_log_array as $key=>$value)
									{
										if($value!=$after_log_array[$key])
										{
											if($key==0)
											{
												echo $value."<br/>";
											}
											if($key==1)
											{
												echo $value."<br/>";
											}
											
											if($key==2)
											{
												echo $value."<br/>";
											}	
											if($key==3)
											{
												include("admin/inc/main_dbcon.inc.php");
												$respect = $obj->GetSingleData(" dev2_main.spssp_respect ", "title", " id='".$value."'");
												include("admin/inc/return_dbcon.inc.php");
												echo $respect."<br/>";
											}
											if($key==4)
											{
												include("admin/inc/main_dbcon.inc.php");
												$guest_type = $obj->GetSingleData(" dev2_main.spssp_guest_type ", "name", " id='".$value."'");
												include("admin/inc/return_dbcon.inc.php");
												echo $guest_type."<br/>";
											}
											if($key==5)
											{
												echo $value."<br/>";
											}
											if($key==6)
											{
												echo $value."<br/>";
											}
											if($key==7)
											{
												
												echo $value."<br/>";
											}
											if($key==8)
											{
												$gift_name = $obj->GetSingleData(" spssp_gift_group ", "name", " id='".$value."' and  user_id = ".$user_id);
												echo $gift_name."<br/>";
											}
											if($key==9)
											{
												$menu_name = $obj->GetSingleData(" spssp_menu_group ", "name", " id='".$value."' and user_id = ".$user_id);
												echo $menu_name."<br/>";
											}
											if($key==10)
											{
												$stage=($value==0)?"招待席":"高砂席";
												echo $stage."<br/>";
											}
											if($key==11)
											{
												if($value=="1")	
												$stage_guest="媒酌人1";
												if($value=="2")	
												$stage_guest="媒酌人2";
												if($value=="3")	
												$stage_guest="媒酌人3";
												if($value=="4")	
												$stage_guest="媒酌人4";
												if($value=="5")	
												$stage_guest="お子様";
												echo $stage_guest."<br/>";
											}
										}
									}
								?>
							</td>
                            <td  width="10%"> 
								<?php
									$before_log_array=explode("|",$row['before_log']);
									$after_log_array=explode("|",$row['after_log']);
									foreach($after_log_array as $key=>$value)
									{
										if($value!=$before_log_array[$key])
										{
											if($key==0)
											{
												echo $value."<br/>";
											}
											if($key==1)
											{
												echo $value."<br/>";
											}
											
											if($key==2)
											{
												echo $value."<br/>";
											}	
											if($key==3)
											{
												include("admin/inc/main_dbcon.inc.php");
												$respect = $obj->GetSingleData(" dev2_main.spssp_respect ", "title", " id='".$value."'");
												include("admin/inc/return_dbcon.inc.php");
												echo $respect."<br/>";
											}
											if($key==4)
											{
												include("admin/inc/main_dbcon.inc.php");
												$guest_type = $obj->GetSingleData(" dev2_main.spssp_guest_type ", "name", " id='".$value."'");
												include("admin/inc/return_dbcon.inc.php");
												echo $guest_type."<br/>";
											}
											if($key==5)
											{
												echo $value."<br/>";
											}
											if($key==6)
											{
												echo $value."<br/>";
											}
											if($key==7)
											{
												
												echo $value."<br/>";
											}
											if($key==8)
											{
												$gift_name = $obj->GetSingleData(" spssp_gift_group ", "name", " id='".$value."' and  user_id = ".$user_id);
												echo $gift_name."<br/>";
											}
											if($key==9)
											{
												$menu_name = $obj->GetSingleData(" spssp_menu_group ", "name", " id='".$value."' and user_id = ".$user_id);
												echo $menu_name."<br/>";
											}
											if($key==10)
											{
												$stage=($value==0)?"招待席":"高砂席";
												echo $stage."<br/>";
											}
											if($key==11)
											{
												if($value=="1")	
												$stage_guest="媒酌人1";
												if($value=="2")	
												$stage_guest="媒酌人2";
												if($value=="3")	
												$stage_guest="媒酌人3";
												if($value=="4")	
												$stage_guest="媒酌人4";
												if($value=="5")	
												$stage_guest="お子様";
												echo $stage_guest."<br/>";
											}
										}
									}
								?>
							</td>
                         
                            
                            
                           </tr>
                        </table>
                    </div>
             <?php
			 	$i++;$j++;
			 	}
			 ?>
			  <?php
				$i=0;$j=$current_page*$data_per_page+1;	
				foreach($data_rows2 as $row)	
				{
					if($i%2==0)
					{
						$class = 'box5';
					}
					else
					{
						$class = 'box6';
					}
					$user_id=$obj->GetSingleData("spssp_user","user_id"," id='".$row['user_id']."'");
					
					
					//$plan_name=$obj->GetSingleData("spssp_plan","name"," id='".$row['plan_id']."'");
					
					$guest_name=$obj->GetSingleData("spssp_guest","last_name"," id='".$row['guest_id']."'");
					$guest_name2=$obj->GetSingleData("spssp_guest","first_name"," id='".$row['guest_id']."'");
					
					$guest_name.="&nbsp;".$guest_name2;
					
					
					
					if($row[previous_status])
					{
						$table_id=$obj->GetSingleData("spssp_default_plan_seat","table_id"," id=".$row[previous_status]." limit 1");
						
						$table_details=$obj->getSingleRow("spssp_default_plan_table"," id=".$table_id." limit 1");
						
		
						
						$tbl_row = $obj->getSingleRow("spssp_table_layout"," table_id=".$table_details['id']." and user_id=".(int)$row['user_id']." limit 1");
						
						
						
						$new_name_row = $obj->getSingleRow("spssp_user_table"," default_table_id=".$tbl_row['id']." and user_id=".(int)$row['user_id']." limit 1");
						
						
						if(!empty($new_name_row))
						{
							$tblname_prev = $obj->getSingleData("spssp_tables_name","name","id=".$new_name_row['table_name_id']);
							
						}
						else
						{
							$tblname_prev = $tbl_row['name'];
							
						}
						
						
						$seats = $obj->getRowsByQuery("select * from spssp_default_plan_seat where table_id =".$table_id." order by id asc ");
						
						$j=1;
						foreach($seats as $seat)
									{
										
										if($seat['id']==$row[previous_status])
										{
											$seat_pos_prev=$j;
											break;	
										}
										$j++;
									}
					}			
					if($row[current_status])
					{			
						$table_id=$obj->GetSingleData("spssp_default_plan_seat","table_id"," id=".$row[current_status]." limit 1");
					
						$table_details=$obj->getSingleRow("spssp_default_plan_table"," id=".$table_id." limit 1");
		
						$tbl_row = $obj->getSingleRow("spssp_table_layout"," table_id=".$table_details['id']." and user_id=".(int)$row['user_id']." limit 1");
						$new_name_row = $obj->getSingleRow("spssp_user_table"," default_table_id=".$tbl_row['id']." and user_id=".(int)$row['user_id']." limit 1");
						if(!empty($new_name_row))
						{
							$tblname_current = $obj->getSingleData("spssp_tables_name","name","id=".$new_name_row['table_name_id']);
							
						}
						else
						{
							$tblname_current = $tbl_row['name'];
							
						}
						
						$seats = $obj->getRowsByQuery("select * from spssp_default_plan_seat where table_id =".$table_id." order by id asc ");
						
						$j=1;
						foreach($seats as $seat)
									{
										if($seat['id']==$row[current_status])
										{
											$seat_pos_current=$j;
											break;	
										}
										$j++;
									}
					}					
			?>
                    <div class="<?=$class?>">
                        <table width="875"  border="0" align="center" cellpadding="1" cellspacing="1">
                            <tr align="center">
                           
                            <!--<td  width="10%"><?=$plan_name?></td>-->
							<td  width="15%">
                            	<?=$row[date]?>
                            </td>
							<td  width="15%">
                            	お客様
                            </td>
							<td  width="13%">
                            	席次表編集
                            </td>
							
                            <td  width="13%"><?=$guest_name?></td>
							<td  width="13%"><?php
							if($tblname_prev=="")
							echo "新規";
							else
							echo "移動";
							?></td>
							<td  width="10%">席次表</td>
                            <td   width="10%">
							<?php
							if($tblname_prev!="")
							{
							?>
								<?=$tblname_prev; ?>/<?=$seat_pos_prev;?>
							<?php
								}
								else
								{
							?>	
								<font color="#FF0000">Blank</font>
								<?php
									}
								?>
							</td>
                            <td  width="10%"> 
							<?php
								if($tblname_current!="")
								{
								?>
								<?=$tblname_current; ?>/<?=$seat_pos_current;?>
							<?php
								}
								else
								{
							?>	
							<font color="#FF0000">Blank</font>
								<?php
									}
								?>	
							</td>
                         
                            
                            
                           </tr>
                        </table>
                    </div>
             <?php
			 	$i++;$j++;
			 	}
			 ?>
        </div>
                   
    </div>
</div>
</div>
</div>
<?php
include("inc/new.footer.inc.php");
?>