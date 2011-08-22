<?php
mysql_close();

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


$link = mysql_connect($sqlhost, $sqluser,$sqlpassword)
    	or die("COULD NOT CONNECT : " . mysql_error());
		
mysql_select_db($sqldatabase) or die("COULD NOT SELECT DATABASE");
mysql_query("SET CHARACTER SET 'utf8'"); 
mysql_query("SET NAMES 'utf8'");
?>
