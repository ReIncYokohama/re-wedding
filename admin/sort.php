<?php
	require_once("inc/class.dbo.php");
	include_once("inc/checklogin.inc.php");
	
    
	$tableName = 'spssp_'.$_GET['table'];
    $orderItem = ($_GET['sort'] =='')?'ASC': $_GET['sort'];
	$orderItem2 = ($_GET['sort'] =='ASC')?'DESC': 'ASC';

	$query_string = "SELECT * FROM  $tableName WHERE id='".(int)$_GET['id']."' LIMIT 0,1";
	$db_result = mysql_query($query_string); 
	if($db_row=mysql_fetch_array($db_result))
	{
        
			$displayorder=$db_row['display_order'];
			

			if($_GET['move']=='up')
				$query_string = "SELECT * FROM  $tableName WHERE display_order <= '".$displayorder."' ORDER BY display_order $orderItem2 LIMIT 0,2";
			else
				$query_string = "SELECT * FROM  $tableName WHERE display_order >= '".$displayorder."' ORDER BY display_order $orderItem LIMIT 0,2";

//echo $query_string;exit;

			$db_result = mysql_query($query_string); 
			$sortid=array();
			$sortorder=array();
			$i=0;
			while($db_row=mysql_fetch_array($db_result))
			{
				$sortid[$i]		=	$db_row['id'];
				$sortorder[$i]	=	$db_row['display_order'];
				$i++;
			}

			if($i>1)
			{

				$query_string = "UPDATE  $tableName SET display_order= '".$sortorder[0]."' WHERE id = '".$sortid[1]."'";
				mysql_query($query_string); 
				$query_string = "UPDATE  $tableName SET display_order= '".$sortorder[1]."' WHERE id = '".$sortid[0]."'";
				mysql_query($query_string);
			}
		}
		
		
		   redirect($_GET['pagename'].".php?page=".$_GET['page']);

	die();

?>