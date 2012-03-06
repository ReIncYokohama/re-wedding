<?php
	require_once("admin/inc/include_class_files.php");
	include_once("inc/checklogin.inc.php");

	$obj = new DBO();
	$objMsg = new MessageClass();
	$objMail = new MailClass();
	$get = $obj->protectXSS($_GET);
	$user_id = (int)$_SESSION['userid'];

  //tabの切り替え
  $tab_order = true;

$TITLE = "発注 - ウエディングプラス";
include_once("inc/new.header.inc.php");

	/*
		Hints : FROM ADMIN SIDE
		SPSSP_PLAN TABLE FOR THE FIELD "admin_to_pcompany"
		1 => WHEN ADMIN SENDS THE USER SUB-ORDER TO THE PRINT COMPANY
		2 => WHEN PRINT COMPANY UPLOAD FILES ON THE RESPONSE OF USER SUB-ORDER
		3 => WHEN ADMIN SENDS THE USER PRINTINGT REQUEST TO THE PRINT COMPANY
	*/
	/*
		Hints : FROM USER[HERE] SIDE
		SPSSP_MESSAGE TABLE FOR THE FIELD "msg_type"
		1 => WHEN USER SENDS SUB-ORDER TO THE ADMIN
		2 => WHEN USER SENDS PRINT REQUEST TO THE ADMIN
	*/
	/*
		Hints : FROM USER[HERE] SIDE
		SPSSP_PLAN TABLE FOR THE FIELD "order"
		1 => WHEN USER SENDS SUB-ORDER TO THE ADMIN
		2 => WHEN USER SENDS PRINT REQUEST TO THE ADMIN
    
	*/
  
	$plan_info = $obj ->GetSingleRow("spssp_plan"," user_id=".$user_id);

// UCHIDA EDIT 11/08/16 クリック日付を取得
	$click_info = $obj ->GetSingleRow("spssp_clicktime"," user_id=".$user_id);
	$print_irai = $objMsg->clicktime_format($click_info['print_irai']);
	$print_ok = $objMsg->clicktime_format($click_info['print_ok']);
	$hikide_irai = $objMsg->clicktime_format($click_info['hikide_irai']);

	if($get['action']=="suborder")
	{

		if($plan_info['print_company']>0)
		{
      $res = $objMail->user_suborder_mail_to_admin($user_id,$plan_info['print_company']);
      
      unset($post);
      $post['order']=1;
      $obj->UpdateData('spssp_plan',$post," user_id=".$user_id);
      $print_irai = $objMsg->clicktime_entry_return( "print_irai", $user_id );
      redirect("order.php?msg=5");
		}
		else
		{
			$err=26;
		}
	}

	if($get['action'] == "print_request")
	{
		if($plan_info['print_company']>0)
		{
      $objMail->user_print_request_mail_to_admin($user_id,$plan_info['print_company']);
      unset($post);
      $post['order']=2;
      $post['dl_print_com_times']=0;
      $post['ul_print_com_times']=0;
      $obj->UpdateData('spssp_plan',$post," user_id=".$user_id);
      
      unset($post);
      $post['print_ok']=date("Y-m-d H:i:s");
      $obj->UpdateData('spssp_clicktime',$post," user_id=".$user_id);
        
      $print_ok = $objMsg->clicktime_entry_return( "print_ok", $user_id );
      
      redirect("order.php?msg=10");
		}
		else
		{
			$err=26;
		}
	}
	if($get['action'] == "daylimit_over_request")
	{
    $objMail->process_mail_user_gift_daylimit_request($user_id);
    
    unset($post);
    $post['gift_daylimit']=1;
    
    $obj->UpdateData('spssp_plan',$post," user_id=".$user_id);
    
    unset($post);
    $post['hikide_irai']=date("Y-m-d H:i:s");
    $obj->UpdateData('spssp_clicktime',$post," user_id=".$user_id);
    
    $hikide_irai = $objMsg->clicktime_entry_return( "hikide_irai", $user_id ); // UCHIDA EDIT 11/08/16 クリック日付を記録
    
    redirect("order.php?msg=10");

	}

?>
<script>

$(function(){

	});
</script>
<script type="text/javascript">
function confirmAction(urls , msg)
{
   	var agree = confirm(msg);
	if(agree)
	{
		window.location = urls;
	}
}

</script><div id="contents_wrapper" class="displayBox">
<div id="nav_left">
  <div class="step_bt"><a href="table_layout.php"><img src="img/step_head_bt01.jpg" width="150" height="60" border="0" class="on" /></a></div>
  <div class="step_bt"><a href="hikidemono.php"><img src="img/step_head_bt02.jpg" width="150" height="60" border="0" class="on"/></a></div>
  <div class="step_bt"><a href="my_guests.php"><img src="img/step_head_bt03.jpg" width="150" height="60" border="0" class="on" /></a></div>
  <div class="step_bt"><a href="make_plan.php"><img src="img/step_head_bt04.jpg" width="150" height="60" border="0" class="on" /></a></div>
  <div class="step_bt"><img src="img/step_head_bt05_on.jpg" width="150" height="45" border="0"/></div>
  <div class="clear"></div></div>
<?php
	$plan_info = $obj ->GetSingleRow("spssp_plan"," user_id=".$user_id);
?>
<div id="contents_right">
	<div id="box_1">
		<div class="title_bar">
			<div class="title_bar_txt_L">席次表の印刷イメージ依頼</div>
			<div class="title_bar_txt_R"></div>
			<div class="clear"></div>
		</div>
		<div class="cont_area">
			「席次表プレビュー」をご確認の上、「印刷イメージ依頼」ボタンをクリックしてください。<br />
			修正がある場合は、招待者リストの作成画面に戻り修正してください。<br />
			印刷イメージ依頼後は、担当者が確認の上、印刷会社へ仮発注をいたします。<br /><br />

			<table width="800" border="0 cellspacing="1" cellpadding="3">
				  <tr>
					<td width="28%" valign="middle"><a href="plan_pdf_small.php" target="_blank"><img src="img/order/preview_sekiji_bt.jpg" alt="席次表プレビュー" width="200" height="40" border="0" class="on" /></a></td>
					<td width="5%" valign="middle" style="font-size:16pt">→</td>
					<?php
						$isGrey=false;
						if($plan_info['order']>0) $isGrey=true;
						if ($plan_info['admin_to_pcompany']==2) $isGrey=false;
// add 20111228 start PDFのタイムスタンプが印刷イメージ依頼のタイムスタンプよりも古ければ、依頼ボタンをグレーアウトする。
						$pd = strptime($click_info['print_irai'],"%Y-%m-%d %H:%M:%S");
						$pidate = mktime($pd[tm_hour],$pd[tm_min],$pd[tm_sec],$pd[tm_mon]+1,$pd[tm_mday],$pd[tm_year] + 1900);
						if(!preg_match('/.*\/(\d*).PDF$/', $plan_info['p_company_file_up'] , $matches)){
							$matches = array("1");
						}
						if($plan_info['admin_to_pcompany']==2){
							if($pidate > $matches[1]) $isGrey=true;
						}
// added 20111228
						if($isGrey==true)
						{
					?>
					<td width="20%" valign="middle"><img src="img/order/print_img_bt_greyed.jpg" /></td>
				  		<?php }else{?>
					<td width="20%" valign="middle"><a href="javascript:void(0);" onclick = "confirmAction('order.php?action=suborder','印刷の仮発注を行います。宜しいですか？')" ><img src="img/order/print_img_bt.jpg" class="on"/></a></td>
						<?php }?>
					<td>
						<?php
							echo $print_irai;
						?>
					</td>
				  </tr>
				</table>
				  <tr>
					 <td colspan="3" valign="middle">「印刷イメージ依頼」ボタンをクリックすると、印刷イメージが出来上がるまでの間、招待者リストの作成・席次表の修正ができなくなります。ご注意ください。</td>
				  </tr>
		</div>
	</div><!--END OF BOX_1-->

	<div id="box_2">
		<div class="title_bar">
			<div class="title_bar_txt_L">席次表の印刷依頼</div>
			<div class="title_bar_txt_R"></div>
			<div class="clear"></div>
		</div>
		<div class="cont_area">
			「席次表プレビュー」をご確認の上、「印刷ＯＫ」ボタンをクリックしてください。<br />
			修正がある場合は、招待者リストの作成画面に戻り修正してください。<br />
			席次表の印刷依頼後は、担当者が確認の上、印刷会社へ発注をいたします。<br /><br />

			<table width="800" border="0" cellspacing="1" cellpadding="3">
				  <tr>
					<td width="28%" valign="middle"><a href="plan_pdf_small.php" target="_blank"><img src="img/order/preview_sekiji_bt.jpg" alt="席次表プレビュー" width="200" height="40" border="0" class="on" /></a></td>
					<td width="5%" valign="middle" style="font-size:16pt" >→</td>
					<?php
//admin_to_pcompanyを追加
						if($plan_info["admin_to_pcompany"] != 2 && $plan_info['order']<=3 && $plan_info['order']>1)
						{
					?>
					<td width="20%" valign="middle"><img src="img/order/print_bt_greyed.jpg"/></td>
					<?php }else{?>

					<td width="20%" valign="middle"><a href="javascript:void(0);" onclick = "confirmAction('order.php?action=print_request','印刷の発注を行います。宜しいですか？')"><img src="img/order/print_bt.jpg" class="on"/></a></td>
				  	<?php }?>
					<td>
						<?php
							echo $print_ok;
						?>
					</td>
				  </tr>
					</table>
				  <tr>
					 <td colspan="3" valign="middle">「印刷ＯＫ」ボタンをクリックすると、招待者リストの作成・席次表の修正ができなくなります。ご注意ください。</td>
				  </tr>
		</div>
	</div><!--END OF BOX_2-->

	<div id="box_3" style="height:auto;">
		<div class="title_bar">
		  <div class="title_bar_txt_L">引出物の発注依頼</div>
		  <div class="title_bar_txt_R"></div>
		  <div class="clear"></div>
		</div>
		<div class="cont_area">
			「引出物プレビュー」をご確認の上、「発注依頼」ボタンをクリックしてください。<br />
			修正がある場合は、招待者リストの作成画面に戻り修正してください。<br />
			引出物の発注依頼後は、担当者が確認の上、引出物の発注をいたします。<br /><br />

			  <table width="800" border="0" cellspacing="1" cellpadding="3">
				  <tr>
					<td width="28%" valign="middle"><a href="plan_pdf.php" target="_blank"><img src="img/order/preview_hikidemono_bt.jpg" alt="席次表プレビュー" width="200" height="40" border="0" class="on" /></a></td>
					<td width="5%" valign="middle" style="font-size:16pt">→</td>
					<td width="20%" valign="middle">
					<?php
//					if($plan_info['gift_daylimit']==1 && $plan_info['gift_daylimit']>0) // UCHIDA EDIT 11/08/09 ２度とボタンは有効にしない
					if($plan_info['gift_daylimit']==1 || $plan_info['gift_daylimit']==3)
					{
					?>
						<img src="img/order/order_request_bt_greyed.jpg"/>
					<?php }else{?>
					<a href="javascript:void(0);" onclick = "confirmAction('order.php?action=daylimit_over_request','引出物を発注します。宜しいですか？')">
						<img src="img/order/order_request_bt.jpg" class="on"/>
					</a>
					<?php }?>
					</td>
					<td>
						<?php
							echo $hikide_irai;
						?>
					</td>
				  </tr>
			 </table>
				  <tr>
					 <td colspan="3" valign="middle">「発注依頼」ボタンををクリックすると、引出物の数の変更ができなくなります。ご注意ください。</td>
				  </tr>
		</div>
	</div><!--END OF BOX_3-->
<?php
include("inc/new.footer.inc.php");
?>
<?php
			if($_GET['err']){$err=$_GET['err'];}
			if($_GET['msg']){$msg=$_GET['msg'];}

			///ERROR MESSAGE
			if($err){
			echo "<script>
			alert('".$obj->GetErrorMsgNew($err)."');
			</script>";
			}



?>
