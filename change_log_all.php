<?php
@session_start();

require_once("admin/inc/class.dbo.php");
include_once("admin/inc/dbcon.inc.php");
include_once("inc/checklogin.inc.php");
include("inc/new.header.inc.php");
include_once("admin/inc/class_data.dbo.php");

$user_id = (int)$_SESSION['userid'];

$dataObj = new DataClass;
//ログ出力に必要なデータを取得。class_data.dbo.jp
$logArray = $dataObj->get_all_log($user_id);

?>
<script>

var title=$("title");
$(title).html("席次表データ修正ログ - お客様情報 - ウエディングプラス");

$("ul#menu li").removeClass();
$("ul#menu li:eq(7)").addClass("active");

</script>

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
</style>
<script type="text/javascript" src="admin/calendar/calendar.js"></script>


<script type="text/javascript" language="javascript" src="datepicker/prototype-1.js"></script>

<script type="text/javascript" language="javascript" src="datepicker/prototype-date-extensions.js"></script>
<script type="text/javascript" language="javascript" src="datepicker/behaviour.js"></script>

<script type="text/javascript" language="javascript" src="datepicker/datepicker.js"></script>
<script type="text/javascript">

Control.DatePicker.Locale['ahad'] = { dateTimeFormat: 'yyyy-MM-dd HH:mm', dateFormat: 'yyyy-MM-dd', firstWeekDay: 1, weekend: [0,6], language: 'ahad'};

Control.DatePicker.Language['ahad'] = { months: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'], days:[  '日', '月','火', '水', '木', '金','土'], strings: { 'Now': '今度', 'Today': '今日', 'Time': '時間', 'Exact minutes': '正確な分', 'Select Date and Time': '閉じる', 'Open calendar': 'オープンカレンダー' } };



</script>

<link rel="stylesheet" href="datepicker/datepicker.css">
<script type="text/javascript" language="javascript" src="datepicker/behaviors.js"></script>



<div id="main_contents">
<div class="title_bar">
    	<div class="title_bar_txt_L";>席次表データ修正ログ</div>
    		<div class="clear"></div></div>


<div style="width:100%; text-align:center">


<!--search始まり

<form action="change_log_all.php" method="get">
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
search終わり-->
<div id="contents">
<!-- UCHIDA EDIT 11/08/04 ↓ -->
        <div class="box_table">
      		<div style="text-align:left; width:875px">&nbsp;</div>
<!-- UCHIDA EDIT 11/08/04 ↑ -->
      		<div class="box4">
                <table border="0" width="875px" align="center" cellpadding="1" cellspacing="1">
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
              <div class="<?php echo ($i%2==0)?"box5" :"box6" ;?>">
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
        <table width="875px" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><div style="height:20px; text-align:right"><a href="#">▲ページ上へ</a></div></td>
  </tr>
  <tr>
    <td align="center"><a href="user_info.php?user_id=<?=$user_id?>&stuff_id=<?=$stuff_id?>"><img src="img/btn_back_user.gif" width="43" height="17" alt="戻る" /></a></td>
  </tr>
</table></div>
</div>

</div><br /><br />
<?php
include("inc/new.footer.inc.php");
?>
