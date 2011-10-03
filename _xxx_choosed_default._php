<?php
@session_start();

include_once("admin/inc/class.dbo.php");
$obj = new DBO();

$get = $obj->protectXSS($_GET);
$plan_id = (int)$get['id'];


$details = $obj->getRowsByQuery("SELECT * FROM `spssp_default_plan_details` WHERE plan_id =".$plan_id);


if(isset($_SESSION['cart']))
{
unset($_SESSION['cart']);
}
if(isset($_SESSION['cats']))
{
unset($_SESSION['cats']);
}
if(isset($_SESSION['subcats']))
{
unset($_SESSION['subcats']);
}

//if(isset($_SESSION['cart']))
//{
	$default_plan_row = $obj->GetSingleRow("`spssp_default_plan`", " id =".$plan_id);

	
	$default_plan_row['default_plan_id']=$default_plan_row['id'];
	$default_plan_row['creation_date'] = date("Y-m-d H:i:s");
	$default_plan_row['user_id']=(int)$_GET['user_id'];
	
	unset($default_plan_row['id']);
	unset($default_plan_row['display_order']);
	
	$num_rows = $obj->GetNumRows("spssp_plan"," user_id=".(int)$_SESSION['userid']);
	if($num_rows >0)
	{
		$obj->DeleteRow("spssp_plan"," user_id=".(int)$_SESSION['userid']);
		$obj->DeleteRow("spssp_guest"," user_id=".(int)$_SESSION['userid']);
		
	}
	
	$lastid = $obj->InsertData("spssp_plan", $default_plan_row);
	//redirect('my_plan.php?id='.$plan_id);
//}

foreach($details as $dt)
{
	
	$guest_row = $obj->GetSingleRow("spssp_default_guest", " id=".$dt['guest_id']);
	$guest_row['user_id']=(int)$_SESSION['userid'];
	//print_r($guest_row);exit;
	unset($guest_row['id']);
	$gid = $obj->InsertData("spssp_guest", $guest_row);
	
	$subcat_row = $obj->GetSingleRow("spssp_guest_sub_category"," id=".$guest_row['sub_category_id']);
	
	//print_r($subcat_row);exit;
	
	$subcat_id = $subcat_row['id'];
	$catid = $subcat_row['category_id'];
	
	$catkey = 'cat_'.$catid;
	$subcat_key = 'subcat_'.$subcat_id;
	
	$_SESSION['cats'][$catkey] = $catid;
	$_SESSION['subcats'][$subcat_key] = $subcat_id;
	
	
/*	$num_subcat = $obj->GetNumRows("spssp_guest_sub_category","user_id=".(int)$_SESSION['userid']." and default_sub_cat_id=".$guest_row['sub_category_id']);
	
	
		
	$num_cat = $obj->GetNumRows("spssp_guest_category","user_id=".(int)$_SESSION['userid']." and default_cat_id=".$subcat_row['category_id']);
		
	if($num_subcat <= 0)
	{
		$guest_subcat_row = $obj->GetSingleRow("spssp_guest_sub_category", " id=".$guest_row['sub_category_id']);
		
		$guest_subcat_row['default_sub_cat_id'] = $guest_subcat_row['id'];
		$guest_subcat_row['user_id'] = (int)$_SESSION['userid'];
		$guest_subcat_row['creation_date'] = date("Y-m-d H:i:s");
		$guest_subcat_row['display_order'] = time();
		unset($guest_subcat_row['id']);
		
		$lastsubcatid = $obj->InsertData("spssp_guest_sub_category", $guest_subcat_row);
		
		if($lastsubcatid > 0)
		{
			$guest_row['sub_category_id']=$lastsubcatid;
		}
		
	}
	
	if($num_cat <= 0)
	{
		$cat_row = $obj->GetSingleRow("spssp_guest_category"," id=".$subcat_row['category_id']);

		$cat_row['user_id'] = (int)$_SESSION['userid'];
		$cat_row['default_cat_id'] = $cat_row['id'];
		$cat_row['creation_date'] = date("Y-m-d H:i:s");
		$cat_row['display_order'] = time();
		
		unset($cat_row['id']);
		
		$lastcatid = $obj->InsertData("spssp_guest_category", $cat_row);
	}*/
	
	
	
	$seatid = $dt['seat_id'];
	$key = $seatid."_input";
	$val = "#".$seatid."_".$gid;
	
	if($gid <= 0)
	{
		redirect('registration.php');
	}
	
	$_SESSION['cart'][$key] = $val;
}
redirect('my_plan.php?id='.$plan_id.'&user_plan_id='.$lastid);

?>
