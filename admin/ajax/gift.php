<?php
	require_once("inc/class.dbo.php");
	include_once("inc/checklogin.inc.php");
	include_once("inc/new.header.inc.php");
	$obj = new DBO();
	
	if($_SESSION['user_type'] == 222)
	{
		$btn_disp = " display:none";
		$ro = " readonly='readonly'";
	}
	else
	{
		$btn_disp = "";
		$ro = "";
	}
	
	$get = $obj->protectXSS($_GET);
	
	$gift_criteria_num = $obj->GetNumRows("spssp_gift_criteria"," 1=1");
	
	if($_POST['insert']=="insert")
	{
		if(trim($_POST['num_gift_groups']) && trim($_POST['num_gift_items']) && trim($_POST['order_deadline']))
		{
			$num=$obj->GetNumRows("spssp_gift_criteria"," 1=1");
			if(!$num)
			{
				$post = $obj->protectXSS($_POST);
				unset($post['insert']);
				//$post['display_order']= time();
				//$post['creation_date'] = date("Y-m-d H:i:s");
				
				$lastid = $obj->InsertData('spssp_gift_criteria',$post);
				
				if($lastid)
				{
					mysql_query("TRUNCATE TABLE `spssp_gift_group_default` ");
					mysql_query("TRUNCATE TABLE `spssp_gift_item_default` ");
					$max_limt_group=65+$post['num_gift_groups'];
					for($p = '65'; $p < $max_limt_group; ++$p)
					{   
						$obj->InsertData('spssp_gift_group_default',array("name" =>chr($p)));
					}
					$max_limt_item=1+$post['num_gift_items'];
					for($q = 1; $q < $max_limt_item; $q++)
					{	
						$obj->InsertData('spssp_gift_item_default',array("name"=>"Item".$q));
					}
					$msg=1;
					redirect("gift.php?msg=".$msg);
					
				}
				else
				{
					$err=1;
				}
			}
			else
			{
				$err=3;
			}
		}
		else
		{
			$err=2;	
		}
	}
	
	if($_POST['update']=="update")
	{
		
		if(trim($_POST['num_gift_groups'])<=7 && trim($_POST['num_gift_items'])<=7 && trim($_POST['order_deadline']))
		{
			$num=$obj->GetNumRows("spssp_gift_criteria"," 1=1");
			if($num==1)
			{
				$post = $obj->protectXSS($_POST);
				unset($post['update']);
				//$post['display_order']= time();
				//$post['creation_date'] = date("Y-m-d H:i:s");
				
				$lastid = $obj->UpdateData('spssp_gift_criteria',$post,"id=".$_POST['id']);
				
				if($lastid)
				{
					mysql_query("TRUNCATE TABLE `spssp_gift_group_default` ");
					mysql_query("TRUNCATE TABLE `spssp_gift_item_default` ");
					$max_limt_group=65+$post['num_gift_groups'];
					for($p = '65'; $p < $max_limt_group; ++$p)
					{   
						$obj->InsertData('spssp_gift_group_default',array("name" =>chr($p)));
					}
					$max_limt_item=1+$post['num_gift_items'];
					for($q = 1; $q < $max_limt_item; $q++)
					{	
						$obj->InsertData('spssp_gift_item_default',array("name"=>"Item".$q));
					}
					$msg=2;
					//redirect("gift.php?msg=".$msg);
					
				}
				else
				{
					$err=1;
				}
			}
			else
			{
				$err=3;
			}
		}
		else
		{
			$err=2;	
		}
	}
	if($gift_criteria_num>0)
	{
		$gift_criteria_data_row = $obj->GetAllRow("spssp_gift_criteria");
	}
	
	$menu_criteria_num = $obj->GetNumRows("spssp_menu_criteria"," 1=1");
	
	if($_POST['insert2']=="insert")
	{
		if(trim($_POST['num_menu_groups'])<=3)
		{
			$num=$obj->GetNumRows("spssp_menu_criteria"," 1=1");
			if(!$num)
			{
				$post = $obj->protectXSS($_POST);
				unset($post['insert2']);
				//$post['display_order']= time();
				//$post['creation_date'] = date("Y-m-d H:i:s");
				
				$lastid = $obj->InsertData('spssp_menu_criteria',$post);
				
				if($lastid)
				{
					$msg=1;
					redirect("gift.php?msg=".$msg);
					
				}
				else
				{
					$err=1;
				}
			}
			else
			{
				$err=3;
			}
		}
		else
		{
			$err=2;	
		}
	}
	if($_POST['update2']=="update")
	{
		if(trim($_POST['num_menu_groups'])<=3)
		{
			$num=$obj->GetNumRows("spssp_menu_criteria"," 1=1");
			if($num==1)
			{
				$post = $obj->protectXSS($_POST);
				unset($post['update2']);
				//$post['display_order']= time();
				//$post['creation_date'] = date("Y-m-d H:i:s");
				
				$lastid = $obj->UpdateData('spssp_menu_criteria',$post,"id=".$_POST['id']);
				
				if($lastid)
				{
					$msg=2;
					redirect("gift.php?msg=".$msg);
					
				}
				else
				{
					$err=1;
				}
			}
			else
			{
				$err=3;
			}
		}
		else
		{
			$err=2;	
		}
	}
	if($menu_criteria_num>0)
	{
		$menu_criteria_data_row = $obj->GetAllRow("spssp_menu_criteria");
	}
	//DEFAULT GROUP NAME UPDaTE STart
	
	if($_POST['editUserGiftGroupsUpdate']=='editUserGiftGroupsUpdate')
	{
		unset($_POST['editUserGiftGroupsUpdate']);
		$number = count($_POST);
		$number = ($number/2);
		for($i=1;$i<=$number;$i++)
		{
			$array['name'] = $_POST['name'.$i];
			echo $obj->UpdateData("spssp_gift_group_default", $array," id=".(int)$_POST['fieldId'.$i]);
		}
	
	}
	//DEFAULT GROUP NAME UPDaTE END
?>
<script type="text/javascript">
$(document).ready(function(){
    
    $('#num_gift_groups').keyup(function(){        
		var r=isInteger("num_gift_groups");		
    });
	$('#num_gift_items').keyup(function(){        
		var r=isInteger("num_gift_items");		
    });
	$('#order_deadline').keyup(function(){        
		var r=isInteger("order_deadline");		
    });
	$('#num_menu_groups').keyup(function(){        
		var r=isInteger("num_menu_groups");		
    });
});

function isInteger(id){
 var i;
 var s=$("#"+id).val();
    for (i = 0; i < s.length; i++){  
        // Check that current character is number.
        var c = s.charAt(i);
       if(i==0&&c==0)
	   {
	   		var msg="0の入力はできません。";
			$('#'+id).attr("value","");
			alert(msg);
	   }
	    if (((c < "0") || (c > "9")))
		{
			var msg="数字のみの入力はできません。";
			$('#'+id).attr("value","");
			 alert(msg);
		}
    }
    // All characters are numbers.
    return true;
}
function validForm()
{
	var num_gift_groups  = document.getElementById('num_gift_groups').value;
	var num_gift_items  = document.getElementById('num_gift_items').value;
	var order_deadline  = document.getElementById('order_deadline').value;
	
	if(!num_gift_groups)
	{
		 alert("引出物グループ数を入力してください");
		 document.getElementById('num_gift_groups').focus();		 
		 return false;
	}
	else
	{
		if(num_gift_groups>7)
		{
			 alert("引出物グループ数の上限は７グループまでです");
			 document.getElementById('num_gift_groups').focus();		 
			 return false;
		}
	}
	
	if(!num_gift_items)
	{
		 alert("引出物商品数を入力してください");
		 document.getElementById('num_gift_items').focus();		 
		 return false;
	}
	else
	{
		if(num_gift_items>7)
		{
			 alert("引出物商品数の上限は7種類までです");
			 document.getElementById('num_gift_items').focus();		 
			 return false;
		}
	}
	if(!order_deadline)
	{
		 alert("発注締切日を入力してください");
		 document.getElementById('order_deadline').focus();		 
		 return false;
	}
	
	document.gift_criteria_form.submit();
}
function validForm2()
{
	
	var num_menu_groups  = document.getElementById('num_menu_groups').value;
	if(!num_menu_groups)
	{
		 alert("引出物グループ数を入力してください");
		 document.getElementById('num_menu_groups').focus();		 
		 return false;
	}
	else
	{
		if(num_menu_groups>3)
		{
			 alert("子供料理設定の上限は3種類までです");
			 document.getElementById('num_menu_groups').focus();		 
			 return false;
		}
	}
	document.menu_criteria_form.submit();
}

function checkGroupForm(x)
{	//alert(x);
	
	for(var y=0;y<x;y++)
	{
		if($("#name"+y).val()=="")
		{
			alert("空にすることは");
			var error =1;
		}
	}
	if(error!=1)
	{
		document.editUserGiftGroupsForm.submit();	
	}
}
</script>
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
        <a href="logout.php"><img src="img/common/btn_logout.jpg" alt="ログアウト" width="102" height="19" /></a>　
        <a href="#"><img src="img/common/btn_help.jpg" alt="ヘルプ" width="82" height="19" /></a>
    </div>
</div>

<div id="container">
<?php if($err){echo "<script>
			alert('".$obj->GetErrorMsgNew($err)."');
			</script>";}?>
<?php if($_GET['msg']){echo "<script>
			alert('".$obj->GetSuccessMsgNew($_GET['msg'])."');
			</script>";}?> 




<div style="clear:both;"></div>   
	<div id="contents"> 
	 <h2>       	
            	<a href="manage.php">TOP</a> &raquo; 引出物・料理
        </h2>	
	<h2>引出物・料理</h2>	
	<div>
 <div style="float:left;">   
			<form  action="gift.php" method="post" name="gift_criteria_form">
				<p class="txt3">
			  <div style="margin:5px;">
			  <label for="引出物商品数">引出物商品数：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
			  <input name="num_gift_items" <?=$ro?> type="text" onlyNumeric="i" id="num_gift_items" size="10" value="<?=$gift_criteria_data_row[0]['num_gift_items']?>" />
				種類(最大10つまで)
			  </div>
			  <div style="margin:5px;">
		<label for="発注締切日">引出物グループ数：&nbsp;</label>
		<input name="num_gift_groups" <?=$ro?> type="text"  onlyNumeric="i" id="num_gift_groups" size="10" value="<?=$gift_criteria_data_row[0]['num_gift_groups']?>"  />
		種類(最大5つまで)
		   </div>
		   <div style="margin:5px;">
		<label for="発注締切日">発注締切日：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
			<input name="order_deadline" <?=$ro?> type="text"  onlyNumeric="i" id="order_deadline" size="10" value="<?=$gift_criteria_data_row[0]['order_deadline']?>" />
			</div>
		　
		<!--    グループ記号：
		<label for="引出物グループ数"></label>
		<input name="グループ記号" type="text" id="textfield2" size="20" />-->
		
		<br />
		<br />
		<?php if($gift_criteria_num>0)
		{?>
		<input type="hidden" name="update" value="update" />
		<input type="hidden" name="id" value="<?=$gift_criteria_data_row[0]['id']?>" />
		<a href="#" onclick="validForm();"><img src="img/common/btn_regist.jpg" alt="登録" width="62" height="22" style=" <?=$btn_disp?>"  /></a>
		<?php }
		else
		{?>
		<input type="hidden" name="insert" value="insert">
		<a href="#" onclick="validForm();"><img src="img/common/btn_regist.jpg" alt="登録" width="62" height="22" style=" <?=$btn_disp?>"  /></a>
		<?php }?>
		
			</p>
			</form>
	</div>
	<div style="float:right;width:775px;border:1px solid gray;">
		<h2>編集　引出物グループ名</h2>
		<div style="padding-left:10px;padding-bottom:10px;">
			<?php
				$group_sql ="SELECT * FROM spssp_gift_group_default  ORDER BY id asc ;";
				$data_rows = $obj->getRowsByQuery($group_sql);
				
			?>
			<form action="gift.php" method="post" name="editUserGiftGroupsForm">
		  <input type="hidden" name="editUserGiftGroupsUpdate" value="editUserGiftGroupsUpdate">
		  <?php
			$xx = 1;
			foreach($data_rows as $row)
			{
				echo "<div style='float:left;margin-left:15px;margin-bottom:10px;'><input type='text' id='name".$xx."' ".$ro." name='name".$xx."' maxlength='4' size='6' value='".$row['name']."'>";
				echo "<input type='hidden' name='fieldId".$xx."' value='".$row['id']."'></div>";
				
				$xx++;
			}
		  
		  ?>
		   <br /><br /><br />
		   
		   <input type="button" style=" <?=$btn_disp?>" name="editUserGiftGroupsUpdateButton" value="保存" onclick="checkGroupForm(<?=$xx?>);">
		   </form>
			
		</div>	
		
	</div>
</div>
<div style="clear:both;"></div>
	<br />
<br />
<div>
     <h2>料理</h2>
	<form  action="gift.php" method="post" name="menu_criteria_form">
	<p class="txt3">子供料理設定：
	  <label for="引出物商品数"></label>
      <input name="num_menu_groups" type="text" id="num_menu_groups" <?php if($_SESSION['user_type']==222){?> readonly="readonly"<?php }?> size="10" <?=$ro?> value="<?=$menu_criteria_data_row[0]['num_menu_groups']?>" />
      種類(最大3つまで)<br />
      <br />
<?php if($menu_criteria_num>0)
{?>
<input type="hidden" name="update2" value="update" />
<input type="hidden" name="id" value="<?=$menu_criteria_data_row[0]['id']?>" />
<a href="#" onclick="validForm2();"><img src="img/common/btn_regist.jpg" alt="登録" width="62" height="22" style="<?=$btn_disp?>" /></a>
<?php }
else
{?>
<input type="hidden" name="insert2" value="insert">
<a href="#" onclick="validForm2();"><img src="img/common/btn_regist.jpg" alt="登録" width="62" height="22" style="<?=$btn_disp?>" /></a>
<?php }?>

	</p> 
	</form>
	</div>             
    </div>
</div>

<?php	
	include_once("inc/left_nav.inc.php");
	include_once("inc/new.footer.inc.php");
?>
