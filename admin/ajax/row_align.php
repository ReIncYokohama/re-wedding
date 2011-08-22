<?php
@session_start();
include_once('../inc/dbcon.inc.php');
include_once('../inc/class.dbo.php');
$obj = new DBO();

$tablename= "spssp_table_layout";

$arr['align'] = $_POST['align'];

if($_POST['default_plan_id']!=0)
$obj->UpdateData($tablename, $arr, " default_plan_id=".(int)$_POST['default_plan_id']." and row_order=".(int)$_POST['row_order']);
else
$obj->UpdateData($tablename, $arr, " row_order=".(int)$_POST['row_order']." and user_id=".(int)$_POST['user_id']);

if($_POST['default_plan_id']!=0)
	$sql_select="select * from spssp_table_layout where  default_plan_id=".(int)$_POST['default_plan_id']." and row_order=".(int)$_POST['row_order'];
else
	$sql_select="select * from spssp_table_layout where  row_order=".(int)$_POST['row_order']." and user_id=".(int)$_POST['user_id'];	

if($arr['align']=="N")
{
	
	$result_query=mysql_query($sql_select);
	while($row=mysql_fetch_array($result_query))
		{
			
			if($row['display']==0)
			{
				$arr_up['visibility']=0;
				$arr_up['display']=1;
				$obj->UpdateData($tablename, $arr_up, " id=".(int)$row['id']);
			}
		}
}
else
{

	$result_query=mysql_query($sql_select);
	while($row=mysql_fetch_array($result_query))
		{
			if($row['visibility']==0 && $row['display']==1)
			{
				$arr_up['visibility']==0;
				$arr_up['display']=0;
				$obj->UpdateData($tablename, $arr_up, " id=".(int)$row['id']);
			}
		}

}
?>
