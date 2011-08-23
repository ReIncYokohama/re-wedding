<?php
require_once("inc/class.dbo.php");
include_once("inc/checklogin.inc.php");
$obj = new DBO();
$post = $obj->protectXSS($_POST);
include_once("inc/header.inc.php");

$hotel_code = $obj->GetSingleData(" super_spssp_hotel", " hotel_code", " 1=1 order by hotel_code DESC LIMIT 1");

// UCHIDA EDIT 11/08/11 デモ用のパスワードを用意
$PassWD["0001"] = "BCXqVf";
$PassWD["0002"] = "yYcZwd";
$PassWD["0003"] = "fcYGvb";
$PassWD["0004"] = "jxrHnB";
$PassWD["0005"] = "vfWZz2";
$PassWD["0006"] = "g8QbJz";
$PassWD["0007"] = "zpXQZ6";
$PassWD["0008"] = "WtRHfM";
$PassWD["0009"] = "PH6jwR";
$PassWD["0010"] = "vrW8QY";

// UCHIDA EDIT 11/08/11 ２進コードを１０進コードへ修正
if($hotel_code!='') $nums = strval(intval($hotel_code)+1);
else                $nums = "0001";
$hotel_code = $nums;
$admin_code = $nums;

/*
if($hotel_code!='')
$nums = bindec($hotel_code);
else
$nums=0;

$hotel_code=decbin($nums+1);
*/

$code_lan=strlen($hotel_code);

if($code_lan<4)
for($i=0;$i<(4-$code_lan);$i++)
$hotel_code_extra.="0";

$hotel_code=$hotel_code_extra.$hotel_code;

// $admin_code=decbin($nums+1);

$adminid="AA";

if($code_lan<8)
for($i=0;$i<(8-$code_lan);$i++)
$adminid_extra.="0";

$adminid.=$adminid_extra.$admin_code;

// // UCHIDA EDIT 11/08/11 0001から0010までのＩＤは用意したパスワードを指定する
	if (intval($hotel_code) <=10) 	$password = $PassWD[$hotel_code];
	else							$password = generatePassword();
//	$password=generatePassword();

	if($_POST['hotel_name'])
	{
		unset($post['email_confirm']);
		$post['hotel_name'] = "TESTtest";
		$hid = $obj->InsertData("super_spssp_hotel", $post);

		if($hid>0)
		{
			if($_POST['hotel_code']=="0001")
			{
				$dbhost='localhost'; $dbuser='wplus_hotel1'; $dbpass = 'wph1_123456';$dbname ="wplus_hotel1";
			}else if($_POST['hotel_code']=="0002")
			{
				$dbhost='localhost'; $dbuser='dev2_hotel1'; $dbpass = 'dev2_123456';$dbname ="dev2_hotel1";
			}
			else if($_POST['hotel_code']=="0003")
			{
				$dbhost='localhost'; $dbuser='wplus_hotel3'; $dbpass = 'wph3_123456';$dbname ="wplus_hotel3";
			}
			else if($_POST['hotel_code']=="0004")
			{
				$dbhost='localhost'; $dbuser='wplus_hotel4'; $dbpass = 'wph4_123456';$dbname ="wplus_hotel4";
			}
			else if($_POST['hotel_code']=="0005")
			{
				$dbhost='localhost'; $dbuser='wplus_hotel5'; $dbpass = 'wph5_123456';$dbname ="wplus_hotel5";
			}
			else if($_POST['hotel_code']=="0006")
			{
				$dbhost='localhost'; $dbuser='wplus_hotel6'; $dbpass = 'wph6_123456';$dbname ="wplus_hotel6";
			}
			else if($_POST['hotel_code']=="0007")
			{
				$dbhost='localhost'; $dbuser='wplus_hotel7'; $dbpass = 'wph7_123456';$dbname ="wplus_hotel7";
			}
			else if($_POST['hotel_code']=="0008")
			{
				$dbhost='localhost'; $dbuser='wplus_hotel8'; $dbpass = 'wph8_123456';$dbname ="wplus_hotel8";
			}
			else if($_POST['hotel_code']=="0009")
			{
				$dbhost='localhost'; $dbuser='wplus_hotel9'; $dbpass = 'wph9_123456';$dbname ="wplus_hotel9";
			}
			else if($_POST['hotel_code']=="0010")
			{
				$dbhost='localhost'; $dbuser='wplus_hotel10'; $dbpass = 'wph10_123456';$dbname ="wplus_hotel10";
			}

			$link_for_hotel = mysql_connect($dbhost, $dbuser, $dbpass) or die(mysql_error("データベースに接続できません"));
			mysql_select_db($dbname, $link_for_hotel);


			//CREAT ARRAY
			$data['username'] =  $_POST['adminid'];
			$data['password'] =  $_POST['password'];
			$data['email'] =  $_POST['email'];
			$data['name'] =  $_POST['adminstrator'];
			$data['permission'] =  "333";
			$data['display_order'] =  time();
			$data['stype'] = 1;

			$Lid = $obj->InsertData("spssp_admin", $data);

			if($Lid>0)
			{
				mysql_close($link_for_hotel);
			}
		}

		redirect("hotel.php");

	}
?>
  <div id="topnavi">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  <tr>
    <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
</table>
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
            <td><input name="adminid" type="text" id="adminid" size="50" value="<?=$adminid?>" readonly="readonly" /></td>
          </tr>
          <tr>
            <td align="left">パスワード</td>
            <td>：</td>
            <td><input name="password" type="text" value="<?=$password?>" id="password" size="50"  readonly="readonly" /></td>
          </tr>
          <tr>
            <td align="left">招待者リストデータ削除日<span class="txtred">*</span></td>
            <td>：</td>
            <td><input name="delete_guest" type="text" id="delete_guest" size="5" />
              ヶ月後</td>
          </tr>
          <tr>
            <td align="left">挙式情報データ削除日<span class="txtred">*</span></td>
            <td>：</td>
            <td><input name="delete_weeding" type="text" id="delete_weeding" size="5" />
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
function validform_hotel(){

	var email = document.getElementById('email').value;

//	RE_EMAIL   = new RegExp(/^[A-Za-z0-9](([_|\.|\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([_|\.|\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})$/);
	if(document.getElementById("hotel_name").value=='')
	{
		alert("ホテル名を入力してください");
		document.getElementById('hotel_name').focus();
		return false;
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