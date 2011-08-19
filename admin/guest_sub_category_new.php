<?php
	include_once("inc/dbcon.inc.php");
	
	include_once("inc/checklogin.inc.php");
	
	include_once("inc/header.inc.php");
	
	include_once("inc/class.dbo.php");
	
	$obj = new DBO();
	
	$get = $obj->protectXSS($_GET);
	//print_r($get);exit;
	$id = (int)$get['id'];
	
	$table = "spssp_guest_sub_category";
	if($id > 0)
	{
		$row = $obj->GetSingleRow($table,' id='.$id);
	}
	
	if(trim($_POST['name']))
	{
		
		$post = $obj->protectXSS($_POST);
		$post['display_order']= time();
		$post['creation_date'] = date("Y-m-d H:i:s");
		$post['category_id'] = (int)$get['catid'];
		
		if($id > 0)
		{
			$obj->UpdateData($table,$post," id=".$id);
		}
		else
		{
			$lastid = $obj->InsertData($table,$post);
		}
		
		redirect("guest_sub_categories.php?catid=".$get['catid']."&amp;page=".(int)$_GET['page']);
		
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
		 alert("お名前が未入力です");
		 document.getElementById('name').focus();
		 flag=false;
	}


	if(flag == true)
	{	
		document.guest_category_form.submit();
	}	
}

</script>

<div id="nav">
	<a href="manage.php" >Home</a>  &raquo; <a href="guest_categories.php?page=<?=(int)$_GET['page']?>">Guest Categories</a>  
    &raquo; <a href="guest_sub_categories.php?page=<?=(int)$get['page']?>&catid=<?=(int)$get['catid']?>">Guest Sub Categories</a>
    &raquo;
    <?php
    	if((int)$get['id'] > 0)
		{
			echo "Edit Sub Category";
		}
		else
		{			
			echo "New Sub Category";
		}
	?>

</div><br />


<form action="guest_sub_category_new.php?page=<?=(int)$_GET['page']?>&id=<?=(int)$_GET['id']?>&catid=<?=(int)$get['catid']?>" method="post" name="guest_category_form">
	<table align="center" cellspacing="5" border="0">
    	<tr>
			<td style="text-align:right;">Sub Category Name</td>
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

