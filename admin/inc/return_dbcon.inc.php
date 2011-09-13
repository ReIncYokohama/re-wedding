<?php

include_once(dirname(__FILE__)."/../../conf/conf.php");

$sqlhost=$hotel_sqlhost;
$sqluser=$hotel_sqluser;
$sqlpassword=$hotel_sqlpassword;
$sqldatabase=$hotel_sqldatabase;		

mysql_connected($sqlhost,$sqluser,$sqlpassword,$sqldatabase);

?>
