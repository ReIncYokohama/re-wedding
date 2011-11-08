<?php
include_once("admin/inc/class_information.dbo.php");
include_once("admin/inc/class_data.dbo.php");
include_once("inc/checklogin.inc.php");

$obj = new DataClass();
$obj_data = new DataClass();

$objInfo = new InformationClass();
$get = $obj->protectXSS($_GET);
$user_id = (int)$_SESSION['userid'];
include_once("inc/new.header.inc.php");

$message = "";
if($_GET["insert"]) $message = "招待者および招待者情報が登録されました。";
if($_GET["update"]) $message = "招待者および招待者情報が更新されました。";;


if(isset($_GET['action']) && $_GET['action'] == 'delete' )
	{
		$guest_id = (int)$_GET['guest_id'];
    
    //ゲストの削除をログに
    $obj->set_log_guest_delete($user_id,$guest_id,$_SESSION["adminid"]);

		$obj->DeleteRow('spssp_guest', 'id='.$guest_id);
		$obj->DeleteRow('spssp_plan_details', 'guest_id='.$guest_id);
		
		$obj->DeleteRow('spssp_guest_gift', 'guest_id='.$guest_id); // 関連テーブルの削除
		$obj->DeleteRow('spssp_guest_menu', 'guest_id='.$guest_id); // 関連テーブルの削除
		$obj->DeleteRow('spssp_guest_gift', 'guest_id=0'); // バグでguest_id=0ができるので、一時的に削除
		$obj->DeleteRow('spssp_guest_menu', 'guest_id=0'); // バグでguest_id=0ができるので、一時的に削除
	}

include_once("admin/inc/class_message.dbo.php");
$message_class = new MessageClass();

switch($_SESSION["adminid"]){
  case 1:
  case 2:
    $message_class->finish_message_csv_import_for_hotel($user_id);
    break;
  case 0:
    $message_class->finish_message_csv_import_for_user($user_id);
    break;
}


include("admin/inc/main_dbcon.inc.php");
$respects = $obj->GetAllRow(" spssp_respect"." order by display_order DESC ");

$guest_types = $obj->GetAllRow(" spssp_guest_type"." order by display_order DESC ");
include("admin/inc/return_dbcon.inc.php");
$table='spssp_guest';
$where = " user_id =".(int)$_SESSION['userid'];
$data_per_page=2;
$current_page=(int)$_GET['page'];
$redirect_url = "my_guests.php";

//$order = ' last_name asc ';
$order = ' id asc ';

$plan_info = $obj ->GetSingleRow("spssp_plan"," user_id=".(int)$_SESSION['userid']);


if(isset($_GET['option']) && $_GET['option'] != '')
	{
		$optionValue = $_GET['option'];

		$ordervalue = explode(",",$_GET['option']);

    for($i=0;$i<(count($ordervalue)-1);$i++)
      {
        if($ordervalue[$i]!="guest_type")
					$orderarray[] =  $ordervalue[$i].' asc';
        else
					{
						include("admin/inc/main_dbcon.inc.php");
						$query_string="SELECT * FROM spssp_guest_type  ORDER BY id asc ;";

						$data_type = $obj->getRowsByQuery($query_string);

						include("admin/inc/return_dbcon.inc.php");
						$j=0;
						foreach($data_type as $type)
              {
                $orderarray_value.=" WHEN '".$type['id']."' THEN ".$j;
                $j++;
              }
						$orderarray_value="CASE guest_type".$orderarray_value." ELSE ".$j." END ASC";
						$orderarray[]=$orderarray_value;

					}
      }
    $order=implode(",",$orderarray);
	}


$sql = "select count(*) AS total_record from $table where $where";
$db_result=mysql_query($sql);
if($db_row=mysql_fetch_array($db_result))
	{
		$total_record=$db_row['total_record'];
	}
$total_record  = (int)$total_record;
$total_page=ceil($total_record/$data_per_page);
$last_page = $total_page-1;
//echo $total_page;
$current_page=(int)$current_page;

if($current_page>$total_page) $current_page=$total_page;

//if($current_page<=0) $current_page=1;

if($current_page+1 >=$total_page)
  $next=$current_page;
else
  $next=$current_page+1;

if($current_page<=0)
  $prev=0;
else
  $prev=$current_page-1;
if(strpos($redirect_url,'?') !== false)
	{
		$url_sign = '&';
	}
else
	{
		$url_sign = '?';
	}
$pagination = '';
if($total_page >= 1)
	{

    $pagination.= '<td width="29" rowspan="3" align="center">最初<br/> <a href="'.$redirect_url.$url_sign.'page=0"><img src="img/arrow_first.jpg" width="19" height="14" border="0" /></a></td>  <td width="29" rowspan="3" align="center">前へ<br/> <a href="'.$redirect_url.$url_sign.'page='.$prev.'"><img src="img/arrow_prev.jpg" width="10" height="14" border="0" /></a></td>';

    $pagination .= '<td width="29" rowspan="3" align="center">次へ<br/><a href="'.$redirect_url.$url_sign.'page='.$next.'"><img src="img/arrow_next.jpg" width="10" height="14" border="0" /></a> </td> <td width="50" rowspan="3" align="center">最後<br/><a href="'.$redirect_url.$url_sign.'page='.$last_page.'"><img src="img/arrow_last.jpg" width="19" height="14" border="0" /></a></td>';

	}

//$query_string="SELECT * FROM $table where $where  ORDER BY $order  LIMIT ".((int)($current_page)*$data_per_page).",".((int)$data_per_page).";";
$query_string="SELECT * FROM $table where $where  ORDER BY $order";
//echo $query_string;
$guests = $obj->getRowsByQuery($query_string);

$genderStatus = $obj->GetSingleData(" spssp_guest_orderstatus ", "orderstatus", " user_id = ".$user_id);


?>
<script type="text/javascript">
//jqueryの変数を統一するため。
$j = $;
</script>
<script src="js/gaiji.js"></script>
<script type="text/javascript">
$j = $;

$(document).ready(function(){
      
  $(".check_sjs_1").change(function(){
      checkGaiji($(this).val(),"gaiji_check.php",this);
  });


    setDeleteGaiji({
      input_id:"last_name",
          form_name:"male_last_gaiji_",
          div_image:"lastname_img_div_id"});
    setDeleteGaiji({
      input_id:"first_name",
          form_name:"male_first_gaiji_",
          div_image:"firstname_img_div_id"});
    setDeleteGaiji({
      input_id:"comment1",
          form_name:"comment1_gaiji_",
          div_image:"comment1_img_div_id"});
    setDeleteGaiji({
      input_id:"comment2",
          form_name:"comment2_gaiji_",
          div_image:"comment2_img_div_id"});
});

  function backtotop()
{
  $('html, body').animate({scrollTop: '200px'}, 500);

}
function stage_enebeled()
{
  var stage_guest_current=document.getElementById('stage_guest_current').value;
  var stage_value=document.getElementById('stage').value;
  if(stage_value=='1')
    {
      $('#stage_guest').val(stage_guest_current);
      document.getElementById('stage_guest').disabled=false;

    }
  else
    {
      $('#stage_guest').val("");
      document.getElementById('stage_guest').disabled=true;

    }
	changeAction = true;
	resetTimeOut();
}
var title=$("title");
$(title).html("招待者リストの作成 - ウエディングプラス");

function gsearch()
{

  var searchArray = new Array(3);
  var searchvalue = new Array(3);

  searchvalue[0] = 'sexsearch';
  searchvalue[1] ='guset_typesearch';
  searchvalue[2] ='last_namesearch';


  searchArray[0] =$("#sexsearch").attr('checked');
  searchArray[1] = $("#guset_typesearch").attr("checked");
  searchArray[2] = $("#last_namesearch").attr("checked");


  var url = "my_guests.php?page=<?=(int)$_GET['page']?>&option=";
  var url2 ='';
  for(j=0;j<3;j++)
		{
		  if(searchArray[j])
        {
          url2 += $("#"+searchvalue[j]+":checked").val()+",";
        }
		}

  if(url2 =='')
		{
			alert("ソートする項目を選択してください");
			return false;
		}
  window.location = url+url2;
}

$(function(){
		$("ul#menu li").removeClass();
		$("ul#menu li:eq(3)").addClass("active");

		var msg_html=$("#msg_rpt").html();

    //続けて登録する人用に事前に情報を保存。
    //gidがある場合はもちろん入力されている項目を出力する。
    <?php
    if($_GET["gid"]) print "/*";
    ?>
    var cookieArray = ["sex","guest_type","gift_group"];
    for(var i=0;i<cookieArray.length;++i){
      var t = cookieArray[i];
      if($.cookie('user_'+t+"<?=$HOTELID?>")) $("#"+t).val($.cookie('user_'+t+"<?=$HOTELID?>"));
    }
    <?php
    if($_GET["gid"]) print "*/";
    ?>
		if(msg_html!='')
      {
        $("#msg_rpt").fadeOut(5000);
      }
    var message = "<?php echo $message;?>";
    if(message != "") alert(message);
	});

function edit_guest(gid)
{
	var op = "<?=$optionValue?>";
	if (op!="") op = "&option="+op;
	window.location = "my_guests.php?gid="+gid+op;
}

function toggle_new()
{
  $("#new_guest").toggle(500);
}
function cancel_div(did)
{
  $("#"+did).hide(500);
}
function confirmDelete(urls,id)
{
  var gid = "<?=$get["gid"]?>";
  var msg;
  if(gid == id){
    msg = "編集中ですが、削除してよろしいですか？\n現在の入力内容は破棄されます";
  }else{
    msg = "削除してよろしいですか";
  }
  if(confirm(msg))
		{
			var op = "<?=$optionValue?>";
			if (op!="") op = "&option="+op;
	  		window.location = urls+op;
		}
}

function validForm()
{
  var last_name = $("#last_name").val();
  var first_name = $("#first_name").val();
  var respect_id = $("#respect_id").val();
  var guest_type = $("#guest_type").val();
  var comment1 = $("#comment1").val();
  var comment2 = $("#comment2").val();
  var memo = $("#memo").val();

  //var sub_category_id = $("#sub_category_id").val();


  if(last_name == '')
		{
			if (timeOutNow==true) {
				alert("姓が未入力なので、保存できませんでした"); // cronでログアウトされた場合の対応が必要
				location.href = "logout.php";
			}
			alert("姓を入力してください");
			$("#last_name").focus();
			return false;
		}

  if(first_name == '')
		{
			if (timeOutNow==true) {
				alert("名が未入力なので、保存できませんでした"); // cronでログアウトされた場合の対応が必要
				location.href = "logout.php";
			}
			alert("名を入力してください");
			$("#first_name").focus();
			return false;
		}
  var str = document.getElementById("furigana_last").value;
  /*
  if(str=="")
		{
      // UCHIDA EDIT 11/07/27
      //			alert("姓のふりがなを正しく入力してください");
			alert("姓のふりがなを入力してください");
      document.getElementById('furigana_last').focus();
      return false;

		}
*/
  if(str!="")
		{
      // UCHIDA EDIT 11/07/27
      //			if( str.match( /[^ぁ-ん\s]+/ ) ) {
      //				alert("新郎の姓のふりがなを正しく入力してください");
			if( str.match( /[^ぁ-ん\sー]+/ ) ) {
				if (timeOutNow==true) {
					alert("姓のふりがなが正しくないので、保存できませんでした"); // cronでログアウトされた場合の対応が必要
					location.href = "logout.php";
				}
				alert("姓のふりがなを平仮名で正しく入力してください");
				document.getElementById('furigana_last').focus();
				return false;
			}
		}
  var str2 = document.getElementById("furigana_first").value;
 /*
  if(str2=="")
		{
      // UCHIDA EDIT 11/07/27
      //			alert("姓のふりがなを正しく入力してください");
			alert("名のふりがなを入力してください");
      document.getElementById('furigana_first').focus();
      return false;

		}
*/
  if(str2!="")
		{
      // UCHIDA EDIT 11/07/27
      //			if( str2.match( /[^ぁ-ん\sー]+/ ) ) {
      //				alert("新郎の姓のふりがなを正しく入力してください");
			if( str2.match( /[^ぁ-ん\sー]+/ ) ) {
				if (timeOutNow==true) {
					alert("名のふりがなが正しくないので、保存できませんでした"); // cronでログアウトされた場合の対応が必要
					location.href = "logout.php";
				}
		        alert("名のふりがなを平仮名で正しく入力してください");
		        document.getElementById('furigana_first').focus();
		        return false;
			}
		}
  //続けて登録する際に選択させるため。1日だけ保存
  var cookieArray = ["sex","guest_type","gift_group"];
  for(var i=0;i<cookieArray.length;++i){
    var t = cookieArray[i];
    $.cookie('user_'+t+'<?=$HOTELID?>', $("#"+t).val());
  }

  
   //gaiji_check
   var return_flag = true;
   $(".check_sjs_1").each(function(){
       if(return_flag && !checkGaiji($(this).val(),"gaiji_check.php",this)) return_flag = false;
   });
  
  document.newguest.submit();
}

function cancelldiv()
{

  $("#newgustform").animate({height: 'hide', opacity: 'hide'}, 'slow');
  $("#newgustform").css("display","none");
}
function newguestShow()
{
  $("#first_name").val('');
  $("#last_name").val('');
  $("#respect_id").val('');
  $("#guest_type").val('');
  $("#comment1").val('');
  $("#comment2").val('');
  $("#id").val('');
  $("#memo").val('');
  $("#newgustform").animate({height: 'show', opacity: 'show'}, 'slow');
  $("#newgustform").css("display","block");
}

function resetButton()
{
  location.reload();
  $("#sex").val('Male');
  $("#guest_type").val("10");
  $("#gift_group").val('');
  $("#first_name").val('');
  $("#furigana_first").val('');
  $("#comment1").val('');
  $("#menu_grp").val('');
  $("#last_name").val('');
  $("#furigana_last").val('');
  $("#comment2").val('');
  $("#stage").val("0");
  $("#respect_id").val('');
  $("#memo").val('');
  $("#stage_guest").val('');
  //$("#id").val('');
}

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

var now_action = null;
function change_gaiji_link(action)
{
  if(now_action==action) return;
  $(".gaiji_button").hide();
  $("#gaiji_button_"+action).show();
  now_action = action;
}


function get_gaiji_value(from,img,gid,gsid)
{
  //gidと書いてあるがshiftjisのコードを入れている。
	//alert(from);alert(img);	alert(gid);	alert(gsid);
	if(img==""){
    alert("外字が正しく選択されていません。");
    return;
  }
	if(from=="first_name")
    {
      var firstname = $("#first_name").val();
      $("#first_div_id").append("<input type='hidden' name='male_first_gaiji_img[]' value='"+img+"'>");
      $("#first_div_id").append("<input type='hidden' name='male_first_gaiji_gid[]' value='"+gid+"'>");
      $("#first_div_id").append("<input type='hidden' name='male_first_gaiji_gsid[]' value='"+gsid+"'>");

      $("#firstname_img_div_id").append("<img src='../gaiji-image/img_ans/"+img+"' wight='20' height='20'>");
      $("#first_name").attr("value", firstname+"＊");
    }
	if(from=="last_name")
    {
      var lastname = $("#last_name").val();
      $("#last_div_id").append("<input type='hidden' name='male_last_gaiji_img[]' value='"+img+"'>");
      $("#last_div_id").append("<input type='hidden' name='male_last_gaiji_gid[]' value='"+gid+"'>");
      $("#last_div_id").append("<input type='hidden' name='male_last_gaiji_gsid[]' value='"+gsid+"'>");

      $("#lastname_img_div_id").append("<img src='../gaiji-image/img_ans/"+img+"' wight='20' height='20'>");
      $("#last_name").attr("value", lastname+"＊");
    }
	if(from=="comment1")
    {
      var comment1 = $("#comment1").val();
      $("#comment1_div_id").append("<input type='hidden' name='comment1_gaiji_img[]' value='"+img+"'>");
      $("#comment1_div_id").append("<input type='hidden' name='comment1_gaiji_gid[]' value='"+gid+"'>");
      $("#comment1_div_id").append("<input type='hidden' name='comment1_gaiji_gsid[]' value='"+gsid+"'>");

      $("#comment1_img_div_id").append("<img src='../gaiji-image/img_ans/"+img+"' wight='20' height='20'>");
      $("#comment1").attr("value", comment1+"＊");
    }
	if(from=="comment2")
    {
      var comment2 = $("#comment2").val();
      $("#comment2_div_id").append("<input type='hidden' name='comment2_gaiji_img[]' value='"+img+"'>");
      $("#comment2_div_id").append("<input type='hidden' name='comment2_gaiji_gid[]' value='"+gid+"'>");
      $("#comment2_div_id").append("<input type='hidden' name='comment2_gaiji_gsid[]' value='"+gsid+"'>");

      $("#comment2_img_div_id").append("<img src='../gaiji-image/img_ans/"+img+"' wight='20' height='20'>");
      $("#comment2").attr("value", comment2+"＊");
    }


}

function user_timeout() {
	clearInterval(timerId);
	if (changeAction==true) {
		timeOutNow=true;
		var agree = confirm("タイムアウトしました。\n保存しますか？");
	    if(agree==true) {
	    	$("#timeout").val("timeout");
	    	validForm();
	    }
	    else {
	    	window.location = "logout.php";	
	    }
	}
	else {
		alert("タイムアウトしました");
		window.location = "logout.php";	
	}
}

</script>
<style>
.guest_list_table{
  width:918px;
  padding-top:1px;
margin:0px;

}
.guest_list_table td{
  background-color:white;
 }
.guest_list_table .sex{
width:24px;
 }
.guest_list_table .name{
  width:160px;
 }
.guest_list_table .respect{
  width:52px;
}

.guest_list_table .group{
width:36px;
 }
.guest_list_table .comment{
width:170px;
 }
.guest_list_table .table_name{
width:36px;
 }
.guest_list_table .gift{
width:36px;
 }
.guest_list_table .food{
width:145px;
 }
.guest_list_table .memo{
width:100px;
 }
.guest_list_table .action{
width:85px;
 }
</style>

<?php
  /*$editable=true;
    $user_row = $obj->GetSingleRow("spssp_user", " id=".(int)$_SESSION['userid']);

    $date_array=explode("-",$user_row['party_day']);
    $time_array=explode(":",$user_row['party_day_with_time']);

    $gift_criteria_data_row = $obj->GetAllRow("spssp_gift_criteria");

    $confirm_day_num =$gift_criteria_data_row[0]['num_gift_items'];

    $mk_time_before=mktime($time_array[0], $time_array[1], $time_array[2], $date_array[1], $date_array[2]-$confirm_day_num, $date_array[0]);

    $tody_day_mk=mktime(date("H"),date("i"),date("s"), date("m")  , date("d"), date("Y"));

    if($tody_day_mk>=$mk_time_before)
    $editable=false;*/
$editable=$objInfo->get_editable_condition($plan_info);
?>
<link href="css/main.css" rel="stylesheet" type="text/css" />
  <div id="contents_wrapper" class="displayBox">
  <div id="nav_left">
  <div class="step_bt"><a href="table_layout.php"><img src="img/step_head_bt01.jpg" width="150" height="60" border="0" class="on" /></a></div>
  <div class="step_bt"><a href="hikidemono.php"><img src="img/step_head_bt02.jpg" width="150" height="60" border="0" class="on" /></a></div>
  <div class="step_bt"><img src="img/step_head_bt03_on.jpg" width="150" height="60" border="0"/></div>
  <div class="step_bt"><a href="make_plan.php"><img src="img/step_head_bt04.jpg" width="150" height="60" border="0" class="on" /></a></div>
  <div class="step_bt"><a href="order.php"><img src="img/step_head_bt05.jpg" width="150" height="45" border="0" class="on" /></a></div>
  <div class="clear"></div></div>

  <div id="contents_right">
  <div class="title_bar">
  <div class="title_bar_txt_L">招待者リストの作成を行います</div>
  <div class="title_bar_txt_R"></div>
  <div class="clear"></div></div>
	<?php
  
  $edit = false;
  if(isset($get['gid']) && (int)$get['gid'] > 0)
		{
      $edit = true;
			$guest_row = $obj->GetSingleRow(" spssp_guest ", " id=".(int)$get['gid']);

      $query_string = "SELECT * FROM spssp_gaizi_detail_for_guest WHERE guest_id = ".$get['gid'];
      $firstname_gaijis = $obj->getRowsByQuery($query_string." and gu_trgt_type=0 order by gu_char_position");
      $lastname_gaijis = $obj->getRowsByQuery($query_string." and gu_trgt_type=1 order by gu_char_position");
      $comment1_gaijis = $obj->getRowsByQuery($query_string." and gu_trgt_type=2 order by gu_char_position");
      $comment2_gaijis = $obj->getRowsByQuery($query_string." and gu_trgt_type=3 order by gu_char_position");

      function getGaijis($gaiji_objs){
        $returnImage = "";
        for($i=0;$i<count($gaiji_objs);++$i){
          $returnImage .= "<image src='../gaiji-image/img_ans/".$gaiji_objs[$i]["gu_char_img"]."' width='20' height='20'>";
        }
        return $returnImage;
      }
      function getGaijisInputEle($gaiji_objs){
        $html = "";
        for($i=0;$i<count($gaiji_objs);++$i){
          $html .= getHiddenValue( $gaiji_objs[$i]["gu_trgt_type"],$gaiji_objs[$i]["gu_char_img"],$gaiji_objs[$i]["gu_char_setcode"]);
        }
        return $html;
      }
      function getHiddenValue($type,$img,$gid){
        $typeArray = array("male_first","male_last","comment1","comment2");
        $value = $typeArray[$type];
        $html = "";
        $html .= "<input type='hidden' name='".$value."_gaiji_img[]' value='".$img."'>";
        $html .= "<input type='hidden' name='".$value."_gaiji_gid[]' value='".$gid."'>";
        return $html;
      }
      function getAllGaijisInputEle($gaijis){
        $html = "";
        for($i=0;$i<count($gaijis);++$i){
          $html .= getGaijisInputEle($gaijis[$i]);
        }
        return $html;
      }
		}

$gaiji_button_first_name = <<<_EOE_
<a id="gaiji_button_first_name" class="gaiji_button" onclick="m_win(this.href,'mywindow7',500,500); return false;" href="gaiji/palette.php?from=first_name" style="display:none;">
						 <img src="img/btn_gaiji_user.jpg" width="82" height="22">
					</a>
_EOE_;
$gaiji_button_last_name = <<<_EOE_
<a id="gaiji_button_last_name" class="gaiji_button" onclick="m_win(this.href,'mywindow7',500,500); return false;" href="gaiji/palette.php?from=last_name" style="display:none;">
						 <img src="img/btn_gaiji_user.jpg" width="82" height="22">
					</a>
_EOE_;
$gaiji_button_comment1 = <<<_EOE_
<a id="gaiji_button_comment1" class="gaiji_button" onclick="m_win(this.href,'mywindow7',500,500); return false;" href="gaiji/palette.php?from=comment1" style="display:none;">
						 <img src="img/btn_gaiji_user.jpg" width="82" height="22">
					</a>
_EOE_;
$gaiji_button_comment2 = <<<_EOE_
<a id="gaiji_button_comment2" class="gaiji_button" onclick="m_win(this.href,'mywindow7',500,500); return false;" href="gaiji/palette.php?from=comment2" style="display:none;">
						 <img src="img/btn_gaiji_user.jpg" width="82" height="22">
					</a>
_EOE_;


if($editable)
  {
    ?>

    <div id="newgustform" style="width:873px; margin:auto; min-height:100px; padding-top:5px; display:block">
    ■ 招待者名を入力のうえ、各項目の情報を入力してください。<br />
    　<font color="red">*</font>の付いた項目は必須です。
    <?php 
    if ($optionValue!="") $op = "&option=".$optionValue;
    ?>
      <form id="newguest" name="newguest" method="post" action="new_guest.php?page=<?=$_GET['page']?><?=$op?>">
	 <input type="hidden" name="id" id="id" value="<?=$_GET['gid']?>" />
   <?php if($firstname_gaijis || $lastname_gaijis || $comment1_gaijis || $comment2_gaijis) echo getAllGaijisInputEle(array($firstname_gaijis,$lastname_gaijis,$comment1_gaijis,$comment2_gaijis))?>
   
   
   
   
   

<table width="873" border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td width="100" align="right" valign="bottom" nowrap="nowrap">&nbsp;</td>
    <td width="100" align="left" valign="bottom">&nbsp;</td>
    <td width="100" align="right" valign="bottom" nowrap="nowrap">&nbsp;</td>
    <td width="120" align="left" valign="bottom"><div id="lastname_img_div_id">
            <?php if($lastname_gaijis) echo getGaijis($lastname_gaijis);?>
        </div></td>
    <td width="100" align="right" valign="bottom" nowrap="nowrap">&nbsp;</td>
    <td width="120" align="left" valign="bottom"><div id="firstname_img_div_id">
            <?php if($firstname_gaijis) echo getGaijis($firstname_gaijis);?>
          </div></td>
    <td width="100" align="right" valign="bottom" nowrap="nowrap">&nbsp;</td>
    <td width="140" align="left" valign="bottom">&nbsp;</td>
  </tr>
  <tr>
    <td width="100" align="right" nowrap="nowrap">新郎新婦側<font color="red">*</font>：</td>
    <td width="100" align="left"><select id="sex" name="sex" style="width:80px; padding-top:3px; padding-bottom:3px;border-style:inset;" <?php if($guest_row['self']==1){echo "disabled";}?> tabindex=1 onChange="setChangeAction()" onkeydown="keyDwonAction(event)" onClick="clickAction()">
      <?php if($guest_row["sex"]=="") echo "<option value=''></option>";?>
      <option value="Male" <?php if($guest_row['sex']=="Male"){ echo "Selected='Selected'"; }?> >新郎側</option>
      <option value="Female" <?php if($guest_row['sex']=="Female"){ echo "Selected='Selected'"; }?> >新婦側</option>
    </select></td>
    <td width="100" align="right" nowrap="nowrap">姓<font color="red">*</font>：</td>
    <td width="120" align="left"><input type="text" size="20" class="check_sjs_1" tabindex=2 style="padding-top:3px; padding-bottom:3px;border-style: inset;" name="last_name" id="last_name" <?php if($guest_row['self']==1){echo "disabled";}?> value="<?=$guest_row['last_name']?>" onfocus="change_gaiji_link('last_name');" onChange="setChangeAction()" onkeydown="keyDwonAction(event)" onClick="clickAction()"/>
				<div id="last_div_id" style="display:none;"></div>
        <div><?=$gaiji_button_last_name?></div></td>
    <td width="100" align="right" nowrap="nowrap">名<font color="red">*</font>：</td>
    <td width="120" align="left"><input type="text" name="first_name" class="check_sjs_1" size="20" tabindex=3 style="padding-top:3px; padding-bottom:3px;border-style: inset;" id="first_name" <?php if($guest_row['self']==1){echo "disabled";}?> value="<?=$guest_row['first_name']?>" onfocus="change_gaiji_link('first_name')" onChange="setChangeAction()" onkeydown="keyDwonAction(event)" onClick="clickAction()"/>
			  <div id="first_div_id" style="display:none;" ></div>
        <div><?=$gaiji_button_first_name?></div></td>
    <td width="100" align="right" nowrap="nowrap">敬称<font color="red">*</font>：</td>
    <td width="140" align="left"><select id="respect_id" name="respect_id" tabindex=4 style="width:70px; padding-top:3px; padding-bottom:3px;border-style: inset;" <?php if($guest_row['self']==1){echo "disabled";}?> onChange="setChangeAction()" onkeydown="keyDwonAction(event)" onClick="clickAction()">
			      <?php
          if($guest_row["respect_id"]=="") echo "<option value=''></option>";
					foreach($respects as $respect)
					{
						if($guest_row['respect_id'] == $respect['id'])
						{
							$sel = "Selected='Selected'";
						}
						else
						{
							$sel = " ";
						}
						$tl = $respect['title'];
						if ($respect['title']=="なし") $tl = "x";
						echo "<option value='".$respect['id']."'  $sel >".$tl."</option>";
					}
				?>
		        </select></td>
  </tr>
  <tr>
    <td width="100" align="right" nowrap="nowrap">&nbsp;</td>
    <td width="100" align="left">&nbsp;</td>
    <td width="100" align="right" nowrap="nowrap">ふりがな姓：</td>
    <td width="120" align="left"><input type="text" tabindex=5 size="20" style="padding-top:3px;border-style:inset; padding-bottom:3px;" name="furigana_last" id="furigana_last" <?php if($guest_row['self']==1){echo "disabled";}?> value="<?=$furigana_lastname?>" onChange="setChangeAction()" onkeydown="keyDwonAction(event)" onClick="clickAction()"/></td>
    <td width="100" align="right" nowrap="nowrap">ふりがな名：</td>
    <td width="120" align="left"><input type="text" name="furigana_first" tabindex=6 size="20"  style="padding-top:3px; padding-bottom:3px;border-style: inset;" id="furigana_first" <?php if($guest_row['self']==1){echo "disabled";}?> value="<?=$furigana_first_name?>" onChange="setChangeAction()" onkeydown="keyDwonAction(event)" onClick="clickAction()"/></td>
    <td width="100" align="right" nowrap="nowrap">&nbsp;</td>
    <td width="140" align="left">&nbsp;</td>
  </tr>
  <tr>
    <td width="100" align="right" nowrap="nowrap">&nbsp;</td>
    <td width="100" align="left">&nbsp;</td>
    <td width="100" align="right" nowrap="nowrap">&nbsp;</td>
    <td width="120" align="left"><div id="comment1_img_div_id">
            <?php if($comment1_gaijis) echo getGaijis($comment1_gaijis);?>
          </div></td>
    <td width="100" align="right" nowrap="nowrap">&nbsp;</td>
    <td width="120" align="left"><div id="comment2_img_div_id">
            <?php if($comment2_gaijis) echo getGaijis($comment2_gaijis);?>
          </div></td>
    <td width="100" align="right" nowrap="nowrap">&nbsp;</td>
    <td width="140" align="left">&nbsp;</td>
  </tr>
  <tr>
    <td width="100" align="right" nowrap="nowrap">区分：</td>
    <td width="100" align="left"><select id="guest_type" name="guest_type" tabindex=7 style="width:80px; padding-top:3px; padding-bottom:3px;border-style:inset;" <?php if($guest_row['self']==1){echo "disabled";}?> onChange="setChangeAction()" onkeydown="keyDwonAction(event)" onClick="clickAction()">
					<?php
						foreach($guest_types as $guest_type)
						{
							if($guest_row['guest_type'] == $guest_type['id'])
							{
								$sel = "Selected='Selected'";
							}
							else
							{
								$sel = " ";
							}
							echo "<option value='".$guest_type['id']."' $sel>".$guest_type['name']."</option>";
						}
					?>
		    </select></td>
    <td width="100" align="right" nowrap="nowrap">肩書 １行目：</td>
    <td width="120" align="left"><input size="23" name="comment1" tabindex=8 type="text" class="check_sjs_1" id="comment1" style="padding-top:3px; padding-bottom:3px;border-style: inset;" value="<?=$guest_row['comment1']?>" size="10" maxlength="40" <?php if($guest_row['self']==1){echo "disabled";}?>  onfocus="change_gaiji_link('comment1')" onChange="setChangeAction()" onkeydown="keyDwonAction(event)" onClick="clickAction()"/>
							<div id="comment1_div_id" style="display:none;"></div>
              <div><?=$gaiji_button_comment1?></div></td>
    <td width="100" align="right" nowrap="nowrap">肩書 ２行目：</td>
    <td width="120" align="left"><input size="23" name="comment2" tabindex=9 type="text" id="comment2" class="check_sjs_1" style="padding-top:3px; padding-bottom:3px;border-style: inset;" value="<?=$guest_row['comment2']?>" size="10" maxlength="40" <?php if($guest_row['self']==1){echo "disabled";}?>  onfocus="change_gaiji_link('comment2')" onChange="setChangeAction()" onkeydown="keyDwonAction(event)" onClick="clickAction()"/>
							<div id="comment2_div_id" style="display:none;"></div>
            <div><?=$gaiji_button_comment2?></div></td>
    <td width="100" align="right" nowrap="nowrap">特記：</td>
    <td width="140" align="left"><input type="text" tabindex=10 style="padding-top:3px; padding-bottom:3px; width:120px;border-style: inset;"   name="memo" id="memo" maxlength="40" value="<?=$guest_row['memo']?>" onChange="setChangeAction()" onkeydown="keyDwonAction(event)" onClick="clickAction()"/>
  <tr>
    <td width="100" align="right" nowrap="nowrap">引出物<font color="red">*</font>：</td>
    <td width="100" align="left"><?php
                            	$gift_groups = $obj->GetAllRowsByCondition(" spssp_gift_group "," user_id=".$user_id);
								if((int)$_GET['gid'])
								 $guest_gifts = $obj->GetAllRowsByCondition(" spssp_guest_gift "," user_id=".$user_id." and guest_id='".$_GET['gid']."'");

                
								$gg_arr = array();
							    if(is_array($guest_gifts))
								{
									foreach($guest_gifts as $gg)
									{
                    
										$gg_arr[] = $gg['group_id'];
									}

								}


								 if($guest_row['self']==1){$access= "disabled";}
								echo "<select id='gift_group' tabindex=11 name='gift_group_id' style='width:80px; padding-top:3px; padding-bottom:3px; border-style:inset;' onChange='setChangeAction()' onkeydown='keyDwonAction(event)' onClick='clickAction()'>";
                if($edit && count($gg_arr)==0) echo "<option selected value=''></option>";
								foreach($gift_groups as $gg)
								{
                  if($gg["name"]=="") continue;
									$selected = (in_array($gg['id'],$gg_arr))?"selected":"";
									echo "<option ".$selected." value='".$gg['id']."' >".$gg['name']."</option>";
								}
								echo "</select>";
							?></td>
    <td width="100" align="right" nowrap="nowrap">料理<font color="red">*</font>：</td>
    <td width="120" align="left"><?php
                            	$menus = $obj->GetAllRowsByCondition(" spssp_menu_group "," user_id=".$user_id);
								if((int)$_GET['gid'])
								$guest_menus = $obj->GetAllRowsByCondition(" spssp_guest_menu "," user_id=".$user_id." and guest_id=".$_GET['gid']);

								if($guest_row['self']==1){$access= "disabled";}
								$gm_arr = array();
								if(is_array($guest_menus))
								{
									foreach($guest_menus as $gm)
									{
										$gm_arr[] = $gm['menu_id'];
									}
								}
								echo "<select id='menu_grp' tabindex=12 name='menu_grp' style='width:96px; padding-top:3px; padding-bottom:3px;border-style:inset;' onChange='setChangeAction()' onkeydown='keyDwonAction(event)' onClick='clickAction()'>";
								echo "<option value='' >大人</option>";

								foreach($menus as $m)
								{
                  if($m['name']=="") continue;
									$selected = (in_array($m['id'],$gm_arr))?"selected":"";
									echo "<option ".$selected." value='".$m['id']."' >".$m['name']."</option>";
								}
								echo "</select>";

							?>         </td>
    <td width="100" align="right" nowrap="nowrap">席種別<font color="red">*</font>：</td>
    <td width="120" align="left"><select id="stage" tabindex=13 name="stage" style="width:96px;padding-top:3px; padding-bottom:3px;border-style:inset;"  <?php if($guest_row['self']==1){echo "disabled";}?> onchange="stage_enebeled();" onkeydown="keyDwonAction(event)" onClick="clickAction()">
                              <option value="0" <?php if($guest_row['stage']=="0"){ echo "Selected='Selected'"; }?> >招待席</option>
                              <option value="1" <?php if($guest_row['stage']=="1"){ echo "Selected='Selected'"; }?> >高砂席</option>
                            </select></td>
    <td width="100" align="right" nowrap="nowrap">高砂席名：                              <input type="hidden" id="stage_guest_current" value="<?=$guest_row[stage_guest]?>" ></td>
    <td width="140" align="left"><select id="stage_guest" tabindex=14 name="stage_guest" style="width:120px; padding-top:3px; padding-bottom:3px;border-style:inset;" <?php if($guest_row['self']==1 || $guest_row['stage']!="1"){echo "disabled";}?> onChange="setChangeAction()" onkeydown="keyDwonAction(event)" onClick="clickAction()">
                              <option value="">選択してください</option>
                              <?php
							$stage_guest_1 = $obj->GetRowCount("spssp_guest"," user_id=".$user_id." and stage_guest=1");
							$stage_guest_2 = $obj->GetRowCount("spssp_guest"," user_id=".$user_id." and stage_guest=2");
							$stage_guest_3 = $obj->GetRowCount("spssp_guest"," user_id=".$user_id." and stage_guest=3");
							$stage_guest_4 = $obj->GetRowCount("spssp_guest"," user_id=".$user_id." and stage_guest=4");
							$stage_guest_5 = $obj->GetRowCount("spssp_guest"," user_id=".$user_id." and stage_guest=5");
							if(!$stage_guest_1 || $guest_row['stage_guest']=="1"){
							?>
                              <option value="1" <?php if($guest_row['stage_guest']=="1"){ echo "Selected='Selected'"; }?> >媒妁人1</option>
                              <?php
							}
							if(!$stage_guest_2 || $guest_row['stage_guest']=="2"){
							?>
                              <option value="2" <?php if($guest_row['stage_guest']=="2"){ echo "Selected='Selected'"; }?> >媒妁人2</option>
                              <?php
							}
							if(!$stage_guest_3 || $guest_row['stage_guest']=="3"){
							?>
                              <option value="3" <?php if($guest_row['stage_guest']=="3"){ echo "Selected='Selected'"; }?> >媒妁人3</option>
                              <?php
							}
							if(!$stage_guest_4 || $guest_row['stage_guest']=="4"){
							?>
                              <option value="4" <?php if($guest_row['stage_guest']=="4"){ echo "Selected='Selected'"; }?> >媒妁人4</option>
                              <?php
							}
							if(!$stage_guest_5 || $guest_row['stage_guest']=="5"){
							?>
                              <option value="5" <?php if($guest_row['stage_guest']=="5"){ echo "Selected='Selected'"; }?> >お子様</option>
                              <?php
							}
							?>
                            </select></td>
  </tr>
  <tr>
    <td width="100" align="right" nowrap="nowrap">&nbsp;</td>
    <td width="100" align="left">&nbsp;</td>
    <td width="100" align="right" nowrap="nowrap">&nbsp;</td>
    <td width="120" align="left">&nbsp;</td>
    <td width="100" align="right" nowrap="nowrap">&nbsp;</td>
    <td width="120" align="left">&nbsp;</td>
    <td width="100" align="right" nowrap="nowrap">&nbsp;</td>
    <td width="140" align="left" nowrap="nowrap"><a href="my_guests_takasago.html" onclick="m_win(this.href,'mywindow7',520,270); return false;">高砂席位置について</a></td>
  </tr>
  <tr>
  </tr>
</table>
<table width="100%" border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td width="100" nowrap="nowrap">&nbsp;</td>
    <td align="left"><a href="javascript:void(0)" onclick="validForm()" tabindex=15 >
			<img border="0" src="img/btn_regist_update_user.jpg" alt="登録"></a>&nbsp;&nbsp;
			<a href="javascript:void(0)" tabindex=16 <?php if($_GET['gid']=="") {?>onclick="resetButton()" <?php } else  { ?> onclick="window.location='my_guests.php?page=<?=$_GET['page']?>'" <?php } ?> onChange="setChangeAction()" onkeydown="keyDwonAction(event)" onClick="clickAction()">
			<img border="0" src="img/btn_clear_user.jpg" alt="ｸﾘｱ">
			</a>
			</td>
  </tr>
</table>

	   
		<input type="hidden" id="timeout" name="timeout" value="" />
	 </form>
	</div>
	<?php
	}
	?>

  <form id="form1" name="form1" method="post" action="">

<div class="cont_area">
<div align="right">
	<input type="checkbox" tabindex=17 name="sex_sort" <?php if(strstr($optionValue, 'sex')!="") { ?> checked <?php } ?> value="sex" id="sexsearch"  onChange="setChangeAction()" onkeydown="keyDwonAction(event)" onClick="clickAction()"/>新郎新婦側
	<input type="checkbox" tabindex=18 name="guest_type_sort"  <?php if(strstr($optionValue, 'guest_type')!="") { ?> checked <?php } ?> value="guest_type" id="guset_typesearch"  onChange="setChangeAction()" onkeydown="keyDwonAction(event)" onClick="clickAction()"/>区分
  <input type="checkbox"  tabindex=19 name="furigana_first_search"  <?php if(strstr($optionValue, 'furigana_last')!="") { ?> checked <?php } ?> value="furigana_last" id="last_namesearch"  onChange="setChangeAction()" onkeydown="keyDwonAction(event)" onClick="clickAction()"/>姓
	<a href="#" tabindex=20><img border="0" alt="ｸﾘｱ" src="img/btn_sort_user.jpg" onclick="gsearch();"></a>
	<a href="my_guests.php" tabindex=21><img border="0" alt="ｸﾘｱ" src="img/btn_sort_free_user.jpg"></a>
</div>

	<div class="guests_area_L">
	<table class="guest_list_table" border="0" cellpadding="3" cellspacing="1" bgcolor="#999999">
<tr>

  <td class="sex" align="center" bgcolor="#ffffff">席</td>
  <td class="name" align="center" bgcolor="#ffffff">出席者名</td>
  <td class="respect" align="center" bgcolor="#ffffff">敬称</td>
  <td class="group" align="center" bgcolor="#ffffff">区分</td>
  <td class="comment" align="center" bgcolor="#ffffff">肩書</td>
  <td class="table_name" align="center" bgcolor="#ffffff">卓名</td>
  <td class="gift" align="center" bgcolor="#ffffff">引出物</td>
  <td class="food" align="center" bgcolor="#ffffff">料理</td>
  <td class="memo" align="center" bgcolor="#ffffff">特記</td>
  <td class="action" align="center" bgcolor="#ffffff">&nbsp;</td>
</tr>
<?php
//新郎新婦の情報を取得
$userArray = $obj_data->get_userdata($user_id);
for($i=0;$i<count($userArray);++$i){
  $userData = $userArray[$i];
?>
<tr>
  <td class="sex" align="center" bgcolor="#ffffff"><?=$userData["sex_text"]?></td>
  <td class="name" align="left" bgcolor="#ffffff"><?=$userData["name_image"];?></td>
  <td class="respect" align="center" bgcolor="#ffffff">様</td>
  <td class="group" align="center" bgcolor="#ffffff"><?=$userData["sex_text"]?></td>
  <td class="comment" align="center" bgcolor="#ffffff"></td>
  <td class="table_name" align="center" bgcolor="#ffffff"><?=$userData["table_name"]?></td>
  <td class="gift" align="center" bgcolor="#ffffff"><?=$userData["gift_group_text"]?></td>
  <td class="food" align="center" bgcolor="#ffffff"><?=$userData["menu_text"]?></td>
  <td class="memo" align="center" bgcolor="#ffffff"><?=$userData["memo"]?></td>
  <td class="action" align="center" bgcolor="#ffffff">
  <?php if($editable){ ?>
    <input type="button" name="button" tabindex=25 id="button" value="編集" onclick="edit_guest(<?=$userData['id']?>)"/>
  <?php }?>
</tr>
<?
}
?>
</table>
<br>
<table class="guest_list_table" border="0" cellpadding="3" cellspacing="1" bgcolor="#999999">
    	<tr>
            <td class="sex" align="center" bgcolor="#ffffff">席</td>
           <td class="name" align="center" bgcolor="#ffffff">出席者名</td>
            <td class="respect" align="center" bgcolor="#ffffff">敬称</td>
            <td class="group" align="center" bgcolor="#ffffff">区分</td>
            <td class="comment" align="center" bgcolor="#ffffff">肩書</td>
            <td class="table_name" align="center" bgcolor="#ffffff">卓名</td>
            <td class="gift" align="center" bgcolor="#ffffff">引出物</td>
            <td class="food" align="center" bgcolor="#ffffff">料理</td>
            <td class="memo" align="center" bgcolor="#ffffff">特記</td>

			<td class="action" align="center" bgcolor="#ffffff">&nbsp;</td>
</tr></table>
<div style="height:600px;overflow-y:auto;width:940px;">
	<table class="guest_list_table" border="0" cellpadding="3" cellspacing="1" bgcolor="#999999">

<?php


foreach($guests as $guest)
	{
  if($guest['self'] == 1) continue;
  $boyRows='';
  include("admin/inc/main_dbcon.inc.php");
  $respect = $obj->GetSingleData(" spssp_respect ", "title", " id=".$guest['respect_id']);
  if($respect == '')
	 {
		$respect ='×';
	}
  $guest_type = $obj->GetSingleData(" spssp_guest_type ", "name", " id=".$guest['guest_type']);
  include("admin/inc/return_dbcon.inc.php");
$gift_id = $obj->GetSingleData(" spssp_guest_gift ", "group_id", " guest_id=".$guest['id']." and user_id = ".$user_id);

	$gift_name='';
		if((int)$gift_id > 0)
		{
			$gift_group = $obj->GetSingleData(" spssp_gift_group ", "name", " id=".$gift_id);
      $gift_name = $gift_group;
		}

		$menu_id = $obj->GetSingleData(" spssp_guest_menu ", "menu_id", " guest_id=".$guest['id']." and user_id = ".$user_id);
		$menu_name='';
		if($menu_id > 0)
		{
			$menu_name = $obj->GetSingleData(" spssp_menu_group ", "name", " id=".$menu_id." and user_id = ".$user_id);
		}
	  $comment1 = $objInfo->get_user_name_image_or_src_from_user_side($user_id ,$hotel_id=1, $name="comment1.png",$extra="guest/".$guest['id']."/thumb1");
		$comment2 = $objInfo->get_user_name_image_or_src_from_user_side($user_id ,$hotel_id=1, $name="comment2.png",$extra="guest/".$guest['id']."/thumb1");
		if($comment1==false){$comment1 = $guest['comment1'];}
		if($comment2==false){$comment2 = $guest['comment2'];}
	  ?>
		<tr>
      		<td bgcolor="#FFFFFF" class="sex">
<?php
if($guest["sex"] == "Male"){
  echo "新郎";
}else if($guest["sex"] == "Female"){
  echo "新婦";
}
?>
            </td>
             <td align="left" valign="middle" bgcolor="#FFFFFF" class="name">

			 <?php echo $objInfo->get_user_name_image_or_src_from_user_side($user_id ,$hotel_id=1, $name="guest_fullname_only.png",$extra="guest/".$guest['id']."/thumb1");?> </td>
        	<td align="center" valign="middle" bgcolor="#FFFFFF" class="respect"> <? if($respect!="なし") echo $respect; else echo "　　" ?> </td>
        	<td align="center" valign="middle" bgcolor="#FFFFFF" class="group"> <?=$guest_type?> </td>
        	<td align="left" valign="middle" bgcolor="#FFFFFF" class="comment"> <?=$comment1?><br><?=$comment2?> </td>
			<?php
				$plan_details=$obj->getSingleRow("spssp_plan_details"," guest_id=".$guest['id']." limit 1");
				$seat_details=$obj->getSingleRow("spssp_default_plan_seat"," id=".$plan_details['seat_id']." limit 1");
				$table_details=$obj->getSingleRow("spssp_default_plan_table"," id=".$seat_details['table_id']." limit 1");

				$tbl_row = $obj->getSingleRow("spssp_table_layout"," table_id=".$table_details['id']." and user_id=".(int)$user_id." limit 1");
				$new_name_row = $obj->getSingleRow("spssp_user_table"," default_table_id=".$tbl_row['id']." and user_id=".(int)$user_id." limit 1");
				if(!empty($new_name_row))
				{
					$tblname = $obj->getSingleData("spssp_tables_name","name","id=".$new_name_row['table_name_id']);
				}
				else
				{
					$tblname = $tbl_row['name'];
				}
     if($guest["stage"] == 1){
       $tblname = "高砂";
     }
				?>
        	<td align="center" valign="middle" bgcolor="#FFFFFF" class="table_name"><?=$tblname?></td>
        	<td align="center" valign="middle" bgcolor="#FFFFFF" class="gift"> <?=$gift_name?> </td>
        	<td align="center" valign="middle" bgcolor="#FFFFFF" class="food"> <?=$menu_name?>  </td>

        	<td align="left" valign="middle" bgcolor="#FFFFFF" class="memo"><?=$guest['memo']?> </td>
        	<td valign="middle" align="center" bgcolor="#FFFFFF" class="action">
			<?php
			if($editable)
				{
			?>
            	<input type="button" name="button" tabindex=25 id="button" value="編集" onclick="edit_guest(<?=$guest['id']?>)"  onChange="setChangeAction()" onkeydown="keyDwonAction(event)" onClick="clickAction()"/>

				<input name="button" type="button" tabindex=25 value="削除" onclick="confirmDelete('my_guests.php?guest_id=<?=$guest['id']?>&action=delete&page=<?=(int)$_GET['page']?>',<?=$guest["id"]?>)"  onChange="setChangeAction()" onkeydown="keyDwonAction(event)" onClick="clickAction()"/>
				<?php
					}
				?>

            </td>
        </tr>
        <?php
        }
		?>
    </table>
</div>
    </div>

</div>
    <div class="cont_area">

      <br /><br /><br />

      <table width="920" border="0" cellspacing="1" cellpadding="3">
        <tr>
          <td width="50" align="right" nowrap="nowrap"><strong>新郎側：</strong></td>
          <td width="50"  align="left" nowrap="nowrap">
          	<strong>
          	<?php
            	$male_guest_num = $obj->GetNumRows("spssp_guest","user_id=".(int)$_SESSION['userid']." and sex='Male'");
				      echo $male_guest_num;
			      ?>
          	名
            </strong></td>
          <td width="50" align="right" nowrap="nowrap"><strong>新婦側：</strong></td>
          <td width="50"  align="left" nowrap="nowrap">
          	<strong>
          	<?php
            	$female_guest_num = $obj->GetNumRows("spssp_guest","user_id=".(int)$_SESSION['userid']." and sex='Female'");
				      echo $female_guest_num;
			      ?>w
          	名
            </strong></td>

          <td width="50" align="right" nowrap="nowrap"><strong>計：</strong></td>
          <td width="100"  align="left" nowrap="nowrap"><strong>
            <?=$total_record?>
            名</strong></td>
          <td></td>
          <td width="76" rowspan="3" align="center"><a href="javascript:void(0)" onclick="backtotop();">Top</a></td>

          <?php

          	//echo $pagination;

		  ?>
        </tr>
        <tr>

          </td>
          </tr>
        <tr>
          </tr>
      </table>
      <div class="line01"></div>
    </div>
    <br /><br />
    <div class="cont_area">
      <div class="guests_area_L">■ 引出物 商品数
      <?php 
        $group_rows = $obj->GetAllRowsByCondition("spssp_gift_group"," user_id=".$user_id);
		$gift_rows = $obj->GetAllRowsByCondition("spssp_gift"," user_id=".$user_id);
      ?>
        <table width="510" border="0" cellspacing="1" cellpadding="0" bgcolor="#999999">
          <tr>
            <td colspan="2" width="105" align="right" bgcolor="#ffffff">グループ</td>
            <?php
				foreach($group_rows as $grp)
				{
					if ($grp['name']!="") {
					?>
			            <td width="45" align="center" bgcolor="#ffffff"><?=$grp['name']?></td>
		            <?php
					}
            	}
			?>
            <td width="45" align="center" bgcolor="#ffffff">予備</td>
            <td width="45" align="center" bgcolor="#ffffff">合計</td>
          </tr>
        
          <tr >
            <td colspan="2" align="right" bgcolor="#ffffff">グループ数</td>
            <?php
				$total = 0;
            	foreach($group_rows as $grp)
				{
					if ($grp['name']!="") {
						$num_guests_groups = $obj->GetNumRows(" spssp_guest_gift "," user_id = $user_id and group_id = ".$grp['id']);
						$total += $num_guests_groups;
						echo "<td align='center' bgcolor='#ffffff'> $num_guests_groups </td>";
					}
				}
			?>

            <td align="center" bgcolor="#ffffff"> - </td>
            <td bgcolor="#ffffff" align="center"><?=$total?></td>
          </tr>     

          <?php
          echo "<tr>";
          echo "<td bgcolor='#ffffff' align='center' width='45' rowspan='7'>商品名</td>";
          foreach($gift_rows as $gift)
			{
			if ($gift['name']!="") {
				echo "<td bgcolor='#FFFFFF' width='60' align='left' nowrap='nowrap'>".$gift['name']."</td>";
				$num_gifts = 0;
            	foreach($group_rows as $grp)
				{
					if ($grp['name']!="") {
						$gift_ids = $obj->GetSingleData("spssp_gift_group_relation","gift_id", "user_id= $user_id and group_id = ".$grp['id']);
						$gift_arr = explode("|",$gift_ids);
						$groups = array();
	
						if(in_array($gift['id'],$gift_arr))
						{
							array_push($groups,$grp['id']);
						}
	
	          			$num_gifts_in_group = 0;
						if(!empty($groups))
						{
							foreach($groups as $gp)
							{
								$num_guests_groups = $obj->GetNumRows(" spssp_guest_gift "," user_id = $user_id and group_id = ".$gp);
								$num_gifts += $num_guests_groups;
	              				$num_gifts_in_group += $num_guests_groups;
							}
							unset($groups);
						}
	          			$htm = $num_gifts_in_group;
	          			echo "<td width='45' align='center' bgcolor='#FFFFFF'>".$htm."</td>";
	            	}
				}
         	$num_reserve = $obj->GetSingleData("spssp_item_value","value", "item_id = ".$gift["id"]);
          	$num_gifts += $num_reserve;
            echo "<td width='45' align='center' bgcolor='#FFFFFF'>".$num_reserve."</td>";
            echo "<td width='45' align='center' bgcolor='#FFFFFF'>".$num_gifts."</td>";
        	echo "</tr>";
			}
		}
		?>
        </table>

      </div>
      <div class="guests_area_R">■ 引出物　グループ内容
        <table width="243" border="0" cellspacing="1" cellpadding="3" bgcolor="#999999">
        <?php
            	foreach($group_rows as $grp)
				{
					$gift_ids = $obj->GetSingleData("spssp_gift_group_relation","gift_id", "user_id= $user_id and group_id = ".$grp['id']);
					$gift_arr = explode("|",$gift_ids);
					$gift_ids = implode(',',$gift_arr);
					$item_name_arr = getObjectArrayToArray($obj->GetAllRowsByCondition("spssp_gift" , " id in ( $gift_ids )"),"name");
          if(count($item_name_arr) == 0) continue;
          $item_names = implode("<br>",$item_name_arr);

					echo "<tr><td bgcolor='#ffffff' width='30' align='center'>".$grp['name']."</td><td align='letf' width='200' bgcolor='#FFFFFF'>".$item_names."</td></tr>";
				}
			?>
        </table>
      </div>
<div class="clear"></div>
    </div>
<div class="cont_area">

■ 料理数
  <table width="180" border="0" cellspacing="1" cellpadding="3" bgcolor="#999999" style="padding-top:1px">
  <?php
  	$menu_groups = $obj->GetAllRowsByCondition("spssp_menu_group","user_id=".(int)$user_id);
	$num_groups = count($menu_groups);
	$totalsum='';
	$Noofguest = $obj->GetNumRows("spssp_guest","user_id=".(int)$_SESSION['userid']);

	foreach($menu_groups as $mg)
	{
		$num_menu_guest = $obj->GetNumRows("spssp_guest_menu","user_id=$user_id and menu_id=".$mg['id']." and guest_id<>0");

		$totalsum +=$num_menu_guest;
	}
	echo'<tr><td   bgcolor="#ffffff" align="center" >大人</td><td  bgcolor="#FFFFFF" align="center">'.($Noofguest-$totalsum).'</td></tr>';
	foreach($menu_groups as $mg)
	{
		$num_menu_guest = $obj->GetNumRows("spssp_guest_menu","user_id=$user_id and menu_id=".$mg['id']." and guest_id<>0");

		if ($mg['name']!="") {
	  ?>
	     <tr>
	      <td width="120" align="center" bgcolor="#ffffff"  nowrap="nowrap"><?=$mg['name']?></td>
	      <td width="60" align="center" bgcolor="#FFFFFF"  nowrap="nowrap"><?=$num_menu_guest?></td>
	    </tr>
	   <?php
		}
   	}
	echo'<tr><td   bgcolor="#ffffff" align="center" >合計</td><td  bgcolor="#FFFFFF" align="center" >'.$Noofguest.'</td></tr>';
   ?>
  </table>

</div>
  </form>
</div>
</div>
<?php
include("inc/new.footer.inc.php");
?>

<?php
        	if(isset($_GET['err']) && $_GET['err']!='')
			{
				if($_GET['err']==1)
				{
					echo '<script type="text/javascript"> alert("データベースエラー"); </script>';
				}

			}
			else if(isset($_GET['msg']) && $_GET['msg']!='')
			{
				if($_GET['msg']==1)
				{
					echo '<script type="text/javascript"> alert("保存されました"); </script>';
				}
				if($_GET['msg']==2)
				{
					echo '<script type="text/javascript"> alert("変更されました"); </script>';
				}
			}
		?>
