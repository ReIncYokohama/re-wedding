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
		$sqluser='wplus_main';
		$sqlpassword="wp_123456";
		$sqldatabase="wplus_main";

		//$sqlhost='localhost';
		//$sqluser='dev2_main';
		//$sqlpassword="re123456";
		//$sqldatabase="dev2_main";

    //$sqluser='dev2_hotel1';
		//$sqlpassword="dev2_123456";
		//$sqldatabase="dev2_hotel1";
		
	}


$link = mysql_connect($sqlhost, $sqluser,$sqlpassword)
    	or die("COULD NOT CONNECT : " . mysql_error());
		
mysql_select_db($sqldatabase) or die("COULD NOT SELECT DATABASE");
mysql_query("SET CHARACTER SET 'utf8'"); 
mysql_query("SET NAMES 'utf8'");
?>
