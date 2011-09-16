<<?php
	include_once("admin/inc/dbcon.inc.php");
	include_once("admin/inc/class.dbo.php");
	include_once("inc/checklogin.inc.php");
	$obj = new DBO();
	$get = $obj->protectXSS($_GET);
	$user_id = (int)$_SESSION['userid'];

	$user_layout = $obj->GetNumRows("spssp_table_layout"," user_id= $user_id");
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

	include_once("inc/new.header.inc.php");

	$cats =	$obj->GetAllRowsByCondition('spssp_guest_category',' user_id='.$user_id);

	$plan_id = $obj->GetSingleData("spssp_plan", "id","user_id=".$user_id);

	$plan_row = $obj->GetSingleRow("spssp_plan", " id =".$plan_id);




	$room_rows = $plan_row['row_number'];

	$row_width = $row_width-6;

	$table_width = (int)($row_width/2);
	$table_width = $table_width-6;

	$room_tables = $plan_row['column_number'];
	$room_width = (int)(184*(int)$room_tables)."px";


	$row_width = (int)(182*$room_tables);
	$content_width = ($row_width+235).'px';

	$room_seats = $plan_row['seat_number'];

	$num_tables = $room_rows * $room_tables;

	$tblrows = $obj->getRowsByQuery("select distinct row_order from spssp_table_layout where user_id = ".(int)$user_id);

	$itemids = array();
	$plan_details_row = $obj->GetAllRow("spssp_plan_details"," plan_id=".$plan_id);
	unset($_SESSION['cart']);
	if(!empty($plan_details_row))
	{
		foreach($plan_details_row as $pdr)
		{
			$skey= $pdr['seat_id'].'_input';
			$sval = '#'.$pdr['seat_id'].'_'.$pdr['guest_id'];
			$_SESSION['cart'][$skey]=$sval;
		}
	}


	if(isset($_SESSION['cart']) && !empty($_SESSION['cart']))
	{
		foreach($_SESSION['cart'] as $item)
		{
			if($item)
			{
				$itemArr = explode("_",$item);
				$itemids[] = $itemArr[1];
			}
		}

	}
	include("admin/inc/main_dbcon.inc.php");
	$respects = $obj->GetAllRow( "spssp_respect");
	include("admin/inc/return_dbcon.inc.php");
?>


<link rel="stylesheet" type="text/css" href="css/jquery.treeview.css">

<link href="css/drag_n_drop.css" type="text/css" rel="stylesheet">

<script type="text/javascript">

var title=$("title");
 $(title).html("招待者リストダウンロード - ウエディングプラス");

$(function(){

$("ul#menu li").removeClass();
$("ul#menu li:eq(9)").addClass("active");

var msg_html=$("#msg_rpt").html();

	if(msg_html!='')
	{
		$("#msg_rpt").fadeOut(5000);
	}

});
$(function(){
		$("ul#menu li").removeClass();
		$("ul#menu li:eq(6)").addClass("active");
	});
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
width:182px;
}
.tables p
{
margin-top:0;
margin-bottom:0;
vertical-align:middle;
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
#preview ul
{
margin:2px;
padding:5px 20px;
font-size:13px;
}
#preview ul li
{
margin:2px;
}
.data tr
{
height:45px;
}
</style>

<!--<div id="step_bt_area">
  <div class="step_bt"><a href="table_layout.php"><img src="img/step_head_bt01.jpg" width="155" height="60" border="0" class="on" /></a></div>
  <div class="step_flow_img"><img src="img/step_flow.gif" width="25" height="60" /></div>
  <div class="step_bt"><a href="hikidemono.php"><img src="img/step_head_bt02.jpg" width="155" height="60" border="0" class="on"/></a></div>
  <div class="step_flow_img"><img src="img/step_flow.gif" width="25" height="60" /></div>
  <div class="step_bt"><a href="my_guests.php"><img src="img/step_head_bt03.jpg" width="155" height="60" border="0" class="on" /></a></div>
  <div class="step_flow_img"><img src="img/step_flow.gif" width="25" height="60" /></div>
  <div class="step_bt"><img src="img/step_head_bt04_on.jpg" width="155" height="60" border="0"/></div>
  <div class="step_flow_img"><img src="img/step_flow.gif" width="25" height="60" /></div>
  <div class="step_bt"><a href="order.php"><img src="img/step_head_bt05.jpg" width="155" height="60" border="0" class="on" /></a></div>
  <div class="clear"></div>
</div>-->

<div id="main_contents">
<div class="title_bar">
    <div class="title_bar_txt_L">招待者リストのダウンロード</div>
    <div class="title_bar_txt_R"></div>
<div class="clear"></div></div>

  <!--<div class="title_bar">
    <div class="title_bar_txt_L">席次表の編集、表示、席次の形で引出物、料理の種類を表示します。</div>
    <div class="title_bar_txt_R"></div>
<div class="clear"></div></div>-->

<div class="cont_area">
  <!--<div id="make_plan_bt_area">
    <div class="make_plan_bt"><a href="make_plan.php"><img src="img/make_plan_bt01.jpg" width="155" height="60" class="on" /></a></div>
    <div class="make_plan_bt"><a href="#"><img src="img/make_plan_bt02.jpg" width="155" height="60" class="on" /></a></div>
    <div class="make_plan_bt"><a href="plan_preview.php"><img src="img/make_plan_bt03.jpg" width="155" height="60" class="on" /></a></div>
    <div class="make_plan_bt"><a href="#"><img src="img/make_plan_bt04.jpg" width="155" height="60" class="on" /></a></div>
    <div class="make_plan_bt2"><a href="#"><img src="img/make_plan_bt05.jpg" width="155" height="60" class="on" /></a></div>
  </div>-->

<div class="clear"></div>

<?php
  	if(isset($_GET['msg']) && $_GET['msg'] !='')
	{
		$obj->GetSuccessMsg((int)$_GET['msg']);
	}
	else if(isset($_GET['err']) && $_GET['err'] !='')
	{
		$obj->GetErrorMsg((int)$_GET['err']);
	}

  ?>
</div>

<div class="cont_area">
<div style="font-size:12px;">
招待者リストをエクセル形式（.xls）でダウンロードできます。</div><br />
<table width="700" border="0" cellspacing="1" cellpadding="3">
    <tr>
      <td valign="middle">
      <a href="export_file.php"><img src="img/download_xls.jpg" border="0" /></a>
      </td>
      </tr>
     <tr>
      <td valign="middle"><br /><br /><br /><br /><br /><a href="csvdownload.php">ダウンロード CSV</a></td>
      </tr>

  </table>

</div>
<?php
include("inc/new.footer.inc.php");
?>
?php
	include_once("admin/inc/dbcon.inc.php");
	include_once("admin/inc/class.dbo.php");
	include_once("inc/checklogin.inc.php");
	$obj = new DBO();
	$get = $obj->protectXSS($_GET);
	$user_id = (int)$_SESSION['userid'];

	$user_layout = $obj->GetNumRows("spssp_table_layout"," user_id= $user_id");
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

	include_once("inc/new.header.inc.php");

	$cats =	$obj->GetAllRowsByCondition('spssp_guest_category',' user_id='.$user_id);

	$plan_id = $obj->GetSingleData("spssp_plan", "id","user_id=".$user_id);

	$plan_row = $obj->GetSingleRow("spssp_plan", " id =".$plan_id);




	$room_rows = $plan_row['row_number'];

	$row_width = $row_width-6;

	$table_width = (int)($row_width/2);
	$table_width = $table_width-6;

	$room_tables = $plan_row['column_number'];
	$room_width = (int)(184*(int)$room_tables)."px";


	$row_width = (int)(182*$room_tables);
	$content_width = ($row_width+235).'px';

	$room_seats = $plan_row['seat_number'];

	$num_tables = $room_rows * $room_tables;

	$tblrows = $obj->getRowsByQuery("select distinct row_order from spssp_table_layout where user_id = ".(int)$user_id);

	$itemids = array();
	$plan_details_row = $obj->GetAllRow("spssp_plan_details"," plan_id=".$plan_id);
	unset($_SESSION['cart']);
	if(!empty($plan_details_row))
	{
		foreach($plan_details_row as $pdr)
		{
			$skey= $pdr['seat_id'].'_input';
			$sval = '#'.$pdr['seat_id'].'_'.$pdr['guest_id'];
			$_SESSION['cart'][$skey]=$sval;
		}
	}


	if(isset($_SESSION['cart']) && !empty($_SESSION['cart']))
	{
		foreach($_SESSION['cart'] as $item)
		{
			if($item)
			{
				$itemArr = explode("_",$item);
				$itemids[] = $itemArr[1];
			}
		}

	}
	include("admin/inc/main_dbcon.inc.php");
	$respects = $obj->GetAllRow( "spssp_respect");
	include("admin/inc/return_dbcon.inc.php");
?>


<link rel="stylesheet" type="text/css" href="css/jquery.treeview.css">

<link href="css/drag_n_drop.css" type="text/css" rel="stylesheet">

<script type="text/javascript">

var title=$("title");
 $(title).html("招待者リストダウンロード - ウエディングプラス");

$(function(){

$("ul#menu li").removeClass();
$("ul#menu li:eq(9)").addClass("active");

var msg_html=$("#msg_rpt").html();

	if(msg_html!='')
	{
		$("#msg_rpt").fadeOut(5000);
	}

});
$(function(){
		$("ul#menu li").removeClass();
		$("ul#menu li:eq(6)").addClass("active");
	});
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
width:182px;
}
.tables p
{
margin-top:0;
margin-bottom:0;
vertical-align:middle;
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
#preview ul
{
margin:2px;
padding:5px 20px;
font-size:13px;
}
#preview ul li
{
margin:2px;
}
.data tr
{
height:45px;
}
</style>

<!--<div id="step_bt_area">
  <div class="step_bt"><a href="table_layout.php"><img src="img/step_head_bt01.jpg" width="155" height="60" border="0" class="on" /></a></div>
  <div class="step_flow_img"><img src="img/step_flow.gif" width="25" height="60" /></div>
  <div class="step_bt"><a href="hikidemono.php"><img src="img/step_head_bt02.jpg" width="155" height="60" border="0" class="on"/></a></div>
  <div class="step_flow_img"><img src="img/step_flow.gif" width="25" height="60" /></div>
  <div class="step_bt"><a href="my_guests.php"><img src="img/step_head_bt03.jpg" width="155" height="60" border="0" class="on" /></a></div>
  <div class="step_flow_img"><img src="img/step_flow.gif" width="25" height="60" /></div>
  <div class="step_bt"><img src="img/step_head_bt04_on.jpg" width="155" height="60" border="0"/></div>
  <div class="step_flow_img"><img src="img/step_flow.gif" width="25" height="60" /></div>
  <div class="step_bt"><a href="order.php"><img src="img/step_head_bt05.jpg" width="155" height="60" border="0" class="on" /></a></div>
  <div class="clear"></div>
</div>-->

<div id="main_contents">
<div class="title_bar">
    <div class="title_bar_txt_L">招待者リストのダウンロード</div>
    <div class="title_bar_txt_R"></div>
<div class="clear"></div></div>

  <!--<div class="title_bar">
    <div class="title_bar_txt_L">席次表の編集、表示、席次の形で引出物、料理の種類を表示します。</div>
    <div class="title_bar_txt_R"></div>
<div class="clear"></div></div>-->

<div class="cont_area">
  <!--<div id="make_plan_bt_area">
    <div class="make_plan_bt"><a href="make_plan.php"><img src="img/make_plan_bt01.jpg" width="155" height="60" class="on" /></a></div>
    <div class="make_plan_bt"><a href="#"><img src="img/make_plan_bt02.jpg" width="155" height="60" class="on" /></a></div>
    <div class="make_plan_bt"><a href="plan_preview.php"><img src="img/make_plan_bt03.jpg" width="155" height="60" class="on" /></a></div>
    <div class="make_plan_bt"><a href="#"><img src="img/make_plan_bt04.jpg" width="155" height="60" class="on" /></a></div>
    <div class="make_plan_bt2"><a href="#"><img src="img/make_plan_bt05.jpg" width="155" height="60" class="on" /></a></div>
  </div>-->

<div class="clear"></div>

<?php
  	if(isset($_GET['msg']) && $_GET['msg'] !='')
	{
		$obj->GetSuccessMsg((int)$_GET['msg']);
	}
	else if(isset($_GET['err']) && $_GET['err'] !='')
	{
		$obj->GetErrorMsg((int)$_GET['err']);
	}

  ?>
</div>

<div class="cont_area">
<div style="font-size:12px;">
招待者リストをエクセル形式（.xls）でダウンロードできます。</div><br />
<table width="700" border="0" cellspacing="1" cellpadding="3">
    <tr>
      <td valign="middle">
      <a href="export_file.php"><img src="img/download_xls.jpg" border="0" /></a>
      </td>
      </tr>
     <tr>
      <td valign="middle"><br /><br /><br /><br /><br /><!--<a href="csvdownload.php">ダウンロード CSV</a>--></td>
      </tr>

  </table>

</div>
<?php
include("inc/new.footer.inc.php");
?>
