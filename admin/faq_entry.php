<?php
	include_once("inc/dbcon.inc.php");
	
	include_once("inc/checklogin.inc.php");
	
	include_once("inc/header.inc.php");
	
	include_once("inc/class.dbo.php");
	$obj = new DBO();
	
	if($_POST['submit'])
	{
			if($_POST['question']||$_POST['answare'])
		{
			$post['key_word']=$_POST['keyword'];
			$post['question']=$_POST['question'];
			$post['answare']=$_POST['answare'];
			$post['creation_date'] = date("Y-m-d H:i:s");
			$post['display_order']= time();
			$lastid = $obj->InsertData('spssp_admin_faq',$post);
			redirect("faq.php?page=0");
			
		}
	}
?>
<script type="text/javascript">
function validForm()
{
	RE_EMAIL   = new RegExp(/^[A-Za-z0-9](([_|\.|\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([_|\.|\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})$/);
	var email1  = document.getElementById('email').value;
	if(!email1)
	{
		 alert("メールアドレスが未入力です");
		 document.getElementById('email').focus();
		 return false;
	}		
	else
	{

	 if(!RE_EMAIL.exec(email1))
	 {
		alert("正しいメールアドレスではありません");  
		document.getElementById('email').focus();
		return false;
	  }
	}
	document.stuff_form.submit();	
}
</script>
<div id="nav">
	<a href="manage.php" >Home</a>  &raquo; <a href=faq.php?page=<?=(int)$_GET['page']?>">Faq</a>  &raquo; New Faq
</div>
<h2> &nbsp;New Faq Entry</h2>

<?php if($err){echo "<h3 style='color:red;margin-left:50px;'>$err</h3>";}?>
<form action="faq_entry.php?page=<?=(int)$_GET['page']?>" method="post" name="msg_form">
	<table align="center" cellspacing="5" border="0">
    	<!--<tr>
			<td style="text-align:right;">Key Word</td>
			<td style="text-align:left;">
            	<input type="text" name="keyword" style="width:250px;"/>
            </td>
		</tr>-->
		<tr>
			<td style="text-align:right;">Question</td>
			<td style="text-align:left;">
            	<input type="text" name="question" style="width:660px;"/>
            </td>
		</tr>
        <tr>
        	<td style="text-align:right;">Answare</td>
			<td style="text-align:left;">
            	<textarea name="answare" cols="80" rows="5"></textarea>
            </td>
        </tr>
        
       
        <tr>
        	<td>&nbsp;</td>
            <td>
            	<input type="submit" name="submit" value="保存"/> &nbsp; <input type="button" value="リセット" />
            </td>
        </tr>
    </table>
</form>
<?php
	include_once("inc/footer.inc.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=shift_jis" />
<title>Untitled Document</title>
</head>

<body>
</body>
</html>
