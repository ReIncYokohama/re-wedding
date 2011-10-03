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

<div id="topnavi">
    <?php
include("inc/main_dbcon.inc.php");
$hcode=$HOTELID;
$hotel_name = $obj->GetSingleData(" super_spssp_hotel ", " hotel_name ", " hotel_code=".$hcode);
?>
<h1><?=$hotel_name?>　管理</h1>
<?
include("inc/return_dbcon.inc.php");
?>
 
    <div id="top_btn"> 
        <a href="logout.php"><img src="img/common/btn_logout.jpg" alt="ログアウト" width="102" height="19" /></a>　
        <a href="javascript:;" onclick="MM_openBrWindow('../support/operation_h.html','','scrollbars=yes,width=620,height=600')"><img src="img/common/btn_help.jpg" alt="ヘルプ" width="82" height="19" /></a>
    </div>
</div>

<div id="container">
<div style="clear:both;"></div>   
	<div id="contents"> 
		<h2>セキュリティ</h2>
        
 現在、この画面はご使用できません。                  
    </div>
</div>

<?php	
	include_once("inc/left_nav.inc.php");
?>

        
<?php	
	include_once("inc/new.footer.inc.php");
?>

