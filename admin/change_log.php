<?php



require_once("inc/class.dbo.php");
include_once("inc/dbcon.inc.php");
include_once("inc/checklogin.inc.php");
include("inc/new.header.inc.php");
include_once("inc/class_data.dbo.php");

$obj = new dbo;

$user_id = (int)$_GET['user_id'];

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
<script src="../js/noConflict.js" type="text/javascript"></script>
<script type="text/javascript" src="calendar/calendar.js"></script>


<script type="text/javascript" language="javascript" src="../datepicker/prototype-1.js"></script>

<script type="text/javascript" language="javascript" src="../datepicker/prototype-date-extensions.js"></script>
<script type="text/javascript" language="javascript" src="../datepicker/behaviour.js"></script>

<script type="text/javascript" language="javascript" src="../datepicker/datepicker.js"></script>
<script type="text/javascript">

Control.DatePicker.Locale['ahad'] = { dateTimeFormat: 'yyyy-MM-dd HH:mm', dateFormat: 'yyyy-MM-dd', firstWeekDay: 1, weekend: [0,6], language: 'ahad'};

Control.DatePicker.Language['ahad'] = { months: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'], days:[  '日', '月','火', '水', '木', '金','土'], strings: { 'Now': '今度', 'Today': '今日', 'Time': '時間', 'Exact minutes': '正確な分', 'Select Date and Time': '閉じる', 'Open calendar': 'オープンカレンダー' } };



</script>

<link rel="stylesheet" href="../datepicker/datepicker.css">
<script type="text/javascript" language="javascript" src="../datepicker/behaviors.js"></script>

<div id="topnavi">
    <?php
include("inc/main_dbcon.inc.php");
$hcode=$HOTELID;
$hotel_name = $obj->GetSingleData(" super_spssp_hotel ", " hotel_name ", " hotel_code=".$hcode);
?>
<h1><?=$hotel_name?></h1>
<?
include("inc/return_dbcon.inc.php");
?>

    <div id="top_btn">
        <a href="logout.php"><img src="img/common/btn_logout.jpg" alt="ログアウト" width="102" height="19" /></a>　
        <a href="javascript:;" onclick="MM_openBrWindow('../support/operation_h.html','','scrollbars=yes,width=620,height=600')"><img src="img/common/btn_help.jpg" alt="ヘルプ" width="82" height="19" /></a>
    </div>
</div>

<div id="container">
<a href="#top"> </a>
<div style="clear:both;"></div>

	<div id="contents">
        <?php
            $data_user = $obj->GetSingleRow("spssp_user", "id=".$user_id);
			require_once("inc/include_class_files.php");
			$objInfo = new InformationClass();
        ?>
  <?php  echo $objInfo->get_user_name_image_or_src($data_user['id'] ,$hotel_id=1, $name="man_lastname.png",$extra="thumb1",$height=20);?>
・
  <?php  echo $objInfo->get_user_name_image_or_src($data_user['id'] ,$hotel_id=1, $name="woman_lastname.png",$extra="thumb1",$width=20);?>
  様
	 <h4>
<!-- UCHIDA EDIT 11/08/04
        <a href="users.php">お客様一覧 </a> &gt;&gt; <a href="user_info.php?user_id=<?=$user_id?>">お客様挙式情報 </a> &gt;&gt; 席次データ修正ログ
 -->
		<?php
		if($stuff_id==0) {?>
            <a href="manage.php">ＴＯＰ</a> &raquo; <a href="user_info_allentry.php?user_id=<?=$user_id?>&stuff_id=<?=$stuff_id?>">お客様挙式情報 </a> &raquo; 席次表データ修正ログ
		<?php }
		else {?>
            <a href="users.php">管理者用お客様一覧</a> &raquo; <a href="user_info_allentry.php?user_id=<?=$user_id?>&stuff_id=<?=$stuff_id?>">お客様挙式情報 </a> &raquo; 席次表データ修正ログ
		<?php }
		?>

     </h4>
	 <h2>席次表データ修正ログ</h2>

		<!--<form action="change_log.php" method="get">
		<input type="hidden" name="user_id" value="< ?=$_GET[user_id]?>"  />
           <table  cellpadding="5" cellspacing="5" style="width:600px;" >
		   <tr>
				<td  style="width:200px;"  valign="top"><label for="Guest Name">招待者名:</label>

					<select name="guest_id" id="guest_id">
					<option value="">選択</option>
					< ?php
					$query_string="SELECT last_name,first_name,id FROM spssp_guest where user_id=".$user_id.";";
					$guest_rows = $obj->getRowsByQuery($query_string);
					foreach($guest_rows as $guest)
						{
					?>
							<option value="< ?=$guest[id]?>" < ?php if($_GET['guest_id']==$guest[id]) { ?> selected="selected" < ?php } ?>>< ?=$guest[last_name]." ".$guest[first_name]?></option>
					< ?php
						}
					?>
					</select>
			　	</td>

				<td  style="width:200px;"  valign="top"><label for="Date">日付</label>
						<input type="text" name="date" value="< ?=$_GET['date']?>" id="date"  style="background: url('img/common/icon_cal.gif') no-repeat scroll right center rgb(255, 255, 255); padding-right: 20px;" class="datepicker"/>

				　	</td>
				</td>

				<td width="100" valign="top">
				<input type="submit" name="submit" value="検索" />

				　</td>


			</tr>

		</table>
		</form>
		-->

        <div class="box_table">

			<!--<div style="text-align:right;">< ?=$pageination?></div>-->

      		<div class="box8">
                <table border="0" width="100%" align="center" cellpadding="1" cellspacing="1">
                    <tr align="center">

                        <!--<td  width="10%">Plan name</td>-->
                        <td  width="14%" bgcolor="#2252A3" style="color:#FFFFFF">アクセス日時</td>
                        <td  width="15%" bgcolor="#2252A3" style="color:#FFFFFF">ログイン名</td>
                        <td  width="13%" bgcolor="#2252A3" style="color:#FFFFFF">アクセス画面名</td>
                        <td  width="13%" bgcolor="#2252A3" style="color:#FFFFFF">修正対象者</td>
						<td  width="7%" bgcolor="#2252A3" style="color:#FFFFFF">修正種別</td>
						<td  width="12%" bgcolor="#2252A3" style="color:#FFFFFF">変更項目名</td>
						<td  width="13%" bgcolor="#2252A3" style="color:#FFFFFF">変更前情報</td>
						<td  width="13%" bgcolor="#2252A3" style="color:#FFFFFF">変更後情報</td>


                    </tr>
                </table>
        	</div>


<?php
            /*ログ出力*/
            for($i=0;$i<count($logArray);++$i){
              $logObject = $logArray[$i];
              ?>
              <div class="box8">
              <table width="875px"  border="0" align="center" cellpadding="1" cellspacing="1">
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
              <?
            }
?>
        </div>
        
        <div align="center"> 
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><div style="height:20px; text-align:right"><a href="#"><font size="3">▲ページ上へ</font></a></div></td>
  </tr>
  <tr>
    <td align="center"><a href="user_info_allentry.php?user_id=<?=$user_id?>&stuff_id=<?=$stuff_id?>"><img src="img/common/btn_back.gif" width="43" height="17" alt="戻る" /></a></td>
  </tr>
</table></div>
</div>

</div>

<?php
	include_once("inc/left_nav.inc.php");
?>


<?php
	include_once("inc/new.footer.inc.php");
?>
