<?php
@session_start();
include_once("../admin/inc/dbcon.inc.php");

	$userID = $_POST['userID'];
	$password = $_POST['password'];

if(isset($_POST['sub']))
{
	$query_string = "SELECT * from spssp_printing_comapny WHERE email= '".$userID."' and number = '".$password."'";

	$result = mysql_query( $query_string );
	$row = mysql_fetch_array($result);
$message='';



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
			redirect("list.php");
		}
	}else{
		$message=1;
	}
}

?>

<style>
body{
	font: normal 11px "メイリオ","Meiryo","ヒラギノ角ゴ Pro W3","Hiragino Kaku Gothic Pro","Osaka","Osaka－等幅", Osaka-mono, monospace,"ＭＳ Ｐゴシック","MS P Gothic", sans-serif;
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
.clr {	clear: both;
}

.login
{
width:150px;
ime-mode: inactive; /* 半角モード UCHIDA EDIT 11/07/26 */
}
</style>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>ログイン - 印刷会社様画面 - ウエディングプラス</title>
<link href="css/common.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="js/jquery.rollover.js"></script>
</head>

<body>
<div id="wrapper">
  <div id="header"> <a href="top.html"><img src="img/common/logo.jpg" width="246" height="43" /></a> </div>
  <div id="topnavi">
    <h1>ログイン</h1>
  </div>
  <div align="center">
    <div id="login_BOX">
      <div id="login_midashi">管理ページ</div>

         <form name="loginform" method="post" action="index.php">
		 <input type="hidden" name="user_id" value="<?=$_GET['user_id']?>"/>
		 <input type="hidden" name="page" value="<?=$_GET['page']?>"/>
	  <div id="login_IDarea">
         <? if($message ==1){?>
		   <table width="100%" border="0" cellspacing="0" cellpadding="2">
		  <tr>

            <td width="100%" align="center" ><font color="#FF0000"> User name or password wrong</font></td>

          </tr>
		  </table>
		  <? }?>
		<table width="100%" border="0" cellspacing="10" cellpadding="0">


		  <tr>
            <td width="30%" align="right" nowrap="nowrap">ログインID：</td>
            <td width="70%" nowrap="nowrap"><label for="ログインID"></label>
            <input name="userID" type="text" id="userID" size="20" /></td>
          </tr>
          <tr>
            <td align="right" nowrap="nowrap">パスワード：</td>
            <td nowrap="nowrap"><input name="password" type="password" id="password" size="20" /></td>
          </tr>
        </table>
      </div>
      <div id="login_bt"><a href="Javascript:document.loginform.submit();"><img src="img/common/btn_login.jpg" alt="ログイン" width="152" height="22" /></a></div>
	  <input type="hidden" name="sub" value="1" />
	  </form>
</div><div class="clr"></div>
<div><img src="../img/bar_recommended.jpg" /></div><div>

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
    <div id="foot_center"> ●Windows XP ／ Vista ／ 7<br />
	      ・Internet Explorer 7／8<br />
	      ・FireFox 3.5／3.6／4.0 </div>
  <div id="foot_right">●Mac OS Ｘ（10.4）<br />

	      ・Safari 3.0以上<br />
	      ・FireFox 3.5／3.6／4.0 </div>
	    <div class="clr"></div>
	    </div>
		<!-- UCHIDA EDIT 11/07/26 -->
		<script type="text/javascript"> document.login_form.adminid.focus(); </script>
</div>
<!-- SEKIDUKA EDIT 11/08/12 SSLシール貼付 End -->    </div>
  </div>
<div class="clr"></div>
  <div id="footer">
    <p>Copyright (C) 株式会社サンプリンティングシステム ALL Rights reserved.</p>
  </div>
</div>
</body>
</html>
