<?php
	require_once("inc/class.dbo.php");
	include_once("inc/checklogin.inc.php");
	include_once("inc/new.header.inc.php");
	$user_id = $_GET['user_id'];
	$category_id=$_GET['cat_id'];
	if(!$category_id)
	{
	$category_id=$_POST['cat_id'];
	}
	$sub_cat_id=$_GET['sub_cat_id'];
	if(!$sub_cat_id)
	{
	$sub_cat_id=$_POST['sub_cat_id'];
	}
	
	$obj = new DBO();
	
	$get = $obj->protectXSS($_GET);
	//print_r($get);exit;
	$id = (int)$get['id'];
	
	$table = "spssp_default_guest";
	if($id > 0)
	{
		$row = $obj->GetSingleRow($table,' id='.$id);
	}
	
	include("inc/main_dbcon.inc.php");
	$respects = $obj->GetAllRow("dev2_main.spssp_respect");
	include("inc/return_dbcon.inc.php");
	//print_r($respects);
	
	if(trim($_POST['name']))
	{
		
		$post = $obj->protectXSS($_POST);
		$post['display_order']= time();
		$post['creation_date'] = date("Y-m-d H:i:s");
		$post['sub_category_id'] = (int)$_GET['sub_cat_id'];
		
		if($id > 0)
		{
			unset($post['display_order']);
			$obj->UpdateData($table,$post," id=".$id);
		}
		else
		{
			$lastid = $obj->InsertData($table,$post);
		}
		
		redirect("guests.php?cat_id=".$category_id."&amp;sub_cat_id=".(int)$sub_cat_id."&amp;page=".(int)$_GET['page']);
		
	}
?>

<script type="text/javascript">
function validForm()
{
	
	var name  = document.getElementById('name').value;


	var flag = true;
	if(!name)
	{
		 alert("招待者名が未入力です");
		 document.getElementById('name').focus();
		 flag=false;
	}


	if(flag == true)
	{	
		document.guest_category_form.submit();
	}	
}

</script>


<div id="topnavi">
    <?php
include("inc/main_dbcon.inc.php");
$hcode="0001";
$hotel_name = $obj->GetSingleData(" dev2_main.super_spssp_hotel ", " hotel_name ", " hotel_code=".$hcode);
?>
<h1><?=$hotel_name?>　管理</h1>
<?
include("inc/return_dbcon.inc.php");
?>
 
    <div id="top_btn"> 
        <a href="logout.php"><img src="img/common/btn_logout.jpg" alt="ログアウト" width="102" height="19" /></a>　
        <a href="javascript:;" onclick="MM_openBrWindow('../support/operation_h.html','','scrollbars=yes,width=620,height=600')"><img src="img/common/btn_help.jpg" alt="ヘルプ" width="82" height="19" /></a>
    </div>
</div>

<div id="container">
<?php if($err){echo "<script>
			alert('".$obj->GetErrorMsgNew($err)."');
			</script>";}?>
<?php if($_GET['msg']){echo "<script>
			alert('".$obj->GetSuccessMsgNew($msg)."');
			</script>";}?> 
<div style="clear:both;"></div>   
	<div id="contents"> 
		<div><div class="navi"><a href="user_info.php?user_id=<?=$user_id?>"><img src="img/common/navi01.jpg" width="148" height="22" class="on" /></a></div>
      <div class="navi"><a href="message_admin.php?user_id=<?=$user_id?>"><img src="img/common/navi02.jpg" width="96" height="22" class="on" /></a></div>
      <div class="navi"><a href="http://agu-athlete.sakura.ne.jp/spssp_sekiji/dashboard.html" target="_blank"><img src="img/common/navi03.jpg" width="126" height="22" class="on" /></a></div>
      <div class="navi"><img src="img/common/navi04_on.jpg" width="150" height="22"/></div>
      <div class="navi"><a href="customers_date_dl.php?user_id=<?=$user_id?>"><img src="img/common/navi05.jpg" width="116" height="22" class="on" /></a></div>
      <div style="clear:both;"></div></div>
      <br />
     
	 
	 <h2>
    <?php
    	if((int)$get['id'] > 0)
		{
			echo "招待者編集";
		}
		else
		{			
			echo "招待者新規";
		}
	?></h2>

</div><br />


<form action="guest_new.php?page=<?=(int)$_GET['page']?>&id=<?=(int)$_GET['id']?>&cat_id=<?=(int)$get['cat_id']?>&sub_cat_id=<?=(int)$get['sub_cat_id']?>" method="post" name="guest_category_form">
	<table align="center" cellspacing="5" border="0">

    	<tr>
			<td style="text-align:right;">招待者名前</td>
			<td style="text-align:left;">     
            	<input type="text" name="name" style="width:185px;" id="name" value="<?=$row['name']?>"/> &nbsp;
                       	<select name="respect_id">
                	<?php
                    	foreach($respects as $rsp)
						{
							if($rsp['id']==$row['respect_id'])
							{
								$sel = "Selected='Selected'";
							}
							else
							{
								$sel = "";
							}
							echo "<option value=".$rsp['id']." $sel>".$rsp['title']."</option>";
						}
					?>
                </select> 
            </td>
		</tr>
        
        <tr>
			<td style="text-align:right;">内容</td>
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
</div>		
</div>

<?php	
	include_once("inc/left_nav.inc.php");
	include_once("inc/new.footer.inc.php");
?>

