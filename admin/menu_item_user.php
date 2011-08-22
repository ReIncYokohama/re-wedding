<?php
	require_once("inc/class.dbo.php");
	include_once("inc/checklogin.inc.php");
	include_once("inc/new.header.inc.php");
	$obj = new DBO();
	$user_id = $_GET['user_id'];
	$group_id=$_GET['group_id'];
	if(!$group_id)
	{
		$group_id=$_POST['group_id'];
	}
	
	$table='spssp_menu';
	$where = "menu_group_id=".$group_id." and user_id=".$user_id;
	$data_per_page=5;
	$current_page=(int)$_GET['page'];
	$redirect_url = 'menu_item_user.php?user_id='.$user_id."&group_id=".$group_id;
	
	$pageination = $obj->pagination($table, $where, $data_per_page,$current_page,$redirect_url);
	
	$get = $obj->protectXSS($_GET);
	
	if($_POST['insert']=="insert")
	{
		if(trim($_POST['name']) && trim($_POST['remarks'])&& trim($_POST['price']))
		{
				$post = $obj->protectXSS($_POST);
				$post['user_id']=$user_id;
				
				unset($post['insert']);				
				$lastid = $obj->InsertData('spssp_menu',$post);				
				if($lastid)
				{					
					$msg=1;
					redirect("menu_item_user.php?user_id=".$user_id."&group_id=".$group_id."&msg=".$msg."&page=".$current_page);
					
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
	

if($_POST['update']=="update")
	{
		if(trim($_POST['name']) && trim($_POST['remarks'])&& trim($_POST['price']))
		{
			$num=$obj->GetNumRows("spssp_menu"," id=".$_POST['item_id']);
			if($num==1)
			{
				$post = $obj->protectXSS($_POST);
				unset($post['update']);
				unset($post['item_id']);
				//$post['display_order']= time();
				//$post['creation_date'] = date("Y-m-d H:i:s");
				
				$lastid = $obj->UpdateData('spssp_menu',$post,"id=".$_POST['item_id']);
				
				if($lastid)
				{
					
					$msg=2;
					redirect("menu_item_user.php?user_id=".$user_id."&msg=".$msg."&group_id=".$group_id."&page=".$current_page);
					
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
if($_GET{'action'}=="delete")
	{
		echo $where=" id=".$group_id;
		$lastid = $obj->DeleteRow('spssp_menu',$where);
		unset($_GET['action']);
		redirect('menu_user.php?user_id='.$user_id."&group_id=".$group_id."&page=".$current_page);		
	}
?>
<script type="text/javascript">
function validForm()
{
	var name  = document.getElementById('name').value;
	var remarks  = document.getElementById('remarks').value;
	var price  = document.getElementById('price').value;
	
	
	var flag = true;
	if(!name)
	{
		 alert("名前が未入力です");
		 document.getElementById('name').focus();
		 return false;
	}
	if(!price)
	{
		 alert("priceが未入力です");
		 document.getElementById('price').focus();		 
		 return false;
	}
	if(!remarks)
	{
		 alert("remarksが未入力です");
		 document.getElementById('remarks').focus();		 
		 return false;
	}
	
	document.insert_menu_item_form.submit();
}

function validForm_Edit()
{
	var name  = document.getElementById('edit_name').value;
	var price  = document.getElementById('edit_price').value;
	var remarks  = document.getElementById('edit_remarks').value;
	
	
	var flag = true;
	if(!name)
	{
		 alert("名前が未入力です");
		 document.getElementById('edit_name').focus();
		 return false;
	}
	if(!price)
	{
		 alert("Priceが未入力です");
		 document.getElementById('edit_price').focus();		 
		 return false;
	}
	if(!remarks)
	{
		 alert("remarksが未入力です");
		 document.getElementById('edit_remarks').focus();		 
		 return false;
	}
	
	document.edit_menu_item_form.submit();
}
function edit_menu_group(id)
{	
	$.post("ajax/edit_menu_item.php",{'item_id':id},function(data){
			//alert(data);
			var substrs = data.split('#');
			//alert(substr[0]);
			$("#edit_name").attr("value",substrs[2]);			
			$("#edit_price").attr("value",substrs[4]);			
			$("#edit_remarks").attr("value",substrs[3]);	
			$("#item_id").val(id);		
		});
	
	$("#edit").toggle("slow");
	$("#insert").hide("slow");
}
function cancel_insert()
{
	$("#name").attr("value","");	
	$("#description").attr("value","");
	$("#insert").hide("slow");
}
function cancel_update()
{
	$("#edit_name").attr("value","");	
	$("#edit_description").attr("value","");
	$("#edit").hide("slow");
} 

$(function(){

	var msg_html=$("#msg_rpt").html();
	
	if(msg_html!='')
	{
		$("#msg_rpt").fadeOut(5000);
	}

});
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
        <a href="login.html"><img src="img/common/btn_logout.jpg" alt="ログアウト" width="102" height="19" /></a>　
        <a href="javascript:;" onclick="MM_openBrWindow('../support/operation_h.html','','scrollbars=yes,width=620,height=600')"><img src="img/common/btn_help.jpg" alt="ヘルプ" width="82" height="19" /></a>
    </div>
</div>

<div id="container">

<div style="clear:both;"></div>   
	<div id="contents"> 
		<div>
         <div class="navi"><a href="gift_user.php?user_id=<?=$_GET['user_id']?>"><img src="img/common/jushin_img.jpg" width="71" height="22" class="on" /></a></div>
      <div class="navi"><img src="img/common/soushin_img_on.jpg" width="71" height="22" /></div>
	  <div style="clear:both;"></div></div>
        <?php if($err){echo "<script>
			alert('".$obj->GetErrorMsgNew($err)."');
			</script>";}?>
		<?php if($_GET['msg']){echo "<script>
			alert('".$obj->GetSuccessMsgNew($_GET['msg'])."');
			</script>";}?> 
		<h2>メニュー項目</h2>
		<?php
			$group_name = $obj->GetSingleData("spssp_menu_group","name","id=".$group_id);
		?>
		<div><h3><a href="menu_user.php?user_id=<?=$user_id?>"><?=$group_name?></h3></a></div>
		<a href="#" id="insertlink">Add Menu Item</a>
		
<div id="insert" style="display:none;padding:10px;">
<form action="menu_item_user.php?user_id=<?=$user_id?>&group_id=<?=$group_id;?>&page=<?=$current_page?>" method="post" name="insert_menu_item_form">
	<input type="hidden" name="insert" value="insert">
	<input type="hidden" name="menu_group_id" value="<?=$group_id?>">
	Name:<br /><input type="text" id="name" name="name" value=""><br />
	Price:<br /><input type="text" id="price" name="price" value=""><br />
	Remarks:<br /><textarea id="remarks" name="remarks" cols="50" rows="3"></textarea><br />
	<input type="button" name="Insert" value="保存" onclick="validForm();"/> &nbsp; <input type="reset" value="キャンセル"  onclick="cancel_insert();" />
</form>

</div>
<div id="edit" style="display:none;padding:10px;">
		
	<form action="menu_item_user.php?user_id=<?=$user_id;?>&group_id=<?=$group_id;?>&page=<?=$current_page?>" method="post" name="edit_menu_item_form">
			<input type="hidden" id="item_id" name="item_id" value="">
			<input type="hidden" name="update" value="update">
			Name:<br /><input type="text" id="edit_name" name="name" value="<?php echo $row['name'];?>"><br />
			Price:<br /><input type="text" id="edit_price" name="price" value=""><br />
			Decription:<br /><textarea id="edit_remarks" name="remarks" cols="50" rows="3"><?php echo $row['description'];?></textarea><br />
			<input type="button" name="Edit" value="変更" onclick="validForm_Edit();"/> &nbsp; <input type="reset" value="キャンセル"  onclick="cancel_update();" />
	</form>
</div>

<script>
$("#insertlink").click(function () {
$("#insert").toggle("slow");
$("#edit").hide("slow");
});    
</script>
<div id="message_BOX">
	  <div class="page_next"><?php echo $pageination;?></div>
      <div class="box4">
          <table border="0" align="center" cellpadding="1" cellspacing="1">
            <tr align="center">
              <td>No.</td>
              <td>日　付</td> 
			   <td>Price</td>           
              <td>タイトル</td>
              <td>添付</td>			  
              <td>削除</td>
            </tr>
          </table>
        </div>
		
		
		<?php	
		
		
	$query_string="SELECT * FROM spssp_menu where menu_group_id=".$group_id." ORDER BY id DESC limit ".((int)($current_page)*$data_per_page).",".((int)$data_per_page).";";
		$data_rows = $obj->getRowsByQuery($query_string);
		//echo "<pre>";
		//print_r($data_rows);exit;
		$i=0;$j=$current_page*$data_per_page+1;	
			
		foreach($data_rows as $row)	
		{
			if($i%2==0)
			{
				$class = 'box5';
			}
			else
			{
				$class = 'box6';
			}	
	?>
		<div class="<?=$class?>">
	 	<table border="0" align="center" cellpadding="1" cellspacing="1">
            <tr align="center">
              <td><?=$j?></td>
			
        	<td><?php echo $row['name'];?></td>
            <td><?php echo $row['price'];?></td>
			<td><?php echo $row['remarks'];?></td> 
            <td><a href="#" id="editlink<?php echo $row['id'];?>" onclick="edit_menu_group(<?php echo $row['id'];?>);">Edit</a></td>
           <td> <a href="javascript:void(0);" onclick="confirmDelete('menu_user.php?user_id=<?=$user_id?>&id=<?php echo $row['id'];?>&action=delete&page=<?=$current_page?>');">Delete</a>
            </td>

		</tr>
		
		</table>
		</div>
		<?php $i++;$j++; }?>
	
</div>

                   
    </div>
</div>

<?php	
	include_once("inc/left_nav.inc.php");
	include_once("inc/new.footer.inc.php");
?>
