<?php
	require_once("../admin/inc/class.dbo.php");
	
	$user_id = (int)$_SESSION['userid'];
	$obj = new DBO();
	$total_table=$_POST['total_table'];
	
	if((int)$total_table>0)
	{
		for($loop=1;$loop<=$total_table;$loop++)
		{
			$arr['name'] = $_POST['name_'.$loop];
			$id=$_POST['id_'.$loop];
			$r=$obj->UpdateData("spssp_table_layout", $arr, " user_id=".$user_id." and id = ".$id);
		}
	}

