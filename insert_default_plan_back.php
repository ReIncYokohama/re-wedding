<?php
	session_start();
	include_once("admin/inc/dbcon.inc.php");	
	include_once("inc/checklogin.inc.php");	
	
	include_once("admin/inc/class.dbo.php");
	
	$obj = new DBO();
	$user_id = (int)$_SESSION['userid'];
	$plan_row = $obj->GetSingleRow("spssp_plan", "user_id=".$user_id);
	$plan_id = $plan_row['id'];
		
	$num_rowws = $obj->GetNumRows("spssp_plan_details"," plan_id=".$plan_id);
	
	if($num_rowws > 0)
	{
		$obj->DeleteRow("spssp_plan_details"," plan_id=".$plan_id);
	}
	

	
	if(isset($_SESSION['cart']))
	{
		foreach($_SESSION['cart'] as $item)
		{
			if($item != '')
			{
				$itemArr = explode("_",$item);
				//print_r($item);
				$itemids[] = $itemArr[1];
			}
		}
	}

	
	if(isset($itemids))
	{
		
		foreach($_SESSION['cart'] as $key=>$val)
		{

			if($val != '')
			{
				$seat_arr = explode("_",$key);
				$plan_arr['seat_id'] = $seat_arr[0];
		
				
				$guest_arr = explode("_",$val);
				$plan_arr['guest_id'] = $guest_arr[1];
				
				$plan_arr['plan_id'] = $plan_id;
				
				$guest_row = $obj->GetSingleRow("spssp_guest"," id=".$plan_arr['guest_id']);
	

				$lastids = $obj->InsertData("spssp_plan_details", $plan_arr);
			}
			
		}
		if($lastids > 0)
		{
			//echo "here";
			redirect("make_plan.php?msg=4");
		}
		else
		{
			//echo "aaaaaaaa";
			redirect("make_plan.php?err=11");
		}
	}
	else
	{
		//echo "Not Itemids";
		redirect("make_plan.php?err=12");
	}
?>