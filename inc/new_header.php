<?php
@session_start();
//echo $_SESSION['adminid'];exit;
	if(trim($_SESSION['userid'])=='')
		{
			
			@session_destroy();
			redirect("index.php?action=required");
		}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SPSSP-Your wedding partner</title>

<link href="css/tmpl.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="tmp_js/cufon-yui.js"></script>
<script type="text/javascript" src="tmp_js/arial.js"></script>
<script type="text/javascript" src="tmp_js/cuf_run.js"></script>
<script language="javascript" type="text/javascript" src="js/jquery.js"></script>
<script language="javascript" type="text/javascript">
function confirmDelete(urls){
   	var agree = confirm("You want tio to Delete It. Are You Sure?");
	if(agree)
	{
		window.location = urls;
	}
}
</script>
<style>
.menu_nav h2 span
{
font-size:14px;
}
</style>
</head>
<body>
<div class="main">
<div class="header">
	<div class="header_resize">
  		<div class="logo">
    		<h1><a href="#">SPSSP <span>Desk</span></a> <small>Your Wedding Partner</small></h1>
  		</div>
  		<div class="clr"></div>
        <div class="menu_nav" id="top_text">
            <h2>Choose Default Plan or Not - <span> To view suggested plan for you just point over the plan name</span></h2>
        </div>
  		<div class="clr"></div>
	</div>
</div>
<div class="content">
	<div class="content_resize"> 
