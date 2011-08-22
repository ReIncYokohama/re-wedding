<?php
	include_once("inc/dbcon.inc.php");	
	include_once("inc/checklogin.inc.php");	
	require_once("inc/class.dbo.php");
	$obj = new DBO();
	
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
	$rooms = $obj->GetAllRow("spssp_room");
	$staff_name = $obj->GetSingleData("spssp_admin", "name"," id=".$user_row['stuff_id']);
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
		$j('#password_msg').html("<font style='color:red;'>パスワードは英数字6文字以上にしてください</font>");
	}
	else
	{
		$j('#password_msg').html("");	
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
	var row_num = $j("#row_number").val();
	var col_num = $j("#column_number").val();
	var seat_num = $j("#seat_number").val();
	var name = $j("#name").val();
	
	var max_rows=$j("#max_rows").val();
	var max_columns=$j("#max_columns").val();
	var max_seats=$j("#max_seats").val();
	
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
		alert("　数字のみの入力はできません。");
		$j("#column_number").focus();		
		return false;
	}
	if(parseInt(col_num) > parseInt(max_columns))
	{
		alert("Maximum allowed Column number is: "+max_columns);
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
		alert("　数字のみの入力はできません。");
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

function load_party_room(id)
{
	$j.post('user_info.php',{'ajax':'ajax','pid':id},function (data){
		$j("#party_room_id").fadeOut(100);
		$j("#party_room_id").html(data);
		$j("#party_room_id").fadeIn(300);
	});
}
function checkGroupForm(x)
{	//alert(x);
	
	for(var y=0;y<x;y++)
	{
		//alert(y);
		if($j("#name"+y).val()=="")
		{
			alert("空にすることは");
			var error =1;
		}
	}
	
	if(error!=1)
	{
		document.editUserGiftGroupsForm.submit();	
	}
}
function checkGiftForm(x)
{	//alert(x);
	
	for(var y=0;y<x;y++)
	{
		if($j("#item"+y).val()=="")
		{
			alert("空にすることは");
			var error =1;
		}
	}
	if(error!=1)
	{
		document.editUserGiftItemsForm.submit();	
	}
}
function checkMenuGroupForm(x)
{	//alert(x);
	
	for(var y=0;y<x;y++)
	{
		if($j("#menu"+y).val()=="")
		{
			alert("空にすることは");
			var error =1;
		}
	}
	if(error!=1)
	{
		document.editUserMenuGroupsForm.submit();	
	}
}
</script>

<div id="topnavi">
    <?php
include("inc/main_dbcon.inc.php");
$hcode=$HOTELID;
$hotel_name = $obj->GetSingleData(" super_spssp_hotel ", " hotel_name ", " hotel_code=".$hcode);
?>
<h1><?=$hotel_name?>　管理</h1>
<?
include("inc/return_dbcon.inc.php");
?>
 
    <div id="top_btn"> 
        <a href="logout.php"><img src="img/common/btn_logout.jpg" alt="ログアウト" width="102" height="19" /></a>　
        <a href="#"><img src="img/common/btn_help.jpg" alt="ヘルプ" width="82" height="19" /></a>
    </div>
</div>
<div id="container">
    <div id="contents"> 
    <div style="font-size:16; font-weight:bold;">
            <?=$user_row['man_firstname']?> 様・ <?=$user_row['woman_firstname']?> 様
    </div>
        <h2>       	
            	<a href="manage.php">TOP</a> &raquo; <a href="users.php">お客様一覧</a> &raquo; お客様挙式情報
        </h2>
		<div>
        	<div class="navi">
            	<img src="img/common/navi01_on.jpg" width="148" height="22" />
            </div>
        	<div class="navi">
            	<a href="message_admin.php?user_id=<?=(int)$_GET['user_id']?>"><img src="img/common/navi02.jpg" width="96" height="22" class="on" /></a>
            </div>
        	<div class="navi">
            	<a href="user_dashboard.php?user_id=<?=$user_id?>" target="_blank">
            		<img src="img/common/navi03.jpg" width="126" height="22" class="on" />
                </a>
            </div>
        	<!--<div class="navi"><a href="guest_gift.php?user_id=<?=$user_id?>"><img src="img/common/navi04.jpg" width="150" height="22" class="on" /></a></div>-->
        	<div class="navi"><a href="customers_date_dl.php?user_id=<?=$user_id?>"><img src="img/common/navi05.jpg" width="116" height="22" class="on" /></a></div>
        	<div style="clear:both;"></div>
        </div>
        <br />
        <div class="bottom_line_box">
        	<p class="txt3"><font color="#2052A3"><strong>お客様挙式情報</strong>　<font color="red">*</font>項目は必須です。</font></p>
        </div>
		<?php
		//echo "<pre>";
		//print_r($user_row);
		?>
        <form action="insert_user.php?user_id=<?=$user_id;?>" method="post" name="user_form_register">
        <table width="100%" border="0" cellspacing="10" cellpadding="0">
            <tr>
                <td align="right" valign="top" nowrap="nowrap">挙式日<font color="#2052A3"><font color="red">*</font></font>：</td>
                <td nowrap="nowrap">
                	<input name="marriage_day" type="text" id="marriage_day" value="<?=$user_row['marriage_day']?>" id="marriage_day" size="10" readonly="readonly" style="background: url('img/common/icon_cal.gif') no-repeat scroll right center rgb(255, 255, 255); padding-right: 20px;" class="datepicker" />
                	<!--&nbsp;<a href="javascript:void(0)" onclick="document.getElementById('marriage_day').value='';">クリア </a>-->
                </td>
                <td width="7%" align="right" nowrap="nowrap">挙式時間<font color="#2052A3"><font color="red">*</font></font>：</td>
                <td width="67%" nowrap="nowrap"><input name="marriage_day_with_time" type="text" id="marriage_day_with_time" value="<?=date("H:i",strtotime($user_row['marriage_day_with_time']))?>" size="10" readonly="readonly" style="background: url('img/common/icon_cal.gif') no-repeat scroll right center rgb(255, 255, 255); padding-right: 20px;" class="timepicker"/>
                <!--&nbsp;<a href="javascript:void(0)" onclick="document.getElementById('marriage_day_with_time').value='';">クリア </a>-->
                </td>
            </tr>
            <tr>
                <td width="8%" align="right" valign="top" nowrap="nowrap">新郎氏名<font color="#2052A3"><font color="red">*</font></font>：</td>
                <td width="92%" nowrap="nowrap" colspan="3">
                    <input name="man_firstname" type="text" id="man_firstname" value="<?=$user_row['man_firstname']?>" size="10" /> 
                    <input name="man_lastname" type="text" id="man_lastname" value="<?=$user_row['man_lastname']?>" size="10" />
                	様　<img src="img/common/btn_gaiji.jpg" width="82" height="22" />
                </td>
               
            </tr>
            <tr>
            	<td align="right" valign="top" nowrap="nowrap">フリガナ<font color="#2052A3"><font color="red">*</font></font>：</td>
           		<td nowrap="nowrap"  colspan="3">
            		<input name="man_furi_firstname" type="text" id="man_furi_firstname" value="<?=$user_row['man_furi_firstname']?>" size="10" />
                   	<input name="man_furi_lastname" type="text" id="man_furi_lastname" value="<?=$user_row['man_furi_lastname']?>" size="10" /> 様
                </td>
            </tr>
            <tr>
            	<td align="right" valign="top" nowrap="nowrap">新婦氏名<font color="#2052A3"><font color="red">*</font></font>：</td>
            	<td nowrap="nowrap"  colspan="3">
                	<input name="woman_firstname" type="text" id="woman_firstname" value="<?=$user_row['woman_firstname']?>" size="10" />
                   	<input name="woman_lastname" type="text" id="woman_lastname" value="<?=$user_row['woman_lastname']?>" size="10" />
                    様　<img src="img/common/btn_gaiji.jpg" width="82" height="22" />
                </td>
            	
            </tr>
            <tr>
            	<td align="right" valign="top" nowrap="nowrap">フリガナ<font color="#2052A3"><font color="red">*</font></font>：</td>
            	<td nowrap="nowrap"  colspan="3">
            		<input name="woman_furi_firstname" type="text" id="woman_furi_firstname" value="<?=$user_row['woman_furi_firstname']?>" size="10" />
                   	<input name="woman_furi_lastname" type="text" id="woman_furi_lastname" value="<?=$user_row['woman_furi_lastname']?>" size="10" /> 様
                </td>
            </tr>
            <tr>
                
                <td align="right" nowrap="nowrap">挙式種類<font color="#2052A3"><font color="red">*</font></font>：</td>
                <td nowrap="nowrap">
                	<select name="religion" id="religion" ><!--onchange="load_party_room(this.value);"-->
                    
                        <option value=""  <?php if($user_row[religion]=='') {?> selected="selected" <?php } ?>>選択してください</option>
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
				<td align="right" valign="top" nowrap="nowrap">挙式会場<font color="#2052A3"><font color="red">*</font></font>：</td>
                <td nowrap="nowrap">
                	<?php
						$party_rooms_name = $obj->GetSingleData("spssp_party_room","name"," id=".$user_row['party_room_id']);
					
					?>
					<input name="party_room_id" type="text" id="party_room_id"  class="input_text" value="<?=$party_rooms_name?>" />
					<!--<select name="party_room_id" id="party_room_id" style="width:106px;">
                    	

                    <?php
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
                <td align="right" valign="top" nowrap="nowrap">披露宴日<font color="#2052A3"><font color="red">*</font></font>：</td>
                <td nowrap="nowrap">
                	<input name="party_day" type="text" id="party_day" value="<?=$user_row['party_day']?>" size="10" readonly="readonly" style="background: url('img/common/icon_cal.gif') no-repeat scroll right center rgb(255, 255, 255); padding-right: 20px;" class="datepicker"/>
                <!--&nbsp;<a href="javascript:void(0)" onclick="document.getElementById('party_day').value='';">クリア </a>-->
                </td>
                <td align="right" nowrap="nowrap">披露宴時間<font color="#2052A3"><font color="red">*</font></font>：</td>
                <td nowrap="nowrap"><input name="party_day_with_time" type="text" id="party_day_with_time" value="<?=date("H:i", strtotime($user_row['party_day_with_time']))?>" size="10" readonly="readonly" style="width:86px;background: url('img/common/icon_cal.gif') no-repeat scroll right center rgb(255, 255, 255); padding-right: 20px;" class="timepicker"/>
                <!--&nbsp;<a href="javascript:void(0)" onclick="document.getElementById('party_day_with_time').value='';">クリア </a>-->
                </td>
            </tr>
            <tr>
                <td align="right" valign="top" nowrap="nowrap">披露宴会場<font color="#2052A3"><font color="red">*</font></font>：</td>
                <td nowrap="nowrap">
                	<select name="room_id" id="room_id">

                    <?php
                        if($rooms)
                        {
                            foreach($rooms as $room)
                            {
                               
								
								if($room['id']==$user_row['room_id'])
								echo "<option value ='".$room['id']."' selected> ".$room['name']."</option>";
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
                <td align="right" valign="top" nowrap="nowrap">メールアドレス<font color="red">*</font>：</td>
                <td colspan="3" nowrap="nowrap"><input name="mail" type="text" id="mail" size="30" value="<?=$user_row['mail']?>" />
				
				</td>
            </tr>
			 <tr>
                <td align="right" valign="top" nowrap="nowrap">メールアドレス確認用<font color="red">*</font>：</td>
                <td colspan="3" nowrap="nowrap"><input name="commail" type="text" id="con_mail" size="30" value="<?=$user_row['mail']?>" />
				</td>
            </tr>
            <tr>
                <td align="right" valign="top" nowrap="nowrap">ログインID：</td>
                <td colspan="3" nowrap="nowrap"><input name="user_id" type="text" id="user_id" value="<?=$user_row['user_id']?>" size="10" /></td>
            </tr>
            <tr>
                <td align="right" valign="top" nowrap="nowrap">お客様パスワード：</td>
                <td colspan="3" nowrap="nowrap"><input name="password" type="password" id="password" value="<?=$user_row['password']?>" size="10"  onblur="checkvalidity()"/>
				<span id="password_msg">パスワードは英数字6文字以上にしてください</span>
				</td>
            </tr>
            <tr>
                <td align="right" valign="top" nowrap="nowrap">担当：</td>
                <td colspan="3" nowrap="nowrap"><input name="stuff_id" type="text" id="stuff_id" value="<?=$staff_name?>" size="10" /></td>
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
            	<td colspan="4" nowrap="nowrap">
                	<a href="user_log.php?user_id=<?=(int)$_GET['user_id']?>"><img src="img/common/btn_access.jpg" width="173" height="23" /></a>　
                    <a href="#"><img src="img/common/btn_data.jpg" width="173" height="23" /></a>
                </td>
            </tr>
        </table>
        </form>
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
        <form action="insert_user_info_plan.php?user_id=<?=(int)$_GET['user_id']?>" method="post" name="user_info_plan">
        <input type="hidden" id="max_rows" value="<?=$room_row['max_rows']?>" />
        <input type="hidden" id="max_columns" value="<?=$room_row['max_columns']?>" />
        <input type="hidden" id="max_seats" value="<?=$room_row['max_seats']?>" />
        
        <table width="100%" border="0" cellspacing="10" cellpadding="0">
            <tr>
                <td width="7%" align="right" nowrap="nowrap">披露宴会場名：</td>
                <td width="15%" nowrap="nowrap">
                	<label for="新郎 姓3"></label>  <input  type="text" id="room_name" value="<?=$room_name?>" size="15" />
                </td>
                <td width="10%" align="right" nowrap="nowrap">
					プラン名:
						</td>
                <td width="68%" nowrap="nowrap">
                
                   
                            	
                    	
                        <input type="text" name="name" value="<?=$user_plan_row['name']?>" id="name" />
                      
                    
                </td>
            </tr>
            <tr>
                <td align="right" nowrap="nowrap">最大卓数：</td>
                <td nowrap="nowrap">
                	横 <input name="row_number" type="text" id="row_number" value="<?php if($user_plan_row['row_number']){echo $user_plan_row['row_number'];}else{echo $room_row['max_rows'];}?>" size="1" />
                	列×縦 <input name="column_number" type="text" id="column_number" value="<?php if($user_plan_row['column_number']){echo $user_plan_row['column_number'];}else{echo $room_row['max_columns'];}?>" size="1" />  列 
                </td>
                <td align="right" nowrap="nowrap">一卓人数：</td>
                <td nowrap="nowrap"><input name="seat_number" type="text" id="seat_number" value="<?php if($user_plan_row['seat_number']){echo $user_plan_row['seat_number'];}else{echo $room_row['max_seats'];}?>" size="1" /></td>
            </tr>
            <tr>
                <td align="right" nowrap="nowrap">卓名変更：</td>
                <td nowrap="nowrap">
                	
                	<input name="rename_table" type="radio" id="radio1" value="1"  <?php 	if($user_plan_row['rename_table'] == 1){echo "checked='checked'";}?> />   可
                	<input type="radio" name="rename_table" id="radio0" value="0" <?php 	if($user_plan_row['rename_table'] == 0){echo "checked='checked'";}?> /> 不可　
                    <!--<img src="img/common/btn_taku_edit.jpg" width="82" height="22" />-->
                </td>
                <td align="right" nowrap="nowrap">校了予定日：</td>
                <td nowrap="nowrap">
                	<input name="confirm_date" type="text" id="confirm_date" value="<?=$user_plan_row['confirm_date']?>" size="10" readonly="readonly" style="background: url('img/common/icon_cal.gif') no-repeat scroll right center rgb(255, 255, 255); padding-right: 20px;" class="datepicker"/>
                <!--&nbsp;<a href="javascript:void(0)" onclick="document.getElementById('confirm_date').value='';">クリア </a>-->
                
                </td>
            </tr>
            <tr>
                <td align="right" nowrap="nowrap">校了日設定：</td>
                <td nowrap="nowrap"><input  type="text" id="final_proof" size="1" name="final_proof" value="<?=$user_plan_row['final_proof']?>" maxlength="2" /></td>
                <td align="right" nowrap="nowrap">商品名：</td>
                <td nowrap="nowrap"><input name="product_name" type="text" id="product_name" value="<?=$user_plan_row['product_name']?>" size="10" /></td>
            </tr>
            <tr>
                <td align="right" nowrap="nowrap">商品区分：</td>
                <td nowrap="nowrap">
                	<select name="dowload_options" id="dowload_options">
                    	<option value="1" <?php if($user_plan_row['dowload_options'] == 1) echo "selected='selected'";?>>1.席次表</option>
                        <option value="2" <?php if($user_plan_row['dowload_options'] == 2) echo "selected='selected'";?>>2.席札</option>
                        <option value="3" <?php if($user_plan_row['dowload_options'] == 3) echo "selected='selected'";?>>3. 3項</option>
                    </select>
                	
                </td>
                <td align="right" nowrap="nowrap">サイズ：</td>
                <td nowrap="nowrap"><input name="print_size" type="text" id="print_size" value="<?=$user_plan_row['print_size']?>" size="1" /></td>
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
                                            
                   	$row_width = $plan_row['column_number'] *45;
                    echo "<div class='plans' id='plan_".$plan_row['id']."' style='width:".$row_width."px;margin:0 auto;'>";	
					echo "<p style='text-align:center'><img src='img/sakiji_icon/icon_takasago.gif' width='102' height='22' /></p>";				
                   $sqltrace = "select distinct row_order from spssp_table_layout where default_plan_id= ".(int)$plan['id'];
					$tblrows = $obj->getRowsByQuery($sqltrace);
								
					foreach($tblrows as $tblrow)
					{
						$ralign = $obj->GetSingleData("spssp_table_layout", "row_align"," row_order=".$tblrow['row_order']." and default_plan_id=".(int)$plan_row['id']." limit 1");                    
						if($ralign == 0)
						{
							$num_none = $obj->GetSingleData("spssp_table_layout", "count(*) "," display=0 and row_order=".$tblrow['row_order']." and default_plan_id=".(int)$plan_row['id']." limit 1");
							
							if($num_none > 0)
							{
								$width = $row_width - ($num_none*41);
								$styles = "width:".$width."px;margin: 0 auto;";
								
							}
							else
							{
								$styles = "";
							}
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
					echo "<p style='text-align:center'><img src='img/sakiji_icon/icon_takasago.gif' width='102' height='22' /></p>";				
                    $tblrows = $obj->getRowsByQuery("select distinct row_order from spssp_table_layout where user_id= ".(int)$user_id);
									
					foreach($tblrows as $tblrow)
					{
						$ralign = $obj->GetSingleData("spssp_table_layout", "row_align"," row_order=".$tblrow['row_order']." and user_id=".(int)$user_id." limit 1");                    
						if($ralign == 0)
						{
							$num_none = $obj->GetSingleData("spssp_table_layout", "count(*) "," display=0 and row_order=".$tblrow['row_order']." and user_id=".$user_id." limit 1");
							
							if($num_none > 0)
							{
								$width = $row_width - ($num_none*41);
								$styles = "width:".$width."px;margin: 0 auto;";
								
							}
							else
							{
								$styles = "";
							}
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
							
							echo "<div class='tables' style='".$disp."'><p>".$tblname."</p></div>";
						}
						echo "</div></div>";
					}
						
					echo "</div>";
                    

            }
            ?>
				</td>
              </tr>
            </table>
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
        <br />
        <div class="bottom_line_box">
        	<p class="txt3"><font color="#2052A3"><strong>引出物設定</strong></font></p>
            <?php
            	$gift_groups = $obj->GetAllRowsByCondition("spssp_gift_group","user_id=".(int)$_GET['user_id']." order by id ASC");
				$gifts = $obj->GetAllRowsByCondition("spssp_gift","user_id=".(int)$_GET['user_id']." order by id ASC")
			?>
        </div>
        <div style="width:100%; float:left">
        	<div style="width:32%; float:left; ">
            	<div style="width:30%; float:left; ">
                	<p> 引出物商品： <p>
                </div>
                <div style="width:69%; float:left;">
                	
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
                	<p>
<form action="user_info.php?user_id=<?=$user_id?>" method="post"  name="editUserGiftItemsForm">
	   <input type="hidden" name="editUserGiftItemsUpdate" value="editUserGiftItemsUpdate">
	   <?php
	  	$yy = 1;
		foreach($gifts as $gift_name)
		{
		echo "<input type='text' id='item".$yy."' name='name".$yy."' value='".$gift_name['name']."' size='13'>&nbsp;&nbsp;&nbsp;";
		
		echo "<input type='hidden' name='fieldId".$yy."' value='".$gift_name['id']."'>";
		$yy++;
		}
	  
	  ?>
	  
	  <br /><br />
	  <div>
	  <input type="button" name="editUserGiftItemsUpdateButton" value="保存" onclick="checkGiftForm(<?=$yy?>)"></div><br />
	  </form>
<!--GIFT ITEM BOX END-->
                      </p>
                </div>
            </div>
            <div style="width:67%; float:left; ">
       
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
                	締切日設定： &nbsp;<?=$gift_criteria['order_deadline']?> &nbsp;日前
                </p><br />
				<p>
                	<?php
				$day = strftime('%d',strtotime($user_row['marriage_day']));
				$month = strftime('%m',strtotime($user_row['marriage_day']));
				$year = strftime('%Y',strtotime($user_row['marriage_day']));
				$lastmonth = mktime(0, 0, 0, $month, $day-7, $year);
				$dateBeforeparty = date("Y-m-d",$lastmonth);
					?>
					締切予定日： &nbsp; <input style="background:#EBEBE4;border:1px solid gray;padding:2px;" type="text" id="textfield15" readonly="readonly" value="<?=$obj->japanyDateFormate($dateBeforeparty)?>" size="25" />
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
		   
		   </form>
					
			<?php	}
			?>
            
       
        <br />
        <br />
        <br />
    
    </div>
</div>
<?php
	include_once('inc/left_nav.inc.php');
	include_once("inc/new.footer.inc.php");
?>
