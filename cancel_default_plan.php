<?php
include_once("admin/inc/dbcon.inc.php");	
include_once("inc/checklogin.inc.php");	
session_start();
$_SESSION["cart"] = null;
$php_file="make_plan_full.php";
redirect($php_file."?delete=true");
