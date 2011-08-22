<?php
session_start();
	include_once("inc/dbcon.inc.php");	
	include_once("inc/checklogin.inc.php");	
	
	include_once("inc/class.dbo.php");
	
	$obj = new DBO();
	$plan_id = (int)$_GET['plan_id'];
	
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
	
	if(isset($_SESSION['cats']))
	{
		foreach($_SESSION['cats'] as $key=>$val)
		{

			if($val != '')
			{
				$cat_row = $obj->GetSingleRow("spssp_guest_category", " id=".$val);
				$cat_row['default_cat_id'] = $cat_row['id'];
				$cat_row['user_id'] = (int)$_SESSION['userid'];
				
				$cat_row['display_order'] = time();
				$cat_row['creation_date'] = date("Y-m-d H:i:s");
				unset($cat_row['id']);
	

				$lastid = $obj->InsertData("spssp_guest_category", $cat_row);
			}
			
		}
	}
	
	if(isset($_SESSION['subcats']))
	{
		foreach($_SESSION['subcats'] as $key=>$val)
		{

			if($val != '')
			{
				$subcat_row = $obj->GetSingleRow("spssp_guest_sub_category", " id=".$val);
				$subcat_row['default_sub_cat_id'] = $subcat_row['id'];
				$subcat_row['user_id'] = (int)$_SESSION['userid'];
				
				$catrow = $obj->GetSingleRow("spssp_guest_category", " default_cat_id=".$subcat_row['category_id']." and user_id=".(int)$_SESSION['userid']);
				if($catrow['id'] > 0)
				{
					$subcat_row['category_id']= $catrow['id'];
				}
				
				$subcat_row['display_order'] = time();
				$subcat_row['creation_date'] = date("Y-m-d H:i:s");
				
				//$guest_row = $obj->GetSingleRow("spssp_guest", " sub_category_id=".$subcat_row['id']." and user_id=".(int)$_SESSION['userid']);	
				//$subcatid =	$subcat_row['id'];		
				unset($subcat_row['id']);
	

				$lastid = $obj->InsertData("spssp_guest_sub_category", $subcat_row);
				/*$arr['sub_category_id'] = $lastid;
				if($guest_row['id'] > 0)
				{
					$obj->UpdateData("spssp_guest", $arr, " user_id=".(int)$_SESSION['user_id']." and sub_category_id=".$subcatid);
				}*/
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
			redirect("users.php");
		}
		else
		{
			//echo "aaaaaaaa";
			redirect("make_.php?user_id=".(int)$_GET['user_id']."&plan_id=".(int)$_GET['plan_id']);
		}
	}
	else
	{
		//echo "Not Itemids";
		redirect("make_.php?user_id=".(int)$_GET['user_id']."&plan_id=".(int)$_GET['plan_id']);
	}
?>
