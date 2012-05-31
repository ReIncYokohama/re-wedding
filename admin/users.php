<?php
require_once("inc/include_class_files.php");
include_once("inc/checklogin.inc.php");
include_once("../fuel/load_classes.php");

$post = $obj->protectXSS($_POST);
$get = $obj->protectXSS($_GET);

include_once("inc/new.header.inc.php");

$staff_id = Core_Session::get_staff_id();

$messages = Model_Message::get_by_admin();

$user_id_arr = array();
if(!Core_Session::is_admin()) {
  Response::redirect("manage.php");
}


$search_party_day_start = "";
$search_party_day_end = "";
$search_man_name = "";
$search_woman_name = "";

$current_view = $_GET['view'];
if($current_view=="before") {
  $whereArr = array(array("party_day","<",date("Y-m-d")));
  $passPresent = "<a href='users.php'><img src='img/common/btn_honjitsu_on.jpg' /></a><img src='img/common/btn_kako.jpg' /></a>";
}else {
  $whereArr = array(array("party_day",">=",date("Y-m-d")));
  $passPresent = "<img src='img/common/btn_honjitsu.jpg' /></a><a href='users.php?view=before'><img src='img/common/btn_kako_on.jpg' /></a>";
}

$orderArr = array();
if($_GET["sort_option"]){
  $arr2 = explode("|",$_GET["sort_option"]);
  foreach($arr2 as $data){
    $arr = explode(",",$data);
    if(count($arr)==3){
      array_push($whereArr,$arr);
      if($arr[0]=="party_day" and $arr[1] == ">="){
        $search_party_day_start = $arr[2];
      }
      if($arr[0]=="party_day" and $arr[1] == "<="){
        $search_party_day_end = $arr[2];
      }
      if($arr[0]=="man_lastname"){
        $search_man_name = mb_substr($arr[2],1,mb_strlen($arr[2],"UTF-8")-2,"UTF-8");
      }
      if($arr[0]=="woman_lastname"){
        $search_woman_name = mb_substr($arr[2],1,mb_strlen($arr[2],"UTF-8")-2,"UTF-8");
      }
    }
  }
}
if($_GET["order_option"]){
  $arr = explode(",",$_GET["order_option"]);
  if(count($arr)==2){
    $orderArr[$arr[0]] = $arr[1];
  }
}else{
  $orderArr["party_day"] = "asc";
}

$users = Model_User::find(array("where"=>$whereArr,"order_by"=>$orderArr));
?>
<style>
.datepickerControl table
{
width:200px;
}
.input_text
{
width:120px;
border-style :inset;
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

<script src="../js/noConflict.js" type="text/javascript"></script>
<script type="text/javascript" src="calendar/calendar.js"></script>


<script type="text/javascript" language="javascript" src="../datepicker/prototype-1.js"></script>

<script type="text/javascript" language="javascript" src="../datepicker/prototype-date-extensions.js"></script>
<script type="text/javascript" language="javascript" src="../datepicker/behaviour.js"></script>

<script type="text/javascript" language="javascript" src="../datepicker/datepicker.js"></script>
<script type="text/javascript">

Control.DatePicker.Locale['ahad'] = { dateTimeFormat: 'yyyy/MM/dd HH:mm', dateFormat: 'yyyy/MM/dd', firstWeekDay: 1, weekend: [0,6], language: 'ahad'};

Control.DatePicker.Language['ahad'] = { months: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'], days:[  '日', '月','火', '水', '木', '金','土'], strings: { 'Now': '今度', 'Today': '今日', 'Time': '時間', 'Exact minutes': '正確な分', 'Select Date and Time': '閉じる', 'Open calendar': 'オープンカレンダー' } };



</script>

<link rel="stylesheet" href="../datepicker/datepicker.css">
<script type="text/javascript" language="javascript" src="../datepicker/behaviors.js"></script>

<script type="text/javascript">

function guestCheck()
{
	alert("必須項目は必ず入力してください。");
}

function alert_staff_plan()
{
	alert("席次表をできません");
	return false;
}

$j(function(){

	var msg_html=$j("#msg_rpt").html();

	if(msg_html!='')
	{
		$j("#msg_rpt").fadeOut(5000);
	}


});

//UCHIDA EDIT 11/08/02 日付フォームの確認
/****************************************************************
* 機　能： 入力された値が日付でYYYY/MM/DD形式になっているか調べる
* 引　数： datestr　入力された値
* 戻り値： 正：true　不正：false
****************************************************************/
function ckDate(datestr) {
	// 正規表現による書式チェック
	if(!datestr.match(/^\d{4}\/\d{2}\/\d{2}$/)){
		return false;
	}
	var vYear = datestr.substr(0, 4) - 0;
	var vMonth = datestr.substr(5, 2) - 1; // Javascriptは、0-11で表現
	var vDay = datestr.substr(8, 2) - 0;
	// 月,日の妥当性チェック
	if((vMonth >= 0 && vMonth <= 11) && (vDay >= 1 && vDay <= 31)){
		var vDt = new Date(vYear, vMonth, vDay);
		if(isNaN(vDt)){
			return false;
		}else if(vDt.getFullYear() == vYear && vDt.getMonth() == vMonth && vDt.getDate() == vDay){
			return true;
		}else{
			return false;
		}
	}else{
		return false;
	}
}

function confirmDeleteUser(user_id) {
	if(confirm("削除しても宜しいですか？") == false) return false;
	$j.post('ajax/search_user.php',{'action':"delete_user", 'user_id':user_id}, function(data){
      window.location.reload();
	});
}

function validSearch()
{

	var date_from;
	var date_to;
	var mname;
	var wname;

	date_from = $j("#date_from").val();
	date_to = $j("#date_to").val();
	mname= $j("#man_lastname").val();
	wname = $j("#woman_lastname").val();
  
	if(date_from == '' && date_to == '' && mname == '' && wname == '')
	{
		alert("検索項目のいづれかを入力してください");
		return false;
	}else
	{

	date = new Date();
	y = date.getFullYear();
	m = date.getMonth() + 1;
	d = date.getDate();
	if (m < 10) { m = "0" + m; }
	if (d < 10) { d = "0" + d; }
	var today = y + "/" + m + "/" + d;

		if (date_from != "") {
			if (ckDate(date_from) == false) {
				alert("披露宴開始日の日付指定が間違っています。\nカレンダーアイコンから選択するか、正しく入力してください");
				return false;
			}
		}
		if (date_from !="" && date_from < today) {
			alert("披露宴開始日が過去になっています"+date_from+" : "+today);
			return false;
		}
		if (date_to != "") {
			if (ckDate(date_to) == false) {
				alert("披露宴終了日の日付指定が間違っています。\nカレンダーアイコンから選択するか、正しく入力してください");
				return false;
			}
		}
		if (date_to!="" && date_to < today) {
			alert("披露宴終了日が過去になっています");
			return false;
		}
		if (date_from != "" && date_to != "") {
			if (date_from > date_to) {
				alert("検索開始日より検索終了日が先になっています。\n検索範囲を正しく指定してください");
				return false;
			}
		}
    sort_arr = [];
    if(date_from!=""){
      sort_arr.push("party_day,>=,"+date_from);
    }
    if(date_to!=""){
      sort_arr.push("party_day,<=,"+date_to);
    }
    if(mname!=""){
      sort_arr.push("man_lastname,like,%"+mname+"%");
    }
    if(wname!=""){
      sort_arr.push("woman_lastname,like,%"+wname+"%");
    }
    window.location = "?sort_option="+sort_arr.join("|");
	}
}

function clearForm()
{
	$j("#date_from").val('');
	$j("#date_to").val('');
	$j("#man_lastname").val('');
	$j("#woman_lastname").val('');
}
</script>

<div id="topnavi">
  <?php
$hotel = Model_Hotel::find_one_by_hotel_code($hcode);
$hotel_name = $hotel["hotel_name"];
?>
<h1><?=$hotel_name?></h1>
<?
include("inc/return_dbcon.inc.php");
?>

    <div id="top_btn">
        <a href="logout.php"><img src="img/common/btn_logout.jpg" alt="ログアウト" width="102" height="19" border="0" /></a>　
        <a href="javascript:;" onclick="MM_openBrWindow('../support/operation_h.html','','scrollbars=yes,width=620,height=600')"><img src="img/common/btn_help.jpg" alt="ヘルプ" width="82" height="19" border="0" /></a>
    </div>
</div>
<div id="container">
    <div id="contents">

    	<h2><div> 管理者用お客様一覧</div></h2>

            <div id="top_search_view">

                <form action="" name="condition" id="user_search_condition">
				<table width="720" border="0" cellpadding="0" cellspacing="0">

			  <tr style="height:30px;">
				<td width="80">披露宴日：</td>
				<td width="169"><input name="date_from" type="text" id="date_from" value="<?=$search_party_day_start?>" style="background: url('img/common/icon_cal.gif') no-repeat scroll right center rgb(255, 255, 255); padding-right: 20px; " class="datepicker" readonly="readonly"/> </td>
			    <td width="80" >～</td>

				<td width="389"><input name="date_to" type="text" id="date_to" value="<?=$search_party_day_end?>" style="background: url('img/common/icon_cal.gif') no-repeat scroll right center rgb(255, 255, 255); padding-right: 20px; " class="datepicker" readonly="readonly" /></td>
			  </tr>
			  <tr style="height:30px;">
				<td>新郎姓：</td>
				<td><input name="man_lastname" type="text" id="man_lastname"  class="input_text"  value="<?=$search_man_name?>" /></td>
			    <td>新婦姓：</td>
				<td><input name="woman_lastname" type="text" id="woman_lastname" class="input_text" value="<?=$search_woman_name?>" /></td>
			  </tr>
			  </table>

			  <table width="720" border="0" cellpadding="0" cellspacing="8">
			  <tr>
			  	<td width="55" >&nbsp;</td>
				<td width="90" align="left" valign="bottom" >
					<a href="javascript:void(0);" onclick="validSearch();"><img src="img/common/btn_search1.jpg" alt="search" width="82" height="22" border="0" /></a></td>
				<td width="90" align="left" valign="bottom" >
					<a href="javascript:void(0);" onclick="clearForm()"><img src="img/common/btn_clear.jpg" alt="ｸﾘｱ" width="82" height="22" border="0"></a></td>
			 	<td width="90" align="left" valign="bottom" >
			 		<a href="manage.php"><img src="img/common/new_userlist.gif" width="82" height="22" border="0"/></a></td>
			  </tr>
			   <tr>
			   <td>&nbsp; </td>
			    <td align="left" colspan="3" > <a href="user_info_allentry.php"><img src="img/common/new_register.gif" alt="New Register" border="0"></a></td>
			   </tr>
			</table>
			</form>
            </div>
       		<p>&nbsp;</p>
			<div id="passPresent"> <?php echo $passPresent; ?> </div>
		    <div class="box_table" id="box_table">

            <div class="box4" style="width:1000px;">
                <table width="100%" border="0" align="center" cellpadding="1" cellspacing="1" >
                    <tr align="center">
                        <td width="80">披露宴日<span class="txt1">
                        	<a href="?order_option=party_day,asc">▲</a>
                        	<a href="?order_option=party_day,desc">▼</a></span>
                        </td>
                        <td width="145" > 新郎氏名<span class="txt1">
                        	<a href="?order_option=man_furi_lastname,asc">▲</a>
                        	<a href="?order_option=man_furi_lastname,desc">▼</a></span>
                        　　</td>
                        <td width="145" align="center" >新婦氏名<span class="txt1">
                        	<a href="?order_option=woman_furi_lastname,asc">▲</a>
                        	<a href="?order_option=woman_furi_lastname,desc">▼</a></span>
                        　　</td>
                    	<td width="60" >詳細</td>
                        <td width="80" >スタッフ</td>
<?php
  if(!$IgnoreMessage){
?>
                        <td width="60" >メッセージ</td>
  <?php
  }
?>
                        <td width="80" >最終アクセス</td>
                        <td width="60" >&nbsp;</td>
                        <td width="40" >席次表</td>
                        <td width="40" >引出物</td>
                        <td  width="40">削除</td>
                    </tr>
                </table>
            </div>

            <?php
			$i=0;
			foreach($users as $user)
			{
        $row = $user->to_array();
        $man_name = $user->get_image_html("thumb2/man_fullname.png");
        $woman_name = $user->get_image_html("thumb2/woman_fullname.png");
        $party_day_date = Core_Date::create_from_date_string($user->party_day);
        $party_day_text = $party_day_date->format_string_date();
        $staff_name = $user->get_staffname();
        $class = 'box5';
        $userlog = Model_Userlog::get_user_last_login($user->id);
        $last_login = "";
        if($userlog) $last_login = $userlog->get_login_text();

			?>
	            <div class="<?=$class?>" style="width:1000px; ">
                <table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">
                    <tr align="center">
						<td  width="80"><?=$party_day_text?></td>

                        <td width="145" align="left">
                 <?=$man_name?>
                        </td>

                        <td width="145" align="left">
                        <?=$woman_name?>
                        </td>

                    	<td width="60"><a href="user_info_allentry.php?user_id=<?=$row['id']?>"><img src="img/common/customer_info.gif" border="0"  /></a></td>
                        <td width="80"> <?=$staff_name?></td>
<?php
  if(!$IgnoreMessage){
?>
                            <td width="60" > <a href='message_user.php?user_id=<?=$user_id?>&stuff_id=<?=$staff_id?>'><img src='img/common/btn_midoku.gif' border = '0'></a>
  <?php
  }
?>
                        <td  width="80">
						<?=$last_login?>
                        </td>
                        <td class="txt1" width="60" >
                        	<a href="user_dashboard.php?user_id=<?=$row['id']?>" target="_blank"><img src="img/common/customer_view.gif" border="0" /></a>
                      </td>

                        <td width="40">
                           <?php echo $user->get_sekijihyo_status();?>
                        </td>

                        <td width="40">
                           <?php echo $user->get_hikidemono_status();?>
						</td>

                        <td width="40">
                        	<a href="javascript:void(0);" onclick="confirmDeleteUser(<?=$user->id?>);" >
                        		<img src="img/common/btn_deleate.gif" border="0"  />
                            </a>
                        </td>
                    </tr>
            	</table>
            </div>
            <?php
			$i++;
            }
			?>
			<br /><br /><br /><br />
        </div>
    </div>
</div>

<?php
	include_once("inc/left_nav.inc.php");
?>

<?php
	include_once("inc/new.footer.inc.php");
?>
