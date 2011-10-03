<?php
@session_start();
include_once('admin/inc/dbcon.inc.php');
include_once('admin/inc/class.dbo.php');
$obj = new DBO();

$itemname = $_POST['name'];
$catul = $_POST['catul'];
$catulArray = explode("_",$catul);


$catid = (int)$catulArray[1];
$insertArray['name'] = $itemname;
$insertArray['sub_category_id'] = $catid;
$insertArray['display_order'] = time();
$insertArray['user_id'] = (int)$_SESSION['userid'];
$insertArray['creation_date'] = date("Y-m-d H:i:s");
$insertArray['self']=1;
$insertArray['respect_id']=(int)$_POST['respect_id'];
$insertArray['sex']=$_POST['sex'];
$insertArray['description']=$_POST['description'];

/*$guestArray['name'] = $itemname; 
$guestArray['categoryid'] = $catid; 
$guestArray['freetext'] = $freetext; 
$guestArray['designation'] = $designation; */

$lastid = $obj->InsertData("spssp_guest", $insertArray);
//$guestid = $obj->InsertData("wedpartner_guest",$guestArray);

echo $lastid;
?>
