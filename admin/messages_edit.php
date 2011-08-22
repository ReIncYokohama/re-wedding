<?php
	require_once("inc/class.dbo.php");
	include_once("inc/checklogin.inc.php");
	include_once("inc/new.header.inc.php");
	
	
	$obj = new DBO();
	$get = $obj->protectXSS($_GET);
		
	
	if($_POST{'Edit'})
	{
		$post = $obj->protectXSS($_POST);
		unset($post['Edit']);
		$where=" id=".$_GET['id'];
		$lastid = $obj->UpdateData('spssp_admin_messages',$post,$where);
		
		redirect("messages.php?id=".$_GET['id']."&page=".(int)$_GET['page']);
		
	}
	
	
	$row = $obj->GetSingleRow('spssp_admin_messages',' id='.$get['id']);
	//print_r($row);
	
?>
<div id="topnavi">
    <?php
include("inc/main_dbcon.inc.php");
$hcode="0001";
$hotel_name = $obj->GetSingleData(" super_spssp_hotel ", " hotel_name ", " hotel_code=".$hcode);
?>
<h1><?=$hotel_name?>　管理</h1>
<?
include("inc/return_dbcon.inc.php");
?>
 
    <div id="top_btn"> 
        <a href="logout.php"><img src="img/common/btn_logout.jpg" alt="OAEg" width="102" height="19" /></a>@
        <a href="#"><img src="img/common/btn_help.jpg" alt="wv" width="82" height="19" /></a>
    </div>
</div>
<div id="container">
    <div id="contents"> 
<!--<div id="nav">
	<a href="manage.php" >Home</a>  &raquo; <a href="messages.php?page=<?=(int)$_GET['page']?>">Messages</a>  &raquo; Edit Messages
</div>-->
<h2> &nbsp;メッセージ編集</h2>
<form action="messages_edit.php?page=<?=(int)$_GET['page']?>&id=<?=(int)$_GET['id']?>" method="post" name="stuff_form">
	<table align="center" border="0">
    	<tr>
			<td width="5%" style="text-align:right;">タイトル</td>
			<td style="text-align:left;">
            	<input type="text" name="title" style="width:250px;" value="<?php echo $row['title'];?>"/>
            </td>
		</tr>
        <tr>
        	<td width="5%" style="text-align:right;">本文</td>
			<td style="text-align:left;">
            	<textarea name="description" cols="70" rows="10"><?=$row['description']?></textarea>
            </td>
        </tr>
        
        <tr>
        	<td>&nbsp;</td>
            <td>
            	<input type="submit" name="Edit" value="保存" /> 
            </td>
        </tr>
    </table>
</form>
</div>
</div>
<?php	
include_once("inc/left_nav.inc.php");
include_once("inc/new.footer.inc.php");
?>

