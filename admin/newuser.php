<?php
	include_once("inc/dbcon.inc.php");
	include_once("inc/checklogin.inc.php");
	include_once("inc/new.header.inc.php");
	require_once("inc/class.dbo.php");

	$obj = new DBO();

	$query_string="SELECT * FROM spssp_room where status=1  ORDER BY display_order ASC ;";
	$rooms = $obj->getRowsByQuery($query_string);

	$table='spssp_user';

	$data_per_page=10;
	$current_page=(int)$_GET['page'];
	if($_GET['view']=="before")
	{
		$where = " marriage_day < '".date("Y-m-d")."' and  stuff_id=".(int)$_SESSION['adminid'];
		$redirect_url = 'users.php?view=before';
	}
	else
	{
		$where = " marriage_day >= '".date("Y-m-d")."' and stuff_id=".(int)$_SESSION['adminid'];
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
			$order=" marriage_day ";

		}

		else if($orderby=='woman_last_name')
		{
			$order=" woman_firstname ";
		}

		else if($orderby=='man_last_name')
		{
			$order=" man_firstname ";
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
		$order="party_day DESC";
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
		if(isset($_POST['chk_woman_firstname']) && trim($_POST['woman_firstname']) != "")
		{

				/*$where .= " and ( UPPER(woman_firstname) like '%".strtoupper(trim($_POST['woman_firstname']))."%' or UPPER(woman_lastname) like '%".strtoupper(trim($_POST['woman_firstname']))."%') ";*/

				$where .= " and ( woman_firstname like '%".trim($_POST['woman_firstname'])."%' or woman_lastname like '%".trim($_POST['woman_firstname'])."%') ";

		}
		if(isset($_POST['chk_man_firstname']) && trim($_POST['man_firstname']) != "")
		{
				//$where .= " and man_firstname like '%".trim($_POST['man_firstname'])."%'";
				/*$where .= " and ( UPPER(man_firstname) like '%".strtoupper(trim($_POST['man_firstname']))."%' or UPPER(man_lastname) like '%".strtoupper(trim($_POST['man_firstname']))."%') ";*/

				$where .= " and ( man_firstname like '%".trim($_POST['man_firstname'])."%' or UPPER(man_lastname) like '%".trim($_POST['man_firstname'])."%') ";

		}
		if(isset($_POST['chk_marriage_day']) && trim($_POST['marriage_day']) != "")
		{

				$where .= " and marriage_day = '".trim($_POST['marriage_day'])."'";

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
	$pageination = $obj->pagination($table, $where, $data_per_page,$current_page,$redirect_url);
	$query_string="SELECT * FROM spssp_user where $where ORDER BY $order LIMIT ".((int)($current_page)*$data_per_page).",".((int)$data_per_page).";";
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

<script src="../js/noConflict.js" type="text/javascript"></script>
<script type="text/javascript" src="calendar/calendar.js"></script>


<script type="text/javascript" language="javascript" src="../datepicker/prototype-1.js"></script>

<script type="text/javascript" language="javascript" src="../datepicker/prototype-date-extensions.js"></script>
<script type="text/javascript" language="javascript" src="../datepicker/behaviour.js"></script>

<script type="text/javascript" language="javascript" src="../datepicker/datepicker.js"></script>
<script type="text/javascript" src="../js/gaiji.js"></script>
<script type="text/javascript">

Control.DatePicker.Locale['ahad'] = { dateTimeFormat: 'yyyy/MM/dd HH:mm', dateFormat: 'yyyy/MM/dd', firstWeekDay: 1, weekend: [0,6], language: 'ahad'};

Control.DatePicker.Language['ahad'] = { months: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'], days:[  '日', '月','火', '水', '木', '金','土'], strings: { 'Now': '今度', 'Today': '今日', 'Time': '時間', 'Exact minutes': '正確な分', 'Select Date and Time': '閉じる', 'Open calendar': 'オープンカレンダー' } };



</script>

<link rel="stylesheet" href="../datepicker/datepicker.css">
<script type="text/javascript" language="javascript" src="../datepicker/behaviors.js"></script>

<script type="text/javascript">
$j(document).ready(function(){
    setDeleteGaiji({
      input_id:"man_firstname",
          form_name:"male_last_gaiji_",
          div_image:"male_firstname_img_div_id"});
    setDeleteGaiji({
      input_id:"man_lastname",
          form_name:"male_first_gaiji_",
          div_image:"male_lastname_img_div_id"});
    setDeleteGaiji({
      input_id:"woman_firstname",
          form_name:"female_last_gaiji_",
          div_image:"female_firstname_img_div_id"});
    setDeleteGaiji({
      input_id:"woman_lastname",
          form_name:"female_first_gaiji_",
          div_image:"female_lastname_img_div_id"});
});

function valid_user()
{
	//AKIKUSA Edit about firstname&lastname 2011/08/22
	if(document.getElementById("man_lastname").value=='')
	{
		alert("新郎の姓を正しく入力してください");
		document.getElementById('man_lastname').focus();
		return false;
	}

	if(document.getElementById("man_firstname").value=='')
	{
		//alert("お客様情報が入力されておりません。");
		alert("新郎の名を正しく入力してください");

		document.getElementById('man_firstname').focus();
		return false;
	}
	if(document.getElementById("man_furi_lastname").value=='')
	{
		alert("新郎の姓のふりがなを正しく入力してください");
		document.getElementById('man_furi_lastname').focus();
		return false;
	}

   var str = document.getElementById("man_furi_lastname").value;
   if( str.match( /[^ぁ-ん\sー]+/ )  ) { // UCHIDA EDIT 11/08/03 'ー'を入力可
      alert("新郎の姓のふりがなを正しく入力してください");
	  document.getElementById('man_furi_lastname').focus();
	  return false;
   }


	if(document.getElementById("man_furi_firstname").value=='')
	{
		alert("新郎の名のふりがなを正しく入力してください");
		document.getElementById('man_furi_firstname').focus();
		return false;
	}

   var str1 = document.getElementById("man_furi_firstname").value;
   if( str1.match( /[^ぁ-ん\sー]+/ ) ) { // UCHIDA EDIT 11/08/03 'ー'を入力可
      alert("新郎の名のふりがなを正しく入力してください");
	  document.getElementById('man_furi_firstname').focus();
	  return false;
   }



	if(document.getElementById("woman_lastname").value=='')
	{
		alert("新婦の姓を正しく入力してください");
		document.getElementById('woman_lastname').focus();
		return false;
	}

	if(document.getElementById("woman_firstname").value=='')
	{
		alert("新婦の名を正しく入力してください");
		document.getElementById('woman_firstname').focus();
		return false;
	}
	if(document.getElementById("woman_furi_lastname").value=='')
	{
		alert("新婦の姓のふりがなを正しく入力してください");
		document.getElementById('woman_furi_lastname').focus();
		return false;
	}

    var str2 = document.getElementById("woman_furi_lastname").value;
   if( str2.match( /[^ぁ-ん\sー]+/ ) ) { // UCHIDA EDIT 11/08/03 'ー'を入力可
      alert("新婦の姓のふりがなを正しく入力してください");
	  document.getElementById('woman_furi_lastname').focus();
	  return false;
   }

	if(document.getElementById("woman_furi_firstname").value=='')
	{
		alert("新婦の名のふりがなを正しく入力してください");
		document.getElementById('woman_furi_firstname').focus();
		return false;
	}

    var str3 = document.getElementById("woman_furi_firstname").value;
   if( str3.match( /[^ぁ-ん\sー]+/ ) ) { // UCHIDA EDIT 11/08/03 'ー'を入力可
      alert("新婦の名のふりがなを正しく入力してください");
	  document.getElementById('woman_furi_firstname').focus();
	  return false;
   }


	if(document.getElementById("marriage_day").value=='')
	{
		alert("挙式日を正しく入力してください");
		document.getElementById('marriage_day').focus();
		return false;
	}
	/*if(document.getElementById("marriage_day_with_time").value=='')
	{
		alert("挙式時間を正しく入力してください");
		document.getElementById('marriage_day_with_time').focus();
		return false;
	}
		if(document.getElementById("marriage_day").value==''&&document.getElementById("marriage_year").value==''&&document.getElementById("marriage_month").value=='')
	{
		alert("挙式日を正しく入力してください");
		document.getElementById('marriage_year').focus();
		return false;
	}*/
	if(document.getElementById("marriage_hour").value=='')
	{
		alert("挙式時間を正しく入力してください");
		document.getElementById('marriage_hour').focus();
		return false;
	}

   var str4 = document.getElementById("marriage_hour").value;
   if( str4.match( /[^0-9\s]+/ ) ) {
      alert("挙式時間を正しく入力してください");
	  document.getElementById('marriage_hour').focus();
	  return false;
   }




if(document.getElementById("marriage_minute").value=='')
	{
		alert("挙式時間を正しく入力してください");
		document.getElementById('marriage_minute').focus();
		return false;
	}

   var str5 = document.getElementById("marriage_minute").value;
   if( str5.match( /[^0-9\s]+/ ) ) {
      alert("挙式時間を正しく入力してください");
	  document.getElementById('marriage_minute').focus();
	  return false;
   }



	if(document.getElementById("room_id").value=='')
	{
		alert("必須項目は必ず入力してください。");
		document.getElementById('room_id').focus();
		return false;
	}

	if(document.getElementById("religion").value=='')
	{
		alert("挙式種類を選択してください");
		document.getElementById('religion').focus();
		return false;
	}
	if(document.getElementById("party_room_id").value=='')
	{
		alert("挙式会場を入力してください。");
		document.getElementById('party_room_id').focus();
		return false;
	}
	if(document.getElementById("party_day").value=='')
	{
		alert("披露宴日を正しく入力してください");
		document.getElementById('party_day').focus();
		return false;
	}
	/*if(document.getElementById("party_day_with_time").value=='')
	{
		alert("披露宴時間を正しく入力してください");
		document.getElementById('party_day_with_time').focus();
		return false;
	}
	if(document.getElementById("party_day").value==''&&document.getElementById("party_year").value==''&&document.getElementById("party_month").value=='')
	{
		alert("披露宴日を正しく入力してください");
		document.getElementById('party_year').focus();
		return false;
	}*/
	if(document.getElementById("party_hour").value=='')
	{
		alert("披露宴時間を正しく入力してください");
		document.getElementById('party_hour').focus();
		return false;
	}

   var str6 = document.getElementById("party_hour").value;
   if( str6.match( /[^0-9\s]+/ ) ) {
      alert("披露宴時間を正しく入力してください");
	  document.getElementById('party_hour').focus();
	  return false;
   }



	if(document.getElementById("party_minute").value=='')
	{
		alert("披露宴時間を正しく入力してください");
		document.getElementById('party_minute').focus();
		return false;
	}

   var str7 = document.getElementById("party_minute").value;
   if( str7.match( /[^0-9\s]+/ ) ) {
      alert("披露宴時間を正しく入力してください");
	  document.getElementById('party_minute').focus();
	  return false;
   }




	document.user_form_register.submit();
}
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

function load_party_room(id)
{
	$j.post('user_info.php',{'ajax':'ajax','pid':id},function (data){
		$j("#party_room_id").fadeOut(100);
		$j("#party_room_id").html(data);
		$j("#party_room_id").fadeIn(300);
	});
}
function get_gaiji_value(from,img,gid,gsid)
{
	//alert(img);	alert(gid);	alert(gsid);
  if(img==""){
    alert("外字が正しく選択されていません。");
    return;
  }
	if(from=="man_lastname")
	{
		var man_lastname = $j("#man_lastname").val();
		$j("#male_first_div_id").append("<input type='hidden' name='male_first_gaiji_img[]' value='"+img+"'>");
		$j("#male_first_div_id").append("<input type='hidden' name='male_first_gaiji_gid[]' value='"+gid+"'>");
		$j("#male_first_div_id").append("<input type='hidden' name='male_first_gaiji_gsid[]' value='"+gsid+"'>");

		$j("#male_lastname_img_div_id").append("<img src='../gaiji/upload/img_ans/"+img+"' wight='20' height='20'>");
		$j("#man_lastname").attr("value", man_lastname+"＊");
	}
	if(from=="man_firstname")
	{
		var man_firstname = $j("#man_firstname").val();
		$j("#male_last_div_id").append("<input type='hidden' name='male_last_gaiji_img[]' value='"+img+"'>");
		$j("#male_last_div_id").append("<input type='hidden' name='male_last_gaiji_gid[]' value='"+gid+"'>");
		$j("#male_last_div_id").append("<input type='hidden' name='male_last_gaiji_gsid[]' value='"+gsid+"'>");

		$j("#male_firstname_img_div_id").append("<img src='../gaiji/upload/img_ans/"+img+"' wight='20' height='20'>");
		$j("#man_firstname").attr("value", man_firstname+"＊");
	}
	if(from=="woman_lastname")
	{
		var woman_lastname = $j("#woman_lastname").val();
		$j("#female_first_div_id").append("<input type='hidden' name='female_first_gaiji_img[]' value='"+img+"'>");
		$j("#female_first_div_id").append("<input type='hidden' name='female_first_gaiji_gid[]' value='"+gid+"'>");
		$j("#female_first_div_id").append("<input type='hidden' name='female_first_gaiji_gsid[]' value='"+gsid+"'>");

		$j("#female_lastname_img_div_id").append("<img src='../gaiji/upload/img_ans/"+img+"' wight='20' height='20'>");
		$j("#woman_lastname").attr("value", woman_lastname+"＊");
	}
	if(from=="woman_firstname")
	{
		var woman_firstname = $j("#woman_firstname").val();
		$j("#female_last_div_id").append("<input type='hidden' name='female_last_gaiji_img[]' value='"+img+"'>");
		$j("#female_last_div_id").append("<input type='hidden' name='female_last_gaiji_gid[]' value='"+gid+"'>");
		$j("#female_last_div_id").append("<input type='hidden' name='female_last_gaiji_gsid[]' value='"+gsid+"'>");

		$j("#female_firstname_img_div_id").append("<img src='../gaiji/upload/img_ans/"+img+"' wight='20' height='20'>");
		$j("#woman_firstname").attr("value", woman_firstname+"＊");

	}
}

function change_gaiji_link(action)
{

	if(action == "man_lastname")
		$j("a#man_gaiji_link_id").attr("href", "../gaiji/palette.php?from=man_lastname");
	else if(action == "man_firstname")
		$j("a#man_gaiji_link_id").attr("href", "../gaiji/palette.php?from=man_firstname");
	else if(action == "woman_lastname")
		$j("a#woman_gaiji_link_id").attr("href", "../gaiji/palette.php?from=woman_lastname");
	else if(action == "woman_firstname")
		$j("a#woman_gaiji_link_id").attr("href", "../gaiji/palette.php?from=woman_firstname");
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
    	<h2><div style="width:200px;"> お客様新規登録 </div></h2>
		<h2><div style="width:100px">お客様新規登録</div></h2>

        <form action="insert_user.php" method="post" name="user_form_register">
        <table width="100%" border="0" cellspacing="10" cellpadding="0" style="width:1000px;">
            <tr>
                <td width="7%" align="right" nowrap="nowrap"></td>
                <td width="25%" nowrap="nowrap">
          <div id="male_lastname_img_div_id" style="float:left;width:100px;height:20px;"></div>
					<div id="male_firstname_img_div_id" style="float:left;width:100px;height:20px;"></div>

              	</td>

                <td width="7%" align="right" nowrap="nowrap"></td>
                <td width="65%" nowrap="nowrap">
					<div id="female_lastname_img_div_id" style="float:left;width:100px;height:20px;"></div>
					<div id="female_firstname_img_div_id" style="float:left;width:100px;height:20px;"></div>

                </td>
            </tr>
			<tr>
                <td width="7%" align="right" nowrap="nowrap">新郎氏名：</td>
                <td width="25%" nowrap="nowrap">

                	<input name="man_lastname" type="text" id="man_lastname"  class="input_text" onclick="change_gaiji_link('man_lastname')"  />
                    <input name="man_firstname" type="text" id="man_firstname"  class="input_text" onclick="change_gaiji_link('man_firstname')"  />
様

					<div id="male_last_div_id" style="display:none;"></div>
					<div id="male_first_div_id" style="display:none;"></div>
              	</td>

                <td width="7%" align="right" nowrap="nowrap">新婦氏名：</td>
                <td width="65%" nowrap="nowrap">

                    <input name="woman_lastname" type="text" id="woman_lastname"  class="input_text"  onclick="change_gaiji_link('woman_lastname')"  />
                    <input name="woman_firstname" type="text" id="woman_firstname"  class="input_text"  onclick="change_gaiji_link('woman_firstname')" />
 様


					<div id="female_last_div_id" style="display:none;"></div>
					<div id="female_first_div_id" style="display:none;"></div>
                </td>
            </tr>

            <tr>
                <td width="7%" align="right" nowrap="nowrap">&nbsp;</td>
                <td width="25%" nowrap="nowrap" align="left"><a id="man_gaiji_link_id" onclick="m_win(this.href,'mywindow7',500,500); return false;" href="../gaiji/palette.php">
                  <img src="img/common/btn_gaiji.jpg" width="82" height="22" /></a>
              	</td>

                <td width="7%" align="right" nowrap="nowrap">&nbsp;</td>
                <td width="65%" nowrap="nowrap" align="left"><a id="woman_gaiji_link_id" onclick="m_win(this.href,'mywindow7',500,500); return false;" href="../gaiji/palette.php">
                 &nbsp;<img src="img/common/btn_gaiji.jpg" width="82" height="22" /></a>
                </td>
            </tr>


            <tr>
                <td align="right" nowrap="nowrap">ふりがな：</td>
                <td nowrap="nowrap">


                	<input name="man_furi_lastname" type="text" id="man_furi_lastname"  class="input_text" />
                    <input name="man_furi_firstname" type="text" id="man_furi_firstname"  class="input_text"  />
様

                </td>

                <td align="right" nowrap="nowrap">ふりがな：</td>
                <td nowrap="nowrap">


                	<input name="woman_furi_lastname" type="text" id="woman_furi_lastname"  class="input_text"  />
                    <input name="woman_furi_firstname" type="text" id="woman_furi_firstname"  class="input_text"  />
様
                </td>


            </tr>
            <tr>
            	<td align="right" nowrap="nowrap">挙式日：</td>
            	<td nowrap="nowrap">
                	<input name="marriage_day" type="text" id="marriage_day" size="15" readonly="readonly" style="background: url('img/common/icon_cal.gif') no-repeat scroll right center rgb(255, 255, 255); padding-right: 20px;" class="datepicker"/>

					<!--<input type="text" style="width:30px;" maxlength="4" name="marriage_year" id="marriage_year" value="">/
					<input type="text" style="width:17px;" maxlength="2" name="marriage_month" id="marriage_month" value="">/
					<input type="text" style="width:17px;" maxlength="2" name="marriage_day" id="marriage_day" value=""> yyyy/mm/dd-->
                <!--&nbsp;<a href="javascript:void(0)" onclick="document.getElementById('marriage_day').value='';">クリア </a>-->

                </td>
            	<td align="right" nowrap="nowrap">挙式時間：</td>
            	<td nowrap="nowrap">
                	<!--<input name="marriage_day_with_time" type="text" id="marriage_day_with_time" size="10" readonly="readonly" style="background: url('img/common/icon_cal.gif') no-repeat scroll right center rgb(255, 255, 255); padding-right: 20px;" class="timepicker"/>-->
					<input type="text" style="width:17px;" maxlength="2" name="marriage_hour" id="marriage_hour" value=""> :
				<input type="text" style="width:17px;" maxlength="2" name="marriage_minute" id="marriage_minute" value=""> (24時間表記)
              <!--  &nbsp;<a href="javascript:void(0)" onclick="document.getElementById('marriage_day_with_time').value='';">クリア </a>-->
                </td>
            </tr>
            <tr>

            	<td align="right" nowrap="nowrap">挙式種類：</td>
            	<td nowrap="nowrap">
                	<select name="religion" id="religion" class="select">
                        <option value=""  <?php if($_SESSION['regs'][religion]=='') {?> selected="selected" <?php } ?>>選択してください</option>
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
					<td align="right" nowrap="nowrap">挙式会場：</td>
            	<td nowrap="nowrap">
                	<input name="party_room_id" type="text" id="party_room_id"  class="input_text"  />
                </td>
            </tr>
            <tr>
            	<td align="right" nowrap="nowrap">披露宴日：</td>
            	<td nowrap="nowrap">
                	<input name="party_day" type="text" id="party_day" size="15" readonly="readonly" style="background: url('img/common/icon_cal.gif') no-repeat scroll right center rgb(255, 255, 255); padding-right: 20px;" class="datepicker"/>
                <!--&nbsp;<a href="javascript:void(0)" onclick="document.getElementById('party_day').value='';">クリア </a>-->
				<!--<input type="text" style="width:30px;" maxlength="4" name="party_year" id="party_year" value="">/
					<input type="text" style="width:17px;" maxlength="2" name="party_month" id="party_month" value="">/
					<input type="text" style="width:17px;" maxlength="2" name="party_day" id="party_day" value=""> yyyy/mm/dd-->
                </td>
            	<td align="right" nowrap="nowrap">披露宴時間：</td>
            	<td nowrap="nowrap">
              <!--  <input name="party_day_with_time" type="text" id="party_day_with_time" size="10" readonly="readonly" style="background: url('img/common/icon_cal.gif') no-repeat scroll right center rgb(255, 255, 255); padding-right: 20px;" class="timepicker"/>-->
			  <input type="text" style="width:17px;" maxlength="2" name="party_hour" id="party_hour" value=""> :
				<input type="text" style="width:17px;" maxlength="2" name="party_minute" id="party_minute" value=""> (24時間表記)
               <!-- &nbsp;<a href="javascript:void(0)" onclick="document.getElementById('party_day_with_time').value='';">クリア </a>-->
            　
            披露宴会場：

                <select name="room_id" id="room_id" class="select" >


                    <?php

                        if($rooms)
                        {
                            foreach($rooms as $room)
                            {

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
                	<a href="javascript:void(0);" onclick="valid_user();"><img src="img/common/btn_regist.jpg" border="0" width="82" height="22" /></a>
                </td>
            </tr>
        </table>
        </form>
<?php
if($_SESSION['user_type'] == 111)
{
?>
<?php
if($noview==1)
{
?>
		<br />
        <br />
        <h2>検索・編集・削除</h2>

        <p class="txt3">
        	<form action="users.php" method="post">
       		  　
       		  <input name="chk_man_firstname" value="1" type="checkbox" />
       		  新郎姓：<input name="man_firstname" type="text" id="新郎姓" class="input_text" /> &nbsp; &nbsp;
              <input name="chk_woman_firstname" type="checkbox" value="1" id="checkbox2" />
              新婦姓：
              <input name="woman_firstname" type="text" id="新婦姓"  class="input_text" /> &nbsp; &nbsp;
              <input type="checkbox" name="chk_marriage_day" value="1" id="chk_marriage_day" />
              挙式日：
        <!--<input name="marriage_day" type="text" id="marriage_day" size="10" />-->
                <input name="marriage_day" type="text" id="marriage_day" size="10" readonly="readonly" style="background: url('img/common/icon_cal.gif') no-repeat scroll right center rgb(255, 255, 255); padding-right: 20px;" class="datepicker"/>
               <!-- &nbsp;<a href="javascript:void(0)" onclick="document.getElementById('marriage_day').value='';">クリア </a>-->
                <br />
                <input type="hidden" name="search_user" value="1" />
                <input type="image" name="search" src="img/common/bt_search.jpg" width="62" height="22" />
            </form>
        </p>
        <br />
<?php }?>
<?php
if($noview==1)
{
?>	        <div class="bottom_line_box">
        	<p class="txt3"><font color="#2052A3"><strong>席次表設定</strong></font></p>
        </div>
        <div>
        	<?php if($_GET['view']=="before"){?>
			<a href="users.php"><font color="#2052A3"><strong>本日以降のお客様一覧</strong></font></a>

			<?php }else{?>
			<a href="users.php?view=before"><font color="#2052A3"><strong>過去のお客様一覧</strong></font></a>
			<?php }?>
        </div>



        <div class="box_table">

            <div class="page_next"><?=$pageination?></div>
            <?php if($_GET['view']=="before"){?>
			<div class="box4">
                <table border="0" align="center" cellpadding="1" cellspacing="1">
                    <tr align="center">
                    	<td>詳細</td>
                        <td width="120">挙式日<span class="txt1"><a href="users.php?view=before&order_by=mdate&asc=true">▲</a>
						<a href="users.php?view=before&order_by=mdate&asc=false">▼</a></span></td>
                        <td>新郎氏名<span class="txt1"><a href="users.php?view=before&order_by=woman_last_name&asc=true">▲</a>
                        	<a href="users.php?view=before&order_by=woman_last_name&asc=false">▼</a></span>
                         </td>
                        <td>新婦氏名<span class="txt1"><a href="users.php?view=before&order_by=man_last_name&asc=true">▲</a>
                        	<a href="users.php?view=before&order_by=man_last_name&asc=false">▼</a></span>
                        </td>
                        <td>head</td>
                        <td>スタッフ</td>
                        <td>メッセージ</td>
                        <td>head</td>
                        <td width="120">最終アクセス</td>
                        <td>席次表</td>
                        <td>引出物</td>
                        <td>削除</td>
                    </tr>
                </table>
            </div>

			<?php }else{?>
			<div class="box4">
                <table border="0" align="center" cellpadding="1" cellspacing="1">
                    <tr align="center">
                    	<td>詳細</td>
                        <td width="120">挙式日<span class="txt1"><a href="users.php?order_by=mdate&asc=true">▲</a> <a href="users.php?order_by=mdate&asc=false">▼</a></span></td>
                        <td>新郎氏名<span class="txt1"><a href="users.php?order_by=woman_last_name&asc=true">▲</a>
                        	<a href="users.php?order_by=woman_last_name&asc=false">▼</a></span>
                         </td>
                        <td>新婦氏名<span class="txt1"><a href="users.php?order_by=man_last_name&asc=true">▲</a>
                        	<a href="users.php?order_by=man_last_name&asc=false">▼</a></span>
                        </td>
                        <td>head</td>
                        <td>スタッフ</td>
                        <td>メッセージ</td>
                        <td>head</td>
                        <td width="120">最終アクセス</td>
                        <td>席次表</td>
                        <td>引出物</td>

                        <td>削除</td>
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

				$man_respect = $obj->GetSingleData(" spssp_respect", " title", " id=".(int)$data_rows['man_respect_id']);
				$woman_respect = $obj->GetSingleData(" spssp_respect", " title", " id=".(int)$data_rows['woman_respect_id']);

				$staff_name = $obj->GetSingleData("spssp_admin","name"," id=".$row['stuff_id']);

				if($i%2==0)
				{
					$class = 'box5';
				}
				else
				{
					$class = 'box6';
				}
				$last_login = $obj->GetSingleData("spssp_user_log","max(login_time)"," id=".$row['id']);

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
						$msg_opt = "<a href='message_user.php?user_id=".$row['id']."'><img src='img/common/btn_midoku.gif' border = '0'></a>";
					}
					else
					{
						$msg_opt = "<a href='message_user.php?user_id=".$row['id']."'><img src='img/common/btn_zumi.gif' border = '0'></a>";
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
						$plan_link = "<a href='make_plan.php?plan_id=".$plan_row['id']."&user_id=".$row['id']."'><img src='img/common/btn_syori.gif' height='17' width='42' border='0' /></a>";

					}
					else
					{
						if(!empty($user_guests))
						{
							$plan_link = "<a href='make_plan.php?plan_id=".$plan_row['id']."&user_id=".$row['id']."'><img src='img/common/btn_syori.gif' height='17' width='42' border='0' /></a>";
						}
						else
						{
							$plan_link = "<a href='javascript:void(0);' onclick='guestCheck();'><img src='img/common/btn_kousei.gif' height='17' width='42' border='0' /></a>";
						}
					}

					$layout_link = "<a href='set_table_layout.php?plan_id=".$plan_row['id']."&user_id=".(int)$row['id']."'><img src='img/common/btn_taku_edit.gif' boredr='0' width='52' height='17'> </a>";
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
            <div class="<?=$class?>">
                <table border="0" align="center" cellpadding="1" cellspacing="1">
                    <tr align="center">

                    	<td><a href="user_info.php?user_id=<?=$row['id']?>"><img src="img/common/customer_info.gif" /></a></td>
                        <td width="120"><?=$obj->japanyDateFormate($row['party_day'])?></td>
                        <td><?=$row['man_lastname']." ".$row['man_firstname'].' 様';?></td>
                        <td><?=$row['woman_lastname']." ".$row['woman_firstname'].' 様';?></td>
                        <td class="txt1">
                        	<a href="user_dashboard.php?user_id=<?=$row['id']?>" target="_blank"><img src="img/common/customer_view.gif" /></a>
                        </td>
                        <td> <?=$staff_name?></td>
                        <td> <?=$msg_opt;?> </td>
                        <td>
                        	<?=$layout_link?>
                        </td>
                        <td width="120">
						<?php
						if($last_login==""){

						}else{
						echo $obj->japanyDateFormate($last_login);
						//echo date("Y-m-d", mktime($last_login));
						}

						?>
						<? //date("Y-m-d", mktime($last_login));?>
                        </td>
                        <td>
                        	<?php
                            	if($var == 1)
								{
									echo $plan_link ;
								}
								else
								{
									if(isset($conf_plan_row) && !empty($conf_plan_row))
									{
										echo "<a href='view_plan.php?plan_id=".$plan_row['id']."&user_id=".$row['id']."'><img src='img/common/btn_syori.gif' height='17' width='42' border='0' /></a>";
									}
									else
									{
										echo "<a href='javascript:void(0)' onclick='alert_staff_plan();'><img src='img/common/btn_syori.gif' height='17' width='42' border='0' /></a>";
									}
								}
							?>
                        </td>
                        <td><a href="gift_user.php?user_id=<?=$row['id'];?>"><img src="img/common/btn_kentou.gif" width="42" height="17" /></a></td>
   <?php
	if($_SESSION['user_type'] == 111  || $_SESSION['user_type'] == 333)
	{
?>
                        <td>
                        	<a href="javascript:void(0);" onclick="<?=$delete_onclick;?>" >
                        		<img src="img/common/btn_deleate.gif" width="42" height="17" />
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
