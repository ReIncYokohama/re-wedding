<?php
session_start();
include_once("admin/inc/dbcon.inc.php");
include_once("inc/checklogin.inc.php");
include("inc/new.header.inc.php");
?>
<script>
$(function(){
		$("ul#menu li").removeClass();
		$("ul#menu li:eq(7)").addClass("active");
	});
	
	
	var title=$("title");  
 $(title).html("お客様情報 - ウエディングプラス"); 
</script>

<div id="main_contents">



<div style="width:100%; text-align:center">
	<p style="text-align:center; font-size:24px; font-weight:bold"> This page is under construction</p>
</div>
</div>
<?php
include("inc/new.footer.inc.php");
?>
