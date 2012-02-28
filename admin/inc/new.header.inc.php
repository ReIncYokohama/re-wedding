<?php
include("inc/pagetitle.inc.php");
$pagetitle1= getPageTitle(basename($_SERVER['SCRIPT_NAME']));
$pagetitle=($pagetitle1=='')?"管理画面 | ログイン":$pagetitle1;
 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?=$pagetitle?></title>
  <link href="css/common.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../js/util.js"></script>
<script type="text/javascript">

exuid_global=0;
function windowUserOpen(url) {
  userWindow = window.open(url,"_userWindow");
}

function confirmDelete(urls)
{
   	var agree = confirm("削除しても宜しいですか？");
	if(agree)
	{
		window.location = urls;
	}
}

function alert_staff()
{
	alert("You are not allowed to Make any change to this user");
	return false;
}

</script>
<style>
.datepicker
{
cursor:pointer;
}
.timepicker
{
cursor:pointer;
}
</style>
</head>

<body>
<div id="wrapper">
  <div id="header"> <img src="img/common/logo.jpg" width="200" height="57" /> </div>
  
