<?php
@session_start();

require_once("admin/inc/class.dbo.php");
include_once("admin/inc/dbcon.inc.php");
include_once("inc/checklogin.inc.php");

$TITLE = "席次表データ修正ログ - お客様情報 - ウエディングプラス";
include("inc/new.header.inc.php");
include_once("admin/inc/class_data.dbo.php");

$user_id = (int)$_SESSION['userid'];

$dataObj = new DataClass;
//ログ出力に必要なデータを取得。class_data.dbo.jp
$logArray = $dataObj->get_all_log($user_id);

?>

<style>
.datepickerControl table
{
width:200px;
}
.input_text
{
	width:100px;
}
.select
{
	width:122px;
}
.datepicker
{
width:100px;
}
.timepicker
{
width:100px;
}

.box8{
  
}
.box8 td{
  vertical-align:top;
}
.littlebox{
height:20px;
}
</style>

<div id="main_contents" class="displayBox">
<div class="title_bar">
    	<div class="title_bar_txt_L";>席次表データ修正ログ</div>
    	<div class="clear"></div></div>
		<div style="width:100%; text-align:center">

<div id="contents">
        <div class="box_table">
      		<div style="text-align:left; width:875px">&nbsp;</div>
      		<div class="box8">
                <table border="0" width="875px" align="center" cellpadding="1" cellspacing="1" style="word-break: break-all;">
                    <tr align="center">
                        <td  width="15%" bgcolor="#00C6FF" style="color:#FFFFFF">アクセス日時</td>
                        <td  width="14%" bgcolor="#00C6FF" style="color:#FFFFFF">ログイン名</td>
                        <td  width="14%" bgcolor="#00C6FF" style="color:#FFFFFF">アクセス画面名</td>
                        <td  width="13%" bgcolor="#00C6FF" style="color:#FFFFFF">修正対象者</td>
						<td  width="7%" bgcolor="#00C6FF" style="color:#FFFFFF">修正種別</td>
						<td  width="10%" bgcolor="#00C6FF" style="color:#FFFFFF">変更項目名</td>
						<td  width="13%" bgcolor="#00C6FF" style="color:#FFFFFF">変更前情報</td>
						<td  width="13%" bgcolor="#00C6FF" style="color:#FFFFFF">変更後情報</td>


                    </tr>
                </table>
        	</div>

			<?php
            /*ログ出力*/
            for($i=0;$i<count($logArray);++$i){
              $logObject = $logArray[$i];
            ?>
              <div class="box8">
              <table width="875px"  border="0" align="center" cellpadding="1" cellspacing="1" style="word-break: break-all;">
              <tr align="left">
                <td  width="15%"><?=$logObject["access_time"]?></td>
                <td  width="14%"><?=$logObject["login_name"]?></td>
                <td  width="14%"><?=$logObject["screen_name"]?></td>
                <td  width="13%"><?=$logObject["target_user"]?></td>
                <td  width="7%"><?=$logObject["kind"]?></td>
                <td  width="10%"><?=$logObject["target_category"]?></td>
                <td  width="13%"><?=$logObject["previous_status"]?></td>
                <td  width="13%"><?=$logObject["current_status"]?></td>
              </tr>
              </table>
              </div>
              <? } ?>
        </div>
        <div align="center"> 
        <table width="875px" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><div style="height:20px; text-align:right"><a href="#"><font size="3">▲ページ上へ</font></a></div></td>
  </tr>
  <tr>
    <td align="center"><a href="user_info.php?user_id=<?=$user_id?>&stuff_id=<?=$stuff_id?>"><img src="img/btn_back_user.gif" width="43" height="17" alt="戻る" /></a></td>
  </tr>
</table></div>
</div>

</div><br /><br />
</div>
<?php
include("inc/new.footer.inc.php");
?>
