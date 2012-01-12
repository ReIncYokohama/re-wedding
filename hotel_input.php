<?php
require_once("inc/class.dbo.php");
include_once("inc/checklogin.inc.php");

$obj = new DBO();
$post = $obj->protectXSS($_POST);
include_once("inc/header.inc.php");

include_once("inc/class_hotel.dbo.php");
$hotel_dbo = new HotelDBO();
//hotel_codeを自動生成するため次のhotelcodeを生成
$hotel_code = $hotel_dbo->getNextHotelCode();

$hotel_sqlhost_val = "hotel".((int)$hotel_code)."_sqlhost";
$hotel_sqluser_val = "hotel".((int)$hotel_code)."_sqluser";
$hotel_sqlpassword_val = "hotel".((int)$hotel_code)."_sqlpassword";
$hotel_sqldatabase_val = "hotel".((int)$hotel_code)."_sqldatabase";

mysql_close();
if(!$$hotel_sqlhost_val){
  echo "ただいま登録ホテル数が上限のため、新規登録はできません<button onClick='history.back()'>戻る</button>";
  exit();
}
$link = mysql_connected($$hotel_sqlhost_val,$$hotel_sqluser_val,$$hotel_sqlpassword_val,$$hotel_sqldatabase_val);
//$hotel_row = $obj->GetSingleRow("spssp_admin","stype=1");
mysql_connected($main_sqlhost,$main_sqluser,$main_sqlpassword,$main_sqldatabase);

//if($hotel_row){
// $password = $hotel_row["password"];
//}else{
  $password = generatePassword();
//}

$admin_code = $hotel_dbo->getAdminCode($hotel_code);


if($_POST['hotel_name'])
	{
		unset($post['email_confirm']);
		$hid = $obj->InsertData("super_spssp_hotel", $post);

    $hotel_code = $_POST["hotel_code"];
    $hotel_sqlhost_val = "hotel".((int)$hotel_code)."_sqlhost";
    $hotel_sqluser_val = "hotel".((int)$hotel_code)."_sqluser";
    $hotel_sqlpassword_val = "hotel".((int)$hotel_code)."_sqlpassword";
    $hotel_sqldatabase_val = "hotel".((int)$hotel_code)."_sqldatabase";

    if($hid > 0){
      mysql_close();

      $link = mysql_connected($$hotel_sqlhost_val,$$hotel_sqluser_val,$$hotel_sqlpassword_val,$$hotel_sqldatabase_val);
      $hotel_row = $obj->GetSingleRow("spssp_admin","stype=1");

      //CREAT ARRAY
      $data['username'] =  $_POST['adminid'];
      $data['password'] =  $_POST['password'];
//      $data['email'] =  $_POST['email'];
      $data['name'] =  $_POST['adminstrator'];
      $data['permission'] =  "333";
      $data['display_order'] =  time();
      $data['stype'] = 1;
      if(!$hotel_row){
        $obj->InsertData("spssp_admin", $data);
      }else{
        $obj->UpdateData("spssp_admin", $data, "id=".$hotel_row["id"]);
      }
      mysql_close($link);
    }
		redirect("hotel.php");
	}
?>

 <div id="topnavi">

	<h1>サンプリンティングシステム 　管理</h1>
    <div id="top_btn"> <a href="logout.php"><img src="img/common/btn_logout.jpg" alt="ログアウト" width="102" height="19" /></a></div>
</div>
  <div id="container">
    <div id="contents">
      <h2><a href="hotel.php">ホテル管理</a> &raquo; 新規登録</h2>
      <div class="subtitle">新規登録　<span class="txtred">*</span>項目は必須です。</div>
      <div class="txt2">
	  <form name="hotelform" id="hotelform" action="hotel_input.php" method="post">
        <table class="new_super_message" cellpadding="5" cellspacing="1" border="0" align="left">
          <tr>
            <td width="25%" align="left">ホテルコード</td>
            <td width="2%">：</td>
            <td width="73%">
            <input name="hotel_code" value="<?=$hotel_code?>" readonly="readonly" type="text" id="hotel_code" size="50" style="border: #ffffff;" /></td>
          </tr>
          <tr>
            <td align="left">ホテル名<span class="txtred">*</span></td>
            <td>：</td>
            <td><input name="hotel_name" type="text" id="hotel_name" size="50" /></td>
          </tr>
          <tr>
            <td align="left">郵便番号<span class="txtred">*</span></td>
            <td>：</td>
            <td><input name="zip" type="text" id="zip" size="20" /></td>
          </tr>
          <tr>
            <td align="left">住所1<span class="txtred">*</span></td>
            <td>：</td>
            <td><input name="address1" type="text" id="address1" size="50" /></td>
          </tr>
          <tr>
            <td align="left">住所2</td>
            <td>：</td>
            <td><input name="address2" type="text" id="address2" size="50" /></td>
          </tr>
          <tr>
            <td align="left">電話番号<span class="txtred">*</span></td>
            <td>：</td>
            <td><input name="phone" type="text" id="phone" size="30" /></td>
          </tr>
          <tr>
            <td align="left">担当者<span class="txtred">*</span></td>
            <td>：</td>
            <td><input name="contact" type="text" id="contact" size="30" /></td>
          </tr>
          <tr>
            <td align="left"><div>メールアドレス<span class="txtred">*</span></div></td>
            <td>：</td>
            <td><input name="email" type="text" id="email" size="30" /></td>
          </tr>
          <tr>
            <td align="left"><div>メールアドレス確認用<span class="txtred">*</span></div></td>
            <td>：</td>
            <td><input name="email_confirm" type="text" id="email_confirm" size="30" /></td>
          </tr>
          <tr>
            <td align="left"><div>管理者</div></td>
            <td>：</td>
            <td><input name="adminstrator" type="text" id="adminstrator" size="30" readonly="readonly""/></td>
          </tr>
          <tr>
            <td align="left">管理者用 ID </td>
            <td>：</td>
            <td><input name="adminid" type="text" id="adminid" size="50" value="<?=$admin_code?>" readonly="readonly" style="border: #ffffff;" /></td>
          </tr>
          <tr>
            <td align="left">パスワード</td>
            <td>：</td>
            <td><input name="password" type="text" value="<?=$password?>" id="password" size="50"  readonly="readonly" style="border: #ffffff;" /></td>
          </tr>
          <tr>
            <td align="left">招待者リストデータ削除日<span class="txtred">*</span></td>
            <td>：</td>
            <td><input name="delete_guest" type="text" id="delete_guest" size="5" value="6" />
              ヶ月後</td>
          </tr>
          <tr>
            <td align="left">挙式情報データ削除日<span class="txtred">*</span></td>
            <td>：</td>
            <td><input name="delete_weeding" type="text" id="delete_weeding" size="5" value="12" />
              ヶ月後</td>
          </tr>
          <tr>
            <td colspan="3" align="right">&nbsp;</td>
          </tr>
          <tr>
            <td align="left">&nbsp;</td>
            <td colspan="2">

		<!--	<input type="button" value="登録" onclick="validform_hotel();" />
              &nbsp;
            <input type="reset" value="キャンセル"  /></td>-->
			  <img width="83" height="22" alt="登録" src="img/common/btn_regist_update_admin.jpg" onclick="validform_hotel();">
					<!--<input type="button" value="送信" onclick="save_super_message();" /> -->&nbsp;
               <img width="83" height="22" border="0" src="img/common/btn_clear_admin.jpg" alt="ｸﾘｱ" onclick="clearSubmit();">
          </tr>
        </table>
		</form>
<p></p>
      </div>
    </div>
  </div>
<?php
  	include_once("inc/sidebar.inc.php");
	include_once("inc/footer.inc.php");
?>
<script language="javascript" type="text/javascript">
function clearSubmit()
{
    document.getElementById("hotel_name").value='';
	document.getElementById("zip").value='';
	document.getElementById("address1").value='';
	document.getElementById("address2").value='';
	document.getElementById("phone").value='';
	document.getElementById("contact").value='';
	document.getElementById("email").value='';
	document.getElementById("email_confirm").value='';
	document.getElementById("adminstrator").value='';
	document.getElementById("delete_guest").value='';
	document.getElementById("delete_weeding").value='';

}

// UCHIDA EDIT 11/08/19 アラートメッセージの表示、メール内容の確認
var gReg = /[!-~A-Za-z0-9ｦ-ﾝ]$/;
function validform_hotel(){

	var email = document.getElementById('email').value;

//	RE_EMAIL   = new RegExp(/^[A-Za-z0-9](([_|\.|\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([_|\.|\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})$/);
	if(document.getElementById("hotel_name").value=='')
	{
		alert("ホテル名を入力してください");
		document.getElementById('hotel_name').focus();
		return false;
	}
	else {
		var hn = document.getElementById("hotel_name").value;
		if(gReg.test(hn)==true) {
			alert("ホテル名は全て全角で入力してください");
			document.getElementById('hotel_name').focus();
			return false;
		}
	}
	if(document.getElementById("zip").value=='')
	{
		alert("郵便番号を入力してください");
		document.getElementById('zip').focus();
		return false;
	}
	if(document.getElementById("address1").value=='')
	{
		alert("住所１を入力してください");
		document.getElementById('address1').focus();
		return false;
	}
	if(document.getElementById("phone").value=='')
	{
		alert("電話番号を入力してください");
		document.getElementById('phone').focus();
		return false;
	}
	if(document.getElementById("contact").value=='')
	{
		alert("担当者を入力してください");
		document.getElementById('contact').focus();
		return false;
	}
	if(document.getElementById("email").value=='')
	{
		alert("メールアドレスを入力してください");
		document.getElementById('email').focus();
		return false;
	}
//	 if(!RE_EMAIL.exec(email))
	if(email_validate(email)==false)
	{
		alert("正しいメールアドレスではありません");
		document.getElementById('mail').focus();
		return false;
	}
	if(document.getElementById("email").value!=document.getElementById("email_confirm").value)
	{
		alert("メールアドレスが一致しません。再度入力してください");
		document.getElementById('email').focus();
		return false;
	}
	if(document.getElementById("delete_guest").value=='')
	{
		alert("招待者リストデータ削除日を入力してください");
		document.getElementById('delete_guest').focus();
		return false;
	}
	if(document.getElementById("delete_weeding").value=='')
	{
		alert("挙式情報データ削除日を入力してください");
		document.getElementById('delete_weeding').focus();
		return false;
	}

	document.hotelform.submit();
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
</script>