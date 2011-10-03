<?php
	include_once("inc/dbcon.inc.php");
	
	include_once("inc/checklogin.inc.php");
	
	include_once("inc/header.inc.php");
	
	include_once("inc/class.dbo.php");
	
	
	$obj = new DBO();
	$get = $obj->protectXSS($_GET);
		
	
	
		$post['admin_viewed']=1;
		$where=" id=".$_GET['id'];
		$lastid = $obj->UpdateData('spssp_message',$post,$where);
		
	
	
	
	$row = $obj->GetSingleRow('spssp_message',' id='.$get['id']);
	
?>

<div id="nav">
	<a href="manage.php" >Home</a>  &raquo; <a href="messages.php?page=<?=(int)$_GET['page']?>">Messages</a>  <?php if($_GET['from']==1){echo "&raquo;<a href='messages_all_user.php?page=".(int)$_GET['page']."'>User Messages</a>&raquo;<b>View user Messages</b>";}else{echo "&raquo;<b>View user Messages</b>";}?>
</div>
<h2> &nbsp;View Messages</h2>

	<table cellspacing="5" border="0">
    	<tr>
			
			<td>
            	<span style="font-weight:bold;font-size:14px;"><?=$row['title']?></sapn><br />
				<span style="font-weight:normal;font-size:11px;">Posted Date:<?php echo $row['creation_date'];?><br />
            </td>
		</tr>
        <tr>
        	
			<td>
            	<p><?=$row['description']?></p>
            </td>
        </tr>
        
         <!--<tr>
        	<td style="text-align:right;">Field 1</td>
			<td style="text-align:left;">
            	<input type="text" name="field_1" style="width:250px;" value="<?=$row['field_1']?>"/>
            </td>
        </tr>
        <tr>
        	<td style="text-align:right;">Field 2</td>
			<td style="text-align:left;">
            	<input type="text" name="field_2" style="width:250px;" value="<?=$row['field_2']?>"/>
            </td>
        </tr>-->

       
    </table>
<?php
	include_once("inc/footer.inc.php");
?>

