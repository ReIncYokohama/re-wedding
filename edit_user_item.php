<?php
@session_start();
include_once('admin/inc/dbcon.inc.php');
include_once('admin/inc/class.dbo.php');
$obj = new DBO();

$itemname = $_POST['name'];
$catul = $_POST['catul'];
$catulArray = explode("_",$catul);

/*$freetext = $_POST['freetext'];
$designation = $_POST['designation'];*/
$edititem_id = $_POST['edit_item_id'];

$userid = $_SESSION['userid'];
$divid = $_POST['divid'];

$catid = (int)$catulArray[1];
$insertArray['name'] = $itemname;
$insertArray['sub_category_id'] = $catid;
$insertArray['display_order'] = time();
$insertArray['user_id'] = (int)$_SESSION['userid'];
$insertArray['edit_item_id'] = (int)$edititem_id;
$insertArray['creation_date'] = date("Y-m-d H:i:s");
$insertArray['respect_id']=(int)$_POST['respect_id'];
$insertArray['sex']=$_POST['sex_edit'];
$insertArray['description']=$_POST['description_edit'];


/*$guestArray['name'] = $itemname; 
$guestArray['categoryid'] = $catid; 
$guestArray['freetext'] = $freetext; 
$guestArray['designation'] = $designation;*/

$edited_row = $obj->GetSingleRow("spssp_guest", " id =".$edititem_id);

if(isset($edited_row['user_id']) && $edited_row['user_id'] > 0 && $edited_row['self'] == 1)
{
	/*$editArray['name'] = $itemname;
	if(isset($edited_row['self']) && $edited_row['self']==1)
	{*/
	unset($insertArray['edit_item_id']);
	//}
	$obj->UpdateData("spssp_guest", $insertArray, " id=".$edititem_id);
	
	
/*	$garray['name'] = $itemname; 
	$obj->UpdateData("wedpartner_guest", $garray, " id=".$edititem_id);*/
	echo $edititem_id; //."user here id = $user_id";	
}
else
{
	/*if(isset($edited_row['self']) && $edited_row['self']==1)
	{
		unset($insertArray['edit_item_id']);
	}*/
	
	$lastid = $obj->InsertData("spssp_guest", $insertArray);
	
	$obj->DeleteRow(" spssp_guest", " id=".$edititem_id);
	//$guestid = $obj->InsertData("wedpartner_guest",$guestArray);
	$key = $divid."_input";
	$val = "#".$divid."_".$lastid;
	$_SESSION['cart'][$key] = $val;
	
	echo $lastid;
}

//echo "Item Name= $itemname , cat = $catul, Freetext = $freetext, Designation = $designation, Edit Item =$edititem_id, user = $userid";
?>
