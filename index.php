<?php
	include_once("inc/dbcon.inc.php");
	if($_SESSION['super_adminid']!="")
	{
		redirect("hotel.php");
	}

	$id=$_GET['adminid']; // UCHIDA EDIT 11/08/17 ＩＤを再表示

	if($_GET['action']=='failed')
	{
		echo '<script type="text/javascript"> alert("ログインIDかパスワードが間違っています。\\n正しいログインIDとパスワードを入力してください"); </script>';
		//redirect("index.php");
	}

	if(trim($_POST['adminid'])&&trim($_POST['adminpass']))
	{
		$query_string="SELECT * FROM super_spssp_admin WHERE BINARY username='".jp_encode($_POST['adminid'])."' AND BINARY password='".jp_encode($_POST['adminpass'])."' LIMIT 0,1;";

		$db_result=mysql_query($query_string);
		if(mysql_num_rows($db_result))
		{
			if($db_row=mysql_fetch_array($db_result))
			{

				$_SESSION['super_adminid']=$db_row['id'];
// UCHIDA EDIT 11/08/11
// UCHIDA EDIT 11/09/07
//				redirect("manage.php");
				redirect("hotel.php");
			}
			else
			{
				// UCHIDA EDIT 11/08/17 ＩＤを再表示
				$id=$_POST['adminid'];
				redirect("index.php?adminid=$id&action=failed");
			}
		}
		else
		{
			// UCHIDA EDIT 11/08/17 ＩＤを再表示
			$id=$_POST['adminid'];
			redirect("index.php?adminid=$id&action=failed");
		}
	}
	else
	{
		@session_destroy();
	}
?>
<html>
<head>
<META HTTP-EQUIV="Content-type" CONTENT="text/html; charset=UTF-8">
<script type="text/javascript">
var reg = /^[A-Za-z0-9]{1,32}$/; // UCHIDA EDIT 11/07/26
function login_admin()
{
	var adminid = document.getElementById('adminid').value;
	if(adminid =="") {
		alert("ログインIDが未入力です");
		document.getElementById('adminid').focus();
		return false;
	}
 	if(reg.test(adminid) == false) {
		alert("ログインIDは半角英数字で入力してください");
		document.getElementById('adminid').focus();
		return false;
	 }

	var adminpass = document.getElementById('adminpass').value;
	if(adminpass =="") {
		alert("パスワードが未入力です");
		document.getElementById('adminpass').focus();
		return false;
	}
	if(reg.test(adminpass) == false) {
		alert("パスワードは半角英数字で入力してください");
		document.getElementById('adminpass').focus();
		return false;
	}
	document.login_form.submit();
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
	background: #EAF5E9;
	border: 1px solid #B8DEB6;
	margin-bottom: 30px;
	text-align: left;
}

#login_BOX{
	margin:30px auto;
	width:680px;
}
#footer{
clear:both;
margin-left:auto;
margin-right:auto;
text-align:center;
width:1000px;
}

#foot_left {
	float: left;
	width: 330px;
	text-align: left;
}
#foot_right {
	float: right;
	width: 330px;
	text-align: left;
}
.clr {	clear: both;
}
.login
{
width:150px;
ime-mode: inactive; /* 半角モード UCHIDA EDIT 11/07/26 */
}
#text-indent {
	text-indent: 125px; /* SEKIDUKA ADD 11/08/12 */
}
#foot_left {
	float: left; /* SEKIDUKA ADD 11/08/12 */
	width: 269px;
	text-align: left;
}
#foot_center {
	float: left;
	width: 205px; /* SEKIDUKA EDIT 11/08/12 */
	text-align: left;
}
#foot_right {
	float: left;
	width: 205px; /* SEKIDUKA EDIT 11/08/12 */
	text-align: left;
}
</style>
<title>ログイン - ウエディングプラス</title>
</head>
  <div align="center">
    <div id="login_BOX">
      	<div><img src="../img/bar_pri.jpg" /></div>
      	<table id="logo">
      		<tr><td>
	        <img src="../img/logo_wp.jpg" width="269" height="77" />
	        </td></tr>
      		<tr><td>&nbsp;</td></tr>
      		<tr><td align="center" style="font-size:14;"><?php echo $weddingVersion; ?></td></tr>
		</table>

        <div id="login_area">こちらからログインしてください。<br />
<br />
<form action="index.php" name="login_form" method="post" id="login_form">

        <table width="100%" border="0" cellspacing="10" cellpadding="0">
          <tr>
            <td width="27%" align="left" nowrap="nowrap" style=" font-size:11px ">ログインID</td>
            <td width="73%" align="left" nowrap="nowrap"><label for="ログインID"></label>
            <input onKeyDown="if (event.keyCode == 13) { login_admin(); }" name="adminid" type="text" id="adminid" size="20" class="login"  value='<?php echo $id ?>' /></td> <!-- UCHIDA EDIT 11/08/17 ＩＤを再表示 -->
          </tr>
          <tr>
            <td align="left" nowrap="nowrap" style=" font-size:11px ">パスワード</td>
            <td align="left" nowrap="nowrap">
            <input onKeyDown="if (event.keyCode == 13) { login_admin(); }"name="adminpass" type="password" id="adminpass" size="20" class="login" /></td>
          </tr>
          <tr>
            <td align="right" nowrap="nowrap">&nbsp;</td>
            <td nowrap="nowrap">
            <input type="button" value="ログイン" onClick="login_admin()" /></td>
          </tr>
        </table>
      <div id="login_bt"></div>
	  </form></div><div class="clr"></div>
      <div><img src="../img/bar_recommended.jpg" /></div>

<!-- SEKIDUKA EDIT 11/08/12 SSLシール貼付 Start -->
<br/>
<div id="text-indent">当システムは下記OS、ブラウザを推奨しております。</div>
<br/>
<div id="foot_left">
<table width="269" border="0" cellpadding="2" cellspacing="0" title="SSLサーバ証明書導入の証 グローバルサインのサイトシール">
<tr>
<td width="269" align="center" valign="top"> <span id="ss_img_wrapper_115-57_image_ja">
<a href="http://jp.globalsign.com/" target="_blank"> <img alt="SSL グローバルサインのサイトシール" border="0" id="ss_jpn2_gif" src="//seal.globalsign.com/SiteSeal/images/gs_noscript_115-57_ja.gif">
</a>
</span><br>
<script type="text/javascript" src="//seal.globalsign.com/SiteSeal/gs_image_115-57_ja.js" defer="defer"></script> <a href="https://www.sslcerts.jp/" target="_blank" style="color:#000000; text-decoration:none; font:bold 12px 'ＭＳ ゴシック',sans-serif; letter-spacing:.5px; text-align:center; margin:0px; padding:0px;">SSLとは?</a>
</td>
</tr>
</table>
</div>
    <div id="foot_center"> <strong>●Windows XP ／ Vista ／ 7</strong><br />
      ・Internet Explorer ７～9<br />
・FireFox 7／8 <br />
・Google Chrome 14.0~ </div>
  <div id="foot_right"><strong>●Mac OS Ｘ 10.4~</strong><br />
    ・Safari 5.0~<br />
・FireFox 7／8</div>
	    <div class="clr"></div>
	    </div>
</div>
<!-- SEKIDUKA EDIT 11/08/12 SSLシール貼付 End -->

	<!-- UCHIDA EDIT 11/07/26 -->
	<script type="text/javascript"> document.login_form.adminid.focus(); </script>

<div align="center">
<table width="680" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><font size="2">Copyright (C) 株式会社サンプリンティングシステム ALL Rights reserved.</font></td>
  </tr>
</table>
</div>