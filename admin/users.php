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

	if($_GET['view']=="before")
	{
		if($_SESSION['user_type'] == 111  || $_SESSION['user_type'] == 333)
		{
// UCHIDA EDIT 11/08/03 本日以降を挙式日→披露宴日に変更
//			$where = " u.marriage_day < '".date("Y-m-d")."'";
			$where = " u.party_day < '".date("Y-m-d")."'";
		}
		else
		{
// UCHIDA EDIT 11/08/03 本日以降を挙式日→披露宴日に変更
//			$where = " u.marriage_day < '".date("Y-m-d")."' and  stuff_id=".(int)$_SESSION['adminid'];
			$where = " u.party_day < '".date("Y-m-d")."' and  stuff_id=".(int)$_SESSION['adminid'];
		}
		$redirect_url = 'users.php?view=before';
	}
	else
	{
		if($_SESSION['user_type'] == 111  || $_SESSION['user_type'] == 333)
		{
// UCHIDA EDIT 11/08/03 本日以降を挙式日→披露宴日に変更
//			$where = " u.marriage_day < '".date("Y-m-d")."'";
			$where = " u.party_day < '".date("Y-m-d")."'";
		}
		else
		{
// UCHIDA EDIT 11/08/03 本日以降を挙式日→披露宴日に変更
//			$where = " u.marriage_day < '".date("Y-m-d")."' and  u.stuff_id=".(int)$_SESSION['adminid'];
			$where = " u.party_day < '".date("Y-m-d")."' and  u.stuff_id=".(int)$_SESSION['adminid'];
		}
// UCHIDA EDIT 11/08/03 本日以降を挙式日→披露宴日に変更
//		$where = " u.marriage_day >= '".date("Y-m-d")."'";
		$where = " u.party_day >= '".date("Y-m-d")."'";
		$redirect_url = 'users.php';
	}

	if($_GET['action']=='delete_user' && (int)$_GET['id'] > 0)
	{
		$sql = "delete from spssp_user where id=".(int)$_GET['id'];
		mysql_query($sql);

	}
	if(isset($_GET['order_by']) && $_GET['order_by'] != '')
	{
		$orderby = mysql_real_escape_string($_GET['order_by']);
		$dir = mysql_real_escape_string($_GET['asc']);

		if($orderby=='mdate')
		{
			$order=" u.marriage_day ";

		}

		else if($orderby=='man_furi_firstname')
		{
			//$order=" woman_lastname ";
			$order=" u.man_furi_firstname ";
		}

		else if($orderby=='woman_furi_firstname')
		{
			$order=" u.woman_furi_firstname ";
			//$order=" man_lastname ";
		}
		else if($orderby=='stuff_id')
		{
			$order=" s.name";
			//$order=" man_lastname ";
		}
		if($dir == 'true')
		{
			$order.=' asc';
		}
		else
		{
			$order.=' desc';
		}

	}
	else
	{
		$order="party_day ASC";
	}

	if($_SESSION['user_type'] == 222)
	{
		$data = $obj->GetAllRowsByCondition("spssp_user"," stuff_id=".(int)$_SESSION['adminid']);
		foreach($data as $dt)
		{
			$staff_users[] = $dt['id'];
		}

	}





	if(isset($_POST['search_user']) && $_POST['search_user'] > 0)
	{
		if(isset($_POST['chk_woman_lastname']) && trim($_POST['woman_lastname']) != "")
		{

				/*$where .= " and ( UPPER(woman_lastname) like '%".strtoupper(trim($_POST['woman_lastname']))."%' or UPPER(woman_firstname) like '%".strtoupper(trim($_POST['woman_lastname']))."%') ";*/

				$where .= " and ( u.woman_lastname like '%".trim($_POST['woman_lastname'])."%' or u.woman_firstname like '%".trim($_POST['woman_lastname'])."%') ";

		}
		if(isset($_POST['chk_man_lastname']) && trim($_POST['man_lastname']) != "")
		{
				//$where .= " and man_lastname like '%".trim($_POST['man_lastname'])."%'";
				/*$where .= " and ( UPPER(man_lastname) like '%".strtoupper(trim($_POST['man_lastname']))."%' or UPPER(man_firstname) like '%".strtoupper(trim($_POST['man_lastname']))."%') ";*/

				$where .= " and ( u.man_lastname like '%".trim($_POST['man_lastname'])."%' or UPPER(u.man_firstname) like '%".trim($_POST['man_lastname'])."%') ";

		}
		if(isset($_POST['chk_marriage_day']) && trim($_POST['marriage_day']) != "")
		{

				$where .= " and u.marriage_day = '".trim($_POST['marriage_day'])."'";

		}
		//echo $where;exit;
	}

	if($_SESSION['user_type'] == 222  || $_SESSION['user_type'] == 333)
	{
		$data = $obj->GetAllRowsByCondition("spssp_user"," stuff_id=".(int)$_SESSION['adminid']);
		foreach($data as $dt)
		{
			$staff_users[] = $dt['id'];
		}
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
		$var = 1;
	}
	//$pageination = $obj->pagination($table, $where, $data_per_page,$current_page,$redirect_url);
	//$query_string="SELECT * FROM spssp_user where $where ORDER BY $order LIMIT ".((int)($current_page)*$data_per_page).",".((int)$data_per_page).";";
	$query_string="SELECT u.* FROM spssp_user as u join spssp_admin AS s on u.stuff_id = s.id where $where ORDER BY $order";
	//echo $query_string;

	$data_rows = $obj->getRowsByQuery($query_string);







?>
<style>
.datepickerControl table
{
width:200px;
}
.input_text
{
	width:125px;
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

function validSearch()
{
	var date_from;
	var date_to;
	var mdate;
	var mname;
	var wname;

		date_from = $j("#date_from").val();
		date_to = $j("#date_to").val();
		mdate = $j("#marriage_day").val();
		mname= $j("#man_lastname").val();
		wname = $j("#woman_lastname").val();

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
				alert("披露宴開始日より終了日が後の日付になっています。\n披露宴日の検索範囲を正しく指定してください");
				return false;
			}
		}

		$j.post('ajax/search_user.php',{'date_from':date_from,'date_to':date_to,'mdate':mdate,'mname':mname,'wname':wname}, function(data){

		$j("#srch_result").fadeOut(100)
		$j("#srch_result").html(data);
		$j("#srch_result").fadeIn(700);
		$j("#box_table").fadeOut(100);
	});
	}
}
function load_party_room(id)
{
	$j.post('user_info.php',{'ajax':'ajax','pid':id},function (data){
		$j("#party_room_id").fadeOut(100);
		$j("#party_room_id").html(data);
		$j("#party_room_id").fadeIn(300);
	});
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


    	<h2><div style="width:350px;"> 管理者用お客様一覧</div></h2>
		<!--<h2>新規登録</h2>-->  <h2><div style="width:100px;">お客様一覧</div></h2>
    <?php
if($noview==1)
{
?>
        <form action="insert_user.php" method="post" name="user_form_register">
        <table border="1" cellspacing="10" cellpadding="0" style="width:1000px;">
            <tr>
                <td width="150" align="right" nowrap="nowrap">新郎氏名：</td>
                <td width="350" nowrap="nowrap">
                	<input name="man_firstname" type="text" id="man_firstname"  class="input_text"  />
                    <input name="man_lastname" type="text" id="man_lastname"  class="input_text"  /> 様
              	</td>
                <td width="150" align="right" nowrap="nowrap">新婦氏名：</td>
                <td width="350" nowrap="nowrap">
                    <input name="woman_firstname" type="text" id="woman_firstname"  class="input_text"  />
                    <input name="woman_lastname" type="text" id="woman_lastname"  class="input_text"  />  様
                </td>
            </tr>
            <tr>
                <td align="right" nowrap="nowrap">フリガナ：</td>
                <td nowrap="nowrap">

                	<input name="man_furi_firstname" type="text" id="man_furi_firstname"  class="input_text" />
                    <input name="man_furi_lastname" type="text" id="man_furi_lastname"  class="input_text"  /> 様
                </td>

                <td align="right" nowrap="nowrap">フリガナ：</td>
                <td nowrap="nowrap">

                	<input name="woman_furi_firstname" type="text" id="woman_furi_firstname"  class="input_text"  />
                    <input name="woman_furi_lastname" type="text" id="woman_furi_lastname"  class="input_text"  /> 様
                </td>


            </tr>
            <tr>
            	<td align="right" nowrap="nowrap">挙式日：</td>
            	<td nowrap="nowrap">
                	<input name="marriage_day" type="text" id="marriage_day" size="10" readonly="readonly" style="background: url('img/common/icon_cal.gif') no-repeat scroll right center rgb(255, 255, 255); padding-right: 20px;" class="datepicker"/>
                <!--&nbsp;<a href="javascript:void(0)" onclick="document.getElementById('marriage_day').value='';">クリア </a>-->

                </td>
            	<td align="right" nowrap="nowrap">挙式時間：</td>
            	<td nowrap="nowrap">
                	<input name="marriage_day_with_time" type="text" id="marriage_day_with_time" size="10" readonly="readonly" style="background: url('img/common/icon_cal.gif') no-repeat scroll right center rgb(255, 255, 255); padding-right: 20px;" class="timepicker"/>
              <!--  &nbsp;<a href="javascript:void(0)" onclick="document.getElementById('marriage_day_with_time').value='';">クリア </a>-->
                </td>
            </tr>
            <tr>

            	<td align="right" nowrap="nowrap">挙式種類：</td>
            	<td nowrap="nowrap">
                	<select name="religion" id="religion" class="select" onchange="load_party_room(this.value);">
                        <option value=""  <?php if($_SESSION['regs'][religion]=='') {?> selected="selected" <?php } ?>>選択してください</option>
                        <?php
							$religions = $obj->GetAllRowsByCondition("spssp_religion", " 1=1 order by title asc");
							foreach($religions as $rel)
							{
								if($user_row['religion'] == $rel['id'])
								{
									$sel = "Selected = 'Selected'";
								}
								else
								{
									$sel = '';
								}
								echo "<option value='".$rel['id']."' $sel >".$rel['title']."</option>";
							}
						?>
                    </select>
                </td>
					<td align="right" nowrap="nowrap">挙式会場：</td>
            	<td nowrap="nowrap">
                	<input name="party_room_id" type="text" id="party_room_id"  class="input_text"  />
					<!--<select name="party_room_id" id="party_room_id" class="select" >
                    	<option value="0">選択してください</option>
						<?php
						$party_rooms = $obj->GetAllRowsByCondition("spssp_party_room"," 1=1 order by name asc");
                        if($party_rooms)
                        {
                            foreach($party_rooms as $pr)
                            {
                                if($pr['id'] == $user_row['party_room_id'])
									echo "<option value ='".$pr['id']."' selected> ".$pr['name']." </option>";
								else
								 	echo "<option value ='".$pr['id']."'> ".$pr['name']." </option>";

                            }
                        }
                    ?>

                	</select>   -->
                </td>
            </tr>
            <tr>
            	<td align="right" nowrap="nowrap">披露宴日：</td>
            	<td nowrap="nowrap">
                	<input name="party_day" type="text" id="party_day" size="10" readonly="readonly" style="background: url('img/common/icon_cal.gif') no-repeat scroll right center rgb(255, 255, 255); padding-right: 20px; " class="datepicker" />
                <!--&nbsp;<a href="javascript:void(0)" onclick="document.getElementById('party_day').value='';">クリア </a>-->
                </td>
            	<td align="right" nowrap="nowrap">披露宴時間：</td>
            	<td nowrap="nowrap">
                <input name="party_day_with_time" type="text" id="party_day_with_time" size="10" readonly="readonly" style="background: url('img/common/icon_cal.gif') no-repeat scroll right center rgb(255, 255, 255); padding-right: 20px; " class="timepicker"/>
               <!-- &nbsp;<a href="javascript:void(0)" onclick="document.getElementById('party_day_with_time').value='';">クリア </a>-->
            　
            披露宴会場：

                <select name="room_id" id="room_id" class="select" >


                    <?php
                        if($rooms)
                        {
                            foreach($rooms as $room)
                            {


								if($room['id']==$_SESSION['regs']['room_id'])
								echo "<option value ='".$room['id']."' selected> ".$room['name']." [".$room['max_seats']."]</option>";
								else
								 echo "<option value ='".$room['id']."'> ".$room['name']."</option>";
								 //SEAT NUMBER IF NEEDED." [".$room['max_rows']*$room['max_columns']*$room['max_seats']."]

                            }
                        }
                    ?>
                </select>

                </td>
            </tr>
            <tr>
            	<td colspan="4" align="left">
                	<a href="javascript:void(0);" onclick="valid_user();"><img src="img/common/btn_regist.jpg" border="0" width="62" height="22" /></a>
                </td>
            </tr>
        </table>
        </form>
<?php
}
////USER ENTRY FORM DISABLEED END

if($_SESSION['user_type'] == 333 || $_SESSION['user_type'] == 111)
{
?>


        <!--<h2>検索・編集・削除</h2>

        <p class="txt3">
        	<form action="users.php" method="post">
       		  　
       		  <input name="chk_man_lastname" value="1" type="checkbox" />
       		  新郎姓：<input name="man_lastname" type="text" id="新郎姓" class="input_text" /> &nbsp; &nbsp;
              <input name="chk_woman_lastname" type="checkbox" value="1" id="checkbox2" />
              新婦姓：
              <input name="woman_lastname" type="text" id="新婦姓"  class="input_text" /> &nbsp; &nbsp;
              <input type="checkbox" name="chk_marriage_day" value="1" id="chk_marriage_day" />
              挙式日：

                <input name="marriage_day" type="text" id="marriage_day" size="10" readonly="readonly" style="background: url('img/common/icon_cal.gif') no-repeat scroll right center rgb(255, 255, 255); padding-right: 20px;" class="datepicker"/>

                <br />
                <input type="hidden" name="search_user" value="1" />
                <input type="image" name="search" src="img/common/bt_search.jpg" width="62" height="22" />
            </form>
        </p>-->
<!--SEARCH FORM START-->
 <div id="top_search_view">


                	<form action="" method="post">
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

              </form>
            </div>
       		<p></p>
            <div style="width:100%; display:none;" id="srch_result">
            </div>

<!--SEARCH FORM END-->


        <div class="box_table"  id="box_table">
			<div class="bottom_line_box" style="width:1010px;">
        	<p class="txt3"><font color="#2052A3"><strong>席次表設定</strong></font></p>
			</div>
			<div>
				<?php if($_GET['view']=="before"){?>
				<a href="users.php"><font color="#2052A3"><strong>本日以降のお客様一覧</strong></font></a>

				<?php }else{?>
				<a href="users.php?view=before"><font color="#2052A3"><strong>過去のお客様一覧</strong></font></a>
				<?php }?>
			</div>

            <div class="page_next"><? #echo $pageination?></div>
            <?php if($_GET['view']=="before"){?>
			<div class="box4" style="width:1000px;" >
                <table border="0" align="center" cellpadding="1" cellspacing="1" width="100%">
                    <tr align="center">
<!-- UCHIDA EDIT 11/08/08 ソートでも過去のお客様情報を表示する ↓ -->
<!--
                    	<td width="68">詳細</td>
                      <td width="114">披露宴日<span class="txt1"><a href="users.php?order_by=mdate&asc=true">▲</a> <a href="users.php?order_by=mdate&asc=false">▼</a></span></td>
                        <td width="140">新郎氏名<span class="txt1"><a href="users.php?order_by=man_furi_firstname&asc=true">▲</a>
                        	<a href="users.php?order_by=man_furi_firstname&asc=false">▼</a></span>
                         </td>
                        <td width="140">新婦氏名<span class="txt1"><a href="users.php?order_by=woman_furi_firstname&asc=true">▲</a>
                        	<a href="users.php?order_by=woman_furi_firstname&asc=false">▼</a></span>
                        </td>
                      <td width="94">&nbsp;</td>
                        <td  width="88">スタッフ
						<span class="txt1"><a href="users.php?order_by=stuff_id&asc=true">▲</a>
                        	<a href="users.php?order_by=stuff_id&asc=false">▼</a></span>
						</td>
 -->
                        <td width="113">披露宴日<span class="txt1"><a href="users.php?order_by=mdate&asc=true<? if($_GET['view']=='before') {echo '&view=before';} ?>">▲</a> <a href="users.php?order_by=mdate&asc=false<? if($_GET['view']=='before') {echo '&view=before';} ?>">▼</a></span></td>
                        <td width="147">新郎氏名<span class="txt1"><a href="users.php?order_by=man_furi_firstname&asc=true<? if($_GET['view']=='before') {echo '&view=before';} ?>">▲</a>
                        	<a href="users.php?order_by=man_furi_firstname&asc=false<? if($_GET['view']=='before') {echo '&view=before';} ?>">▼</a></span>
                        </td>
                        <td width="147">新婦氏名<span class="txt1"><a href="users.php?order_by=woman_furi_firstname&asc=true<? if($_GET['view']=='before') {echo '&view=before';} ?>">▲</a>
                        	<a href="users.php?order_by=woman_furi_firstname&asc=false<? if($_GET['view']=='before') {echo '&view=before';} ?>">▼</a></span>
                        </td>
                        <td width="68">詳細</td>
                        <td  width="88">スタッフ
						<span class="txt1"><a href="users.php?order_by=stuff_id&asc=true<? if($_GET['view']=='before') {echo '&view=before';} ?>">▲</a>
                        	<a href="users.php?order_by=stuff_id&asc=false<? if($_GET['view']=='before') {echo '&view=before';} ?>">▼</a></span>
						</td>
<!-- UCHIDA EDIT 11/08/08 ソートでも過去のお客様情報を表示する ↑ -->
                        <td width="71">メッセージ</td>
						<!--<td>ログイン</td>-->
                        <td width="80">最終アクセス</td>
                        <td width="80">&nbsp;</td>
                        <td width="50">席次表</td>
                        <td width="50">引出物</td>
                        <td width="62">削除</td>
                    </tr>
                </table>
			</div>

			<?php }else{?>
			<div class="box4" style="width:1000px;" >
                <table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">
                    <tr align="center">
                        <td width="113">披露宴日<span class="txt1"><a href="users.php?order_by=mdate&asc=true">▲</a> <a href="users.php?order_by=mdate&asc=false">▼</a></span></td>
                        <td width="147">新郎氏名<span class="txt1"><a href="users.php?order_by=man_furi_firstname&asc=true">▲</a>
                        	<a href="users.php?order_by=man_furi_firstname&asc=false">▼</a></span>
                        </td>
                        <td width="147">新婦氏名<span class="txt1"><a href="users.php?order_by=woman_furi_firstname&asc=true">▲</a>
                        	<a href="users.php?order_by=woman_furi_firstname&asc=false">▼</a></span>
                        </td>
                    	<td width="68">詳細</td>
                        <td  width="88">スタッフ
						<span class="txt1"><a href="users.php?order_by=stuff_id&asc=true">▲</a>
                        	<a href="users.php?order_by=stuff_id&asc=false">▼</a></span>
						</td>
                        <td width="71">メッセージ</td>
						<!--<td>ログイン</td>-->
                        <td width="80">最終アクセス</td>
                        <td width="80">&nbsp;</td>
                        <td width="50">席次表</td>
                        <td width="50">引出物</td>
                        <td width="62">削除</td>
                    </tr>
                </table>
            </div>
			<?php }?>

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

				$user_messages = $obj->GetAllRowsByCondition("spssp_message"," user_id=".$row['id']);

				$admin_viewed = true;

				if(!empty($user_messages) )
				{
					foreach($user_messages as $msg)
					{
						if($msg['admin_viewed'] == 0)
						{
							$admin_viewed = false;
						}
					}
					if($admin_viewed== false)
					{
						$msg_opt = "<a href='message_user.php?user_id=".$row['id']."&stuff_id=".$row['stuff_id']."'><img src='img/common/btn_midoku.gif' border = '0'></a>";
					}
					else
					{
						$msg_opt = "<a href='message_user.php?user_id=".$row['id']."&stuff_id=".$row['stuff_id']."'><img src='img/common/btn_zumi.gif' border = '0'></a>";
					}

				}
				else
				{
					$msg_opt="";
				}

				$plan_row = $obj->GetSingleRow("spssp_plan", " user_id=".$row['id']);

				if(!empty($plan_row) && $plan_row['id'] > 0)
				{
					$conf_plan_row = $obj->GetSingleRow("spssp_plan_details", " plan_id=".$plan_row['id']);
					$user_guests = $obj->GetSingleRow("spssp_guest"," user_id=".$row['id']);
					if(!empty($conf_plan_row))
					{
						//$plan_link = "<a href='make_plan.php?plan_id=".$plan_row['id']."&user_id=".$row['id']."'><img src='img/common/btn_syori.gif'  border='0' /></a>";
						$plan_link = "<img src='img/common/btn_syori.gif'  border='0' />";
					}
					else
					{
						if(!empty($user_guests))
						{
							//$plan_link = "<a href='make_plan.php?plan_id=".$plan_row['id']."&user_id=".$row['id']."'><img src='img/common/btn_syori.gif' border='0' /></a>";
							$plan_link = "<img src='img/common/btn_syori.gif'  border='0' />";
						}
						else
						{
							$plan_link = "<a href='javascript:void(0);' onclick='guestCheck();'><img src='img/common/btn_kousei.gif'  border='0' /></a>";
						}
					}

					$layout_link = "<a href='set_table_layout.php?plan_id=".$plan_row['id']."&user_id=".(int)$row['id']."'><img src='img/common/btn_taku_edit.gif' boredr='0' > </a>";
				}
				else
				{
					$plan_link = "";
					$layout_link = "";
				}

				if($_SESSION['user_type'] == 222)
				{
					if(!empty($staff_users))
					{
						if(in_array($row['id'],$staff_users))
						{
							$delete_onclick = "confirmDelete('users.php?action=delete_user&page=".(int)$_GET['page']."&id=".$row['id']."');";
						}
						else
						{
							$delete_onclick = "alert_staff();";
						}
					}
					else
					{
						$delete_onclick = "alert_staff();";
					}
				}
				else
				{
					$delete_onclick = "confirmDelete('users.php?action=delete_user&page=".(int)$_GET['page']."&id=".$row['id']."');";
				}

			?>
            <div class="<?=$class?>" style="width:1000px;">
                <table width="100%" border="0" align="center" cellpadding="1" cellspacing="1" >
                    <tr align="center">
                        <td width="113"><?=$obj->japanyDateFormateShortWithWeek($row['party_day'])?></td>
                        <td width="147">
						<?php
                          $man_name = $objinfo->get_user_name_image_or_src($row['id'] ,$hotel_id=1, $name="man_fullname.png",$extra="thumb1");
						  if($man_name==false){$man_name = $row['man_firstname']." ".$row['man_lastname'].' 様';}
						  echo $man_name;
					    ?>
				        </td>
                        <td width="147">
						<?php
                           $woman_name = $objinfo->get_user_name_image_or_src($row['id'],$hotel_id=1 , $name="woman_fullname.png",$extra="thumb1");
						   if($woman_name==false){$woman_name = $row['woman_firstname']." ".$row['woman_lastname'].' 様';}
						   echo $woman_name;
					   ?>
						</td>
                    	<td width="68"><a href="user_info.php?user_id=<?=$row['id']?>"><img src="img/common/customer_info.gif" /></a></td>
                        <td width="88"> <?=$staff_name?></td>
                        <td width="71"> <?php echo $objMsg->get_admin_side_user_list_new_status_notification_usual($row['id'], $row['stuff_id']);?> </td>
                        <!-- <td></td>-->
					    <td width="80">
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

/*
                       if($last_login['login_time'] > "0000-00-00 00:00:00")
					   	{
							if($last_login['logout_time'] > "0000-00-00 00:00:00"){
							echo $obj->japanyDateFormateShort($last_login['login_time']);
							}else{
							echo 'ログイン中';
							}
						}
*/
						?>						<? //date("Y-m-d", mktime($last_login));?>
                        </td>
                        <td width="80" class="txt1">
                        	<a href="user_dashboard.php?user_id=<?=$row['id']?>" target="_blank"><img src="img/common/customer_view.gif" /></a>
                        </td>

                        <td width="50">
                        	<?php
                            	echo $objMsg->admin_side_user_list_new_status_notification_image_link_system($row['id']);
								/*<!--if($var == 1)
								{
									echo $plan_link ;
								}
								else
								{
									if(isset($conf_plan_row) && !empty($conf_plan_row))
									{
										//echo "<a href='view_plan.php?plan_id=".$plan_row['id']."&user_id=".$row['id']."'><img src='img/common/btn_syori.gif'  border='0' /></a>";
										echo "<img src='img/common/btn_syori.gif'  />";
									}
									else
									{
										//echo "<a href='javascript:void(0)' onclick='alert_staff_plan();'><img src='img/common/btn_syori.gif'  border='0' /></a>";
										echo "<img src='img/common/btn_syori.gif'  border='0' />";
									}
								}-->*/
							?>
                        </td>

						<td width="50">
					<?php echo $objMsg->admin_side_user_list_gift_day_limit_notification_image_link_system($row['id']);?>
					<?php	/*if(!empty($user_guests))
						{?>
						<img src="img/common/btn_kentou.gif" />
						<?php }else{ ?>
						<img src="img/common/btn_kentou.gif" />
						<?php }*/?>
						</td>
   <?php
	if($_SESSION['user_type'] == 111  || $_SESSION['user_type'] == 333)
	{
?>
                        <td width="62">
                        	<a href="javascript:void(0);" onclick="<?=$delete_onclick;?>" >
                        		<img src="img/common/btn_deleate.gif" />
                            </a>
                        </td>
<?php
	}
?>
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
