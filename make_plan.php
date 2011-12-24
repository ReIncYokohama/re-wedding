<?php
	include_once("admin/inc/class_information.dbo.php");
	include_once("inc/checklogin.inc.php");
	$obj = new DBO();
	$objInfo = new InformationClass();
	$get = $obj->protectXSS($_GET);
	$user_id = (int)$_SESSION['userid'];

	$user_layout = $obj->GetNumRows("spssp_table_layout"," user_id= $user_id");

	$user_info = $obj->GetSingleRow("spssp_user"," id=".$user_id);
	if($user_layout <= 0)
	{
		redirect('table_layout.php?err=13');
	}
	$user_guest = $obj->GetNumRows("spssp_guest"," user_id= $user_id");
	if($user_guest <= 0)
	{
		redirect('my_guests.php?err=14');
	}

	$plan_criteria = $obj->GetNumRows("spssp_plan"," user_id= $user_id");
	if($plan_criteria <= 0)
	{
		redirect('table_layout.php?err=15');
	}


  //tabの切り替え
  $tab_make_plan = true;

	include_once("inc/new.header.inc.php");

	$plan_id = $obj->GetSingleData("spssp_plan", "id","user_id=".$user_id);

	$plan_row = $obj->GetSingleRow("spssp_plan", " id =".$plan_id);

	$permission_table_edit = $obj->GetSingleData("spssp_plan", "rename_table"," user_id =".$user_id);
	$plan_info = $obj ->GetSingleRow("spssp_plan"," user_id=".$user_id);
	$editable=$objInfo->get_editable_condition($plan_info);
	if($permission_table_edit==1 && $editable) $button_enable=true; else $button_enable=false;

	$room_rows = $plan_row['row_number'];

	$row_width = $row_width-6;

	$table_width = (int)($row_width/2);
	$table_width = $table_width-6;

	$room_tables = $plan_row['column_number'];
	$room_width = (int)(215*(int)$room_tables)."px";


	$row_width = (int)(213*$room_tables);
	$content_width = ($row_width+235).'px';

	$room_seats = $plan_row['seat_number'];

	$num_tables = $room_rows * $room_tables;

	$tblrows = $obj->getRowsByQuery("select distinct row_order from spssp_table_layout where user_id = ".(int)$user_id);


	$itemids = array();

	$num_rowws = $obj->GetNumRows("spssp_plan_details"," plan_id=".$plan_id);

	$plan_details_row = $obj->GetAllRowsByCondition("spssp_plan_details"," plan_id=".$plan_id);

	if(isset($_SESSION['cart']))
	{

	}
	else if(!empty($plan_details_row))
	{
		$i=0;
		foreach($plan_details_row as $pdr)
		{

			$i++;
			$skey= $pdr['seat_id'].'_input';
			$sval = '#'.$pdr['seat_id'].'_'.$pdr['guest_id'];
			$_SESSION['cart'][$skey]=$sval;
		}
	}



	if(isset($_SESSION['cart']))
	{
		foreach($_SESSION['cart'] as $item)
		{
			if($item)
			{
				$itemArr = explode("_",$item);

				$seatids[]=str_replace("#","",$itemArr[0]);
				$itemids[] = $itemArr[1];
			}
		}

	}



	include("admin/inc/main_dbcon.inc.php");
	$respects = $obj->GetAllRow(" spssp_respect");
	include("admin/inc/return_dbcon.inc.php");
?>
<script>
var edited_Flag=0;
</script>
<script src="js/jquery.ui.droppable.js" type="text/javascript"></script>


<link rel="stylesheet" type="text/css" href="css/jquery.treeview.css">

<script src="js/jquery.cookie.js" type="text/javascript"></script>
<script type="text/javascript" src="js/jquery.tipTip.js"></script>
<link href="js/tipTip.css" rel="stylesheet" type="text/css" />
<script src="js/jquery.treeview.js" type="text/javascript"></script>
<link href="css/drag_n_drop.css" type="text/css" rel="stylesheet">
<?php
if($objInfo->get_editable_condition($plan_row))
	{
?>
<script src="js/drag_n_drop.js" type="text/javascript"></script>
<?php
	}
?>
<script src="js/rp.js" type="text/javascript"></script>


<script type="text/javascript">

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


var title=$("title");
 $(title).html("席次表編集 - 席次表 - ウエディングプラス");

$(function(){

$("ul#menu li").removeClass();
$("ul#menu li:eq(4)").addClass("active");

var msg_html=$("#msg_rpt").html();

	if(msg_html!='')
	{
		$("#msg_rpt").fadeOut(5000);
	}

});
function checkConfirm()
{
	var conf;
	conf = confirm('修正内容を保存しても宜しいですか？');
	if(conf)
	{
		document.insert_plan.submit();
	}

}
function make_plan_check()
{
var button_enable="<?=$button_enable?>";
	if(button_enable==true) {
		if (edited_Flag==1) { // 変更したか
			if(confirm("席次表を保存しますか？"))
			{
				$.post('ajax/insert_plan.php',{'make_plan':'true'}, function (data){
					return true;
				});
			}
			else
			{
				$.post('ajax/unset_plan.php',{'make_plan':'true'}, function (data){
					return true;
				});
			}
		}
		else {
			$.post('ajax/unset_plan.php',{'make_plan':'true'}, function (data){
				return true;
			});
		}
	}
}
</script>
<style>
.rows
{
float:left;

clear:both;
width:100%;
}
.tables
{
float:left;
width:198px;
margin-left:15px;
}
.tables p
{
margin-top:0;
margin-bottom:0;
vertical-align:middle;
}

table a:link {
	color: #F90;
	text-decoration:underline;
}
table a:visited {
	color: #F90;
	text-decoration:underline;
}
table a:hover {
	color: #C30;
	text-decoration:underline;
}
table a:active {
	color: #C30;
	text-decoration:underline;
}



#room
{

width:auto;

}
.droppable
{
width:80px;
height:30px;
}
</style>

<div id="contents_wrapper" class="displayBox">
<div id="nav_left">
  <div class="step_bt"><a href="table_layout.php" onclick="return make_plan_check();"><img src="img/step_head_bt01.jpg" width="150" height="60" border="0" class="on" /></a></div>
  <div class="step_bt"><a href="hikidemono.php" onclick="return make_plan_check();"><img src="img/step_head_bt02.jpg" width="150" height="60" border="0" class="on"/></a></div>
  <div class="step_bt"><a href="my_guests.php" onclick="return make_plan_check()"><img src="img/step_head_bt03.jpg" width="150" height="60" border="0" class="on" /></a></div>
  <div class="step_bt"><img src="img/step_head_bt04_on.jpg" width="150" height="60" border="0"/></div>
  <div class="step_bt"><a href="order.php" onclick="return make_plan_check()"><img src="img/step_head_bt05.jpg" width="150" height="45" border="0" class="on" /></a></div>
  <div class="clear"></div></div>

<div id="contents_right">


<div id="box_1">
		<div class="title_bar">
			<div class="title_bar_txt_L">席次表を編集し、プレビューで席次表・引出物の確認をします</div>
			<div class="title_bar_txt_R"></div>
			<div class="clear"></div>
		</div>
		<div class="cont_area"><br />

			<table width="800" border="0 cellspacing="1" cellpadding="3">
				  <tr>
					<td width="210" valign="middle"><a href="make_plan_full.php"><img src="img/order/btn_sekiji.jpg" alt="席次表の編集" width="200" height="60" border="0" class="on" /></a></td>
					<td width="10" valign="middle">　</td>

					<td width="580" valign="middle">席次表をドラッグ＆ドロップで簡単に編集ができます。<br />
※新規ウィンドウが開きます。</td>

			  </tr>
		  </table>
				  <tr>
				    <td colspan="3" valign="middle">&nbsp;</td>
		  </tr>
	  </div>
	</div>



    
    
<div id="box_2" style="height:auto;">
		<div class="title_bar">
		  <div class="title_bar_txt_L">席次表の編集結果の確認</div>
		  <div class="title_bar_txt_R"></div>
		  <div class="clear"></div>
		</div>
		<div class="cont_area"><br />

			  <table width="800" border="0" cellspacing="1" cellpadding="3">
				  <tr>
					<td width="210" valign="middle"><a href="plan_pdf_small.php" target="_blank"><img src="img/order/preview_sekiji_bt.jpg" alt="席次表プレビュー" width="200" height="40" border="0" class="on" /></a></td>
					<td width="10" valign="middle">　</td>
					<td width="580" valign="middle">プレビューで「席次表の編集」で配席したレイアウトがご確認いただけます。</td>
				  </tr>
			 </table>
				  <tr>
				    <td colspan="3" valign="middle">&nbsp;</td>
				  </tr>
		</div>
	</div>


    
    
<div id="box_2" style="height:auto;">
		<div class="title_bar">
		  <div class="title_bar_txt_L">招待者への引出物の確認</div>
		  <div class="title_bar_txt_R"></div>
		  <div class="clear"></div>
		</div>
		<div class="cont_area"><br />

			  <table width="800" border="0" cellspacing="1" cellpadding="3">
				  <tr>
					<td width="210" valign="middle"><a href="plan_pdf.php" target="_blank"><img src="img/order/preview_hikidemono_bt.jpg" alt="席次表プレビュー" width="200" height="40" border="0" class="on" /></a></td>
					<td width="10" valign="middle">　</td>
					<td width="580" valign="middle">プレビューで「引出物・料理の登録」、「招待者リストの作成」で編集した招待者への引出物がご確認いただけます。</td>
				  </tr>
			 </table>
				  <tr>
				    <td colspan="3" valign="middle">&nbsp;</td>
				  </tr>
		</div>
	</div>

    
    
<div id="box_2" style="height:auto;">
<div class="title_bar">
		  <div class="title_bar_txt_L">席次表の印刷イメージの確認</div>
		  <div class="title_bar_txt_R"></div>
		  <div class="clear"></div>
		</div>
		<div class="cont_area"><br />

			  <table width="800" border="0" cellspacing="1" cellpadding="3">
				  <tr>
				    <?php 
				    if ($obj->GetRowCount("spssp_plan"," admin_to_pcompany >= 2 and `ul_print_com_times` < 2 and `order` >= 1 and user_id=".$user_id) && $user_info['party_day'] >= date("Y-m-d")) {
				    ?>
				    	<td width="210" valign="middle"><a href="<?=substr($plan_info['p_company_file_up'], 3)?>" target="_blank"><img src="img/order/preview_print_bt.jpg" alt="席次表プレビュー" width="200" height="40" border="0" class="on"/></a></td>
				    <?php 
				    } 
				    else { 
				    ?>
				    	<td width="210" valign="middle"><img src="img/order/preview_print_bt_gray.jpg" alt="席次表プレビュー" width="200" height="40" border="0" /></td>
				    <?php 
				    } ?>
					<td width="10" valign="middle">　</td>
					<td width="580" valign="middle"><p>印刷会社よりアップロードされた「席次表の印刷イメージ」がご確認いただけます。<br />※印刷会社よりアップロードされるまでは、ボタンは使用できません。</p></td>
				  </tr>
			 </table>
				  <tr>
				    <td colspan="3" valign="middle">&nbsp;</td>
				  </tr>
		</div>
	</div>

<?php
include("inc/new.footer.inc.php");
?>
