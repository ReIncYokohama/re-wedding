<?php
include_once("inc/dbcon.inc.php");

$id=$_GET['adminid']; // UCHIDA EDIT 11/08/17 ＩＤを再表示
$adminid = $_POST["adminid"];
$adminpass = $_POST["adminpass"];

if(trim($_POST['adminid'])&&trim($_POST['adminpass']))
	{
		$query_string="SELECT * FROM spssp_admin WHERE BINARY username='".jp_encode($_POST['adminid'])."' AND BINARY password='".jp_encode($_POST['adminpass'])."' AND sessionid='' LIMIT 0,1;";
		//echo $query_string;
		$db_result=mysql_query($query_string);
        $_SESSION["super_user"] = false;

    if(!mysql_num_rows($db_result)){
      mysql_connected($main_sqlhost,$main_sqluser,$main_sqlpassword,$main_sqldatabase);
      $query_string = "SELECT * from super_spssp_admin WHERE BINARY username= '".$adminid."' and BINARY password = '".$adminpass."'";
      $db_result = mysql_query($query_string);
      mysql_connected($hotel_sqlhost,$hotel_sqluser,$hotel_sqlpassword,$hotel_sqldatabase);
      if(mysql_num_rows($db_result)){
        $_SESSION["super_user"] = true;
      }
    }
		if(mysql_num_rows($db_result))
		{
			if($db_row=mysql_fetch_array($db_result))
			{

				$_SESSION['adminid']=jp_decode($db_row['id']);
				$_SESSION['user_type'] = $db_row['permission'];
		        if($_SESSION["super_user"]){
		          $_SESSION["user_type"] = 333;
		        }
		        if ($_SESSION["user_type"] == 333) {
			        include_once("inc/staff_login_check.php");
		        }
			//$sql="update spssp_admin set sessionid='".session_id()."',logintime='".date("Y-m-d H:i:s")."', updatetime='".date("Y-m-d H:i:s")."' WHERE username='".jp_encode($_POST['adminid'])."';";

        	if ($_SESSION["super_user"]!=true) {
				$sql="update spssp_admin set logintime='".date("Y-m-d H:i:s")."', updatetime='".date("Y-m-d H:i:s")."' WHERE username='".jp_encode($_POST['adminid'])."';";
				mysql_query($sql);
        	}
//       	if (isset($_SESSION['regenerate_id'])) {
	        	$_SESSION["hotel_id"] =$HOTELID;
				redirect("manage.php");
//        	}
		}
		else
		{
			// UCHIDA EDIT 11/08/17 ＩＤを再表示
			$id=$_POST['adminid'];
			redirect("index.php?adminid=$id&action=failed");			}
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
//	$id=$_POST['adminid'];
//	redirect("index.php?adminid=$id&action=failed");
}

//	include_once("inc/new.header.inc.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<script src="../js/jquery-1.4.2.js"></script>
<script language="javascript" type="text/javascript">
//var reg = /^[A-Za-z0-9]{1,16}$/;
var reg = /^[A-Za-z0-9]{1,32}$/; // UCHIDA EDIT 11/07/26
function login_admin()
{
	var adminid = $("#adminid").val();
	if(adminid =="")
	{
		alert("ログインIDが未入力です");
		document.getElementById('adminid').focus();
		return false;
	}
 	if(reg.test(adminid) == false) {
		alert("ログインIDは半角英数字で入力してください");
		document.getElementById('adminid').focus();
		return false;
	 }

	var adminpass = $("#adminpass").val();
	if(adminpass =="")
	{
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

<title>ログイン - ウエディングプラス</title>
</head>
<body>
<div align="center">
<div id="login_BOX">
   	<div><img src="../img/bar_wm.jpg" /></div>
      	<table id="logo">
      		<tr><td>
	        <img src="../img/logo_wp.jpg" width="269" height="77" />
	        </td></tr>
      		<tr><td>&nbsp;</td></tr>
      		<tr><td align="center" style="font-size:14;"><?php echo $weddingVersion; ?></td></tr>
		</table>
	<div id="login_area">
  <?
			if($_GET['action']=='failed')
	{
		echo '<script type="text/javascript"> alert("ログインIDかパスワードが間違っています。\\n正しいログインIDとパスワードを入力してください"); </script>';
		//redirect("index.php");
	}

	?>こちらからログインしてください。<br />
ログインID、パスワードを忘れた方は、ホテル管理者へお問い合わせください。<br />
<br />
  <form action="index.php" method="post" name="login_form" id="login_form">
    <table width="100%" cellpadding="0" cellspacing="10">
      <tr>
        <td width="27%" style=" font-size:11px ">ログインID</td>
<!-- UCHIDA EDIT 11/08/08 エンターキーを有効に設定 -->
        <td width="73%" style="text-align:left;" onkeydown="if (event.keyCode == 13) { login_admin(); }" ><input type="text" name="adminid" id="adminid" class="login" value='<?php echo $id ?>' /></td>
      </tr>
      <tr>
        <td style=" font-size:11px">パスワード</td>
<!-- UCHIDA EDIT 11/08/08 エンターキーを有効に設定 -->
        <td style="text-align:left;" onkeydown="if (event.keyCode == 13) { login_admin(); }" ><input type="password" name="adminpass" id="adminpass" class="login"  /></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td style="text-align:left;"><input type="button" value="ログイン" onclick="login_admin()" /></td>
      </tr>
    </table>
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
<!-- SEKIDUKA EDIT 11/08/12 SSLシール貼付 End -->


</div>
</body>
</html>
