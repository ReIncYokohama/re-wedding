<?php
	session_start();
	include("../admin/inc/dbcon.inc.php");
	include("../inc/checklogin.inc.php");
	include("../admin/inc/class.dbo.php");

	$userid = $_SESSION['userid'];
	$obj = new DBO();
	
	$sql= "select * from spssp_admin_messages order by display_order DESC limit 0,5";
	$data = $obj->getRowsByQuery($sql);
	
	$date_msg = date("Y年m月d日",mktime($row['creation_date']));
                    	
	$party_day = $obj->GetSingleData("spssp_user","party_day"," id = ".$userid);
	$gift_criteria = $obj->GetSingleRow("spssp_gift_criteria", " id=1");
	
	
	$confirm_day_num = $obj->GetSingleData("spssp_options" ,"option_value" ," option_name='confirm_day_num'");
	$user_plan_row = $obj->GetSingleRow("spssp_plan"," user_id= ".$userid);
	$user_plan_row_count = $obj->GetRowCount("spssp_plan"," user_id= ".$userid);
	if($user_plan_row_count&&$user_plan_row['final_proof'])
	{
		$mydate = $user_plan_row['final_proof'];
	}
	else
	{
		$mydate = $confirm_day_num;
	}
	
	
	
	
?>			
	<div align="center">
		<?php 
		if($party_day>date("Y-m-d-"))
		{		
			$day = strftime('%d',strtotime($party_day));
			$month = strftime('%m',strtotime($party_day));
			$year = strftime('%Y',strtotime($party_day));
			$lastmonth = mktime(0, 0, 0, $month, $day-$gift_criteria['order_deadline'], $year);
			$dateBeforeparty = date("Y-m-d",$lastmonth);
			
			//echo "<div style='float:left;'><b>".LAST_GIFT_PRESENT_DATE_MSG;
			//$obj->japanyDateFormate($dateBeforeparty);
			//echo "</b></div>";
		}
		if($mydate)
		{
			$day = strftime('%d',strtotime($party_day));
			$month = strftime('%m',strtotime($party_day));
			$year = strftime('%Y',strtotime($party_day));
			$lastmonth = mktime(0, 0, 0, $month, $day-$mydate, $year);
			$dateBeforeparty = date("Y-m-d",$lastmonth);
			
			
			//echo "<div style='float:right;'><b>".LAST_GIFT_PRESENT_DATE_EXCEED_MSG;
			//$obj->japanyDateFormate($dateBeforeparty);
			//echo "</b></div>";
		}
		?>	
	</div>		
<?php			
			
			foreach($data as $row)
			{?>
							<div class="whatsnew_article">
      		<div class="whatsnew_date"><? //$date_msg?></div>
				<div class="whts_new_maintopic"><?=$row['title']?><br />
					<?=$row['description']?>
				</div>
				<div class="clear"></div>
    		</div>
						<?php }
?>
          
