<?php
	include_once("inc/dbcon.inc.php");
	
	include_once("inc/checklogin.inc.php");
	

	include_once("inc/class.dbo.php");
	
	$obj = new DBO();
	
	
	//$id = (int)$_GET['id'];
	$table = "spssp_default_plan";
	
	/*$rooms = $obj->GetSingleRow("spssp_room", " id=".(int)$_GET['room_id']);
	
	if($id > 0)
	{
		$row = $obj->GetSingleRow($table,' id='.$id);
	}*/
	
	if(trim($_POST['name'])!= '')
	{
		$post = $obj->protectXSS($_POST);
		$post['display_order']= time();
		$post['creation_date'] = date("Y-m-d H:i:s");
		
		$room_id = (int)$_GET['room_id'];
		$room_row = $obj->GetSingleRow("spssp_room", " id = ".$room_id);
		
		
		if( (int) $post['row_number'] > $room_row['max_rows'])
		{
			redirect("plans.php?room_id=".$_POST['room_id']."&err=7");	
			exit;
		}
		if( (int) $post['column_number'] > $room_row['max_columns'] )
		{
			redirect("plans.php?room_id=".$_POST['room_id']."&err=8");	
			exit;
		}
		if( ( (int) $post['seat_number']) > $room_row['max_seats'] )
		{
			redirect("plans.php?room_id=".$_POST['room_id']."&err=9");	
			exit;
		}
		

		
		$lastid = $obj->InsertData($table,$post);
		
		
		
		$plan_row = $obj->GetSingleRow("spssp_default_plan", " id=".(int)$lastid);
		
		$room_rows = $plan_row['row_number'];

		$room_tables = $plan_row['column_number'];
		$row_width = (int)((115)*$room_tables);
		$room_seats = $plan_row['seat_number'];
		
		$num_tables = $room_rows * $room_tables;
		
		$table_rows = $obj->getRowsByQuery("select * from spssp_default_plan_table where room_id = ".$plan_row['room_id']." order by id asc limit 0, $num_tables");

		$num_layouts = $obj->GetNumRows("spssp_table_layout","default_plan_id= ".(int)$lastid);
		
		
		if($num_layouts <= 0)
		{
			
			$row_ord = 1;
			$column_ord = 1;
			$i = 1;
			$j = 1;
			for($i = 1; $i<= (int)$room_rows; $i++)
			{
			
				for($j=1; $j<= (int)$room_tables; $j++)
				{
					$tr = array_shift($table_rows);
					
					$lo_arr['plan_id'] = $plan_row['id'];
					$lo_arr['table_id'] = $tr['id'];
		
					$lo_arr['visibility'] = 1;
					$lo_arr['row_order'] = $i;
					$lo_arr['column_order'] = $j;
					$lo_arr['name'] = $tr['name'];
					$lo_arr['default_plan_id'] = $lastid;
				
					$lid = $obj->InsertData("spssp_table_layout", $lo_arr);
					exit;
				
				}
				
			}
		}
		
		//redirect("plans.php?room_id=".$_POST['room_id']);
		
	}
?>

