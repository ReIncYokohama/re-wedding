<?php
@session_start();
include_once("../admin/inc/dbcon.inc.php");
include_once(dirname(__FILE__)."/../../madmin/conf/version.php");

	$userID = $_POST['userID'];
	$password = $_POST['password'];

	$id=$_GET['userID'];
	if($_GET['action']=='failed') {
		echo '<script type="text/javascript"> alert("ログインIDかパスワードが間違っています。\\n正しいログインIDとパスワードを入力してください"); </script>';
	}
	if($_GET['action']=='noEntry') {
		echo '<script type="text/javascript"> alert("現在、ログインはいただけません。\\nホテルから発注依頼があった場合のみログインいただけます。"); </script>';
	}

	if(isset($_POST['sub'])) {
	$query_string = "SELECT * from spssp_printing_comapny WHERE BINARY email= '".$userID."' and number = '".$password."'";

	$result = mysql_query( $query_string );
	$row = mysql_fetch_array($result);

		if($row['id'])
		{
			$_SESSION['contact_name'] = $row['contact_name'];
			$_SESSION['company_name'] = $row['company_name'];
			$_SESSION['email'] = $row['email'];
			$_SESSION['printid'] = $row['id'];


			if($_POST['page']!="")
			{
				redirect($_POST['page'].".php?user_id=".$_POST['user_id']);
			}
			else
			{
				redirect("index.php?userID=$userID&action=noEntry");
			}
		}else{
			redirect("index.php?userID=$userID&action=failed");
		}
	}

?>

<script type="text/javascript">
var reg = /^[A-Za-z0-9]{1,32}$/; // UCHIDA EDIT 11/07/26
function login_admin()
{
	var adminid = document.getElementById('userID').value;
	if(adminid =="") {
		alert("ログインIDが未入力です");
		document.getElementById('userID').focus();
		return false;
	}
 	if(email_validate(adminid) == false) {
		alert("ログインIDを入力してください");
		document.getElementById('userID').focus();
		return false;
	 }

	var adminpass = document.getElementById('password').value;
	if(adminpass =="") {
		alert("パスワードが未入力です");
		document.getElementById('password').focus();
		return false;
	}
	if(reg.test(adminpass) == false) {
		alert("パスワードは半角英数字で入力してください");
		document.getElementById('password').focus();
		return false;
	}
	document.loginform.submit();
}
function email_validate(email) {
   var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
   if(reg.test(email) == false) {
       return false;
   }
   else
   {
   		return true;
   }
}
</script>

<style>
body{
	font: normal 11px "メイリオ","Meiryo","ヒラギノ角ゴ Pro W3","Hiragino Kaku Gothic Pro","Osaka","Osaka－等幅", Osaka-mono, monospace,"ＭＳ Ｐゴシック","MS P Gothic", sans-serif;
	}
#logo {
	float: left;
	margin-top: 80px;
}
#login_area {
	float: right;
	width: 310px;
	margin-left: 30px;
	margin-top: 50px;
	padding: 20px;
	background: #FCFAF3;
	border: 1px solid #CCC;
	margin-bottom: 30px;
	text-align: left;
}

#login_BOX{
	margin:30px auto;
	width:680px;
}
#footer{
clear:both;
margin-left:200px;
margin-right:auto;
text-align:center;
width:1000px;
}

#text-indent {
	text-indent: 125px; /* SEKIDUKA ADD 11/08/12 */
}

.clr {	clear: both;
}

.login
{
width:150px;
ime-mode: inactive; /* 半角モード UCHIDA EDIT 11/07/26 */
}
</style>

<title>ログイン - 印刷会社様画面 - ウエディングプラス</title>
  <div align="center">
    <div id="login_BOX">
      	<div><img src="img/common/bar_print.jpg" /></div>
      	<table id="logo">
      		<tr><td>
	        <img src="../img/logo_wp.jpg" width="269" height="77" />
	        </td></tr>
      		<tr><td>&nbsp;</td></tr>
      		<tr><td align="center" style="font-size:14;"><?php echo $weddingVersion; ?></td></tr>
		</table>

        <div id="login_area">こちらからログインしてください。<br /><br />
        <form name="loginform" method="post" action="index.php">
		<input type="hidden" name="user_id" value="<?=$_GET['user_id']?>"/>
		<input type="hidden" name="page" value="<?=$_GET['page']?>"/>
	    <div id="login_IDarea">
		<table width="100%" border="0" cellspacing="10" cellpadding="0">

		  <tr>
            <td width="20%" align="left" nowrap="nowrap" style="font-size:12px;">ログインID</td>
            <td width=" 5%" align="left" nowrap="nowrap" style="font-size:12px;">：</td>
            <td width="75%" nowrap="nowrap"><label for="ログインID"></label>
            <input onkeydown="if (event.keyCode == 13) { login_admin(); }" name="userID" type="text" id="userID" size="20"  value='<?php echo $id ?>'/></td>
          </tr>
          <tr>
            <td align="left" nowrap="nowrap" style="font-size:12px;">パスワード</td>
            <td align="left" nowrap="nowrap" style="font-size:12px;">：</td>
            <td nowrap="nowrap">
            <input onkeydown="if (event.keyCode == 13) { login_admin(); }" name="password" type="password" id="password" size="20" /></td>
          </tr>
        </table>
      <div id="login_bt" style="margin-left: 104px;"><a href="javascript:void(0);" onclick="login_admin();"><img src="img/common/btn_login.jpg" alt="ログイン" width="152" height="22" /></a></div>
	  <input type="hidden" name="sub" value="1" />
      </div>
	  </form>
</div><div class="clr"></div>
<div><img src="../img/bar_recommended.jpg" /></div>
		<!-- UCHIDA EDIT 11/07/26 -->
		<script type="text/javascript"> document.loginform.userID.focus(); </script>
<div>

<!-- SEKIDUKA EDIT 11/08/12 SSLシール貼付 Start -->
<br/>
<div id="text-indent">当システムは下記OS、ブラウザを推奨しております。</div>
<br/>
<div style="width:500px">
<table border="0" cellpadding="2" cellspacing="0" align="center" width="500" title="SSLサーバ証明書導入の証 グローバルサインのサイトシール">
<tr>
<td width="120" align="center" valign="top"> <span id="ss_img_wrapper_115-57_image_ja">
<a href="http://jp.globalsign.com/" target="_blank"> <img alt="SSL グローバルサインのサイトシール" border="0" id="ss_jpn2_gif" src="//seal.globalsign.com/SiteSeal/images/gs_noscript_115-57_ja.gif">
</a>
</span><br />
<script type="text/javascript" src="//seal.globalsign.com/SiteSeal/gs_image_115-57_ja.js" defer="defer"></script> <a href="https://www.sslcerts.jp/" target="_blank" style="color:#000000; text-decoration:none; font:bold 12px 'ＭＳ ゴシック',sans-serif; letter-spacing:.5px; text-align:center; margin:0px; padding:0px;">SSLとは?</a>
</td>
<td width="120" style="font-size:12px;">
●Windows XP ／ Vista ／ 7<br />
・Internet Explorer 7／8<br />
・FireFox 3.5／3.6／4.0
</td>
<td width="120" style="font-size:12px;">
●Mac OS Ｘ（10.4）<br />
・Safari 3.0以上<br />
・FireFox 3.5／3.6／4.0
</td>
</tr>
</table>
</div>
</div>
</div>
</div>
<div class="clr"></div>
  <div id="footer" style="width:1000px">
    <p>Copyright (C) 株式会社サンプリンティングシステム ALL Rights reserved.</p>
  </div>

