<?php
	session_start();
	include_once("admin/inc/dbcon.inc.php");	
	include_once("inc/checklogin.inc.php");	
	
	include_once("admin/inc/class.dbo.php");
	
	$obj = new DBO();
	$user_id = (int)$_SESSION['userid'];
	$plan_row = $obj->GetSingleRow("spssp_plan", " user_id=".$user_id);
	$plan_id = $plan_row['id'];
		
	$num_rowws = $obj->GetNumRows("spssp_plan_details"," plan_id=".$plan_id);
	
	
	////checking update for log
	
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
	
	
	if($num_rowws > 0)
	{
    $guest_seat_arr = $obj->GetRowsByQuery("select * from spssp_plan_details where plan_id = ".$plan_id);
    //print_r($guest_seat_arr);
    print_r($_SESSION["cart"]);
		foreach($_SESSION['cart'] as $key=>$val)
		{

			if($val != '')
			{
				$seat_arr = explode("_",$key);
				$seat_id= $seat_arr[0];
		
				
				
				$guest_arr = explode("_",$val);
				$guest_id = $guest_arr[1];
				
				$plan_id = $plan_id;
				
				$guest_row = $obj->GetSingleRow("spssp_plan_details"," guest_id=".$guest_id." and plan_id=".$plan_id);
        
				if($guest_row['seat_id']!=$seat_id)
				{
					$update_array['plan_id']=$plan_id;
					$update_array['date']=date("Y-m-d H:i:s");
					$update_array['guest_id']=$guest_id;
					$update_array['user_id']=$user_id;
					$update_array['previous_status']=$guest_row['seat_id'];
					$update_array['current_status']=$seat_id;
					$update_array['admin_id']=$_SESSION['adminid'];
					$update_array['type']=1;
					
					$lastids = $obj->InsertData("spssp_change_log", $update_array);
          //print_r($update_array);
				}
			}
		}
		exit();
		$obj->DeleteRow("spssp_plan_details"," plan_id=".$plan_id);
	}
	
	if(isset($itemids))
	{
		
		foreach($_SESSION['cart'] as $key=>$val)
		{

			if($val != '')
			{
        
				$seat_arr = explode("_",$key);
        $seat_id = $seat_arr[0];
        
				$plan_arr['seat_id'] = $seat_id;
				$guest_arr = explode("_",$val);
				$plan_arr['guest_id'] = $guest_arr[1];
				
				$plan_arr['plan_id'] = $plan_id;
				
				$lastids = $obj->InsertData("spssp_plan_details", $plan_arr);
        
        
			}
			
		}
    
    $_SESSION["cart"] = null;
    
    if($_GET["ajax"]){
      exit();
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
