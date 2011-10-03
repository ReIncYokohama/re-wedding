<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>デックス株式会社 - オンラインバーチャル注文住宅</title>
<link href="../css/tmpl.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="tmp_js/cufon-yui.js"></script>
<script type="text/javascript" src="tmp_js/arial.js"></script>
<script type="text/javascript" src="tmp_js/cuf_run.js"></script>
<script language="javascript" type="text/javascript" src="../js/jquery.js"></script>
<script language="javascript" type="text/javascript" src="../js/jquery.dd.js"></script>
	<link rel="stylesheet" type="text/css" href="../css/jquery.ui.all.css">
   	<script src="../js/jquery-1.4.2.js" type="text/javascript"></script>
	<script src="../js/jquery.ui.position.js" type="text/javascript"></script>
    <script src="../js/jquery.ui.core.js" type="text/javascript"></script>
	<script src="../js/jquery.ui.widget.js" type="text/javascript"></script>
	<script src="../js/jquery.ui.mouse.js" type="text/javascript"></script>
	<script src="../js/jquery.ui.draggable.js" type="text/javascript"></script>
	<script src="../js/jquery.ui.droppable.js" type="text/javascript"></script>
	<script src="../js/jquery.ui.resizable.js" type="text/javascript"></script>    
	<script src="../js/jquery.ui.dialog.js" type="text/javascript"></script>
	
	<link rel="stylesheet" href="../css/demos.css" type="text/css">
    
   
	
    <link rel="stylesheet" type="text/css" href="../css/jquery.treeview.css">
  
    
	<link rel="stylesheet" type="text/css" href="../css/drag_n_drop.css">
    <script src="../js/jquery.cookie.js" type="text/javascript"></script>
  	<script src="../js/jquery.treeview.js" type="text/javascript"></script>
    <script src="../js/drag_n_drop_admin.js" type="text/javascript"></script>

<script language="javascript" type="text/javascript">
function confirmDelete(urls){
   	var agree = confirm("You want tio to Delete It. Are You Sure?");
	if(agree)
	{
		window.location = urls;
	}
   }
   function goBack()
		{
			
			$.post('set_session.php', {'divitems': "reset",'value':""}, function(data) {
													
			});	
			javascript:history.go(-1);
		}
/*$(document).ready(function(arg) {
  $("#relation_type").msDropDown();
  $("#listbox").msDropDown();
  
   });*/
   
						   
</script>
<link href="../css/plan_admin.css" rel="stylesheet" type="text/css" />

<style type="text/css">
<!--
#wrapper {
	text-align: left;
	width: 930px;
	margin: 0 auto;
}
#containerHolder {
	background: #eee;
	padding: 5px;
	width:920px;
	margin:0 auto;
}


#container {
	background: #fff url(../img/content.gif) repeat-y left top;
	border: 1px solid #ddd;
	width: 918px;
}
#sidebar {
	width: 179px;
	float: left;
}

#sidebar .sideNav { width: 179px; }

#sidebar .sideNav li { border-bottom: 1px solid #ddd; width: 179px; list-style:none;}

#sidebar .sideNav li a {
	display: block;
	color: #646464;
	background: #f6f6f6;
	text-decoration: none;
	height: 29px;
	line-height: 29px;
	padding: 0 19px;
	width: 141px;
}

#sidebar .sideNav li a:hover { background: #fdfcf6; }

#sidebar .sideNav li a.active, #sidebar .sideNav li a.active:hover {
	background: #f0f7fa;
	color: #c66653;
}
#menu_link
{
padding: 0 0 19px;

}
#main_cont {
	width: 700px;
	float: right;
	padding: 0 19px 0 0;
}

h3 {
	font-size: 14px;
	line-height: 14px;
	font-weight: bold;
	color: #5494af;
	padding: 0 0 0 10px;
	margin: 20px 0 10px;
}
#footer {
	width:970px;
	margin:0 auto;
}
#footer_content{
	float:left;
	background: #fff;
	border: 2px solid #ddd;
	padding: 3px;
	margin-top:20px;
	width:953px;
	
}
	table tr {
		background-color:#FFFFFF;
	}

	table.grid {
		background-color:#FFFFFF; 
		width:100%;
		text-align:center;
	}
	table.grid tr.head{
		font-size:120%;
		background-color:#FFCCFF;
		color:#990000;
		font-weight:bold;
		letter-spacing:2px;
	}
	table.grid tr.odd{
		background-color:#FFFFFF; 
		color:#000000;
	}
	table.grid tr.even{
		background-color:#FFEEFF; 
		color:#000000;
	}
	table.grid tr.odd:hover td, table.grid tr.even:hover td{
		background-color:#FFDDFF;
	}
	
	
	
-->
</style>
</head>

<body>
<div class="main">
<div class="header">
	<div class="header_resize">
  		<div class="logo">
    		<h1><a href="#">SPSSP Admin <span>Desk</span></a> <small>Your Wedding Partner</small></h1>
  		</div>
  		
<?php if(stristr(curPageURL(), $Admin_site_url.'users.php') != FALSE){echo "class=actice";}?>
		
		<div class="clr"></div>
        <div class="menu_nav">
            <ul id="menu">
            <?php
				if($_SESSION['adminid']||$_SESSION['stuffid'])
				{
			?>
           	<li <?php if(stristr(curPageURL(), $Admin_site_url.'users.php') != FALSE||stristr(curPageURL(), $Admin_site_url.'create_user.php') != FALSE||stristr(curPageURL(), $Admin_site_url.'stuff_new.php') != FALSE||stristr(curPageURL(), $Admin_site_url.'stuffs.php') != FALSE||stristr(curPageURL(), $Admin_site_url.'stuff_edit.php') != FALSE||stristr(curPageURL(), $Admin_site_url.'user_edit.php') != FALSE){echo "class=active";}?>><a href="users.php"> Users</a></li>
        	<li <?php if(stristr(curPageURL(), $Admin_site_url.'respects.php') != FALSE||stristr(curPageURL(), $Admin_site_url.'respect_new.php') != FALSE){echo "class=active";}?>><a href="respects.php"> Respects</a></li>
        	<li <?php if(stristr(curPageURL(), $Admin_site_url.'guest_categories.php') != FALSE||stristr(curPageURL(), $Admin_site_url.'guest_category_new.php') != FALSE||stristr(curPageURL(), $Admin_site_url.'guest_sub_categories.php') != FALSE||stristr(curPageURL(), $Admin_site_url.'guest_sub_category_new.php') != FALSE||stristr(curPageURL(), $Admin_site_url.'guests.php') != FALSE||stristr(curPageURL(), $Admin_site_url.'guest_new.php') != FALSE||stristr(curPageURL(), $Admin_site_url.'guest_type.php') != FALSE){echo "class=active";}?>><a href="guest_categories.php"> Guest Categories</a></li>
        	
            <li <?php if(stristr(curPageURL(), $Admin_site_url.'rooms.php') != FALSE||stristr(curPageURL(), $Admin_site_url.'room_new.php') != FALSE||stristr(curPageURL(), $Admin_site_url.'room_edit.php') != FALSE||stristr(curPageURL(), $Admin_site_url.'plans.php') != FALSE||stristr(curPageURL(), $Admin_site_url.'plan_new.php') != FALSE||stristr(curPageURL(), $Admin_site_url.'view_default_plan.php') != FALSE||stristr(curPageURL(), $Admin_site_url.'plan_edit.php') != FALSE||stristr(curPageURL(), $Admin_site_url.'tables.php') != FALSE||stristr(curPageURL(), $Admin_site_url.'default_plan.php') != FALSE){echo "class=active";}?>><a href="rooms.php"> Rooms</a></li>
            
            <li <?php if(stristr(curPageURL(), $Admin_site_url.'gift_category.php') != FALSE||stristr(curPageURL(), $Admin_site_url.'user_log.php') != FALSE){echo "class=active";}?>><a href="gift_category.php">Gift</a></li>
        	
        	<li <?php if(stristr(curPageURL(), $Admin_site_url.'messages.php') != FALSE||stristr(curPageURL(), $Admin_site_url.'msg_view.php') != FALSE||stristr(curPageURL(), $Admin_site_url.'edit_messages.php') != FALSE||stristr(curPageURL(), $Admin_site_url.'new_messages.php') != FALSE){echo "class=active";}?>><a href="messages.php">Messages</a></li>
        	<li <?php if(stristr(curPageURL(), $Admin_site_url.'faq.php') != FALSE||stristr(curPageURL(), $Admin_site_url.'faq_entry.php') != FALSE||stristr(curPageURL(), $Admin_site_url.'faq_edit.php') != FALSE){echo "class=active";}?>><a href="faq.php">Faq</a></li>
        	
			<li <?php if(stristr(curPageURL(), $Admin_site_url.'tables_new.php') != FALSE){echo "class=active";}?>><a href="tables_new.php" title="Tables">Tables</a></li>
			
			<li class="logout"><a href="index.php?logout=true" title="LOGOUT">ログアウト</a></li>
			<?php
				}
			?>
            </ul>
        </div>
  		<div class="clr"></div>
	</div>
</div></div>
<div style="width:970px;min-height:490px;margin:0 auto;">
<div style="float:left;border:2px solid #ccc;padding:20px;">

