<?php
	include_once("inc/dbcon.inc.php");
	include_once("inc/checklogin.inc.php");
	include_once("inc/new.header.inc.php");
	require_once("inc/class.dbo.php");

	$obj = new DBO();
	$post = $obj->protectXSS($_POST);
	if($post['DefaultSettings']=="DefaultSettings")
	{
 // echo $post['confirm_day_num']." : ".$post['limitation_ranking']." : ".$post['order_deadline']." : ".$post['user_id_limit'];
		unset($post['DefaultSettings']);
		unset($value['option_value']);
		if($post['useridlimit'] <100)
		{
		$whereCloser = array('confirm_day_num','user_id_limit','limitation_ranking');
			for($i=0;$i<count($whereCloser);$i++)
			{
//				echo $whereCloser[$i]." : ";
				$sql="Update spssp_options set option_value='".$post[$whereCloser[$i]]."' where option_name='".$whereCloser[$i]."'";
				mysql_query($sql);
			}
		}
		else
		{
			echo '<script>alert("お客様ID利用期限日は１００日以下にしてください");</script>';
		}
			$value['order_deadline'] = $post['order_deadline'];
			$sql="Update spssp_gift_criteria set order_deadline='".$post['order_deadline']."'";
			mysql_query($sql);
	}

	//$data_rows = $obj->getRowsByQuery($query_string);

    $confirm_day_num    = $obj->GetSingleData("spssp_options" ,"option_value" ," option_name='confirm_day_num'");		// 席次表本発注締切日
    $limitation_ranking = $obj->GetSingleData("spssp_options" ,"option_value" ," option_name='limitation_ranking'");	// 席次表編集利用制限日
    $user_id_limit      = $obj->GetSingleData("spssp_options" ,"option_value" ," option_name='user_id_limit'");			// お客様ID利用期限日

    $order_deadline     = $obj->GetSingleData("spssp_gift_criteria" ,"order_deadline" ,"1=1");							// 引出物本発注締切日


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
<script type="text/javascript">
var crnt = <?=$user_id_limit?>;
function valid_user()
{
var lim = document.getElementById("user_id_limit").value;
	if(document.getElementById("user_id_limit").value=='')
	{
		alert("お客様ID利用期限日を正しく入力してください");
		document.getElementById('user_id_limit').focus();
		return false;
	}

// UCHIDA EDIT 11/08/01
	if (lim.match( /[^0-9\s]+/ ) ) {
		alert("お客様ID利用期限日には半角数字で入力してください");
		document.getElementById('user_id_limit').focus();
		document.user_form_register.user_id_limit.value = crnt;
//		$('#useridlimit').val(crnt);
		return false;
	}
	document.user_form_register.submit();
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

<h2><div style="width:300px;"> 締切日設定 </div></h2>


<h2>席次表本発注締切日</h2>
  <form action="useridlimit.php" method="post" name="user_form_register">
<table width="500" border="0" align="left" cellpadding="0" cellspacing="10" style="width:500px;">
            <tr>
              <td width="120" align="left" nowrap="nowrap">席次表本発注締切日</td>
                <td width="10" align="left" nowrap="nowrap">：</td>
                <td width="270" nowrap="nowrap">
                	 披露宴日<?php if($_SESSION['user_type']=='333'){?>
                	 <?php 		if ($InputArea=="") {?> <input type="text" style="text-align:right" name="confirm_day_num" id="confirm_day_num" size="5" maxlength="2" onblur="isInteger('confirm_day_num')" value="<?=$confirm_day_num?>" />&nbsp;日前 <?php } else  echo $confirm_day_num." 日前"  ?>
                <?
				}else{
					?>
					<?=$confirm_day_num?>&nbsp;日前
					<?
					}
				?>
           	  </td>
            </tr>
    </table>

<td valign="middle" style=" vertical-align:middle; text-align:left; height:140px;">　　　　　　　&nbsp;&nbsp;
</td>

<br /><br /><br /><br />

<h2>席次表編集利用制限日</h2>
<table width="500" border="0" align="left" cellpadding="0" cellspacing="10" style="width:500px;">
            <tr>
              <td width="120" align="left" nowrap="nowrap">席次表編集利用制限日</td>
                <td width="10" align="left" nowrap="nowrap">：</td>
                <td width="270" nowrap="nowrap">
                	 披露宴日<?php if($_SESSION['user_type']=='333'){?>
                	 <?php 		if ($InputArea=="") {?>
               	     	<input type="text" style="text-align:right" name="limitation_ranking" id="limitation_ranking" size="5" maxlength="2" onblur="isInteger('limitation_ranking')" value="<?=$limitation_ranking?>" />&nbsp;日前 <?php } else	echo $limitation_ranking." 日前" ?>
                <?
				}else{
					?>
					<?=$limitation_ranking?>&nbsp;日前
					<?
					}
				?>
           	  </td>
            </tr>
        </table>

<td valign="middle" style=" vertical-align:middle; text-align:left; height:140px;">　　　　　　　&nbsp;&nbsp;
</td>

<br /><br /><br /><br />

<h2>引出物本発注締切日</h2>
<div style="float:left; width:800px;">
			 <input type="hidden" name="editUserGiftGroupsUpdate" value="editUserGiftGroupsUpdate">

<table width="500" border="0" align="left" cellpadding="0" cellspacing="10" style="width:500px;">
            <tr>
              <td width="120" align="left" nowrap="nowrap">引出物本発注締切日</td>
                <td width="10" align="left" nowrap="nowrap">：</td>
                <td width="270" nowrap="nowrap">
                	 披露宴日<?php if($_SESSION['user_type']==333){?>
                     <input name="order_deadline" <?=$ro?> type="text" style="text-align:right" onlyNumeric="i" id="order_deadline" size="5" value="<?=$order_deadline?>" />
			&nbsp;日前
                <?
				}else{
					?>
					<?=$order_deadline?>&nbsp;日前
					<?
					}
				?>
           	  </td>
            </tr>
        </table>
</div>

<td valign="middle" style=" vertical-align:middle; text-align:left; height:40px;">　　　　　　　&nbsp;&nbsp;
</td>

<br /><br /><br /><br />
<h2>お客様ID利用期限日</h2>
        <input type="hidden" name="DefaultSettings" value="DefaultSettings">


        <table width="500" border="0" align="left" cellpadding="0" cellspacing="10" style="width:500px;">
            <tr>
              <td width="120" align="left" nowrap="nowrap">お客様ID利用期限日</td>
                <td width="10" align="left" nowrap="nowrap">：</td>
                <td width="270" nowrap="nowrap">
                	 披露宴日<?php if($_SESSION['user_type']=='333'){?>
					<input name="user_id_limit" type="text";" style="text-align:right" size="5" id="user_id_limit" maxlength="2"  value="<?=$user_id_limit?>" /> 日後
					<? }else{?>
						<?=$user_id_limit?> 日後
					<? }?>
           	  </td>
            </tr>
        </table>


<br /><br /><br />


<table width="500" border="0" align="left" cellpadding="0" cellspacing="10" style="width:300px;">


		  <tr>
            <tr align="left">
              <td colspan="3" nowrap="nowrap">&nbsp;</td>
            </tr>
            <tr align="left">
           	  <td colspan="3" nowrap="nowrap">
			   <?php if($_SESSION['user_type']=='333'){?>
                	<!--<input type="button" onclick="valid_user();" value="保存" />-->
					<a href="#"><img width="82" height="22" alt="保存" onclick="valid_user();" src="img/common/btn_save.jpg">
					<? }?>

                </td>
            </tr>
      </table>
      </form>
  </div>
</div>

  <?php
	include_once('inc/left_nav.inc.php');
	include_once("inc/new.footer.inc.php");
?>
