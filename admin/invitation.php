<?php
	require_once("inc/class.dbo.php");
	include_once("inc/checklogin.inc.php");
	include_once("inc/new.header.inc.php");
	$obj = new DBO();

	$table='spssp_admin';
	$where = " 1=1";

	$query_string="SELECT * FROM spssp_admin where id!=".$_SESSION['adminid']." ORDER BY id ASC LIMIT ".((int)($current_page)*$data_per_page).",".((int)$data_per_page).";";
	$data_rows = $obj->getRowsByQuery($query_string);
?>

<?php include_once("inc/topnavi.php");?>

<div id="container">
<div style="clear:both;"></div>
	<div id="contents">
		<h4>招待状</h4>

        <br /><br /><br /><br />
        <br /><br /><br /><br />
        <br /><br /><br /><br />
<div style="font-size:23px; font-weight:bold; text-align:center;"> 現在、この画面はご使用できません。</div>
    </div>
</div>

<?php
	include_once("inc/left_nav.inc.php");
?>


<?php
	include_once("inc/new.footer.inc.php");
?>

