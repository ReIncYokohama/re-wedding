<?php
	include_once("inc/dbcon.inc.php");
	include_once("inc/class.dbo.php");
	
	$obj = new DBO();
	
	if($_POST['getForm']=='getForm')
	{
		if($_POST['email']=="")
		{
			$msg = '<script type="text/javascript"> alert("メールアドレスが未入力です"); </script>';
		}
		else
		{
			/*$dataRow = $obj->GetSingleRow("spssp_user"," 	mail = '".$_POST['email']."'");
			if($dataRow['mail'] == "")
			{
				$msg = '<script type="text/javascript"> alert("電子メールが見つかりませんでした"); </script>';
			}*/
			//else
			//{
				
$eaddress=$_POST['email'];
$todate=date('Y-m-d');
$mailbody = <<<html
=====このメールは-自動配信メールです。======

I forgot My password
Please give me password
{$eaddress}
Thank You.
Date:{$todate}
On Regards

html;
				$msg = hotelPassword_mail($eaddress,$mailbody);
				if($msg==1)
				{
					$msg = '<script type="text/javascript"> alert("エラー..."); </script>';
				}else{
			        redirect("index.php");		
					}
			}
		}
		//redirect("index.php");
//	}
		

	include_once("inc/new.header.inc.php");
?>
<script src="js/jquery-1.4.2.js"></script>
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

<style>
.login
{
width:150px;
}
</style>


<div id="topnavi">
    <h1>ログイン</h1>
</div>

<div align="center">
	<div id="login_BOX" style="padding:1px; width:520px;">
    	<div id="login_midashi">席次表システム　管理ページ </div>
    	<div id="login_IDarea" style="width:350px;">
						<form action="hotelpassword.php" method="post" name="passwordForgetForm">
							<input type="hidden" name="getForm" value="getForm">
                <table align="center"  cellspacing="10" cellpadding="0" width="100%">
                    <tr>
                        <td align="right">メールアドレス</td>
                        <td><input type="text" name="email" id="email"  class="login" /></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td><input type="button" name="forgetpass" value="送信"  onclick="validForm();"/>
                        &nbsp;&nbsp;
                        <a href="index.php"><b>戻る</b></a>
                        </td>
                    </tr>

                </table>
             </form>


        </div>
     </div>
</div>
<?php
	include_once("inc/new.footer.inc.php");
?>
