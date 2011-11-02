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
<!--<script type="text/javascript" src="js/jquery.rollover.js"></script>-->
<script type="text/javascript">

exuid_global=0; // javascript グローバル変数
function windowUserOpen(url) {
	var exuid = "<? echo $_SESSION['userid']; ?>";
	var uid = url.split("=");
//	alert("call :"+uid[1]+" session:"+exuid+" global:"+exuid_global);
	if (exuid_global==0) {
		if (exuid == uid[1] || exuid<=0) {
			exuid_global = uid[1];
			userWindow = window.open(url,"_userWindow");
		}
		else {
			alert("お客様画面は違うお客様で開かれています\n現在のお客様画面をログアウトしてから開いてください\nその後F５キーでこのページを更新してください");
		}
	}
	else if (exuid_global == uid[1]) {
		userWindow = window.open(url,"_userWindow");
	} 
	else {
		alert("お客様画面は違うお客様で開かれています\n現在のお客様画面をログアウトしてから開いてください\nその後F５キーでこのページを更新してください");
	}
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
  
