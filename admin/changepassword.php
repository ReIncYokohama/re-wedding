<?php
require_once("inc/include_class_files.php");
include_once("inc/checklogin.inc.php");
$obj = new DBO();
$get = $obj->protectXSS($_GET);
$user_id = (int)$_SESSION['userid'];
include_once("inc/new.header.inc.php");

if(trim($_POST['password'])!=="" && trim($_POST['userID'])!='')
	{
		$old_pw = $_POST['userID'];
		$nm = $obj->GetRowCount("spssp_user", "password='$old_pw' and id=".$user_id);
		if ($nm) {
			$query_string="UPDATE spssp_user SET password='".$_POST['password']."' WHERE password='".$_POST['userID']."';";
			mysql_query($query_string);
			if(mysql_affected_rows())
				{
					echo "<script> alert('パスワードの変更が正常に完了しました'); </script>";
					redirect("user_info.php");
				}
				else
				{
				   redirect("changepassword.php?mass=2");
				}
			}
			else {
				   redirect("changepassword.php?mass=3");
			}
		}

?>
<script type="text/javascript">
function view_adminitem(id,no){

		$.post("ajax/admin_message_edit.php",{'id':id},function(data){
			//alert(data);
			var substrs = data.split('#');
			$("#v_no").html("No."+no);
			$("#v_title").html(substrs[2]);
			$("#v_desc").html(substrs[3]);

		});


}
$(function(){
		$("ul#menu li").removeClass();

	});

 var title=$("title");
 $(title).html("パスワード変更 - ウエディングプラス");

</script>
<script type="text/javascript" language="javascript">
	function validForm()
	{
// UCHIDA EDIT 11/08/01
	var oldPw  = document.getElementById('userID').value;
	var newPw  = document.getElementById('password').value;
	var rePw  = document.getElementById('repassword').value;
	var reg = /^[A-Za-z0-9\!\#\$\%\&\(\)\*\+\-\.\d\/\:\;\<\=\>\?\@\[\]\^\_\`\{\|\}\~]{1,15}$/; //2011/12/09 yamanaka

	if(oldPw=='')
	{
	   alert("現在のパスワードを入力してください");
	   document.getElementById('userID').focus();
	   return false;
	}
	if(newPw=='')
	{
	   alert("新しいパスワードを入力してください");
	   document.getElementById('repassword').focus();
	   return false;
	}
	if(rePw=='')
	{
	   alert("確認用パスワードを入力してください");
	   document.getElementById('repassword').focus();
	   return false;
	}
	if (newPw.length<6 || newPw.length>15) {
	   alert("新しいパスワードは英数字6文字以上15文字以内で入力してください");
	   document.getElementById('repassword').focus();
	   return false;
	}
	if (rePw.length<6 || rePw.length>15) {
	   alert("確認用パスワードは英数字6文字以上15文字以内で入力してください");
	   document.getElementById('repassword').focus();
	   return false;
	}
	if(reg.test(newPw) == false) {
	　　alert("新しいパスワードは英数字記号で入力してください");
	　　document.getElementById('repassword').focus();
	　　return false;
	 }
	if(reg.test(rePw) == false) {
	　　alert("確認用パスワードは英数字記号で入力してください");
	　　document.getElementById('repassword').focus();
	　　return false;
	 }
	if(newPw != rePw)
	{
	   alert("新しいパスワードが一致しません。新しいパスワードを再入力してください。");
	   document.getElementById('password').value="";
	   document.getElementById('repassword').value="";
	   document.getElementById('password').focus();
	   return false;
	}
	if(newPw == oldPw)
	{
	   alert("現在のパスワードと新しいパスワードが同じです。\n新しいパスワードを再入力してください。");
	   document.getElementById('password').value="";
	   document.getElementById('repassword').value="";
	   document.getElementById('password').focus();
	   return false;
	}
	document.login_form.submit();

/*
	if(document.getElementById('userID').value=='')
		{
		   alert("古いパスワードを入力してください");
		   document.getElementById('userID').focus();
		   return false;
		}

		if(document.getElementById('password').value=='')
		{
		   alert("新しいパスワードを入力してください");
		   document.getElementById('password').focus();
		   return false;
		}

		if(document.getElementById('repassword').value != document.getElementById('password').value)
		{
		   alert("新しいパスワードが一致しません。新しいパスワードを再入力してください。");
		   document.getElementById('repassword').focus();
		   return false;
		}
		document.login_form.submit();
	*/
	}
</script>
<div id="main_contents">
  <div class="title_bar">
    <div class="title_bar_txt_L">パスワード変更</div>
    <div class="title_bar_txt_R"></div>
<div class="clear"></div></div>
  <div class="cont_area">

   <form action="changepassword.php" method="post" name="login_form">
		<table style="font-size:10px;" align="center" cellspacing="10" cellpadding="0" width="100%">

			<tr>
				<td width="300" align="right"></td>
				<td><font color="red">半角英数字記号6文字以上で入力してください。以下の文字が利用できます。<br>
! # $ % & ( ) * + - . / : ; < = > ? @ [ ] ^ _ ` { | } ~</font></td>
			</tr>


			<?
			 if($_GET['mass'] >=1) {
				if($_GET['mass'] == 1) {
					echo "<script> alert('パスワードの変更が正常に完了しました'); </script>";
				}
				else if ($_GET['mass'] == 2) {
					echo "<script> alert('パスワード変更に失敗しました'); </script>";
				}
				else {
					echo "<script> alert('現在のパスワードが違っています'); </script>";
				}
			}
			?>
<!-- UCHIDA EDIT 11/08/01 -->

			<tr>
				<td width="300" align="right">現在のパスワード</td>
				<td align="left"><input style="width:250px;border-style: inset;" type="password" id="userID" name="userID" /></td>
			</tr>
			<tr>
				<td align="right">新しいパスワード</td>
				<td align="left"><input type="password" style="width:250px;border-style: inset;" id="password" name="password" /></td>
			</tr>
			<tr>
				<td align="right">確認用パスワード</td>
				<td align="left"><input type="password" style="width:250px;border-style: inset;" id="repassword" name="repassword" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><a href="#" onclick="validForm();"><img src="img/btn_save_user.jpg" alt="更新" /></a>
				&nbsp;
				<a href="#" onclick="window.location='user_info.php'"><img src="img/btn_cancel_user.jpg" alt="キャンセル" /></a></td>
			</tr>
		</table>
	 </form>
    <script>document.getElementById('userID').focus();</script>
    </div>
    <div class="clear"></div>
  <div class="clear"></div>
  </div>

<?php
include("inc/new.footer.inc.php");
?>
