<?php
	require_once("inc/include_class_files.php");
	include_once("inc/checklogin.inc.php");
	$obj = new DBO();
	$objMsg = new MessageClass();
    $objinfo = new InformationClass();
	$post = $obj->protectXSS($_POST);

	if(isset($_POST['ajax']) && $_POST['ajax'] != '' && $_POST['id'] != '')
	{
		$super_msg_row = $obj->GetSingleRow("spssp_super_message", "id=".(int)$_POST['id']);
		$des = str_replace('<br />','',$super_msg_row['description']);
		echo $super_msg_row['title'].','.$des;
		exit;
	}

	include_once("inc/new.header.inc.php");

	if($_SESSION['user_type'] == 222 || $_SESSION['user_type'] == 333)
	{
		$stuff_users = $obj->GetAllRowsByCondition("spssp_user", "stuff_id=".(int)$_SESSION['adminid']);

		foreach($stuff_users as $su)
		{
			$user_id_arr[] = $su['id'];
			$staff_users[]=$su['id'];
		}
		if(!empty($user_id_arr))
		{
			$stuff_users_string = implode(",",$user_id_arr);

			$umsg_where = " admin_viewed=0 and user_id in ( $stuff_users_string )  order by id DESC";
		}

		$amsg_where = " admin_id=".(int)$_SESSION['adminid'];
		if(!empty($staff_users))
		{
			if(in_array((int)$get['user_id'],$staff_users))
			{
				$var = 1;
			}
			else
			{
				$var = 0;
			}
		}
	}
	else
	{
		$umsg_where = " admin_viewed=0 order by id DESC";
		$amsg_where = " 1 = 1  order by id DESC";
		$var = 1;
	}

	if(isset($post['save_super']) && $post['save_super'] != '' && $_SESSION['user_type'] == 111 && $post['edit_sp'] == '')
	{
		if($post['title'] != '')
		{
			unset($post['save_super']);
			$post['display_order'] = time();
			$post['description'] = nl2br($post['description']);
			unset($post['edit_sp']);
			$lsid = $obj->InsertData("spssp_super_message", $post);
		}

	}
	else if(isset($post['edit_sp']) && $post['edit_sp'] != '' && $_SESSION['user_type'] == 111 )
	{

		$edit_arr['title'] = $post['title'];
		$edit_arr['description'] = nl2br($post['description']);

		$obj->UpdateData("spssp_super_message", $edit_arr, " id = ".(int)$post['edit_sp']);

	}

	if(isset($_GET['action']) && $_GET['action'] == 'delete' && (int)$_GET['smsg_id'] > 0 )
	{
		$obj->DeleteRow("spssp_super_message"," id =".(int)$_GET['smsg_id']);
	}

	$usermsgs = $obj->GetAllRowsByCondition("spssp_message",$umsg_where);
	$adminmsgs = $obj->GetAllRowsByCondition("spssp_admin_messages"," $amsg_where order by display_order desc ");

/* 削除はsearch_user2.phpで行う
	if($_GET['action']=='delete_user' && (int)$_GET['id'] > 0)
	{
		$sql = "delete from spssp_user where id=".(int)$_GET['id'];
		mysql_query($sql);
	}
*/
?>

		   <!--User view respect to admin start-->
<?php
	$table_users='spssp_user';
	$where_users = " stuff_id = ".$_SESSION['adminid']." and  party_day >= '".date("Y-m-d")."'";
	$data_per_page_users=10;
	$current_page_users=(int)$_GET['page'];

	$order="party_day ASC , party_day_with_time asc ";
	$query_string="SELECT * FROM $table_users where $where_users ORDER BY $order ;";
	$data_rows = $obj->getRowsByQuery($query_string);

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
color:#999999;
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

//$j('.datepicker').mouseover(function() {  $j('.datepicker').css('cursor', 'pointer'); });

});

function add_supper_message()
{
	$j(".new_super_message").toggle("slow");
	$j("#edit_sp").val('');
}
function save_super_message()
{
	var title = $j("#super_title").val();
	var desc = $j("#super_description").val();
	if(title == '')
	{
		alert("タイトルが未入力です");
		$j("#super_title").focus();
		return false;
	}
	if(desc == '')
	{
		alert("内容が未入力です");
		$j("#super_description").focus();
		return false;
	}

	document.super_msg_frm.submit();
}
function cancel_super_message()
{
	$j("#edit_sp").val('');
	$j(".new_super_message").fadeOut(500);
}

function view_dsc_super(id)
{
	$j("#super_desc_"+id).toggle("slow");

}
function edit_super_msg(id)
{

	var mid = id;
	$j.post('manage.php',{'ajax':'ajax','id':mid}, function(data){

		$j("#edit_sp").val(id);
		$j("#save_super").val('');
		var arr = data.split(",");
		$j("#super_title").val(arr[0]);
		$j("#super_description").val(arr[1]);
		$j("#super_desc_"+id).fadeOut(100);
		$j(".new_super_message").fadeIn(500);
	});
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

	date_from = document.condition.h_date_from.value;
	date_to = document.condition.h_date_to.value;
	mname= document.condition.h_man_lastname.value;
	wname = document.condition.h_woman_lastname.value;
	delete_user = "delete_user";

	$j.post('ajax/search_user2.php',{'action':delete_user, 'user_id':user_id,'date_from':date_from,'date_to':date_to,'mname':mname,'wname':wname,'sortOptin':sortOptin}, function(data){

	$j("#srch_result").fadeOut(100);
	$j("#srch_result").html(data);
	$j("#srch_result").fadeIn(700);
	$j("#box_table").fadeOut(100);
	});
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
		if (date_from != "") {
			if (ckDate(date_from) == false) {
				alert("披露宴開始日の日付指定が間違っています。\nカレンダーアイコンから選択するか、正しく入力してください");
				return false;
			}
		}
		if (date_to != "") {
			if (ckDate(date_to) == false) {
				alert("披露宴終了日の日付指定が間違っています。\nカレンダーアイコンから選択するか、正しく入力してください");
				return false;
			}
		}
		if (date_from != "" && date_to != "") {
			if (date_from > date_to) {
				alert("披露宴開始日より終了日が後の日付になっています。\n披露宴日の検索範囲を正しく指定してください");
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
<?php
include("inc/main_dbcon.inc.php");
$hcode=$HOTELID;
$hotel_name = $obj->GetSingleData("super_spssp_hotel ", " hotel_name ", " hotel_code=".$hcode);
include("inc/return_dbcon.inc.php");
?>
<h1><?=$hotel_name?></h1>

    <div id="top_btn">
        <a href="logout.php"><img src="img/common/btn_logout.jpg" alt="ログアウト" width="102" height="19" /></a>　
        <a href="javascript:;" onclick="MM_openBrWindow('../support/operation_h.html','','scrollbars=yes,width=620,height=600')"><img src="img/common/btn_help.jpg" alt="ヘルプ" width="82" height="19" /></a>
    </div>
</div>
<div id="container">
    <div id="contents">
        <h2>お知らせ</h2>

       <div style="font-size:12px; font-weight:bold;">
        <ul class="ul2">

            <?php
			foreach($data_rows as $row)
			{
				echo $objMsg->get_admin_side_order_print_mail_system_status_msg($row['id']);	// 席次・席札確認 → メッセージ表示
				echo $objMsg->get_admin_side_daylimit_system_status_msg($row['id']);			// 引出物確認 	　→ メッセージ表示
			}
			?>

			<?php
				$user_id_array=array();
            	foreach($usermsgs as $umsg)
				{
					if(in_array($umsg['user_id'],$user_id_array))
					continue;

					$user_id_array[]=$umsg['user_id'];

					$man_firstname = $obj->GetSingleData("spssp_user", "man_firstname"," id=".$umsg['user_id']);

					$woman_firstname = $obj->GetSingleData("spssp_user", "woman_firstname"," id=".$umsg['user_id']);
					$party_day = $obj->GetSingleData("spssp_user", "party_day"," id=".$umsg['user_id']);

					$party_date_array=explode("-",$party_day);

					$party_day=$party_date_array[1]."/".$party_date_array[2];

				  $man_name = $objinfo->get_user_name_image_or_src($umsg['user_id'] ,$hotel_id=1, $name="man_lastname.png",$extra="thumb2");
          $woman_name = $objinfo->get_user_name_image_or_src($umsg['user_id'],$hotel_id=1 , $name="woman_lastname.png",$extra="thumb2");
          $user_name = $man_name."・".$woman_name;
					echo "<li><a href='message_user.php?user_id=".$umsg['user_id']."' >".$party_day." ".$user_name." 様よりの未読メッセージがあります。</a></li>";
				}
			?>
        </ul>
      </div>

        <h2>お客様一覧</h2>

            <!--<div id="top_imgbox">
                <a href="javascript:void()" onclick="todays_user();"><img src="img/common/img_top01.jpg" width="200" height="122" class="top_img01" /></a>
                <img src="img/common/img_top02.jpg" width="202" height="32" class="top_img02" />
            </div>-->
            <div id="top_search_view">

                <form action="" method="post" name="condition">
				<table width="720" border="0" cellpadding="0" cellspacing="0">

			  <tr style="height:30px;">
				<td width="80">披露宴日：</td>
				<td width="169"><input name="date_from" type="text" id="date_from"    style="background: url('img/common/icon_cal.gif') no-repeat scroll right center rgb(255, 255, 255); padding-right: 20px; " class="datepicker" readonly="readonly"/> </td>

				<!-- UCHIDA EDIT 11/07/26 -->
			    <!-- <td width="62" >～</td> -->
			    <td width="80" >～</td>

				<td width="389"><input name="date_to" type="text" id="date_to"   style="background: url('img/common/icon_cal.gif') no-repeat scroll right center rgb(255, 255, 255); padding-right: 20px; " class="datepicker" readonly="readonly" /></td>
			  </tr>
			  <tr style="height:30px;">
				<td>新郎姓：</td>
				<td><input name="man_lastname" type="text" id="man_lastname"  class="input_text" /></td>
			    <td>新婦姓：</td>
				<td><input name="woman_lastname" type="text" id="woman_lastname" class="input_text" /></td>
			  </tr>
			  </table>

			  <table width="720" border="0" cellpadding="0" cellspacing="8">
			  <tr>
			  	<td width="30" >&nbsp;</td>
				<td width="50" align="left" valign="bottom" >
					<a href="javascript:void(0);" onclick="validSearch();"><img src="img/common/btn_search1.jpg" alt="search" /></a></td>
				<td width="50" align="left" valign="bottom" >
					<a href="javascript:void(0);" onclick="clearForm()"><img border="0" height="22" alt="ｸﾘｱ" src="img/common/btn_clear.jpg"></a></td>
			 	<td width="50" align="left" valign="bottom" >
			 		<a href="manage.php"><img src="img/common/new_userlist.gif"/></a></td> <!-- UCHIDA EDIT 11/07/26 -->
			  </tr>
			   <tr>
			   <td>&nbsp; </td>
			    <td align="left" colspan="3" > <a href="newuser.php"><img src="img/common/new_register.gif" alt="New Register"></a></td>
			   </tr>
			</table>
                <input type="hidden" name="h_date_from" value="">
                <input type="hidden" name="h_date_to" value="">
                <input type="hidden" name="h_man_lastname" value="">
                <input type="hidden" name="h_woman_lastname" value="">
                <input type="hidden" name="h_sortOption" value="">
			</form>

            </div>

       		<p></p>
            <div style="width:100%; display:none;" id="srch_result"></div>
            <p></p>

		    <div class="box_table" id="box_table" style="height:360px; overflow-y:auto;">
            <p>&nbsp;</p>

            <div class="box4" style="width:1000px;">
                <table width="100%" border="0" align="center" cellpadding="1" cellspacing="1" >
                    <tr align="center">
                        <td width="70">披露宴日<span class="txt1">
                        	<a href="javascript:void(0);" onclick="sortAction('party_day asc + party_day_with_time asc');">▲</a>
                        	<a href="javascript:void(0);" onclick="sortAction('party_day desc + party_day_with_time desc');">▼</a></span>
                        </td>
                        <td width="150" > 新郎氏名<span class="txt1">
                        	<a href="javascript:void(0);" onclick="sortAction('man_furi_lastname asc');">▲</a>
                        	<a href="javascript:void(0);" onclick="sortAction('man_furi_lastname desc');">▼</a></span>
                        </td>
                        <td width="150" align="center" >新婦氏名<span class="txt1">
                        	<a href="javascript:void(0);" onclick="sortAction('woman_furi_lastname asc');">▲</a>
                        	<a href="javascript:void(0);" onclick="sortAction('woman_furi_lastname desc');">▼</a></span>
                        </td>
                    	<td width="60" >詳細</td>
                        <td width="80" >スタッフ</td>
                        <td width="60" >メッセージ</td>
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
			foreach($data_rows as $row)
			{
				$roomname =  $obj->GetSingleData(" spssp_room", " name", " id=".(int)$data_rows['room_id']);
				$party_roomname = $obj->GetSingleData(" spssp_room", " name", " id=".(int)$data_rows['party_room_id']);
				include("inc/main_dbcon.inc.php");
				$man_respect = $obj->GetSingleData(" spssp_respect", " title", " id=".(int)$data_rows['man_respect_id']);
				$woman_respect = $obj->GetSingleData(" spssp_respect", " title", " id=".(int)$data_rows['woman_respect_id']);
				include("inc/return_dbcon.inc.php");
        
				$staff_name = $obj->GetSingleData("spssp_admin","name"," id=".$row['stuff_id']);
        
        
				if($i%2==0)
				{
					$class = 'box5';
				}
				else
				{
					$class = 'box6';
				}
				//$last_login = $obj->GetSingleData("spssp_user_log","max(login_time)"," id=".$row['id']);

				$last_login = $obj->GetSingleRow("spssp_user_log", " user_id=".$row['id']." and admin_id='0' ORDER BY login_time DESC");

			?>
	            <div class="<?=$class?>" style="width:1000px; ">
                <table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">
                    <tr align="center">
						<td  width="70"><?=$obj->japanyDateFormateShortWithWeek($row['party_day'] )?></td>

                        <td width="150">
                        <?php
                          $man_name = $objinfo->get_user_name_image_or_src($row['id'] ,$hotel_id=1, $name="man_fullname.png",$extra="thumb1");
						  if($man_name==false){$man_name = $row['man_firstname']." ".$row['man_lastname'].' 様';}
						  echo $man_name;
					   ?>
                        </td>

                        <td width="150">
                        <?php
                           $woman_name = $objinfo->get_user_name_image_or_src($row['id'],$hotel_id=1 , $name="woman_fullname.png",$extra="thumb1");
						   if($woman_name==false){$woman_name = $row['woman_firstname']." ".$row['woman_lastname'].' 様';}
						   echo $woman_name;
					   ?>
                        </td>

                    	<td width="60"><a href="user_info.php?user_id=<?=$row['id']?>"><img src="img/common/customer_info.gif"  /></a></td>
                         <!--<td><?php //echo $obj->japanyDateFormate($row['party_day'] , $row['party_day_with_time'])?></td>-->

                        <td width="80"> <?=$staff_name?></td>
                        <td width="60" > <?php echo $objMsg->get_admin_side_user_list_new_status_notification_usual($row['id']);?> </td>
                        <td  width="80">
						<?php
// UCHIDA EDIT 11/08/03 'ログイン中' → ログイン時間
						if($last_login['login_time'] > "0000-00-00 00:00:00") {
							if($last_login['logout_time'] > "0000-00-00 00:00:00") {
								$dMsg = strftime('%m月%d日',strtotime($last_login['logout_time']));
								echo$dMsg;
							}else {
								$dMsg = strftime('%m月%d日',strtotime($last_login['login_time']));
								echo "<font color='#888888'>$dMsg</font>";
							}
					   	}
						?>
                        </td>
                        <td class="txt1" width="60" >
                        	<a href="user_dashboard.php?user_id=<?=$row['id']?>" target="_blank"><img src="img/common/customer_view.gif" /></a>
                        </td>

                        <td width="40">
                        	<?php
                        	echo $objMsg->admin_side_user_list_new_status_notification_image_link_system($row['id']); // 席次・席札確認 → アイコン表示
							?>
                        </td>

                        <td width="40">
						<?php echo $objMsg->admin_side_user_list_gift_day_limit_notification_image_link_system($row['id']); // 引出物確認     → アイコン表示
						?>
						</td>

                        <td width="40">
                        	<a href="javascript:void(0);" onclick="confirmDeleteUser(<?=$row['id']?>);" >
                        		<img src="img/common/btn_deleate.gif"  />
                            </a>
                        </td>
                    </tr>
            	</table>
            </div>
            <?php
			$i++;
            }
			?>
        </div>
		 <div style="width:100%; margin-top:25px">
            	<?php
                	if($_SESSION['user_type'] == 111)
					{
				?>
                		<div class="txt2">
<!--  UCHIDA EDIT 11/08/08 表示方法を変更 -->
<!--                        <p>■管理会社より -News- &nbsp;&nbsp;&nbsp;<a href="javascript:void()" onclick="add_supper_message();">メッセージ作成 </a></p> -->
                        	<p><h2>管理会社より -News- 　　<a href="javascript:void()" onclick="add_supper_message();">メッセージ作成</a></h2></p>
                            <form action="manage.php" method="post" name="super_msg_frm">
                        	<table class="new_super_message" cellpadding="5" cellspacing="1" border="0" align="left">
                            	<tr>
                                	<?php
                                    	$nums = $obj->GetNumRows("spssp_super_message"," 1 = 1");
									?>
                                	<td width="30%" align="right"> No</td><td><input type="text" value="<?=$nums?>" readonly="readonly" size="1"/></td>
                                </tr>
								<tr>
                                	<td align="right"> タイトル </td><td> <input type="text" name="title" id="super_title" /></td>
                                </tr>
                                <tr>
                                	<td align="right">本文 </td>
                                    <td> <textarea name="description" id="super_description" cols="40" /></textarea></td>
                                </tr>
                                <tr>
                                	<td>&nbsp; </td>
                                    <td>
                                    	<input type="button" value="送信" onclick="save_super_message();" /> &nbsp;
                                        <input type="button" value="キャンセル" onclick="cancel_super_message();" />
                                        <input type="hidden" name="save_super" value="save_super" id="save_super" />
                                        <input type="hidden" id="edit_sp" value="" name="edit_sp" />
                                    </td>
                                </tr>
							</table>
                            </form>
                            <p></p>

							<ul class="ul3" id="message_BOX" style="height:200px; overflow:auto;">

                            	<?php
								include("inc/main_dbcon.inc.php");
								$super_messeges = $obj->GetAllRowsByCondition("super_admin_message "," 1 = 1 order by id desc");
								include("inc/return_dbcon.inc.php");
                                foreach($super_messeges as $msg)
                                {
                                    echo "<li><span class='date2'>".date('Y/m/d',$msg['display_order'])."</span> &nbsp; &nbsp; &nbsp; &nbsp;
									<a href='javascript:void(0);' onclick='view_dsc_super(".$msg['id'].")' id='super_title_".$msg['id']."'> ".$msg['title']."</a><br />
                                    <p class='super_desc' id='super_desc_".$msg['id']."'><span>".$msg['description']."</span> &nbsp; &nbsp; &nbsp; <a href='javascript:void();' onclick='edit_super_msg(".$msg['id'].")'> 編集 </a> &nbsp; &nbsp; &nbsp; <a href='javascript:void();' onclick=\"confirmDelete('manage.php?action=delete&smsg_id=".$msg['id']."')\"> 削除 </a></p></li>";

                                }
                            	?>
                            </ul>

                        </div>
                <?php
					}
					else
					{
				?>
<!--  UCHIDA EDIT 11/08/08 表示方法を変更 -->
<!--                         <div class="txt2">■管理会社より -News- -->
                        <div class="txt2"> <h2>管理会社より -News-</h2>
                            <ul class="ul3">

                            <?php
	include("inc/main_dbcon.inc.php");
 	$super_messeges = $obj->GetAllRowsByCondition(" super_admin_message "," 1 = 1 order by id desc");

 include("inc/return_dbcon.inc.php");
 foreach($super_messeges as $msg)
                                {
                                    echo "<li><span class='date2'>".date('Y/m/d',$msg['display_order'])."</span> &nbsp; &nbsp; &nbsp; &nbsp;
									<a href='javascript:void(0);' onclick='view_dsc_super(".$msg['id'].")' id='super_title_".$msg['id']."'> ".$msg['title']."</a><br />
                                    <p class='super_desc' id='super_desc_".$msg['id']."'><span>".$msg['description']."</span> </p></li>";

                                }?>
                            </ul>
                        </div>
                 <?php
                 	}
				 ?>
           </div>
		 <!--User view respect to admin END-->

    </div>
</div>

<?php
	include_once("inc/left_nav.inc.php");
?>


<?php
	include_once("inc/new.footer.inc.php");
?>
