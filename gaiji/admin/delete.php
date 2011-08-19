<?php
include_once("inc/dbcon.inc.php");
include_once("inc/checklogin.inc.php");
    
	$fileName = $_GET['pagename'];
	$tableName='gaiji_'.$_GET['t'];
	$basepath='../upload/'.$fileName.'/';
  
	
	if($_GET['t'] !='')
	{
		$imagename = ($_GET['t'] =='option')?"g_image_name":"buso_image_name";
		
		$db_query="SELECT * FROM ".$tableName." WHERE id='".(int)$_GET['id']."';";
		$db_result=mysql_query($db_query);
		$db_row=mysql_fetch_array($db_result);
		$oldfile=$db_row[$imagename];
		
		$db_query="DELETE FROM ".$tableName." WHERE id='".(int)$_GET['id']."'";
	//echo $basepath.$oldfile;
		if(mysql_query($db_query))
		{				
			@unlink($basepath.$oldfile);
		}
			
		redirect($fileName.".php?page=".(int)$_GET['page']);exit;
	}
?>