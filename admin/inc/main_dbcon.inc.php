<?php
include(dirname(__FILE__)."/../../conf/conf.php");

$sqlhost=$main_sqlhost;
$sqluser=$main_sqluser;
$sqlpassword=$main_sqlpassword;
$sqldatabase=$main_sqldatabase;

mysql_connected($sqlhost,$sqluser,$sqlpassword,$sqldatabase);

?>
