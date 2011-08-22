<?php
	require_once("inc/class.dbo.php");
	include_once("inc/checklogin.inc.php");
	include_once("inc/new.header.inc.php");
	$obj = new DBO();
	
	$table='spssp_admin';
	$where = " 1=1";
	$data_per_page=10;
	$current_page=(int)$_GET['page'];
	$redirect_url = 'staffs.php';
	
	$pageination = $obj->pagination($table, $where, $data_per_page,$current_page,$redirect_url);
	
	$get = $obj->protectXSS($_GET);
	if($_GET['action']=='delete' && (int)$_GET['id'] > 0)
	{
		$sql = "delete from spssp_admin where id=".(int)$_GET['id'];
		mysql_query($sql);
		//redirect('staffs.php?page='.$_GET['page']);
	}
	else if($_GET['action']=='sort' && (int)$_GET['id'] > 0)
	{
		$table = 'spssp_admin';
		
		$id = $get['id'];
		$move = $get['move'];
		//$redirect = 'staffs.php?page='.(int)$get['page'];
		
		$obj->sortItem($table,$id,$move,$redirect);
	}
	else if($_GET['action']=='delete' && (int)$_GET['id'] > 0)
	{
		
		$obj->DeleteRow("spssp_admin", " id=".$get['id']);
	}
	
	//print_r($_POST);
	//Inser stuff or admin
	if($_POST['insert']=="insert")
	{
		if(trim($_POST['name']) && trim($_POST['username']) && trim($_POST['password'])&& trim($_POST['permission']))
		{
			$num=$obj->GetNumRows('spssp_admin',"username='".$_POST['username']."'");
			if(!$num)
			{
				$post = $obj->protectXSS($_POST);
				unset($post['insert']);
				$post['display_order']= time();
				//$post['creation_date'] = date("Y-m-d H:i:s");
				if($post['permission'] == '111')
				{
					$num_admin = $obj->GetNumRows("spssp_admin"," permission = '111'");
					if($num_admin > 0)
					{
						
						$err = 5;
					}
					else
					{
						$lastid = $obj->InsertData('spssp_admin',$post);
					}
				}
				else
				{
					$lastid = $obj->InsertData('spssp_admin',$post);
				}
				
				
				
				if($lastid > 0)
				{
					$msg=1;
					redirect("staffs.php?msg=".$msg."&page=".$current_page);
					
				}
				else if($lastid <= 0 && $err != 5)
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
		if(trim($_POST['name']) && trim($_POST['username']) && trim($_POST['password'])&& trim($_POST['permission']))
		{
			$num=$obj->GetNumRows('spssp_admin',"username='".$_POST['username']."'");
			if(!($num>1))
			{
				$post = $obj->protectXSS($_POST);
				unset($post['update']);
				//$post['display_order']= time();
				//$post['creation_date'] = date("Y-m-d H:i:s");
				
				
				
				
				if($post['permission'] == '111')
				{
					$num_admin = $obj->GetNumRows("spssp_admin"," permission = '111'");
					if($num_admin > 0)
					{
						
						$err = 5;
					}
					else
					{
						$msg = 2;
						$obj->UpdateData('spssp_admin',$post,"id=".$_POST['id']);
					}

				}
				else
				{
					$msg = 2;
					$obj->UpdateData('spssp_admin',$post,"id=".$_POST['id']);
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
	if($_GET['action']=="edit")
	{
		$edit_data_rows = $obj->GetSingleRow("spssp_admin"," id=".$_GET['id']);
		
	}
	$query_string="SELECT * FROM spssp_admin where id!=".$_SESSION['adminid']." ORDER BY display_order ASC LIMIT ".((int)($current_page)*$data_per_page).",".((int)$data_per_page).";";
	$data_rows = $obj->getRowsByQuery($query_string);
	
	
	
	
	/*COUNT MANAGER ADMIN WHICH COULD BE ONLY ONE*/
	if($_GET['action']=="edit")
	{
		$num_of_manager_admin=$obj->GetNumRows($table," id !=".$_GET['id']." and permission=333");
	}else
	{
		$num_of_manager_admin=$obj->GetNumRows($table," permission=333");
	}
	
	
?>

<script type="text/javascript">

$(function(){

	var msg_html=$("#msg_rpt").html();

	if(msg_html!='')
	{
		$("#msg_rpt").fadeOut(5000);
	}
});
$(document).ready(function(){
    
    $('#password').keyup(function(){        
		var r=checkvalidity();		
    });	
});
function checkvalidity()
{
	var password = $('#password').val();
	var c = password.length;
	if(c<6)
	{
		$('#password_msg').html("<font style='color:red;'>数字6文字以上にしてください</font>");
	}
	else
	{
		$('#password_msg').html("");	
	}
	
    // All characters are numbers.
    return true;
}
function validForm()
{
	var name  = document.getElementById('name').value;
	var ID  = document.getElementById('ID').value;
	var password  = document.getElementById('password').value;
	var radio2  = document.stuff_form.permission;
	
	var flag = true;
	if(!name)
	{
		 alert("名前が未入力です。");
		 document.getElementById('name').focus();
		 return false;
	}
	if(!ID)
	{
		 alert("UserIDが未入力です。");
		 document.getElementById('ID').focus();		 
		 return false;
	}
	if(!password)
	{
		 alert("パスワードが未入力です。");
		 document.getElementById('password').focus();		 
		 return false;
	}
	else
	{
		var c = document.getElementById("password").value.length;
		if(c<6)
		{
			alert("パスワードを少なくとも6文字 ");
			document.getElementById('password').focus();
			return false;
		}  
	}
	
	for (i=0; i<radio2.length; i++)
	{
  		if (radio2[i].checked == true)
  		{
			if(!radio2[i].value)
			{
				 alert("権限を選択してください");
				 document.getElementById('permission').focus();		 
				 return false;
			}
		}
	}
	document.stuff_form.submit();
}
function manager_admin_confirme(p)
{
	doIt=confirm('管理者を変更しても宜しいですか？');
	if(doIt)
	{
		$.post("ajax/admin_permission_update.php",{'p':p},function(data){
			//alert(data);
			//var substrs = data.split('#');
			//$("#v_no").html("No."+no);
			//$("#v_title").html(substrs[2]);	
			$("#m_admin").html("スタッフ");	
			
		});
	}else
	{
		$('input:radio[name=permission]')[1].checked = true;
	}
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

<div style="clear:both;"></div>   
	<div id="contents"> 
	 <h2>       	
            	 スタッフ
        </h2>
		<h2>スタッフ登録・編集・削除</h2>
        	<?php if($err){
			echo "<script>
			alert('".$obj->GetErrorMsgNew($err)."');
			</script>";
			} 
			if($_GET['msg'])
			echo "<script>
			alert('".$obj->GetSuccessMsgNew($_GET['msg'] )."');
			</script>";
			
        	if($_SESSION['user_type'] == 111 ||$_SESSION['user_type'] == 333)
			{
		?>
		<form  action="staffs.php?page=<?=$current_page?>" method="post" name="stuff_form">
		<?php if($_GET['action']=="edit"){?>
		<input type="hidden" name="update" value="update">
		<input type="hidden" name="id" value="<?=$_GET['id']?>">
		<?php }else{?>
		<input type="hidden" name="insert" value="insert">
		<?php }?>
		
		<p class="txt3">
           <table>
		   <tr>
		   		<td width="190" valign="top"><label for="名前">名前：</label>
		    <input name="name" type="text" id="name" size="20" value="<?=$edit_data_rows['name']?>" />
        　	</td>
			<td  width="140" valign="top">
			User ID：
            <input name="username" type="text" id="ID" size="10"  value="<?=$edit_data_rows['username']?>"/>    　
            </td>
			<td  width="160" valign="top">
            <label for="パスワード">パスワード：</label>
            <input name="password" type="text" id="password" size="13"  value="<?=$edit_data_rows['password']?>"  onblur="checkvalidity()"/><br>
			<span id="password_msg">英数字6文字以上にしてください</span>
			</td>
			<td valign="top">
        　	権限：
            <?php if($edit_data_rows['permission']==111){ ?>
			<input type="radio" name="permission" id="radio2" value="111" <?php if($edit_data_rows['permission']==111){echo "checked='checked'";}?> />
            管理者　			
			<?php }else{ ?>            
			<input type="radio" name="permission" id="radio2"  <?php if($num_of_manager_admin==1){?> onclick="manager_admin_confirme(1);" <?php }?> value="333" <?php if($edit_data_rows['permission']==333){echo "checked='checked'";}?> />
            
            管理者　
			<input type="radio" name="permission" id="radio2" value="222" <?php if($edit_data_rows['permission']==222){echo "checked='checked'";}?>/>
			 スタッフ
			
            <?php }?>
			 </td>
			</tr>
			</table>
			<br />
            
            <label for="権限"></label> <a href="#" onclick="validForm();"><img src="img/common/btn_regist.jpg" alt="登録" width="62" height="22" /></a>
        </p>
        </form>
        
        <div class="box_table">
      		
      		<div class="page_next"><?=$pageination?></div>
            
      		<div class="box4">
                <table border="0" align="center" cellpadding="1" cellspacing="1">
                    <tr align="center">
                        <td>名前</td>
                        <td>ID</td>
                        <td>パスワード</td>
                        <td>権限</td>
                        <td>順序変更</td>
                        <td>編集</td>
                        <td>削除</td>
                    </tr>
                </table>
        	</div>
            <?php
				$i=0;
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
                            <td><?=$row['name']?></td>
                            <td><?=$row['username']?></td>
                            <td><?=$row['password']?></td>
                            <td <?php if($row['permission']==333){?>id="m_admin"<?php }?>><?php if($row['permission']==222){echo 'スタッフ';} if($row['permission']==111){echo 'スーパー管理者';}if($row['permission']==333){echo '管理者';}?></td>
                            <td><form id="form1" name="form1" method="post" action="">
                              <span class="txt1"><a href="staffs.php?page=<?=$current_page?>&action=sort&amp;move=bottom&amp;id=<?=$row['id']?>">▲</a> 
                             <a href="staffs.php?page=<?=(int)$_GET['page']?>&action=sort&amp;move=up&amp;id=<?=$row['id']?>"> ▼</a></span>
                            </form></td>
                            <td><a href="staffs.php?id=<?=$row['id']?>&action=edit&page=<?=$current_page?>"><img src="img/common/btn_edit.gif" width="42" height="17" /></a></td>
                            <td>
                            	<a href="javascript:void(0);" onClick="confirmDelete('staffs.php?page=<?=(int)$_GET['page']?>&action=delete&id=<?=$row['id']?>');">
                            		<img src="img/common/btn_deleate.gif" width="42" height="17" />
                                </a>
                            </td>
                           </tr>
                        </table>
                    </div>
             <?php
			 	$i++;
			 	}
			 ?>
        </div>
        <?php
        	}
			else
			{
				
				$stuff_row = $obj->GetSingleRow("spssp_admin", " id=".(int)$_SESSION['adminid']);
			?>
            	<p class="txt3">名前：<label for="名前"></label>
                    <input name="name" type="text" id="name" size="20" value="<?=$stuff_row['name']?>" readonly="readonly" />
                　	User ID：
                    <input name="username" type="text" id="ID" size="10"  value="<?=$stuff_row['username']?>" readonly="readonly" />    　
                    パスワード：
                    <label for="パスワード"></label>
                    <input name="password" type="text" id="password" size="10"  value="<?=$stuff_row['password']?>" readonly="readonly" />
                   
        		</p>
            <?php
			}
		?>       
    </div>
</div>

<?php	
	include_once("inc/left_nav.inc.php");
?>

        
<?php	
	include_once("inc/new.footer.inc.php");
?>

<?php
	/*include_once("inc/dbcon.inc.php");
	
	include_once("inc/checklogin.inc.php");
	
	
	include_once("inc/new.header.inc.php");
	require_once("inc/class.dbo.php");
	$obj = new DBO();
	$table='spssp_stuff';
	$where = " 1=1";
	$data_per_page=10;
	$current_page=(int)$_GET['page'];
	$redirect_url = 'staffs.php';
	
	$pageination = $obj->pagination($table, $where, $data_per_page,$current_page,$redirect_url);*/
	

?>
<!--<div id="topnavi">
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
    <div id="contents"> 
        <h2>スタッフ登録・編集・削除</h2>
		<p class="txt3">名前：<label for="名前"></label>
            <input name="名前" type="text" id="名前" size="20" />
        　	ID：
            <input name="ID" type="text" id="ID" size="10" />    　
            パスワード：
            <label for="パスワード"></label>
            <input name="パスワード" type="text" id="パスワード" size="10" />
        　	権限：
            <input type="radio" name="権限" id="radio2" value="管理者" />
            <label for="権限"></label>
            管理者　
            <input name="権限" type="radio" id="radio3" value="スタッフ" checked="checked" /> スタッフ
            <br />
            <br />
            <label for="権限"></label> <a href="#"><img src="img/common/btn_regist.jpg" alt="登録" width="62" height="22" /></a>
        </p>
        <div class="box_table">
      		<p></p>
      		
       </div>
    </div>-->
 
<?php
	
/*	echo "<p style='text-align:left; text-indent:10px'><a href='stuff_new.php?page=".$current_page."'>Create New Stuff</a>";		
	echo $pageination;
	$get = $obj->protectXSS($_GET);
	if($_GET['action']=='delete' && (int)$_GET['id'] > 0)
	{
		$sql = "delete from spssp_stuff where id=".(int)$_GET['id'];
		mysql_query($sql);
		redirect('staffs.php?page='.$_GET['page']);
	}
	else if($_GET['action']=='sort' && (int)$_GET['id'] > 0)
	{
		$table = 'spssp_stuff';
		
		$id = $get['id'];
		$move = $get['move'];
		$redirect = 'staffs.php?page='.(int)$get['page'];
		
		$obj->sortItem($table,$id,$move,$redirect);
	}
	else if($_GET['action']=='delete' && (int)$_GET['id'] > 0)
	{
		
		$obj->DeleteRow("spssp_stuff", " id=".$get['id']);
	}*/
?>


<!--	<table width="100%" align="center" cellspacing="0" cellpadding="3" border="0" class="grid" >
    	<tr class="head">
     
        	<td width="150px"> Name</td>
            <td width="60px" align="center">Zip</td> 
            <td width="60px" align="center">Address</td> 
            <td width="60px" align="center">Telephone</td> 
            <td width="60px" align="center">Email</td> 
            <td width="60px" align="center">Fax</td> 
            <td width="100px" align="center">Stuff Id</td>
            <td width="60px" align="center">Password</td>
            <td width="60px" align="center">Status</td>
            
            <td valign="middle" width="10%" nowrap="nowrap">順序変更</td>
			<td valign="middle" width="5%" nowrap="nowrap">編集</td>
			<td valign="middle" width="5%" nowrap="nowrap">削除</td>
		</tr>-->
    <?php	
		
		
	/*	$query_string="SELECT * FROM spssp_stuff  ORDER BY display_order DESC LIMIT ".((int)($current_page)*$data_per_page).",".((int)$data_per_page).";";
		$data_rows = $obj->getRowsByQuery($query_string);
		//echo "<pre>";
		//print_r($data_rows);exit;
		$i=0;
		foreach($data_rows as $row)
		{*/		
	?>
		<!--<tr <?php if($i%2==0){?>style="background:#FFFEEA"<?php }else{?>style="background:#FFFFDA"<?php }?>>
        	<td><?=$row['name']?></td>
            <td><?=$row['zip']?></td>
            <td><?=$row['address']?></td>
            <td><?=$row['telephone']?></td>
            <td><?=$row['email']?></td>
            <td><?=$row['fax']?></td>
            <td><?=$row['stuff_id']?></td>
            <td><?=$row['password']?></td>
            <td><?=$row['status']?></td>
            
            <td valign="top" width="10%" nowrap="nowrap">
            	<a href="staffs.php?action=sort&amp;move=up&amp;id=<?=$row['id']?>">▲</a> &nbsp;
                <a href="staffs.php?action=sort&amp;move=bottom&amp;id=<?=$row['id']?>">▼</a>
            </td>
            <td><a href="stuff_edit.php?id=<?=$row['id']?>&page=<?=(int)$_GET['page']?>">編集</a></td>
            <td><a href="javascript:void(0);" onClick="confirmDelete('staffs.php?page=<?=(int)$_GET['page']?>&action=delete&id=<?=$row['id']?>');">削除</a></td>
        </tr>-->
        <?php
     //$i++;   }
		?>
<!--	</table>
</div>-->
