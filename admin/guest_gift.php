<?php
	require_once("inc/include_class_files.php");
	include_once("inc/checklogin.inc.php");
	include_once("inc/new.header.inc.php");
	include_once("../fuel/load_classes.php");

	$obj = new DBO();
	$objMsg = new MessageClass(); // UCHIDA EDIT 11/08/16 クリック日付を記録のため
	$objInfo = new InformationClass();
	$objMail = new MailClass();
	$user_id = $_GET['user_id'];
	$stuff_id = $_GET['stuff_id'];

$user = Model_User::find_by_pk($user_id);
$user_row = $user->to_array();
$plan = Model_Plan::find_one_by_user_id($user_id);
$plan_info = $plan->to_array();

	$get = $obj->protectXSS($_GET);
	$post = $obj->protectXSS($_POST);

	$click_info = $obj ->GetSingleRow("spssp_clicktime"," user_id=".$user_id);
	$kari_hachu = $objMsg->clicktime_format($click_info['kari_hachu']);
	$hon_hachu = $objMsg->clicktime_format($click_info['hon_hachu']);
	$hikide_zumi = $objMsg->clicktime_format($click_info['hikide_zumi']);

	$_hotel_name=$get['hotel_name'];

	if($get['action']=="allow")
	{
		if($obj->GetRowCount("spssp_plan"," `order` = 0 or `order` = 1 and user_id=".$user_id) >0 )
		{
			if($plan_info['print_company']>0)
			{
				$kari_hachu = $objMsg->clicktime_entry_return( "kari_hachu", $user_id );
        $plan->do_kari_hatyu();
        
				unset($post);
				$post['state']=date("Y/m/d");
				$obj->UpdateData('spssp_user',$post,"id=".$user_id);

				$res = $objMail->process_mail_user_suborder($user_id,$plan_info['print_company'], $_hotel_name);
			}
			else
			{
				$err=30;
			}
		}
	}
	if($get['action']=="print_request")
	{

		if($obj->GetRowCount("spssp_plan"," `order` >= 0 and user_id=".$user_id) >0 )
		{
			if($plan_info['print_company']>0)
			{
        $plan->do_hon_hatyu();

				$sekiji = (int)$get['sekiji'];
				$sekifuda = (int)$get['sekifuda'];
				$sql = "update spssp_plan set day_limit_1_to_print_com=".$sekiji.", day_limit_2_to_print_com=".$sekifuda." where user_id=".(int)$user_id;
				mysql_query($sql);
				
				$hon_hachu = $objMsg->clicktime_entry_return( "hon_hachu", $user_id );

				$res = $objMail->process_mail_user_print_request($user_id,$plan_info['print_company'], $_hotel_name);
			}
			else
			{
				$err=30;
			}
		}
	}
	if($get['action']=="daylimit_request")
	{

		if($obj->GetRowCount("spssp_plan"," gift_daylimit < 3 and user_id=".$user_id) > 0)
		{

			unset($post);
			$post['gift_daylimit']=3;
			$res = $obj->UpdateData('spssp_plan',$post,"user_id=".$user_id);

			$hikide_zumi = $objMsg->clicktime_entry_return( "hikide_zumi", $user_id );

			if(!$res)
			{
				$err=28;
			}
		}
	}
	if($get['action']=="reset")
	{
		$objInfo->reset_guest_gift_page_and_user_orders_conditions($user_id);
		$kari_hachu="";
		$hon_hachu="";
	}

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
	if (urls.search("print_request")!=-1) {
		var busuu="<?=$plan_info['dowload_options']?>";
		var user_id = "<?=$user_id?>";
	    var ans = window.showModalDialog("confirm_order.php?busuu="+busuu+"&user_id="+user_id, window ,"dialogTop:400px; dialogLeft:600px; dialogwidth:400px; dialogheight:180px;");
	    var ansArray = ans.split(",");
	    if (ansArray[0]=="OK") {
	    	urls = urls + "&sekiji="+ansArray[1]+"&sekifuda="+ansArray[2];
		    window.location = urls;
	    }
	}
	else {
	   	var agree = confirm(msg);
		if(agree)
		{
			window.location = urls;
		}
	}
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

<div style="clear:both;"></div>
	<div id="contents">
 <div style="font-size:11px; width:250px;">
  <?php  echo $objInfo->get_user_name_image_or_src($user_row['id'] ,$hotel_id=1, $name="man_lastname.png",$extra="thumb1");?>
・
  <?php  echo $objInfo->get_user_name_image_or_src($user_row['id'] ,$hotel_id=1, $name="woman_lastname.png",$extra="thumb1");?>
  様

    </div>
    <h4>
		<div style="width:600px">
		<?php
		if($stuff_id==0) {
            echo '<a href="manage.php">ＴＯＰ</a> &raquo; お客様挙式情報 &raquo; 席次表・引出物発注';
		}
		else {
            echo '<a href="users.php">管理者用お客様一覧</a> &raquo; お客様挙式情報 &raquo; 席次表・引出物発注';
		}
		?>
		</div>
        </h4>
	  <div   style="width:800px;"><div class="navi"><a href="user_info_allentry.php?user_id=<?=$user_id?>&stuff_id=<?=$stuff_id?>"><img src="img/common/navi01.jpg" class="on" onMouseOver="this.src='img/common/navi01_over.jpg'"onMouseOut="this.src='img/common/navi01.jpg'" /></a></div>
<?php
  if(!$IgnoreMessage){
?>
      <div class="navi"><a href="message_user.php?user_id=<?=$user_id?>&stuff_id=<?=$stuff_id?>"><img src="img/common/navi02.jpg" class="on" onMouseOver="this.src='img/common/navi02_over.jpg'"onMouseOut="this.src='img/common/navi02.jpg'" /></a></div>
  <?php
  }
?>

      <div class="navi">
      	
      		<img src="img/common/navi04_on.jpg" />
        </a>
      </div>
      <div class="navi">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
        	<div class="navi">
        	<a href="javascript:void(0);" onClick="windowUserOpen('user_dashboard.php?user_id=<?=$user_id?>')">
        		<img src="img/common/navi03.jpg" onMouseOver="this.src='img/common/navi03_on.jpg'"onMouseOut="this.src='img/common/navi03.jpg'" />
        	</a>
            </div><div class="navi2">
            
<?php if($_SESSION["super_user"]){ ?>
          <div class="navi"><a href="csv_upload.php?user_id=<?=$user_id?>"  onclick="m_win(this.href,'mywindow7',700,200); return false;">
           <img src="img/common/navi05.jpg" onMouseOver="this.src='img/common/navi05_over.jpg'"onMouseOut="this.src='img/common/navi05.jpg'" />
          </a></div>
<?php } ?></div>
      <div style="clear:both;"></div>
	  </div>
      <br />


	<div style="width:800px;">
		<h2>①席次表・席札　発注</h2>
		席次表の仮発注、本発注、依頼解除ができます。
        <div style="margin-top:20px;">
		<table width="50%" border="0" cellspacing="1" cellpadding="3">
			 <tr>
				<td width="182" valign="middle"><a href="../plan_pdf_small.php?user_id=<?=$user_id?>" target="_blank"><img src="img/common/order/seat_preview.gif" alt="" width="182" height="32" border="0" class="on" /></a></td>
					<td width="50" rowspan="3" align="center" valign="middle" style="font-size:16pt"><img src="img/common/arrow_1to2.gif" alt="矢印" width="32" height="59" border="0" /></td>
			<?php
             if(!$plan->can_kari_hatyu()){
?>
				<td valign="middle"><img src="img/common/order/seat_pro_order_greyed.gif" width="146" height="32" /></td>
				<?php }else{?>
			  	<td valign="middle"><a href="javascript:void(0);" onclick = "confirmAction('guest_gift.php?action=allow&user_id=<?=$user_id?>&hotel_name=<?=$hotel_name ?>&stuff_id=<?=$stuff_id?>','印刷会社へ仮発注を行います。宜しいですか？')" ><img src="img/common/order/seat_pro_order.gif" width="146" height="32" /></a></td>
				<?php }?>
				<td width="300">
					<?php
						echo $kari_hachu;
					?>
				</td>
				<td  width="80">&nbsp;</td>
		    </tr>
		    <tr>
			    <td valign="middle" height="20">&nbsp;</td>
			    <td width="146" height="20" valign="middle">&nbsp;</td>
			    <td width="146" height="20" valign="middle">&nbsp;</td>
			    <td>&nbsp;</td>
            </tr>


			  <tr>
				<td width="182" valign="middle">
			    </td>
				<?php
           if(!$plan->can_hon_hatyu()){
			?>
				<td valign="middle"><img src="img/common/order/seat_order_greyed.gif" width="146" height="32" /></td>
				<?php }else{?>
			  	<td valign="middle"><a href="javascript:void(0);" onclick = "confirmAction('guest_gift.php?action=print_request&user_id=<?=$user_id?>&hotel_name=<?=$hotel_name ?>&stuff_id=<?=$stuff_id?>','印刷会社へ本発注を行います。宜しいですか？')"><img src="img/common/order/seat_order.gif" width="146" height="32" /></a></td>
				<?php }?>
 				<td>
					<?php
						echo $hon_hachu;
						if ($hon_hachu!="") {
							echo "<br />";
							if ($plan_info['dowload_options']==1 || $plan_info['dowload_options']==3) echo "席次表印刷部数 ".$dayLimit_1." 部";
							echo "　";
							if ($plan_info['dowload_options']==2 || $plan_info['dowload_options']==3) echo "席札印刷部数 ".$dayLimit_2." 部";
						}
					?>
				</td>
		  </tr>

		</table>
	  </div>
		<div style="margin-top:20px;margin-bottom:20px;">
		<table width="783" border="0" cellspacing="1" cellpadding="3">
			  <tr>
				<td width="182" valign="middle"></td>
				<td width="50" align="center" valign="middle">&nbsp;</td>

			  	<td width="617" valign="middle">
          
              <?php if (!$plan->can_reset()) {?>
        <img src="img/common/order/seat_order_release_greyed.gif" width="146" height="32" /></a>
           <?php }else { ?>
					<a href="javascript:void(0);" onclick = "confirmAction('guest_gift.php?action=reset&user_id=<?=$user_id?>&stuff_id=<?=$stuff_id?>','依頼解除を行います。宜しいですか？')"><img src="img/common/order/seat_order_release.gif" width="146" height="32" /></a>
				<?php }?>
					<br>
					※お客様画面の「依頼」ボタンが押されていて、<br>
　　						依頼解除したい場合にはこちらを押してください。
				</td>

			  </tr>

		</table>
</div>

		<div>
			<h2>②引出物発注</h2>
			お客様からの引出物発注を確認後は、引出物処理済ボタンをクリックしてください。
		</div>
		<div style="margin-top:20px;">
        <table width="50%" border="0" cellspacing="1" cellpadding="3">
			  <tr>
				<td width="182" valign="middle">
          <a href="../plan_pdf.php?user_id=<?=$user_id?>" target="_blank"><img src="img/common/order/gift_preview.gif" alt="" width="182" height="32" border="0" class="on" /></a>
        </td>
					<td width="50" align="center" valign="middle" style="font-size:16pt"><img src="img/common/arrow_1to1.gif" alt="矢印" width="32" height="7" border="0" /></td>
			<?php
				if($plan_info['gift_daylimit']>=3)
				{
			?>
				<td width="182" valign="middle"><img src="img/common/order/gift_processed_greyed.gif" width="146" height="32" /></td>
				<?php }else{?>
			  	<td width="182" valign="middle">
				<a href="javascript:void(0);" onclick = "confirmAction('guest_gift.php?action=daylimit_request&user_id=<?=$user_id?>&stuff_id=<?=$stuff_id?>','引出物処理をします。宜しいですか？')">
				<img src="img/common/order/gift_processed.gif" width="146" height="32" /></a></td>
				<?php }?>
				<td>
					<?php
						echo $hikide_zumi; // UCHIDA EDIT 11/08/16 クリック日付を表示
					?>
				</td>
		  </tr>
		  <tr><td>　</td></tr>
		  <tr><td>　</td></tr>
		  <tr>
				    <?php 
				    if ($obj->GetRowCount("spssp_plan"," admin_to_pcompany >= 2 and `ul_print_com_times` < 2 and `order` >= 1 and user_id=".$user_id) && $user_row['party_day'] >= date("Y-m-d")) {
				    ?>
				    	<td width="210" valign="middle"><a href="<? echo "../".substr($plan_info['p_company_file_up'], 3)?>" target="_blank"><img src="img/common/preview_print_bt_hotel.jpg" alt="席次表プレビュー" border="0" class="on"/></a></td>
				    <?php 
				    } 
				    else { 
				    ?>
				    	<td width="210" valign="middle"><img src="img/common/preview_print_bt_hotel_gray.jpg" alt="席次表プレビュー" border="0" /></td>
				    <?php 
				    } ?>
				    
					<td valign="middle">　</td>
					<td colspan="2" valign="middle"><p>印刷会社よりアップロードされた「席次表の印刷イメージ」がご確認いただけます。<br />※印刷会社よりアップロードされるまでは、ボタンは使用できません。</p></td>
					 
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
