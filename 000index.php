<?php
	include_once("admin/inc/dbcon.inc.php");
	include_once("admin/inc/class.dbo.php");
			$obj = new DBO();

	if($_GET['action']=='failed')
	{
		echo '<script type="text/javascript"> alert("正しいユーザー名とパスワードを入力してください"); </script>';
		redirect("index.php");
	}

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
	margin-top: 120px;
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

#foot_left {
	float: left;
	width: 330px;
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
			}
		?>
		<div id="login_BOX">
			<div><img src="img/bar_user.jpg" /></div>
			<div id="logo"><img src="img/logo_wp.jpg" width="269" height="77" /></div>
            <div id="login_area">こちらからログインしてください。<br />
ログインID、パスワードを忘れた方は、ホテル担当者へお問い合わせください。<br />
<br />
<form action="login.php" method="post" name="login_form" id="login_form">
		<table cellspacing="10" cellpadding="0" width="100%">
						<tr>
							<td>ログインID</td>
<!-- UCHIDA EDIT 11/08/08 エンターキーを有効に設定 -->
							<td onkeydown="if (event.keyCode == 13) { validForm(); }" ><input type="text" id="userID" name="userID" class="login" /></td>
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
	※メールアドレスを登録されているお客様のみ、<br />　ご利用いただけます。<br />
	</div>

	<div class="clr"></div>
	  <div><img src="img/bar_recommended.jpg" /></div>
	  <div>
	    <p>当システムは下記OS、ブラウザを推奨しております。</p>
	    <div id="foot_left"> ●Windows XP ／ Vista ／ 7<br />
	      ・Internet Explorer 7／8<br />
	      ・FireFox 3.5／3.6／4.0 </div>
	    <div id="foot_right">●Mac OS Ｘ（10.4）<br />

	      ・Safari 3.0以上<br />
	      ・FireFox 3.5／3.3／4.0 </div>
	    <div class="clr"></div>
	    </div>
	  </div>
<div class="footer">
		  <div id="footer">
    		Copyright (C) 株式会社サンプリンティングシステム ALL Rights reserved.
  		 </div>
	  </div>
	<!-- UCHIDA EDIT 11/07/26 -->
	<script type="text/javascript"> document.login_form.userID.focus(); </script>
	</div>

</body>
</html>