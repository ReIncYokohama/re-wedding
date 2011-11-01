<?php
	require_once("inc/include_class_files.php");
	include_once("inc/checklogin.inc.php");
	include_once("inc/new.header.inc.php");

	$obj = new DBO();
	$objMsg = new MessageClass();
	$objinfo = new InformationClass();

	$rooms = $obj->GetAllRow("spssp_room");

	$table='spssp_user';

	$data_per_page=10;
	$current_page=(int)$_GET['page'];

	$current_view = $_GET['view'];
	if($current_view=="before") {
		$where = " party_day < '".date("Y-m-d")."'";
	}
	else {
		$where = " party_day >= '".date("Y-m-d")."'";
	}

/* 削除はsearch_user.phpで行う
	if($_GET['action']=='delete_user' && (int)$_GET['id'] > 0)
	{
		$sql = "delete from spssp_user where id=".(int)$_GET['id'];
		mysql_query($sql);
	}
*/
	$order="party_day ASC , party_day_with_time asc ";

	if($_SESSION['user_type'] == 222)
	{
		$data = $obj->GetAllRowsByCondition("spssp_user"," stuff_id=".(int)$_SESSION['adminid']);
		foreach($data as $dt)
		{
			$staff_users[] = $dt['id'];
		}
	}

	$query_string="SELECT spssp_user.*, spssp_admin.name FROM spssp_user INNER JOIN spssp_admin ON spssp_user.stuff_id = spssp_admin.id where $where ORDER BY $order";

//	echo $query_string;

	$data_rows = $obj->getRowsByQuery($query_string);

 	if($current_view=="before") {
//		$passPresent = "<a href='users.php'><font color='#2052A3'><strong>本日以降のお客様一覧</strong></font></a>";
		$passPresent = "<a href='users.php'><img src='img/common/btn_honjitsu_on.jpg' /></a>";
		$passPresent .= "<img src='img/common/btn_kako.jpg' /></a>";
 	}
	else {
//		$passPresent = "<a href='users.php?view=before'><font color='#2052A3'><strong>過去のお客様一覧</strong></font></a>";
		$passPresent = "<img src='img/common/btn_honjitsu.jpg' /></a>";
		$passPresent .= "<a href='users.php?view=before'><img src='img/common/btn_kako_on.jpg' /></a>";
	}

	include_once("inc/update_user_log_for_db.php");
	
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
	var date_from;
	var date_to;
	var mname;
	var wname;
	var view = "<?=$current_view?>";
	var sortOptin = document.condition.h_sortOption.value;
	var delete_user;

	if(confirm("削除しても宜しいですか？") == false) return false;

	date_from = document.condition.h_date_from.value;
	date_to = document.condition.h_date_to.value;
	mname= document.condition.h_man_lastname.value;
	wname = document.condition.h_woman_lastname.value;
	delete_user = "delete_user";

	$j.post('ajax/search_user.php',{'action':delete_user, 'user_id':user_id,'date_from':date_from,'date_to':date_to,'mname':mname,'wname':wname,'sortOptin':sortOptin,'view':view}, function(data){

	$j("#passPresent").fadeOut(100);
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
	var view = "<?=$current_view?>";

	date_from = document.condition.h_date_from.value;
	date_to = document.condition.h_date_to.value;
	mname= document.condition.h_man_lastname.value;
	wname = document.condition.h_woman_lastname.value;

	document.condition.h_sortOption.value = sortOptin;

	// スタッフのソートはstuff_idの有無で判別
	$j.post('ajax/search_user.php',{'date_from':date_from,'date_to':date_to,'mname':mname,'wname':wname,'sortOptin':sortOptin,'view':view}, function(data){

	$j("#passPresent").fadeOut(100);
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
	var view = "<?=$current_view?>";

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
//		alert("検索表示ボックスにチェックを入れてください");
		alert("検索項目のいずれかを入力してください"); // UCHIDA EDIT 11/08/02
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
				alert("検索開始日より検索終了日が先になっています。\n検索範囲を正しく指定してください");
				return false;
			}
		}

	 	$j.post('ajax/search_user.php',{'date_from':date_from,'date_to':date_to,'mname':mname,'wname':wname ,'view':view}, function(data){

		$j("#passPresent").fadeOut(100);
		$j("#srch_result").fadeOut(100);
		$j("#srch_result").html(data);
		$j("#srch_result").fadeIn(700);
		$j("#box_table").fadeOut(100);
		});
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
    <div id="contents">

    	<h2><div> 管理者用お客様一覧</div></h2>

<?php
if($_SESSION['user_type'] == 333 || $_SESSION['user_type'] == 111)
{
?>

<!--SEARCH FORM START-->
 <div id="top_search_view">

                	<form action="" method="post" name="condition">
					<table width="720" border="0" cellspacing="0" cellpadding="0">
					  <tr style="height:30px;">

						<!-- UCHIDA EDIT 11/07/26 -->
					    <!-- <td width="62" >～</td> -->
						<td width="80" >披露宴日</td>

						<td width="169"><input name="date_from" type="text" id="date_from"    style="background: url('img/common/icon_cal.gif') no-repeat scroll right center rgb(255, 255, 255); padding-right: 20px; " class="datepicker" readonly="readonly"/> </td>
						<td width="80" >&nbsp;～&nbsp;</td>
						<td width="389"><input name="date_to" type="text" id="date_to"   style="background: url('img/common/icon_cal.gif') no-repeat scroll right center rgb(255, 255, 255); padding-right: 20px; " class="datepicker" readonly="readonly" /></td>
					  </tr>
					  <tr style="height:30px;">
						<td>新郎姓：</td>
						<td><input name="man_lastname" type="text" id="man_lastname" class="input_text" /></td>
						<td>新婦姓:</td>
						<td><input name="woman_lastname" type="text" id="woman_lastname" class="input_text" /></td>
					  </tr>
					</table>
					<table width="720" border="0" cellpadding="0" cellspacing="8">
					  <tr>
					  	<td width="27" >&nbsp;</td>
					    <td width="50" align="left" valign="bottom" > <a href="javascript:void(0);" onclick="validSearch();"><img src="img/common/btn_search1.jpg" alt="検索" width="82" height="22" /></a></td>
					    <td width="50" align="left" valign="bottom"> <a href="javascript:void(0)" onclick="clearForm()"><img border="0" height="22" width="82" alt="クリア" src="img/common/btn_clear.jpg" ></a></td>
					 	<td width="30" align="left" valign="bottom" ><a href="users.php"><img border="0" height="22" width="82" alt="検索解除" src="img/common/btn_search_clear.jpg"/></a></td> <!-- UCHIDA EDIT 11/07/26 -->
				      </tr>
					</table>
		                <input type="hidden" name="h_date_from" value="">
		                <input type="hidden" name="h_date_to" value="">
		                <input type="hidden" name="h_man_lastname" value="">
		                <input type="hidden" name="h_woman_lastname" value="">
		                <input type="hidden" name="h_sortOption" value="">
              </form>
            </div>
       		<p>&nbsp;</p>

<!--SEARCH FORM END-->

			<div id="passPresent"> <?php echo $passPresent; ?> </div>

            <div style="width:100%; display:none;" id="srch_result"></div>

	        <div class="box_table"  id="box_table" style="height:485px; overflow-y:auto;">

            <div class="page_next"><? #echo $pageination?></div>

			<div class="box4" style="width:1000px;" >
                <table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">
                    <tr align="center">
                        <td width="70">披露宴日<span class="txt1">
                        	<a href="javascript:void(0);" onclick="sortAction('party_day asc + party_day_with_time asc');">▲</a>
                        	<a href="javascript:void(0);" onclick="sortAction('party_day desc + party_day_with_time desc ');">▼</a></span>
                        </td>
                        <td width="150" > 新郎氏名<span class="txt1">
                        	<a href="javascript:void(0);" onclick="sortAction('man_furi_lastname asc');">▲</a>
                        	<a href="javascript:void(0);" onclick="sortAction('man_furi_lastname desc');">▼</a></span>
                        </td>
                        <td width="150" align="center" >新婦氏名<span class="txt1">
                        	<a href="javascript:void(0);" onclick="sortAction('woman_furi_lastname asc');">▲</a>
                        	<a href="javascript:void(0);" onclick="sortAction('woman_furi_lastname desc');">▼</a></span>
                        </td>
                    	<td width="60">詳細</td>
                        <td  width="80">スタッフ<span class="txt1">
                        	<a href="javascript:void(0);" onclick="sortAction('spssp_admin.name asc');">▲</a>
                        	<a href="javascript:void(0);" onclick="sortAction('spssp_admin.name desc');">▼</a></span>
						</td>
                        <td width="60">メッセージ</td>
						<!--<td>ログイン</td>-->
                        <td width="80">最終アクセス</td>
                        <td width="60">&nbsp;</td>
                        <td width="40">席次表</td>
                        <td width="40">引出物</td>
                        <td width="40">削除</td>
                    </tr>
                </table>
            </div>

            <?php
			$i=0;
			foreach($data_rows as $row)
			{
				//$staff_name = $obj->GetSingleData("spssp_admin","name"," id=".$row['stuff_id']);

				if($i%2==0)
				{
					$class = 'box5';
				}
				else
				{
					$class = 'box6';
				}
				$last_login = $obj->GetSingleRow("spssp_user_log", " user_id=".$row['id']." and admin_id=0  ORDER BY login_time DESC");

				$user_messages = $obj->GetAllRowsByCondition("spssp_message"," user_id=".$row['id']);

				$user_id_arr[0] = $row['id'];
				update_user_log_for_db((int)(USER_LOGIN_TIMEOUT), $obj, $user_id_arr);
			?>
            <div class="<?=$class?>" style="width:1000px;">
                <table width="100%" border="0" align="center" cellpadding="1" cellspacing="1" >
                    <tr align="center">
                        <td width="70"><?=$obj->japanyDateFormateShortWithWeek($row['party_day'])?></td>
                        <td width="150" align="left">
						<?php
                          $man_name = $objinfo->get_user_name_image_or_src($row['id'] ,$hotel_id=1, $name="man_fullname.png",$extra="thumb1");
						  if($man_name==false){$man_name = $row['man_firstname']." ".$row['man_lastname'].' 様';}
						  echo $man_name;
					    ?>
				        </td>
                        <td width="150" align="left" >
						<?php
                           $woman_name = $objinfo->get_user_name_image_or_src($row['id'],$hotel_id=1 , $name="woman_fullname.png",$extra="thumb1");
						   if($woman_name==false){$woman_name = $row['woman_firstname']." ".$row['woman_lastname'].' 様';}
						   echo $woman_name;
					   ?>
						</td>
                    	<td width="60"><a href="user_info_allentry.php?user_id=<?=$row['id']?>&stuff_id=<?=$row['stuff_id']?>"><img src="img/common/customer_info.gif" /></a></td>
                        <td width="80"> <?=$row['name']?></td>
                        <td width="60"> <?php echo $objMsg->get_admin_side_user_list_new_status_notification_usual($row['id'], $row['stuff_id']);?> </td>
                        <!-- <td></td>-->
					    <td width="80">
                       <?php

						if($last_login['login_time'] != "0000-00-00 00:00:00" && $last_login['login_time']!="") {
							if($last_login['logout_time'] != "0000-00-00 00:00:00" && $last_login['logout_time'] != $last_login['login_time']) {
								$dMsg = date('m月d日',strtotime($last_login['logout_time']));
								echo $dMsg;
							}else {
								echo "ログイン中";
							}
					   	}
						?>
                        </td>
                        <td width="60" class="txt1">
                        	<a href="javascript:void(0);" onClick="windowUserOpen('user_dashboard.php?user_id=<?=$row['id']?>')" target="_userWindow"><img src="img/common/customer_view.gif" /></a>
                        </td>

                        <td width="40">
                        	<?php
                            	echo $objMsg->admin_side_user_list_new_status_notification_image_link_system($row['id']);
							?>
                        </td>
						<td width="40">
							<?php echo $objMsg->admin_side_user_list_gift_day_limit_notification_image_link_system($row['id']);?>
						</td>
                        <td width="40">
                        	<a href="javascript:void(0);" onclick="confirmDeleteUser(<?=$row['id']?>);" >
                        		<img src="img/common/btn_deleate.gif" />
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
<?php
	}

?>

    </div>
</div>

<?php
	include_once('inc/left_nav.inc.php');
	include_once("inc/new.footer.inc.php");
?>
 <?php if($_GET['err']){echo "<script>
			alert('".$obj->GetErrorMsgNew($err)."');
			</script>";}?>
		<?php if($_GET['msg']){echo "<script>
			alert('".$obj->GetSuccessMsgNew($_GET['msg'])."');
			</script>";}?>
