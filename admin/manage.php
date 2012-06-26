<?php
//session_start();
//include_once("../fuel/load_fuel.php");
//exit;
require_once("inc/include_class_files.php");
include_once("inc/checklogin.inc.php");
include_once("../fuel/load_classes.php");

$post = $obj->protectXSS($_POST);
$get = $obj->protectXSS($_GET);

include_once("inc/new.header.inc.php");

$staff_id = Core_Session::get_staff_id();

$user_id_arr = array();

$search_party_day_start = "";
$search_party_day_end = "";
$search_man_name = "";
$search_woman_name = "";

if(!Core_Session::is_super()) {
  $whereArr = array(array("stuff_id","=",$_SESSION["adminid"]),
                    array("party_day",">=",date("Y-m-d")));
  $orderArr = array();
  if($_GET["sort_option"]){
    $arr2 = explode("|",$_GET["sort_option"]);
    foreach($arr2 as $data){
      $arr = explode(",",$data);
      if(count($arr)==3){
        array_push($whereArr,$arr);
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
  $information_arr = array();
  $messages = array();
  foreach($users as $user){
    $arr = $user->get_hotel_message();
    $information_arr = array_merge($information_arr,$arr);
    array_push($user_id_arr,$user->id);
    //メッセージ
    $ms = $user->get_messages_for_hotel();
    if($ms){
      $messages = array_merge($messages,$ms);
    }
  }
}
?>

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
	if(confirm("削除しても宜しいですか？") == false) return false;
	$j.post('ajax/search_user.php',{'action':"delete_user", 'user_id':user_id}, function(data){
      window.location.reload();
	});
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
			alert("披露宴開始日が過去になっています");
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

function hide_this(id)
{
	$j("#"+id).hide("slow");
}
</script>

<?php include_once("inc/topnavi.php");?>
<div id="container">
    <div id="contents">
        <h2>お知らせ</h2>

       <div style="font-size:12px; font-weight:bold;">
        <ul class="ul2">

<?php
foreach($information_arr as $info){
  echo $info;
}
$staff_id = Core_Session::get_staff_id();
$msgs = Model_Csvuploadlog::get_messages_for_hotel($staff_id);
echo implode("",$msgs);

$user_id_array=array();
foreach($messages as $message)
  {
    //既に読まれたものを表示しない
    if(in_array($message->user_id,$user_id_array)) continue;
    if($message->is_read()) continue;
    $user_id_array[]=$message->user_id;
    echo $message->get_message();
  }
?>
        </ul>
      </div>

        <h2>お客様一覧</h2>
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
			    <td align="left" colspan="3" > <a href="user_info_allentry.php"><img src="img/common/new_register.gif" alt="New Register" border="0" id="new_user_button"></a></td>
			   </tr>
			</table>
			</form>
            </div>
       		<p></p>
            <div style="width:100%; display:none;" id="srch_result"></div>
            <p></p>

		    <div class="box_table" id="box_table">
            <p>&nbsp;</p>

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
                            <td width="60" > 
<?php
if(!$user->is_read_by_admin()){
?>
<a href='message_user.php?user_id=<?=$user->id?>&stuff_id=<?=$staff_id?>'><img src='img/common/btn_midoku.gif' border = '0'></a>

  <?php
}
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
		 <div style="width:100%; margin-top:25px">
                        <div class="txt2"> <h2>管理会社より -News-</h2>
                            <ul class="ul3">
                            <div style="height:124px; overflow-y:auto;">
                            <?php
$super_messeges = Model_Supermessage::get_messages();
foreach($super_messeges as $msg)
                                {
                                    echo "<li><span class='date2'>".date('Y/m/d',$msg['display_order'])."</span> &nbsp; &nbsp; &nbsp; &nbsp;
									<a href='javascript:void(0);' onclick='view_dsc_super(".$msg['id'].")' id='super_title_".$msg['id']."'> ".$msg['title']."</a><br />
                                    <p class='super_desc' id='super_desc_".$msg['id']."'><span>".$msg['description']."</span> </p></li>";

                                }?>
                            </div>
                            </ul>
                        </div>

           </div>
    </div>
</div>

<?php
	include_once("inc/left_nav.inc.php");
?>

<?php
	include_once("inc/new.footer.inc.php");
?>
