<?php
	include_once("../inc/dbcon.inc.php");
    if($_GET['cmd']=='delete')
    {
		$query_string="delete FROM spssp_hotelinfo WHERE id ='".(int)$_GET['id']."';";
		mysql_query($query_string);
		//echo $query_string;
		header("Location:../hotel_info.php?page=".$_GET['page']);exit;
	}
	else
	{
	   $query_string="SELECT * FROM spssp_hotelinfo WHERE userid='".$_POST['mail']."';";
		$db_result=mysql_query($query_string);
		if(mysql_num_rows($db_result))
		{
		   echo "1";
		}
		else
		  echo "0";
	}
?>