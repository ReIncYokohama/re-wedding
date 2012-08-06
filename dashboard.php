<?php
session_start();
require_once("admin/inc/include_class_files.php");
include_once("inc/checklogin.inc.php");
include_once("fuel/load_classes.php");

$user_id = $_SESSION['userid'];
$user = Model_User::find_by_pk($user_id);

$obj = new DBO();
$objMsg = new MessageClass();
$get = $obj->protectXSS($_GET);

$login_rows = $obj->GetAllRowsByCondition("spssp_user_log", " user_id=".$user_id." and admin_id='0'");

$admin_login = $obj->GetSingleRow("spssp_user_log", " user_id=".$user_id." ORDER BY login_time DESC");

$user_data = $obj->GetSingleRow("spssp_user", " id=".$user_id);

$fullTime = $user_data['party_day']." ".$user_data['party_day_with_time'];
$party_date = new DateTime($fullTime);
$time = $party_date->getTimestamp();
$date = getdate();
$day = ($time - mktime(0,0,0,$date["mon"],$date["mday"],$date["year"]))/(60*60*24);
$limit_day = floor($day);

$date= $party_date->format('Y/m/d h:i A');

$roomName = $obj->GetSingleData("spssp_room","name"," id = ".$user_data["room_id"]);

//tabの切り替え
$tab_home = true;

$TITLE = "ホーム - ウエディングプラス";
include("inc/new.header.inc.php");

// UCHIDA EDIT 11/08/09　フォーマトの変更があったので、japanyDateFormateをコピーす修正
function japanyDateFormateForDashboard($rawTime, $time_24h=0) {

	//午前／午後hh時mm分
	$date = strftime('%Y年%m月%d日',strtotime($rawTime));
	$day = strftime('%A',strtotime($rawTime));
	$weekday_E = array( "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday" );
	$weekday_J = array( "日", "月", "火", "水", "木", "金", "土" );
	$keys = array_keys($weekday_E, $day);
	echo $date."(".$weekday_J[$keys[0]].")"." "; // スペースを追加

	if($time_24h)
	{
		$day_TE = array( "PM", "AM" );
		$day_TJ = array( "午後", "午前");
		$timepart= strftime('%p',strtotime($time_24h));
		$keys_T = array_keys($day_TE, $timepart);

		$time = strftime('%I時%M分',strtotime($time_24h));
		echo $day_TJ[$keys_T[0]].$time;
	}
}

?>


<div id="main_contents" class="displayBox">

        <div id="bridal_info">
            <table width="500" border="0" cellspacing="1" cellpadding="0">
                <tr>
                <td width="105" nowrap="nowrap"><font size="3">披露宴日時：</font></td>
                <td width="392" nowrap="nowrap" ><font size="3"><?=japanyDateFormateForDashboard($user_data['party_day'],$user_data['party_day_with_time'])?></font></td>
                </tr>
                <tr>
                <td nowrap="nowrap"><font size="3">披露宴会場：</font></td>
                <td nowrap="nowrap"><font size="3"><?=$roomName;?></font></td>
                </tr>
            </table>
        </div>
        <div>

        </div>
        <div id="ready_to_date" >
        <?php
  if($limit_day>0){
    echo "披露宴日まで".$limit_day."日";
  }else if($limit_day==0){
    echo "披露宴日当日です";
  }else{
    echo "披露宴は終了しました";
  }
        ?>
		</div>
	<div class="clear"></div>
	<div class="title_bar">
    	<div class="title_bar_txt_L">お知らせ</div>
    	<!--<div class="title_bar_txt_R"><a href="admin_messages.php">＞メッセージ画面</a></div>-->
		<div class="clear"></div>
    </div>
	<div id="whatsnew_area" style="height:auto">
		<ul>
		<?php
  if(count($login_rows)==1 && !Core_Session::is_staff() && !Core_Session::is_super())
		echo "<div>ご結婚おめでとうございます。印刷物・引出物のリスト作成をよろしくお願いします。</div>";
		?>
<?php
$information_arr = $user->get_user_message();
foreach($information_arr as $msg){
  echo $msg;
}
echo $objMsg->get_message_csv_import_for_user($user_id);

$new_msg_count = $obj->GetRowCount("spssp_admin_messages","user_view=0 and  user_id='".$user_id."'");
if(!$IgnoreMessage && $new_msg_count>0)
  {
?>
			<div><a href="admin_messages.php">未読メッセージがあります。</a></div>
		<?php
			}
		?>
		</ul>
	</div>



<div class="title_bar">
    <div class="title_bar_txt_L">席次表・席札、引出物の準備</div>
    <div class="title_bar_txt_R"></div>
    <div class="clear"></div>
  </div>

	  <div class="cont_area">
    <div class="cont_txt01">以下の手順で席次表･席札、引出物のデータを作成してください。<br />
      ボタンをクリックすると各手順のページに移動します。</div>
    <div id="flow">
      <div class="step_box">
        <div class="step"><a href="table_layout.php"><img src="img/step_bt01.jpg" width="199" height="79" border="0" class="on" /></a></div>
        <!-- <div class="step_txt">披露宴のテーブルのレイアウト、卓名を決めます。</div> -->
        <div class="step_txt">披露宴会場のテーブルのレイアウト、卓名をご確認いただけます。</div>
        <div class="clear"></div>
      </div>
      <div class="step_triangle"><div align="center"><br></div></div>
      <div class="step_box">
        <div class="step"><a href="hikidemono.php"><img src="img/step_bt02.jpg" width="199" height="79" border="0" class="on" /></a></div>
        <!-- <div class="step_txt">引出物の商品の登録、お子様料理の登録を行います。</div> -->
        <div class="step_txt">引出物のグループ登録、および、お子様料理をご確認いただけます。</div>
        <div class="clear"></div>
      </div>
      <div class="step_triangle">
        <div align="center"><br></div>
      </div>
      <div class="step_box">
        <div class="step"><a href="my_guests.php"><img src="img/step_bt03.jpg" width="199" height="79" border="0" class="on" /></a></div>
        <div class="step_txt">ご招待するお客様のお名前、肩書などを入力し、引出物、料理などの種類を設定します。</div>
        <div class="clear"></div>
      </div>
      <div class="step_triangle">
        <div align="center"><br></div>
      </div>
      <div class="step_box">
        <div class="step"><a href="make_plan.php"><img src="img/step_bt04.jpg" width="199" height="79" border="0" class="on" /></a></div>
        <div class="step_txt_2">席次表をドラッグ＆ドロップで簡単に編集ができます。<br>
			プレビューで席次表、引出物の確認ができます。</div>
        <div class="clear"></div>
      </div>
      <div class="step_triangle">
        <div align="center"><br></div>
      </div>
      <div class="step_box">
        <div class="step"><a href="order.php"><img src="img/step_bt05.jpg" width="199" height="61" border="0" class="on" /></a></div>
        <div class="step_txt">席次表、引出物の発注を行います。</div>
        <div class="clear"></div>
      </div><br />

       <div class="step_box">
        <div class="step"><a href="download.php"><img src="img/list_download_bt.jpg" alt="招待者リストダウンロード" width="200" height="40" border="0" class="on" /></a></div>
        <div class="step_txt_2">招待者リストをエクセル形式でダウンロードできます。</div>
        <div class="clear"></div>
      </div>
      <div class="step_box">
        <div class="step"><a href="user_info.php"><img src="img/info_bt.jpg" alt="お客様情報" width="200" height="40" border="0" class="on" /></a></div>
        <div class="step_txt_2">お客様の婚礼情報です。</div>
        <div class="clear"></div>
      </div>
<?php
  if(!$IgnoreMessage){
?>
      <div class="step_box">
        <div class="step"><a href="admin_messages.php"><img src="img/message_bt.jpg" alt="メッセージ" width="200" height="40" border="0" class="on" /></a></div>
        <div class="step_txt_2">ホテル担当者とメッセージの送受信ができます。 </div>
        <div class="clear"></div>
      </div>
<?php
  }
?>

    </div>
    　<div class="clear"></div>
  </div>


</div>



<?php
include("inc/new.footer.inc.php");
?>
