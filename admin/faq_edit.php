<?php
	include_once("inc/dbcon.inc.php");
	
	include_once("inc/checklogin.inc.php");
	
	include_once("inc/header.inc.php");
	
	include_once("inc/class.dbo.php");
	
	
	$obj = new DBO();
	$get = $obj->protectXSS($_GET);
		
	
	if($_POST{'Edit'})
	{	if($_POST['question']&&$_POST['answare'])
		{
		$post['key_word']=$_POST['keyword'];
			$post['question']=$_POST['question'];
			$post['answare']=$_POST['answare'];
		$where=" id=".$_GET['id'];
		$lastid = $obj->UpdateData('spssp_admin_faq',$post,$where);
		if($lastid)
		{
			redirect("faq.php?page=".(int)$_GET['page']);
		}
		
		}
	}
	
	
	$row = $obj->GetSingleRow('spssp_admin_faq',' id='.$get['id']);
	
?>

<div id="nav">
	<a href="manage.php" >Home</a>  &raquo; <a href="faq.php?page=<?=(int)$_GET['page']?>">faq</a>  &raquo; Edit faq
</div>
<h2> &nbsp;Edit Messages</h2>
<?php if($err){echo "<h3 style='color:red;margin-left:50px;'>$err</h3>";}?>
<?php if($msg){echo "<h3 style='color:green;margin-left:50px;'>$msg</h3>";}?>
<form action="faq_edit.php?page=<?=(int)$_GET['page']?>&id=<?=(int)$_GET['id']?>" method="post" name="stuff_form">
	<table align="center" cellspacing="5" border="0">
    	<!--<tr>
			<td style="text-align:right;">Key Word</td>
			<td style="text-align:left;">
            	<input type="text" name="keyword" style="width:250px;" value="<?=$row['key_word']?>"/>
            </td>
		</tr>-->
        <tr>
        	<td style="text-align:right;">Question</td>
			<td style="text-align:left;">
            	<input type="text" name="question" style="width:250px;" value="<?=$row['question']?>"/>
            </td>
        </tr>
        
        <tr>
        	<td style="text-align:right;">Answare</td>
			<td style="text-align:left;">
            	<textarea name="answare" cols="80" rows="5"><?=$row['answare']?></textarea>
            </td>
        </tr>
        

        <tr>
        	<td>&nbsp;</td>
            <td>
            	<input type="submit" name="Edit" value="•Û‘¶" /> &nbsp; <input type="button" value="ƒŠƒZƒbƒg" />
            </td>
        </tr>
    </table>
</form>
<?php
	include_once("inc/footer.inc.php");
?>

