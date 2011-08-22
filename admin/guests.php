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

	$table='spssp_guest';
	$where = " sub_category_id=".$sub_cat_id;
	$data_per_page=10;
	$current_page=(int)$_GET['page'];
	$redirect_url = 'guests.php?user_id='.$user_id.'&cat_id='.$category_id.'&sub_cat_id='.$sub_cat_id.'&page='.(int)$get['page'];

	$pageination = $obj->pagination($table, $where, $data_per_page,$current_page,$redirect_url);

	if($_POST['insert']=="insert")
	{
		//if(trim($_POST['name']) && trim($_POST['description']))
		if(trim($_POST['name']))
		{
			$num=$obj->GetNumRows('spssp_default_guest',"name='".$_POST['name']."'");
			if(!$num)
			{
				$post = $obj->protectXSS($_POST);
				$post['display_order']= time();
				$post['creation_date'] = date("Y-m-d H:i:s");
				$post['sub_category_id'] = (int)$_GET['sub_cat_id'];
				$post['user_id'] = (int)$_GET['user_id'];
				unset($post['insert']);
				$lastid = $obj->InsertData('spssp_guest',$post);
				if($lastid)
				{
					$msg=1;

					redirect("guests.php?user_id=".$user_id."&msg=".$msg."&cat_id=".$category_id."&amp;sub_cat_id=".(int)$sub_cat_id."&amp;page=".(int)$current_page);

				}
				else
				{
					$err=1;
				}
			}else
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
		//if(trim($_POST['name']) && trim($_POST['description']))
		if(trim($_POST['name']))
		{
			$num=$obj->GetNumRows("spssp_default_guest"," id=".$_POST['id']);
			if($num==1)
			{
				$post = $obj->protectXSS($_POST);
				$post['display_order']= time();
				$post['creation_date'] = date("Y-m-d H:i:s");
				$post['sub_category_id'] = (int)$_GET['sub_cat_id'];
				$post['user_id'] = (int)$_GET['user_id'];
				$where=" id=".$_POST['id'];
				unset($post['update']);
				unset($post['id']);
				$lastid = $obj->UpdateData('spssp_guest',$post,$where);

				if($lastid)
				{

					$msg=2;
					redirect("guests.php?user_id=".$user_id."&msg=".$msg."&cat_id=".$category_id."&amp;sub_cat_id=".(int)$sub_cat_id."&amp;page=".(int)$current_page);

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
		 alert("招待者名が未入力です");
		 document.getElementById('name').focus();
		 return false;
	}
	/*if(!description)
	{
		 alert("メールアドレスが未入力です");
		 document.getElementById('description').focus();
		 return false;
	}*/

	document.insert_guest_form.submit();
}

function cancel_insert()
{
	$("#name").attr("value","");
	$("#description").attr("value","");
	$("#insert").hide("slow");
}
function edit_guest(id)
{
	$.post("ajax/edit_guest.php",{'id':id},function(data){
			//alert(data);
			var substrs = data.split('#');
			//alert(substr[0]);
			var temp=$("#edit").css("display");
			if(temp!="block")
			{
				$("#edit").toggle("slow");
			}

			$("#from_ajax").html(data);
			$("#from_ajax").fadeIn(1000);
			$("#insert").hide("slow");
		});

}
function validForm_Edit()
{
	var name  = document.getElementById('edit_name').value;
	var description  = document.getElementById('edit_description').value;


	var flag = true;
	if(!name)
	{
		 alert("招待者名が未入力です");
		 document.getElementById('edit_name').focus();
		 return false;
	}
	/*if(!description)
	{
		 alert("メールアドレスが未入力です");
		 document.getElementById('edit_description').focus();
		 return false;
	}*/

	document.edit_guest_form.submit();
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
$hcode="0001";
$hotel_name = $obj->GetSingleData(" super_spssp_hotel ", " hotel_name ", " hotel_code=".$hcode);
?>
<h1><?=$hotel_name?></h1>
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
	<h2>Guest</h2>

	<?php

 $category_name = $obj->GetSingleData("spssp_guest_category","name","id=".$category_id);
 $sub_category_name = $obj->GetSingleData("spssp_guest_sub_category","name","id=".$sub_cat_id);

 include("inc/main_dbcon.inc.php");
$respects = $obj->GetAllRow( "spssp_respect");
include("inc/return_dbcon.inc.php");
?>
<div>
<h3>Guestカテゴリー: <a href="guest_gift.php?user_id=<?=$user_id?>"><?php echo $category_name;?></a>&nbsp;&nbsp;&nbsp;Subカテゴリー :  <a href="guest_sub_cat_gift.php?user_id=<?=$user_id?>&cat_id=<?=$category_id?>"><?php echo $sub_category_name;?></a></h3>
</div>

	<a href="#" onclick="insert_item();"> New Guest </a>



	<div id="insert" style="display:none;padding:10px;">
		<form action="guests.php?user_id=<?=$user_id?>&cat_id=<?=(int)$get['cat_id']?>&sub_cat_id=<?=(int)$get['sub_cat_id']?>&page=<?=(int)$_GET['page']?>" method="post" name="insert_guest_form">
		<input type="hidden" name="insert" value="insert">
			Guest名前<br>
			<input type="text" name="name" style="width:185px;" id="name" value="<?=$row['name']?>"/>
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
           </select><br>
           内容<br>
		   <input type="text" name="description" style="width:250px;" id="description" value="<?=$row['description']?>"/><br>
           <input type="button" name="Insert" value="送信" onclick="validForm();"/> &nbsp; <input type="reset" value="キャンセル"  onclick="cancel_insert();" />

		</form>
	</div>
	<div id="edit" style="display:none;padding:10px;">

	<form action="guests.php?user_id=<?=$user_id?>&cat_id=<?=(int)$get['cat_id']?>&sub_cat_id=<?=(int)$get['sub_cat_id']?>&page=<?=(int)$_GET['page']?>" method="post" name="edit_guest_form">
			<input type="hidden" name="update" value="update">
			<div id="from_ajax">
			</div>
			<input type="button" name="Edit" value="送信" onclick="validForm_Edit();"/> &nbsp; <input type="reset" value="キャンセル"  onclick="cancel_update();" />
	</form>
</div>
<?php


	if($_GET['action']=='delete' && (int)$_GET['id'] > 0)
	{
		$sql = "delete from $table where id=".(int)$get['id'];
		mysql_query($sql);
		redirect('guests.php?sub_cat_id='.(int)$sub_cat_id.'&page='.$get['page']);
	}
	else if($_GET['action']=='sort' && (int)$_GET['id'] > 0)
	{
		$id = $get['id'];
		$move = $get['move'];
		$redirect = 'guests.php?sub_cat_id='.$sub_cat_id.'&page='.(int)$get['page'];

		$obj->sortItem($table,$id,$move,$redirect);
	}

?>
<div id="message_BOX">
	  <div class="page_next"><?php echo $pageination;?></div>
      <div class="box4">
          <table border="0" align="center" cellpadding="1" cellspacing="1">
            <tr align="center">
				<td>No.</td>
				<td>名前</td>
				<td>内容</td>
				<td> Subカテゴリー</td>
				<td>順序変更</td>
				<td>編集</td>
				<td>削除</td>
            </tr>
          </table>
        </div>


    <?php


		$query_string="SELECT t.*, ct.name as subcat FROM $table t left outer join spssp_guest_sub_category ct on t.sub_category_id=ct.id where t.sub_category_id=".$sub_cat_id."   ORDER BY display_order DESC limit ".((int)($current_page)*$data_per_page).",".((int)$data_per_page).";";
		$data_rows = $obj->getRowsByQuery($query_string);
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

		include("inc/main_dbcon.inc.php");
		$rsp = $obj->GetSingleData( "spssp_respect", "title"," id=".$row['respect_id']);
		include("inc/return_dbcon.inc.php");
	?>
		<div class="<?=$class?>">
	 	<table border="0" align="center" cellpadding="1" cellspacing="1">
            <tr align="center">
              <td><?=$j?></td>
        	<td><?=$row['name']." ".$rsp?></td>
            <td><?=$row['description']?></td>
            <td><?=$row['subcat']?></td>

            <td>
            	<a href="guests.php?action=sort&amp;move=up&amp;id=<?=$row['id']?>&user_id=<?=$user_id?>&amp;cat_id=<?=(int)$category_id?>&amp;sub_cat_id=<?=(int)$sub_cat_id?>">
                	▲
                </a> &nbsp;
              	<a href="guests.php?action=sort&amp;move=bottom&amp;id=<?=$row['id']?>&user_id=<?=$user_id?>&amp;cat_id=<?=(int)$category_id?>&amp;sub_cat_id=<?=(int)$sub_cat_id?>">
                	▼
                </a>
            </td>
            <td>
                	<a href="#" onclick="edit_guest(<?php echo $row['id'];?>);">
					<img src="img/common/btn_edit.gif" width="42" height="17" />
                </a>
            </td>
            <td>
            	<a href="javascript:void(0);" onClick="confirmDelete('guests.php?user_id=<?=$user_id?>&page=<?=(int)$_GET['page']?>&action=delete&id=<?=$row['id']?>&amp;cat_id=<?=(int)$category_id?>&amp;sub_cat_id=<?=(int)$sub_cat_id?>');">
                	<img src="img/common/btn_deleate.gif" width="42" height="17" />
                </a>
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
