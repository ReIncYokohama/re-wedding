<?php
include("admin/inc/dbcon.inc.php");
include("admin/inc/class.dbo.php");
$obj = new DBO();
$post = $obj->protectXSS($_POST);
$row = $obj->GetSingleRow("spssp_default_plan", " id=".$post['planid']);

$row_number = $row['row_number'];

$row_width = (int) (970/$row_number);
$row_width = $row_width-6;
$seat_width = (int) ($row_width/2);
$seat_width = $seat_width-6;

$room_rows = $row['row_number'];
$room_tables = $row['column_number'];
$room_seats = $row['seat_number'];


$num_tables = $room_rows * $room_tables;

$table_rows = $obj->getRowsByQuery("select * from spssp_default_plan_table where room_id = ".$row['room_id']." order by id asc limit 0, $num_tables");

$details =	$obj->getRowsByQuery("select * from spssp_default_plan_details where plan_id=".$row['id'] );

$arr = array();
foreach($details as $dt)
{
	$seatid = $dt['seat_id'];
	$guestid = $dt['guest_id'];
	$arr[$seatid]= $guestid;
}


?>
<div class="rows" style="width:<?=$row_width?>px;  float:left; border-left:solid 1px #666666;"> 	
			<?php
			$i=1;
			$count = count($table_rows);

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
							<div id="<?=$seat['id']?>" class="droppable" style="width:<?=$seat_width?>px">
								<?php
								$key = $seat['id'];
								if(isset($arr) && $arr[$key] != '')
								{
								
									$item = $arr[$key];
									$item_info =  $obj->GetSingleRow("spssp_default_guest", " id=".$item);
									?>
								
											<b style="font-size:10px;">
												<?php echo $item_info['name'];?>
                                            </b>
											
	
								<?php
								}
							?>    
							</div>
						<?php
						}
					?>
				</div>
			<?php
				if($i % $room_tables == 0 && $i !=0 && $i != $count)
				{
					echo "</div> <div class='rows' style='width:".$row_width."px'> ";
				}
			$i++;
			}

			?>
		
	</div>