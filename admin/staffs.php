<?php
	require_once("inc/class.dbo.php");
	include_once("inc/checklogin.inc.php");
	include_once("inc/new.header.inc.php");
	include_once(dirname(__FILE__)."/../conf/conf.php");
	$obj = new DBO();


	$get = $obj->protectXSS($_GET);
	$post = $obj->protectXSS($_POST);

	if($get['action']=='delete' && (int)$get['id'] > 0)
	{
		$sql = "delete from spssp_admin where id=".(int)$get['id'];
		mysql_query($sql);
		if ($get['edit_id']>0) {
			$get['action']="edit";
			$get['id'] = $get['edit_id'];
		}
		//redirect('stuffs.php');
	}

	//print_r($post);
	if($post['insert']=="insert")
	{
		if(trim($post['name']) && trim($post['username']) && trim($post['password'])&& trim($post['permission']))
		{
			$num=$obj->GetNumRows('spssp_admin',"username='".$post['username']."'");
			if(!$num)
			{
				unset($post['insert']);
				unset($post['conf_email']);
				unset($post['permission_old']);
				$post['display_order']= time();

				if($post['permission'] == '222')
				{
					$lastid = $obj->InsertData('spssp_admin',$post);
					echo "<script> alert('新しいスタッフが登録されました'); </script>";
				}
				else
				{
			        $post['stype'] = 1;
			        //管理者を登録
			        //既存の管理者をスタッフに変更
					if($post['permission'] == '333')
					{
					   $value['permission']='222';
             		   $value['stype'] = 0;
					   $where = "permission = '333'";
					   $obj->UpdateData('spssp_admin',$value, $where);
					}
					  $lastid = $obj->InsertData('spssp_admin',$post);
					  echo "<script> alert('新しいスタッフが登録されました'); </script>";
					  
					  if($post['permission'] == '333') {					  
				          $upd_data = array();
				          $upd_data["adminid"] = $post["username"];
				          $upd_data["password"] = $post["password"];
				          $upd_data["email"] = $post["email"];
				          $upd_data["adminstrator"] = $post["name"];
				          mysql_connected($main_sqlhost,$main_sqluser,$main_sqlpassword,$main_sqldatabase);
				          $obj->UpdateData("super_spssp_hotel",$upd_data,"hotel_code=".$HOTELID."");
				          //$obj->UpdateData("super_spssp_admin",$upd_data,"hotel_code=".$HOTELID);
				          mysql_connected($hotel_sqlhost,$hotel_sqluser,$hotel_sqlpassword,$hotel_sqldatabase);
					  }
				}

				if($lastid > 0)
				{
					$msg=130;

					redirect("staffs.php");

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
	if($post['update']=="update")
	{
		if(trim($post['name']) && trim($post['username']) && trim($post['password']))
		{
			$num=$obj->GetNumRows('spssp_admin'," id!=".$post['id']." and username='".$post['username']."'");

			if(!($num>0))
			{
				$post = $obj->protectXSS($post);
				unset($post['update']);
				unset($post['conf_email']);
				unset($post['permission_old']);

				if($post['permission'] == '222')
				{
		          $msg = 2;
		          $obj->UpdateData('spssp_admin',$post,"id=".$post['id']);
		          echo "<script> alert('スタッフ情報が更新されました'); </script>";
				}
				else
				{
 			        $post['stype'] = 1;
					if($post['permission'] == '333')
					{
					   $value['permission']='222';
					   $where = "permission = '333'";
       	  			   $value['stype'] = 0;
					   $obj->UpdateData('spssp_admin',$value, $where);
//		          	   echo "<script> alert('スタッフ情報が更新されました'); </script>";
					}

					$msg = 2;
					$obj->UpdateData('spssp_admin',$post,"id=".$post['id']);
					echo "<script> alert('スタッフ情報が更新されました'); </script>";
					if($post['permission'] == '333') {
			          $upd_data = array();
			          $upd_data["adminid"] = $post["username"];
			          $upd_data["password"] = $post["password"];
			          $upd_data["adminstrator"] = $post["name"];

			          $link = mysql_connected($main_sqlhost,$main_sqluser,$main_sqlpassword,$main_sqldatabase);

			          $obj->UpdateData("super_spssp_hotel ",$upd_data,"hotel_code=".$HOTELID);
			          mysql_connected($hotel_sqlhost,$hotel_sqluser,$hotel_sqlpassword,$hotel_sqldatabase);
					}
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
	if($get['action']=="edit")
	{
		$edit_data_rows = $obj->GetSingleRow("spssp_admin"," id=".$get['id']);
	}
	//SORING START
	if(isset($get['order_by']) && $get['order_by'] != '')
	{
		$orderby = mysql_real_escape_string($get['order_by']);
		$dir = mysql_real_escape_string($get['asc']);

		if($orderby=='name')
		{
			$order=" name ";

		}

		else if($orderby=='ID')
		{
			$order=" username ";
		}
		if($dir == 'true')
		{
			$order.=' asc';
			$getOrder="&order_by=name&asc=true";
		}
		else
		{
			$order.=' desc';
			$getOrder="&order_by=name&asc=false";
		}

		$order_main=" permission DESC,".$order;
		if($get['action']!="edit") {
			$edit_data_rows['name']				=$get['name'];
			$edit_data_rows['username']			=$get['username'];
			$edit_data_rows['password']			=$get['password'];
			$edit_data_rows['permission']		=$get['permission'];
			$edit_data_rows['subcription_mail']	=$get['subcription_mail'];
			$edit_data_rows['email']			=$get['email'];
			$edit_data_rows['conf_email']		=$get['conf_email'];
		}
	}
	else
	{
		$order_main=" permission DESC";
	}
//echo "edit : ".$get['id'];
	$query_string="SELECT * FROM spssp_admin where permission!=111  ORDER BY  $order_main ;";
	$data_rows = $obj->getRowsByQuery($query_string);
?>

<script type="text/javascript">
var nameArray=new Array();
var idArray=new Array();
var emailArray=new Array();
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

function validForm()
{
	var name  = document.getElementById('name').value;
	var ID  = document.stuff_form.username.value;
	var name_current  = document.getElementById('name_current').value;
	var username_current  = document.getElementById('username_current').value;
	var email_current  = document.getElementById('email_current').value;
	var password  = document.getElementById('password').value;
	var radio2  = document.stuff_form.permission;
	var email = document.getElementById('email').value;
	var com_email = document.getElementById('conf_email').value;
	var radio3  = document.stuff_form.subcription_mail;
	var permission=false;

	var permission_old  = document.getElementById('permission_old').value;
	var reg = /^[A-Za-z0-9\!\#\$\%\&\(\)\*\+\-\.\d\/\:\;\<\=\>\?\@\[\]\^\_\`\{\|\}\~]{1,15}$/;
	var reg2 = /^[A-Za-z0-9](([_|\.|\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([_|\.|\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})$/;
	var flag = true;
	if(!name)
	{
		 alert("名前が未入力です");
		 document.getElementById('name').focus();
		 return false;
	}
	if($.inArray(name,nameArray)!=-1 && name_current!=name)
	{
		alert("同じ名前があるため登録・変更できません");
		document.getElementById('name').focus();
		return false;
	}
	if(!ID)
	{
		 alert("ログインIDが未入力です");
		 document.getElementById('ID').focus();
		 return false;
	}
	if($.inArray(ID,idArray)!=-1 && username_current!=ID)
	{
		alert("ログインIDが既に登録されています");
		document.getElementById('ID').focus();
		return false;
	}
	if(ID.length > 15)
	{
			alert("ログインIDは15文字以内で入力してください");
			document.getElementById('ID').focus();
			return false;
	}
	
	
	if(reg.test(ID) == false) {
		alert("ログインIDは半角英数字記号で入力してください");
		document.getElementById('ID').focus();
		return false;
	}
	
	if(!password)
	{
		 alert("パスワードが未入力です");
		 document.getElementById('password').focus();
		 return false;
	}
	else
	{
		var c = document.getElementById("password").value.length;
		if(c<6)
		{
			alert("パスワードは6文字以上で入力してください");
			document.getElementById('password').focus();
			return false;
		}
		if(c>15)
		{
			alert("パスワードは15文字以内で入力してください");
			document.getElementById('password').focus();
			return false;
		}
	}
 	if(reg.test(password) == false) {
		alert("パスワードは半角英数字記号で入力してください");
		document.getElementById('password').focus();
		return false;
	 }

	for (i=0; i<radio2.length; i++)
	{
  		if (radio2[i].checked == true)
  		{
			permission=true;
		}

	}
	if(permission==false)
	{
		 alert("権限を選択してください");
		 document.getElementById('permission').focus();
		 return false;
	}

	if(email!='')
	{
		if($.inArray(email,emailArray)!=-1 && email_current!=email)
		{
				alert("同じメールアドレスがあるため登録・変更できません");
				document.getElementById('email').focus();
				return false;
		 }
	}
	if(radio3[0].checked)
	{
		if(email=='')
		 {
			alert("メールを受信する場合は、メールアドレスは必須です");
			document.getElementById('email').focus();
			return false;
		  }
		else
		  {
			if(email_validate(email)==false)
			{
				alert("正しいメールアドレスではありません");//Enter a valid email address.
				document.getElementById('email').focus();
				return false;

			}

		  }

		if(com_email=='')
		  {
			alert("メールアドレス確認用を正しく入力してください");
			document.getElementById('conf_email').focus();
			return false;
		 }
		 else
		 {
			if(matchMail())
			{
				alert("メールアドレスが一致しません。再度入力してください");
				document.getElementById('conf_email').focus();
				return false;
			}
			else
			{
				if(permission_old=="222" || permission_old=="")
				{
					if(radio2[0].checked)
					{
						if(!confirm("管理者を変更してよいですか？"))
						{
							return false;
						}
					}
				}
				document.stuff_form.submit();
			}
		}
	}
	else
	{
		if(matchMail())
		{
			alert("メールアドレスが一致しません。再度入力してください");
			document.getElementById('conf_email').focus();
			return false;
		}

		if(permission_old=="222" || permission_old=="")
		{
			if(radio2[0].checked)
			{
				if(!confirm("管理者を変更してよいですか？"))
				{
					return false;
				}

			}
		}
		document.stuff_form.submit();
	}
}
function validForm_staff()
{

	var name  = document.getElementById('name').value;
	var ID  = document.getElementById('ID').value;
	var password  = document.getElementById('password').value;
	var email = document.getElementById('email').value;
	var radio9  = document.stuff_form2.subcription_mail;
	var name_current  = document.getElementById('name_current').value;
	var username_current  = document.getElementById('username_current').value;
	var email_current  = document.getElementById('email_current').value;

	var reg = /^[A-Za-z0-9\!\#\$\%\&\(\)\*\+\-\.\/\:\;\<\=\>\?\@\[\]\^\_\`\{\|\}\~]{1,15}$/;
	var reg2 = /^[A-Za-z0-9](([_|\.|\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([_|\.|\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})$/;
	var flag = true;
	var com_email = document.getElementById('conf_email').value; // UCHIDA EDIT 11/07/25

	if(!name)
	{
		 alert("名前が未入力です");
		 document.getElementById('name').focus();
		 return false;
	}
	if($.inArray(name,nameArray)!=-1 && name_current!=name)
	{
		alert("同じ名前があるため登録・変更できません");
		document.getElementById('name').focus();
		return false;
	}
	if(!ID)
	{
		 alert("ログインIDが未入力です");
		 document.getElementById('ID').focus();
		 return false;
	}
	else if($.inArray(ID,idArray)!=-1 && username_current!=ID)
	{
		alert("ログインIDが既に登録されています");
		document.getElementById('ID').focus();
		return false;
	}
	else
	{
		 if(reg.test(ID) == false) {
		 	alert("ログインIDは英数字で入力してください");
			document.getElementById('ID').focus();
		 	return false;
		 }
	}
	if(email!='')
	{
		if($.inArray(email,emailArray)!=-1 && email_current!=email)
		{
				alert("同じメールアドレスがあるため登録・変更できません");
				document.getElementById('email').focus();
				return false;
		 }
	}
	if(radio9[0].checked)
	{
		if(email=='')
		{
			alert("メールを受信する場合は、メールアドレスは必須です");
			document.getElementById('email').focus();
			return false;
		}
		else
		{
			if(email_validate(email)==false)
			{
				alert("正しいメールアドレスではありません");//Enter a valid email address.
				document.getElementById('email').focus();
				return false;
			}
		}
		if($.inArray(email,emailArray)!=-1 && email_current!=email)
		{
			alert("同じメールアドレスがあるため登録・変更できません");
			document.getElementById('email').focus();
			return false;
		}
		if(com_email=='')
		  {
			alert("メールアドレス確認用を正しく入力してください");
			document.getElementById('conf_email').focus();
			return false;
		 }
		 else
		 {
			if(document.getElementById('conf_email').value != email)
			{
				alert("メールアドレスが一致しません。再度入力してください");
				document.getElementById('conf_email').focus();
					return false;
			}
		}
	}
	else {
		if(document.getElementById('conf_email').value != email)
		{
			alert("メールアドレスが一致しません。再度入力してください");
			document.getElementById('conf_email').focus();
				return false;
		}
	}

	if(!password)
	{
		 alert("パスワードが未入力です");
		 document.getElementById('password').focus();
		 return false;
	}
	else
	{
		var c = document.getElementById("password").value.length;
		if(c<6)
		{
			alert("パスワードは英数字6文字以上にしてください");
			document.getElementById('password').focus();
			return false;
		}
		if(c>15)
		{
			alert("パスワードは15文字以内で入力してください");
			document.getElementById('password').focus();
			return false;
		}
	}
	if(reg.test(password) == false) {
		alert("パスワードは英数字記号で入力してください");
		document.getElementById('password').focus();
		return false;
	 }

	document.stuff_form2.submit();
}
function email_validate(email) {
   var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
   if(reg.test(email) == false) {
       return false;
   }
   else
   {
   		return true;
   }
}
function matchMail()
{
	var mail = $("#email").val();
	var conemail = $("#conf_email").val();
	if(mail != conemail)
	{
		return true;

	}
}
function manager_admin_confirme(p)
{
	var err
	var name  = document.getElementById('name').value;
	var ID  = document.getElementById('ID').value;
	var password  = document.getElementById('password').value;

	if(!name)
	{
		err=1;
	}
	if(!ID)
	{
		err=1;
	}
	if(!password)
	{
		err=1;
	}
	if(err==1)
	{
		alert("すべて入力してください");
	}
	else
	{
		doIt=confirm('管理者を変更しても宜しいですか？');
	}
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
function clearform()
{
	$('#name').attr("value","");
	$('#ID').attr("value","");
	$('#password').attr("value","");
	$('#email').attr("value","");
	$('#conf_email').attr("value","");
}

function confirmDeletePlus(urls, permission, id)
{
	if(permission == '333') {
		alert("管理者は削除できません。\n他のスタッフへ権限を移し、新しい管理者から削除してください");
		return false;
	}
	var edit_id="<?=$get['id']?>";
	var agree = confirm("削除してもよろしいですか？");
	if(agree)
	{
		if (edit_id != id) window.location = collecting_data(urls)+"&edit_id="+edit_id;
		else               window.location = urls;
	}
}

</script>
<div id="topnavi" >
    <?php
include("inc/main_dbcon.inc.php");
$hcode=$HOTELID;
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

<div style="clear:both;"></div>
	<div id="contents">
	 <h4>
            	<div style="width:200px;"> スタッフ</div>
        </h4>
		<h2><div style="width:180px;">スタッフ設定</div></h2>
        	<?php
        	if($_SESSION['user_type'] == 111 ||$_SESSION['user_type'] == 333)
			{
		?>
		<div style="width:1000px;">
		<form  action="staffs.php" method="post" name="stuff_form">
		<?php if($get['action']=="edit"){?>
		<input type="hidden" name="update" value="update">

		<input type="hidden" name="id" value="<?=$get['id']?>">
		<?php }else{?>
		<input type="hidden" name="insert" value="insert">
		<?php }?>

		<input type="hidden" name="permission_old" id="permission_old" value="<?=$edit_data_rows['permission']?>">

		<p class="txt3">
			<table style="width:1000px; border="0" cellspacing="6" cellpadding="0">
				<tr>
				  <td width="60" nowrap="nowrap">名前<font color="red">*</font></td>
			    <td width="5" nowrap="nowrap">：</td>
					<td width="300" nowrap="nowrap">
	    		  <input name="name" type="text" id="name" size="25" style="border-style: inset;" value="<?=$edit_data_rows['name']?>" />
					<input  type="hidden" id="name_current" size="20" value="<?=$edit_data_rows['name']?>" />
					</td>
				</tr>
				<tr>
				  <td align="left" nowrap="nowrap">ログインID<font color="red">*</font></td>
				  <td align="left" nowrap="nowrap">：</td>
					<td align="left" nowrap="nowrap">
					<input name="username" type="text" id="ID" size="25" style="border-style: inset;" value="<?=$edit_data_rows['username']?>"/>
					<input  type="hidden" id="username_current" size="20" value="<?=$edit_data_rows['username']?>" />
	            	</td>
				</tr>
				<tr>
				  <td align="left" valign="middle" nowrap="nowrap">パスワード<font color="red">*</font></td>
			    <td align="left" valign="middle" nowrap="nowrap">：</td>
					<td align="left" valign="middle" nowrap="nowrap">
					<input name="password" type="text" id="password" size="25" style="border-style: inset;" value="<?=$edit_data_rows['password']?>"  onblur="checkvalidity()"/><br>
						<span id="password_msg" style="color:#FF0000;font-size:8px;">半角英数字記号6文字以上15文字以下にしてください。<br>記号は以下の文字が利用できます(! # $ % & ( ) * + - . / : ; < = > ? @ [ ] ^ _ ` { | } ~)</span>
					</td>
				</tr>
				<tr>
				  <td align="left" nowrap="nowrap">メールを受信する</td>
				  <td align="left" nowrap="nowrap">：</td>
					<td nowrap="nowrap">
				<input type="radio" name="subcription_mail" value="0" <?php echo ($edit_data_rows['subcription_mail']=='0' && $edit_data_rows['email'] !='')?"checked":"";?> /> 受信する
				<input type="radio" name="subcription_mail" value="1" <?php if ($edit_data_rows['subcription_mail']=='1' || !isset($edit_data_rows['subcription_mail'])) echo "checked";?>/>  受信しない
					</td>
				</tr>
                				<tr>
                				  <td align="left" nowrap="nowrap">権限</td>
					<td align="left" nowrap="nowrap">： </td>
                    <td nowrap="nowrap">
				<?php if($edit_data_rows['permission']==333){ ?>
						<input type="radio" name="permission" id="radio2" value="333" checked="checked"  disabled="disabled" />
						管理者　
				<input type="radio" name="permission" id="radio2" value="222" disabled="disabled"  />
			 			スタッフ
			<?php   }
					else if($edit_data_rows['permission']==222 || isset($edit_data_rows['permission'])==false) { ?>
					  <input type="radio" name="permission" id="radio2" value="333" />
						管理者　
				<input type="radio" name="permission" id="radio2" value="222" checked="checked" />
			 			スタッフ
			<?php   }?>
					</td>
				</tr>
				<tr>
				  <td align="left" nowrap="nowrap">メールアドレス</td>
				  <td align="left" nowrap="nowrap">：</td>
					<td align="left" nowrap="nowrap">
						<input name="email" type="text" id="email" size="25" style="border-style: inset;" value="<?=$edit_data_rows['email']?>" />
					                        <input  type="hidden" id="email_current" size="20" value="<?=$edit_data_rows['email']?>" />
					</td>
				</tr>
				<tr>
				  <td align="left" nowrap="nowrap">メールアドレス確認用</td>
				  <td align="left" nowrap="nowrap">：</td>
				<td width="160" nowrap="nowrap" onpaste="alert('メールアドレス確認用は貼り付けできません');return false;">
					<input name="conf_email" type="text" id="conf_email" size="25" style="border-style: inset;" value="<?=$edit_data_rows['email']?>" />
					</td>
				</tr>
			</table>
<br>

            <label for="権限"></label><div style="width:800px; padding-left:156px; text-align:left;"> <a href="#" onclick="validForm();"><img src="img/common/btn_regist_update.jpg" alt="登録・更新" width="82" height="22" /></a>&nbsp;&nbsp;&nbsp;
			<?php if($get[id]=="") { ?>
			<a  href="#" onclick="clearform();"><img src="img/common/btn_clear.jpg" alt="クリア" width="82" height="22" /></a>
			<?php } else { ?>
			<a  href="#" onclick="window.location='staffs.php?page=<?=$get[page]?>';"><img src="img/common/btn_clear.jpg" alt="クリア" width="82" height="22" /></a>
			<?php } ?>
			</div>
        </p>
        </form>
        </div>
        
        <br /><br />
        
        <div class="box_table" style="width: 1000px;">

      		<!--<div class="page_next"><?=$pageination?></div>-->

      		<div class="box4">
                <table border="0" align="center" cellpadding="1" cellspacing="1">
                    <tr align="center">
                        <td nowrap="nowrap">名前
						<span class="txt1">
							<a href="javascript:void(0);" onClick="sortAction('staffs.php?id=<?=$get['id']?>&order_by=name&asc=true');">▲</a>
                        	<a href="javascript:void(0);" onClick="sortAction('staffs.php?id=<?=$get['id']?>&order_by=name&asc=false');">▼</a>
                        </span>
						</td>
                      <td nowrap="nowrap">ログインID</td>
                      <td nowrap="nowrap">パスワード</td>
                      <td nowrap="nowrap">権限</td>
                      <td nowrap="nowrap">編集</td>
                        <td nowrap="nowrap">削除</td>
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
			<script language="javascript" type="text/javascript">
			nameArray[<?=$i?>]="<?=$row['name']?>";
			idArray[<?=$i?>]="<?=$row['username']?>";
			emailArray[<?=$i?>]="<?=$row['email']?>";

			</script>
                    <div class="<?=$class?>" id="boxid<?=$row['id']?>">
                        <table border="0" align="center" cellpadding="1" cellspacing="1">
                            <tr align="center">
                            <td nowrap="nowrap"><?=$row['name']?></td>
                            <td nowrap="nowrap"><?=$row['username']?></td>
                            <td nowrap="nowrap"><?=$row['password']?></td>
                            <td nowrap="nowrap" <?php if($row['permission']==333){?>id="m_admin"<?php }?>><?php if($row['permission']==222){echo 'スタッフ';} if($row['permission']==111){echo 'スーパー管理者';}if($row['permission']==333){echo '管理者';}?></td>
                            <td nowrap="nowrap">
							<?php //if($row['id']!=$_SESSION['adminid']) { ?>
							<a href="staffs.php?id=<?=$row['id']?>&action=edit<?=$getOrder?>"><img src="img/common/btn_edit.gif" width="42" height="17" /></a>
							<?php //} ?>
							</td>
                            <td nowrap="nowrap">
							<?php// if($row['id']!=$_SESSION['adminid']) { ?>
                            	<a href="javascript:void(0);" onClick="confirmDeletePlus('staffs.php?action=delete&id=<?=$row['id']?><?=$getOrder?>', <?=$row['permission']?>, <?=$row['id']?>);">
                            		<img src="img/common/btn_deleate.gif" width="42" height="17" />
                                </a>
							<?php // } ?>
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

				$i=0;
				foreach($data_rows as $row)
				{
				?>
				<script language="javascript" type="text/javascript">
					nameArray[<?=$i?>]="<?=$row['name']?>";
					idArray[<?=$i?>]="<?=$row['username']?>";
					emailArray[<?=$i?>]="<?=$row['email']?>";
				</script>
				<?php
				$i++;
				}

				$stuff_row = $obj->GetSingleRow("spssp_admin", " id=".(int)$_SESSION['adminid']);

			?>
            	<div style="width:1000px;">
				<form  action="staffs.php" method="post" name="stuff_form2">
				<input type="hidden" name="update" value="update">
				<input type="hidden" name="id" value="<?=(int)$_SESSION['adminid']?>">
				<input  type="hidden" id="name_current" size="20" value="<?=$stuff_row['name']?>" />
				<input  type="hidden" id="username_current" size="20" value="<?=$stuff_row['username']?>" />
				<input  type="hidden" id="email_current" size="20" value="<?=$stuff_row['email']?>" />
				<p>
				<table style="width:1000px; border="0" cellspacing="10" cellpadding="0">
				<tr>
				  <td width="60" nowrap="nowrap">名前<font color="red">*</font></td>
			    <td width="5" nowrap="nowrap">：</td>
					<td width="300" nowrap="nowrap">
	    		    <input name="name" type="text" id="name" style="width:200px;padding:3px;" value="<?=$stuff_row['name']?>" />
						<input  type="hidden" id="name_current" style="width:200px;padding:3px;" value="<?=$stuff_row['name']?>" />
					</td>
				</tr>
				<tr>
				  <td align="left" nowrap="nowrap">ログインID<font color="red">*</font></td>
				  <td align="left" nowrap="nowrap">：</td>
					<td align="left" nowrap="nowrap">
					  <input name="username" type="text" id="ID" style="width:200px;padding:3px;" value="<?=$stuff_row['username']?>"/>
						<input type="hidden" id="username_current" style="width:200px;padding:3px;" value="<?=$stuff_row['username']?>" />    　
	            	</td>
				</tr>
				<tr>
				  <td align="left" valign="middle" nowrap="nowrap">パスワード<font color="red">*</font></td>
			    <td align="left" valign="middle" nowrap="nowrap">：</td>
					<td align="left" valign="middle" nowrap="nowrap">
				    <span id="password_msg" style="color:#FF0000;font-size:8px;">&nbsp;</span><br><input name="password" type="text" id="password" style="width:200px;padding:3px;" value="<?=$stuff_row['password']?>"  onblur="checkvalidity()"/><br>
						<span id="password_msg" style="color:#FF0000;font-size:8px;">半角英数字記号6文字以上15文字以下にしてください。<br>記号は以下の文字が利用できます(! # $ % & ( ) * + - . / : ; < = > ? @ [ ] ^ _ ` { | } ~)</span>
					</td>
				</tr>
				<tr>
				  <td align="left" nowrap="nowrap">メールを受信する</td>
				  <td align="left" nowrap="nowrap">：</td>
					<td nowrap="nowrap">
					<input type="radio" name="subcription_mail" value="0" <?php echo ($stuff_row['subcription_mail']=='0' && $stuff_row['email'] !='')?"checked":"";?> /> 受信する
					<input type="radio" name="subcription_mail" value="1" <?php echo($stuff_row['subcription_mail']=='1')?"checked":"";?>/>  受信しない
					</td>
				</tr>
				<tr>
				  <td align="left" nowrap="nowrap">メールアドレス</td>
				  <td align="left" nowrap="nowrap">：</td>
					<td align="left" nowrap="nowrap">
					  <input name="email" type="text" id="email" style="width:200px;padding:3px;" value="<?=$stuff_row['email']?>" />
						<input  type="hidden" id="email_current" style="width:200px;padding:3px;" value="<?=$stuff_row['email']?>" />
					</td>
				</tr>
				<tr>
				  <td align="left" nowrap="nowrap">メールアドレス確認用</td>
				  <td align="left" nowrap="nowrap">：</td>
					<!--  UCHIDA EDIT 11/08/08 確認用メールアドレスのペーストを禁止 -->
				  <td nowrap="nowrap" onpaste="alert('メールアドレス確認用は貼り付けできません');return false;">
						<input name="conf_email" type="text" id="conf_email" style="width:200px;padding:3px;" value="<?=$stuff_row['email']?>" />
					</td>
				</tr>
			</table>
                <br />
				   <div style="width:800px; padding-left:156px; text-align:left;"><a href="#" onclick="validForm_staff();"><img src="img/common/btn_update.jpg" alt="更新" width="82" height="22" /></a></div>
        		</p>
				</form>

				</div>
            <?php
			}
		?>
    </div>
</div>
		<? if($get['id'] !=''){ ?>
			<script>
			$("#boxid<?=$get['id']?>").css({backgroundColor: "#FFF0FF", color: "#990000"});
			</script>
		<? }?>

<?php
	include_once("inc/left_nav.inc.php");
?>


<?php
	include_once("inc/new.footer.inc.php");
?>

<?php
if($get['msg'] ==130)
{
	echo'<script>alert("新しいスタッフが登録されました");</script>';
}else{
  echo $obj->GetErrorMsgAlert($get["err"]);
}
echo $obj->GetSuccessMsgAlert($get["msg"]);
?>

<script>
function sortAction(url) {

	window.location = collecting_data(url);
}

function collecting_data(url) {

var radio1  = document.stuff_form.permission[0].checked;
var radio2  = document.stuff_form.subcription_mail[0].checked;
var edit_data;
var urlPlus;
var perm = (radio1==true)? "333" : "222";;
var sub = (radio2==true)? "0" : "1";
//var action = urls.search("action=edit");
	edit_data  = "&name="			  + document.stuff_form.name.value;
	edit_data += "&username="		  + document.stuff_form.username.value;
	edit_data += "&password="		  + document.stuff_form.password.value;
	edit_data += "&permission="		  + perm;
	edit_data += "&subcription_mail=" + sub;
	edit_data += "&email="			  + document.stuff_form.email.value;
	edit_data += "&conf_email="		  + document.stuff_form.conf_email.value;

//	if(action=="edit") 	edit_data += "&action=edit";

	urlPlus = url+edit_data;
	return urlPlus;
}
</script>