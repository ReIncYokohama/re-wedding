<?php
	include_once("inc/dbcon.inc.php");

	include_once("inc/checklogin.inc.php");

	include_once("inc/class.dbo.php");


	if(trim($_POST['name']) !='' && trim($_POST['max_rows'])!='' && trim($_POST['max_columns'])!='')
	{
		$obj = new DBO();

		$post = $obj->protectXSS($_POST);

		$name_exists =  $obj->GetSingleData("spssp_tables_name", "name"," name='".$post['name']."'");

		$room_id = $post['room_id'];
		unset($post['room_id']);

		$post['max_rows'] = (int)$post['max_rows'];
		$post['max_columns'] = (int)$post['max_columns'];
		$post['max_seats'] = (int)$post['max_seats'];
		$post['display_order']= time();
		$post['creation_date'] = date("Y-m-d H:i:s");

		if( ($post['max_rows'] > 0 &&  $post['max_rows'] <= 500) && ($post['max_columns'] > 0 && $post['max_columns'] <=500) && ( $post['max_seats'] > 0 && $post['max_seats'] <= 100) && $name_exists=="")
		{
			$lastid = $obj->InsertData('spssp_room',$post);
			if($lastid > 0)
			{
				$room_data = $obj->GetSingleRow('spssp_room','id='.$lastid);
				unset($post);
				$post['name']=$room_data['name'];
				$post['room_id']=$lastid;
				$post['row_number']=$room_data['max_rows'];
				$post['column_number']=$room_data['max_columns'];
				$post['seat_number']=$room_data['max_seats'];
				$post['creation_date']=date("Y-m-d H:i:s");
				$post['display_order'] = time();


				$planinsertid = $obj->InsertData('spssp_default_plan',$post);

				for($i=1; $i <= (int)($room_data['max_rows']) *($room_data['max_columns']); $i++)
				{
					$table_arr['name'] =  $obj->GetSingleData("spssp_tables_name", "name"," 1=1 ORDER BY RAND() LIMIT 1");
					if($table_arr['name'] == '')
					{

						$table_arr['name'] = ''.$i;
					}
					$table_arr['name'] = '';
					$table_arr['room_id'] = $lastid;
					$ltid = $obj->InsertData('spssp_default_plan_table',$table_arr);
					if($ltid > 0)
					{
						for($j=1; $j <= (int)$room_data['max_seats']; $j++)
						{
							$sit_arr['table_id'] = $ltid;
							$stid = $obj->InsertData('spssp_default_plan_seat',$sit_arr);
							if($stid >0)
							{
								redirect("rooms.php?page=".(int)$_GET['page']."&room_id=".$lastid);
							}
						}
					}
				}


				if($planinsertid)
				{
						$plan_row = $obj->GetSingleRow("spssp_default_plan", " id=".(int)$planinsertid);

						$room_rows = $plan_row['row_number'];

						$room_tables = $plan_row['column_number'];
						$row_width = (int)((115)*$room_tables);
						$room_seats = $plan_row['seat_number'];

						$num_tables = $room_rows * $room_tables;

						$table_rows = $obj->getRowsByQuery("select * from spssp_default_plan_table where room_id = ".$plan_row['room_id']." order by id asc limit 0, $num_tables");

						$num_layouts = $obj->GetNumRows("spssp_table_layout","default_plan_id= ".(int)$planinsertid);

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
									$lo_arr['default_plan_id'] = $planinsertid;

									$lid = $obj->InsertData("spssp_table_layout", $lo_arr);
								}
							}
						}
				}
			}
			redirect("rooms.php?room_id=".$lastid."&msg=0");
		}
		else
		{
			redirect("rooms.php?room_id=".$lastid."&msg=1");
		}
	}
?>
