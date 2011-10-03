<?php
	include_once("inc/dbcon.inc.php");
	
	include_once("inc/checklogin.inc.php");
	
	include_once("inc/plan.header.inc.php");
	
	include_once("inc/class.dbo.php");
	
		$obj = new DBO();
	
	
	
	$get = $obj->protectXSS($_GET);

	$plan_id = $get['plan_id'];
	$plan_row = $obj->GetSingleRow("spssp_default_plan", " id =".$plan_id);
	
	$room_rows = $plan_row['row_number'];
	$room_tables = $plan_row['column_number'];
	$room_seats = $plan_row['seat_number'];
	
	$num_tables = $room_rows * $room_tables;
	
	$table_rows = $obj->getRowsByQuery("select * from spssp_default_plan_table where room_id = ".$plan_row['room_id']." order by id asc limit 0, $num_tables");
	
	$details =	$obj->getRowsByQuery("select * from spssp_default_plan_details where plan_id=".$plan_id );
	
	$arr = array();
	foreach($details as $dt)
	{
		$seatid = $dt['seat_id'];
		$guestid = $dt['guest_id'];
		$arr[$seatid]= $guestid;
	}
?>
<link href="../css/plan_admin.css" rel="stylesheet" type="text/css" />

<div id="nav">
	<a href="manage.php" >Home</a>  &raquo; <a href="rooms.php" >Rooms</a> &raquo; <a href="plans.php?room_id=<?=$plan_row['room_id']?>"> Default Plans</a> &raquo; View Plan
</div>

<div id="room" style="width:100%; background-color:#FFFFFF">
<div><h3><a href="export_file.php?plan_id=<?php echo $plan_id;?>">Download In Excel File</a></h3></div>   
        <div class="rows" style="width:24%; padding:3px;"> 	
			<?php
			$i=1;
			foreach($table_rows as $table_row)
			{
			
			?>
				<div class="tables">
					<p align="center" style="text-align:center"><b><?=$table_row['name']?></b></p>
					<?php
						$seats = $obj->getRowsByQuery("select * from spssp_default_plan_seat where table_id =".$table_row['id']." order by id asc limit 0,$room_seats");
						foreach($seats as $seat)
						{
						?>
							<div id="<?=$seat['id']?>" class="droppable" style="width:82px; padding:1px">
								<?php
								$key = $seat['id'];
								if(isset($arr) && $arr[$key] != '')
								{
								
									$item = $arr[$key];
									$item_info =  $obj->GetSingleRow("spssp_default_guest", " id=".$item);
									?>
								
											<b style="font-size:10px;">
												<?php echo $item_info['name'];?>
                                            <b>
											
	
								<?php
								}
							?>    
							</div>
						<?php
						}
					?>
				</div>
			<?php
				if($i % $room_tables == 0 && $i !=0)
				{
					echo "</div> <div class='rows'>";
				}
			$i++;
			}

			?>
		
	</div>
<?php
	include_once("inc/new_footer.php");
?>
