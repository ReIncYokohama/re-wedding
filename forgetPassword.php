<?php
	include_once("admin/inc/dbcon.inc.php");
	include_once("admin/inc/class.dbo.php");

	$obj = new DBO();

	if($_POST['getForm']=='getForm')
	{
		if($_POST['email']=="")
		{
			$msg = '<script type="text/javascript"> alert("メールアドレスが未入力です"); </script>';
		}
		else
		{
			$dataRow = $obj->GetSingleRow("spssp_user"," 	mail = '".$_POST['email']."'");
			if($dataRow['mail'] == "")
			{
				$msg = '<script type="text/javascript"> alert("メールアドレスが違います"); </script>';
			}
			else
			{

$mailbody = <<<html
=====このメールは-自動配信メールです。======

{$dataRow['man_lastname']} {$dataRow['man_firstname']} 様
ウエディングプラスのマイページにログインするための
パスワードを送信しました

=================
パスワード :　{$dataRow['password']}
=================

Wedding Plus

html;
				$msg = forgetPassword_mail($dataRow['mail'],$mailbody);
				if($msg==1)
				{
					$msg = '<script type="text/javascript"> alert("パスワードを送信しました"); </script>';
				}
			}
		}
		//redirect("index.php");
	}

	//include_once("inc/new.header.inc.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>パスワード送信 - ウエディングプラス</title>
<script src="js/jquery-1.4.2.js"></script>

<style>
a{
color:#00C6FF;
text-decoration:underline;
}
.page{
/*color:#959595;*/
color:#000000;
font:12px/1.8em Arial,Helvetica,sans-serif;
}
.content{
width:1000px;
margin:0 auto;
height:400px;
}
#login_BOX{
border:1px solid #00C6FF;
margin:10px auto;
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
#footer{
clear:both;
margin-left:auto;
margin-right:auto;
text-align:center;
width:1000px;
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
</style>
<script type="text/javascript">
function validForm()
{
	var email  = document.getElementById('email').value;


	var flag = true;
	if(!email)
	{

		 alert("メールアドレスが未入力です");
		 document.getElementById('email').focus();
		 return false;
	}

	document.passwordForgetForm.submit();
}

</script>
</head>

<body>
	<div class="page">
		<div class="header">
			<div style="width:1000px;margin:0 auto;"> <img src="img/logo.jpg" width="200" height="57" border="0" />
		  </div>
			<div style="border-bottom: 8px solid #00C6FF;"></div>
		</div>

			<div align="center">
				<div id="login_BOX" style="padding:1px; width:520px;">
					<div id="login_midashi">ウエディングプラス</div>
					<div id="login_IDarea">
						<form action="forgetPassword.php" method="post" name="passwordForgetForm">
							<table style="font-size:12px;" width="400" align="center" cellspacing="10" cellpadding="0" border="0">
								<tr>
									<td colspan="2">パスワードを忘れ場合は、下記のフォームにメールアドレスを入力の上、<br />送信ボタンを押下してください<br />登録されているメールアドレスと照合の上、パスワードを送信いたします<br/></td>
								</tr>
								<tr>
									<td width="30%">メールアドレス</td>
									<td width="70%"><input type="text" id="email" size="30px;" name="email" /></td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td><input type="button" name="forgetpass" value="送信"  onclick="validForm();"/></td>
								</tr>
							</table>
							<input type="hidden" name="getForm" value="getForm">
						 </form>
					</div>
					<div  style="width:350px;"><a href="index.php"><b>戻る</b></a></div>
				</div>
			</div>

		<div class="footer">
		<div style="border-bottom: 8px solid #00C6FF;"></div>
		<div id="footer">
    		Copyright (C) 株式会社サンプリンティングシステム ALL Rights reserved.
  		 </div>
		</div>
		<script type="text/javascript"> document.passwordForgetForm.email.focus(); </script>
	</div>
</body>
</html>

<?php if($msg!=""){echo $msg;}?>
