<?php
	require_once("inc/class.dbo.php");
	include_once("inc/checklogin.inc.php");
	include_once("inc/new.header.inc.php");
	$obj = new DBO();
	$user_id = $_GET['user_id'];
	$table='spssp_guest_category';
	$where = " user_id =".$user_id;
	$data_per_page=5;
	$current_page=(int)$_GET['page'];
	$redirect_url = 'guest_gift.php?user_id='.$user_id;
	
	$pageination = $obj->pagination($table, $where, $data_per_page,$current_page,$redirect_url);
	
	$get = $obj->protectXSS($_GET);
	
	if($_POST['insert']=="insert")
	{
		if(trim($_POST['category_name']) && trim($_POST['description']))
		{
				$post['name']=$_POST['category_name'];
				$post['description']=$_POST['description'];
				$post['display_order']= time();
				$post['creation_date'] = date("Y-m-d H:i:s");
				$post['user_id'] = (int)$_SESSION['userid'];
				$lastid = $obj->InsertData('spssp_guest_category',$post);			
				if($lastid)
				{					
					$msg=1;
					redirect("guest_gift.php?user_id=".$user_id."&msg=".$msg."&page=".$current_page);
					
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
		if(trim($_POST['name']) && trim($_POST['description']))
		{
			$num=$obj->GetNumRows("spssp_guest_category"," id=".$_POST['cat_id']);
			if($num==1)
			{
				$post['name']=$_POST['name'];
				$post['description']=$_POST['description'];
				$where=" id=".$_POST['cat_id'];
				$lastid = $obj->UpdateData('spssp_guest_category',$post,$where);
				
				if($lastid)
				{
					
					$msg=2;
					redirect("guest_gift.php?user_id=".$user_id."&msg=".$msg."&page=".$current_page);
					
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
		$where=" id=".$_GET['id'];;
		$lastid = $obj->DeleteRow('spssp_guest_category',$where);
		//redirect('guest_gift.php?user_id='.$user_id."&page=".$current_page);		
		
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
	var description  = document.getElementById('description').value;
	
	
	var flag = true;
	if(!name)
	{
		 alert("メールアドレスが未入力です");
		 document.getElementById('name').focus();
		 return false;
	}
	if(!description)
	{
		 alert("メールアドレスが未入力です");
		 document.getElementById('description').focus();		 
		 return false;
	}
	
	document.insert_guest_gift_form.submit();
}
function validForm_Edit()
{
	var name  = document.getElementById('edit_name').value;
	var description  = document.getElementById('edit_description').value;
	
	
	var flag = true;
	if(!name)
	{
		 alert("メールアドレスが未入力です");
		 document.getElementById('edit_name').focus();
		 return false;
	}
	if(!description)
	{
		 alert("メールアドレスが未入力です");
		 document.getElementById('edit_description').focus();		 
		 return false;
	}
	
	document.edit_guest_gift_cat_form.submit();
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
function viewDefaultCategories(user_id)
{
	$.post('ajax/view_default_categories.php',{'user_id':user_id}, function(data){
		$("#message_BOX").hide();
		$("#message_BOX").html(data);
		$("#message_BOX").fadeIn(1000);
	});
}

function acceptDefaultCategory(catid,user_id)
{
	$.post('ajax/accept_default_categories.php',{'catid':catid,'user_id':user_id}, function(data){
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
function edit_guest_cat(id)
{	
	$.post("ajax/edit_guest_gift_category.php",{'cat_id':id},function(data){
			//alert(data);
			var substrs = data.split('#');
			//alert(substr[0]);
			$("#edit_name").attr("value",substrs[1]);			
			$("#edit_description").attr("value",substrs[2]);	
			$("#cat_id").val(id);		
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
<?php if($err){$obj->GetErrorMsg($err);}?>
<?php if($_GET['msg']){$obj->GetSuccessMsg($_GET['msg']);}?> 
<div style="clear:both;"></div>   
	<div id="contents"> 
		<div><div class="navi"><a href="user_info.php?user_id=<?=$user_id?>"><img src="img/common/navi01.jpg" width="148" height="22" class="on" /></a></div>
      <div class="navi"><a href="message_admin.php?user_id=<?=$user_id?>"><img src="img/common/navi02.jpg" width="96" height="22" class="on" /></a></div>
      <div class="navi">
      	<a href="user_dashboard.php?user_id=<?=$user_id?>" target="_blank">
      		<img src="img/common/navi03.jpg" width="126" height="22" class="on" />
        </a>
      </div>
      <div class="navi"><img src="img/common/navi04_on.jpg" width="150" height="22"/></div>
      <div class="navi"><a href="customers_date_dl.html"><img src="img/common/navi05.jpg" width="116" height="22" class="on" /></a></div>
      <div style="clear:both;"></div></div>
      <br />
     
	 
	 
	 <a href="#" onclick="insert_item();">Add Category</a> <br />

<a href="guest_gift.php?user_id=<?=$user_id?>">My Guest Categories</a> &nbsp; &nbsp; <a href="javascript:void(0);" onclick="viewDefaultCategories(<?=$user_id?>);" >View Default Categories</a>
<div id="insert" style="display:none;padding:10px;">
<form action="guest_gift.php?user_id=<?=$user_id?>&page=<?=$current_page?>" method="post" name="insert_guest_gift_form">
	<input type="hidden" name="insert" value="insert">
	Name:<br /><input type="text" id="name" name="category_name" value=""><br />
	Decription:<br /><textarea id="description" name="description" cols="50" rows="3"></textarea><br />
	<input type="button" name="Insert" value="Insert" onclick="validForm();"/> &nbsp; <input type="reset" value="Cancel"  onclick="cancel_insert();" />
</form>
</div>

<div id="edit" style="display:none;padding:10px;">
		
	<form action="guest_gift.php?user_id=<?=$user_id;?>&page=<?=$current_page?>" method="post" name="edit_guest_gift_cat_form">
			<input type="hidden" id="cat_id" name="cat_id" value="">
			<input type="hidden" name="update" value="update">
			Name:<br /><input type="text" id="edit_name" name="name" value="<?php echo $row['name'];?>"><br />
			Decription:<br /><textarea id="edit_description" name="description" cols="50" rows="3"><?php echo $row['description'];?></textarea><br />
			<input type="button" name="Edit" value="Update" onclick="validForm_Edit();"/> &nbsp; <input type="reset" value="Cancel"  onclick="cancel_update();" />
	</form>
</div>
	
	
	
	
<div id="message_BOX">
	  <div class="page_next"><?php echo $pageination;?></div>
      <div class="box4">
          <table border="0" align="center" cellpadding="1" cellspacing="1">
            <tr align="center">
              <td>No.</td>
              <td><b>Name</b></td>           
              <td><b>Description</b></td>
              <td>添付</td>			  
              <td>削除</td>
            </tr>
          </table>
        </div>
		
<?php	
	$query_string="SELECT * FROM spssp_guest_category where user_id=".$user_id."  ORDER BY display_order DESC limit ".((int)($current_page)*$data_per_page).",".((int)$data_per_page).";";
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
              <td><a href="guest_sub_cat_gift.php?user_id=<?=$user_id?>&cat_id=<?php echo $row['id'];?>"><?php echo $row['name'];?></a></td>
              <td><?php echo $row['description'];?></td> 
              <td><a href="#" onclick="edit_guest_cat(<?php echo $row['id'];?>);"><img src="img/common/btn_edit.gif" width="42" height="17" /></a></td>
			  <td> <a href="javascript:void(0);" onclick="confirmDelete('guest_gift.php?user_id=<?=$user_id?>&id=<?php echo $row['id'];?>&action=delete&page=<?=$current_page?>');"><img src="img/common/btn_deleate.gif" width="42" height="17" /></a>
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
