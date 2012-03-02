<?php
	require_once("inc/class.dbo.php");
	include_once("inc/checklogin.inc.php");
	$obj = new DBO();
	$post = $obj->protectXSS($_POST);
    $hcode=$HOTELID;

	include_once("inc/new.header.inc.php");

	if(isset($_POST['save']))
	{
	$hotelcode=jp_encode($_POST[hotel_code]);
    $name=jp_encode($_POST[name]);
    $zipcode=jp_encode($_POST[zipcode]);
    $address1=jp_encode($_POST[address1]);
    $address2=jp_encode($_POST[address2]);
    $tel=jp_encode($_POST[tel]);
    $contactperson=jp_encode($_POST[contactperson]);
    $email=jp_encode($_POST[email]);
    $delete_guest=jp_encode($_POST[delete_guest]);
    $delete_weeding=jp_encode($_POST[delete_weeding]);

include("inc/main_dbcon.inc.php");

$hotel_row = $obj->GetSingleRow("super_spssp_hotel", " hotel_code=".$hcode);
	   $query = "update super_spssp_hotel set hotel_name='".$name."',zip='".$zipcode."',address1='".$address1."',address2='".$address2."',phone='".$tel."',contact='".$contactperson."',email='".$email."',delete_guest='".$delete_guest."',delete_weeding='".$delete_weeding."' where hotel_code='".$hcode."' ";
	   mysql_query($query);
	   include("inc/return_dbcon.inc.php");

	   $query = "update spssp_admin set email='".$email."' where username='".$hotel_row[adminid]."' ";
	   mysql_query($query);
	   echo "<script> alert('ホテル情報が保存されました'); </script>";
	  redirect("hotel_info.php");exit;
	}

	$disp_option1 = "";
	$disp_option2 = "";
	$disp_option3='<span class="txtred">*</span>';
	if ($_SESSION['user_type']==222) {
		$disp_option1 = ' readonly="readonly"; ';
		$disp_option2 = ' border:#ffffff; ';
		$disp_option3="";
	}
	
?>
<link rel="stylesheet" type="text/css" href="../css/jquery.ui.all.css">
<script src="../js/jquery-1.4.2.js" type="text/javascript"></script>
<script src="../js/jquery.ui.position.js" type="text/javascript"></script>
<script src="../js/jquery.ui.core.js" type="text/javascript"></script>
<script src="../js/jquery.ui.widget.js" type="text/javascript"></script>
<script type="text/javascript" src="../js/jquery.ui.dialog.js"></script>
<script src="../js/ui/jquery.effects.core.js"></script>
<script src="../js/ui/jquery.effects.blind.js"></script>
<script src="../js/ui/jquery.effects.fade.js"></script>


<script src="../js/noConflict.js" type="text/javascript"></script>

<script type="text/javascript" language="javascript" src="../datepicker/prototype-1.js"></script>

<script type="text/javascript" language="javascript" src="../datepicker/prototype-date-extensions.js"></script>
<script type="text/javascript" language="javascript" src="../datepicker/behaviour.js"></script>

<script type="text/javascript" language="javascript" src="../datepicker/datepicker.js"></script>
<script type="text/javascript">

Control.DatePicker.Locale['ahad'] = { dateTimeFormat: 'yyyy-MM-dd HH:mm', dateFormat: 'yyyy-MM-dd', firstWeekDay: 1, weekend: [0,6], language: 'ahad'};

Control.DatePicker.Language['ahad'] = { months: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'], days:[  '日', '月','火', '水', '木', '金','土'], strings: { 'Now': '今度', 'Today': '今日', 'Time': '時間', 'Exact minutes': '詳細に入力 ', 'Select Date and Time': '選択して日付と時刻', 'Open calendar': 'オープンカレンダー' } };
</script>

<link rel="stylesheet" href="../datepicker/datepicker.css">
<script type="text/javascript" language="javascript" src="../datepicker/behaviors.js"></script>


<style>
.datepickerControl table
{
width:200px;
}
.input_text
{
width:100px;
}
.datepicker
{
width:100px;
cursor:pointer;
}
.admin_desc
{
display:none;
padding:10px 10px 10px 120px;
}
.top_searchbox1, .top_searchbox2
{
width:490px;
}
.new_super_message
{
width:300px;
display:none;
}
.new_super_message tr td
{
padding:5px;
}
.super_desc
{
padding:5px 5px 5px 160px;;
display:none;
color:#999999;
font-weight:normal;
}
.txtred {color:#ff0000;}
</style>
<script type="text/javascript">

RE_EMAIL   = new RegExp(/^[A-Za-z0-9](([_|\.|\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([_|\.|\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})$/);
function validForm()
{
	var postcode = document.getElementById('zipcode').value;
	if($j("#name").val() =='')
	{
	     alert("ホテル名が未入力です");
		 $j("#name").focus();
		 return false;
	}
	if (postcode.length != 0) {
		if( postcode.match( /[^0-9\s-]+/ ) ) {
				alert("郵便番号は半角数字と'-'だけで入力してください");
        $j("#zipcode").focus();
				return false;
		}
		if (postcode.length !=8) {
			alert("郵便番号は'123-4567'の形式で入力してください");
      $j("#zipcode").focus();

			return false;
		}
	}
	if($j("#address1").val() =='')
	{
	     alert("住所1が未入力です");
		 $j("#address1").focus();
		 return false;
	}
	if($j("#tel").val() =='')
	{
	     alert("電話番号が未入力です");
		 $j("#tel").focus();
		 return false;
	}

	if($j("#contactperson").val() =='')
	{
	     alert("担当者が未入力です");
		 $j("#contactperson").focus();
		 return false;
	}
	if($j("#email").val() =='')
	{
		 alert("メールアドレスを入力して下さい");
		 $j("#email").focus();
		 return false;
	}
	else
	{

	 if(!RE_EMAIL.exec($j("#email").val()))
	 {
		alert("メールの書式が正しくありません");
		 $j("#email").focus();
		return false;
	  }
	}

	if ($j("#delete_guest").val() == "") {
		alert ("招待者リストデータ削除日を入力してください");
		$j("#delete_guest").focus();
		return false;
	}
	if (isNaN($j("#delete_guest").val()) == true) {
		alert ("招待者リストデータ削除日は半角数字で入力してください");
		$j("#delete_guest").focus();
		return false;
	}
	if ($j("#delete_weeding").val() == "") {
		alert ("挙式情報データ削除日を入力してください");
		$j("#delete_weeding").focus();
		return false;
	}
	if (isNaN($j("#delete_weeding").val()) == true) {
		alert ("挙式情報データ削除日は半角数字で入力してください");
		$j("#delete_weeding").focus();
		return false;
	}

	document.hotelinfo.submit();
}

function checkEmail(id)
{

	var userid = $j('#userid').val();
	if(userid == id)
	{
	   return false;
	}
	//alert(userid);
	if(userid)
	{
		$j.post('./ajax/checkemail.php', {'mail': userid}, function(data) {
		if(data ==1)
		{
		  alert("ユーザIDが重複しています");
		  document.getElementById('userid').value="";
		  return false;
		}
		});
	}

}

function clearSubmit()
{
    document.getElementById("name").value='';
	document.getElementById("zipcode").value='';
	document.getElementById("address1").value='';
	document.getElementById("address2").value='';
	document.getElementById("tel").value='';
	document.getElementById("contactperson").value='';
	document.getElementById("email").value='';
	document.getElementById("adminstrator").value='';
	document.getElementById("delete_guest").value='';
	document.getElementById("delete_weeding").value='';

}
</script>




<div id="topnavi">
    <?php
include("inc/main_dbcon.inc.php");
$hotel_name = $obj->GetSingleData(" super_spssp_hotel ", " hotel_name ", " hotel_code=".$hcode);
$hotel_row = $obj->GetSingleRow(" super_spssp_hotel", " hotel_code=".$hcode);
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
    <div id="contents">

        <h4><div style="width:300px;">ホテル情報</div></h4>

            <!--<div id="top_imgbox">
                <a href="javascript:void()" onclick="todays_user();"><img src="img/common/img_top01.jpg" width="200" height="122" class="top_img01" /></a>
                <img src="img/common/img_top02.jpg" width="202" height="32" class="top_img02" />
            </div>-->
            <div id="top_search_view">
          <br />
		  <p>
                	<form action="hotel_info.php?page=<?=$_GET['page']?>&id=<?=$sqlrow['id']?>" name="hotelinfo" method="post">
                       <table width="100%" border="0" cellspacing="6" cellpadding="3">
						  <tr>
						    <td width="150" align="left">ホテルコード</td>
							<td width="20" align="left"> ：</td>
							<td width="1358"; style="padding:1px;">
							<input type="hidden" name="hotel_code" id="hotel_code" value="<?=$hotel_row[hotel_code]?>"/>
							<?=$hotel_row[hotel_code]?></td>
						  </tr>
						  <tr>
						    <td align="left">ホテル名<?=$disp_option3?></td>
							<td align="left">：</td>
							<td><input type="text" name="name" id="name" <?=$disp_option1?> style="width:250px;padding:0px;border-style: inset; <?=$disp_option2?> " value="<?=$hotel_row[hotel_name]?>"/></td>
						  </tr>
						  <tr>
						    <td align="left">郵便番号<?=$disp_option3?></td>
							<td align="left">：</td>
							<td><input name="zipcode" type="text" id="zipcode" style="padding:0px;border-style: inset; <?=$disp_option2?> " size="10" maxlength="8" value="<?=$hotel_row[zip]?>"/>（例　231-0000）</td>
						  </tr>
						  <tr>
						    <td align="left">住所1<?=$disp_option3?></td>
							<td align="left">：</td>
							<td><input type="text" name="address1" id="address1" <?=$disp_option1?> style="width:250px;padding:0px;border-style: inset; <?=$disp_option2?> " value="<?=$hotel_row[address1]?>"/></td>
						  </tr>
						  <tr>
						    <td align="left">住所2</td>
							<td align="left">：</td>
							<td><input type="text" name="address2" id="address2" <?=$disp_option1?> style="width:250px;padding:0px;border-style: inset; <?=$disp_option2?> " value="<?=$hotel_row[address2]?>"/></td>
						  </tr>
						  <tr>
						    <td align="left">電話番号<?=$disp_option3?></td>
							<td align="left">：</td>
							<td><input type="text" name="tel" id="tel" <?=$disp_option1?> style="padding:0px;border-style: inset; <?=$disp_option2?> " value="<?=$hotel_row[phone]?>" />（例　0451111111)</td>
						  </tr>
						  <tr>
						    <td align="left">担当者<?=$disp_option3?></td>
							<td align="left">：</td>
							<td><input name="contactperson" type="text" id="contactperson" <?=$disp_option1?> style="padding:0px;border-style: inset; <?=$disp_option2?> " value="<?=$hotel_row[contact]?>"/></td>
						  </tr>
						  <tr>
						    <td align="left">メールアドレス<?=$disp_option3?></td>
							<td align="left">：</td>
							<td><input type="text" name="email" id="email" <?=$disp_option1?> style="padding:0px;border-style: inset; <?=$disp_option2?> " value="<?=$hotel_row[email]?>"/></td>
						  </tr>
						  <tr>
						    <td align="left">招待者リストデータ削除日</td>
							<td align="left">：</td>
							<td><input name="delete_guest" type="text" id="delete_guest" readonly="readonly"; style="padding:0px;border-style: inset; border:#ffffff; " size="5"   value="<?=$hotel_row[delete_guest]?>"/> ヶ月後</td>
						  </tr>
						  <tr>
						    <td align="left">挙式情報データ削除日</td>
							<td align="left"> ：</td>
							<td><input name="delete_weeding" type="text" id="delete_weeding" readonly="readonly"; style="padding:0px;border-style: inset; border:#ffffff; " size="5"   value="<?=$hotel_row[delete_weeding]?>"/> ヶ月後</td>
						  </tr>

                            <tr>
                              <td>&nbsp;</td>
                            <td>&nbsp;</td><td>&nbsp;</td>
                            </tr>
						  <tr>
						    <td></td>
							<td></td>
							<td>
							<?php if ($disp_option1=="") { ?>
							   <img width="82" height="22" alt="登録・更新" src="img/common/btn_save.jpg" onclick="validForm();">
							<?php } ?>
						  </tr>

						</table>
						<input type="hidden" name="save" value="<?=($sqlrow['id'])?"2":"1"?>" />

                    </form>
                </p>
            </div>

</div>
</div>

<?php
	include_once("inc/left_nav.inc.php");
?>


<?php
	include_once("inc/new.footer.inc.php");
?>
