<?php
	require_once("inc/class.dbo.php");
	include_once("inc/checklogin.inc.php");
	include_once("inc/new.header.inc.php");
	$obj = new DBO();
	$user_id = $_GET['user_id'];
	$user_row = $obj->GetSingleRow("spssp_user"," id= $user_id");
	$table='spssp_menu_group';
	$where = " user_id=".$user_id;
	$data_per_page=10;
	$current_page=(int)$_GET['page'];
	$redirect_url = 'menu_user.php?user_id='.$user_id;
	
	$pageination = $obj->pagination($table, $where, $data_per_page,$current_page,$redirect_url);
	
	$get = $obj->protectXSS($_GET);
	
	if($_POST['insert']=="insert")
	{
		if(trim($_POST['name']) && trim($_POST['description']))
		{
				$post = $obj->protectXSS($_POST);
				$post['user_id']=$user_id;
				unset($post['insert']);				
				$lastid = $obj->InsertData('spssp_menu_group',$post);				
				if($lastid)
				{					
					$msg=1;
					redirect("menu_user.php?user_id=".$user_id."&msg=".$msg."&page=".$current_page);
					
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
	
if($_POST{'Edit'})
	{
		
		$post['name']=$_POST['menu_name'];
		$post['description']=$_POST['description'];
		$where=" id=".$_POST['id'];
		$lastid = $obj->UpdateData('spssp_menu_group',$post,$where);
		unset($_POST['Edit']);
		redirect('menu_group.php');		
	}
if($_POST['update']=="update")
	{
		
		if(trim($_POST['name']) && trim($_POST['description']))
		{
			$num=$obj->GetNumRows("spssp_menu_group"," id=".$_POST['group_id']);
			if($num==1)
			{
				$post = $obj->protectXSS($_POST);
				unset($post['update']);
				unset($post['group_id']);
				//$post['display_order']= time();
				//$post['creation_date'] = date("Y-m-d H:i:s");
				
				$lastid = $obj->UpdateData('spssp_menu_group',$post,"id=".$_POST['group_id']);
				
				if($lastid)
				{
					
					$msg=2;
					redirect("menu_user.php?user_id=".$user_id."&msg=".$msg."&page=".$current_page);
					
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
		echo $where=" id=".$_GET['id'];
		$lastid = $obj->DeleteRow('spssp_menu_group',$where);
		unset($_GET['action']);
		redirect('menu_user.php?user_id='.$user_id."&page=".$current_page);		
	}
	
	if($_SESSION['user_type'] == 222)
	{
		$data = $obj->GetAllRowsByCondition("spssp_user"," stuff_id=".(int)$_SESSION['adminid']);
		foreach($data as $dt)
		{
			$staff_users[] = $dt['id'];
		}
		if(!empty($staff_users))
		{
			if(in_array((int)$get['user_id'],$staff_users))
			{
				$var = 1;			
			}
			else
			{
				$var = 0;
			}
		}
		
	}
	else
	{
		$var = 1;
	}
?>
<script type="text/javascript">
function validForm()
{
	var name  = document.getElementById('name').value;
	var description  = document.getElementById('description').value;
	
	
	var flag = true;
	if(!name)
	{
		 alert("料理名が未入力です");
		 document.getElementById('name').focus();
		 return false;
	}
	if(!description)
	{
		 alert("内容が未入力です");
		 document.getElementById('description').focus();		 
		 return false;
	}
	
	document.insert_menu_group_form.submit();
}

function validForm_Edit()
{
	var name  = document.getElementById('edit_name').value;
	var description  = document.getElementById('edit_description').value;
	
	
	var flag = true;
	if(!name)
	{
		 alert("料理名が未入力です");
		 document.getElementById('edit_name').focus();
		 return false;
	}
	if(!description)
	{
		 alert("内容が未入力です");
		 document.getElementById('edit_description').focus();		 
		 return false;
	}
	
	document.edit_menu_group_form.submit();
}
function edit_menu_group(id)
{	
	$.post("ajax/edit_menu_group.php",{'group_id':id},function(data){
			//alert(data);
			var substrs = data.split('#');
			//alert(substr[0]);
			$("#edit_name").attr("value",substrs[2]);			
			$("#edit_description").attr("value",substrs[3]);	
			$("#group_id").val(id);		
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
        <a href="login.html"><img src="img/common/btn_logout.jpg" alt="ログアウト" width="102" height="19" /></a>　
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
     <div style="font-size:14; font-weight:bold;">
           <?=$user_row['man_firstname']?> 様・ <?=$user_row['woman_firstname']?> 様
    </div>
 <h2>       	
            	 <a href="users.php">お客様一覧</a> &raquo; お客様挙式情報 &raquo; 席次表・引出物発注 &raquo; 料理設定（子供料理）
        </h2>
      <div><div class="navi"><a href="user_info.php?user_id=<?=$user_id?>"><img src="img/common/navi01.jpg" width="148" height="22" class="on" /></a></div>
      <div class="navi"><a href="message_admin.php?user_id=<?=$user_id?>"><img src="img/common/navi02.jpg" width="96" height="22" class="on" /></a></div>
      <div class="navi">
      	<a href="user_dashboard.php?user_id=<?=$user_id?>" target="_blank">
      		<img src="img/common/navi03.jpg" width="126" height="22" class="on" />
        </a>
      </div>
        	<div class="navi"><a href="guest_gift.php?user_id=<?=$user_id?>"><img src="img/common/navi04_on.jpg" width="150" height="22" /></a></div>
        	<div class="navi"><a href="customers_date_dl.php?user_id=<?=$user_id?>"><img src="img/common/navi05.jpg" width="116" height="22" class="on" /></a></div>
      <div style="clear:both;"></div></div>

      <br />
	<div>
     <a href="new_guest.php?user_id=<?=$user_id?>"> <b>招待者登録</b></a>&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;
     <a href="gift_user.php?user_id=<?=$user_id?>"><b>引出物設定</b></a>&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;
     <b>料理設定（子供料理）</b>
	  <div style="clear:both;"></div></div>
      <br />
		<h2>料理設定（子供料理）</h2>
		<?php
			$max=$obj->GetSingleData("spssp_menu_criteria","num_menu_groups","id=1");
			$num=$obj->GetNumRows("spssp_menu_group","user_id=".$user_id);
			if($num<$max){
		?>
		<?php if($var == 1){?><a href="#" id="insertlink">料理新規登録</a><?php }?>
		<?php }?>
<div id="insert" style="display:none;padding:10px;">

<form action="menu_user.php?user_id=<?=$user_id?>&page=<?=$current_page?>" method="post" name="insert_menu_group_form">
	<input type="hidden" name="insert" value="insert">
	料理名:<br /><input type="text" id="name" name="name" value=""><br />
	内容:<br /><textarea id="description" name="description" cols="50" rows="3"></textarea><br />
	<?php if($var == 1){?><input type="button" name="Insert" value="保存" onclick="validForm();"/><?php }?> &nbsp; <input type="reset" value="キャンセル"  onclick="cancel_insert();" />
</form>
</div>
<div id="edit" style="display:none;padding:10px;">
		
	<form action="menu_user.php?user_id=<?=$user_id;?>&page=<?=$current_page?>" method="post" name="edit_menu_group_form">
			<input type="hidden" id="group_id" name="group_id" value="">
			<input type="hidden" name="update" value="update">
			料理名:<br /><input type="text" id="edit_name" name="name" value="<?php echo $row['name'];?>"><br />
			内容:<br /><textarea id="edit_description" name="description" cols="50" rows="3"><?php echo $row['description'];?></textarea><br />
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
              <td>タイトル</td>           
              <td>内容</td>
              <td>編集</td>			  
              <td>削除</td>
            </tr>
          </table>
        </div>
		
		
		<?php	
		
		
	$query_string="SELECT * FROM spssp_menu_group where user_id =".$user_id."  ORDER BY id DESC limit ".((int)($current_page)*$data_per_page).",".((int)$data_per_page).";";
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
			
        	<td><!--<a href="menu_item_user.php?user_id=<?=$user_id?>&group_id=<?php echo $row['id'];?>">--><?php echo $row['name'];?><!--</a>--></td>
            <td><?php echo $row['description'];?></td> 
            <td>
            	<?php if($var == 1){?>
                	<a href="#" id="editlink<?php echo $row['id'];?>" onclick="edit_menu_group(<?php echo $row['id'];?>);">編集</a>
                <?php } ?>
            </td>
           <td> 
           		<?php if($var == 1){?>
           			<a href="javascript:void(0);" onclick="confirmDelete('menu_user.php?user_id=<?=$user_id?>&id=<?php echo $row['id'];?>&action=delete&page=<?=$current_page?>');">削除</a>
                <?php } ?>
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
