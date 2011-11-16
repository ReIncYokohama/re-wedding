<?php
	require_once("inc/include_class_files.php");
	include_once("inc/checklogin.inc.php");
	include_once("inc/new.header.inc.php");

	$obj = new DBO();
	$objInfo = new InformationClass();
	$objMsg = new MessageClass(); // UCHIDA EDIT 11/08/18 曜日表示のため

	$get = $obj->protectXSS($_GET);
	$user_id = (int)$get['user_id'];
	$stuff_id = (int)$get['stuff_id'];

if($user_id>0) {
	$user_row = $obj->GetSingleRow("spssp_user"," id= $user_id");

	$query_string = "SELECT * FROM spssp_gaizi_detail_for_user WHERE gu_id = $user_id";
	$man_firstname_gaijis = $obj->getRowsByQuery($query_string." and gu_trgt_type=0");
	$man_lastname_gaijis = $obj->getRowsByQuery($query_string." and gu_trgt_type=1");
	$woman_firstname_gaijis = $obj->getRowsByQuery($query_string." and gu_trgt_type=2");
	$woman_lastname_gaijis = $obj->getRowsByQuery($query_string." and gu_trgt_type=3");

	function getGaijis($gaiji_objs){
		 $returnImage = "";
		 for($i=0;$i<count($gaiji_objs);++$i){
			$returnImage .= "<image src='../../gaiji-image/img_ans/".$gaiji_objs[$i]["gu_char_img"]."' width='20' height='20'>";
		 }
		 return $returnImage;
	}
	function getGaijisInputEle($gaiji_objs){
		 $html = "";
		 for($i=0;$i<count($gaiji_objs);++$i){
			$html .= getHiddenValue( $gaiji_objs[$i]["gu_trgt_type"],$gaiji_objs[$i]["gu_char_img"],$gaiji_objs[$i]["gu_id"],$gaiji_objs[$i]["gu_char_position"]);
		 }
		 return $html;
	}
	function getHiddenValue($type,$img,$gid,$gsid){
		  $typeArray = array("male_first","male_last","female_first","female_last");
		  $value = $typeArray[$type];
		  $html = "";
		  $html .= "<input type='hidden' name='".$value."_gaiji_img[]' value='".$img."'>";
		  $html .= "<input type='hidden' name='".$value."_gaiji_gid[]' value='".$gid."'>";
		  //$html .= "<input type='hidden' name='".$value."_gaiji_gsid[]' value='".$gsid."'>";
		  return $html;
	}
	function getAllGaijisInputEle($gaijis){
		 $html = "";
		 for($i=0;$i<count($gaijis);++$i){
			$html .= getGaijisInputEle($gaijis[$i]);
		 }
		 return $html;
	}

	$query_string="SELECT * FROM spssp_room where (status=1 or id=".$user_row['room_id'].")   ORDER BY display_order ASC ;";
	$rooms = $obj->getRowsByQuery($query_string);

	//$rooms = $obj->GetAllRow("spssp_room");
	$staff_name = $obj->GetSingleData("spssp_admin", "name"," id=".$user_row['stuff_id']);

	$All_staffs = $obj->GetAllRowsByCondition("spssp_admin"," `permission` != '111' ORDER BY `permission` DESC");

	$room_name = $obj->GetSingleData("spssp_user", "room_name"," id=".$user_id);

	if($room_name=="")
	$room_name = $obj->GetSingleData("spssp_room", "name"," id=".$user_row['room_id']);
	//print_r($user_row);
	$room_plan_rows = $obj->GetAllRowsByCondition("spssp_default_plan"," room_id=".$user_row['room_id']);

	$user_plan_row = $obj->GetSingleRow("spssp_plan"," user_id= $user_id");
	$user_plan_row_count = $obj->GetRowCount("spssp_plan"," user_id= $user_id");

	$room_row = $obj->GetSingleRow("spssp_room"," id= ".$user_row['room_id']);

	$disp_option1 = "";
	$disp_option2 = "";
	$disp_option3 = "";
	$disp_option4 = '<font color="red">*</font>';
	$disp_option5 = "";
	if ($user_plan_row['admin_to_pcompany'] == 3) {
		$disp_option1 = ' readonly="readonly"; ';
		$disp_option2 = ' border:#ffffff; ';
		$disp_option3 = ' border-style:none ';
		$disp_option4 = "";
		$disp_option5 = ' disabled="disabled" ';
	}
}
else {
	$disp_option4 = '<font color="red">*</font>';
	$query_string="SELECT * FROM spssp_room where status=1 ORDER BY display_order ASC ;";
	$rooms = $obj->getRowsByQuery($query_string);
	$All_staffs = $obj->GetAllRowsByCondition("spssp_admin"," `permission` != '111' ORDER BY `permission` DESC");
}

?>
<style>
.datepickerControl table
{
width:200px;
}
.tables
	{
		height:31px;
		width: 31px;
		float:left;
		margin:5px 5px;
		background-image:url(img/circle_small.jpg);

	}

	.tables p
	{
		margin-top:5px;
	}

	.tables p a
	{
	text-decoration:none;
	cursor:default;

	}

</style>
<script src="../js/noConflict.js" type="text/javascript"></script>
<script type="text/javascript" src="calendar/calendar.js"></script>


<script type="text/javascript" language="javascript" src="../datepicker/prototype-1.js"></script>

<script type="text/javascript" language="javascript" src="../datepicker/prototype-date-extensions.js"></script>
<script type="text/javascript" language="javascript" src="../datepicker/behaviour.js"></script>
<script type="text/javascript" language="javascript" src="../datepicker/datepicker.js"></script>
<script type="text/javascript">

Control.DatePicker.Locale['ahad'] = { dateTimeFormat: 'yyyy/MM/dd HH:mm', dateFormat: 'yyyy/MM/dd', firstWeekDay: 1, weekend: [0,6], language: 'ahad'};

Control.DatePicker.Language['ahad'] = { months: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'], days: [  '日', '月','火', '水', '木', '金','土'], strings: { 'Now': '今度', 'Today': '今日', 'Time': '時間', 'Exact minutes': '正確な分', 'Select Date and Time': '閉じる', 'Open calendar': 'オープンカレンダー' } };

</script>

<link rel="stylesheet" href="../datepicker/datepicker.css">
<script type="text/javascript" language="javascript" src="../datepicker/behaviors.js"></script>
<!-- <script type="text/javascript" src="../js/registration_validation.js"></script> -->
<script type="text/javascript" src="../js/jquery.cj-object-scaler.min.js"></script>
<script type="text/javascript" src="../js/ierange-m2.js"></script>
<script type="text/javascript" src="../js/gaiji.js"></script>
<script type="text/javascript">
$j(function(){
	var msg_html=$j("#msg_rpt").html();
	if(msg_html!='')
	{
		$j("#msg_rpt").fadeOut(5000);
	}
});

$j(document).ready(function(){

    $j('#password').keyup(function(){
		var r=checkvalidity();
    });
    $j(".image_adjust img").each(function() {
        $j(this).cjObjectScaler({
          method: "fit",
              fade: 1200
              });
      });

    setDeleteGaiji({
      input_id:"man_lastname",
          form_name:"male_last_gaiji_",
          div_image:"male_lastname_img_div_id",
          input_ele:"male_lastname_div_id"});
    setDeleteGaiji({
      input_id:"man_firstname",
          form_name:"male_first_gaiji_",
          div_image:"male_firstname_img_div_id",
          input_ele:"male_firstname_div_id"});
    setDeleteGaiji({
      input_id:"woman_lastname",
          form_name:"female_last_gaiji_",
          div_image:"female_lastname_img_div_id",
          input_ele:"female_lastname_div_id"});
    setDeleteGaiji({
      input_id:"woman_firstname",
          form_name:"female_first_gaiji_",
          div_image:"female_firstname_img_div_id",
          input_ele:"female_firstname_div_id"});

  $j(".check_sjs_1").change(function(){
      checkGaiji($j(this).val(),"../gaiji_check.php",this);
  });

});


function checkvalidity()
{
	var password = $j('#password').val();
	var c = password.length;
	if(c<6)
	{
		$j('#password_msg').html("<font style='color:black;'>パスワードは英数字6文字以上にしてください</font>");
	}
	else
	{
		$j('#password_msg').html("<font style='color:black;'>パスワードは英数字6文字以上にしてください</font>");
	}

    // All characters are numbers.
    return true;
}

function valid_plan(noUpdate)
{

	if (noUpdate == true) {
		alert("披露宴日が過ぎてきるため、登録・更新ができません");
		return false;
	}
	return true;
	//document.user_info_plan.submit();
}

var gReg = /[!-~A-Za-z0-9ｦ-ﾝ]$/;
function checkGroupForm(x, noUpdate)
{

	if (noUpdate == true) {
		alert("披露宴日が過ぎてきるため、登録・更新ができません");
		return false;
	}

	for(var y=1;y<=x;y++)
	{
		var gval = $j("#name_group"+y).val()
		if(gReg.test(gval) == true && gval)
		{
			alert("引出物グループ名は全角１文字で入力してください");
			$j("#name_group"+y).focus();
			return false;
		}

		else if (gval=="¥" && gval != "") {
			alert("引出物グループ名は全角１文字で入力してください");
			$j("#name_group"+y).focus();
			return false;
		}
	}
	return true;
}
function checkGiftForm(x, noUpdate)
{

	if (noUpdate == true) {
		alert("披露宴日が過ぎてきるため、登録・更新ができません");
		return false;
	}

	return true;
}
function checkMenuGroupForm(x, noUpdate)
{	//alert(x);

	if (noUpdate == true) {
		alert("披露宴日が過ぎてきるため、登録・更新ができません");
		return false;
	}

	for(var y=0;y<x;y++)
	{
		if($j("#menu_child"+y).val()=="")
		{
			alert("子供料理名を入力してください");
			$j("#menu_child"+y).focus();
			return false;
		}
	}
	return true;
}

function user_layout_title_input_show(id)
	{
		$j("#"+id).fadeOut();
		$j("#input_user_layoutname").fadeIn(500);

	}
function box_expand(id)
{

	$j("#"+id).toggle("slow");
}

function get_gaiji_value(from,img,gid,gsid)
{
  if(img==""){
    alert("外字が正しく選択されていません。");
    return;
  }
	if(from=="man_firstname")
	{
    set_gaiji("male_first","man_firstname","male_firstname_div_id","male_firstname_img_div_id",img,gid,gsid,"../..");
	}
	if(from=="man_lastname")
	{
    set_gaiji("male_last","man_lastname","male_lastname_div_id","male_lastname_img_div_id",img,gid,gsid,"../..");
	}
	if(from=="woman_firstname")
	{
    set_gaiji("female_first","woman_firstname","female_firstname_div_id","female_firstname_img_div_id",img,gid,gsid,"../..");
	}
	if(from=="woman_lastname")
	{
    set_gaiji("female_last","woman_lastname","female_lastname_div_id","female_lastname_img_div_id",img,gid,gsid,"../..");
	}
}

function change_gaiji_link(action)
{
	if(action == "man_firstname")
		$j("a#man_gaiji_link_id").attr("href", "../gaiji/palette.php?from=man_firstname");
	else if(action == "man_lastname")
		$j("a#man_gaiji_link_id").attr("href", "../gaiji/palette.php?from=man_lastname");
	else if(action == "woman_firstname")
		$j("a#woman_gaiji_link_id").attr("href", "../gaiji/palette.php?from=woman_firstname");
	else if(action == "woman_lastname")
		$j("a#woman_gaiji_link_id").attr("href", "../gaiji/palette.php?from=woman_lastname");
}

function valid_user(user_id, noUpdate, count_gift, count_group, count_child) // registration_validation.jsから移動 11/08/26
{
	if (noUpdate == true) {
		alert("披露宴日が過ぎてきるため、登録・更新ができません");
		return false;
	}

	var email = document.getElementById('mail').value;
	var com_email = document.getElementById('con_mail').value;
	
	var reg = /^[A-Za-z0-9](([_|\.|\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([_|\.|\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})$/;

	if(document.getElementById("party_day").value=='')
	{
		alert("披露宴日を正しく入力してください");
		document.getElementById('party_day').focus();
		return false;
	}

	   var str6 = document.getElementById("party_hour").value;
	   if (str6 == "") {
	      alert("披露宴時間を入力ください");
		  document.getElementById('party_hour').focus();
		  return false;
		}
	   if( str6.match( /[^0-9\s]+/ ) ) {
	      alert("披露宴時間は半角数字で入力ください");
		  document.getElementById('party_hour').focus();
		  return false;
	   }
	   if( str6.indexOf(" ")>=0 || str6.indexOf("　")>=0 ) {
	      alert("披露宴時間は半角数字で入力ください");
		  document.getElementById('party_hour').focus();
		  return false;
	   }
		if(str6 > 23)
		{
		      alert("披露宴時間は0から23の間で入力してください");
			  document.getElementById('party_hour').focus();
			  return false;
		}

		if(str6 > 22 || str6 < 7)
		{
			alert("披露宴時間は7:00〜22:00の間で入力してください");
			document.getElementById('party_hour').focus();
			return false;
		}

		var str7 = document.getElementById("party_minute").value;
		if(str7 >59)
		{
			alert("59分以上は入力できません");
			document.getElementById('party_minute').focus();
			return false;
		}
	   if (str7 == "") {
	      alert("披露宴時間を入力ください");
		  document.getElementById('party_minute').focus();
		  return false;
		}
	   if( str7.match( /[^0-9\s]+/ ) ) {
	      alert("披露宴時間は半角数字で入力ください");
		  document.getElementById('party_minute').focus();
		  return false;
	   }
	   if( str7.indexOf(" ")>=0 || str7.indexOf("　")>=0 ) {
	      alert("披露宴時間は半角数字で入力ください");
		  document.getElementById('party_minute').focus();
		  return false;
	   }
		if(str6 == 22 && str7 > 00)
		{
			alert("披露宴時間は7:00～22:00の間で入力してください");
			document.getElementById('party_minute').focus();
			return false;
		}

	if(document.getElementById("man_lastname").value=='')
	{
		alert("新郎の姓を正しく入力してください"); // UCHIDA EDIT 11/08/05 メッセージを変更
		document.getElementById('man_lastname').focus();
		return false;
	}
	if(document.getElementById("man_firstname").value=='')
	{
		alert("新郎の名を正しく入力してください");
		document.getElementById('man_firstname').focus();
		return false;
	}

	if(document.getElementById("man_furi_lastname").value=='')
	{
		alert("新郎の姓のふりがなを正しく入力してください");
		document.getElementById('man_furi_lastname').focus();
		return false;
	}

   var str1 = document.getElementById("man_furi_lastname").value;
   if( str1.match( /[^ぁ-ん\sー]+/ ) ) {
      alert("新郎の名のふりがなを正しく入力してください");
	  document.getElementById('man_furi_lastname').focus();
	  return false;
   }

   if(document.getElementById("man_furi_firstname").value=='')
	{
		alert("新郎の名のふりがなを正しく入力してください");
		document.getElementById('man_furi_firstname').focus();
		return false;
	}

   var str = document.getElementById("man_furi_firstname").value;
   if( str.match( /[^ぁ-ん\sー]+/ ) ) {
      alert("新郎の名のふりがなを正しく入力してください");
	  document.getElementById('man_furi_firstname').focus();
	  return false;
   }

	if(document.getElementById("woman_lastname").value=='')
	{
		alert("新婦の姓を正しく入力してください");
		document.getElementById('woman_lastname').focus();
		return false;
	}

	if(document.getElementById("woman_firstname").value=='')
	{
		alert("新婦の名を正しく入力してください");
		document.getElementById('woman_firstname').focus();
		return false;
	}

	if(document.getElementById("woman_furi_lastname").value=='')
	{
		alert("新婦の姓のふりがなを正しく入力してください");
		document.getElementById('woman_furi_lastname').focus();
		return false;
	}

    var str3 = document.getElementById("woman_furi_lastname").value;
   if( str3.match( /[^ぁ-ん\sー]+/ ) ) {
      alert("新婦の姓のふりがなを正しく入力してください");
	  document.getElementById('woman_furi_lastname').focus();
	  return false;
   }

   if(document.getElementById("woman_furi_firstname").value=='')
	{
		alert("新婦の名のふりがなを正しく入力してください");
		document.getElementById('woman_furi_firstname').focus();
		return false;
	}

    var str2 = document.getElementById("woman_furi_firstname").value;
   if( str2.match( /[^ぁ-ん\sー]+/ ) ) {
      alert("新婦の名のふりがなを正しく入力してください");
	  document.getElementById('woman_furi_firstname').focus();
	  return false;
   }

	if(document.getElementById("marriage_day").value=='')
	{
//		alert("挙式日を正しく入力してください");
//		document.getElementById('marriage_day').focus();
//		return false;
	}
    var str4 = document.getElementById("marriage_hour").value;
    if (str4 == "") {
//      alert("挙式時間を入力ください");
//	  document.getElementById('marriage_hour').focus();
//	  return false;
    }
    if( str4.match( /[^0-9\s]+/ ) ) {
      alert("挙式時間は半角数字で入力してください");
	  document.getElementById('marriage_hour').focus();
	  return false;
    }
    if( str4.indexOf(" ")>=0 || str4.indexOf("　")>=0 ) {
      alert("挙式時間は半角数字で入力してください");
	  document.getElementById('marriage_hour').focus();
	  return false;
    }
	if(str4 > 23)
	{
	      alert("挙式時間は0から22の間で入力してください");
		  document.getElementById('marriage_hour').focus();
		  return false;
	}

	if(str4!="" && (str4 > 22 || str4 < 7))
	{
		alert("挙式時間は7:00〜22:00の間で入力してください");
		document.getElementById('marriage_hour').focus();
		return false;
	}

	var str5 = document.getElementById("marriage_minute").value;
	if(str5 > 59)
	{
		alert("59分以上は入力できません");
		document.getElementById('marriage_minute').focus();
		return false;
	}
  if (str5 == "") {
//      alert("挙式時間を入力ください");
//	  document.getElementById('marriage_minute').focus();
//	  return false;
	}
   if( str5.match( /[^0-9\s]+/ ) ) {
      alert("挙式時間は半角数字で入力してください");
	  document.getElementById('marriage_minute').focus();
	  return false;
   }
   if( str5.indexOf(" ")>=0 || str5.indexOf("　")>=0 ) {
      alert("挙式時間は半角数字で入力してください");
	  document.getElementById('marriage_minute').focus();
	  return false;
   }
   if(str4!="" && (str4 == 22 && str5 > 00))
	{
	   alert("挙式時間は7:00～22:00の間で入力してください");
		document.getElementById('marriage_minute').focus();
		return false;
	}

	if(document.getElementById("room_id").value=='')
	{
//		alert("必須項目は必ず入力してください。");
//		document.getElementById('room_id').focus();
//		return false;
	}

	if(document.getElementById("religion").value=='')
	{
//		alert("挙式種類を選択してください");
//		document.getElementById('religion').focus();
//		return false;
	}


//	if(document.getElementById("user_id").value=='')
//	{
//		alert("ログインIDを入力してください");
//		document.getElementById('user_id').focus();
//		return false;
//	}

	var radio3  = document.user_form_register.subcription_mail;

// UCHIDA EDIT 11/08/05 メッセージを変更
	if(radio3[0].checked)
	{
		if(email=='')
		{
			alert("メールアドレスが未入力です");
			document.getElementById('mail').focus();
			return false;
		}
		else
		{
			if(email_validate(email)==false)
			{
				alert("正しいメールアドレスではありません");//Enter a valid email address.
				document.getElementById('mail').focus();
				return false;
			}

			if(com_email!=email)
			{
				alert("メールアドレス確認用を正しく入力してください");
				document.getElementById('con_mail').focus();
				return false;
			}
		}
	}else{

		if(email !='')
		{
			if(email_validate(email)==false)
			{
				alert("正しいメールアドレスではありません");//Enter a valid email address.
				document.getElementById('mail').focus();
				return false;
			}

			if(com_email != email)
			{
				alert("メールアドレス確認用を正しく入力してください");
				document.getElementById('con_mail').focus();
				return false;
			}
		}
	}
	if(email =='' && com_email != "") {
		alert("メールアドレスが未入力です");
		document.getElementById('mail').focus();
		return false;
	}

	var confirm_day_num = document.getElementById("confirm_day_num").value;
	if (isNaN(parseInt(confirm_day_num, 10))) {
		alert("席次表本発注締切日は半角数字で入力してください");
		document.getElementById('confirm_day_num').focus();
		return false;
	}
	var limitation_ranking = document.getElementById("limitation_ranking").value;
	if (isNaN(parseInt(limitation_ranking, 10))) {
		alert("席次表編集利用制限日は半角数字で入力してください");
		document.getElementById('limitation_ranking').focus();
		return false;
	}
	var order_deadline = document.getElementById("order_deadline").value;
	if (isNaN(parseInt(order_deadline, 10))) {
		alert("引出物本発注締切日は半角数字で入力してください");
		document.getElementById('order_deadline').focus();
		return false;
	}
	
	if (valid_plan(noUpdate) == false) return false;
	if (checkGiftForm(count_gift, noUpdate) == false) return false;
	if (checkGroupForm(count_group, noUpdate) == false) return false;
//	if (checkMenuGroupForm(count_group, noUpdate) == false) return false;


   //gaiji_check
   var return_flag = true;
   $j(".check_sjs_1").each(function(){
       if(return_flag && !checkGaiji($j(this).val(),"../gaiji_check.php",this)) return_flag = false;
   });
   if(!return_flag) return false;


	if(user_id==0) {
		document.user_form_register.submit();
	}
	else if(document.getElementById("room_id").value==document.getElementById("current_room_id").value) {
		document.user_form_register.submit();
	}
	else {
		if(confirm("披露宴会場を変更すると、現在の設定が削除されます。変更してよろしいですか？")) {
			document.user_form_register.submit();
		}
	}
}

function email_validate(email) {
   var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
   if(reg.test(email) == false) {
       return false;
   }
   else {
   		return true;
   }
}

function dowload_options_change() {
  var element = document.getElementById("dowload_options");
  for(var i=0; i<element.options.length; i++) {
    var option = element.options[i];
    if(option.selected) {
      var sel = option.value;
    }
  }

  var doc = document.getElementById("print_size");
  switch (sel) {
    case "1":
        doc.length = 2;
        doc.options[0].text = "A3";
        doc.options[0].value = 1;
        doc.options[1].text = "B4";
        doc.options[1].value = 2;
        doc.options[1].selected = true;
        break;
    case "2":
        doc.length = 1;
        doc.options[0].text = "A3";
        doc.options[0].value = 1;
        doc.options[0].selected = true;
        break;
    case "3":	  
        doc.length = 2;
        doc.options[0].text = "A3";
        doc.options[0].value = 1;
        doc.options[0].selected = true;
        doc.options[1].text = "B4";
        doc.options[1].value = 2;
        break;
  }
}

change_record = false;
window.onchange = function(event) {
	change_record = true;
}

function date_change () {
	change_record = true;
}

function check_change (url) {
	if (change_record) {
		var ans = confirm("変更したお客様情報が無効になります\nよろしいですか？");
		if (ans) 	return window.location = url;
		else		return false;
	}
	else {
		window.location = url;
	}
}

</script>

<script type="text/javascript"><!--
function m_win(url,windowname,width,height) {
 var features="location=no, menubar=no, status=yes, scrollbars=yes, resizable=yes, toolbar=no";
 if (width) {
  if (window.screen.width > width)
   features+=", left="+(window.screen.width-width)/2;
  else width=window.screen.width;
  features+=", width="+width;
 }
 if (height) {
  if (window.screen.height > height)
   features+=", top="+(window.screen.height-height)/2;
  else height=window.screen.height;
  features+=", height="+height;
 }
 window.open(url,windowname,features);
}
// --></script>
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
    <div id="contents">
    <div style="font-size:11px; width:250px;">

  <?php if($user_id>0) echo $objInfo->get_user_name_image_or_src($user_row['id'] ,$hotel_id=1, $name="man_lastname.png",$extra="thumb1",$height=20)."・";?>

  <?php if($user_id>0) echo $objInfo->get_user_name_image_or_src($user_row['id'] ,$hotel_id=1, $name="woman_lastname.png",$extra="thumb1",$width=20)."  様";?>

    </div>
        <h4><div style="width:500px; ">
	<!-- UCHIDA EDIT 11/08/02
	        <a href="users.php">お客様一覧</a> &raquo; お客様挙式情報</div>
	-->
		<?php
		if($user_id>0) {
			if($stuff_id==0) {
	            echo '<a href="manage.php">ＴＯＰ</a> &raquo; お客様挙式情報 &raquo; お挙式情報・各種設定';
			}
			else {
	            echo '<a href="users.php">管理者用お客様一覧</a> &raquo; お客様挙式情報 &raquo; お挙式情報・各種設定';
			}
		}
		else {
	        echo 'お客様新規登録';
		}
		?>
		</div>
        </h4>
		<?php if($user_id>0) { ?>
		<div style="width:1035px;">
        	<div class="navi">
            	<img src="img/common/navi01_on.jpg"/>
            </div>
        	<div class="navi">
            	<a href="message_user.php?user_id=<?=$user_id?>&stuff_id=<?=$stuff_id?>">
                <img src="img/common/navi02.jpg" onMouseOver="this.src='img/common/navi02_over.jpg'"onMouseOut="this.src='img/common/navi02.jpg'" />
                </a>
            </div>
        	
            
            <div class="navi"><a href="guest_gift.php?user_id=<?=$user_id?>&stuff_id=<?=$stuff_id?>">
            <img src="img/common/navi04.jpg" onMouseOver="this.src='img/common/navi04_over.jpg'"onMouseOut="this.src='img/common/navi04.jpg'" />
            </a></div>
            <div class="navi">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
        	<div class="navi">
            	<a href="javascript:void(0);" onClick="windowUserOpen('user_dashboard.php?user_id=<?=$user_id?>')">
<img src="img/common/navi03.jpg" onMouseOver="this.src='img/common/navi03_on.jpg'"onMouseOut="this.src='img/common/navi03.jpg'" />
                </a>
            </div><div class="navi2">
            
<?php if($_SESSION["super_user"]){　?>
          <div class="navi"><a href="csv_upload.php?user_id=<?=$user_id?>"  onclick="m_win(this.href,'mywindow7',700,200); return false;">
<img src="img/common/navi05.jpg" onMouseOver="this.src='img/common/navi05_on.jpg'"onMouseOut="this.src='img/common/navi05.jpg'" />
          </a></div>
<?php } ?></div>
        	<div style="clear:both;"></div>
        </div>
        <br />
        <?php } ?>

<!-- UCHIDA EDIT 11/08/05 左寄せなどその他の修正  -->
		<h2>
		<?php if($user_id>0) { ?>
        	<div style="width:400px;"><font color="#2052A3"><strong>お客様挙式情報</strong></font></div>
        <?php } else {?>
        	<div style="width:400px;"><font color="#2052A3"><strong>お客様新規登録</strong></font></div>
        <?php } ?>
        </h2>
　 <?php if (preg_match("/red/", $disp_option4)) echo '<font color="red">*</font>の付いた項目は必須です。' ?> <br /><br />
		<?php
		//echo "<pre>";
		//print_r($user_row);

		?>
		<div id="div_box_1" style="width:1000px;">
         <form action="insert_user.php?user_id=<?=$user_id;?>" method="post" name="user_form_register">
        <table width="800" border="0" cellspacing="10" cellpadding="0">
            <tr>
              <td width="160" align="left" valign="middle" nowrap="nowrap">披露宴日<?=$disp_option4?>　</td>
              <td width="10" align="left" valign="middle" nowrap="nowrap">：</td>
                <td colspan="1" align="left" valign="middle" nowrap="nowrap">
                	<input name="party_day" type="text" id="party_day" value="<?if($user_id>0) echo $obj->date_dashes_convert($user_row['party_day'])?>" size="15" readonly="readonly" style="<?=$disp_option2?> <?=$disp_option3?> background: url('img/common/icon_cal.gif') no-repeat scroll right center rgb(255, 255, 255); padding-right: 20px;padding-top:4px; padding-bottom:4px;" <?php if($disp_option2=="") echo 'class="datepicker" onclick="date_change()"'; ?> />
					<?php
					if ($user_row['party_day'] < date("Y-m-d") && $user_id>0) $noUpdate = true; else $noUpdate = false;
					//$party_day_array = explode("-",$user_row['party_day']);
					?>
					<!--<input type="text" style="width:30px;" maxlength="4" name="party_year" id="party_year" value="<?=$party_day_array[0]?>">/
					<input type="text" style="width:17px;" maxlength="2" name="party_month" id="party_month" value="<?=$party_day_array[1]?>">/
					<input type="text" style="width:17px;" maxlength="2" name="party_day" id="party_day" value="<?=$party_day_array[2]?>"> yyyy/mm/dd-->
                <!--&nbsp;<a href="javascript:void(0)" onclick="document.getElementById('party_day').value='';">クリア </a>-->
                </td>
				<td colspan="2" align="left" valign="middle" nowrap="nowrap">　　&nbsp;披露宴時間<?=$disp_option4?>：<!--<input name="party_day_with_time" type="text" id="party_day_with_time" value="<?=date("H:i", strtotime($user_row['party_day_with_time']))?>" size="10" readonly="readonly" style="width:86px;background: url('img/common/icon_cal.gif') no-repeat scroll right center rgb(255, 255, 255); padding-right: 20px;" class="timepicker"/>-->
				<?php
				$party_time_array = explode(":",$user_row['party_day_with_time']);
				?> 
				&nbsp;
				<input type="text" <?=$disp_option1?> style="padding-top:4px; padding-bottom:4px;width:17px;border-style: inset;<?=$disp_option2?> <?=$disp_option3?> " maxlength="2" name="party_hour" id="party_hour" value="<?=$party_time_array[0]?>"> :
				<input type="text" <?=$disp_option1?> style="padding-top:4px; padding-bottom:4px;width:17px;border-style: inset;<?=$disp_option2?> <?=$disp_option3?> " maxlength="2" name="party_minute" id="party_minute" value="<?=$party_time_array[1]?>"> (24時間表記)
                <!--&nbsp;<a href="javascript:void(0)" onclick="document.getElementById('party_day_with_time').value='';">クリア </a>-->
                </td>
            </tr>

            <tr>
              <td width="160" align="left" valign="middle" nowrap="nowrap">新郎氏名<?=$disp_option4?>
              <td width="10" align="left" valign="middle" nowrap="nowrap">：</td>
                <td width="92%" colspan="3" align="left" valign="middle" nowrap="nowrap">
		   <div style="height:20px;width:346px;">

		   	<div id="male_lastname_img_div_id" style="width:173px;float:left;height:20px;"><?php if($user_id>0) echo getGaijis($man_lastname_gaijis);?></div>
	      <div id="male_firstname_img_div_id" style="width:173px;float:left;height:20px;"><?php if($user_id>0) echo getGaijis($man_firstname_gaijis);?></div>
	     	<div id="male_firstname_div_id"><?php if($user_id>0 && $man_firstname_gaijis) echo getGaijisInputEle($man_firstname_gaijis);?></div>
        <div id="male_lastname_div_id"><?php if($user_id>0 && $man_lastname_gaijis) echo getGaijisInputEle($man_lastname_gaijis);?></div>
	   	
                   </div>
                    <input name="man_lastname" class="check_sjs_1" <?=$disp_option1?> style="padding-top:4px; padding-bottom:4px;border-style: inset; <?=$disp_option2?> <?=$disp_option3?> " type="text" id="man_lastname" value="<?=$user_row['man_lastname']?>" size="30" onclick="change_gaiji_link('man_lastname')"  onblur="set_gaiji_position()"/>
					<input name="man_firstname"  class="check_sjs_1" <?=$disp_option1?> type="text" style="padding-top:4px; padding-bottom:4px;border-style: inset; <?=$disp_option2?> <?=$disp_option3?> " id="man_firstname" value="<?=$user_row['man_firstname']?>" size="30"  onclick="change_gaiji_link('man_firstname')"  onblur="set_gaiji_position()"/>
                	様　<?php if ($disp_option1=="") { ?> <a id="man_gaiji_link_id" onclick="m_win(this.href,'mywindow7',500,500); return false;" href="../gaiji/palette.php"><img src="img/common/btn_gaiji.jpg" width="82" height="22"alt="外字検索" title="外字検索" /></a> <?php } ?>
                </td>

            </tr>
            <tr>
              <td width="160" align="left" valign="middle" nowrap="nowrap">ふりがな<?=$disp_option4?>　</td>
           	  <td width="10" align="left" valign="middle" nowrap="nowrap">：</td>
           		<td  colspan="3" align="left" valign="middle" nowrap="nowrap">

               		<input name="man_furi_lastname" <?=$disp_option1?> style="padding-top:4px; padding-bottom:4px;border-style: inset; <?=$disp_option2?> <?=$disp_option3?> " type="text" id="man_furi_lastname" value="<?=$user_row['man_furi_lastname']?>" size="30" />
            		<input name="man_furi_firstname" <?=$disp_option1?> style="padding-top:4px;border-style: inset; padding-bottom:4px; <?=$disp_option2?> <?=$disp_option3?> " type="text" id="man_furi_firstname" value="<?=$user_row['man_furi_firstname']?>" size="30" /> 様
                </td>
            </tr>
            <tr>
              <td width="160" align="left" valign="middle" nowrap="nowrap">新婦氏名<?=$disp_option4?> </td>
           	  <td width="10" align="left" valign="middle" nowrap="nowrap">：</td>
            	<td  colspan="3" align="left" valign="middle" nowrap="nowrap">
		    	<div style="width:346px;height:20px;">
		        <div id="female_lastname_img_div_id" style="width:173px;float:left;height:20px;"><?php if($user_id>0) echo getGaijis($woman_lastname_gaijis);?></div>
            <div id="female_firstname_img_div_id" style="width:173px;float:left;height:20px;"><?php if($user_id>0) echo getGaijis($woman_firstname_gaijis);?></div>
	     	<div id="female_firstname_div_id"><?php if($user_id>0 && $woman_firstname_gaijis) echo getGaijisInputEle($woman_firstname_gaijis);?></div>
        <div id="female_lastname_div_id"><?php if($user_id>0 && $woman_lastname_gaijis) echo getGaijisInputEle($woman_lastname_gaijis);?></div>
			</div>

            <input name="woman_lastname"  class="check_sjs_1" <?=$disp_option1?> style="padding-top:4px; padding-bottom:4px;border-style: inset; <?=$disp_option2?> <?=$disp_option3?> " type="text" id="woman_lastname" value="<?=$user_row['woman_lastname']?>" size="30" onclick="change_gaiji_link('woman_lastname')" onblur="set_gaiji_position()"/>
            <input name="woman_firstname"  class="check_sjs_1" <?=$disp_option1?> style="padding-top:4px; padding-bottom:4px;border-style: inset; <?=$disp_option2?> <?=$disp_option3?> " type="text" id="woman_firstname" value="<?=$user_row['woman_firstname']?>" size="30"  onclick="change_gaiji_link('woman_firstname')" onblur="set_gaiji_position()"/>
                    様　<?php if ($disp_option1=="") { ?> <a id="woman_gaiji_link_id" onclick="m_win(this.href,'mywindow7',500,500); return false;" href="../gaiji/palette.php"><img src="img/common/btn_gaiji.jpg" width="82" height="22" alt="外字検索" title="外字検索" /></a> <?php } ?>
                </td>

            </tr>
            <tr>
              <td width="160" align="left" valign="middle" nowrap="nowrap">ふりがな<?=$disp_option4?>　</td>
           	  <td width="10" align="left" valign="middle" nowrap="nowrap">：</td>
            	<td  colspan="3" align="left" valign="middle" nowrap="nowrap">
                  	<input name="woman_furi_lastname" <?=$disp_option1?> style="padding-top:4px; padding-bottom:4px;border-style: inset; <?=$disp_option2?> <?=$disp_option3?> " type="text" id="woman_furi_lastname" value="<?=$user_row['woman_furi_lastname']?>" size="30" />
                   <input name="woman_furi_firstname" <?=$disp_option1?> style="padding-top:4px; padding-bottom:4px;border-style: inset; <?=$disp_option2?> <?=$disp_option3?> " type="text" id="woman_furi_firstname" value="<?=$user_row['woman_furi_firstname']?>" size="30" />
様

                </td>
            </tr>
            <tr>
              <td width="160" align="left" valign="middle" nowrap="nowrap">披露宴会場<?=$disp_option4?> </td>
              <td width="10" align="left" valign="middle" nowrap="nowrap">：</td>
                <td align="left" valign="middle" nowrap="nowrap">
				<input type="hidden" name="current_room_id" id="current_room_id" value="<?=$user_row['room_id']?>" />
				<?php if ($disp_option1=="") { ?>
                	<select name="room_id" id="room_id" style="padding-top:4px; padding-bottom:4px;border-style:inset; width: 140px;">

                    <?php
                        if($rooms)
                        {
                            foreach($rooms as $room)
                            {
								if($room['id']==$user_row['room_id'])
								echo "<option value ='".$room['id']."' selected> ".$room_name."</option>";
								else
								 echo "<option value ='".$room['id']."'> ".$room['name']."</option>";
								// [".$room['max_rows']*$room['max_columns']*$room['max_seats']."
                            }
                        }
                    ?>
                </select>
                <?php } 
                else { 
                	echo $room_name;
                 } ?>
                </td>
                <td align="left" valign="middle" nowrap="nowrap">&nbsp;</td>
                <td nowrap="nowrap">&nbsp;</td>
            </tr>



            <tr>
              <td width="160" align="left" valign="middle" nowrap="nowrap" style="text-align:left;" >挙式日</td>
              <td width="10" align="left" valign="middle" nowrap="nowrap" style="text-align:left;" >：</td>
                <td width="60" align="left" valign="middle" nowrap="nowrap">

					<input name="marriage_day" type="text" id="marriage_day" value="<?if($user_id>0) echo $obj->date_dashes_convert($user_row['marriage_day'])?>"  size="15" readonly="readonly" style="<?=$disp_option2?> <?=$disp_option3?> background: url('img/common/icon_cal.gif') no-repeat scroll right center rgb(255, 255, 255); padding-right: 20px; padding-top:4px; padding-bottom:4px;" <?php if($disp_option2=="") echo 'class="datepicker" onclick="date_change()"'; ?> />

					<?php
					//$marriage_day_array = explode("-",$user_row['marriage_day']);
					?>
					<!--<input type="text" style="width:30px;" maxlength="4" name="marriage_year" id="marriage_year" value="<?=$marriage_day_array[0]?>">/
					<input type="text" style="width:17px;" maxlength="2" name="marriage_month" id="marriage_month" value="<?=$marriage_day_array[1]?>">/
					<input type="text" style="width:17px;" maxlength="2" name="marriage_day" id="marriage_day" value="<?=$marriage_day_array[2]?>"> yyyy/mm/dd-->
                	<!--&nbsp;<a href="javascript:void(0)" onclick="document.getElementById('marriage_day').value='';">クリア </a>-->
                </td>
                <td width="200" colspan="2" align="left" valign="middle" nowrap="nowrap">　　&nbsp;挙式時間&nbsp;　：
				<?php
				$marriage_time_array = explode(":",$user_row['marriage_day_with_time']);
				?>
				&nbsp; 
				<input type="text" <?=$disp_option1?> style="width:17px;padding-top:4px; padding-bottom:4px;border-style: inset; <?=$disp_option2?> <?=$disp_option3?> " maxlength="2" name="marriage_hour" id="marriage_hour" value="<?=$marriage_time_array[0]?>"> :
				<input type="text" <?=$disp_option1?> style="width:17px;padding-top:4px; padding-bottom:4px;border-style: inset; <?=$disp_option2?> <?=$disp_option3?> " maxlength="2" name="marriage_minute" id="marriage_minute" value="<?=$marriage_time_array[1]?>"> (24時間表記)
                <!--&nbsp;<a href="javascript:void(0)" onclick="document.getElementById('marriage_day_with_time').value='';">クリア </a>-->
                </td>
            </tr>
            <tr>
              <td width="160" align="left" valign="middle" nowrap="nowrap">挙式種類</td>

            <td width="10" align="left" valign="middle" nowrap="nowrap">：</td>
                <td align="left" valign="middle" nowrap="nowrap">
                	<?php if ($disp_option1=="") { ?>
                	<select name="religion" style="padding-top:4px; padding-bottom:4px;border-style:inset; width: 140px;" id="religion" >

                        <?php
							$religions = $obj->GetAllRowsByCondition("spssp_religion", " 1=1 order by display_order asc");
							foreach($religions as $rel)
							{
								if($user_row['religion'] == $rel['id'])
								{
									$sel = "Selected = 'Selected'";
								}
								else
								{
									$sel = '';
								}
								echo "<option value='".$rel['id']."' $sel >".$rel['title']."</option>";
							}
						?>
                    </select>
                    <?php }
                    else {
                    	echo $obj->GetSingleData("spssp_religion","title"," id=".$user_row['religion']);
                	} ?>
                </td>

                <td align="left" valign="middle" nowrap="nowrap">
                	　　&nbsp;挙式会場&nbsp;　：
					<?php
						$party_rooms_name = $obj->GetSingleData("spssp_party_room","name"," id=".$user_row['party_room_id']);

					?>
				&nbsp; 
				<input name="party_room_id" <?=$disp_option1?> style="padding-top:4px; padding-bottom:4px; width:130px;border-style: inset; <?=$disp_option2?> <?=$disp_option3?> " type="text" id="party_room_id"  class="input_text" value="<?=$party_rooms_name?>" />
                </td>
            </tr>
            <tr>
              <td width="160" align="left" valign="middle" nowrap="nowrap">新郎新婦高砂席位置</td>
              <td width="10" align="left" valign="middle" nowrap="nowrap">：</td>
                <td colspan="3" align="left" valign="middle" nowrap="nowrap">
                              <input type="radio" name="mukoyoshi" <?=$disp_option5?> value="0" 
<?php 
if($user_row['mukoyoshi']!='1'){
  echo "checked='checked'";
}
?> /> 通常
<input type="radio" name="mukoyoshi" <?=$disp_option5?> value="1" 
<?php
if($user_row['mukoyoshi']=='1'){
  echo "checked='checked'";
}
?> /> 高砂席入れ替え
                </td>
            </tr>
            <tr>
              <td width="160" align="left" valign="middle" nowrap="nowrap">ログインID</td>
              <td width="10" align="left" valign="middle" nowrap="nowrap">：</td>
                <td colspan="3" align="left" valign="middle" nowrap="nowrap">
               <input name="user_id" style="padding-top:4px; padding-bottom:4px;" type="hidden" id="user_id" value="<?=$user_row['user_id']?>" size="30" />
                <?=$user_row['user_id']?>

                </td>
            </tr>
            <tr>
              <td width="160" align="left" valign="middle" nowrap="nowrap">お客様パスワード</td>
              <td width="10" align="left" valign="middle" nowrap="nowrap">：</td>
                <td colspan="3" align="left" valign="middle" nowrap="nowrap"><!--<input name="password" style="padding-top:4px; padding-bottom:4px;" type="text" id="password" value="<?=$user_row['password']?>" size="30"  onblur="checkvalidity()"/>
				<br /><span id="password_msg">パスワードは英数字6文字以上にしてください</span>-->

                <input name="password" style="padding-top:4px; padding-bottom:4px;" type="hidden" id="password" value="<?=$user_row['password']?>" size="30"  onblur="checkvalidity()"/>

				<?=$user_row['password']?>
                </td>
            </tr>
			<tr>
			  <td width="160" align="left" valign="middle" nowrap="nowrap">お客様ＩＤ利用期限日</td>
               <td width="10" align="left" valign="middle" nowrap="nowrap">：</td>
               <?php $user_id_limit = $obj->GetSingleData("spssp_options" ,"option_value" ," option_name='user_id_limit'");

				$dateBeforeparty = $objInfo->get_date_with_supplyed_flag_difference( $user_row['party_day'] , $user_id_limit , $flag=1 );

				// UCHIDA EDIT 11/08/17 曜日表示
				$weekname = $objMsg->get_youbi_name( $dateBeforeparty );
			   ?>
			   <?php if ($user_id>0) { ?><td colspan="3" align="left" valign="middle" nowrap="nowrap"><?=$dateBeforeparty?><?=$weekname?> 披露宴日&nbsp;<?=$user_id_limit?>&nbsp;日後</td> <?php } ?>
            </tr>
			<tr>
  			<td width="160" align="left" valign="middle" nowrap="nowrap">メールアドレス</td>
              <td width="10" align="left" valign="middle" nowrap="nowrap">：</td>
                <td colspan="3" align="left" valign="middle" nowrap="nowrap"><input <?=$disp_option1?> style="padding-top:4px; padding-bottom:4px;border-style: inset; <?=$disp_option2?> <?=$disp_option3?> " name="mail" type="text" id="mail" size="30" value="<?=$user_row['mail']?>" />
				</td>
            </tr>
			<tr>
  			<td width="160" align="left" valign="middle" nowrap="nowrap">メールアドレス確認用</td>
              <td width="10" align="left" valign="middle" nowrap="nowrap">：</td>
                <!--  UCHIDA EDIT 11/08/05 確認用メールアドレスのペーストを禁止 -->
                <!--  UCHIDA EDIT 11/08/08 メッセージ変更 -->
                <td colspan="3" align="left" valign="middle" nowrap="nowrap" onpaste="alert('メールアドレス確認用は貼り付けできません');return false;"><input <?=$disp_option1?> style="padding-top:4px; padding-bottom:4px;border-style: inset; <?=$disp_option2?> <?=$disp_option3?> " name="con_mail" type="text" id="con_mail" size="30" value="<?=$user_row['mail']?>" />
				</td>
            </tr>
            <tr>
              <td width="160" align="left" valign="middle" nowrap="nowrap">お知らせをメールで受信する<?=$disp_option4?> </td>
			   <td width="10" align="left" valign="middle" nowrap="nowrap">：</td>
				<td colspan="3" align="left" valign="middle" nowrap="nowrap">

			<input type="radio" name="subcription_mail" <?=$disp_option5?> value="0" <?php if($user_row['subcription_mail']=='0'){echo "checked='checked'";}?> /> 受信する
			<input type="radio" name="subcription_mail" <?=$disp_option5?> value="1" <?php if($user_row['subcription_mail']=='1' || !isset($user_row['subcription_mail'])) {echo "checked='checked'";}?>/>  受信しない
				</td>
			</tr>
            <tr>
              <td width="160" align="left" valign="middle" nowrap="nowrap">担当</td>
              <td width="10" align="left" valign="middle" nowrap="nowrap">：</td>
                <td colspan="3" align="left" valign="middle" nowrap="nowrap">
                <?php if ($disp_option1=="") {?>
				<select name="stuff_id" style="padding-top:4px; padding-bottom:4px;border-style:inset; width: 140px;">
				<?php
				if ($user_id>0) {
					foreach($All_staffs as $staf_rows) {
				?>
					<option value="<?=$staf_rows['id']?>" <?php if($staf_rows['id']==$user_row['stuff_id']){echo "selected='selected'";}?> ><?=$staf_rows['name']?></option>
				<?php } 
				} else {
					foreach($All_staffs as $staf_rows){
				?>
					<option value="<?=$staf_rows['id']?>" <?php if($staf_rows['id']==$_SESSION['adminid'] && $_SESSION["super_user"] == false){echo "selected='selected'";}?> ><?=$staf_rows['name']?></option>
				<?php } 
				} ?>
				</select>
				<?php } 
				else {
					echo $obj->GetSingleData("spssp_admin","name"," id=".$user_row['stuff_id']);
                } ?>
				<!--<input name="stuff_id" type="text" id="stuff_id" value="<?=$staff_name?>" size="10" />-->
				</td>
            </tr>
<!--            <tr>
              <td width="192" align="left" valign="middle" nowrap="nowrap">&nbsp;</td>
                <td width="10" align="left" valign="middle" nowrap="nowrap">&nbsp;</td>
                <td colspan="3" align="left" valign="middle" nowrap="nowrap">
                 	<a href="javascript:void(0)" onclick="valid_user('<?=$user_row['id']?>' , '<?=$noUpdate?>');">
                    	<img src="img/common/btn_regist_update.jpg" border="0" width="82" height="22" /><br /><br />
                    </a>
                </td>
            </tr> -->
        </table>
<!--         </form> -->
		 </div> <!--end of  id="div_box_1"-->
        <br />
        <h2><div>席次表設定</div></h2>

		<div id="div_box_1" style="1000px;">
 <!--        <form action="insert_user_info_plan.php?user_id=<?=$user_id?>" method="post" name="user_info_plan"> -->
        <input type="hidden" id="max_rows" value="<?=$room_row['max_rows']?>" />
        <input type="hidden" id="max_columns" value="<?=$room_row['max_columns']?>" />
        <input type="hidden" id="max_seats" value="<?=$room_row['max_seats']?>" />

        <table width="850" border="0" cellspacing="10" cellpadding="0">

            <tr>
              <td width="160" align="left" valign="middle" nowrap="nowrap">披露宴会場名</td>
                <td width="10" align="left" valign="middle" nowrap="nowrap">：</td>
              <td width="200" align="left" valign="middle" nowrap="nowrap">
               	<label for="新郎 姓3"></label>  <?=$room_name?>
              </td>
                 <td colspan="2" rowspan="10" align="left" valign="top" nowrap="nowrap">


            <div class="sekiji_table_L" id="plan_preview">

  			<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0">
            	<tr>
                	<td>
			<?php

            if(empty($user_plan_row))
            {
				echo "<div align='center'>テーブル配置が表示されます</div>";
            }
            else
              {


                $layout_rows = $obj->GetAllRowsByCondition("spssp_table_layout"," plan_id=".$user_plan_row['id']);

                $num_layouts = $obj->GetNumRows("spssp_table_layout"," default_plan_id=".$user_plan_row['id']);

                $row_width = $user_plan_row['column_number'] *45;
                echo "<div class='plans' id='plan_".$user_plan_row['id']."' style='width:".$row_width."px;margin:0 auto; display:block;'>";
                $default_layout_title = $obj->GetSingleData("spssp_options" ,"option_value" ," option_name='default_layout_title'");
                if($user_plan_row['layoutname']=="null"){
                  echo "<div id='user_layoutname'  style='height:20px;display:block;text-align:center;width:100px;margin:0 auto;border:1px solid gray;'></div>";
                }elseif($user_plan_row['layoutname']!="")
                  {
                    echo "<div id='user_layoutname'  style='display:block;text-align:center;width:100px;margin:0 auto;border:1px solid gray;'>".$user_plan_row['layoutname']."</div>";
                  }
                elseif($default_layout_title!="")
                  {
                    echo "<div id='default_layout_title' style='display:block;text-align:center;width:100px;margin:0 auto;border:1px solid gray;'>".$default_layout_title."</div>";
                  }
                else
                  {
                    echo "<p id='img_default_layout_title' style='text-align:center'><img src='img/sakiji_icon/icon_takasago.gif' width='102' height='22' /></p>";
                  }

                echo "<div id='input_user_layoutname' style='display:none;'><input type='text' name='layoutname' value='".$user_plan_row['layoutname']."'></div>";
                $tblrows = $obj->getRowsByQuery("select distinct row_order from spssp_table_layout where user_id= ".(int)$user_id);

                foreach($tblrows as $tblrow)
                  {
                    $ralign = $obj->GetSingleData("spssp_table_layout", "align"," row_order=".$tblrow['row_order']." and user_id=".(int)$user_id." limit 1");
                    $num_first = $obj->GetSingleData("spssp_table_layout", "column_order "," display=1 and user_id=".$user_id." and row_order=".$tblrow['row_order']." order by column_order limit 1");
                    $num_last = $obj->GetSingleData("spssp_table_layout", "column_order "," display=1 and user_id=".$user_id." and row_order=".$tblrow['row_order']." order by column_order desc limit 1");
                    $num_max = $obj->GetSingleData("spssp_table_layout", "column_order "," user_id=".$user_id." and row_order=".$tblrow['row_order']." order by column_order desc limit 1");
                    $num_none = $num_max-$num_last+$num_first-1;
                    if($ralign == 'L' || $ralign == "N")
                      {
                        $styles = 'float:left;';
                      }
                    else if($ralign=='R')
                      {
                        $styles = 'float:right;';
                      }
                    else if($num_none>0)
                      {
                        $width = $row_width - ($num_none*41);
                        $styles = "width:".$width."px;margin: 0 auto;";
                      }
                    else
                      {
                        $styles = "";
                      }

                    echo "<div class='rows' style='width:100%;float; left; clear:both;'><div style='".$styles."'>";
                    $tables = $obj->getRowsByQuery("select * from spssp_table_layout where user_id= ".$user_id." and row_order=".$tblrow['row_order']);

                    foreach($tables as $table)
                      {
                        $new_name_row = $obj->GetSingleRow("spssp_user_table", "user_id = ".$user_id." and default_table_id=".$table['id']);

                        if(isset($new_name_row) && $new_name_row['id'] !='')
                          {
                            $tblname_row = $obj->GetSingleRow("spssp_tables_name","id=".$new_name_row['table_name_id']);
                            $tblname = $tblname_row['name'];
                          }
                        else
                          {
                            //$tblname = $table['name'];
                          }

                        if($table["display"] == 1){
                          $disp = 'display:block;';
                        }else if(($num_first <= $table["column_order"] && $table["column_order"]<=$num_last) || $ralign == "N" ){
                          $disp = "visibility:hidden";
                        }else{
                          $disp = "display:none";
                        }
                        echo "<div class='tables' style='".$disp."'><p>".mb_substr ($tblname, 0,1,'UTF-8')."</p></div>";
                      }
                    echo "</div></div>";
                  }
                echo "</div>";
              }
?>
				</td>
              </tr>
          </table>
　　　　　<?php
			if(!empty($user_plan_row))
            {

			?>
			<div>
			<?php
				if ($noUpdate == false && $disp_option1 =="") { ?>
				<a href="javascript:void(0);" onclick='check_change("set_table_layout_edit.php?plan_id=<?=$user_plan_row['id']?>&user_id=<?=$user_id?>&stuff_id=<?=$stuff_id?>");'>
                  <img src='img/common/btn_taku_edit.gif' boredr='0' height='17'>
                </a>
              <?php } ?>
	    </div>
			<?php

			}
			?>
        </div>
            </td>
            </tr>
            <tr>
              <td width="160" align="left" valign="middle" nowrap="nowrap">最大卓数</td>
            <td width="10" align="left" valign="middle" nowrap="nowrap">：</td>
                <td align="left" valign="middle" nowrap="nowrap">
                	横 <?php if($user_plan_row['column_number']){echo $user_plan_row['column_number'];}else{echo $room_row['max_columns'];}?>
                    <input name="column_number" type="hidden" id="column_number" value="<?php if($user_plan_row['column_number']){echo $user_plan_row['column_number'];}else{echo $room_row['max_columns'];}?>" size="1" />
                	列×縦 <?php if($user_plan_row['row_number']){echo $user_plan_row['row_number'];}else{echo $room_row['max_rows'];}?>
                    <input name="row_number" type="hidden" id="row_number" value="<?php if($user_plan_row['row_number']){echo $user_plan_row['row_number'];}else{echo $room_row['max_rows'];}?>" size="1" />
                      段
              </td>
            </tr>
            <tr>
              <td width="160" align="left" valign="middle" nowrap="nowrap">一卓人数</td>
                <td width="10" align="left" valign="middle" nowrap="nowrap">：</td>
              <td width="200" align="left" valign="middle" nowrap="nowrap"><?php if($user_plan_row['seat_number']){echo $user_plan_row['seat_number'];}else{echo $room_row['max_seats'];}?>
              <input name="seat_number" type="hidden" id="seat_number" value="<?php if($user_plan_row['seat_number']){echo $user_plan_row['seat_number'];}else{echo $room_row['max_seats'];}?>" size="1" />
              名まで</td>
            </tr>

  <tr>
    <td width="160" align="left" valign="middle" nowrap="nowrap">卓名変更<?=$disp_option4?> </td>
            <td width="10" align="left" valign="middle" nowrap="nowrap">：</td>
            <td align="left" valign="middle" nowrap="nowrap">
                <?php
					$default_raname_table_view = $obj->GetSingleData("spssp_options" ,"option_value" ," option_name='rename_table_view'");
					$_def_view = "";
					if ($default_raname_table_view == "0") $_def_view = "disabled='disabled'";
					if ($user_plan_row['rename_table'] != "") {
				?>
					<input name="rename_table" type="radio" id="radio1" <?=$disp_option5?> value="1"  <?php if($user_plan_row['rename_table'] == "1") {echo "checked='checked'";}?> <?=$_def_view ?> />   可
					<input type="radio" name="rename_table" id="radio0" <?=$disp_option5?> value="0"  <?php if($user_plan_row['rename_table'] == "0") {echo "checked='checked'";}?> <?=$_def_view ?> /> 不可
			  <?php } else {?>
					<input name="rename_table" type="radio" id="radio1" <?=$disp_option5?> value="1"  <?php if($_def_view=="") {echo "checked='checked'";}?> <?=$_def_view ?> />   可
					<input type="radio" name="rename_table" id="radio0" <?=$disp_option5?> value="0" <?php if($_def_view!="") {echo "checked='checked'";}?> <?=$_def_view ?> /> 不可
			  <?php }?>
            </td>
  </tr>
            <tr>
              <td width="160" align="left" valign="middle" nowrap="nowrap">商品名<?=$disp_option4?> </td>
            <td width="10" align="left" valign="middle" nowrap="nowrap">：</td>
                <td align="left" valign="middle" nowrap="nowrap">
               	  <input name="product_name" type="text" <?=$disp_option1?> style="border-style:inset; <?=$disp_option2?> <?=$disp_option3?> " id="product_name" value="<?=$user_plan_row['product_name']?>" size="18" />
              </td>
            </tr>
      <tr>
        <td width="160" align="left" valign="middle" nowrap="nowrap">商品区分<?=$disp_option4?> </td>
        <td width="10" align="left" valign="middle" nowrap="nowrap">：</td>
                <td align="left" valign="middle" nowrap="nowrap">
				<?php if ($disp_option1=="") { ?>
				<select name="dowload_options" id="dowload_options" style="border-style: inset; width: 140px;" onchange="dowload_options_change();">
                    	<option value="1" <?php if($user_plan_row['dowload_options'] == 1) echo "selected='selected'";?>>席次表</option>
                        <option value="2" <?php if($user_plan_row['dowload_options'] == 2) echo "selected='selected'";?>>席札</option>
                        <option value="3" <?php if($user_plan_row['dowload_options'] == 3) echo "selected='selected'";?>>席次表・席札</option>
                </select>
                <?php } 
                else {
                	switch ($user_plan_row['dowload_options']) {
                		case 1:
                			echo "席次表";
                			break;
                		case 2:
                			echo "席札";
                			break;
                	    case 3:
                	    	echo "席次表・席札";
                			break;
                	}
                }?>
            </td>

          </tr>
            <tr>
              <td width="160" align="left" valign="middle" nowrap="nowrap">サイズ<?=$disp_option4?>／タイプ<?=$disp_option4?> </td>

            <td width="40" align="left" valign="middle" nowrap="nowrap">：</td>
                <td align="left" valign="middle" nowrap="nowrap">
                	<?php if ($disp_option1=="") { ?>
                	<select name="print_size" id="print_size" style="width:45px;border-style:inset;">
					<option value="1" <?php if($user_plan_row['print_size'] == 1) echo "selected='selected'";?>>A3</option>
					<?php if ($user_plan_row['dowload_options'] != 2) { ?>
					<option value="2" <?php if($user_plan_row['print_size'] == 2 || $user_plan_row['dowload_options'] == 0) echo "selected='selected'";?>>B4</option>
					<?php } ?>
				</select>
				<?php }
				else  {
					if ($user_plan_row['print_size'] == 1) echo "A3"; else echo "B4";
				}?>
				<?php if ($disp_option1=="") { ?>
				<select name="print_type" id="print_type" style="width:45px;border-style:inset;">
					<option value="1" <?php if($user_plan_row['print_type'] == 1) echo "selected='selected'";?>>横</option>
					<option value="2" <?php if($user_plan_row['print_type'] == 2) echo "selected='selected'";?>>縦</option>
				</select>
				<?php }
				else  {
					echo " ／ ";
					if ($user_plan_row['print_type'] == 1) echo "横"; else echo "縦";
				}?>
              </td>
            </tr>
            <tr>
              <td width="160" align="left" valign="middle" nowrap="nowrap">本発注締切日<?=$disp_option4?> </td>
            <td width="10" align="left" valign="middle" nowrap="nowrap">：</td>
                <td align="left" valign="middle" nowrap="nowrap">
                	<?php
                	if ($user_id>0) {
						$confirm_day_num = $user_row['confirm_day_num'];
						$dateBeforeparty = $objInfo->get_date_with_supplyed_flag_difference( $user_row['party_day'] , $confirm_day_num , $flag=2 );
			            $weekname = $objMsg->get_youbi_name($dateBeforeparty);
						echo $dateBeforeparty.$weekname." 披露宴日 ";
                	}
                	else {
                		$confirm_day_num = $obj->GetSingleData("spssp_options" ,"option_value" ," option_name='confirm_day_num'");
                	}
					?>
					<?php if ($user_id>0) { ?>
						<input type="text" name="confirm_day_num" id="confirm_day_num" <?=$disp_option1?> style="width:15px; padding:3px;border-style: inset; <?=$disp_option2?> <?=$disp_option3?> " maxlength="2" value="<?=$confirm_day_num?>" /> 日前
					<?php } else {
						$confirm_day_num    = $obj->GetSingleData("spssp_options" ,"option_value" ," option_name='confirm_day_num'");
					?>
						披露宴日&nbsp;
						<input type="text" name="confirm_day_num" id="confirm_day_num" style="width:15px; padding:3px;border-style: inset;" maxlength="2" value="<?=$confirm_day_num?>" /> 日前
					<?php } ?>
			  <input type="hidden" value="<?=$user_row['party_day']?>" name="party_day_for_confirm" />	</td>
            </tr>
            <tr>
              <td width="160" align="left" valign="middle" nowrap="nowrap">席次表編集利用制限日<?=$disp_option4?> </td>
            <td width="10" align="left" valign="middle" nowrap="nowrap">：</td>
                <td align="left" valign="middle" nowrap="nowrap">

				<?php
				if ($user_id>0) {
					$limitation_ranking = $user_row['limitation_ranking'];
					$dateBeforeparty = $objInfo->get_date_with_supplyed_flag_difference( $user_row['party_day'] , $limitation_ranking , $flag=2 );
					$weekname = $objMsg->get_youbi_name( $dateBeforeparty );
				}
				else {
					$limitation_ranking = $obj->GetSingleData("spssp_options" ,"option_value" ," option_name='limitation_ranking'");
				}
				if($user_id>0) {
					echo $dateBeforeparty.$weekname." 披露宴日 ";
				?>
					<input type="text" name="limitation_ranking" id="limitation_ranking" <?=$disp_option1?> style="width:15px; padding:3px;border-style: inset; <?=$disp_option2?> <?=$disp_option3?> " maxlength="2" value="<?=$limitation_ranking?>" /> 日前
				<?php } 
				else { ?>
					披露宴日&nbsp;
					<input type="text" name="limitation_ranking" id="limitation_ranking" style="width:15px; padding:3px;border-style: inset;" maxlength="2" value="<?=$limitation_ranking?>" /> 日前
				<?php } ?>
				
				</td>

            </tr>

            <tr>
              <td width="160" align="left" valign="middle" nowrap="nowrap">印刷会社名<?=$disp_option4?> </td>

            <td width="10" align="left" valign="middle" nowrap="nowrap">：</td>
                <td align="left" valign="middle" nowrap="nowrap">
                	<?php
					$sql_company = "select * from spssp_printing_comapny ORDER BY display_order";
					$company_results = $obj->getRowsByQuery($sql_company);
					?>
					<?php if ($disp_option1=="") { ?>
					<select name="print_company" id="print_company" style="border-style: inset; width: 140px;">
						<?php foreach($company_results as $company){?>
						<option value="<?=$company['id']?>" <?php if($user_plan_row['print_company']==$company['id']){echo "selected='selected'";}?> ><?=$company['company_name']?></option>
						<?php }?>
				    </select>
					<?php } 
					else {
						echo $obj->GetSingleData("spssp_printing_comapny","company_name"," id=".$user_plan_row['print_company']);
					}?>
              </td>
            </tr>
        </table>

<!--  <br />
      <p class="txt3">
        	<?php
            //if(empty($user_plan_row))
            //{
			?>
             <a href="javascript:void(0);" onclick="valid_plan('<?=$noUpdate?>');"> <img src="img/common/btn_regist_update.jpg" border="0" /> </a>
			<?php
			//}
			?>
        </p> -->
<!--         </form> -->
		</div> <!--end of  id="div_box_2"-->
        <br />
        <h2><div>引出物設定</div></h2>
            <?php
            $gift_groups = $obj->GetAllRowsByCondition("spssp_gift_group","user_id=".$user_id." order by id ASC");
            $gifts = $obj->GetAllRowsByCondition("spssp_gift","user_id=".$user_id." order by id ASC");
			$gift_criteria = $obj->GetSingleRow("spssp_gift_criteria", " id=1");
			$count_gift = (int)$gift_criteria['num_gift_items'];
			$count_group = (int)$gift_criteria['num_gift_groups'];
			?>

        <div style="width:1000px; float:left;" id="div_box_3">
        	<div style="width:40%; float:left; ">
            	<div style="width:100px; float:left; ">
                	<p> 引出物商品： <p>
                </div>
                <div style="width:300px; float:left;">
<!-- 			<form action="user_info.php?user_id=<?=$user_id?>" method="post"  name="editUserGiftItemsForm"> -->
	   		<input type="hidden" name="editUserGiftItemsUpdate" value="editUserGiftItemsUpdate" style="border-style: inset;">
	   		<table width="100%" border="0" cellspacing="1" cellpadding="0">
	   <?php
	   if($user_id>0) {
		  	$yy = 1;
			foreach($gifts as $gift_name)
			{
				echo "<tr><td style='text-align:left;'><input type='text' id='item".$yy.$disp_option1."' style='padding-top:3px; padding-buttom:3px;border-style:inset;".$disp_option2.$disp_option3."' name='name_gift".$yy."' value='".$gift_name['name']."' size='30'>&nbsp;&nbsp;&nbsp;";
				echo "<input type='hidden' name='gift_fieldId".$yy."' value='".$gift_name['id']."'></td></tr>";
				$yy++;
			}
	   }
	   else {
		   	for ($yy=1; $yy<=$count_gift; $yy++) {
				echo "<tr><td style='text-align:left;'><input type='text' id='item".$yy."' style='padding-top:3px; padding-buttom:3px;border-style:inset;' name='name_gift".$yy."' value='' size='30'>&nbsp;&nbsp;&nbsp;";
				echo "<input type='hidden' name='gift_fieldId".$yy."' value='".$gift_name['id']."'></td></tr>";
		   	}
	   	}
	  ?>
	  </table>
	  <br />
<!--	  <div>
 		  <input type="button" name="editUserGiftItemsUpdateButton" value="保存" onclick="checkGiftForm('<?=$yy?>', '<?=$noUpdate?>')"></div><br /> -->
<!-- 	  </form> -->
<!--GIFT ITEM BOX END-->

                </div>
            </div>

		 <div style="width:100px; float:left; ">
		 引出物グループ：
		 </div>

	       <div style="width:300px; float:left; ">
		   <!--GIFT GROUP BOX START-->
		        <p>
<!-- 				<form action="user_info.php?user_id=<?=$user_id?>" method="post" name="editUserGiftGroupsForm"> -->
				  <input type="hidden" name="editUserGiftGroupsUpdate" value="editUserGiftGroupsUpdate">
				  <?php
				  if($user_id>0) {
					  $xx = 1;
				  	  foreach($gift_groups as $row)
					  {
						  echo "<div style='margin-left:15px;'><input type='text' id='name_group".$xx."' ".$ro.$disp_option1." name='name_group".$xx."' maxlength='4' size='6' style='border-style:inset; $disp_option2 $disp_option3' value='".$row['name']."'>";
						  echo "<input type='hidden' name='group_fieldId".$xx."' value='".$row['id']."'></div>";
						  $xx++;
					  }
		  			  for (; $xx <=$count_group; $xx++) {
						echo "<div style='margin-left:15px;'><input type='text' id='name_group".$xx."' ".$ro.$disp_option1." name='name_group".$xx."' maxlength='4' size='6' style='border-style:inset; $disp_option2 $disp_option3' value=''>";
						echo "<input type='hidden' name='group_fieldId".$xx."' value=''></div>";
					  }
					  $count_gift=$xx-1;
				    }
				    else {
					  	$group_sql ="SELECT * FROM spssp_gift_group_default  ORDER BY id asc ;";
					  	$data_rows = $obj->getRowsByQuery($group_sql);
						$xx = 1;
						foreach($data_rows as $row) {
							echo "<div style='margin-left:15px;'><input type='text' id='name_group".$xx."' ".$ro." name='name_group".$xx."' maxlength='4' size='6' style='border-style:inset;' value='".$row['name']."'>";
							echo "<input type='hidden' name='group_fieldId".$xx."' value=''></div>";
							$xx++;
						}
/*
						for (; $xx <=7; $xx++) {
							echo "<div style='margin-left:15px;'><input type='text' id='name_group".$xx."' ".$ro." name='name_group".$xx."' maxlength='4' size='6' value=''>";
							echo "<input type='hidden' name='group_fieldId".$xx."' value=''></div>";
						}
*/
					   	$count_gift=$xx-1;
				  }
				  ?>
				   <br />
<!-- 				   <input type="button" style="margin-left:15px;" name="editUserGiftGroupsUpdateButton" value="保存" onclick="checkGroupForm('<?=$xx?>' ,'<?=$noUpdate?>');"> -->
<!-- 				 </form> -->
		         </p>
			<!--GIFT GROUP BOX END-->
					<br />
            </div>
	       <div float:left; valign:top" >
			<p>
            <?php
            if ($user_id>0) {
            	$order_deadline = $user_row['order_deadline'];
            }
            else {
            	$order_deadline = $gift_criteria['order_deadline'];
            }
			$dateBeforeparty = $objInfo->get_date_with_supplyed_flag_difference( $user_row['party_day'] , $order_deadline , $flag=2 );
			?>
				締切予定日<?=$disp_option4?>：&nbsp;
				<?php if($user_id>0) {
				    $weekname = $objMsg->get_youbi_name( $dateBeforeparty );
				?> 
				<?=$dateBeforeparty?><?=$weekname?>&nbsp披露宴日&nbsp;
				<input type="text" name="order_deadline" id="order_deadline"  <?=$disp_option1?> style="width:15px; padding:3px;border-style: inset; <?=$disp_option2?> <?=$disp_option3?> " maxlength="2" value="<?=$order_deadline?>" /> 日前
				<?php } 
				else { ?>
				披露宴日&nbsp;
				<input type="text" name="order_deadline" id="order_deadline" style="width:15px; padding:3px;border-style: inset;" maxlength="2" value="<?=$order_deadline?>" /> 日前
				<?php } ?>
            </p>
            <br /><br />
		</div>

        </div>

        <br />

        <div>
        	<p><h2>料理設定（子供料理）</h2></p>
            <?php
            	$menu_groups = $obj->GetAllRowsByCondition("spssp_menu_group","user_id=".$user_id);
				$num_groups = count($menu_groups);
				$menu_criteria_data_row = $obj->GetAllRow("spssp_menu_criteria");
				$count_child = (int)$menu_criteria_data_row[0]['num_menu_groups'];
				?>
        </div>
       <div id="div_box_4" style="width:500px;">

<!-- 				<form action="user_info.php?user_id=<?=$user_id?>" method="post" name="editUserMenuGroupsForm"> -->
		  			<input type="hidden" name="editUserMenuGroupsUpdate" value="editUserMenuGroupsUpdate"  style="border-style:inset;">
					 <table width="100%" border="0" cellspacing="10" cellpadding="0">
        	<?php if($num_groups >0) {
					$i=1;
					echo "<tr>";
					foreach($menu_groups as $mg)
					{
						echo "<td>子供料理 $i ：<input type='text'".$disp_option1."style='border-style:inset; $disp_option2 $disp_option3' name='menu_child".$i."' id='menu_child".$i."' value='".$mg['name']."' size='20'></td>";
						echo "<input type='hidden' style='border-style:inset;' name='menu_child_id".$i."' id='menu_child_id".$i."' value='".$mg['id']."'>";
						$i++;
					}
					echo "</tr>";
				}
				else {
					echo "<tr>";
					for ($i=1; $i<=$count_child; $i++) {
						echo "<td>子供料理 $i ：<input type='text'".$disp_option1."style='border-style:inset; $disp_option2 $disp_option3' name='menu_child".$i."' id='menu_child".$i."' value='' size='20'></td>";
						echo "<input type='hidden' style='border-style:inset;' name='menu_child_id".$i."' id='menu_child_id".$i."' value=''>";
					}
					echo "</tr>";
				}
				?>
<!-- 					 <tr><td>
					 <input type="button" style="margin-left:15px;" name="editUserMenuGroupsUpdateButton" value="保存" onclick="checkMenuGroupForm('<?=$i?>' ,'<?=$noUpdate?>');">
					 </td> </tr> -->
            </table>
			<br /><br />
            <div colspan="4" align="left" valign="middle" nowrap="nowrap">
            <?php if ($disp_option1=="") { ?>
             <a href="javascript:void(0)" onclick="valid_user('<?=$user_id?>','<?=$noUpdate?>','<?=$count_gift?>','<?=$count_group?>','<?=$count_child?>');">
                <img src="img/common/btn_regist_update.jpg" border="0" />
             </a>
             <?php } ?>
            <?php if($user_id>0) { ?>
            		<br />
            		<br />
                 	<a href="user_log.php?user_id=<?=$user_id?>&stuff_id=<?=$stuff_id?>"><img src="img/common/btn_access.jpg" width="173" height="23" /></a>　
                    <a href="change_log.php?user_id=<?=$user_id?>&stuff_id=<?=$stuff_id?>"><img src="img/common/btn_data.jpg" width="173" height="23" /></a></div>
            <?php } ?>
           </div>
		   </form> <!--end of  id="div_box_3"-->
          </div><!--END OF id="div_box_3" -->

        <br />
        <br />
        <br />

    </div>
</div>
<?php
	include_once('inc/left_nav.inc.php');
	include_once("inc/new.footer.inc.php");
?>
