<?php
	include_once("admin/inc/dbcon.inc.php");
	include_once("admin/inc/class.dbo.php");
			$obj = new DBO();

	/*if($_SERVER['HTTPS']!="on")
	{
	 	redirect($Admin_site_url);
	}	*/

	//include_once("inc/new.header.inc.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ウエディングプラス</title>
<script src="js/jquery-1.4.2.js"></script>

<style>
body{
	font: normal 11px "メイリオ","Meiryo","ヒラギノ角ゴ Pro W3","Hiragino Kaku Gothic Pro","Osaka","Osaka－等幅", Osaka-mono, monospace,"ＭＳ Ｐゴシック","MS P Gothic", sans-serif;
	}


a{
color:#00C6FF;
text-decoration:underline;
}
.page{
/*color:#959595;*/
color:#000000;
/*font:12px/1.8em Arial,Helvetica,sans-serif;*/
}
.content{
width:1000px;
margin:0 auto;
height:400px;
}
#login_BOX{

margin:30px auto;
width:500px;
}
#login_midashi
{
background-color:#00C6FF;
color:#ffffff;
font-size:80%;
font-weight:bold;
}
#login_IDarea{
margin-left:auto;
margin-right:auto;
padding-bottom:20px;
padding-top:20px;
width:300px;
}

#login_BOX{
	margin:30px auto;
	width:680px;
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
}

#footer{
clear:both;
margin-left:auto;
margin-right:auto;
text-align:center;
width:1000px;
}
#text-indent {
	text-align:center;
	text-indent: 125px; /* SEKIDUKA ADD 11/08/12 */
}
#foot_left {
	float: left;
	width: 330px;
	vertical-align:top;
}
#foot_right {
	float: right;
	width: 330px;
}
#foot_right2 {
	float: right;
	width: 350px;
}

.err{
display:none;
text-align:center;
margin-bottom:20px;
background:#E1ECF7;
border:1px solid #3681CB;
padding:7px 10px;
color:red;
font-weight:bold;
font-size:13px;
}
.clr {	clear: both;
}

#foot_left {
	float: left; /* SEKIDUKA ADD 11/08/12 */
	width: 269px;
	text-align: left;
	vertical-align:top;
}
#foot_center {
	float: left;
	width: 205px; /* SEKIDUKA EDIT 11/08/12 */
	text-align: left;
	vertical-align:top;
}
#foot_right {
	float: left;
	width: 205px; /* SEKIDUKA EDIT 11/08/12 */
	text-align: left;
	vertical-align:top;
}
.clr {	clear: both;
}

/* ワイド、半角モード UCHIDA EDIT 11/07/26 */
.login
{
 width:150px;
ime-mode: inactive;
}
</style>
<script type="text/javascript">
//var reg = /^[A-Za-z0-9]{1,16}$/;
var reg = /^[A-Za-z0-9]{1,32}$/; // UCHIDA EDIT 11/07/26
function validForm()
{
	var userID  = document.getElementById('userID').value;
	var password  = document.getElementById('password').value;


	var flag = true;
	if(!userID)
	{
		 alert("ログインIDが未入力です");
		 document.getElementById('userID').focus();
		 return false;
	}
	if(reg.test(userID) == false) {
		alert("ログインIDは半角英数字で入力してください");
		document.getElementById('userID').focus();
		return false;
	 }
	if(!password)
	{

		 alert("パスワードが未入力です");
		 document.getElementById('password').focus();
		 return false;
	}
	if(reg.test(password) == false) {
		alert("パスワードは半角英数字で入力してください");
		document.getElementById('password').focus();
		return false;
	 }

	document.login_form.submit();
}
$(function(){

	var msg_html=$("#msg_rpt").html();

	if(msg_html!='')
	{
		$("#msg_rpt").fadeOut(5000);
	}
});


 var title=$("title");
 $(title).html("ログイン - ウエディングプラス");
</script>
</head>

<body>
	<div class="page">
	  <?php
        	if(isset($_GET['err']) && $_GET['err']!='')
			{
				echo '<script type="text/javascript"> alert("ログインIDかパスワードが間違っています。\\n正しいログインIDとパスワードを入力してください。"); </script>';
				$id=$_GET['adminid']; // UCHIDA EDIT 11/08/17 ＩＤを再表示
			}
		?>
		<div id="login_BOX">
			<div><img src="img/bar_user.jpg" /></div>
	      	<table id="logo">
	      		<tr><td>
		        <img src="../img/logo_wp.jpg" width="269" height="77" />
		        </td></tr>
	      		<tr><td>&nbsp;</td></tr>
	      		<tr><td align="center" style="font-size:18;"><?php echo $weddingVersion; ?></td></tr>
			</table>
            <div id="login_area">こちらからログインしてください。<br />
ログインID、パスワードを忘れた方は、ホテル担当者へお問い合わせください。<br />
<br />
<form action="login.php" method="post" name="login_form" id="login_form">
		<table cellspacing="10" cellpadding="0" width="100%">
						<tr>
							<td>ログインID</td>
<!-- UCHIDA EDIT 11/08/08 エンターキーを有効に設定 -->
							<td onkeydown="if (event.keyCode == 13) { validForm(); }" ><input type="text" id="userID" name="userID" class="login" value='<?php echo $id ?>' /></td>
					    </tr>
						<tr>
							<td>パスワード</td>
<!-- UCHIDA EDIT 11/08/08 エンターキーを有効に設定 -->
							<td onkeydown="if (event.keyCode == 13) { validForm(); }" ><input type="password" id="password" name="password" class="login" /></td>
					    </tr>
						<tr>
							<td>&nbsp;</td>
						  <td><input type="button" name="login" value="ログイン"  onclick="validForm();"/></td>
					    </tr>
	    </table>
</form>
	<a href="forgetPassword.php"><b>パスワードを忘れた場合はこちら</b></a><br /><br />
	※メールアドレスを未登録のお客様は、<br />　ホテル担当者へお問い合わせください。<br />
	</div>

	<div class="clr"></div>
	  <div><img src="img/bar_recommended.jpg" /></div><div>

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
    <div id="foot_center"><strong>●Windows XP ／ Vista ／ 7</strong></strong><br />
	      ・Internet Explorer 6.0以上<br />
	      ・FireFox 5.0以上 <br />
      ・Google Chrome 14.0以上</div>
  <div id="foot_right"><strong>●Mac OS Ｘ（10.4以上）</strong><br />

	      ・Safari 5.0以上<br />
	      ・FireFox 5.0以上 </div>
	    <div class="clr"></div>
	    </div>
</div>
<!-- SEKIDUKA EDIT 11/08/12 SSLシール貼付 End -->

	<!-- UCHIDA EDIT 11/07/26 -->
	<script type="text/javascript"> document.login_form.adminid.focus(); </script>

<?php
	include_once("inc/footer.inc.php");
?>