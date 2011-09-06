<?php
	require_once("inc/include_class_files.php");
	include_once("inc/checklogin.inc.php");

	$obj = new DBO();
	$objInfo = new InformationClass();
	$objMsg = new MessageClass(); // UCHIDA EDIT 11/08/18 曜日表示のため

	if(isset($_POST['ajax']) && $_POST['ajax'] != '')
	{
		$pid = (int)$_POST['pid'];
		$prooms = $obj->GetAllRowsByCondition("spssp_party_room"," religion_id= $pid order by name asc");
		if(!empty($prooms))
		{
			foreach($prooms as $pr)
			{
				echo "<option value ='".$pr['id']."'> ".$pr['name']." </option>";
			}
		}
		exit;
	}

	include_once("inc/new.header.inc.php");

	$get = $obj->protectXSS($_GET);
	$user_id = (int)$get['user_id'];
	$stuff_id = (int)$get['stuff_id'];
	$user_row = $obj->GetSingleRow("spssp_user"," id= $user_id");

	$query_string = "SELECT * FROM spssp_gaizi_detail_for_user WHERE gu_id = $user_id";
	$man_firstname_gaijis = $obj->getRowsByQuery($query_string." and gu_trgt_type=0");
	$man_lastname_gaijis = $obj->getRowsByQuery($query_string." and gu_trgt_type=1");
	$woman_firstname_gaijis = $obj->getRowsByQuery($query_string." and gu_trgt_type=2");
	$woman_lastname_gaijis = $obj->getRowsByQuery($query_string." and gu_trgt_type=3");

	function getGaijis($gaiji_objs){
		 $returnImage = "";
		 for($i=0;$i<count($gaiji_objs);++$i){
			$returnImage .= "<image src='../gaiji/upload/img_ans/".$gaiji_objs[$i]["gu_char_img"]."' width='20' height='20'>";
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
		  $html .= "<input type='hidden' name='".$value."_gaiji_gsid[]' value='".$gsid."'>";
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

// UCHIDA EDIT 11/08/03 内容をスタッフ画面に合わせて調整
//	$All_staffs = $obj->GetAllRowsByCondition("spssp_admin"," 1=1 ");
	$All_staffs = $obj->GetAllRowsByCondition("spssp_admin"," `permission` != '111' ORDER BY `permission` DESC");

	$room_name = $obj->GetSingleData("spssp_user", "room_name"," id=".$user_id);

	if($room_name=="")
	$room_name = $obj->GetSingleData("spssp_room", "name"," id=".$user_row['room_id']);
	//print_r($user_row);
	$room_plan_rows = $obj->GetAllRowsByCondition("spssp_default_plan"," room_id=".$user_row['room_id']);

	$user_plan_row = $obj->GetSingleRow("spssp_plan"," user_id= $user_id");
	$user_plan_row_count = $obj->GetRowCount("spssp_plan"," user_id= $user_id");


	$room_row = $obj->GetSingleRow("spssp_room"," id= ".$user_row['room_id']);
/*
	if($_SESSION['user_type'] == 222)
	{
		$data = $obj->GetAllRowsByCondition("spssp_user"," stuff_id=".(int)$_SESSION['adminid']);
		foreach($data as $dt)
		{
			$staff_users[] = $dt['id'];
		}

		if(!empty($staff_users))
		{
			if(in_array($user_id,$staff_users))
			{
				$registration_onclick = "valid_user(".$user_id.");";
				$plan_onclick = "valid_plan();";
			}
			else
			{
				$registration_onclick="alert_staff();";
				$plan_onclick = "alert_staff();";
			}
		}
		else
		{
			$registration_onclick = "alert_staff();";
			$plan_onclick = "alert_staff();";
		}
	}
	else
	{
		$registration_onclick = "valid_user(".$user_id.");";
		$plan_onclick = "valid_plan();";
	}
*/


	//USER GIFT GROUP NAME UPDaTE STart

	if($_POST['editUserGiftGroupsUpdate']=='editUserGiftGroupsUpdate')
	{
		unset($_POST['editUserGiftGroupsUpdate']);
		$number = count($_POST);
		$number = ($number/2);
		for($i=1;$i<=$number;$i++)
		{
			$array['name'] = $_POST['name'.$i];
			$obj->UpdateData("spssp_gift_group", $array," user_id=".$user_id." and id=".(int)$_POST['fieldId'.$i]);
		}

	}
	//USER GIFT GROUP NAME UPDaTE END
	//USER GIFT ITEM NAME UPDaTE STart
	if($_POST['editUserGiftItemsUpdate']=='editUserGiftItemsUpdate')
	{
		unset($_POST['editUserGiftItemsUpdate']);
		$number = count($_POST);
		$number = ($number/2);
		for($i=1;$i<=$number;$i++)
		{
			$array['name'] = $_POST['name'.$i];
			$obj->UpdateData("spssp_gift", $array," user_id=".$user_id." and id=".(int)$_POST['fieldId'.$i]);
		}

	}
	//USER GIFT ITEM NAME UPDaTE END
	//USER MENU ITEM NAME UPDaTE STart
	if($_POST['editUserMenuGroupsUpdate']=='editUserMenuGroupsUpdate')
	{

		unset($_POST['editUserMenuGroupsUpdate']);
		$number = count($_POST);
		$number = ($number/2);
		for($i=1;$i<=$number;$i++)
		{
			$array['name'] = $_POST['menu'.$i];
			$obj->UpdateData("spssp_menu_group", $array," user_id=".$user_id." and id=".(int)$_POST['menuId'.$i]);
		}

	}
	//USER MENU ITEM NAME UPDaTE END
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
          div_image:"male_lastname_img_div_id"});
    setDeleteGaiji({
      input_id:"man_firstname",
          form_name:"male_first_gaiji_",
          div_image:"male_firstname_img_div_id"});
    setDeleteGaiji({
      input_id:"woman_lastname",
          form_name:"female_last_gaiji_",
          div_image:"female_lastname_img_div_id"});
    setDeleteGaiji({
      input_id:"woman_firstname",
          form_name:"female_first_gaiji_",
          div_image:"female_firstname_img_div_id"});
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
function change_plan(planid)
{
	if(planid > 0)
	{
		$j(".plans").hide();
		$j("#plan_"+planid).fadeIn(500);
		$j.post('ajax/plan_preview_user_info.php',{'id':planid},function(data){
			var plan_arr = data.split(",");
			$j("#row_number").val(plan_arr[0]);
			$j("#column_number").val(plan_arr[1]);
			$j("#seat_number").val(plan_arr[2]);

			$j("#row_number").attr("readonly","readonly");
			$j("#column_number").attr("readonly","readonly");
			$j("#seat_number").attr("readonly","readonly");

		});

	}
	else
	{
		$j("#row_number").removeAttr("readonly");
		$j("#column_number").removeAttr("readonly");
		$j("#seat_number").removeAttr("readonly");

		$j("#row_number").val("");
		$j("#column_number").val("");
		$j("#seat_number").val("");

		$j(".plans").fadeOut(100);


	}
}


function valid_plan(noUpdate)
{

	if (noUpdate == true) {
		alert("披露宴日が過ぎてきるため、登録・更新ができません");
		return false;
	}

	// UCHIDA EDIT 11/08/05 入力確認。上書きをカバー
	var product_name = $j("#product_name").val();


// UCHIDA EDIT 11/08/08 選択状態確認方法を変更
//	var print_type = $("print_type").options[$("print_type").selectedIndex].text;
	var print_size = $("print_size").selectedIndex;
	var print_type = $("print_type").selectedIndex;
	var print_company = $("print_company").selectedIndex;
	var dowload_options = $("dowload_options").selectedIndex;

/* 未入力を承認
	if (print_company == 0) {
		alert("印刷会社名を選択してください");
		$j("#print_company").focus();
		return false;
	}
	if (product_name == "") {
		alert("商品名を入力してください");
		$j("#product_name").focus();
		return false;
	}

	if (print_size == 0) {
		alert("用紙サイズを選択してください");
		$j("#print_size").focus();
		return false;
	}
	if (print_type == 0) {
		alert("用紙タイプを選択してください");
		$j("#print_type").focus();
		return false;
	}
	*/
	document.user_info_plan.submit();
/*
	var row_num = $j("#row_number").val();
	var col_num = $j("#column_number").val();
	var seat_num = $j("#seat_number").val();
	var name = $j("#name").val();

	var max_rows=$j("#max_rows").val();
	var max_columns=$j("#max_columns").val();
	var max_seats=$j("#max_seats").val();

	if(row_num == '')
	{
		alert("縦が未入力です");
		$j("#row_number").focus();
		return false;
	}
	if(!parseInt(row_num))
	{
		alert("数字のみの入力はできません。");
		$j("#row_number").focus();
		return false;
	}
	if(parseInt(row_num) > parseInt(max_rows))
	{
		alert("卓レイアウトは、披露宴会場設定の数値以下にしてください。 : "+max_rows);
		$j("#row_number").focus();
		return false;
	}


	if(col_num == '')
	{
		alert("列が未入力です");
		$j("#column_number").focus();
		return false;
	}
	if(!parseInt(col_num))
	{
		alert("数字のみの入力はできません。");
		$j("#column_number").focus();
		return false;
	}
	if(parseInt(col_num) > parseInt(max_columns))
	{
		alert("最大値は列の番号が許可されて: "+max_columns);
		$j("#column_number").focus();
		return false;
	}


	if(seat_num == '')
	{
		alert("一卓人数を入力してください。");
		$j("#seat_number").focus();
		return false;
	}
	if(!parseInt(seat_num))
	{
		alert("数字のみの入力はできません。");
		$j("#seat_number").focus();
		return false;
	}
	if(parseInt(seat_num) > parseInt(max_seats))
	{
		alert("会場の一卓人数 縦の値を超えています。: "+max_seats);
		$j("#seat_number").focus();
		return false;
	}


	if(name =='')
	{
		alert("プラン名を入力してください。");
		$j("#name").focus();
		return false;
	}
	document.user_info_plan.submit();
*/
}

/*function load_party_room(id)
{
	$j.post('user_info.php',{'ajax':'ajax','pid':id},function (data){
		$j("#party_room_id").fadeOut(100);
		$j("#party_room_id").html(data);
		$j("#party_room_id").fadeIn(300);
	});
}*/
function checkGroupForm(x, noUpdate)
{	//alert(x);

	if (noUpdate == true) {
		alert("披露宴日が過ぎてきるため、登録・更新ができません");
		return false;
	}

	var error =1;
	for(var y=1;y<x;y++)
	{
		//alert(y);
		if($j("#name"+y).val()!="")
		{

			var error =0;
		}
	}

	if(error!=1)
	{
		document.editUserGiftGroupsForm.submit();
	}
	else
	{
		alert("引出物グループ名を入力してください");
	}
}
function checkGiftForm(x, noUpdate)
{	//alert(x);

	if (noUpdate == true) {
		alert("披露宴日が過ぎてきるため、登録・更新ができません");
		return false;
	}

	// UCHIDA EDIT 11/08/05 引出物商品名は無入力でも許可　上書きのカバー
	document.editUserGiftItemsForm.submit();
/*
	var error =1;
	for(var y=1;y<x;y++)
	{
		if($j("#item"+y).val()!="")
		{

			var error =0;
		}
	}
	if(error!=1)
	{
		document.editUserGiftItemsForm.submit();
	}
	else
	{
		alert("引出物商品名を入力してください");
	}
*/
}
function checkMenuGroupForm(x, noUpdate)
{	//alert(x);

	if (noUpdate == true) {
		alert("披露宴日が過ぎてきるため、登録・更新ができません");
		return false;
	}

	for(var y=0;y<x;y++)
	{
		if($j("#menu"+y).val()=="")
		{
			alert("引出物グループ名を入力してください");
			var error =1;
		}
	}
	if(error!=1)
	{
		document.editUserMenuGroupsForm.submit();
	}
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

function appendHiddenValue(type,img,gid,gsid){
	var typeArray = ["male_first","male_last","female_first","female_last"];
	var value = typeArray[type];
	$j("#form_hidden_value").append("<input type='hidden' name='"+value+"_gaiji_img[]' value='"+img+"'>");
	$j("#form_hidden_value").append("<input type='hidden' name='"+value+"_gaiji_gid[]' value='"+gid+"'>");
	$j("#form_hidden_value").append("<input type='hidden' name='"+value+"_gaiji_gsid[]' value='"+gsid+"'>");
}

function get_gaiji_value(from,img,gid,gsid)
{
  if(img==""){
    alert("外字が正しく選択されていません。");
    return;
  }
	if(from=="man_firstname")
	{
		var man_firstname = $j("#man_firstname").val();
		appendHiddenValue(0,img,gid,gsid);
		$j("#male_firstname_img_div_id").append("<img src='../gaiji/upload/img_ans/"+img+"' wight='20' height='20'>");

		$j("#man_firstname").attr("value", man_firstname+"＊");
	}
	if(from=="man_lastname")
	{
		var man_lastname = $j("#man_lastname").val();

		appendHiddenValue(1,img,gid,gsid);

		$j("#male_lastname_img_div_id").append("<img src='../gaiji/upload/img_ans/"+img+"' wight='20' height='20'>");

		$j("#man_lastname").attr("value", man_lastname+"＊");
	}
	if(from=="woman_firstname")
	{
		var woman_firstname = $j("#woman_firstname").val();

		appendHiddenValue(2,img,gid,gsid);

		$j("#female_firstname_img_div_id").append("<img src='../gaiji/upload/img_ans/"+img+"' wight='20' height='20'>");

		$j("#woman_firstname").attr("value", woman_firstname+"＊");
	}
	if(from=="woman_lastname")
	{
		var woman_lastname = $j("#woman_lastname").val();

		appendHiddenValue(3,img,gid,gsid);

		$j("#female_lastname_img_div_id").append("<img src='../gaiji/upload/img_ans/"+img+"' wight='20' height='20'>");

		$j("#woman_lastname").attr("value", woman_lastname+"＊");

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

function valid_user(user_id, noUpdate) // registration_validation.jsから移動 11/08/26
{
	if (noUpdate == true) {
		alert("披露宴日が過ぎてきるため、登録・更新ができません");
		return false;
	}

	var email = document.getElementById('mail').value;
	var com_email = document.getElementById('con_mail').value;

	var reg = /^[A-Za-z0-9](([_|\.|\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([_|\.|\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})$/;

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

	if(document.getElementById("marriage_day").value=='')
	{
		alert("挙式日を正しく入力してください");
		document.getElementById('marriage_day').focus();
		return false;
	}

	if(document.getElementById("marriage_hour").value >23)
	{
		alert("24時間以内で入力してください");
		document.getElementById('marriage_hour').focus();
		return false;
	}

   var str4 = document.getElementById("marriage_hour").value;
   if (str4 == "") {
      alert("挙式時間を入力ください");
	  document.getElementById('marriage_hour').focus();
	  return false;
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
   if(document.getElementById("marriage_minute").value >59)
	{
		alert("59分以上は入力できません");
		document.getElementById('marriage_minute').focus();
		return false;
	}

   var str5 = document.getElementById("marriage_minute").value;
   if (str5 == "") {
      alert("挙式時間を入力ください");
	  document.getElementById('marriage_minute').focus();
	  return false;
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

	if(document.getElementById("room_id").value=='')
	{
		alert("必須項目は必ず入力してください。");
		document.getElementById('room_id').focus();
		return false;
	}

	if(document.getElementById("religion").value=='')
	{
		alert("挙式種類を選択してください");
		document.getElementById('religion').focus();
		return false;
	}

	if(document.getElementById("party_day").value=='')
	{
		alert("披露宴日を正しく入力してください");
		document.getElementById('party_day').focus();
		return false;
	}

	if(document.getElementById("party_hour").value >23)
	{
		alert("24時間以内で入力してください");
		document.getElementById('party_hour').focus();
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

	if(document.getElementById("party_minute").value >59)
	{
		alert("59分以上は入力できません");
		document.getElementById('party_minute').focus();
		return false;
	}

   var str7 = document.getElementById("party_minute").value;
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

	if(document.getElementById("party_room_id").value=='')
	{
		alert("挙式会場名を入力してください");
		document.getElementById('party_room_id').focus();
		return false;
	}
	if(document.getElementById("user_id").value=='')
	{
		alert("ログインIDを入力してください");
		document.getElementById('user_id').focus();
		return false;
	}

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
	}
// UCHIDA EDIT 11/08/05 パスワードは入力対象ではなくなったので、チェックを外す
/*
var pass = document.getElementById("password").value;
if(document.getElementById("password").value=='')
	{
		alert("パスワードは半角英数字6文字以上で入力ください ");
		document.getElementById('password').focus();
		return false;
	}
	else
	{

		var c = document.getElementById("password").value.length;
		if(c<6)
		{
			alert("パスワードは半角英数字6文字以上で入力ください ");
			document.getElementById('password').focus();
			return false;
		}
	}

	if(!RE_PASS.exec(pass))
	 {
		alert("パスワードは英数字 を入力してください");
		document.getElementById('password').focus();
		return false;
	  }
*/
	//checkDuplicateMail();
	/*matchMail();
	checkRoomAvailability();*/
	if(document.getElementById("room_id").value==document.getElementById("current_room_id").value)
	document.user_form_register.submit();
	else
		{
			if(confirm("披露宴会場を変更すると、現在の設定が削除されます。変更してよろしいですか？"))
			document.user_form_register.submit();
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

  <?php  echo $objInfo->get_user_name_image_or_src($user_row['id'] ,$hotel_id=1, $name="man_lastname.png",$extra="thumb1",$height=20);?>
・
  <?php  echo $objInfo->get_user_name_image_or_src($user_row['id'] ,$hotel_id=1, $name="woman_lastname.png",$extra="thumb1",$width=20);?>
  様

    </div>
        <h2><div style="width:400px; ">
	<!-- UCHIDA EDIT 11/08/02
	        <a href="users.php">お客様一覧</a> &raquo; お客様挙式情報</div>
	-->
	        <a href="manage.php">ＴＯＰ</a> &raquo; お客様挙式情報</div>
        </h2>
		<div style="width:800px;">
        	<div class="navi">
            	<img src="img/common/navi01_on.jpg"/>
            </div>
        	<div class="navi">
            	<a href="message_admin.php?user_id=<?=$user_id?>&stuff_id=<?=$stuff_id?>"><img src="img/common/navi02.jpg" class="on" /></a>
            </div>
        	<div class="navi">
            	<a href="user_dashboard.php?user_id=<?=$user_id?>" target="_blank">
            		<img src="img/common/navi03.jpg" class="on" />
                </a>
            </div>
        	<div class="navi"><a href="guest_gift.php?user_id=<?=$user_id?>&stuff_id=<?=$stuff_id?>"><img src="img/common/navi04.jpg" class="on" /></a></div>
<?php if($_SESSION["super_user"]){
?>
          <div class="navi"><a href="csv_upload.php?user_id=<?=$user_id?>"  onclick="m_win(this.href,'mywindow7',500,200); return false;">
            <img src="img/common/navi05.jpg" class="on" />
          </a></div>
             <?php }
          ?>
        	<div style="clear:both;"></div>
        </div>
        <br />

<!-- UCHIDA EDIT 11/08/05 左寄せなどその他の修正  -->
		<h2>
        	<div style="width:400px;"><font color="#2052A3"><strong>お客様挙式情報</strong>　<font color="red">*</font>の付いた項目は必須です。</font></div>
        </h2>
		<?php
		//echo "<pre>";
		//print_r($user_row);

		?>
		<div id="div_box_1" style="width:1000px;">
        <form action="insert_user.php?user_id=<?=$user_id;?>" method="post" name="user_form_register">
        <table width="800" border="0" cellspacing="10" cellpadding="0">
            <tr>
              <td width="192" align="left" valign="middle" nowrap="nowrap">披露宴日<font color="red">*</font>　</td>
                <td width="10" align="left" valign="middle" nowrap="nowrap">：</td>
                <td colspan="1" align="left" valign="middle" nowrap="nowrap">
                	<input name="party_day" type="text" id="party_day" value="<?=$obj->date_dashes_convert($user_row['party_day'])?>" size="15" readonly="readonly" style="background: url('img/common/icon_cal.gif') no-repeat scroll right center rgb(255, 255, 255); padding-right: 20px;padding-top:4px; padding-bottom:4px;" class="datepicker"/>
					<?php
					if ($user_row['party_day'] < date("Y-m-d")) $noUpdate = true; else $noUpdate = false;
					//$party_day_array = explode("-",$user_row['party_day']);
					?>
					<!--<input type="text" style="width:30px;" maxlength="4" name="party_year" id="party_year" value="<?=$party_day_array[0]?>">/
					<input type="text" style="width:17px;" maxlength="2" name="party_month" id="party_month" value="<?=$party_day_array[1]?>">/
					<input type="text" style="width:17px;" maxlength="2" name="party_day" id="party_day" value="<?=$party_day_array[2]?>"> yyyy/mm/dd-->
                <!--&nbsp;<a href="javascript:void(0)" onclick="document.getElementById('party_day').value='';">クリア </a>-->
                </td>
                <td colspan="2" align="left" valign="middle" nowrap="nowrap">　　&nbsp;披露宴時間<font color="red">*</font>：<!--<input name="party_day_with_time" type="text" id="party_day_with_time" value="<?=date("H:i", strtotime($user_row['party_day_with_time']))?>" size="10" readonly="readonly" style="width:86px;background: url('img/common/icon_cal.gif') no-repeat scroll right center rgb(255, 255, 255); padding-right: 20px;" class="timepicker"/>-->
				<?php
					$party_time_array = explode(":",$user_row['party_day_with_time']);
					?>
				<input type="text" style="padding-top:4px; padding-bottom:4px;width:17px;" maxlength="2" name="party_hour" id="party_hour" value="<?=$party_time_array[0]?>"> :
				<input type="text" style="padding-top:4px; padding-bottom:4px;width:17px;" maxlength="2" name="party_minute" id="party_minute" value="<?=$party_time_array[1]?>"> (24時間表記)
                <!--&nbsp;<a href="javascript:void(0)" onclick="document.getElementById('party_day_with_time').value='';">クリア </a>-->
                </td>
            </tr>

            <tr>
              <td width="192" align="left" valign="middle" nowrap="nowrap">新郎氏名<font color="red">*</font></td>
                <td width="10" align="left" valign="middle" nowrap="nowrap">：</td>
                <td width="92%" colspan="3" align="left" valign="middle" nowrap="nowrap">
		   <div id="form_hidden_value"><? echo getAllGaijisInputEle(array($man_firstname_gaijis,$man_lastname_gaijis,$woman_firstname_gaijis,$woman_lastname_gaijis));?></div>
		   <div style="height:20px;width:346px;">

		   	<div id="male_lastname_img_div_id" style="width:173px;float:left;height:20px;"><?php echo getGaijis($man_lastname_gaijis);?></div>
	      <div id="male_firstname_img_div_id" style="width:173px;float:left;height:20px;"><?php echo getGaijis($man_firstname_gaijis);?></div>
                   </div>
                    <input name="man_lastname" style="padding-top:4px; padding-bottom:4px;" type="text" id="man_lastname" value="<?=$user_row['man_lastname']?>" size="30" onclick="change_gaiji_link('man_lastname')" />
					<input name="man_firstname" type="text" style="padding-top:4px; padding-bottom:4px;" id="man_firstname" value="<?=$user_row['man_firstname']?>" size="30"  onclick="change_gaiji_link('man_firstname')" />
                	様　 <a id="man_gaiji_link_id" onclick="m_win(this.href,'mywindow7',500,505); return false;" href="../gaiji/palette.php"><img src="img/common/btn_gaiji.jpg" width="82" height="22"alt="外字検索" title="外字検索" /></a>
                </td>

            </tr>
            <tr>
              <td width="192" align="left" valign="middle" nowrap="nowrap">ふりがな<font color="red">*</font>　</td>
            	<td width="10" align="left" valign="middle" nowrap="nowrap">：</td>
           		<td  colspan="3" align="left" valign="middle" nowrap="nowrap">

               		<input name="man_furi_lastname" style="padding-top:4px; padding-bottom:4px;" type="text" id="man_furi_lastname" value="<?=$user_row['man_furi_lastname']?>" size="30" />
            		<input name="man_furi_firstname" style="padding-top:4px; padding-bottom:4px;" type="text" id="man_furi_firstname" value="<?=$user_row['man_furi_firstname']?>" size="30" /> 様
                </td>
            </tr>
            <tr>
              <td width="192" align="left" valign="middle" nowrap="nowrap">新婦氏名<font color="red">*</font></td>
            	<td width="10" align="left" valign="middle" nowrap="nowrap">：</td>
            	<td  colspan="3" align="left" valign="middle" nowrap="nowrap">
		    	<div style="width:346px;height:20px;">
		        <div id="female_lastname_img_div_id" style="width:173px;float:left;height:20px;"><?php echo getGaijis($woman_lastname_gaijis);?></div>
            <div id="female_firstname_img_div_id" style="width:173px;float:left;height:20px;"><?php echo getGaijis($woman_firstname_gaijis);?></div>
			</div>

            <input name="woman_lastname" style="padding-top:4px; padding-bottom:4px;" type="text" id="woman_lastname" value="<?=$user_row['woman_lastname']?>" size="30" onclick="change_gaiji_link('woman_lastname')" />
            <input name="woman_firstname" style="padding-top:4px; padding-bottom:4px;" type="text" id="woman_firstname" value="<?=$user_row['woman_firstname']?>" size="30"  onclick="change_gaiji_link('woman_firstname')"/>
                    様　<a id="woman_gaiji_link_id" onclick="m_win(this.href,'mywindow7',500,500); return false;" href="../gaiji/palette.php"><img src="img/common/btn_gaiji.jpg" width="82" height="22" alt="外字検索" title="外字検索" /></a>
                </td>

            </tr>
            <tr>
              <td width="192" align="left" valign="middle" nowrap="nowrap">ふりがな<font color="red">*</font>　</td>
            	<td width="10" align="left" valign="middle" nowrap="nowrap">：</td>
            	<td  colspan="3" align="left" valign="middle" nowrap="nowrap">
                  	<input name="woman_furi_lastname" style="padding-top:4px; padding-bottom:4px;" type="text" id="woman_furi_lastname" value="<?=$user_row['woman_furi_lastname']?>" size="30" />

                   <input name="woman_furi_firstname" style="padding-top:4px; padding-bottom:4px;" type="text" id="woman_furi_firstname" value="<?=$user_row['woman_furi_firstname']?>" size="30" />
様

                </td>
            </tr>
            <tr>
              <td width="192" align="left" valign="middle" nowrap="nowrap">披露宴会場</td>
                <td width="10" align="left" valign="middle" nowrap="nowrap">：</td>
                <td align="left" valign="middle" nowrap="nowrap">
				<input type="hidden" name="current_room_id" id="current_room_id" value="<?=$user_row['room_id']?>"  />
                	<select name="room_id" id="room_id" style="padding-top:4px; padding-bottom:4px;">

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
                </td>
                <td align="left" valign="middle" nowrap="nowrap">&nbsp;</td>
                <td nowrap="nowrap">&nbsp;</td>
            </tr>



            <tr>
              <td width="192" align="left" valign="middle" nowrap="nowrap" style="text-align:left;" >挙式日</td>
                <td width="10" align="left" valign="middle" nowrap="nowrap" style="text-align:left;" >：</td>
                <td width="60" align="left" valign="middle" nowrap="nowrap">

					<input name="marriage_day" type="text" id="marriage_day" value="<?=$obj->date_dashes_convert($user_row['marriage_day'])?>"  size="15" readonly="readonly" style="background: url('img/common/icon_cal.gif') no-repeat scroll right center rgb(255, 255, 255); padding-right: 20px; padding-top:4px; padding-bottom:4px;" class="datepicker" />

					<?php
					//$marriage_day_array = explode("-",$user_row['marriage_day']);
					?>
					<!--<input type="text" style="width:30px;" maxlength="4" name="marriage_year" id="marriage_year" value="<?=$marriage_day_array[0]?>">/
					<input type="text" style="width:17px;" maxlength="2" name="marriage_month" id="marriage_month" value="<?=$marriage_day_array[1]?>">/
					<input type="text" style="width:17px;" maxlength="2" name="marriage_day" id="marriage_day" value="<?=$marriage_day_array[2]?>"> yyyy/mm/dd-->
                	<!--&nbsp;<a href="javascript:void(0)" onclick="document.getElementById('marriage_day').value='';">クリア </a>-->
                </td>
                <td width="200" colspan="2" align="left" valign="middle" nowrap="nowrap">　　&nbsp;挙式時間&nbsp;　：
                <!--<input name="marriage_day_with_time" type="text" id="marriage_day_with_time" value="<?=date("H:i",strtotime($user_row['marriage_day_with_time']))?>" size="10" readonly="readonly" style="background: url('img/common/icon_cal.gif') no-repeat scroll right center rgb(255, 255, 255); padding-right: 20px;" class="timepicker"/>-->
				<?php
					$marriage_time_array = explode(":",$user_row['marriage_day_with_time']);
					?>
				<input type="text" style="width:17px;padding-top:4px; padding-bottom:4px;" maxlength="2" name="marriage_hour" id="marriage_hour" value="<?=$marriage_time_array[0]?>"> :
				<input type="text" style="width:17px;padding-top:4px; padding-bottom:4px;" maxlength="2" name="marriage_minute" id="marriage_minute" value="<?=$marriage_time_array[1]?>"> (24時間表記)
                <!--&nbsp;<a href="javascript:void(0)" onclick="document.getElementById('marriage_day_with_time').value='';">クリア </a>-->
                </td>
            </tr>
            <tr>
              <td width="192" align="left" valign="middle" nowrap="nowrap">挙式種類</td>

              <td width="10" align="left" valign="middle" nowrap="nowrap">：</td>
                <td align="left" valign="middle" nowrap="nowrap">
                	<select name="religion" style="padding-top:4px; padding-bottom:4px;" id="religion" ><!--onchange="load_party_room(this.value);"-->

<!-- UCHIDA EDIT 11/08/03  <option value=""  <?php if($user_row[religion]=='') {?> selected="selected" <?php } ?>>選択してください</option> -->
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
                </td>

                <td align="left" valign="middle" nowrap="nowrap">
                	　　&nbsp;挙式会場<font color="red">*</font>　：
					<?php
						$party_rooms_name = $obj->GetSingleData("spssp_party_room","name"," id=".$user_row['party_room_id']);

					?>
					<input name="party_room_id" style="padding-top:4px; padding-bottom:4px; width:130px;" type="text" id="party_room_id"  class="input_text" value="<?=$party_rooms_name?>" />
					<!--<select name="party_room_id" id="party_room_id" style="width:106px;">


                    < ?php
						$party_rooms = $obj->GetAllRowsByCondition("spssp_party_room"," id=".$user_row['party_room_id']);
                        if($party_rooms)
                        {
                            foreach($party_rooms as $pr)
                            {
                                if($pr['id'] == $user_row['party_room_id'])
									echo "<option value ='".$pr['id']."' selected> ".$pr['name']." </option>";
								else
								 	echo "<option value ='".$pr['id']."'> ".$pr['name']." </option>";

                            }
                        }
                    ?>
                	</select>-->
                </td>
            </tr>




            <tr>
              <td width="192" align="left" valign="middle" nowrap="nowrap">ログインID</td>
                <td width="10" align="left" valign="middle" nowrap="nowrap">：</td>
                <td colspan="3" align="left" valign="middle" nowrap="nowrap">

                <!--
                Kumar
                <input name="user_id" style="padding-top:4px; padding-bottom:4px;" type="text" id="user_id" value="<?=$user_row['user_id']?>" size="30" />-->

               <input name="user_id" style="padding-top:4px; padding-bottom:4px;" type="hidden" id="user_id" value="<?=$user_row['user_id']?>" size="30" />
                <?=$user_row['user_id']?>

                </td>
            </tr>
            <tr>
              <td width="192" align="left" valign="middle" nowrap="nowrap">お客様パスワード</td>
                <td width="10" align="left" valign="middle" nowrap="nowrap">：</td>
                <td colspan="3" align="left" valign="middle" nowrap="nowrap"><!--<input name="password" style="padding-top:4px; padding-bottom:4px;" type="text" id="password" value="<?=$user_row['password']?>" size="30"  onblur="checkvalidity()"/>
				<br /><span id="password_msg">パスワードは英数字6文字以上にしてください</span>-->

                <input name="password" style="padding-top:4px; padding-bottom:4px;" type="hidden" id="password" value="<?=$user_row['password']?>" size="30"  onblur="checkvalidity()"/>

				<?=$user_row['password']?>
                </td>
            </tr>
			<tr>
			  <td width="192" align="left" valign="middle" nowrap="nowrap">お客様ＩＤ利用期限日</td>
                <td width="10" align="left" valign="middle" nowrap="nowrap">：</td>
               <?php $user_id_limit = $obj->GetSingleData("spssp_options" ,"option_value" ," option_name='user_id_limit'");


				$dateBeforeparty = $objInfo->get_date_with_supplyed_flag_difference( $user_row['party_day'] , $user_id_limit , $flag=1 );

				// UCHIDA EDIT 11/08/17 曜日表示
				$weekname = $objMsg->get_youbi_name( $dateBeforeparty );
			   ?>
			   <td colspan="3" align="left" valign="middle" nowrap="nowrap"><?=$dateBeforeparty?><?=$weekname?> 披露宴日&nbsp;<?=$user_id_limit?>日後</td>
            </tr>
<tr>
  <td width="192" align="left" valign="middle" nowrap="nowrap">メールアドレス</td>
                <td width="10" align="left" valign="middle" nowrap="nowrap">：</td>
                <td colspan="3" align="left" valign="middle" nowrap="nowrap"><input style="padding-top:4px; padding-bottom:4px;" name="mail" type="text" id="mail" size="30" value="<?=$user_row['mail']?>" />

				</td>
            </tr>


<tr>
  <td width="192" align="left" valign="middle" nowrap="nowrap">メールアドレス確認用</td>
                <td width="10" align="left" valign="middle" nowrap="nowrap">：</td>
                <!--  UCHIDA EDIT 11/08/05 確認用メールアドレスのペーストを禁止 -->
                <!--  UCHIDA EDIT 11/08/08 メッセージ変更 -->
                <td colspan="3" align="left" valign="middle" nowrap="nowrap" onpaste="alert('メールアドレス確認用は貼り付けできません');return false;"><input style="padding-top:4px; padding-bottom:4px;" name="con_mail" type="text" id="con_mail" size="30" value="<?=$user_row['mail']?>" />
				</td>
            </tr>
            <tr>
              <td width="192" align="left" valign="middle" nowrap="nowrap">お知らせをメールで受信する</td>
				 <td width="10" align="left" valign="middle" nowrap="nowrap">：</td>
				<td colspan="3" align="left" valign="middle" nowrap="nowrap">

			<input type="radio" name="subcription_mail" value="0" <?php if($user_row['subcription_mail']=='0'){echo "checked='checked'";}else{echo "checked='checked'";}?> /> 受信する
			<input type="radio" name="subcription_mail" value="1" <?php if($user_row['subcription_mail']=='1'){echo "checked='checked'";}?>/>  受信しない
				</td>
			</tr>
            <tr>
              <td width="192" align="left" valign="middle" nowrap="nowrap">担当</td>
                <td width="10" align="left" valign="middle" nowrap="nowrap">：</td>
                <td colspan="3" align="left" valign="middle" nowrap="nowrap">
				<select name="stuff_id" style="padding-top:4px; padding-bottom:4px;">
				<?php
					foreach($All_staffs as $staf_rows){
				?>
				<option value="<?=$staf_rows['id']?>" <?php if($staf_rows['id']==$user_row['stuff_id']){echo "selected='selected'";}?> ><?=$staf_rows['name']?></option>

				<?php }?>
				</select>

				<!--<input name="stuff_id" type="text" id="stuff_id" value="<?=$staff_name?>" size="10" />-->
				</td>
            </tr>
            <tr>
              <td width="192" align="left" valign="middle" nowrap="nowrap">&nbsp;</td>
                <td width="10" align="left" valign="middle" nowrap="nowrap">&nbsp;</td>
                <td colspan="3" align="left" valign="middle" nowrap="nowrap">
                	<a href="javascript:void(0)" onclick="valid_user('<?=$user_row['id']?>' , '<?=$noUpdate?>');">
                    	<img src="img/common/btn_regist_update.jpg" border="0" width="82" height="22" /><br /><br />
                    </a>
                </td>
            </tr>
            <tr align="left" valign="middle">
            	<td colspan="5" nowrap="nowrap"><div style="width:450px;">
                	<a href="user_log.php?user_id=<?=(int)$_GET['user_id']?>"><img src="img/common/btn_access.jpg" width="173" height="23" /></a>　
                    <a href="change_log.php?user_id=<?=(int)$_GET['user_id']?>"><img src="img/common/btn_data.jpg" width="173" height="23" /></a></div>
                </td>
            </tr>
        </table>
        </form>
		 </div> <!--end of  id="div_box_1"-->
        <br />
        <h2><div>席次表設定</div></h2>
        <?php/* エラーメッセージを抑制
        	if(isset($_GET['err']) && $_GET['err']!='')
			{
				echo "<script>
				alert('".$obj->GetErrorMsgNew((int)$_GET['err'])."');
				</script>";
			}*/
		?>
		<div id="div_box_1" style="1000px;">
        <form action="insert_user_info_plan.php?user_id=<?=(int)$_GET['user_id']?>" method="post" name="user_info_plan">
        <input type="hidden" id="max_rows" value="<?=$room_row['max_rows']?>" />
        <input type="hidden" id="max_columns" value="<?=$room_row['max_columns']?>" />
        <input type="hidden" id="max_seats" value="<?=$room_row['max_seats']?>" />

        <table width="850" border="0" cellspacing="10" cellpadding="0">




            <tr>
              <td width="192" align="left" valign="middle" nowrap="nowrap">披露宴会場名</td>
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

				 //print_r($room_plan_rows);
				 //echo "<br>";
                foreach($room_plan_rows as $plan)
                {

                  $plan_row = $plan;
                  $num_layouts = $obj->GetNumRows("spssp_table_layout"," default_plan_id=".$plan_row['id']);
                  $layoutname = $obj->getSingleData("spssp_plan", "layoutname"," user_id= $user_id");

                  $row_width = $plan_row['column_number'] *45;
                  echo "<div class='plans' id='plan_".$plan_row['id']."' style='width:".$row_width."px;margin:0 auto;'>";
                  $default_layout_title = $obj->GetSingleData("spssp_options" ,"option_value" ," option_name='default_layout_title'");
                  if($layoutname!="")
                    {
                      echo "<div style='display:block;text-align:center;width:100px;margin:0 auto;border:1px solid gray;'>".$layoutname."</div>";
                    }
                  else if($default_layout_title!="")
                    {
                      echo "<div style='display:block;text-align:center;width:100px;margin:0 auto;border:1px solid gray;'>".$default_layout_title."</div>";
                    }
                  else
                    {
                      echo "<p style='text-align:center'><img src='img/sakiji_icon/icon_takasago.gif' width='102' height='22' /></p>";
                    }



                    $sqltrace = "select distinct row_order from spssp_table_layout where default_plan_id= ".(int)$plan['id'];
                  $tblrows = $obj->getRowsByQuery($sqltrace);

                  foreach($tblrows as $tblrow)
                    {

                      $ralign = $obj->GetSingleData("spssp_table_layout", "align"," row_order=".$tblrow['row_order']." and user_id=".(int)$user_id." limit 1");

                      $num_none = $obj->GetSingleData("spssp_table_layout", "count(*) "," display=0 and row_order=".$tblrow['row_order']." and default_plan_id=".(int)$plan_row['id']." limit 1");

                      if($ralign == 'L')
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
                      $tables = $obj->getRowsByQuery("select * from spssp_table_layout where default_plan_id= ".(int)$plan['id']." and row_order=".$tblrow['row_order']);

                      foreach($tables as $table)
                        {
                          $new_name_row = $obj->GetSingleRow("spssp_user_table", "default_plan_id = ".(int)$plan_row['id']." and default_table_id=".$table['id']);

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
                          }else{
                            $disp = "display:none";
                          }

                          echo "<div class='tables' style='".$disp."'><p>".$tblname."</p></div>";
                        }
                      echo "</div></div>";
                    }

                  echo "</div>";
                }
            }
            else
              {


                $layout_rows = $obj->GetAllRowsByCondition("spssp_table_layout"," plan_id=".$user_plan_row['id']);

                $num_layouts = $obj->GetNumRows("spssp_table_layout"," default_plan_id=".$user_plan_row['id']);

                $row_width = $user_plan_row['column_number'] *45;
                echo "<div class='plans' id='plan_".$user_plan_row['id']."' style='width:".$row_width."px;margin:0 auto; display:block;'>";
                $default_layout_title = $obj->GetSingleData("spssp_options" ,"option_value" ," option_name='default_layout_title'");
                if($user_plan_row['layoutname']!="")
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

                    if($ralign == 'L')
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
                        }else if($num_first <= $table["column_order"] && $table["column_order"]<=$num_last){
                          $disp = "visibility:hidden";
                        }else{
                          $disp = "display:none";
                        }
                        echo "<div class='tables' style='".$disp."'><p>".mb_substr ($tblname, 0,1,'UTF-8')."</p></div>";//mb_substr ($tblname, 0,1,'UTF-8')
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
				if ($noUpdate == false) { ?>
				<a href='set_table_layout_edit.php?plan_id=<?=$user_plan_row['id']?>&user_id=<?=$user_id?>&stuff_id=<?=$stuff_id?>'>
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
              <td width="192" align="left" valign="middle" nowrap="nowrap">最大卓数</td>
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
              <td width="192" align="left" valign="middle" nowrap="nowrap">一卓人数</td>
                <td width="10" align="left" valign="middle" nowrap="nowrap">：</td>
              <td width="200" align="left" valign="middle" nowrap="nowrap"><?php if($user_plan_row['seat_number']){echo $user_plan_row['seat_number'];}else{echo $room_row['max_seats'];}?>
              <input name="seat_number" type="hidden" id="seat_number" value="<?php if($user_plan_row['seat_number']){echo $user_plan_row['seat_number'];}else{echo $room_row['max_seats'];}?>" size="1" />
              名まで</td>
            </tr>

  <tr>
    <td width="192" align="left" valign="middle" nowrap="nowrap">卓名変更<font color="red">*</font></td>
            <td width="10" align="left" valign="middle" nowrap="nowrap">：</td>
            <td align="left" valign="middle" nowrap="nowrap">
                <?php
					$default_raname_table_view = $obj->GetSingleData("spssp_options" ,"option_value" ," option_name='rename_table_view'");
					$_def_view = "";
					if ($default_raname_table_view == "0") $_def_view = "disabled='disabled'";
					if ($user_plan_row['rename_table'] != "") {
				?>
					<input name="rename_table" type="radio" id="radio1" value="1"  <?php if($user_plan_row['rename_table'] == "1") {echo "checked='checked'";}?> <?=$_def_view ?> />   可
					<input type="radio" name="rename_table" id="radio0" value="0"  <?php if($user_plan_row['rename_table'] == "0") {echo "checked='checked'";}?> <?=$_def_view ?> /> 不可
			  <?php } else {?>
					<input name="rename_table" type="radio" id="radio1" value="1"  <?php if($_def_view=="") {echo "checked='checked'";}?> <?=$_def_view ?> />   可
					<input type="radio" name="rename_table" id="radio0" value="0"  <?php if($_def_view!="") {echo "checked='checked'";}?> <?=$_def_view ?> /> 不可
			  <?php }?>
            </td>
  </tr>
            <tr>
              <td width="192" align="left" valign="middle" nowrap="nowrap">商品名<font color="red">　</font></td>
              <td width="10" align="left" valign="middle" nowrap="nowrap">：</td>
                <td align="left" valign="middle" nowrap="nowrap">
               	  <input name="product_name" type="text" id="product_name" value="<?=$user_plan_row['product_name']?>" size="20" />
              </td>
            </tr>
      <tr>
        <td width="192" align="left" valign="middle" nowrap="nowrap">商品区分<font color="red">*</font></td>
            <td width="10" align="left" valign="middle" nowrap="nowrap">：</td>
                <td align="left" valign="middle" nowrap="nowrap">

				<select name="dowload_options" id="dowload_options">
                    	<option value="1" <?php if($user_plan_row['dowload_options'] == 1) echo "selected='selected'";?>>席次表</option>
                        <option value="2" <?php if($user_plan_row['dowload_options'] == 2) echo "selected='selected'";?>>席札</option>
                        <option value="3" <?php if($user_plan_row['dowload_options'] == 3) echo "selected='selected'";?>>席次表・席札</option>
                </select>
            </td>

          </tr>
            <tr>
              <td width="192" align="left" valign="middle" nowrap="nowrap">サイズ／タイプ</td>

              <td width="40" align="left" valign="middle" nowrap="nowrap">：</td>
                <td align="left" valign="middle" nowrap="nowrap">
                	<select name="print_size" id="print_size" style="width:45px;">
<!-- 					<option value="">選択してください</option> -->
					<option value="1" <?php if($user_plan_row['print_size'] == 1) echo "selected='selected'";?>>A3</option>
					<option value="2" <?php if($user_plan_row['print_size'] == 2) echo "selected='selected'";?>>B4</option>
				</select>
				<select name="print_type" id="print_type" style="width:45px;">
<!--					<option value="">選択してください</option> -->
					<option value="1" <?php if($user_plan_row['print_type'] == 1) echo "selected='selected'";?>>横</option>
					<option value="2" <?php if($user_plan_row['print_type'] == 2) echo "selected='selected'";?>>縦
</option>
				</select>

              </td>
            </tr>
            <tr>
              <td width="192" align="left" valign="middle" nowrap="nowrap">本発注締切日</td>
              <td width="10" align="left" valign="middle" nowrap="nowrap">：</td>
                <td align="left" valign="middle" nowrap="nowrap">
                	<?php
						 //$obj->date_dashes_convert($user_plan_row['confirm_date']);
						//$confirm_day_num = $obj->GetSingleData("spssp_options" ,"option_value" ," option_name='confirm_day_num'");
						$limitation_ranking = $obj->GetSingleData("spssp_options" ,"option_value" ," option_name='limitation_ranking'");


						$date6 = new DateTime($user_row['party_day']);
						$dateinterval = "-".$user_row['confirm_day_num']."day";
			            $date6->modify($dateinterval);
						echo $date6->format("Y/m/d");

					?>
					<input type="text" name="confirm_day_num" id="confirm_day_num" style="width:15px; padding:3px" maxlength="2" value="<?=$user_row['confirm_day_num']?>" /> 日前
			  <input type="hidden" value="<?=$user_row['party_day']?>" name="party_day_for_confirm" />	</td>
            </tr>
            <tr>
              <td width="192" align="left" valign="middle" nowrap="nowrap">席次表編集利用制限日</td>
              <td width="10" align="left" valign="middle" nowrap="nowrap">：</td>
                <td align="left" valign="middle" nowrap="nowrap">

				<?php

				$dateBeforeparty = $objInfo->get_date_with_supplyed_flag_difference( $user_row['party_day'] , $limitation_ranking , $flag=2 );

				// UCHIDA EDIT 11/08/17 曜日表示
				$weekname = $objMsg->get_youbi_name( $dateBeforeparty );
				?>
				<?=$dateBeforeparty?><?=$weekname?> 披露宴日&nbsp;<?=$limitation_ranking?>&nbsp;日前</td>

            </tr>

            <tr>
              <td width="192" align="left" valign="middle" nowrap="nowrap">印刷会社名<font color="red">*</font></td>

              <td width="10" align="left" valign="middle" nowrap="nowrap">：</td>
                <td align="left" valign="middle" nowrap="nowrap">
                	<?php
// UCHIDA EDIT 11/08/02 印刷会社の並び順を印刷会社一覧と同様にする
//					$sql_company = "select * from spssp_printing_comapny order by id desc";
					$sql_company = "select * from spssp_printing_comapny ORDER BY display_order";
					$company_results = $obj->getRowsByQuery($sql_company);
					?>

					<select name="print_company" id="print_company">
<!-- 						<option value="">選択してください</option> -->
						<?php foreach($company_results as $company){?>
						<option value="<?=$company['id']?>" <?php if($user_plan_row['print_company']==$company['id']){echo "selected='selected'";}?> ><?=$company['company_name']?></option>
						<?php }?>
				  </select>

              </td>
            </tr>
        </table>




        <br />
        <p class="txt3">
        	<?php
            //if(empty($user_plan_row))
            //{
			?>
            <a href="javascript:void(0);" onclick="valid_plan('<?=$noUpdate?>');"> <img src="img/common/btn_regist_update.jpg" border="0" /> </a>
        	<!--<input type="button" style="width:62px;height:22px;background-image:url(img/common/btn_regist.jpg);" onclick="<?=$plan_onclick;?>"  />-->
			<?php
			//}
			?>
        </p>
        </form>
		</div> <!--end of  id="div_box_2"-->
        <br />
        <h2><div>引出物設定</div></h2>
            <?php
            $gift_groups = $obj->GetAllRowsByCondition("spssp_gift_group","user_id=".(int)$_GET['user_id']." order by id ASC");
$gifts = $obj->GetAllRowsByCondition("spssp_gift","user_id=".(int)$_GET['user_id']." order by id ASC")
			?>

        <div style="width:1000px; float:left;" id="div_box_3">
        	<div style="width:32%; float:left; ">
            	<div style="width:100px; float:left; ">
                	<p> 引出物商品： <p>
                </div>
                <div style="width:200px;; float:left;">

                	<?php
							/*$j=1;
                            foreach($gifts as $gift)
                            {
                            	echo " <input style='background:#EBEBE4;border:1px solid gray;padding:2px;' type='text' readonly='readonly' value='".$gift['name']."' size='13'> ";
								if($j%2==0)
								{
									echo "</p><p>";
								}
                            }*/
                      ?>

               <!--GIFT ITEM BOX START-->

<form action="user_info.php?user_id=<?=$user_id?>" method="post"  name="editUserGiftItemsForm">
	   <input type="hidden" name="editUserGiftItemsUpdate" value="editUserGiftItemsUpdate">
	   <table width="100%" border="0" cellspacing="1" cellpadding="0">



	   <?php
	  	$yy = 1;
		foreach($gifts as $gift_name)
		{
		echo "<tr><td style='text-align:left;'><input type='text' id='item".$yy."' style='padding-top:3px; padding-buttom:3px;' name='name".$yy."' value='".$gift_name['name']."' size='13'>&nbsp;&nbsp;&nbsp;";

		echo "<input type='hidden' name='fieldId".$yy."' value='".$gift_name['id']."'></td></tr>";
		$yy++;
		}

	  ?>
	  </table>
	  <br />
	  <div>
	  <input type="button" name="editUserGiftItemsUpdateButton" value="保存" onclick="checkGiftForm('<?=$yy?>', '<?=$noUpdate?>')"></div><br />
	  </form>
<!--GIFT ITEM BOX END-->

                </div>
            </div>

		 <div style="width:100px; float:left; ">
		 引出物グループ:
		 </div>

	       <div style="width:300px; float:left; ">
		   <!--GIFT GROUP BOX START-->
		        <p>
				<form action="user_info.php?user_id=<?=$user_id?>" method="post" name="editUserGiftGroupsForm">
				  <input type="hidden" name="editUserGiftGroupsUpdate" value="editUserGiftGroupsUpdate">
				  <?php
					$xx = 1;
					foreach($gift_groups as $row)
					{
						echo "<div style='margin-left:15px;'><input type='text' id='name".$xx."' ".$ro." name='name".$xx."' maxlength='4' size='6' value='".$row['name']."'>";
						echo "<input type='hidden' name='fieldId".$xx."' value='".$row['id']."'></div>";
						$xx++;
					}
				  ?>
				   <br />
				   <input type="button" style="margin-left:15px;" name="editUserGiftGroupsUpdateButton" value="保存" onclick="checkGroupForm('<?=$xx?>' ,'<?=$noUpdate?>');">
				 </form>
		         </p>
			<!--GIFT GROUP BOX END-->
					<br />
            </div>
	       <div style="width:300px; float:left; valign:top" >
			<?php
			$gift_criteria = $obj->GetSingleRow("spssp_gift_criteria", " id=1");
			//print_r($gift_criteria);
			?>
			<p>
            <?php
			$gift_criteria = $obj->GetSingleRow("spssp_gift_criteria", " id=1");
			$dateBeforeparty = $objInfo->get_date_with_supplyed_flag_difference( $user_row['party_day'] , $gift_criteria['order_deadline'] , $flag=2 );
			?>
				締切予定日： &nbsp; <input style="background:#EBEBE4;border:1px solid gray;padding:2px;" type="text" id="textfield15" readonly="readonly" value="<?=$obj->japanyDateFormate($dateBeforeparty)?>" size="25" />
            </p>
            <br /><p>
                締切日設定： &nbsp;<?=$gift_criteria['order_deadline']?> &nbsp;前日
            <br /><br />
            </p>
		</div>

        </div>

        <br />

        <div>
        	<p><h2>料理設定（子供料理）</h2></p>
            <?php
            	$menu_groups = $obj->GetAllRowsByCondition("spssp_menu_group","user_id=".(int)$_GET['user_id']);
				$num_groups = count($menu_groups);
			?>
        </div>
       <div id="div_box_4" style="width:500px;">

        	<?php
            	if($num_groups >0)
				{?>
				<form action="user_info.php?user_id=<?=$user_id?>" method="post" name="editUserMenuGroupsForm">
		  			<input type="hidden" name="editUserMenuGroupsUpdate" value="editUserMenuGroupsUpdate">
					 <table width="100%" border="0" cellspacing="10" cellpadding="0">
				<?php
					$i=1;
					echo "<tr>";
					foreach($menu_groups as $mg)
					{
						echo "<td>子供料理 $i :<input type='text' name='menu".$i."' id='item".$i."' value='".$mg['name']."' size='20'></td>";

						echo "<input type='hidden' name='menuId".$i."' value='".$mg['id']."'>";



						if($i%3==0)
						{
							echo "</tr><tr>";
						}
						$i++;
					}
					echo "<tr>";?>
					 <tr><td>
					 <input type="button" style="margin-left:15px;" name="editUserMenuGroupsUpdateButton" value="保存" onclick="checkMenuGroupForm('<?=$i?>' ,'<?=$noUpdate?>');">
					 </td> </tr>
					 </table>

		   </form> <!--end of  id="div_box_3"-->

			<?php	}
			?>
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