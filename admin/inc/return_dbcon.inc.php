<?php
mysql_close();	
if($_SERVER['HTTP_HOST']=='localhost')
	{
		$sqlhost='localhost';
		$sqluser='root';
		$sqlpassword="";
		$sqldatabase="spssp";
	}
	else
	{
		$sqlhost='localhost';
		$sqluser='wplus_hotel1_2';
		$sqlpassword="wph1_123456";
		$sqldatabase="wplus_hotel1_2";
		
		
	}


$link = mysql_connect($sqlhost, $sqluser,$sqlpassword)
    	or die("COULD NOT CONNECT : " . mysql_error());
		
mysql_select_db($sqldatabase) or die("COULD NOT SELECT DATABASE");
mysql_query("SET CHARACTER SET 'utf8'"); 
mysql_query("SET NAMES 'utf8'");
?>
