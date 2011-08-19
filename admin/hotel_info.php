<?php
	require_once("inc/class.dbo.php");
	include_once("inc/checklogin.inc.php");
	$obj = new DBO();
	$post = $obj->protectXSS($_POST);
    $hcode="0001";

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

$hotel_row = $obj->GetSingleRow("dev2_main.super_spssp_hotel", " hotel_code=".$hcode);


	   $query = "update dev2_main.super_spssp_hotel set hotel_name='".$name."',zip='".$zipcode."',address1='".$address1."',address2='".$address2."',phone='".$tel."',contact='".$contactperson."',email='".$email."',delete_guest='".$delete_guest."',delete_weeding='".$delete_weeding."' where hotel_code='".$hotelcode."' ";
	   mysql_query($query);
      include("inc/return_dbcon.inc.php");

	   $query = "update spssp_admin set email='".$email."' where username='".$hotel_row[adminid]."' ";
	   mysql_query($query);


	  redirect("hotel_info.php");exit;
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
	if($j("#name").val() =='')
	{
	     alert("ホテル名");
		 $j("#name").focus();
		 return false;
	}
	if($j("#zipcode").val() =='')
	{
	     alert("郵便番号");
		 $j("#zipcode").focus();
		 return false;
	}
	if($j("#address1").val() =='')
	{
	     alert("住所1");
		 $j("#address1").focus();
		 return false;
	}
	if($j("#address2").val() =='')
	{
	     alert("住所2");
		 $j("#address2").focus();
		 return false;
	}
	if($j("#tel").val() =='')
	{
	     alert("電話番号");
		 $j("#tel").focus();
		 return false;
	}

	if($j("#contactperson").val() =='')
	{
	     alert("担当者");
		 $j("#contactperson").focus();
		 return false;
	}
	if($j("#email").val() =='')
	{
		 alert("「メールアドレス」を入力して下さい。");
		 $j("#email").focus();
		 return false;
	}
	else
	{

	 if(!RE_EMAIL.exec($j("#email").val()))
	 {
		alert("メールの書式が正しくありません。");
		 $j("#email").focus();
		return false;
	  }
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
		  alert("Userid duplicate.");
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
$hotel_name = $obj->GetSingleData(" dev2_main.super_spssp_hotel ", " hotel_name ", " hotel_code=".$hcode);
$hotel_row = $obj->GetSingleRow("dev2_main.super_spssp_hotel", " hotel_code=".$hcode);
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

        <h2><div style="width:300px;">ホテル情報設定</div></h2>

            <!--<div id="top_imgbox">
                <a href="javascript:void()" onclick="todays_user();"><img src="img/common/img_top01.jpg" width="200" height="122" class="top_img01" /></a>
                <img src="img/common/img_top02.jpg" width="202" height="32" class="top_img02" />
            </div>-->
            <div id="top_search_view">
          <br />
<p>

                	<form action="hotel_info.php?page=<?=$_GET['page']?>&id=<?=$sqlrow['id']?>" name="hotelinfo" method="post">
                       <table width="100%" border="0" cellspacing="1" cellpadding="3">
						  <tr>
							<td width="170" align="left">ホテルコード&nbsp;&nbsp;</td>
							<td width="1358">
							<input type="hidden" name="hotel_code" id="hotel_code" value="<?=$hotel_row[hotel_code]?>"/>
							<?=$hotel_row[hotel_code]?></td>
						  </tr>
						  <tr>
							<td align="left">ホテル名<span class="txtred">*</span>　</td>
							<td><input type="text" name="name" id="name" style="width:250px;padding:3px;" value="<?=$hotel_row[hotel_name]?>"/></td>
						  </tr>
						  <tr>
							<td align="left">郵便番号<span class="txtred">*</span>　</td>
							<td><input name="zipcode" type="text" id="zipcode" size="10" maxlength="7" value="<?=$hotel_row[zip]?>"/></td>
						  </tr>
						  <tr>
							<td align="left">住所1<span class="txtred">*</span>　</td>
							<td><input type="text" name="address1" id="address1" style="width:250px;padding:3px;" value="<?=$hotel_row[address1]?>"/></td>
						  </tr>
						  <tr>
							<td align="left">住所2&nbsp;&nbsp;</td>
							<td><input type="text" name="address2" id="address2" style="width:250px;padding:3px;" value="<?=$hotel_row[address2]?>"/></td>
						  </tr>
						  <tr>
							<td align="left">電話番号<span class="txtred">*</span>　</td>
							<td><input type="text" name="tel" id="tel" value="<?=$hotel_row[phone]?>" /></td>
						  </tr>
						  <tr>
							<td align="left">担当者<span class="txtred">*</span>　</td>
							<td><input name="contactperson" type="text" id="contactperson" size="15" value="<?=$hotel_row[contact]?>"/></td>
						  </tr>
						  <tr>
							<td align="left">メールアドレス<span class="txtred">*</span>　</td>
							<td><input type="text" name="email" id="email" value="<?=$hotel_row[email]?>"/></td>
						  </tr>
						  <tr>
							<td align="left">招待者リストデータ削除日<span class="txtred">*</span>　</td>
							<td><input name="delete_guest" type="text" id="delete_guest" size="5"   value="<?=$hotel_row[delete_guest]?>"/></td>
						  </tr>
						  <tr>
							<td align="left">挙式情報データ削除日<span class="txtred">*</span>　</td>
							<td><input name="delete_weeding" type="text" id="delete_weeding" size="5"   value="<?=$hotel_row[delete_weeding]?>"/></td>
						  </tr>

                            <tr>
                            <td>&nbsp;</td><td>&nbsp;</td>
                            </tr>
						  <tr>
							<td></td>
							<td>
			   <img width="82" height="22" alt="登録・更新" src="img/common/btn_regist_update.jpg" onclick="validForm();">
					<!--<input type="button" value="送信" onclick="save_super_message();" /> -->&nbsp;
               <img width="82" height="22" border="0" src="img/common/btn_clear.jpg" alt="クリア" onclick="clearSubmit();">


                           <!-- <input type="button" onclick="validForm();" value="保存" />　<input name="" type="button" value="クリア" />--></td>
						  </tr>

						</table>
						<input type="hidden" name="save" value="<?=($sqlrow['id'])?"2":"1"?>" />

                    </form></p>
            </div>

</div>
</div>

<?php
	include_once("inc/left_nav.inc.php");
?>


<?php
	include_once("inc/new.footer.inc.php");
?>