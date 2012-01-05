<?php
require_once("inc/class.dbo.php");
include_once("inc/checklogin.inc.php");
$obj = new DBO();
$post = $obj->protectXSS($_POST);
include_once("inc/header.inc.php");

if($_GET["id"]!="")
{
	$hotel_row = $obj->GetSingleRow("super_spssp_hotel", " id=".$_GET[id]);
}

if($_POST["hotel_name"])
	{
		unset($post['email_confirm']);
		$obj->UpdateData("super_spssp_hotel", $post, " id = ".(int)$_GET['id']);

    $hotel_code = $_POST["hotel_code"];
    $hotel_sqlhost_val = "hotel".((int)$hotel_code)."_sqlhost";
    $hotel_sqluser_val = "hotel".((int)$hotel_code)."_sqluser";
    $hotel_sqlpassword_val = "hotel".((int)$hotel_code)."_sqlpassword";
    $hotel_sqldatabase_val = "hotel".((int)$hotel_code)."_sqldatabase";

    mysql_close();

    $link = mysql_connected($$hotel_sqlhost_val,$$hotel_sqluser_val,$$hotel_sqlpassword_val,$$hotel_sqldatabase_val);
    $hotel_row = $obj->GetSingleRow("spssp_admin","stype=1");

    //CREAT ARRAY
    $data['username'] =  $_POST['adminid'];
    $data['password'] =  $_POST['password'];
//    $data['email'] =  $_POST['email'];
    $data['name'] =  $_POST['adminstrator'];
    $data['permission'] =  "333";
    $data['stype'] = 1;

    if(!$hotel_row){
      $obj->InsertData("spssp_admin", $data);
    }
    mysql_close($link);

		redirect("hotel.php");
	}

?>
  <div id="topnavi">
	<h1>サンプリンティングシステム 　管理</h1>
    <div id="top_btn"> <a href="logout.php"><img src="img/common/btn_logout.jpg" alt="ログアウト" width="102" height="19" /></a></div>
 </div>
  <div id="container">
    <div id="contents">
      <h2><a href="hotel.php">ホテル管理</a> &raquo; 登録更新</h2>
      <div class="subtitle">登録更新　<span class="txtred">*</span>項目は必須です。</div>
      <div class="txt2">
	  <form name="hotelform" id="hotelform" action="hotel_edit.php?id=<?=$hotel_row[id]?>" method="post">
        <table class="new_super_message" cellpadding="5" cellspacing="1" border="0" align="left">
          <tr>
            <td width="25%" align="right">ホテルコード</td>
            <td width="2%">：</td>
            <td width="73%"><input name="hotel_code" value="<?=$hotel_row[hotel_code]?>" readonly="readonly" style="border: #ffffff;" type="text" id="hotel_code" size="50" /></td>
          </tr>
          <tr>
            <td align="right">ホテル名<span class="txtred">*</span></td>
            <td>：</td>
            <td><input name="hotel_name" type="text" id="hotel_name" size="50" value="<?=$hotel_row[hotel_name]?>"/></td>
          </tr>
          <tr>
            <td align="right">郵便番号<span class="txtred">*</span></td>
            <td>：</td>
            <td><input name="zip" type="text" id="zip" size="20"  value="<?=$hotel_row[zip]?>"/></td>
          </tr>
          <tr>
            <td align="right">住所1<span class="txtred">*</span></td>
            <td>：</td>
            <td><input name="address1" type="text" id="address1" size="50"  value="<?=$hotel_row[address1]?>"/></td>
          </tr>
          <tr>
            <td align="right">住所2</td>
            <td>：</td>
            <td><input name="address2" type="text" id="address2" size="50"  value="<?=$hotel_row[address2]?>"/></td>
          </tr>
          <tr>
            <td align="right">電話番号<span class="txtred">*</span></td>
            <td>：</td>
            <td><input name="phone" type="text" id="phone" size="30"  value="<?=$hotel_row[phone]?>"/></td>
          </tr>
          <tr>
            <td align="right">担当者<span class="txtred">*</span></td>
            <td>：</td>
            <td><input name="contact" type="text" id="contact" size="30"  value="<?=$hotel_row[contact]?>"/></td>
          </tr>
          <tr>
            <td align="right"><div>メールアドレス<span class="txtred">*</span></div></td>
            <td>：</td>
            <td><input name="email" type="text" id="email" size="30"  value="<?=$hotel_row[email]?>"/></td>
          </tr>
          <tr>
            <td align="right"><div>メールアドレス確認用<span class="txtred">*</span></div></td>
            <td>：</td>
            <td><input name="email_confirm" type="text" id="email_confirm" size="30"   value="<?=$hotel_row[email]?>"/></td>
          </tr>
          <tr>
            <td align="right"><div>管理者</div></td>
            <td>：</td>
            <td><input name="adminstrator" type="text" id="adminstrator" size="30" readonly="readonly" style="border: #ffffff;" value="<?=$hotel_row[adminstrator]?>"/></td>
          </tr>
          <tr>
            <td align="right">管理者用 ID </td>
            <td>：</td>
            <td><input name="adminid" type="text" id="adminid" size="50"  readonly="readonly" style="border: #ffffff;"   value="<?=$hotel_row[adminid]?>"/></td>
          </tr>
          <tr>
            <td align="right">パスワード</td>
            <td>：</td>
            <td><input name="password" type="text"  id="password" size="50"   value="<?=$hotel_row[password]?>" readonly="readonly" style="border: #ffffff;" /></td>
          </tr>
          <tr>
            <td align="right">招待者リストデータ削除日<span class="txtred">*</span></td>
            <td>：</td>
            <td><input name="delete_guest" type="text" id="delete_guest" size="5"   value="<?=$hotel_row[delete_guest]?>"/>
              ヶ月後</td>
          </tr>
          <tr>
            <td align="right">挙式情報データ削除日<span class="txtred">*</span></td>
            <td>：</td>
            <td><input name="delete_weeding" type="text" id="delete_weeding" size="5"   value="<?=$hotel_row[delete_weeding]?>"/>
              ヶ月後</td>
          </tr>
          <tr>
            <td align="right">メッセージ機能の表示<span class="txtred">*</span></td>
            <td>：</td>
            <td>
              <input type="radio" name="message_display" value="1" <?php echo $hotel_row["message_display"]?"checked":"";?>>表示
              <input type="radio" name="message_display" value="0" <?php echo $hotel_row["message_display"]?"":"checked";?>>非表示
            </td>
          </tr>

          <tr>
            <td colspan="3" align="right">&nbsp;</td>
          </tr>
          <tr>
            <td align="right">&nbsp;</td>
            <td colspan="2">
			   <img width="83" height="22" alt="登録" src="img/common/btn_regist_update_admin.jpg" onclick="validform_hotel();">
					<!--<input type="button" value="送信" onclick="save_super_message();" /> -->&nbsp;
               <img width="83" height="22" border="0" src="img/common/btn_clear_admin.jpg" alt="ｸﾘｱ" onclick="clearSubmit();">
			<!--<input type="button" value="登録" onclick="validform_hotel();" />-->
              &nbsp;
            <!--<input type="reset" value="キャンセル"  /></td>-->
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

//UCHIDA EDIT 11/08/19 アラートメッセージの表示、メール内容の確認
var gReg = /^[A-Za-z0-9ｱ-ｹ]$/;
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