<?php
	require_once("inc/include_class_files.php");
	include_once("inc/checklogin.inc.php");

	$obj = new DBO();
	$objInfo = new InformationClass();

	if(isset($_POST['ajax']) && $_POST['ajax'] != '')
	{
		$pid = (int)$_POST['pid'];
		$prooms = $obj->GetAllRowsByCondition("spssp_party_room"," religion_id= $pid order by name asc");
		if(!empty($prooms))
		{
			foreach($prooms as $pr)
			{
				echo "<option value ='".$pr['id']."'> ".$pr['name']." </option>";
			}
		}
		exit;
	}

	include_once("inc/new.header.inc.php");

	$get = $obj->protectXSS($_GET);
	$user_id = (int)$get['user_id'];
	$user_row = $obj->GetSingleRow("spssp_user"," id= $user_id");

	$query_string="SELECT * FROM spssp_room where (status=1 or id=".$user_row['room_id'].")   ORDER BY display_order ASC ;";
	$rooms = $obj->getRowsByQuery($query_string);

	//$rooms = $obj->GetAllRow("spssp_room");
	$staff_name = $obj->GetSingleData("spssp_admin", "name"," id=".$user_row['stuff_id']);

// UCHIDA EDIT 11/08/03 内容をスタッフ画面に合わせて調整
//	$All_staffs = $obj->GetAllRowsByCondition("spssp_admin"," 1=1 ");
	$All_staffs = $obj->GetAllRowsByCondition("spssp_admin"," `permission` != '111' ORDER BY `permission` DESC");

	$room_name = $obj->GetSingleData("spssp_user", "room_name"," id=".$user_id);

	if($room_name=="")
	$room_name = $obj->GetSingleData("spssp_room", "name"," id=".$user_row['room_id']);
	//print_r($user_row);
	$room_plan_rows = $obj->GetAllRowsByCondition("spssp_default_plan"," room_id=".$user_row['room_id']);

	$user_plan_row = $obj->GetSingleRow("spssp_plan"," user_id= $user_id");
	$user_plan_row_count = $obj->GetRowCount("spssp_plan"," user_id= $user_id");


	$room_row = $obj->GetSingleRow("spssp_room"," id= ".$user_row['room_id']);

	if($_SESSION['user_type'] == 222)
	{
		$data = $obj->GetAllRowsByCondition("spssp_user"," stuff_id=".(int)$_SESSION['adminid']);
		foreach($data as $dt)
		{
			$staff_users[] = $dt['id'];
		}

		if(!empty($staff_users))
		{
			if(in_array($user_id,$staff_users))
			{
				$registration_onclick = "valid_user(".$user_id.");";
				$plan_onclick = "valid_plan();";
			}
			else
			{
				$registration_onclick="alert_staff();";
				$plan_onclick = "alert_staff();";
			}
		}
		else
		{
			$registration_onclick = "alert_staff();";
			$plan_onclick = "alert_staff();";
		}
	}
	else
	{
		$registration_onclick = "valid_user(".$user_id.");";
		$plan_onclick = "valid_plan();";
	}



	//USER GIFT GROUP NAME UPDaTE STart

	if($_POST['editUserGiftGroupsUpdate']=='editUserGiftGroupsUpdate')
	{
		unset($_POST['editUserGiftGroupsUpdate']);
		$number = count($_POST);
		$number = ($number/2);
		for($i=1;$i<=$number;$i++)
		{
			$array['name'] = $_POST['name'.$i];
			$obj->UpdateData("spssp_gift_group", $array," user_id=".$user_id." and id=".(int)$_POST['fieldId'.$i]);
		}

	}
	//USER GIFT GROUP NAME UPDaTE END
	//USER GIFT ITEM NAME UPDaTE STart
	if($_POST['editUserGiftItemsUpdate']=='editUserGiftItemsUpdate')
	{
		unset($_POST['editUserGiftItemsUpdate']);
		$number = count($_POST);
		$number = ($number/2);
		for($i=1;$i<=$number;$i++)
		{
			$array['name'] = $_POST['name'.$i];
			$obj->UpdateData("spssp_gift", $array," user_id=".$user_id." and id=".(int)$_POST['fieldId'.$i]);
		}

	}
	//USER GIFT ITEM NAME UPDaTE END
	//USER MENU ITEM NAME UPDaTE STart
	if($_POST['editUserMenuGroupsUpdate']=='editUserMenuGroupsUpdate')
	{

		unset($_POST['editUserMenuGroupsUpdate']);
		$number = count($_POST);
		$number = ($number/2);
		for($i=1;$i<=$number;$i++)
		{
			$array['name'] = $_POST['menu'.$i];
			$obj->UpdateData("spssp_menu_group", $array," user_id=".$user_id." and id=".(int)$_POST['menuId'.$i]);
		}

	}
	//USER MENU ITEM NAME UPDaTE END
?>
<style>
.datepickerControl table
{
width:200px;
}
.tables
	{
		height:31px;
		width: 31px;
		float:left;
		margin:5px 5px;
		background-image:url(img/circle_small.jpg);

	}

	.tables p
	{
		margin-top:5px;
	}

	.tables p a
	{
	text-decoration:none;
	cursor:default;

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

Control.DatePicker.Language['ahad'] = { months: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'], days: [  '日', '月','火', '水', '木', '金','土'], strings: { 'Now': '今度', 'Today': '今日', 'Time': '時間', 'Exact minutes': '正確な分', 'Select Date and Time': '閉じる', 'Open calendar': 'オープンカレンダー' } };

</script>

<link rel="stylesheet" href="../datepicker/datepicker.css">
<script type="text/javascript" language="javascript" src="../datepicker/behaviors.js"></script>
<script type="text/javascript" src="../js/registration_validation.js"></script>
<script type="text/javascript">
$j(function(){

	var msg_html=$j("#msg_rpt").html();

	if(msg_html!='')
	{
		$j("#msg_rpt").fadeOut(5000);
	}
});
$j(document).ready(function(){

    $j('#password').keyup(function(){
		var r=checkvalidity();
    });
});


function checkvalidity()
{
	var password = $j('#password').val();
	var c = password.length;
	if(c<6)
	{
		$j('#password_msg').html("<font style='color:black;'>パスワードは英数字6文字以上にしてください</font>");
	}
	else
	{
		$j('#password_msg').html("<font style='color:black;'>パスワードは英数字6文字以上にしてください</font>");
	}

    // All characters are numbers.
    return true;
}
function change_plan(planid)
{
	if(planid > 0)
	{
		$j(".plans").hide();
		$j("#plan_"+planid).fadeIn(500);
		$j.post('ajax/plan_preview_user_info.php',{'id':planid},function(data){
			var plan_arr = data.split(",");
			$j("#row_number").val(plan_arr[0]);
			$j("#column_number").val(plan_arr[1]);
			$j("#seat_number").val(plan_arr[2]);

			$j("#row_number").attr("readonly","readonly");
			$j("#column_number").attr("readonly","readonly");
			$j("#seat_number").attr("readonly","readonly");

		});

	}
	else
	{
		$j("#row_number").removeAttr("readonly");
		$j("#column_number").removeAttr("readonly");
		$j("#seat_number").removeAttr("readonly");

		$j("#row_number").val("");
		$j("#column_number").val("");
		$j("#seat_number").val("");

		$j(".plans").fadeOut(100);


	}
}


function valid_plan()
{

	var product_name = $j("#product_name").val();

	var print_size = $("print_size").options[$("print_size").selectedIndex].text;
	var print_type = $("print_type").options[$("print_type").selectedIndex].text;
	var print_company = $("print_company").options[$("print_company").selectedIndex].text;
	var dowload_options = $("dowload_options").options[$("dowload_options").selectedIndex].text;


	if (print_company == "選択してください") {
		alert("印刷会社名を選択してください");
		$j("#print_company").focus();
		return false;
	}
	if (product_name == "") {
		alert("商品名を入力してください");
		$j("#product_name").focus();
		return false;
	}
	if (dowload_options == "選択してください") {
		alert("商品区分を選択してください");
		$j("#dowload_options").focus();
		return false;
	}

	if (print_size == "選択してください") {
		alert("用紙サイズを選択してください");
		$j("#print_size").focus();
		return false;
	}
	if (print_type == "選択してください") {
		alert("用紙タイプを選択してください");
		$j("#print_type").focus();
		return false;
	}

	var row_num = $j("#row_number").val();
	var col_num = $j("#column_number").val();
	var seat_num = $j("#seat_number").val();
	var name = $j("#name").val();

	var max_rows=$j("#max_rows").val();
	var max_columns=$j("#max_columns").val();
	var max_seats=$j("#max_seats").val();

	var print_company = $("print_company").options[$("print_company").selectedIndex].text;

	if (print_company == "選択してください") {
		alert("印刷会社名を選択してください");
		$j("#print_company").focus();
		return false;
	}

	if(row_num == '')
	{
		alert("縦が未入力です");
		$j("#row_number").focus();
		return false;
	}
	if(!parseInt(row_num))
	{
		alert("数字のみの入力はできません。");
		$j("#row_number").focus();
		return false;
	}
	if(parseInt(row_num) > parseInt(max_rows))
	{
		alert("卓レイアウトは、披露宴会場設定の数値以下にしてください。 : "+max_rows);
		$j("#row_number").focus();
		return false;
	}


	if(col_num == '')
	{
		alert("列が未入力です");
		$j("#column_number").focus();
		return false;
	}
	if(!parseInt(col_num))
	{
		alert("数字のみの入力はできません。");
		$j("#column_number").focus();
		return false;
	}
	if(parseInt(col_num) > parseInt(max_columns))
	{
		alert("最大値は列の番号が許可されて: "+max_columns);
		$j("#column_number").focus();
		return false;
	}


	if(seat_num == '')
	{
		alert("一卓人数を入力してください。");
		$j("#seat_number").focus();
		return false;
	}
	if(!parseInt(seat_num))
	{
		alert("数字のみの入力はできません。");
		$j("#seat_number").focus();
		return false;
	}
	if(parseInt(seat_num) > parseInt(max_seats))
	{
		alert("会場の一卓人数 縦の値を超えています。: "+max_seats);
		$j("#seat_number").focus();
		return false;
	}


	if(name =='')
	{
		alert("プラン名を入力してください。");
		$j("#name").focus();
		return false;
	}
	document.user_info_plan.submit();
}

/*function load_party_room(id)
{
	$j.post('user_info.php',{'ajax':'ajax','pid':id},function (data){
		$j("#party_room_id").fadeOut(100);
		$j("#party_room_id").html(data);
		$j("#party_room_id").fadeIn(300);
	});
}*/
function checkGroupForm(x)
{	//alert(x);
	var error =1;
	for(var y=1;y<x;y++)
	{
		//alert(y);
		if($j("#name"+y).val()!="")
		{

			var error =0;
		}
	}

	if(error!=1)
	{
		document.editUserGiftGroupsForm.submit();
	}
	else
	{
		alert("引出物グループ名を入力してください");
	}
}
function checkGiftForm(x)
{	//alert(x);

// UCHIDA EDIT 11/08/04 引出物商品名は無入力でも許可
document.editUserGiftItemsForm.submit();
/*
	var error =1;
	for(var y=1;y<x;y++)
	{
		if($j("#item"+y).val()!="")
		{

			var error =0;
		}
	}
	if(error!=1)
	{
		document.editUserGiftItemsForm.submit();
	}
	else
	{
		alert("引出物商品名を入力してください");
	}
*/
}
function checkMenuGroupForm(x)
{	//alert(x);

	for(var y=0;y<x;y++)
	{
		if($j("#menu"+y).val()=="")
		{
			alert("引出物グループ名を入力してください");
			var error =1;
		}
	}
	if(error!=1)
	{
		document.editUserMenuGroupsForm.submit();
	}
}

function user_layout_title_input_show(id)
	{
		$j("#"+id).fadeOut();
		$j("#input_user_layoutname").fadeIn(500);

	}
function box_expand(id)
{

	$j("#"+id).toggle("slow");
}
</script>
<script type="text/javascript"><!--
function m_win(url,windowname,width,height) {
 var features="location=no, menubar=no, status=yes, scrollbars=yes, resizable=yes, toolbar=no";
 if (width) {
  if (window.screen.width > width)
   features+=", left="+(window.screen.width-width)/2;
  else width=window.screen.width;
  features+=", width="+width;
 }
 if (height) {
  if (window.screen.height > height)
   features+=", top="+(window.screen.height-height)/2;
  else height=window.screen.height;
  features+=", height="+height;
 }
 window.open(url,windowname,features);
}
// --></script>
<div id="topnavi">
    <?php
include("inc/main_dbcon.inc.php");
$hcode="0001";
$hotel_name = $obj->GetSingleData(" super_spssp_hotel ", " hotel_name ", " hotel_code=".$hcode);
?>
<h1><?=$hotel_name?>　管理</h1>
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
    <div style="font-size:20px; font-weight:bold; width:250px;">
            <?=$user_row['man_firstname']?> 様・ <?=$user_row['woman_firstname']?> 様
    </div>
        <h2><div style="width:400px; ">
	<!-- UCHIDA EDIT 11/08/02
	        <a href="users.php">お客様一覧</a> &raquo; お客様挙式情報</div>
	-->
	        <a href="manage.php">ＴＯＰ</a> &raquo; お客様挙式情報</div>
        </h2>
		<div style="width:700px;">
        	<div class="navi">
            	<img src="img/common/navi01_on.jpg" width="148" height="22" />
            </div>
        	<div class="navi">
            	<a href="message_user.php?user_id=<?=(int)$_GET['user_id']?>"><img src="img/common/navi02.jpg" width="96" height="22" class="on" /></a>
            </div>
        	<div class="navi">
            	<a href="user_dashboard.php?user_id=<?=$user_id?>" target="_blank">
            		<img src="img/common/navi03.jpg" width="126" height="22" class="on" />
                </a>
            </div>
        	<div class="navi"><a href="guest_gift.php?user_id=<?=$user_id?>"><img src="img/common/navi04.jpg" width="150" height="22" class="on" /></a></div>
        	<!--<div class="navi"><a href="customers_date_dl.php?user_id=<?=$user_id?>"><img src="img/common/navi05.jpg" width="116" height="22" class="on" /></a></div>-->
        	<div style="clear:both;"></div>
        </div>
        <br />

		<div class="bottom_line_box">
        	<p class="txt3"><div style="width:400px;"><font color="#2052A3"><strong>お客様挙式情報</strong>　<font color="red">*</font>項目は必須です。</font></div></p>
        </div>
		<?php
		//echo "<pre>";
		//print_r($user_row);

		?>
		<div id="div_box_1" style="width:1000px;">
        <form action="insert_user.php?user_id=<?=$user_id;?>" method="post" name="user_form_register">
        <table width="800" border="0" cellspacing="10" cellpadding="0">
            <tr>
                <td valign="top" nowrap="nowrap" width="80" style="text-align:right;" >挙式日<font color="red">*</font>：</td>
                <td nowrap="nowrap" width="60">

					<input name="marriage_day" type="text" id="marriage_day" value="<?=$obj->date_dashes_convert($user_row['marriage_day'])?>"  size="15" readonly="readonly" style="background: url('img/common/icon_cal.gif') no-repeat scroll right center rgb(255, 255, 255); padding-right: 20px; padding-top:4px; padding-bottom:4px;" class="datepicker" />

					<?php
					//$marriage_day_array = explode("-",$user_row['marriage_day']);
					?>
					<!--<input type="text" style="width:30px;" maxlength="4" name="marriage_year" id="marriage_year" value="<?=$marriage_day_array[0]?>">/
					<input type="text" style="width:17px;" maxlength="2" name="marriage_month" id="marriage_month" value="<?=$marriage_day_array[1]?>">/
					<input type="text" style="width:17px;" maxlength="2" name="marriage_day" id="marriage_day" value="<?=$marriage_day_array[2]?>"> yyyy/mm/dd-->
                	<!--&nbsp;<a href="javascript:void(0)" onclick="document.getElementById('marriage_day').value='';">クリア </a>-->
                </td>
                <td width="200" align="left" nowrap="nowrap" colspan="2">挙式時間<font color="red">*</font>：
                <!--<input name="marriage_day_with_time" type="text" id="marriage_day_with_time" value="<?=date("H:i",strtotime($user_row['marriage_day_with_time']))?>" size="10" readonly="readonly" style="background: url('img/common/icon_cal.gif') no-repeat scroll right center rgb(255, 255, 255); padding-right: 20px;" class="timepicker"/>-->
				<?php
					$marriage_time_array = explode(":",$user_row['marriage_day_with_time']);
					?>
				<input type="text" style="width:17px;padding-top:4px; padding-bottom:4px;" maxlength="2" name="marriage_hour" id="marriage_hour" value="<?=$marriage_time_array[0]?>"> :
				<input type="text" style="width:17px;padding-top:4px; padding-bottom:4px;" maxlength="2" name="marriage_minute" id="marriage_minute" value="<?=$marriage_time_array[1]?>">
                <!--&nbsp;<a href="javascript:void(0)" onclick="document.getElementById('marriage_day_with_time').value='';">クリア </a>-->
                </td>
            </tr>
            <tr>
                <td width="8%" align="right" valign="top" nowrap="nowrap">新郎氏名<font color="red">*</font>：</td>
                <td width="92%" nowrap="nowrap" colspan="3">
                    <input name="man_firstname" type="text" style="padding-top:4px; padding-bottom:4px;" id="man_firstname" value="<?=$user_row['man_firstname']?>" size="30" />
                    <input name="man_lastname" style="padding-top:4px; padding-bottom:4px;" type="text" id="man_lastname" value="<?=$user_row['man_lastname']?>" size="30" />
                	様　 <a onclick="m_win(this.href,'mywindow7',500,500); return false;" href="../gaiji/palette.html"><img src="img/common/btn_gaiji.jpg" width="82" height="22"alt="外字検索" title="外字検索" /></a>
                </td>

            </tr>
            <tr>
            	<td align="right" valign="top" nowrap="nowrap">ふりがな<font color="red">*</font>：</td>
           		<td nowrap="nowrap"  colspan="3">
            		<input name="man_furi_firstname" style="padding-top:4px; padding-bottom:4px;" type="text" id="man_furi_firstname" value="<?=$user_row['man_furi_firstname']?>" size="30" />
                   	<input name="man_furi_lastname" style="padding-top:4px; padding-bottom:4px;" type="text" id="man_furi_lastname" value="<?=$user_row['man_furi_lastname']?>" size="30" /> 様
                </td>
            </tr>
            <tr>
            	<td align="right" valign="top" nowrap="nowrap">新婦氏名<font color="red">*</font>：</td>
            	<td nowrap="nowrap"  colspan="3">
                	<input name="woman_firstname" style="padding-top:4px; padding-bottom:4px;" type="text" id="woman_firstname" value="<?=$user_row['woman_firstname']?>" size="30" />
                   	<input name="woman_lastname" style="padding-top:4px; padding-bottom:4px;" type="text" id="woman_lastname" value="<?=$user_row['woman_lastname']?>" size="30" />
                    様　<a onclick="m_win(this.href,'mywindow7',500,500); return false;" href="../gaiji/palette.html"><img src="img/common/btn_gaiji.jpg" width="82" height="22" alt="外字検索" title="外字検索" /></a>
                </td>

            </tr>
            <tr>
            	<td align="right" valign="top" nowrap="nowrap">ふりがな<font color="red">*</font>：</td>
            	<td nowrap="nowrap"  colspan="3">
            		<input name="woman_furi_firstname" style="padding-top:4px; padding-bottom:4px;" type="text" id="woman_furi_firstname" value="<?=$user_row['woman_furi_firstname']?>" size="30" />
                   	<input name="woman_furi_lastname" style="padding-top:4px; padding-bottom:4px;" type="text" id="woman_furi_lastname" value="<?=$user_row['woman_furi_lastname']?>" size="30" /> 様
                </td>
            </tr>
            <tr>

                <td align="right" nowrap="nowrap">挙式種類<font color="red">*</font>：</td>
                <td nowrap="nowrap">
                	<select name="religion" style="padding-top:4px; padding-bottom:4px;" id="religion" ><!--onchange="load_party_room(this.value);"-->

<!-- UCHIDA EDIT 11/08/03  <option value=""  <?php if($user_row[religion]=='') {?> selected="selected" <?php } ?>>選択してください</option> -->
                        <?php
							$religions = $obj->GetAllRowsByCondition("spssp_religion", " 1=1 order by display_order asc");
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

                <td nowrap="nowrap">
                	挙式会場<font color="red">*</font>：
					<?php
						$party_rooms_name = $obj->GetSingleData("spssp_party_room","name"," id=".$user_row['party_room_id']);

					?>
					<input name="party_room_id" style="padding-top:4px; padding-bottom:4px;" type="text" id="party_room_id"  class="input_text" value="<?=$party_rooms_name?>" />
					<!--<select name="party_room_id" id="party_room_id" style="width:106px;">


                    < ?php
						$party_rooms = $obj->GetAllRowsByCondition("spssp_party_room"," id=".$user_row['party_room_id']);
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
                	</select>-->
                </td>
            </tr>
            <tr>
                <td align="right" valign="top" nowrap="nowrap">披露宴日<font color="red">*</font>：</td>
                <td nowrap="nowrap" colspan="1">
                	<input name="party_day" type="text" id="party_day" value="<?=$obj->date_dashes_convert($user_row['party_day'])?>" size="15" readonly="readonly" style="background: url('img/common/icon_cal.gif') no-repeat scroll right center rgb(255, 255, 255); padding-right: 20px;padding-top:4px; padding-bottom:4px;" class="datepicker"/>
					<?php
					//$party_day_array = explode("-",$user_row['party_day']);
					?>
					<!--<input type="text" style="width:30px;" maxlength="4" name="party_year" id="party_year" value="<?=$party_day_array[0]?>">/
					<input type="text" style="width:17px;" maxlength="2" name="party_month" id="party_month" value="<?=$party_day_array[1]?>">/
					<input type="text" style="width:17px;" maxlength="2" name="party_day" id="party_day" value="<?=$party_day_array[2]?>"> yyyy/mm/dd-->
                <!--&nbsp;<a href="javascript:void(0)" onclick="document.getElementById('party_day').value='';">クリア </a>-->
                </td>
                <td colspan="2" align="left" nowrap="nowrap">披露宴時間<font color="red">*</font>：<!--<input name="party_day_with_time" type="text" id="party_day_with_time" value="<?=date("H:i", strtotime($user_row['party_day_with_time']))?>" size="10" readonly="readonly" style="width:86px;background: url('img/common/icon_cal.gif') no-repeat scroll right center rgb(255, 255, 255); padding-right: 20px;" class="timepicker"/>-->
				<?php
					$party_time_array = explode(":",$user_row['party_day_with_time']);
					?>
				<input type="text" style="padding-top:4px; padding-bottom:4px;width:17px;" maxlength="2" name="party_hour" id="party_hour" value="<?=$party_time_array[0]?>"> :
				<input type="text" style="padding-top:4px; padding-bottom:4px;width:17px;" maxlength="2" name="party_minute" id="party_minute" value="<?=$party_time_array[1]?>">
                <!--&nbsp;<a href="javascript:void(0)" onclick="document.getElementById('party_day_with_time').value='';">クリア </a>-->
                </td>
            </tr>
            <tr>
                <td align="right" valign="top" nowrap="nowrap">披露宴会場<font color="red">*</font>：</td>
                <td nowrap="nowrap">
				<input type="hidden" name="current_room_id" id="current_room_id" value="<?=$user_row['room_id']?>"  />
                	<select name="room_id" id="room_id" style="padding-top:4px; padding-bottom:4px;">

                    <?php
                        if($rooms)
                        {
                            foreach($rooms as $room)
                            {


								if($room['id']==$user_row['room_id'])
								echo "<option value ='".$room['id']."' selected> ".$room_name."</option>";
								else
								 echo "<option value ='".$room['id']."'> ".$room['name']."</option>";
								// [".$room['max_rows']*$room['max_columns']*$room['max_seats']."
                            }
                        }
                    ?>
                </select>
                </td>
                <td align="right" nowrap="nowrap">&nbsp;</td>
                <td nowrap="nowrap">&nbsp;</td>
            </tr>
            <tr>
                <td align="right" valign="top" nowrap="nowrap">メールアドレス：</td>
                <td colspan="3" nowrap="nowrap"><input style="padding-top:4px; padding-bottom:4px;" name="mail" type="text" id="mail" size="30" value="<?=$user_row['mail']?>" />

				</td>
            </tr>
			 <tr>
                <td align="right" valign="top" nowrap="nowrap">メールアドレス確認用：</td>
                <td colspan="3" nowrap="nowrap"><input style="padding-top:4px; padding-bottom:4px;" name="commail" type="text" id="con_mail" size="30" value="<?=$user_row['mail']?>" />
				</td>
            </tr>
			<tr>
				 <td align="right" valign="top" nowrap="nowrap">お知らせをメールで受信する:</td>
				<td colspan="3" nowrap="nowrap">

			<input type="radio" name="subcription_mail" value="0" <?php if($user_row['subcription_mail']=='0'){echo "checked='checked'";}else{echo "checked='checked'";}?> /> 受信する
			<input type="radio" name="subcription_mail" value="1" <?php if($user_row['subcription_mail']=='1'){echo "checked='checked'";}?>/>  受信しない
				</td>
			</tr>
            <tr>
                <td align="right" valign="top" nowrap="nowrap">ログインID：</td>
<!--                 <td colspan="3" nowrap="nowrap"><input name="user_id" style="padding-top:4px; padding-bottom:4px;" type="text" id="user_id" value="<?=$user_row['user_id']?>" size="30" /></td> -->
			    <td colspan="3" nowrap="nowrap"><?=$user_row['user_id']?></td>
            </tr>
            <tr>
                <td align="right" valign="top" nowrap="nowrap">お客様パスワード：</td>
<!--                <td colspan="3" nowrap="nowrap"><input name="password" style="padding-top:4px; padding-bottom:4px;" type="text" id="password" value="<?=$user_row['password']?>" size="30"  onblur="checkvalidity()"/> -->
			    <td colspan="3" nowrap="nowrap"><?=$user_row['password']?></td>
<!-- 				<br /><span id="password_msg">パスワードは英数字6文字以上にしてください</span> -->
				</td>
            </tr>
			<tr>
                <td align="right" valign="top" nowrap="nowrap">お客様ID利用期限日：</td>
               <?php $user_id_limit = $obj->GetSingleData("spssp_options" ,"option_value" ," option_name='user_id_limit'");


				$dateBeforeparty = $objInfo->get_date_with_supplyed_flag_difference( $user_row['party_day'] , $user_id_limit , $flag=1 );
			   ?>
			   <td colspan="3" nowrap="nowrap"><?=$dateBeforeparty?> <?=$user_id_limit?>日後</td>
            </tr>


            <tr>
                <td align="right" valign="top" nowrap="nowrap">担当：</td>
                <td colspan="3" nowrap="nowrap">
				<select name="stuff_id" style="padding-top:4px; padding-bottom:4px;">
				<?php
					foreach($All_staffs as $staf_rows){
				?>
				<option value="<?=$staf_rows['id']?>" <?php if($staf_rows['id']==$user_row['stuff_id']){echo "selected='selected'";}?> ><?=$staf_rows['name']?></option>

				<?php }?>
				</select>

				<!--<input name="stuff_id" type="text" id="stuff_id" value="<?=$staff_name?>" size="10" />-->
				</td>
            </tr>

            <tr>
                <td align="right" valign="top" nowrap="nowrap">&nbsp;</td>
                <td colspan="3" nowrap="nowrap" align="left">
                	<a href="javascript:void(0)" onclick="<?=$registration_onclick; ?>">
                    	<img src="img/common/btn_regist.jpg" border="0" width="62" height="22" />
                    </a>
                </td>
            </tr>
            <tr>
            	<td colspan="4" nowrap="nowrap"><div style="width:450px;">
                	<a href="user_log.php?user_id=<?=(int)$_GET['user_id']?>"><img src="img/common/btn_access.jpg" width="173" height="23" /></a>　
                    <a href="change_log.php?user_id=<?=(int)$_GET['user_id']?>"><img src="img/common/btn_data.jpg" width="173" height="23" /></a></div>
                </td>
            </tr>
        </table>
        </form>
		 </div> <!--end of  id="div_box_1"-->
        <br />
        <div class="bottom_line_box">
        	<p class="txt3"><font color="#2052A3"><strong>席次表設定</strong></font></p>
        </div>
        <?php
        	if(isset($_GET['err']) && $_GET['err']!='')
			{
				echo "<script>
				alert('".$obj->GetErrorMsgNew((int)$_GET['err'])."');
				</script>";


			}
		?>
		<div id="div_box_2" style="800px;">
        <form action="insert_user_info_plan.php?user_id=<?=(int)$_GET['user_id']?>" method="post" name="user_info_plan">
        <input type="hidden" id="max_rows" value="<?=$room_row['max_rows']?>" />
        <input type="hidden" id="max_columns" value="<?=$room_row['max_columns']?>" />
        <input type="hidden" id="max_seats" value="<?=$room_row['max_seats']?>" />

        <table width="850" border="0" cellspacing="10" cellpadding="0">




            <tr>
                <td width="150" align="right" nowrap="nowrap">披露宴会場名：</td>
              <td width="150" nowrap="nowrap">
                	<label for="新郎 姓3"></label>  <?=$room_name?>
                </td>
                 <td width="150" align="right" nowrap="nowrap">一卓人数：</td>
                <td nowrap="nowrap"><?php if($user_plan_row['seat_number']){echo $user_plan_row['seat_number'];}else{echo $room_row['max_seats'];}?>
              <input name="seat_number" type="hidden" id="seat_number" value="<?php if($user_plan_row['seat_number']){echo $user_plan_row['seat_number'];}else{echo $room_row['max_seats'];}?>" size="1" />人</td>

            </tr>
            <tr>
                <td align="right" nowrap="nowrap">最大卓数：</td>
                <td nowrap="nowrap">
                	横 <?php if($user_plan_row['column_number']){echo $user_plan_row['column_number'];}else{echo $room_row['max_columns'];}?>
                    <input name="column_number" type="hidden" id="column_number" value="<?php if($user_plan_row['column_number']){echo $user_plan_row['column_number'];}else{echo $room_row['max_columns'];}?>" size="1" />
                	列×縦 <?php if($user_plan_row['row_number']){echo $user_plan_row['row_number'];}else{echo $room_row['max_rows'];}?>
                    <input name="row_number" type="hidden" id="row_number" value="<?php if($user_plan_row['row_number']){echo $user_plan_row['row_number'];}else{echo $room_row['max_rows'];}?>" size="1" />
                      段
                </td>
                <td align="right" nowrap="nowrap">本発注締切日：</td>
                <td nowrap="nowrap">

				<?php
						 //$obj->date_dashes_convert($user_plan_row['confirm_date']);
						//$confirm_day_num = $obj->GetSingleData("spssp_options" ,"option_value" ," option_name='confirm_day_num'");
						$limitation_ranking = $obj->GetSingleData("spssp_options" ,"option_value" ," option_name='limitation_ranking'");


						$date6 = new DateTime($user_row['party_day']);
						$dateinterval = "-".$user_row['confirm_day_num']."day";
			            $date6->modify($dateinterval);
						echo $date6->format("Y/m/d");

					?>
					<input type="text" name="confirm_day_num" id="confirm_day_num" style="width:15px; padding:3px" maxlength="2" value="<?=$user_row['confirm_day_num']?>" /> 日前
					<input type="hidden" value="<?=$user_row['party_day']?>" name="party_day_for_confirm" />	</td>
            </tr>
            <tr>
                <td align="right" nowrap="nowrap">卓名変更：</td>
                <td nowrap="nowrap">
                	<?php
						$default_raname_table_view = $obj->GetSingleData("spssp_options" ,"option_value" ," option_name='rename_table_view'");
						if($user_plan_row['rename_table']=="" && $default_raname_table_view!="")
						{
					?>
				<input name="rename_table" type="radio" id="radio1" value="1"  <?php 	if($default_raname_table_view == 1){echo "checked='checked'";}?> />   可	            	<input type="radio" name="rename_table" id="radio0" value="0" <?php 	if($default_raname_table_view == "0"){echo "checked='checked'";}?> /> 不可　
 						<?php
						}else
						{?>
		<input name="rename_table" type="radio" id="radio1" value="1"  <?php if($user_plan_row['rename_table'] == 1){echo "checked='checked'";}?> />   可
		<input type="radio" name="rename_table" id="radio0" value="0" <?php if($user_plan_row['rename_table'] == "0"){echo "checked='checked'";}?> /> 不可

						<?php }
						?>
                </td>
                <td align="right" nowrap="nowrap">商品名：</td>
                <td nowrap="nowrap"><input name="product_name" type="text" id="product_name" value="<?=$user_plan_row['product_name']?>" size="10" /></td>
            </tr>
            <tr>
                <td align="right" nowrap="nowrap" width="150">席次表編集利用制限日：&nbsp;</td>
                <td nowrap="nowrap">

<!-- UCHIDA 日付領域に変更思案中 -->
<!--
				<?php
				$dateBeforeparty = $objInfo->get_date_with_supplyed_flag_difference( $user_row['party_day'] , $limitation_ranking , $flag=2 );
				?>
				<input name="limit_day" type="text" id="limit_day" value="<?=$dateBeforeparty?>"  size="15" readonly="readonly" style="background: url('img/common/icon_cal.gif') no-repeat scroll right center rgb(255, 255, 255); padding-right: 20px; padding-top:4px; padding-bottom:4px;" class="datepicker" />
<?=$limitation_ranking?>&nbsp;日前
 -->
				<?php
				$dateBeforeparty = $objInfo->get_date_with_supplyed_flag_difference( $user_row['party_day'] , $limitation_ranking , $flag=2 );
				?>
				<?=$dateBeforeparty?>  <?=$limitation_ranking?>&nbsp;日前
				</td>

                <td align="right" nowrap="nowrap">商品区分：</td>
                <td nowrap="nowrap">
                	<select name="dowload_options" id="dowload_options">
                    	<option value="1" <?php if($user_plan_row['dowload_options'] == 1) echo "selected='selected'";?>>席次表</option>
                        <option value="2" <?php if($user_plan_row['dowload_options'] == 2) echo "selected='selected'";?>>席札</option>
                        <option value="3" <?php if($user_plan_row['dowload_options'] == 3) echo "selected='selected'";?>>席次表・席札</option>
                    </select>
                </td>

            </tr>
            <tr>

                <td align="right" nowrap="nowrap">印刷会社名:</td>
                <td nowrap="nowrap">
                	<?php
// UCHIDA EDIT 11/08/02 印刷会社の並び順を印刷会社一覧と同様にする
//					$sql_company = "select * from spssp_printing_comapny order by id desc";
					$sql_company = "select * from spssp_printing_comapny ORDER BY display_order";
					$company_results = $obj->getRowsByQuery($sql_company);
					?>

					<select name="print_company" id="print_company">
						<option value="">選択してください</option>
						<?php foreach($company_results as $company){?>
						<option value="<?=$company['id']?>" <?php if($user_plan_row['print_company']==$company['id']){echo "selected='selected'";}?> ><?=$company['company_name']?></option>
						<?php }?>
					</select>

                </td>



                <td align="right" nowrap="nowrap">サイズ：</td>
                <td nowrap="nowrap">
				<select name="print_size" id="print_size">
					<option value="">選択してください</option>
					<option value="1" <?php if($user_plan_row['print_size'] == 1) echo "selected='selected'";?>>A3</option>
					<option value="2" <?php if($user_plan_row['print_size'] == 2) echo "selected='selected'";?>>B4</option>
				</select>
				<select name="print_type" id="print_type">
					<option value="">選択してください</option>
					<option value="1" <?php if($user_plan_row['print_type'] == 1) echo "selected='selected'";?>>横</option>
					<option value="2" <?php if($user_plan_row['print_type'] == 2) echo "selected='selected'";?>>縦
</option>
				</select>

				</td>
            </tr>
        </table>

        <div class="sekiji_table_L" id="plan_preview">

  			<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0">
            	<tr>
                	<td>
			<?php

            if(empty($user_plan_row))
            {

				 //print_r($room_plan_rows);
				 //echo "<br>";
                foreach($room_plan_rows as $plan)
                {

				    $plan_row = $plan;
                    $num_layouts = $obj->GetNumRows("spssp_table_layout"," default_plan_id=".$plan_row['id']);
					$layoutname = $obj->getSingleData("spssp_plan", "layoutname"," user_id= $user_id");

                   	$row_width = $plan_row['column_number'] *45;
                    echo "<div class='plans' id='plan_".$plan_row['id']."' style='width:".$row_width."px;margin:0 auto;'>";
					$default_layout_title = $obj->GetSingleData("spssp_options" ,"option_value" ," option_name='default_layout_title'");
					if($layoutname!="")
					{
						echo "<div style='display:block;text-align:center;width:100px;margin:0 auto;border:1px solid gray;'>".$layoutname."</div>";
					}
					else if($default_layout_title!="")
					{
						echo "<div style='display:block;text-align:center;width:100px;margin:0 auto;border:1px solid gray;'>".$default_layout_title."</div>";
					}
					else
					{
						echo "<p style='text-align:center'><img src='img/sakiji_icon/icon_takasago.gif' width='102' height='22' /></p>";
					}



                    $sqltrace = "select distinct row_order from spssp_table_layout where default_plan_id= ".(int)$plan['id'];
					$tblrows = $obj->getRowsByQuery($sqltrace);

					foreach($tblrows as $tblrow)
					{

						$ralign = $obj->GetSingleData("spssp_table_layout", "align"," row_order=".$tblrow['row_order']." and user_id=".(int)$user_id." limit 1");

							$num_none = $obj->GetSingleData("spssp_table_layout", "count(*) "," display=0 and row_order=".$tblrow['row_order']." and default_plan_id=".(int)$plan_row['id']." limit 1");

							if($ralign == 'L')
							{
								$styles = 'float:left;';
							}
							else if($ralign=='R')
							{
								$styles = 'float:right;';
							}
							else if($num_none>0)
							{
								$width = $row_width - ($num_none*41);
								$styles = "width:".$width."px;margin: 0 auto;";

							}
							else
							{
								$styles = "";
							}


						echo "<div class='rows' style='width:100%;float; left; clear:both;'><div style='".$styles."'>";
						$tables = $obj->getRowsByQuery("select * from spssp_table_layout where default_plan_id= ".(int)$plan['id']." and row_order=".$tblrow['row_order']);

						foreach($tables as $table)
						{
							$new_name_row = $obj->GetSingleRow("spssp_user_table", "default_plan_id = ".(int)$plan_row['id']." and default_table_id=".$table['id']);

							if(isset($new_name_row) && $new_name_row['id'] !='')
							{
								$tblname_row = $obj->GetSingleRow("spssp_tables_name","id=".$new_name_row['table_name_id']);
								$tblname = $tblname_row['name'];
							}
							else
							{
								$tblname = $table['name'];
							}


							if($table['visibility']==1 && $table['display']==1)
							{

								$disp = 'display:block;';

							}
							else if($table['visibility']==0 && $table['display']==1)
							{
								$disp = 'visibility:hidden;';
							}
							else if($table['display']==0 && $table['visibility']==0)
							{
								$disp = 'display:none;';
							}

							echo "<div class='tables' style='".$disp."'><p>".$tblname."</p></div>";
						}
						echo "</div></div>";
					}

					echo "</div>";


                }
            }
            else
            {


					$layout_rows = $obj->GetAllRowsByCondition("spssp_table_layout"," plan_id=".$user_plan_row['id']);

                    $num_layouts = $obj->GetNumRows("spssp_table_layout"," default_plan_id=".$user_plan_row['id']);

                   	$row_width = $user_plan_row['column_number'] *45;
                    echo "<div class='plans' id='plan_".$user_plan_row['id']."' style='width:".$row_width."px;margin:0 auto; display:block;'>";
					$default_layout_title = $obj->GetSingleData("spssp_options" ,"option_value" ," option_name='default_layout_title'");
					if($user_plan_row['layoutname']!="")
					{
						echo "<div id='user_layoutname'  style='display:block;text-align:center;width:100px;margin:0 auto;border:1px solid gray;'>".$user_plan_row['layoutname']."</div>";
					}
					elseif($default_layout_title!="")
					{
						echo "<div id='default_layout_title' style='display:block;text-align:center;width:100px;margin:0 auto;border:1px solid gray;'>".$default_layout_title."</div>";
					}
					else
					{
						echo "<p id='img_default_layout_title' style='text-align:center'><img src='img/sakiji_icon/icon_takasago.gif' width='102' height='22' /></p>";
					}

					echo "<div id='input_user_layoutname' style='display:none;'><input type='text' name='layoutname' value='".$user_plan_row['layoutname']."'></div>";
                    $tblrows = $obj->getRowsByQuery("select distinct row_order from spssp_table_layout where user_id= ".(int)$user_id);

					foreach($tblrows as $tblrow)
					{
						$ralign = $obj->GetSingleData("spssp_table_layout", "align"," row_order=".$tblrow['row_order']." and user_id=".(int)$user_id." limit 1");

							$num_none = $obj->GetSingleData("spssp_table_layout", "count(*) "," display=0 and row_order=".$tblrow['row_order']." and user_id=".$user_id." limit 1");

							if($ralign == 'L')
							{
								$styles = 'float:left;';
							}
							else if($ralign=='R')
							{
								$styles = 'float:right;';
							}
							else if($num_none>0)
							{
								$width = $row_width - ($num_none*41);
								$styles = "width:".$width."px;margin: 0 auto;";

							}
							else
							{
								$styles = "";
							}


						echo "<div class='rows' style='width:100%;float; left; clear:both;'><div style='".$styles."'>";
						$tables = $obj->getRowsByQuery("select * from spssp_table_layout where user_id= ".$user_id." and row_order=".$tblrow['row_order']);

						foreach($tables as $table)
						{
							$new_name_row = $obj->GetSingleRow("spssp_user_table", "user_id = ".$user_id." and default_table_id=".$table['id']);

							if(isset($new_name_row) && $new_name_row['id'] !='')
							{
								$tblname_row = $obj->GetSingleRow("spssp_tables_name","id=".$new_name_row['table_name_id']);
								$tblname = $tblname_row['name'];
							}
							else
							{
								$tblname = $table['name'];
							}


							if($table['visibility']==1 && $table['display']==1)
							{

								$disp = 'display:block;';

							}
							else if($table['visibility']==0 && $table['display']==1)
							{
								$disp = 'visibility:hidden;';
							}
							else if($table['display']==0 && $table['visibility']==0)
							{
								$disp = 'display:none;';
							}

							echo "<div class='tables' style='".$disp."'><p>".mb_substr ($tblname, 0,1,'UTF-8')."</p></div>";//mb_substr ($tblname, 0,1,'UTF-8')
						}
						echo "</div></div>";
					}

					echo "</div>";


            }
            ?>
				</td>
              </tr>
          </table>
　　　　　<?php
			if(!empty($user_plan_row))
            {

			?>
			<div>
				<a href='set_table_layout_edit.php?plan_id=<?=$user_plan_row['id']?>&user_id=<?=$user_id?>'>
                  <img src='img/common/btn_taku_edit.gif' boredr='0' height='17'>
              </a>
	    </div>
			<?php

			}
			?>
        </div>


        <br />
        <p class="txt3">
        	<?php
            //if(empty($user_plan_row))
            //{
			?>
            <a href="javascript:void(0);" onclick="<?=$plan_onclick;?>"> <img src="img/common/btn_regist.jpg" border="0" /> </a>
        	<!--<input type="button" style="width:62px;height:22px;background-image:url(img/common/btn_regist.jpg);" onclick="<?=$plan_onclick;?>"  />-->
			<?php
			//}
			?>
        </p>
        </form>
		</div> <!--end of  id="div_box_2"-->
        <br />
        <div class="bottom_line_box">
        	<p class="txt3"><font color="#2052A3"><strong>引出物設定</strong></font></p>
            <?php
            	$gift_groups = $obj->GetAllRowsByCondition("spssp_gift_group","user_id=".(int)$_GET['user_id']." order by id ASC");
				$gifts = $obj->GetAllRowsByCondition("spssp_gift","user_id=".(int)$_GET['user_id']." order by id ASC")
			?>
        </div>
        <div style="width:1000px; float:left;" id="div_box_3">
        	<div style="width:32%; float:left; ">
            	<div style="width:100px; float:left; ">
                	<p> 引出物商品： <p>
                </div>
                <div style="width:200px;; float:left;">

                	<?php
							/*$j=1;
                            foreach($gifts as $gift)
                            {
                            	echo " <input style='background:#EBEBE4;border:1px solid gray;padding:2px;' type='text' readonly='readonly' value='".$gift['name']."' size='13'> ";
								if($j%2==0)
								{
									echo "</p><p>";
								}
                            }*/
                      ?>

               <!--GIFT ITEM BOX START-->

<form action="user_info.php?user_id=<?=$user_id?>" method="post"  name="editUserGiftItemsForm">
	   <input type="hidden" name="editUserGiftItemsUpdate" value="editUserGiftItemsUpdate">
	   <table width="100%" border="0" cellspacing="1" cellpadding="0">



	   <?php
	  	$yy = 1;
		foreach($gifts as $gift_name)
		{
		echo "<tr><td style='text-align:left;'><input type='text' id='item".$yy."' style='padding-top:3px; padding-buttom:3px;' name='name".$yy."' value='".$gift_name['name']."' size='13'>&nbsp;&nbsp;&nbsp;";

		echo "<input type='hidden' name='fieldId".$yy."' value='".$gift_name['id']."'></td></tr>";
		$yy++;
		}

	  ?>
	  </table>
	  <br /><br />
	  <div>
	  <input type="button" name="editUserGiftItemsUpdateButton" value="保存" onclick="checkGiftForm(<?=$yy?>)"></div><br />
	  </form>
<!--GIFT ITEM BOX END-->

                </div>
            </div>
 <div style="width:100px; float:left; ">
 引出物グループ:
 </div>

            <div style="width:300px; float:left; ">

	   <!--GIFT GROUP BOX START-->
       	<p>

		<form action="user_info.php?user_id=<?=$user_id?>" method="post" name="editUserGiftGroupsForm">
		  <input type="hidden" name="editUserGiftGroupsUpdate" value="editUserGiftGroupsUpdate">
		  <?php
			$xx = 1;
			foreach($gift_groups as $row)
			{
				echo "<div style='float:left;margin-left:15px;margin-bottom:10px;'><input type='text' id='name".$xx."' ".$ro." name='name".$xx."' maxlength='4' size='6' value='".$row['name']."'>";
				echo "<input type='hidden' name='fieldId".$xx."' value='".$row['id']."'></div>";

				$xx++;
			}

		  ?>
		   <br /><br />
		   <input type="button" style="margin-left:15px;" name="editUserGiftGroupsUpdateButton" value="保存" onclick="checkGroupForm(<?=$xx?>);">
		   </form>
                </p>
<!--GIFT GROUP BOX END-->
				<br />
			<?php

			$gift_criteria = $obj->GetSingleRow("spssp_gift_criteria", " id=1");
			//print_r($gift_criteria);
			?>

				<p>
                	<?php

				$gift_criteria = $obj->GetSingleRow("spssp_gift_criteria", " id=1");
				$dateBeforeparty = $objInfo->get_date_with_supplyed_flag_difference( $user_row['party_day'] , $gift_criteria['order_deadline'] , $flag=2 );
					?>
					締切予定日： &nbsp; <input style="background:#EBEBE4;border:1px solid gray;padding:2px;" type="text" id="textfield15" readonly="readonly" value="<?=$obj->japanyDateFormate($dateBeforeparty)?>" size="25" />
                </p><br> <p>
                	締切日設定： &nbsp;<?=$gift_criteria['order_deadline']?> &nbsp;前日
                </p>
            </div>
        </div>

        <br />
        <div class="bottom_line_box">
        	<p class="txt3"><font color="#2052A3"><strong>料理設定</strong>（子供料理）</font></p>
            <?php
            	$menu_groups = $obj->GetAllRowsByCondition("spssp_menu_group","user_id=".(int)$_GET['user_id']);
				$num_groups = count($menu_groups);
			?>
        </div>
       <div id="div_box_4" style="width:500px;">

        	<?php
            	if($num_groups >0)
				{?>
				<form action="user_info.php?user_id=<?=$user_id?>" method="post" name="editUserMenuGroupsForm">
		  			<input type="hidden" name="editUserMenuGroupsUpdate" value="editUserMenuGroupsUpdate">
					 <table width="100%" border="0" cellspacing="10" cellpadding="0">
				<?php
					$i=1;
					echo "<tr>";
					foreach($menu_groups as $mg)
					{
						echo "<td>子供料理 $i :<input type='text' name='menu".$i."' id='item".$i."' value='".$mg['name']."' size='20'></td>";

						echo "<input type='hidden' name='menuId".$i."' value='".$mg['id']."'>";



						if($i%3==0)
						{
							echo "</tr><tr>";
						}
						$i++;
					}
					echo "<tr>";?>
					 <tr><td>
					 <input type="button" style="margin-left:15px;" name="editUserMenuGroupsUpdateButton" value="保存" onclick="checkMenuGroupForm(<?=$i?>);">
					 </td> </tr>
					 </table>

		   </form> <!--end of  id="div_box_3"-->

			<?php	}
			?>
          </div><!--END OF id="div_box_3" -->

        <br />
        <br />
        <br />

    </div>
</div>
<?php
	include_once('inc/left_nav.inc.php');
	include_once("inc/new.footer.inc.php");
?>
