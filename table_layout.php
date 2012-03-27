<?php
include_once("admin/inc/class_information.dbo.php");
include_once("inc/checklogin.inc.php");
include_once("admin/inc/class_data.dbo.php");
include_once("fuel/load_classes.php");

$obj = new DBO();
$objInfo = new InformationClass();
$get = $obj->protectXSS($_GET);
$user_id = (int)$_SESSION['userid'];
$tab_table_layout = true;
$TITLE = "テーブルの配置 - ウエディングプラス";
include_once("inc/new.header.inc.php");

$obj = new DataClass();
$table_data = $obj->get_table_data_detail($user_id);  

$plan = Model_Plan::find_one_by_user_id($user_id);
$plan_info = $plan->to_array();

$editable = $plan->editable();

if($_POST['user_layout_title']="user_layout_title")
	{
		unset($_POST['user_layout_title']);
		unset($_POST['submit']);
		$obj->UpdateData("spssp_plan",$_POST," user_id=".$user_id);
	}

if($_POST['ajax']=="ajax")
	{
		unset($_POST['ajax']);
		$obj->UpdateData("spssp_plan",$_POST," user_id=".$user_id);
		exit;
	}

$user = Model_User::find_one_by_id($user_id);
$user_data = $plan->to_array();

$roomName = $user->get_room_name();

?>

<link href="css/choose_plan.css" rel="stylesheet" type="text/css" />

<script>
$(function(){

$("ul#menu li").removeClass();
$("ul#menu li:eq(1)").addClass("active");

$(".rows").filter(":first").css("border-top","1px solid #666666");

var msg_html=$("#msg_rpt").html();

	if(msg_html!='')
	{
		//$("#msg_rpt").fadeOut(5000);
	}

});
function checkGuest(tid,cid)
{
	$.post('ajax/check_plan_guest.php',{'tid':tid}, function(data){
		if(parseInt(data) > 0)
		{
			$("#"+cid).attr('checked','checked');
			alert('There are Guests on this Table. Please remove them first.');
		}
	});
}
//FAHIM EDIT 11/07/30 FULL NEW FUNCTION
function validForm(num)
{
	var layoutname_ajax  = $("#layoutname_ajax").val();
	var table_array=new Array();
	var sendObject = {};
	for(var loop=1;loop<=num;loop++)
	  {
	    var tId="tableId_"+loop;
	    var table_name=	$("#"+tId).val();
	    var valueid="hiddenid_"+loop;
	    var table_id=	$("#"+valueid).val();
	    sendObject["name_"+loop] = table_name;
	    sendObject["id_"+loop] = table_id;
	  }
	
	$.post('table_layout.php',{'layoutname':layoutname_ajax,'ajax':"ajax"}, function(data){
			if (layoutname_ajax=="" || !isset(layoutname_ajax)) layoutname_ajax = "　　　";
			$("#user_layoutname").html(layoutname_ajax);
			$("#default_layout_title").html(layoutname_ajax);
			$("#img_default_layout_title").val(layoutname_ajax);
		});
	sendObject["total_table"] = num;
	
	$.post('ajax/plan_table_name_update_all.php',sendObject, function(data){
      	alert("テーブル名が保存されました");
	    if (timeOutNow==true) location.href = "logout.php";
	    else 				  location.href = "table_layout.php";
	});
  
}

function user_layout_title_input_show(id)
{
	$("#"+id).fadeOut();
	$("#input_user_layoutname").fadeIn(500);

}

var table_count=0;
function user_timeout() {
	clearInterval(timerId);
	if (changeAction==true) {
		timeOutNow=true;
		var agree = confirm("タイムアウトしました。\n保存しますか？");
	    if(agree==true) {
		    validForm(table_count);
	    }
	}
	else {
		alert("タイムアウトしました");
	}

	window.location = "logout.php";	
}

</script>
<style>

.tables
	{
		height:31px;
		width: 31px;
		float:left;
		margin:5px 5px;
		background-image:url(./admin/img/circle_small.jpg);

	}

	.tables p
	{
		margin-left:-5px;
		margin-top:6px;
    text-align:center;
	}

	.tables p a
	{
	text-decoration:none;
	cursor:default;

	}
</style>

<div id="contents_wrapper" class="displayBox">
<div id="nav_left">
    <div class="step_bt"><img src="img/step_head_bt01_on.jpg" width="150" height="60" border="0"/></div>
    <div class="step_bt"><a href="hikidemono.php"><img src="img/step_head_bt02.jpg" width="150" height="60" border="0" class="on" /></a></div>
    <div class="step_bt"><a href="my_guests.php"><img src="img/step_head_bt03.jpg" width="150" height="60" border="0" class="on" /></a></div>
    <div class="step_bt"><a href="make_plan.php"><img src="img/step_head_bt04.jpg" width="150" height="60" border="0" class="on" /></a></div>
	  <div class="step_bt"><a href="order.php"><img src="img/step_head_bt05.jpg" width="150" height="45" border="0" class="on" /></a></div>
	  <div class="clear"></div>
</div>

<div id="contents_right">
  <div class="title_bar">
  <div class="title_bar_txt_L">披露宴会場のテーブルのレイアウト、卓名をご確認いただけます</div>
  <div class="title_bar_txt_R"></div>
  <div class="clear"></div>
</div>
  <div class="cont_area">
    <div class="info_box">
    <div class="info_area_L">

		<table width="75%" border="0" >
		  <tr>
			  <td width="100%" nowrap align="left" > ■　テーブルレイアウトは右の形になります。</td>
		  </tr>
		</table>
		<table width="75%" border="0" >
		  <tr>
		    <td width="30%" >披露宴会場　　　：</td>
		    <td width="50%" ><?=$roomName;?></td>
		  </tr>
		  <tr>
			<?
			$seat_number = $obj->GetSingleData("spssp_plan", "seat_number"," user_id =".$user_id);
			$roomid = $obj->GetSingleData("spssp_user","room_id"," id= ".$user_id);
			$max_seats = $obj->GetSingleData("spssp_room","max_seats"," id= ".$roomid);
			?>
		    <td>一卓の最大人数　：</td>
		    <td><?php if($seat_number){echo $seat_number;}else{echo $max_seats;}?> 名まで。</td>
		  </tr>
		</table>
        <br />
      <?php
      
		$user_tables = $obj->getRowsByQuery("select * from spssp_table_layout where user_id = ".$user_id." and display=1");
    //rename_tableがtrueになる条件は、
		$permission_table_edit = $obj->GetSingleData("spssp_plan", "rename_table"," user_id =".$user_id);
		$layoutname = $obj->getSingleData("spssp_plan", "layoutname"," user_id= $user_id");
		$default_layout_title = $obj->GetSingleData("spssp_options" ,"option_value" ," option_name='default_layout_title'");

	   if($permission_table_edit['rename_table']) {?>
        <table width="410" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td width="12" valign="top" nowrap="nowrap">■</td>
            <td width="378">テーブル名は任意の名前で変更できます。<br />
              変更する場合は、下記のフォームに入力して保存ボタンを押してください。<br />
              （右のプレビューは先頭文字のみ表示）
              </td>
          </tr>
      </table>

        <?php

		?>
        <?php
	   }
		if(isset($_GET['msg']) && $_GET['msg'] !='')
		{
			$obj->GetSuccessMsg((int)$_GET['msg']);
		}
		else if(isset($_GET['err']) && $_GET['err'] !='')
		{
			$obj->GetErrorMsg((int)$_GET['err']);
		}

	  ?>
		<div id='msg_rpt' style='display:none;text-align:center;margin-bottom:20px;background:#E1ECF7;border:1px solid #3681CB;padding:7px 10px;color:green;font-weight:bold;font-size:13px;'>
		Table name succesfully updated.
		</div>
		<div id='msg_rpt_err' style='display:none;text-align:center;margin-bottom:20px;background:#E1ECF7;border:1px solid #3681CB;padding:7px 10px;color:red;font-weight:bold;font-size:13px;'>
		Table name could not updated.
		</div>
		<form name="table_entry_form" id="table_entry_form" action="" method="post"	>

		<table width="410" border="0" cellspacing="0" cellpadding="5">
		<tr>
		<td colspan="4" align="center" style="display:none;" id="loading_td">
		
		</td>
		</tr>
		<br />
		<br />
		<tr>
			<td colspan="4" align="center">
			<div style="padding-left:25px;">高砂卓名：
			<?php
      
			$_readonly="";
			if (!$editable || $permission_table_edit['rename_table']==0) {
//				$_readonly=" readonly='readonly' style='border: #ffffff;'";
				$_readonly=" disabled='disabled' style='border:0px #ffffff none;'";
			}
			if($layoutname!="" && $layoutname!="null")
			{
				$name_input=$layoutname;
				echo "<input type='text' id='layoutname_ajax' name='layoutname_ajax'"." readonly='readonly' style='border: #ffffff;'"." value='".$name_input."' onChange='setChangeAction()' onkeydown='keyDwonAction(event)' onClick='clickAction()'>";
			}else if($default_layout_title!=""){
        echo "<input type='text' id='layoutname_ajax' name='layoutname_ajax'"." readonly='readonly' style='border: #ffffff;'"." value='".$default_layout_title."' onChange='setChangeAction()' onkeydown='keyDwonAction(event)' onClick='clickAction()'>";
      }
			else
			{
				echo "<input type='text' id='layoutname_ajax' name='layoutname_ajax'"." readonly='readonly' style='border: #ffffff;'"." value='"."&nbsp;&nbsp;&nbsp;"."' onChange='setChangeAction()' onkeydown='keyDwonAction(event)' onClick='clickAction()'>";

      }
			?></div>
			　　　　　</td>
		</tr>
         <tr>
		  <?php
		  $k=1;

			foreach($user_tables as $user_table_row)
			{

				if($user_table_row['table_id'])
				{
				?>

           <td width="14" align="center" valign="middle" nowrap="nowrap"><strong><?=$k?></strong></td>
           <td width="104" nowrap="nowrap">
		   <?php
			if($_readonly){
				echo $user_table_row['name'];
			}else{
				printf("<input name=\"tableName_%d\" type=\"text\" id=\"tableId_%d\" value=\"%s\" size=\"15\" style=\"border-style: inset;\" %s onChange=\"setChangeAction()\" onkeydown=\"keyDwonAction(event)\" onClick=\"clickAction()\"/>",$k,$k,$user_table_row['name'],$_readonly);
			}
		   ?>
		   <input name="hiddenid_<?=$k?>" type="hidden" id="hiddenid_<?=$k?>" value="<?=$user_table_row['id']?>" size="15" />
		   </td>
          <?php

		  	if($k%2==0)
			{
				echo "</tr><tr>";
			}

		  ?>


         <?php $k++;}} ?>
        </tr>

		<?php
				echo "<script> table_count=$k-1;</script>";
				if($editable && $permission_table_edit['rename_table']==1)
				  {?>
				  <tr>
				  <td colspan="4">

					&nbsp;<br />
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" onclick="validForm(<?=$k-1?>);" name="edit"><img src="img/btn_save_user.jpg" border="0" /></a>
					&nbsp;
					<a href="table_layout.php" name="cancel">
		        	<img src="img/btn_rollback_user.jpg" border="0" />
		        	</a>
					</td>
				  </tr>

				  <?php
				  }
				  ?>
		</table>
		</form>

      </div>
      <div class="info_area_R" style="">■　テーブルのレイアウト<br />

<?php
          $item_table_layout_number = true;
          include("item_table_layout.php");
?>
</div>
</div>
</div>

<?php
include("inc/new.footer.inc.php");
?>
