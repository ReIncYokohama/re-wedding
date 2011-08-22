<?php
	require_once("inc/class.dbo.php");
	include_once("inc/checklogin.inc.php");
	include_once("inc/new.header.inc.php");
	$obj = new DBO();

	$get = $obj->protectXSS($_GET);
	$post = $obj->protectXSS($_POST);

	/*if($_SESSION['user_type'] != 111)
	{
		redirect("manage.php");
	}
*/

	if(isset($post['company_name']) && $post['company_name'] != '' && isset($post['email']) && $post['email'] != '')
	{
		$id = (int)$post['insert_edit'];
		unset($post['insert_edit']);
		unset($post['conf_email']);

		if($id <= 0)
		{

			$post['display_order'] = time();
			$post['date'] = date();
			$lid = $obj->InsertData("spssp_printing_comapny", $post);
			if($lid > 0)
			{
//				$msg = 1; // UCHIDA EDIT 11/08/01
				echo "<script> alert('新しい印刷会社が登録されました'); </script>";
			}
			else
			{
				$err = 1;
			}
		}
		else
		{
			$obj->UpdateData("spssp_printing_comapny", $post," id=".$id);
			$msg = 2;
		}

	}
	if(isset($get['action']) && $get['action'] == 'delete')
	{
		$id = (int)$get['id'];
		if($id > 0)
		{
			$obj->DeleteRow("spssp_printing_comapny", "id=".$id);
//			$msg = 3;
//			redirect('printing_company.php'); // UCHIDA EDIT 11/08/01
		}
		else
		{
			$err = 11;
		}
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

    $('#number').keyup(function(){
		var r=isInteger("number");
    });

});
function isInteger(id){

 var i;
 var s=$("#"+id).val();

	var set =/^([0-9０-９])$/;
	for (i = 0; i < s.length; i++){
        // Check that current character is number.
        var c = s.charAt(i);

	    if (!set.test(c))
		{
			var msg=" 半角数字をご入力下さい。";
			$('#'+id).attr("value","");
			 alert(msg);
			 break;
		}
    }
    // All characters are numbers.
    return true;
}
function new_table()
{
	$("#insert_edit").val("0");
	$("#name").val("");
	$("#new_table").toggle("slow");
}
function cancel_new()
{
	$("#insert_edit").val('');
		$("#company_name").val('');
		$("#email").val('');
		$("#conf_email").val('');
		$("#number").val('');
		$("#postcode").val('');
		$("#address_1").val('');
		$("#address_2").val('');
		$("#contact_name").val('');
		//$("#new_table").fadeOut(300);
}
function check_name()
{
	var company_name  = document.getElementById('company_name').value;
	var email  = document.getElementById('email').value;
	var conf_email  = document.getElementById('conf_email').value;
	var number  = document.getElementById('number').value;
	var postcode = document.getElementById('postcode').value;

	if(company_name == '' && email=='')
	{
		alert("会社名、メールアドレスは必須です");
		$("#company_name").focus();
		return false;
	}
	if(company_name == '' && email!='')
	{
		alert("会社名は必須です");
		$("#company_name").focus();
		return false;
	}
	if(email=='')
	 {
		alert("メールアドレスを入力してください");
		document.getElementById('email').focus();
		return false;
	  }
	else
	  {
		if(email_validate(email)==false)
		{
			alert("正しいメールアドレスを入力してください"); // UCHIDA EDIT 11/08/01
			document.getElementById('email').focus();
			return false;
		}
	  }
	if(conf_email=='')
	  {
		alert("確認用のメールアドレスを入力してください");
		document.getElementById('conf_email').focus();
		return false;
	 }
	if(email != conf_email)
	{
		alert("メールアドレスが確認用と異なっています。\n同じメールアドレスを入力してください"); // UCHIDA EDIT 11/08/01
		document.getElementById('email').focus();
		return false;
	}
	if (postcode.length != 0) {
		if( postcode.match( /[^0-9\s-]+/ ) ) {
				alert("郵便番号は半角数字と'-'だけで入力してください");
				document.getElementById('postcode').focus();
				return false;
		}
		if (postcode.length !=8) {
			alert("郵便番号は'123-4567'の形式で入力してください");
			document.getElementById('postcode').focus();
			return false;
		}
	}

//	matchMail();

	document.new_name.submit();
}
function matchMail()
{
	var mail = $("#email").val();
	var conemail = $("#conf_email").val();
	if(mail != conemail)
	{
		alert("PCメールアドレスが一致しません。再度入力してください。");
		document.getElementById('conf_email').focus();
		exit;
	}
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
function edit_name(id, name,email,number,postcode,address_1,address_2,contact_name,status,adminType)
{
	//if(adminType==222)
	//{
	//	alert("権限がありません");
	//}
	//else
	//{

	 window.location='printing_company.php?id='+id;
		//<!--$("#insert_edit").val(id);
//		$("#company_name").val(name);
//		$("#email").val(email);
//		$("#conf_email").val(email);
//		$("#number").val(number);
//		$("#postcode").val(postcode);
//		$("#address_1").val(address_1);
//		$("#address_2").val(address_2);
//		$("#contact_name").val(contact_name);
//		//if(status=="1")
//			//$('input:radio[name=status]')[0].checked = true;
//		//if(status=="0")
//			//$('input:radio[name=status]')[1].checked = true;
//
//		$("#new_table").fadeOut(100);
//		$("#new_table").fadeIn(500);-->
	//}
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
        <a href="javascript:;" onclick="MM_openBrWindow('../support/operation_h.html','','scrollbars=yes,width=620,height=600')"><img src="img/common/btn_help.jpg" alt="ヘルプ" width="82" height="19" /></a>
    </div>
</div>

<div id="container">

	<div style="clear:both;"></div>
	<div id="contents">
		<h2><div style="width:200px;">印刷会社</div></h2>
		<!--<p class="txt3">
            <a href="default.php"><b>テーブル名</b></a>&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;
            <a href="respects.php"><b>敬称</b></a>&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;
            <a href="guest_types.php"> <b>区分</b></a>&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;
            <b>挙式種類</b>
      </p>-->

      <?php
	  	//if($_SESSION['user_type'] != 222)
		//{
	  ?>


<!-- UCHIDA EDIT 11/08/02 ログイン状態によるタイトルの変更 -->
<?php
	  	if($_SESSION['user_type'] != 222) { ?>
        	<h2 id="title_bar">印刷会社登録・編集 </h2>
		<? }
	 	else { ?>
        	<h2 id="title_bar">印刷会社詳細 </h2>
		<? } ?>

     	<!--<div style="width:1200px;">
        <p class="txt3">
        	<a href="javascript:void(0);" onclick="new_table()"><h2 id="title_bar">新規登録 </h2></a>
        </p></div>-->

		<div  id="new_table" style="display:block; width:1200px;">
        	<? if($_GET['id']) {
			$comp= $obj->GetSingleRow('spssp_printing_comapny','id='.$_GET['id']);
			}

// UCHIDA EDIT 11/08/02
//			if($_SESSION['user_type'] == '222' && $_GET['id'] !='') {
			if($_SESSION['user_type'] == '222') { // スタッフログイン
			?>
			 <table>
					<tr>
						<td width="12%">会社名 :</td>
						<td width="90%"><?=$comp[company_name]?></td>
					</tr>
					<tr>
						<td width="10%">メールアドレス :</td>
						<td width="90%"><?=$comp[email]?></td>
					</tr>
					<tr>
						<td width="10%">メールアドレス確認用 :</td>
						<td width="90%"><?=$comp[email]?></td>
					</tr>
					<tr>
						<td width="10%">電話番号 :</td>
						<td width="90%"><?=$comp[number]?></td>
					</tr>
					<tr>
						<td width="10%">郵便番号 :</td>
						<td width="90%"><?=$comp[postcode]?></td>
					</tr>
					<tr>
						<td width="10%">住所1 :</td>
						<td width="90%"><?=$comp[address_1]?></td>
					</tr>
					<tr>
						<td width="10%">住所2 :</td>
						<td width="90%"><?=$comp[address_2]?></td>
					</tr>
					<tr>
						<td width="10%">担当者名 :</td>
						<td width="90%"><?=$comp[contact_name]?></td>
					</tr>
				<!--	<tr>
						<td width="10%">表示する :</td>
						<td width="90%">
							<input type="radio" name="status" value="1"  /> はい
							<input type="radio" name="status" value="0"  /> ない

						</td>
					</tr>	-->

				</table>
			<? }else if($_SESSION['user_type'] != '222' ) {?>
        	<form action="printing_company.php" method="post" name="new_name">
            	<input type="hidden" name="insert_edit" id="insert_edit" value="<?=$_GET['id']?>" />
                <table>
					<tr>
						<td width="12%">会社名<span style="color:red;">*</span> :</td>
						<td width="90%"><input type="text" name="company_name" id="company_name" size="40" value="<?=$comp[company_name]?>" /></td>
					</tr>
					<tr>
						<td width="10%">メールアドレス<span style="color:red;">*</span> :</td>
						<td width="90%"><input type="text" name="email" id="email"  size="30" value="<?=$comp[email]?>" /></td>
					</tr>
					<tr>
						<td width="10%">メールアドレス確認用<span style="color:red;">*</span> :</td>
						<td width="90%"><input type="text" name="conf_email" id="conf_email" size="30" value="<?=$comp[email]?>"  /></td>
					</tr>
					<tr>
						<td width="10%">電話番号 :

                      </td>
						<td width="90%"><input type="text" name="number" id="number" onblur="isInteger('number')" value="<?=$comp[number]?>"  size="20"/>（例　0451111111)</td>
					</tr>
					<tr>
						<td width="10%">郵便番号 :</td>
						<td width="90%"><input name="postcode" type="text" id="postcode" value="<?=$comp[postcode]?>"  size="8" maxlength="8" />（例　231-0000）</td>
					</tr>
					<tr>
						<td width="10%">住所1 :</td>
						<td width="90%"><input type="text" name="address_1" id="address_1" size="35" value="<?=$comp[address_1]?>"  /></td>
					</tr>
					<tr>
						<td width="10%">住所2 :</td>
						<td width="90%"><input type="text" name="address_2" id="address_2"  size="35" value="<?=$comp[address_2]?>" /></td>
					</tr>
					<tr>
						<td width="10%">担当者名 :</td>
						<td width="90%"><input name="contact_name" type="text" id="contact_name" value="<?=$comp[contact_name]?>" size="10"  /></td>
					</tr>
				<!--	<tr>
						<td width="10%">表示する :</td>
						<td width="90%">
							<input type="radio" name="status" value="1"  /> はい
							<input type="radio" name="status" value="0"  /> ない

						</td>
					</tr>	-->
				<?php if($_SESSION['user_type']=='333' OR $_SESSION['user_type']=='111'){?>
                    <tr>
						<td width="10%"></td>
						<td width="90%">
						<img width="62" height="22" alt="登録・更新" src="img/common/btn_regist1.jpg" onclick="check_name();" >&nbsp; &nbsp;
						<img width="62" height="22" border="0" src="img/common/btn_clear.jpg" alt="ｸﾘｱ" onclick="cancel_new();" value="ｸﾘｱ">


						</td>
				    </tr>
					<? }?>
				</table>


            </form><br />
<? } ?>
        </div>

	<?php
	//}
	?>


        <p>&nbsp;</p>
        <div id="message_BOX" style="auto; overflow:auto; width:800px;">
            <div class="box4">
                <table border="0" align="center" cellpadding="1" cellspacing="1">
                <tr align="center">
                  <td width="10%">No.</td>
                  <td>印刷会社名</td>

<!-- UCHIDA EDIT 11/08/02 詳細・編集/詳細情報の切り替え -->
				   <?php if($_SESSION['user_type']=='333' OR $_SESSION['user_type']=='111' ){?>
	                  	<td>詳細・編集</td>
				   		<td>順序変更</td>
                  		<td>削除 &nbsp;</td>
				  <? }
				  	else {?>
	                  	<td>詳細情報</td>
				  <?} ?>
                </tr>
              </table>
            </div>
            <?php
			    $orderItem = ($_GET['sort'] =='')?'ASC': $_GET['sort'];
            	$query_string="SELECT * FROM spssp_printing_comapny  ORDER BY display_order $orderItem ;";
				$data_rows = $obj->getRowsByQuery($query_string);

				$i=0;
				$j=1;

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
            	 <div class="<?=$class?>" id="boxid<?=$row['id']?>">
            		<table border="0" align="center" cellpadding="1" cellspacing="1">
            			<tr align="center">
                        	<td width="10%"><?=$j?></td>
                            <td><b><?=$row['company_name']?></b></td>
                            <!--<td><a href="party_rooms.php?religion_id=< ?=$row['id']?>">挙式会場</a></td>-->
                            <td>
                            	<a href="#" onclick="edit_name(<?=$row['id']?>,'<?=$row['company_name']?>','<?=$row['email']?>','<?=$row['number']?>','<?=$row['postcode']?>','<?=$row['address_1']?>','<?=$row['address_2']?>','<?=$row['contact_name']?>','<?=$row['status']?>',<?=$_SESSION['user_type']?>);">

<!-- UCHIDA EDIT 11/08/02 ログイン状態によるボタンの変更 -->
						<?php
	  							if($_SESSION['user_type'] != 222) { ?>
                                	<!-- <img src="img/common/btn_regist_update.gif" /> -->
                                	<img src="img/common/btn_edit02.gif" />
								<? }
								else { ?>
                                	<img src="img/common/btn_disp.gif" />
								<? } ?>
                                </a>

                            </td>
							<?php if($_SESSION['user_type']=='333' OR $_SESSION['user_type']=='111' ){?>
							<td>
							 <span class="txt1"><a href="sort.php?table=printing_comapny&sort=ASC&move=up&id=<?=$row['id']?>&pagename=printing_company">▲</a>
                             <a href="sort.php?table=printing_comapny&sort=ASC&move=down&id=<?=$row['id']?>&pagename=printing_company"> ▼</a></span>
              				</td>
							<td>

                            	<a href="javascript:void(0);" onClick="confirmDelete('printing_company.php?action=delete&id=<?=$row['id']?>');">
                                	<img src="img/common/btn_deleate.gif" width="42" height="17" />
                                </a>

                            </td>
							<?php }?>
            			</tr>
                     </table>
        		</div>
             <?php
			 	$i++;
				$j++;
             	}
			 ?>

			</div>
			<? if($_GET['id'] !=''){


				?>
				<script>
				$("#boxid<?=$_GET['id']?>").css({backgroundColor: "#FFF0FF", color: "#990000"});
				</script>
				<? }?>
        </div>
 </div>
<?php
	include_once("inc/left_nav.inc.php");
	include_once("inc/new.footer.inc.php");
?>
<?php if(isset($err)){echo "<script>
			alert('".$obj->GetErrorMsgNew($err)."');
			</script>";}?>
		<?php if(isset($msg)){echo "<script>
			alert('".$obj->GetSuccessMsgNew($msg)."');
			</script>";}?>
