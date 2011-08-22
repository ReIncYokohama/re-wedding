<?php
	include_once("inc/dbcon.inc.php");
	if($_SESSION['super_adminid']!="")
	{
		redirect("manage.php");
	}
	
	if($_GET['action']=='failed')
	{
		echo '<script type="text/javascript"> alert("正しいユーザー名とパスワードを入力してください"); </script>';
		//redirect("index.php");
	}
	
	
	
	
	if(trim($_POST['adminid'])&&trim($_POST['adminpass']))
	{
		$query_string="SELECT * FROM gaiji_admin WHERE username='".jp_encode($_POST['adminid'])."' AND password='".jp_encode($_POST['adminpass'])."' LIMIT 0,1;";

		$db_result=mysql_query($query_string);
		if(mysql_num_rows($db_result))
		{
			if($db_row=mysql_fetch_array($db_result))
			{
				
				$_SESSION['super_adminid']=$db_row['id'];
				redirect("buso.php");
			}
			else
			{
				redirect("index.php?action=failed");
			}
		}
		else
		{
			redirect("index.php?action=failed");
		}
	}
	else
	{
		@session_destroy();
	}

	
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
}
</style>
<title>ログイン - ウエディングプラス</title>

  <div align="center">
    <div id="login_BOX">
      	<div><img src="../img/bar_pri.jpg" /></div>
        <div id="logo"><img src="../img/logo_wp.jpg" width="269" height="77" /></div>
        
        <div id="login_area">こちらからログインしてください。<br />
ログインID、パスワードを忘れた方は、ホテル管理者へお問い合わせください。<br />
<br />
<form action="index.php" name="slogin" method="post">
      
        <table width="100%" border="0" cellspacing="10" cellpadding="0">
          <tr>
            <td width="27%" align="left" nowrap="nowrap" style=" font-size:11px ">ログインID</td>
            <td width="73%" align="left" nowrap="nowrap"><label for="ログインID"></label>
            <input name="adminid" type="text" id="adminid" size="20" /></td>
          </tr>
          <tr>
            <td align="left" nowrap="nowrap" style=" font-size:11px ">パスワード</td>
            <td align="left" nowrap="nowrap"><input name="adminpass" type="password" id="adminpass" size="20" /></td>
          </tr>
          <tr>
            <td align="right" nowrap="nowrap">&nbsp;</td>
            <td nowrap="nowrap"><input type="submit" value="ログイン" /></td>
          </tr>
        </table>
      <div id="login_bt"></div>
	  </form></div><div class="clr"></div>
      <div><img src="../img/bar_recommended.jpg" /></div>
<div>
      <p align="left">当システムは下記OS、ブラウザを推奨しております。</p>
	    <div id="foot_left"> ●Windows XP ／ Vista ／ 7<br />
	      ・Internet Explorer 7／8<br />
	      ・FireFox 3.5／3.6／4.0 </div>
  <div id="foot_right">●Mac OS Ｘ（10.4）<br />
	      
	      ・Safari 3.0以上<br />
	      ・FireFox 3.5／3.3／4.0 </div>
	    <div class="clr"></div>
	    </div>

</div>

<?php
	include_once("inc/footer.inc.php");
?>
