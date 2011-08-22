<?php
	include_once("inc/dbcon.inc.php");
	if(trim($_POST['adminid'])&&trim($_POST['adminpass']))
	{
		$query_string="SELECT * FROM spssp_admin WHERE username='".jp_encode($_POST['adminid'])."' AND password='".jp_encode($_POST['adminpass'])."' AND sessionid='' LIMIT 0,1;";
		//echo $query_string;
		$db_result=mysql_query($query_string);
		if(mysql_num_rows($db_result))
		{
			if($db_row=mysql_fetch_array($db_result))
			{
				
				$_SESSION['adminid']=jp_decode($db_row['id']);
				$_SESSION['user_type'] = $db_row['permission'];
				
				$sql="update spssp_admin set sessionid='".session_id()."',logintime='".date("Y-m-d H:i:s")."', updatetime='".date("Y-m-d H:i:s")."' WHERE username='".jp_encode($_POST['adminid'])."';";
				mysql_query($sql);

				redirect("manage.php");
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
	
	include_once("inc/new.header.inc.php");
?>
<style>
.login
{
width:150px;
}
</style>
<div id="topnavi">
    <h1 >ログイン</h1>
</div>
<div align="center">
	<div id="login_BOX" style="padding:1px; width:520px;">
    	<div id="login_midashi">ウエディングプラス </div>
    	<div id="login_IDarea" style="width:520px; margin:auto;">
            <?
			if($_GET['action']=='failed')
	{
		echo '<script type="text/javascript"> alert("正しいユーザー名とパスワードを入力してください"); </script>';
		//redirect("index.php");
	}
			?>
<div style="width:520px; margin:auto">
			<form action="index.php" method="post">
                <table cellspacing="10" cellpadding="0">
                    <tr>
                        <td style="text-align:right; width:180px;">ログインID</td>
                        <td style="text-align:left;"><input type="text" name="adminid" class="login" /></td>
                    </tr>
                    <tr>
                        <td style="text-align:right;width:180px;">パスワード</td>
                        <td style="text-align:left;"><input type="password" name="adminpass" class="login" /></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td style="text-align:left;"><input type="submit" value="ログイン" /></td>
                    </tr>
                </table>
             </form>
			 </div>
<br /><br />
<div align="center">
ログインID、パスワードを忘れた方はホテル管理者へお問い合わせください。
</div>
<br />
<div align="center">
ーーーーー ご利用にあたって ーーーーー<br /><br />

当システムは下記ブラウザを推奨しております。<br />

●Windows XP ／ Vista ／ 7<br />
・Internet Explorer 7／8<br />
・FireFox 3.5／3.6／4.0<br /><br />

●Mac OS Ｘ（10.4）<br />
・Safari 3.0以上<br />
・FireFox 3.5／3.3／4.0<br />

</div>


        </div>
     </div>
</div>
<?php
	include_once("inc/new.indexfooter.inc.php");
?>
