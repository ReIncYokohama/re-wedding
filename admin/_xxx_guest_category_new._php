<?php
	include_once("inc/dbcon.inc.php");
	
	include_once("inc/checklogin.inc.php");
	
	include_once("inc/header.inc.php");
	
	include_once("inc/class.dbo.php");
	
	$obj = new DBO();
	
	$id = (int)$_GET['id'];
	$table = "spssp_guest_category";
	if($id > 0)
	{
		$row = $obj->GetSingleRow($table,' id='.$id);
	}
	
	if(trim($_POST['name']))
	{
		$post = $obj->protectXSS($_POST);
		$post['display_order']= time();
		$post['creation_date'] = date("Y-m-d H:i:s");
		
		if($id > 0)
		{
			$obj->UpdateData($table,$post," id=".$id);
		}
		else
		{
			$lastid = $obj->InsertData($table,$post);
		}
		
		redirect("guest_categories.php?page=".(int)$_GET['page']);
		
	}
?>
<link href="./calendar/cwcalendar.css" rel="stylesheet" type="text/css" media="all"/>
<script type="text/javascript">
function validForm()
{
	
	var name  = document.getElementById('name').value;
	var description  = document.getElementById('description').value;

	var flag = true;
	if(!name)
	{
		 alert("お名前が未入力です");
		 document.getElementById('name').focus();
		 flag=false;
	}
	/*if(!description)
	{
		 alert("メールアドレスが未入力です");
		 document.getElementById('description').focus();
		 flag=false;
	}*/

	if(flag == true)
	{	
		document.guest_category_form.submit();
	}	
}

</script>

<div id="nav">
	<a href="manage.php" >Home</a>  &raquo; <a href="guest_categories.php?page=<?=(int)$_GET['page']?>">招待者区分</a>  &raquo;
    <?php
    	if((int)$_GET['id'] > 0)
		{
			echo "区分編集";
		}
		else
		{			
			echo "区分新規";
		}
	?>

</div><br />


<form action="guest_category_new.php?page=<?=(int)$_GET['page']?>&id=<?=(int)$_GET['id']?>" method="post" name="guest_category_form">
	<table align="center" cellspacing="5" border="0">
    	<tr>
			<td style="text-align:right;">区分名前</td>
			<td style="text-align:left;">
            	<input type="text" name="name" style="width:250px;" id="name" value="<?=$row['name']?>"/>
            </td>
		</tr>
        
        <tr>
			<td style="text-align:right;">区分内容</td>
			<td style="text-align:left;">
            	<input type="text" name="description" style="width:250px;" id="description" value="<?=$row['description']?>"/>
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
