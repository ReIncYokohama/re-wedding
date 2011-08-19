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
<link rel="stylesheet" href="css/base/jquery.ui.all.css">
<script src="js/jquery-1.4.2.js"></script>
<script src="js/external/jquery.bgiframe-2.1.1.js"></script>
<script src="js/ui/jquery.ui.core.js"></script>
<script src="js/ui/jquery.ui.widget.js"></script>
<script src="js/ui/jquery.ui.mouse.js"></script>
<script src="js/ui/jquery.ui.button.js"></script>
<script src="js/ui/jquery.ui.draggable.js"></script>
<script src="js/ui/jquery.ui.position.js"></script>
<script src="js/ui/jquery.ui.resizable.js"></script>
<script src="js/ui/jquery.ui.dialog.js"></script>
<script src="js/ui/jquery.effects.core.js"></script>
<script src="js/ui/jquery.effects.blind.js"></script>
<script src="js/ui/jquery.effects.fade.js"></script>

<script src="js/dashboard.js"></script>
<style type="text/css">
.header_resize
{
width:95%;
}
.content_resize
{
width:95%;
}
.menu_nav ul li a 
{
width:95px;
}
.footer_resize
{
width:95%;
text-align:center;
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
            <ul id="menu">
              <li class="active"><a href="dashboard.php">トップ</a></li>
              	
			  <li><a href="#">My Plan</a></li>
              <li><a href="set_table_layout.php">Table Lay Out</a></li>
              <li><a href="my_guests.php">My Guests</a></li>
              <li><a href="menu_group.php">Guest Menus</a></li>
              <li><a href="gifts.php">Guest Gifts</a></li>
              <li><a href="user_messages.php">Messages</a></li>
              <li><a href="dummy.php">Preview</a></li>
              <li><a href="dummy.php">Order</a></li>
              <li><a href="dummy.php">Download</a></li>
              <li><a href="dummy.php">About Me</a></li>
              <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
  		<div class="clr"></div>
	</div>
</div>
<div class="content">
	<div class="content_resize"> 
