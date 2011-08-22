<?php
	include_once("inc/dbcon.inc.php");
	
	include_once("inc/checklogin.inc.php");

	require_once("inc/class.dbo.php");
	$qry = "select * from spssp_default_guest";
	$result = mysql_query($qry);
	$num_rows = mysql_num_rows($result);
	echo $num_rows;
?>
