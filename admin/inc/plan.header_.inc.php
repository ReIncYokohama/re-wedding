<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SPSSP</title>
<link rel="stylesheet" type="text/css" href="../css/dd.css" />
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
  
    <link rel="stylesheet" type="text/css" href="../css/screen.css">
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
	*{
		text-align:inherit;
		font-size:14px;
		color:#000000;
	}
	body {
		text-align:center;
		margin:0px auto;
		background-color:#EEEEFF;
	}
	a {
		color:#3366FF;
	}
	a:hover {
		color:#0000FF;
		text-decoration:none;
	}
	hr {
		height:1px;
		overflow:hidden;
		clear:both;
		border:none;
		border-top:1px solid #99CCFF;
	}
	h2 {
		font-size:120%;
	}
	table tr {
		background-color:#FFFFFF;
	}
	#page {
		text-align:left;
		width:950px;
		border:1px solid #99CCFF;
		border-top:none;
		border-bottom:none;
		margin:0px auto;
		background-color:#FFFFFF;
	}
	#header {
		font-size:175%;
		background-color:#99CCFF;
		padding:5px;
	}
	#nav {
		border-bottom:1px solid #99CCFF;
		padding:2px 5px;
	}
	
	#content {
		padding:2px 5px;
	}
	#footer {
		font-size:90%;
		background-color:#99CCFF;
		text-align:center;
		padding:5px;
	}
	div.pagination {
		text-align:right;
		padding-bottom:5px;
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
	
	.clear {
		height:1px;
		overflow:hidden;
		clear:both;
	}
	.clear5 {
		height:5px;
		overflow:hidden;
		clear:both;
	}
	.clear10 {
		height:10px;
		overflow:hidden;
		clear:both;
	}
	
-->
</style>
</head>

<body>
    <div id="page">
        <div id="header">
       		SPSSP
        </div>