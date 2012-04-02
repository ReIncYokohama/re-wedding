<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title></title>
   <link href="css/common.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../js/util.js"></script>
<style>
.datepicker
{
cursor:pointer;
}
.timepicker
{
cursor:pointer;
}
</style>
</head>

<body>
<div id="wrapper">
  <div id="header"> <img src="img/common/logo.jpg" width="200" height="57" /> </div>

<link rel="stylesheet" type="text/css" href="../css/jquery.ui.all.css">
<script src="../js/jquery-1.4.2.js" type="text/javascript"></script>
<script src="../js/jquery.ui.position.js" type="text/javascript"></script>
<script src="../js/jquery.ui.core.js" type="text/javascript"></script>
<script src="../js/jquery.ui.widget.js" type="text/javascript"></script>
<script type="text/javascript" src="../js/jquery.ui.dialog.js"></script>
<script src="../js/ui/jquery.effects.core.js"></script>
<script src="../js/ui/jquery.effects.blind.js"></script>
<script src="../js/ui/jquery.effects.fade.js"></script>


<script src="../js/noConflict.js" type="text/javascript"></script>

<script type="text/javascript" language="javascript" src="../datepicker/prototype-1.js"></script>

<script type="text/javascript" language="javascript" src="../datepicker/prototype-date-extensions.js"></script>
<script type="text/javascript" language="javascript" src="../datepicker/behaviour.js"></script>

<script type="text/javascript" language="javascript" src="../datepicker/datepicker.js"></script>
<script type="text/javascript">

Control.DatePicker.Locale['ahad'] = { dateTimeFormat: 'yyyy/MM/dd HH:mm', dateFormat: 'yyyy/MM/dd', firstWeekDay: 1, weekend: [0,6], language: 'ahad'};

Control.DatePicker.Language['ahad'] = { months: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'], days:[  '日', '月','火', '水', '木', '金','土'], strings: { 'Now': '今度', 'Today': '今日', 'Time': '時間', 'Exact minutes': '詳細に入力 ', 'Select Date and Time': '選択して日付と時刻', 'Open calendar': 'オープンカレンダー' } };
</script>

<link rel="stylesheet" href="../datepicker/datepicker.css">
<script type="text/javascript" language="javascript" src="../datepicker/behaviors.js"></script>


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
.datepicker
{
width:100px;
cursor:pointer;
}
.admin_desc
{
display:none;
padding:10px 10px 10px 120px;
}
.top_searchbox1, .top_searchbox2
{
width:490px;
}
.new_super_message
{
width:300px;
display:none;
}
.new_super_message tr td
{
padding:5px;
}
.super_desc
{
padding:5px 5px 5px 160px;;
display:none;
color:#000000;
font-weight:normal;
}

</style>
<script type="text/javascript">

var user_a_id;
$j(function(){

$j( ".msg_desc" ).dialog({
	autoOpen: false,
			height: 150,
			width: 400,
			show: "fade",
			hide: "clip",
			modal: true,
	buttons: {
		"送信": function() {
				$j("#"+user_a_id).html("Viewed");
				$j( this ).dialog( "close" );

		}
	},
	close: function() {

	}
});

});

function add_supper_message()
{
	$j(".new_super_message").toggle("slow");
	$j("#edit_sp").val('');
}
function cancel_super_message()
{
	$j("#edit_sp").val('');
	$j(".new_super_message").fadeOut(500);
}

function view_dsc_super(id)
{
	$j("#super_desc_"+id).toggle();

}

function viewMsg(id)
{
	$j.post('ajax/view_user_message.php', {'id':id},function(data) {

	});

	user_a_id= $j("#desc_"+id+" input").val();

	$j("#desc_"+id).dialog("open");

}

// UCHIDA EDIT 11/08/02 日付フォームの確認
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
	var date_from;
	var date_to;
	var mname;
	var wname;
	var sortOptin = document.condition.h_sortOption.value;
	var delete_user;

	if(confirm("削除しても宜しいですか？") == false) return false;

	date_from = $j("#date_from").val();
	date_to = $j("#date_to").val();
	mname= $j("#man_lastname").val();
	wname = $j("#woman_lastname").val();

	window.location = "manage.php?action=delete&id="+user_id+"&date_from="+date_from+"&date_to="+date_to+"&mname="+mname+"&wname="+wname+"&sortOptin="+sortOptin;
}

function sortAction(sortOptin)
{
	var date_from;
	var date_to;
	var mname;
	var wname;

	date_from = document.condition.h_date_from.value;
	date_to = document.condition.h_date_to.value;
	mname= document.condition.h_man_lastname.value;
	wname = document.condition.h_woman_lastname.value;

	document.condition.h_sortOption.value = sortOptin;

	$j.post('ajax/search_user2.php',{'date_from':date_from,'date_to':date_to,'mname':mname,'wname':wname,'sortOptin':sortOptin}, function(data){

	$j("#srch_result").fadeOut(100);
	$j("#srch_result").html(data);
	$j("#srch_result").fadeIn(700);
	$j("#box_table").fadeOut(100);
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

	document.condition.h_date_from.value = date_from;
	document.condition.h_date_to.value = date_to;
	document.condition.h_man_lastname.value = mname;
	document.condition.h_woman_lastname.value = wname;

	if(date_from == '' && date_to == '' && mname == '' && wname == '')
	{
		alert("検索項目のいづれかを入力してください");
		return false;
	}else
	{
// UCHIDA EDIT 11/08/02 日付チェックを追加

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

		$j.post('ajax/search_user2.php',{'date_from':date_from,'date_to':date_to,'mname':mname,'wname':wname}, function(data){

		$j("#srch_result").fadeOut(100);
		$j("#srch_result").html(data);
		$j("#srch_result").fadeIn(700);
		$j("#box_table").fadeOut(100);
		});
	}
}
function todays_user()
{
	$j.post('ajax/search_user.php',{'today':'today'}, function(data){
		$j("#srch_result").fadeOut(100);
		$j("#srch_result").html(data);
		$j("#srch_result").fadeIn(700);
		$j("#box_table").fadeOut(100);
	});
}

function view_dsc(id)
{
	$j("#admin_desc_"+id).toggle("slow");
}
function alert_staff_plan()
{
	alert("席次表をできません");
	return false;
}
function clearForm()
{
	$j("#date_from").val('');
	$j("#date_to").val('');
	$j("#man_lastname").val('');
	$j("#woman_lastname").val('');
}

function hide_this(id)
{
	$j("#"+id).hide("slow");
}
</script>

<div id="topnavi">
  <h1><?=$hotel_name?></h1>
  <div id="top_btn">
    <a href="logout.php"><img src="img/common/btn_logout.jpg" alt="ログアウト" width="102" height="19" border="0" /></a>　
    <a href="javascript:;" onclick="MM_openBrWindow('../support/operation_h.html','','scrollbars=yes,width=620,height=600')"><img src="img/common/btn_help.jpg" alt="ヘルプ" width="82" height="19" border="0" /></a>
  </div>
</div>
<div id="container">
  <div id="contents">
  <?=$contents?>
  </div>
</div>

<style>
#text-indent {
	text-indent: 125px; /* SEKIDUKA ADD 11/08/12 */
}
#foot_left {
	float: left; /* SEKIDUKA ADD 11/08/12 */
	width: 140px;
	text-align: left;
}
#foot_center {
	float: left;
	width: 205px; /* SEKIDUKA EDIT 11/08/12 */
	text-align: left;
}
#foot_right {
	float: left;
	width: 205px; /* SEKIDUKA EDIT 11/08/12 */
	text-align: left;
}
.clr {	clear: both;
}
</style>

<div id="sidebar">
	<div id="stuffname">
    	<img src="img/common/nav_stuffname.gif" alt="TOP" width="148" height="30" />
		<div id="stuffname_txt">
  <div style="font-size:18px;"><?php echo $staff_name;?></div>
		</div>
	</div>
    <ul class="nav">
        <li><a href="manage.php"><img src="img/common/nav_top.gif" alt="TOP" width="148" height="30" class=on /></a></li>
        <?php if($is_super){ ?>
        <li><img src="img/common/management.gif" alt="ホテル管理" width="148" height="30" /></li>
        <?php } ?>
        <li><a href="hotel_info.php"><img src="img/common/rules.gif" alt="TOP" width="148" height="30"/></a></li>
        <li><a href="staffs.php"><img src="img/common/nav_stuff.gif" alt="スタッフ" width="148" height="30" class=on /></a></li>
        <li><a href="invitation.php"><img src="img/common/nav_invetation.gif" alt="招待状" width="148" height="30" class=on /></a></li>
        <li><img src="img/common/nav_sekiji.gif" alt="席次表・席札" width="148" height="30" /></li>
            <li><a href="default.php"><img src="img/common/nav_table_name.gif" alt="基本設定" class=on /></a></li>
            <li><a href="religions.php"><img src="img/common/wedding_cate.gif" alt="挙式種類" width="148" height="20" class=on /></a></li>
        <?php if($is_super){ ?>
            <li><a href="respects.php"><img src="img/common/title.gif" alt="敬称" width="148" height="20" class=on /></a></li>
            <li><a href="guest_types.php"><img src="img/common/groups_set.gif" alt="区分の設定" width="148" height="20" class=on /></a></li>
        <?php } ?>
        <li><a href="rooms.php"><img src="img/common/nav_layout.gif" alt="会場ごとの最大卓レイアウト設定" class=on /></a></li>

        <li><a href="gift.php"><img src="img/common/nav_gift.gif" alt="引出物・料理" width="148" height="30" class=on /></a></li>

        <li><a href="printing_company.php"><img src="img/common/printing_company.gif" alt="printing_company" width="148" height="30" class=on /></a></li>

        <li><a href="useridlimit.php"><img src="img/common/nav_timelimit.gif" alt="limit_date" class=on /></a></li>
        <?php if($is_super){ ?>
		<li><a href="users.php"><img src="img/common/admin_customer_List.gif" alt="お客様一覧" width="148" height="43" class=on /></a></li>

       <?php } ?>
	    </ul>
        <br />
<div id="foot_left">
<table border="0" cellpadding="2" cellspacing="0" title="SSLサーバ証明書導入の証 グローバルサインのサイトシール">
<tr>
<td align="center" valign="top"> <span id="ss_img_wrapper_115-57_image_ja">
<a href="http://jp.globalsign.com/" target="_blank"> <img alt="SSL グローバルサインのサイトシール" border="0" id="ss_jpn2_gif" src="//seal.globalsign.com/SiteSeal/images/gs_noscript_115-57_ja.gif">
</a>
</span><br>
<script type="text/javascript" src="//seal.globalsign.com/SiteSeal/gs_image_115-57_ja.js" defer="defer"></script> <a href="https://www.sslcerts.jp/" target="_blank" style="color:#000000; text-decoration:none; font:bold 12px 'ＭＳ ゴシック',sans-serif; letter-spacing:.5px; text-align:center; margin:0px; padding:0px;">SSLとは?</a>
</td>
</tr>
</table>
</div>
        <br />
        <br />
        <br />
        <br />
        <br />
        <br />
        <br />
        <br />
        <br />
        <br />
        <br />
        <br />
        <br />
</div>
<script type="text/javascript">
function MM_openBrWindow(theURL,winName,features) { //v2.0

  window.open(theURL,winName,features);

}
</script>
<div id="undernavi">| <a style="font-size:10pt" href="javascript:;" onclick="MM_openBrWindow('../support/operation_h.html','','scrollbars=yes,width=620,height=600')">操作画面</a> | <a style="font-size:10pt" href="javascript:;" onclick="MM_openBrWindow('../support/security.html','','scrollbars=yes,width=620,height=600')">セキュリティ</a> | <a style="font-size:10pt" href="javascript:;" onclick="MM_openBrWindow('../support/privacy_policy.html','','scrollbars=yes,width=620,height=600')">個人情報保護方針</a> | <a style="font-size:10pt" href="javascript:;" onclick="MM_openBrWindow('../support/qa.html','','scrollbars=yes,width=620,height=600')">よくある質問 Q&amp;A</a> | <a style="font-size:10pt" href="javascript:;" onclick="MM_openBrWindow('../support/contact.html','','scrollbars=yes,width=620,height=600')">お問い合わせ</a> | </div>

<div id="footer" style="width:1200px;">
<p>Copyright (C) 株式会社サンプリンティングシステム ALL Rights reserved.</p>
</div>
</div>
</body>
</html>
