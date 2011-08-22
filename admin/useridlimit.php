<?php
	include_once("inc/dbcon.inc.php");
	include_once("inc/checklogin.inc.php");
	include_once("inc/new.header.inc.php");
	require_once("inc/class.dbo.php");

	$obj = new DBO();
	$post = $obj->protectXSS($_POST);
	if($post['DefaultSettings']=="DefaultSettings")
	{

		unset($post['DefaultSettings']);
		unset($value['option_value']);
		if($post['useridlimit'] <100)
		{
			$value['option_value'] = $post['useridlimit']; // UCHIDA EDIT 11/08/01 スペースを削除
			$res = $obj->UpdateData("spssp_options", $value," option_name='user_id_limit'");
			if($res ==1){
				// $msg = 4;
				echo "<script> alert('お客様ID利用期限日が更新されました'); </script>";
			}
		}
		else
		{
			echo '<script>alert("User limit less then 100.");</script>';
		}

	}

	//$data_rows = $obj->getRowsByQuery($query_string);

    $user_id_limit = $obj->GetSingleData("spssp_options" ,"option_value" ," option_name='user_id_limit'");

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
var lim = document.getElementById("useridlimit").value;
	if(document.getElementById("useridlimit").value=='')
	{
		alert("お客様ID利用期限日を正しく入力してください");
		document.getElementById('useridlimit').focus();
		return false;
	}

// UCHIDA EDIT 11/08/01
	if (lim.match( /[^0-9\s]+/ ) ) {
		alert("お客様ID利用期限日には半角数字で入力してください");
		document.getElementById('useridlimit').focus();
		document.user_form_register.useridlimit.value = crnt;
//		$('#useridlimit').val(crnt);
		return false;
	}
	document.user_form_register.submit();
}
</script>


<div id="topnavi">
<?php
include("inc/main_dbcon.inc.php");
$hcode="0001";
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
    	<h2><div style="width:300px;"> お客様ID利用期限日 </div></h2>
		<h2><div style="width:300px;">お客様ID利用期限日</div></h2>
<!--        <?php $user_id_limit = $obj->GetSingleData("spssp_options" ,"option_value" ," option_name='user_id_limit'");?> -->
        <form action="useridlimit.php" method="post" name="user_form_register">
        <input type="hidden" name="DefaultSettings" value="DefaultSettings">
		<table style="width:300px;" border="0" cellspacing="10" cellpadding="0" align="left">
            <tr>
                <td width="120" align="right" nowrap="nowrap">お客様ID利用期限日：</td>
                <td width="100" nowrap="nowrap">
                	<?php if($_SESSION['user_type']=='111' OR $_SESSION['user_type']=='333'){?>
					<input name="useridlimit" type="text" style="width:40px;" id="useridlimit" maxlength="2"  value="<?=$user_id_limit?>" /> 日後
					<? }else{?>
						<?=$user_id_limit?> 日後
					<? }?>
           	  </td>
            </tr>

            <tr>
           	  <td colspan="2" align="center">
			   <?php if($_SESSION['user_type']=='111' OR $_SESSION['user_type']=='333'){?>
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
