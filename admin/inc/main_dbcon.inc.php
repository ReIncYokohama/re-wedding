<?php
include_once(dirname(__FILE__)."/../../conf/conf.php");

if($_SERVER['HTTP_HOST']=='localhost')
	{
		$sqlhost='localhost';
		$sqluser='root';
		$sqlpassword="";
		$sqldatabase="spssp";
	}
	else
	{
		$sqlhost=$main_sqlhost;
		$sqluser=$main_sqluser;
		$sqlpassword=$main_sqlpassword;
		$sqldatabase=$main_sqldatabase;		
	}
mysql_close();
mysql_connected($sqlhost,$sqluser,$sqlpassword,$sqldatabase);

?>
