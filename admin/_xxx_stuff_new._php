<?php
	include_once("inc/dbcon.inc.php");
	
	include_once("inc/checklogin.inc.php");
	
	include_once("inc/header.inc.php");
	
	include_once("inc/class.dbo.php");
	
	
	if(trim($_POST['submit']) && trim($_POST['stuff_id']) && trim($_POST['password']))
	{
		$obj = new DBO();
		
		$post = $obj->protectXSS($_POST);
		unset($post['submit']);
		
		$post['display_order']= time();
		$post['creation_date'] = date("Y-m-d H:i:s");
		
		$lastid = $obj->InsertData('spssp_stuff',$post);
		
		$query_string="SELECT * FROM spssp_admin  ORDER BY username ASC;";
	  	$data_rows = $obj->getRowsByQuery($query_string);
	
		
		$data_num_admin=count($data_rows);
		
		if($data_num_admin>=10)
		redirect("users.php?page=1");
		else
		redirect("users.php?page=0");
		
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
	<a href="manage.php" >Home</a>  &raquo; <a href="stuffs.php?page=<?=(int)$_GET['page']?>">Stuffs</a>  &raquo; New Stuff
</div>
<h2> &nbsp;Create New Stuff</h2>
<form action="stuff_new.php?page=<?=(int)$_GET['page']?>" method="post" name="stuff_form">
	<table align="center" cellspacing="5" border="0">
    	<tr>
			<td style="text-align:right;">Stuff Name</td>
			<td style="text-align:left;">
            	<input type="text" name="name" style="width:250px;"/>
            </td>
		</tr>
        <tr>
        	<td style="text-align:right;">Zip</td>
			<td style="text-align:left;">
            	<input type="text" name="zip" style="width:250px;"/>
            </td>
        </tr>
        
        <tr>
        	<td style="text-align:right;">Address</td>
			<td style="text-align:left;">
            	<input type="text" name="address" style="width:250px;"/>
            </td>
        </tr>
        <tr>
        	<td style="text-align:right;">Telephone</td>
			<td style="text-align:left;">
            	<input type="text" name="telephone" style="width:250px;"/>
            </td>
        </tr>
        <tr>
        	<td style="text-align:right;">Email</td>
			<td style="text-align:left;">
            	<input type="text" name="email" id="email" style="width:250px;"/>
            </td>
        </tr>
        <tr>
        	<td style="text-align:right;">Fax</td>
			<td style="text-align:left;">
            	<input type="text" name="fax" style="width:250px;"/>
            </td>
        </tr>
        <tr>
        	<td style="text-align:right;">Stuff Id</td>
			<td style="text-align:left;">
            	<input type="text" name="stuff_id" style="width:250px;"/>
            </td>
        </tr>
        <tr>
        	<td style="text-align:right;">Password</td>
			<td style="text-align:left;">
            	<input type="password" name="password" style="width:250px;"/>
            </td>
        </tr>
        <tr>
        	<td style="text-align:right;">Status</td>
			<td style="text-align:left;">
            	<input type="text" name="status" style="width:250px;"/>
            </td>
        </tr>

        <tr>
        	<td>&nbsp;</td>
            <td>
            	<input type="submit" name="submit" value="保存" onclick="validForm();" /> &nbsp; <input type="button" value="リセット" />
            </td>
        </tr>
    </table>
</form>
<?php
	include_once("inc/footer.inc.php");
?>
