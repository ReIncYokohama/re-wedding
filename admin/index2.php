<?php
	include_once("inc/dbcon.inc.php");
	
	if($_GET['action']=='failed')
	{
		echo '<script type="text/javascript"> alert("正しいユーザー名とパスワードを入力してください"); </script>';
		redirect("index.php");
	}
	
	
	
	
	if(trim($_POST['adminid'])&&trim($_POST['adminpass']))
		{
			$query_string="SELECT * FROM spssp_admin WHERE username='".jp_encode($_POST['adminid'])."' AND password='".jp_encode($_POST['adminpass'])."' LIMIT 0,1;";

			$db_result=mysql_query($query_string);
			if(mysql_num_rows($db_result))
				{
					if($db_row=mysql_fetch_array($db_result))
						{
							
							$_SESSION['adminid']=jp_decode($db_row['id']);
							$_SESSION['user_type'] = $db_row['permission'];

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
	

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>管理画面 | ログイン</title>
<link href="css/common.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
<!--<script type="text/javascript" src="js/jquery.rollover.js"></script>-->
<script type="text/javascript">
function confirmDelete(urls)
{
   	var agree = confirm("削除しても宜しいですか？");
	if(agree)
	{
		window.location = urls;
	}
}

function alert_staff()
{
	alert("You are not allowed to Make any change to this user");
	return false;
}

</script>
</head>

<body>
<div id="wrapper">
  <div id="header"> <a href="top.html"><img src="img/common/logo.jpg" width="226" height="50" /></a> </div>
  
<div id="topnavi">
    <h1>ログイン</h1>
</div>
<div align="center">
	<div id="login_BOX">
    	<div id="login_midashi">席次表システム　管理ページ </div>
    	<div id="login_IDarea">
            <form action="index.php" method="post">
                <table align="center" cellspacing="10" cellpadding="0" width="100%">
                    <tr>
                        <td>ログインID</td>
                        <td><input type="text" name="adminid" /></td>
                    </tr>
                    <tr>
                        <td>パスワード</td>
                        <td><input type="password" name="adminpass" /></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td><input type="submit" value="ログイン" /></td>
                    </tr>
                </table>
             </form>
        </div>
     </div>
</div>

<div id="footer">
<p>Copyright (C) 株式会社サンプリンティングシステム ALL Rights reserved.</p>
</div>
</div>
</body>
</html>