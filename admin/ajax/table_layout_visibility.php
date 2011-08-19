<?php
@session_start();
include_once('../inc/dbcon.inc.php');
include_once('../inc/class.dbo.php');
$obj = new DBO();

$align=$_POST[ralign];
$tablename= "spssp_table_layout";
if(isset($_POST['display']) && $_POST['display'] != '')
{
	$arr['display'] = (int)$_POST['display'];
	///MODIFICATION BY FAHIM 18-08-11
	$arr['visibility'] = (int)$_POST['visibility'];
	if($align == "N" && $_POST['display']==0)
	{
		$arr['visibility'] =0;
		$arr['display']=1;
	}
	else if($_POST['display']==0)
	{
		$arr['visibility'] =0;
		$arr['display']=0;
	}
	else if($_POST['visibility']==0)
	{
		$arr['visibility'] =0;
		$arr['display']=1;
	}
	else
	{
		$arr['visibility'] =1;
		$arr['display']=1;
	}
	///END OF MODIFICATION BY FAHIM 18-08-11

}

$obj->UpdateData($tablename, $arr, " id=".(int)$_POST['id']);
?>