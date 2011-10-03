<?php
	require_once("inc/include_class_files.php");
	include_once("inc/checklogin.inc.php");
	include_once("inc/new.header.inc.php");
	$obj = new DBO();
	$objInfo = new InformationClass();
	$objMail = new MailClass();
	$user_id = $_GET['user_id'];


	$user_row1 = $obj->GetSingleRow("spssp_user"," id= ".$user_id);
  $user_row = $user_row1;
	$plan_info = $obj ->GetSingleRow("spssp_plan"," user_id=".$user_id);
	/*
		Hints : FROM ADMIN[HERE] SIDE
		SPSSP_PLAN TABLE FOR THE FIELD "admin_to_pcompany"
		1 => WHEN ADMIN SENDS THE USER SUB-ORDER TO THE PRINT COMPANY
		2 => WHEN PRINT COMPANY UPLOAD FILES ON THE RESPONSE OF USER SUB-ORDER
		3 => WHEN ADMIN SENDS THE USER PRINTINGT REQUEST TO THE PRINT COMPANY
	*/
	/*
		Hints : FROM USER SIDE
		SPSSP_MESSAGE TABLE FOR THE FIELD "msg_type"
		1 => WHEN USER SENDS SUB-ORDER TO THE ADMIN
		2 => WHEN USER SENDS PRINT REQUEST TO THE ADMIN

	*/
	/*
		Hints : FROM USER SIDE
		SPSSP_PLAN TABLE FOR THE FIELD "order"
		1 => WHEN USER SENDS SUB-ORDER TO THE ADMIN
		2 => WHEN USER SENDS PRINT REQUEST TO THE ADMIN

	*/
	/*
		Hints : FROM ADMIN SIDE
		SPSSP_PLAN TABLE FOR THE FIELD "gift_daylimit"
		1 => WHEN USER SENDS GIFT DAY LIMIT TO THE ADMIN
		2 => WHEN ADMIN RESPONSE TO USER SENDS GIFT DAY LIMIT TO THE ADMIN
		3 => WHEN USER DAY LIMIT OVER FOR GIFT
	*/

	$get = $obj->protectXSS($_GET);
	$post = $obj->protectXSS($_POST);

	if($get['action']=="allow")
	{

		if($obj->GetRowCount("spssp_plan"," `order` = 1 and user_id=".$user_id) >0 )
		{
			if($plan_info['print_company']>0)
			{
				$res = $objMail->process_mail_user_suborder($user_id,$plan_info['print_company']);
				if($res==6)
				{
					unset($post);
					$post['admin_to_pcompany']=1;
					$obj->UpdateData('spssp_plan',$post,"user_id=".$user_id);

					/*unset($post);
					$post['msg_type']=0;
					$obj->UpdateData('spssp_message',$post," msg_type=1 and user_id=".$user_id);*/

				}
				else if($res==28)
				{
					$err=28;
				}
			}
			else
			{
				$err=30;
			}
		}
		else
		{
			//$err=31;
		}
	}
	if($get['action']=="print_request")
	{

		if($obj->GetRowCount("spssp_plan"," `order` = 2 and user_id=".$user_id) >0 )
		{
			if($plan_info['print_company']>0)
	{
				$res = $objMail->process_mail_user_print_request($user_id,$plan_info['print_company'],$flag=2);
				if($res==6)
				{
					$post['admin_to_pcompany']=3;
					$obj->UpdateData('spssp_plan',$post,"user_id=".$user_id);

					/*unset($post);
					$post['msg_type']=0;
					$obj->UpdateData('spssp_message',$post," msg_type=2 and user_id=".$user_id);*/
				}
				else if($res==28)
				{
					$err=28;
				}
			}
			else
			{
				$err=30;
			}
		}
		else
		{
			// $err=32;
		}
	}
	if($get['action']=="daylimit_request")
	{

		if($obj->GetRowCount("spssp_plan"," gift_daylimit = 1 and user_id=".$user_id) >0 || $obj->GetRowCount("spssp_plan"," gift_daylimit = 2 and user_id=".$user_id) >0)
		{

			unset($post);
//			$post['gift_daylimit']=2; UCHIDA EDIT 11/08/09 引出物処理済ボタンが押されたので終了を設定
			$post['gift_daylimit']=3;
			$res = $obj->UpdateData('spssp_plan',$post,"user_id=".$user_id);

			if($res)
			{
				//$msg=11;
			}
			else
			{
				$err=28;
			}
		}
		else
		{
			$err=33;
		}
	}
	if($get['action']=="reset")
	{
		$objInfo->reset_guest_gift_page_and_user_orders_conditions($user_id);
	}
	//print_r($_POST);
	//DAY LIMIT FIELD UPGRADE FOR VALUE WHETHER 1 OR 2
	if($_POST['gift_day_limit'] =="gift_day_limit" )
	{

		unset($post);
		$post['day_limit_1_to_print_com'] = (int)$_POST['day_limit_1'];
		$post['day_limit_2_to_print_com'] = (int)$_POST['day_limit_2'];
		$obj->UpdateData('spssp_plan',$post,"user_id=".$user_id);

	}



	//DAY LIMIT CHECK FOR VALUE WHETHER 1 OR 2
	$plan_info2 = $obj ->GetSingleRow("spssp_plan"," user_id=".$user_id);
	if($plan_info2['dowload_options']==1)
	{
		$dayLimit_1 = $plan_info2['day_limit_1_to_print_com'];
	}
	if($plan_info2['dowload_options']==2)
	{
		$dayLimit_2 = $plan_info2['day_limit_2_to_print_com'];
	}
	if($plan_info2['dowload_options']==3)
	{
		$dayLimit_1 = $plan_info2['day_limit_1_to_print_com'];
		$dayLimit_2 = $plan_info2['day_limit_2_to_print_com'];
	}

	unset($plan_info);

	$plan_info = $obj ->GetSingleRow("spssp_plan"," user_id=".$user_id);

//echo $err;

?>
<style>
.edit_guest
{

display:none;
padding:10px 0;
}
.txt_input
{
width:160px;
border:solid 1px #666666;
}
.edit_guest select
{
width:160px;
border:solid 1px #666666;
}
.edit_tbl tr
{
height:25px;
}
</style>
<script type="text/javascript">
function validForm_1()
{

	/*var daylimit_1  = document.getElementById('daylimit_1').value;
	var daylimit_2  = document.getElementById('daylimit_2').value;


	var flag = true;
	if(!daylimit_1)
	{
		 alert("タイトルが未入力です");
		 document.getElementById('daylimit_1').focus();
		 return false;
	}*/

	document.day_limit_form.submit();
}
function validForm_2()
{
	var daylimit_2  = document.getElementById('daylimit_2').value;


	var flag = true;
	if(!daylimit_2)
	{
		 alert("タイトルが未入力です");
		 document.getElementById('daylimit_2').focus();
		 return false;
	}


}
function confirmAction(urls , msg)
{
   	var agree = confirm(msg);
	if(agree)
	{
		window.location = urls;
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
        <a href="javascript:;" onclick="MM_openBrWindow('../support/operation_h.html','','scrollbars=yes,width=620,height=600')"><img src="img/common/btn_help.jpg" alt="ヘルプ" width="82" height="19" /></a>
    </div>
</div>

<div id="container">

<div style="clear:both;"></div>
	<div id="contents">
 <div style="font-size:11px; width:250px;">
  <?php  echo $objInfo->get_user_name_image_or_src($user_row['id'] ,$hotel_id=1, $name="man_lastname.png",$extra="thumb1",$height=20);?>
・
  <?php  echo $objInfo->get_user_name_image_or_src($user_row['id'] ,$hotel_id=1, $name="woman_lastname.png",$extra="thumb1",$width=20);?>
  様

    </div>
    <h2>
	<!-- UCHIDA EDIT 11/08/04
        <div style="width:600px"><a href="users.php">お客様一覧</a> &raquo; <a href="user_info.php?user_id=<?=$user_id?>">お客様挙式情報</a> &raquo; 席次表・引出物発注</div>
	-->
       <div style="width:600px"><a href="manage.php">ＴＯＰ</a> &raquo; 席次表・引出物発注</div>
        </h2>
	  <div   style="width:800px;"><div class="navi"><a href="user_info.php?user_id=<?=$user_id?>"><img src="img/common/navi01.jpg" width="148" height="22" class="on" /></a></div>
      <div class="navi"><a href="message_admin.php?user_id=<?=$user_id?>"><img src="img/common/navi02.jpg" width="96" height="22" class="on" /></a></div>
      <div class="navi">
      	<a href="user_dashboard.php?user_id=<?=$user_id?>" target="_blank">
      		<img src="img/common/navi03.jpg" width="126" height="22" class="on" />
        </a>
      </div>
      <div class="navi"><img src="img/common/navi04_on.jpg" width="150" height="22" /></div>
      <!--<div class="navi"><a href="customers_date_dl.php?user_id=<?=$user_id?>"><img src="img/common/navi05.jpg" width="116" height="22" class="on" /></a></div>-->
      <div style="clear:both;"></div>
	  </div>
      <br />


<!-- 	<div id="message_BOX"  style="width:800px;"> UCHIDA EDIT 11/08/10 -->
	<div style="width:800px;">
		<h2>席次表・席札　発注</h2>
		席次表の仮発注、本発注、依頼解除ができます。
        <div style="margin-top:20px;">
		<table width="50%" border="0" cellspacing="1" cellpadding="3">
			  <tr>
				<td width="210" valign="middle"><a href="../plan_pdf_small.php" target="_blank"><img src="img/common/order/seat_preview.gif" alt="" border="0" class="on" /></a></td>
			<?php
				if(($plan_info['admin_to_pcompany']>0 && $plan_info['ul_print_com_times']==1) || ($plan_info['admin_to_pcompany']>0 && $plan_info['order']<=2))
				{
			?>
				<td width="80%" valign="middle"><img src="img/common/order/seat_pro_order_greyed.gif" /></td>
				<?php }else{?>
			  	<td width="80%" valign="middle"><a href="javascript:void(0);" onclick = "confirmAction('guest_gift.php?action=allow&user_id=<?=$user_id?>','印刷会社へ仮発注を行います。宜しいですか？')" ><img src="img/common/order/seat_pro_order.gif" /></a></td>
				<?php }?>
		  </tr>

		</table>
        </div>

		<!--<div style="margin-top:20px;">
		<table width="50%" border="0" cellspacing="1" cellpadding="3">
			  <tr>
				<td width="20%" valign="middle">
					■席次表印刷部数<br>
					<form action="guest_gift.php?user_id=<?=$user_id?>" method="POST" name="day_linit_form_1">
						<input type="text" id="daylimit" name="day_limit_1" size="1" maxlength="3" value="<?=$dayLimit_1?>" style="height:30px;font-size:18px;font-weight:bold;">
						<input type="submit" value="保存" onclick="validForm_1();">
					</form>
				</td>
			<?php
				if($plan_info['admin_to_pcompany']==1 && $plan_info['admin_to_pcompany']>0)
				{
			?>
				<td width="80%" valign="middle"><img src="img/common/order/seat_paper_order_greyed.gif" /></td>
				<?php }else{?>
			  	<td width="80%" valign="middle"><a href="#"><img src="img/common/order/seat_paper_order.gif" /></a></td>
				<?php }?>
			  </tr>

		</table>
        </div>-->

		<div style="margin-top:20px;">
        <table width="50%" border="0" cellspacing="1" cellpadding="3">
			  <tr>
				<td width="210" valign="middle">

					<form action="guest_gift.php?user_id=<?=$user_id?>" method="POST" name="day_limit_form">
					<input type="hidden" name="gift_day_limit" value="gift_day_limit">

					<?php
					if($plan_info['dowload_options']==1)
					{ ?>
						■席次表印刷部数<br>
			<input type="text" id="daylimit_1" name="day_limit_1" size="1" maxlength="3" value="<?=$dayLimit_1?>" style="height:30px;font-size:18px;font-weight:bold;">
					<br><br>
					<br>
					<?php }
					if($plan_info['dowload_options']==2)
					{ ?>
						■席札表印刷部数<br>
		    <input type="text" id="daylimit_2" name="day_limit_2" size="1" maxlength="3" value="<?=$dayLimit_2?>" style="height:30px;font-size:18px;font-weight:bold;">
		    		<br><br>
					<?php }
					if($plan_info['dowload_options']==3)
					{ ?>
						■席次表印刷部数<br>
			<input type="text" id="daylimit_1" name="day_limit_1" size="1" maxlength="3" value="<?=$dayLimit_1?>" style="height:30px;font-size:18px;font-weight:bold;">
					<br><br>
						■席札表印刷部数
					<br>
					<input type="text" id="daylimit_2" name="day_limit_2" size="1" maxlength="3" value="<?=$dayLimit_2?>" style="height:30px;font-size:18px;font-weight:bold;">
					<br><br>
				<?php	}

					?>
					<?php
					if($plan_info['dowload_options']>0)
					{ ?>
					<a href="javascript:void(0);" onclick="validForm_1();"><img src="img/common/btn_save.jpg" alt="保存"></a>
				<?php }?>
					</form>
				</td>
			<?php
				if($plan_info['admin_to_pcompany']==3 && $plan_info['admin_to_pcompany']>0 &&$plan_info['ul_print_com_times']==1)
				{
			?>
				<td width="80%" valign="middle"><img src="img/common/order/seat_order_greyed.gif" /></td>
				<?php }else{?>
			  	<td width="80%" valign="middle"><a href="javascript:void(0);" onclick = "confirmAction('guest_gift.php?action=print_request&user_id=<?=$user_id?>','印刷会社へ仮発注を行います。宜しいですか？')"><img src="img/common/order/seat_order.gif" /></a></td>
				<?php }?>
		  </tr>

		</table>
		</div>
		<div style="margin-top:20px;margin-bottom:20px;">
		<table width="783" border="0" cellspacing="1" cellpadding="3">
			  <tr>
				<td width="210" valign="middle"></td>

			  	<td width="617" valign="middle">
					<a href="javascript:void(0);" onclick = "confirmAction('guest_gift.php?action=reset&user_id=<?=$user_id?>','依頼解除を行います。宜しいですか？')"><img src="img/common/order/seat_order_release.gif" /></a>
					<br>※お客様画面の「依頼」ボタンが押されていて、<br>
　　						依頼解除したい場合にはこちらを押してください。

				</td>

			  </tr>

		</table>
</div>

<!-- 		<div style="border-top:1px solid #3681CB;padding:5px; width:800px;"> UCHIDA EDIT 11/08/10 -->
		<div>
			<h2>引出物発注</h2>
			お客様からの引出物発注を確認後は、引出物処理済ボタンをクリックしてください。
		</div>
		<div style="margin-top:20px;">
        <table width="50%" border="0" cellspacing="1" cellpadding="3">
			  <tr>
				<td width="210" valign="middle"><a href="../plan_pdf.php" target="_blank"><img src="img/common/order/gift_preview.gif" alt="" border="0" class="on" /></a></td>
			<?php
				if($plan_info['gift_daylimit']==3 && $plan_info['gift_daylimit']>0)
				{
			?>
				<td width="80%" valign="middle"><img src="img/common/order/gift_processed_greyed.gif" /></td>
				<?php }else{?>
			  	<td width="80%" valign="middle">
				<a href="javascript:void(0);" onclick = "confirmAction('guest_gift.php?action=daylimit_request&user_id=<?=$user_id?>','引出物処理をします。宜しいですか？')">
				<img src="img/common/order/gift_processed.gif" /></a></td>
				<?php }?>
		  </tr>

		</table>
		</div>



    </div>



</div>

</div>

<?php

	include_once("inc/left_nav.inc.php");
	include_once("inc/new.footer.inc.php");
?>
<?php


			//echo $error_array[$err];
			if($_GET['err']){$err=$_GET['err'];}
			if($_GET['msg']){$msg=$_GET['msg'];}

			///ERROR MESSAGE
			if($err){
			echo "<script type='text/javascript'>
			alert('".$obj->GetErrorMsgNew($err)."');
			</script>";
			$err=0;
			}
			if($msg)
			{echo "<script type='text/javascript'>
			alert('".$obj->GetSuccessMsgNew($msg)."');
			</script>";
			$msg=0;
			}


?>
