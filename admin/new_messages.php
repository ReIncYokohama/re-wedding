<?php
	include_once("inc/dbcon.inc.php");
	
	include_once("inc/checklogin.inc.php");
	
	include_once("inc/header.inc.php");
	
	include_once("inc/class.dbo.php");
	$obj = new DBO();
	
	if($_POST{'submit'})
	{
		$post['title']=$_POST['title'];
		$post['description']=$_POST['description'];
		$post['creation_date'] = date("Y-m-d H:i:s");
		$post['display_order']= time();
		$lastid = $obj->InsertData('spssp_admin_messages',$post);
		redirect("messages.php?page=0");
		
	}
?>
<script type="text/javascript">
function validForm()
{
	RE_EMAIL   = new RegExp(/^[A-Za-z0-9](([_|\.|\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([_|\.|\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})$/);
	var email1  = document.getElementById('email').value;
	if(!email1)
	{
		 alert("[AhX͂ł");
		 document.getElementById('email').focus();
		 return false;
	}		
	else
	{

	 if(!RE_EMAIL.exec(email1))
	 {
		alert("[AhXł͂܂");  
		document.getElementById('email').focus();
		return false;
	  }
	}
	document.stuff_form.submit();	
}
</script>
<div id="nav">
	<a href="manage.php" >Home</a>  &raquo; <a href="messages.php?page=<?=(int)$_GET['page']?>">メッセージ</a>  &raquo; 新着メッセージ
</div>
<h2> &nbsp;新着メッセージ</h2>
<form action="new_messages.php?page=<?=(int)$_GET['page']?>" method="post" name="msg_form">
	<table align="center" cellspacing="5" border="0">
    	<tr>
			<td style="text-align:right;">タイトル</td>
			<td style="text-align:left;">
            	<input type="text" name="title" style="width:250px;"/>
            </td>
		</tr>
        <tr>
        	<td style="text-align:right;">本文</td>
			<td style="text-align:left;">
            	<textarea name="description" cols="70" rows="5"></textarea>
            </td>
        </tr>
        
       <!-- <tr>
        	<td style="text-align:right;">Field 1</td>
			<td style="text-align:left;">
            	<input type="text" name="field_1" style="width:250px;"/>
            </td>
        </tr>
        <tr>
        	<td style="text-align:right;">Field 2</td>
			<td style="text-align:left;">
            	<input type="text" name="field_2" style="width:250px;"/>
            </td>
        </tr>-->
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
