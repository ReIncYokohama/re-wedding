<?php
	require_once("inc/class.dbo.php");
	include_once("inc/checklogin.inc.php");
	include_once("inc/new.header.inc.php");
	
	$obj = new DBO();
	
	if($_POST{'insert'})
	{
		//if($_POST['title']&&$_POST['description'])
		if($_POST['title'])
		{	
			$post['title']=$_POST['title'];
			$post['description']=$_POST['description'];
			$post['creation_date'] = date("Y-m-d H:i:s");
			$post['display_order']= time();
			$lastid = $obj->InsertData('spssp_admin_messages',$post);
			if($lastid)
			{
				redirect("messages.php?id=".$lastid."&page=0");
			}
			else
			{
				$err=1;
			}
		}
		else
		{
			$err=2;
		}
	}
?>
<script type="text/javascript">
function validForm()
{
	var title  = document.getElementById('title').value;
	var description  = document.getElementById('description').value;
	
	
	var flag = true;
	if(!title)
	{
		 alert("タイトルが未入力です");
		 document.getElementById('name').focus();
		 return false;
	}
	/*if(!description)
	{
		 alert("メールアドレスが未入力です");
		 document.getElementById('ID').focus();		 
		 return false;
	}*/
	
	document.msg_form.submit();
}

</script>
<div id="topnavi">
    <?php
include("inc/main_dbcon.inc.php");
$hcode=$HOTELID;
$hotel_name = $obj->GetSingleData(" super_spssp_hotel ", " hotel_name ", " hotel_code=".$hcode);
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
			alert('".$obj->GetSuccessMsgNew($_GET['msg'])."');
			</script>";}?> 
    <div id="contents"> 
<h2> &nbsp;新着メッセージ</h2>
<form action="messages_new.php?page=<?=(int)$_GET['page']?>" method="post" name="msg_form">
	<input type="hidden" name="insert" value="insert">
	<table align="center" border="0">
    	<tr>
			<td width="5%" style="text-align:right;">タイトル</td>
			<td style="text-align:left;">
            	<input type="text" name="title" id="title" style="width:250px;"/>
            </td>
		</tr>
        <tr>
        	<td width="5%" style="text-align:right;">本文</td>
			<td style="text-align:left;">
            	<textarea name="description" id="description" cols="70" rows="5"></textarea>
            </td>
        </tr>
        
       <!-- <tr>
        	<td style="text-align:right;">Field 1</td>
			<td style="text-align:left;">
            	<input type="text" name="field_1" style="width:250px;"/>
            </td>
        </tr>
        <tr>
        	<td style="text-align:right;">Field 2</td>
			<td style="text-align:left;">
            	<input type="text" name="field_2" style="width:250px;"/>
            </td>
        </tr>-->
        <tr>
        	<td>&nbsp;</td>
            <td>
            	<input type="button" name="insert" value="保存" onclick="validForm();"/> &nbsp; <input type="button" value="リセット" />
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
