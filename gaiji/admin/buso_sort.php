<?php
include_once("inc/dbcon.inc.php");
include_once("inc/checklogin.inc.php");

    $query_string = "SELECT * FROM gaiji_buso WHERE id='".$_GET['id']."' LIMIT 0,1";
	$db_result = mysql_query($query_string); 
	if($db_row=mysql_fetch_array($db_result))
	{
			$displayorder=$db_row['displayorder'];
			
			if($_GET['move']=='up')
				$query_string = "SELECT * FROM gaiji_buso WHERE displayorder >= '".$displayorder."' ORDER BY displayorder ASC LIMIT 0,2";
			else
				$query_string = "SELECT * FROM gaiji_buso WHERE displayorder <= '".$displayorder."' ORDER BY displayorder DESC LIMIT 0,2";

			$db_result = mysql_query($query_string); 
			$sortid=array();
			$sortorder=array();
			$i=0;
			while($db_row=mysql_fetch_array($db_result))
			{
				$sortid[$i]		=	$db_row['id'];
				$sortorder[$i]	=	$db_row['displayorder'];
				$i++;
			}


			if($i>1)
			{
				$query_string = "UPDATE gaiji_buso SET displayorder= '".$sortorder[0]."' WHERE id = '".$sortid[1]."'";
				mysql_query($query_string); 

				$query_string = "UPDATE gaiji_buso SET displayorder= '".$sortorder[1]."' WHERE id = '".$sortid[0]."'";
				mysql_query($query_string);
			}
		}
	redirect("buso.php?page=".$_GET['page']);
	die();

?>
