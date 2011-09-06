<?php
	include_once("inc/dbcon.inc.php");
	
	include_once("inc/checklogin.inc.php");
	
	include_once("inc/header.inc.php");
	
	include_once("inc/class.dbo.php");
	
	$obj = new DBO();
	
	$id = (int)$_GET['id'];
	if($id > 0)
	{
		$row = $obj->GetSingleRow('spssp_admin_gift',' id='.$id);
	}
	
	if(trim($_POST['name']))
	{
		$post = $obj->protectXSS($_POST);
		
		
		if($id > 0)
		{
			$obj->UpdateData('spssp_admin_gift',$post," id=".$id);
		}
		else
		{
			$post['displayorder']=time();
			$lastid = $obj->InsertData('spssp_admin_gift',$post);
		}
		
		redirect("gift.php?page=".(int)$_GET['page']);
		
	}
?>
<link href="./calendar/cwcalendar.css" rel="stylesheet" type="text/css" media="all"/>
<script type="text/javascript">
function validForm()
{
	
	var name  = document.getElementById('name').value;

	var flag = true;
	if(!name)
	{
		 alert("メールアドレスが未入力です");
		 document.getElementById('name').focus();
		 return false;
	}

	if(flag == true)
	{	
		document.respect_form.submit();
	}	
}

</script>

<div id="nav">
	<a href="manage.php" >Home</a>  &raquo; <a href="gift.php?page=<?=(int)$_GET['page']?>">Gift</a>  &raquo; New Gift
</div>
<h2> &nbsp;Create New Gift</h2>
<form action="gift_update.php?page=<?=(int)$_GET['page']?>&id=<?=(int)$_GET['id']?>" method="post" name="respect_form">
	<table align="center" cellspacing="5" border="0">
    	<tr>
			<td style="text-align:right;"></td>
			<td style="text-align:left;">
            	<input type="text" name="name" style="width:250px;" id="name" value="<?=$row['name']?>"/>
            </td>
		</tr>
        <tr>
        	<td>&nbsp;</td>
            <td>
            	<input type="button"  value="保存" onclick="validForm();" /> &nbsp; <input type="button" value="リセット" />
            </td>
        </tr>
    </table>
</form>
<?php
	include_once("inc/footer.inc.php");
?>
