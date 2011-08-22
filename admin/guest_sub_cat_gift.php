<?php
	require_once("inc/class.dbo.php");
	include_once("inc/checklogin.inc.php");
	include_once("inc/new.header.inc.php");
	$obj = new DBO();
	$user_id = $_GET['user_id'];
	$category_id=$_GET['cat_id'];
	if(!$category_id)
	{
	$category_id=$_POST['cat_id'];
	}
	
	
	$table='spssp_guest_sub_category';
	$where = "category_id=".$category_id." and user_id =".$user_id;
	$data_per_page=10;
	$current_page=(int)$_GET['page'];
	$redirect_url = 'guest_sub_cat_gift.php?user_id='.$user_id.'7cat_id='.$category_id;
	
	$pageination = $obj->pagination($table, $where, $data_per_page,$current_page,$redirect_url);
	$get = $obj->protectXSS($_GET);
	
	
	if($_POST['insert']=="insert")
	{
		if(trim($_POST['name']))
		{
				$post['category_id']=$category_id;
				$post['user_id'] = (int)$user_id;
				$post['name']=$_POST['name'];
				$post['display_order']= time();
				$post['creation_date'] = date("Y-m-d H:i:s");
				$lastid = $obj->InsertData('spssp_guest_sub_category',$post);			
				if($lastid)
				{					
					$msg=1;
					redirect("guest_sub_cat_gift.php?user_id=".$user_id."&cat_id=".$category_id."&msg=".$msg."&page=".$current_page);
					
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
		if(trim($_POST['name']))
		{
			$num=$obj->GetNumRows("spssp_guest_sub_category"," id=".$_POST['sub_cat_id']);
			if($num==1)
			{
				$post['name']=$_POST['name'];
				$where=" id=".$_POST['sub_cat_id'];
				$lastid = $obj->UpdateData('spssp_guest_sub_category',$post,$where);
				
				if($lastid)
				{
					
					$msg=2;
					redirect("guest_sub_cat_gift.php?user_id=".$user_id."&cat_id=".$category_id."&msg=".$msg."&page=".$current_page);
					
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
		$where=" id=".$_GET['sub_cat_id'];;
		$lastid = $obj->DeleteRow('spssp_guest_sub_category',$where);
		//redirect('guest_sub_cat_gift.php?user_id='.$user_id."&cat_id=".$category_id."&page=".$current_page);		
		
	}
?>
<script type="text/javascript">

function insert_item(){
	$("#insert").toggle("slow");
	$("#edit").hide("slow");
} 
function validForm()
{
	var name  = document.getElementById('name').value;
	
	var flag = true;
	if(!name)
	{
		 alert("お名前が未入力です");
		 document.getElementById('name').focus();
		 return false;
	}
	document.insert_guest_subcat_gift_form.submit();
}
function validForm_Edit()
{
	var name  = document.getElementById('edit_name').value;
	
	var flag = true;
	if(!name)
	{
		 alert("お名前が未入力です");
		 document.getElementById('edit_name').focus();
		 return false;
	}
	
	document.edit_guest_gift_subcat_form.submit();
}
function cancel_insert()
{
	$("#name").attr("value","");	
	$("#insert").hide("slow");
}
function cancel_update()
{
	$("#edit_name").attr("value","");	
	$("#edit").hide("slow");
} 
function viewDefaultSubCategories(catid,userid)
{
	$.post('ajax/view_default_sub_categories.php',{'catid':catid,'user_id':userid}, function(data){

		$("#message_BOX").hide();
		$("#message_BOX").html(data);
		$("#message_BOX").fadeIn(1000);
	});
}

function acceptDefaultSubCategory(subcat_id,catid,user_id)
{
	
	
	$.post('ajax/accept_default_sub_categories.php',{'subcat_id':subcat_id,'user_id':user_id}, function(data){
		
		if(parseInt(data)==0)
		{
			alert('Acception Failed');
			return;
		}
		else
		{
			$("#message_BOX").hide();
			$("#message_BOX").html(data);
			$("#message_BOX").fadeIn(1000);
		}
	});
}
function edit_guest_cat_sub(id)
{	
	$.post("ajax/edit_guest_gift_sub_category.php",{'sub_id':id},function(data){
			//alert(data);
			var substrs = data.split('#');
			//alert(substr[0]);
			$("#edit_name").attr("value",substrs[2]);			
			$("#sub_cat_id").val(id);		
		});
	var temp=$("#edit").css("display");
	if(temp!="block")
	{
		$("#edit").toggle("slow");
	}
	$("#insert").hide("slow");
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
			alert('".$obj->GetSuccessMsgNew($_GET['msg'])."');
			</script>";}?> 
<div style="clear:both;"></div>   
	<div id="contents"> 
		<div><div class="navi"><a href="user_info.php?user_id=<?=$user_id?>"><img src="img/common/navi01.jpg" width="148" height="22" class="on" /></a></div>
      <div class="navi"><a href="message_admin.php?user_id=<?=$user_id?>"><img src="img/common/navi02.jpg" width="96" height="22" class="on" /></a></div>
      <div class="navi"><a href="user_dashboard.php?user_id=<?=$user_id?>" target="_blank"><img src="img/common/navi03.jpg" width="126" height="22" class="on" /></a></div>
      <div class="navi"><img src="img/common/navi04_on.jpg" width="150" height="22"/></div>
      <div class="navi"><a href="customers_date_dl.php?user_id=<?=$user_id?>"><img src="img/common/navi05.jpg" width="116" height="22" class="on" /></a></div>
      <div style="clear:both;"></div></div>
      <br />
     
	<?php

 $category_name = $obj->GetSingleData("spssp_guest_category","name","id=".$category_id);

?>
<div>
<h3>区分 : <a href="guest_gift.php?user_id=<?=$user_id?>"><?php echo $category_name;?></a></h3>
</div> 
	 
	 <a href="#" onclick="insert_item();">Add Sub Category</a> <br />

<a href="guest_sub_cat_gift.php?user_id=<?=$user_id?>&cat_id=<?=$category_id?>">My Guest Sub Categories</a> &nbsp; &nbsp; <a href="javascript:void(0);" onclick="viewDefaultSubCategories(<?=$category_id;?>,<?=$user_id?>);" >View Default Sub Categories</a>
<div id="insert" style="display:none;padding:10px;">
<form action="guest_sub_cat_gift.php?user_id=<?=$user_id?>&cat_id=<?php echo $category_id;?>&page=<?=$current_page?>" method="post" name="insert_guest_subcat_gift_form">
	<input type="hidden" name="insert" value="insert">
	名前:<br /><input type="text" id="name" name="name" value=""><br />
	<input type="button" name="Insert" value="保存" onclick="validForm();"/> &nbsp; <input type="reset" value="キャンセル"  onclick="cancel_insert();" />
</form>
</div>

<div id="edit" style="display:none;padding:10px;">
		
	<form action="guest_sub_cat_gift.php?user_id=<?=$user_id;?>&cat_id=<?php echo $category_id;?>&page=<?=$current_page?>" method="post" name="edit_guest_gift_subcat_form">
			<input type="hidden" id="sub_cat_id" name="sub_cat_id" value="">
			<input type="hidden" name="update" value="update">
			名前:<br /><input type="text" id="edit_name" name="name" value="<?php echo $row['name'];?>"><br />
			<input type="button" name="Edit" value="変更" onclick="validForm_Edit();"/> &nbsp; <input type="reset" value="キャンセル"  onclick="cancel_update();" />
	</form>
</div>
	
	
	
	
<div id="message_BOX">
	  <div class="page_next"><?php echo $pageination;?></div>
      <div class="box4">
          <table border="0" align="center" cellpadding="1" cellspacing="1">
            <tr align="center">
              <td>No.</td>
              <td><b>Name</b></td>           
           	  <td>添付</td>			  
              <td>削除</td>
            </tr>
          </table>
        </div>
		
<?php	
	$query_string="SELECT * FROM spssp_guest_sub_category where category_id=".$category_id." and user_id=".$user_id."  ORDER BY display_order DESC limit ".((int)($current_page)*$data_per_page).",".((int)$data_per_page).";";
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
              <td><a href="guests.php?user_id=<?=$user_id?>&cat_id=<?php echo $category_id;?>&sub_cat_id=<?php echo $row['id'];?>"><?php echo $row['name'];?></a></td>
              <td><a href="#" onclick="edit_guest_cat_sub(<?php echo $row['id'];?>);"><img src="img/common/btn_edit.gif" width="42" height="17" /></a></td>
			  <td> <a href="javascript:void(0);" onclick="confirmDelete('guest_sub_cat_gift.php?user_id=<?=$user_id?>&cat_id=<?=$category_id?>&sub_cat_id=<?php echo $row['id'];?>&action=delete&page=<?=$current_page?>');"><img src="img/common/btn_deleate.gif" width="42" height="17" /></a>
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
