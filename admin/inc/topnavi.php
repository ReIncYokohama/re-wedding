<div id="topnavi">
<?php
$hotel = Model_Hotel::find_one_by_hotel_code($hcode);
$hotel_name = $hotel["hotel_name"];
//fuel phpでは、自動的にmysqlを切り替えるが以前のコードはアクセスできないため
include("return_dbcon.inc.php");
?>
<h1><?=$hotel_name?></h1>

    <div id="top_btn">
        <a href="logout.php" id="logout_button"><img src="img/common/btn_logout.jpg" alt="ログアウト" width="102" height="19" border="0" /></a>　
        <a href="javascript:;" onclick="MM_openBrWindow('../support/operation_h.html','','scrollbars=yes,width=620,height=600')"><img src="img/common/btn_help.jpg" alt="ヘルプ" width="82" height="19" border="0" /></a>
    </div>
</div>
