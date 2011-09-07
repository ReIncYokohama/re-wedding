<?php
define("DEBUG", "yes");
	require_once("../admin/inc/include_class_files.php");
	include_once("../admin/inc/dbcon.inc.php");

	$query_string="SELECT * FROM spssp_tables_name order by id asc;";
	$data_rows=mysql_query($query_string);

	while($row=mysql_fetch_array($data_rows))
	{
		$sql = "update spssp_default_plan_table set name=".$row['id']." where name='".$row['name']."';";
		mysql_query($sql);
		echo "name : ".$row['name']." → "."id : ".$row['id']."<br />\n";
	}
?>
