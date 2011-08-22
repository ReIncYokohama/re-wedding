<?php
	include_once("inc/dbcon.inc.php");
	
	include_once("inc/checklogin.inc.php");
	
	
	include_once("inc/header.inc.php");
	require_once("inc/class.dbo.php");
	$obj = new DBO();
	$row = $obj->GetSingleRow("spssp_admin", "id=".(int)$_GET['id']);
	if(trim($_POST['submit']) && trim($_POST['username']) && trim($_POST['password']))
	{
		$username = trim($_POST['username']);
		$pass = trim($_POST['password']);
		
		$sql = "update spssp_admin set username='".$username."', password='".$pass."' where id=".(int)$_GET['id'];
		$result=mysql_query($sql);
		redirect("users.php?page=0");
		
	}
	
?>
<div id="nav">
	<a href="manage.php" >Home</a>  &raquo; <a href="users.php?page=<?=(int)$_GET['page']?>">Admin Users</a>&raquo; Edit Admin User 
</div>
<h2>Edit Admin User</h2>
<form action="user_edit.php?id=<?=(int)$_GET['id']?>&page=<?=(int)$_GET['page']?>" method="post">
	<table align="center" cellspacing="5" border="0">
    	<tr>
			<td style="text-align:right;">User Name</td>
			<td style="text-align:left;">
            	<input type="text" name="username" style="width:250px;" value="<?=$row['username']?>"/>
            </td>
		</tr>
        <tr>
        	<td style="text-align:right;">Password</td>
			<td style="text-align:left;">
            	<input type="password" name="password" style="width:250px;" value="<?=$row['password']?>"/>
            </td>
        </tr>
        <tr>
        	<td>&nbsp;</td>
            <td>
            	<input type="submit" name="submit" value="•Û‘¶" /> &nbsp; <input type="button" value="ƒŠƒZƒbƒg" />
            </td>
        </tr>
    </table>
</form>
<?php
	include_once("inc/footer.inc.php");
?>

