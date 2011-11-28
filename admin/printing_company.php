<?php
	require_once("inc/class.dbo.php");
	include_once("inc/checklogin.inc.php");
	include_once("inc/new.header.inc.php");
	$obj = new DBO();

	$get = $obj->protectXSS($_GET);
	$post = $obj->protectXSS($_POST);

	if(isset($get['action']) && $get['action']!="" && (int)$get['id'] > 0) {
		if($get['action']=='sort')
		{
			$table = 'spssp_printing_comapny';

			$id = $get['id'];
			$move = $get['move'];
			if($move=="down") $move="up"; else $move="down";
			$obj->sortItem($table,$id,$move);
			$get['id']=$get['edit_id'];
		}

		if($get['action'] == 'delete')
		{
			$id = (int)$get['id'];
			$obj->DeleteRow("spssp_printing_comapny", "id=".$id);
			$get['id']=$get['edit_id'];
		}
	}
	else {
		if(isset($post['company_name']) && $post['company_name'] != '' && isset($post['email']) && $post['email'] != '')
		{
			$id = (int)$post['insert_edit'];
			unset($post['insert_edit']);
			unset($post['conf_email']);
			if($id <= 0)
			{

				if(checkcompanyDuplicasy($post['company_name'])) {
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
				else {
					echo '<script> alert("既に同じ名前の印刷会社があるため、登録できませんでした"); </script>';
				}
			}
			else
			{
				$obj->UpdateData("spssp_printing_comapny", $post," id=".$id);
				echo '<script> alert("印刷会社が更新されました"); </script>';
				//$msg = 2;
			}

		}
	}

function checkcompanyDuplicasy( $cname )
{
	$obj = new DBO();
	$nm = $obj->GetRowCount("spssp_printing_comapny", "company_name='$cname'");

	if($nm){return false;}else{return true;}
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

//    $('#number').keyup(function(){
//		var r=isInteger("number");
//   });

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
</script>

<script>
function new_table()
{
	$("#insert_edit").val("0");
	$("#name").val("");
	$("#new_table").toggle("slow");
}
function cancel_new()
{

window.location="printing_company.php";

	//$("#insert_edit").val('');
		//s  $("#company_name").val('');
		//s  $("#email").val('');
		//s  $("#conf_email").val('');
		//s  $("#number").val('');
		//s  $("#postcode").val('');
		//s  $("#address_1").val('');
		//s  $("#address_2").val('');
		//s  $("#contact_name").val('');


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
	if(number == '')
	{
		alert("電話番号を入力してください");
		$("#number").focus();
		return false;
	}
	else {
		if(isNaN(number)==true) {
			alert("電話番号は半角数字で入力してください");
			$("#number").focus();
			return false;
		}
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
/*
		$("#insert_edit").val(id);
		$("#company_name").val(name);
		$("#email").val(email);
		$("#conf_email").val(email);
		$("#number").val(number);
		$("#postcode").val(postcode);
		$("#address_1").val(address_1);
		$("#address_2").val(address_2);
		$("#contact_name").val(contact_name);
		$("#new_table").fadeOut(100);
		$("#new_table").fadeIn(100);

		$("#boxid"+id).css({backgroundColor: "#FFF0FF", color: "#990000"});
*/
}
</script>

<script>


function sortAction(url) {
	window.location = collecting_data(url);
}

function confirmDeletePlus(url, id)
{
	var edit_id="<?=$get['id']?>";
	var agree = confirm("印刷会社を削除してもよろしいですか？");
	if(agree)
	{
		if (edit_id != id) window.location = collecting_data(url);
		else               window.location = url;
	}
}

function collecting_data(url) {
var edit_data;
var urlPlus;
var edit_id="<?=$get['id']?>";

	edit_data  = "&company_name="	+document.new_name.company_name.value;
	edit_data += "&email="			+document.new_name.email.value;
	edit_data += "&conf_email="		+document.new_name.conf_email.value;
	edit_data += "&number="			+document.new_name.number.value;
	edit_data += "&postcode="		+document.new_name.postcode.value;
	edit_data += "&address_1="		+document.new_name.address_1.value;
	edit_data += "&address_2="		+document.new_name.address_2.value;
	edit_data += "&contact_name="	+document.new_name.contact_name.value;

	edit_data += "&edit_id="+edit_id;
	urlPlus = url+edit_data;
	return urlPlus;
}

</script>

<div id="topnavi">
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
		<h4><div style="width:200px;">印刷会社</div></h4>
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
        	<h2 id="title_bar">印刷会社設定 </h2>
		<? }
	 	else { ?>
        	<h2 id="title_bar">印刷会社情報 </h2>
		<? } ?>

     	<!--<div style="width:1200px;">
        <p class="txt3">
        	<a href="javascript:void(0);" onclick="new_table()"><h2 id="title_bar">新規登録 </h2></a>
        </p></div>-->

		<div  id="new_table" style="display:block; width:1000px;">
        	<? if($get['id']) {
				$comp= $obj->GetSingleRow('spssp_printing_comapny','id='.$get['id']);
				$comp[conf_email] = $comp[email];
			}
			if($_SESSION['user_type'] == '222') { // スタッフログイン
			?>
			 <table style="width:1000px;" >
					<tr>
					  <td width="15%">会社名</td>
						<td width="1%"> ：</td>
						<td width="85%"><?=$comp[company_name]?></td>
					</tr>
					<tr>
					  <td>メールアドレス</td>
						<td> ：</td>
						<td><?=$comp[email]?></td>
					</tr>
<!-- UCHIDA EDIT 11/08/08 確認用は表示不要
					<tr>
						<td width="10%">メールアドレス確認用 :</td>
						<td width="90%"><?=$comp[email]?></td>
					</tr>
 -->
 					<tr>
 					  <td>電話番号</td>
						<td> ：</td>
						<td><?=$comp[number]?></td>
					</tr>
					<tr>
					  <td>郵便番号</td>
						<td> ：</td>
						<td><?=$comp[postcode]?></td>
					</tr>
					<tr>
					  <td>住所1</td>
						<td> ：</td>
						<td><?=$comp[address_1]?></td>
					</tr>
					<tr>
					  <td>住所2</td>
						<td> ：</td>
						<td><?=$comp[address_2]?></td>
					</tr>
					<tr>
					  <td >担当者名</td>
						<td > ：</td>
						<td ><?=$comp[contact_name]?></td>
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
            	<input type="hidden" name="insert_edit" id="insert_edit" value="<?=$get[id]?>" />
                <table style="width:1000px;" >
					<tr>
					  <td width="15%">会社名<span style="color:red;">*</span></td>
						<td width="1%">：</td>
						<td width="86%"><input type="text" name="company_name" id="company_name" size="40" style="border-style: inset;" value="<?=$comp[company_name]?>" /></td>
					</tr>
					<tr>
					  <td>メールアドレス<span style="color:red;">*</span></td>
						<td>：</td>
						<td><input type="text" name="email" id="email"  size="30" style="border-style: inset;" value="<?=$comp[email]?>" /></td>
					</tr>
					<tr>
					  <td>メールアドレス確認用<span style="color:red;">*</span></td>
						<td>：</td>
						<!--  UCHIDA EDIT 11/08/08 確認用メールアドレスのペーストを禁止 -->
						<td onpaste="alert('メールアドレス確認用は貼り付けできません');return false;"><input type="text" name="conf_email" id="conf_email" size="30" style="border-style: inset;" value="<?=$comp[conf_email]?>"  /></td>
					</tr>
					<tr>
					  <td>電話番号<span style="color:red;">*</span></td>
						<td>：</td>
						<td><input type="text" name="number" id="number" style="border-style: inset;" value="<?=$comp[number]?>"  size="20"/>（例　0451111111)</td>
					</tr>
					<tr>
					  <td>郵便番号</td>
						<td> ：</td>
						<td><input name="postcode" type="text" id="postcode" style="border-style: inset;" value="<?=$comp[postcode]?>" size="10" maxlength="8" />（例　231-0000）</td>
					</tr>
					<tr>
					  <td>住所1</td>
						<td> ：</td>
						<td><input type="text" name="address_1" id="address_1" size="50" style="border-style: inset;" value="<?=$comp[address_1]?>"  /></td>
					</tr>
					<tr>
					  <td>住所2</td>
						<td> ：</td>
						<td><input type="text" name="address_2" id="address_2"  size="50" style="border-style: inset;" value="<?=$comp[address_2]?>" /></td>
					</tr>
					<tr>
					  <td>担当者名</td>
						<td> ：</td>
						<td><input name="contact_name" type="text" id="contact_name" style="border-style: inset;" value="<?=$comp[contact_name]?>" size="10"  /></td>
					</tr>
					<tr>
					  <td>&nbsp;</td>
				    <td>&nbsp;</td></tr>
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
						<td width="10%"></td>
						<td width="90%">
						<a href="#"><img width="82" height="22" border="0" src="img/common/btn_regist_update.jpg" alt="登録・更新" onclick="check_name();" >&nbsp; &nbsp;
						<a href="#"><img width="82" height="22" border="0" src="img/common/btn_clear.jpg" alt="クリア" onclick="cancel_new();" value="クリア">


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
        <div class="box_table" style="width:800px;">
            <div class="box4">
                <table border="0" align="center" cellpadding="1" cellspacing="1">
                <tr align="center">
                  <td width="10%">No.</td>
                  <td>印刷会社名</td>
                  <td>順序変更</td>
                  <td>詳細</td>
                  <td>削除 &nbsp;</td>
                </tr>
              </table>
            </div>
            <?php
			    $orderItem = ($get['sort'] =='')?'ASC': $get['sort'];
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
        		<form action="printing_company.php" method="post" name="name_list<?=$row['id']?>" id="name_list<?=$row['id']?>">
            	 <div class="<?=$class?>" id="boxid<?=$row['id']?>">
            		<table border="0" align="center" cellpadding="1" cellspacing="1">
            			<tr align="center">
                        	<td width="10%"><?=$j?></td>
                            <td><?=$row['company_name']?></td>
							<?php if($_SESSION['user_type']=='333'){?>
							<td>
								 <span class="txt1">
							  <a href="javascript:void(0);" onClick="sortAction('printing_company.php?action=sort&amp;move=up&amp;id=<?=$row['id']?>');">▲</a>
                              <a href="javascript:void(0);" onClick="sortAction('printing_company.php?action=sort&amp;move=down&amp;id=<?=$row['id']?>');">▼</a>
	                             </span>
              				</td>
							<?php } else {?>
									<td><font color="gray">▲▼</font></td>
								<? } ?>

                            <td>
                            	<a href="javascript:void(0);"  onclick="edit_name(<?=$row['id']?>,'<?=$row['company_name']?>','<?=$row['email']?>','<?=$row['number']?>','<?=$row['postcode']?>','<?=$row['address_1']?>','<?=$row['address_2']?>','<?=$row['contact_name']?>','<?=$row['status']?>',<?=$_SESSION['user_type']?>);">
							<?php
	  							if($_SESSION['user_type'] == 333) { ?>
                                	<!-- <img src="img/common/btn_regist_update.gif" /> -->
                                	<img src="img/common/btn_edit02.gif" />
								<? } else { ?>
                                	<img src="img/common/btn_disp.gif" />
								<? } ?>
                                </a>

                            </td>

							<?php if($_SESSION['user_type']=='333'){?>
							<td>
                            	<a href="javascript:void(0);" onClick="confirmDeletePlus('printing_company.php?action=delete&id=<?=$row['id']?>', <?=$row['id']?>);">
                                	<img src="img/common/btn_deleate.gif" width="42" height="17" />
                                </a>
                            </td>
								<? } else { ?>
									<td>
                                	<img src="img/common/btn_deleate_greyed.gif" width="42" height="17" />

								<?php }?>
            			</tr>
                     </table>
        		</div>
        		</form>
             <?php
			 	$i++;
				$j++;
             	}
			 ?>

			</div>
			<div id="selectMark">
			<? if($get['id'] !=''){ ?>
				<script>
				$("#boxid<?=$get['id']?>").css({backgroundColor: "#FFF0FF", color: "#990000"});
				</script>
			<? } ?>
			</div>
        </div>
 </div>
<?php
	include_once("inc/left_nav.inc.php");
	include_once("inc/new.footer.inc.php");
?>
<?php if(isset($err)){echo "<script>
			alert('".$obj->GetErrorMsgNew($err)."');
			</script>";}
?>
		<?php if(isset($msg)){echo "<script>
			alert('".$obj->GetSuccessMsgNew($msg)."');
			</script>";}
?>
