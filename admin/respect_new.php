<?php
	include_once("inc/dbcon.inc.php");
	
	include_once("inc/checklogin.inc.php");
	
	include_once("inc/header.inc.php");
	
	include_once("inc/class.dbo.php");
	
	$obj = new DBO();
	
	$id = (int)$_GET['id'];
	if($id > 0)
	{
		$row = $obj->GetSingleRow('spssp_respect',' id='.$id);
	}
	
	if(trim($_POST['title']))
	{
		$post = $obj->protectXSS($_POST);
		if($id > 0)
		{
			$obj->UpdateData('spssp_respect',$post," id=".$id);
		}
		else
		{
			$lastid = $obj->InsertData('spssp_respect',$post);
		}
		
		redirect("respects.php?page=".(int)$_GET['page']);
		
	}
?>
<link href="./calendar/cwcalendar.css" rel="stylesheet" type="text/css" media="all"/>
<script type="text/javascript">
function validForm()
{
	
	var title  = document.getElementById('title').value;

	var flag = true;
	if(!title)
	{
		 alert("メールアドレスが未入力です");
		 document.getElementById('title').focus();
		 return false;
	}

	if(flag == true)
	{	
		document.respect_form.submit();
	}	
}

</script>

<div id="nav">
	<a href="manage.php" >Home</a>  &raquo; <a href="respects.php?page=<?=(int)$_GET['page']?>">Respects</a>  &raquo; New Respect
</div>
<h2> &nbsp;Create New Stuff</h2>
<form action="respect_new.php?page=<?=(int)$_GET['page']?>&id=<?=(int)$_GET['id']?>" method="post" name="respect_form">
	<table align="center" cellspacing="5" border="0">
    	<tr>
			<td style="text-align:right;">Respect タイトル</td>
			<td style="text-align:left;">
            	<input type="text" name="title" style="width:250px;" id="title" value="<?=$row['title']?>"/>
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

