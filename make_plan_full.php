<?php
include_once("admin/inc/class_information.dbo.php");
include_once("admin/inc/class_data.dbo.php");
include_once("inc/checklogin.inc.php");

//include_once("fuel/load_fuel.php");
include_once("fuel/load_classes.php");

$obj = new DataClass();
$objInfo = new InformationClass();
$get = $obj->protectXSS($_GET);
//編集が終わっていない項目があれば、その項目のあるページに移動
$user_id = Core_Session::get_user_id();
if(!Model_Tablelayout::exist($user_id)){
  Response::redirect("table_layout.php?err=13");
}
if(!Model_Guest::exist($user_id)){
  Response::redirect("table_layout.php?err=14");
}
$plan = Model_Plan::find_one_by_user_id($user_id);

if(!$plan){
  Response::redirect("table_layout.php?err=15");
}


$plan_id = $plan->id;
$plan_row = $plan->to_array();

//席次表編集画面の編集ができるかどうかチェック
if($plan->authority_rename_table()) $button_enable=true; else $button_enable=false;

$room_seats = $plan_row['seat_number'];

// task memo
$tblrows = Model_Tablelayout::find_rows_distinct_order($user_id);

//sessionのみに保存されている席情報を保存する。
$cart = $plan->get_seat_data_in_session();
list($itemids,$seatids) = $plan->get_seat_data_ids();

//guest_typeのidがkeyでnameがvalue
$types_guest = Model_Guesttype::hash("id","name");

//fuelのdbを使わないとき、databaseが切り替わってします。
include("admin/inc/return_dbcon.inc.php");

//guestをソートするためのキーをGETから取得
$sort_property = Model_Guest::get_sort_property();

//takasago席でないゲストをソートして取得。
$guest_models = Model_Guest::find_by_not_takasago($user_id,array($sort_property["sortby"]=>$sort_property["direction"]));
$guests = Core_Arr::func($guest_models,"to_array");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>

<link href="css/tmpl.css" rel="stylesheet" type="text/css" />
<link href="css/tate-style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="tmp_js/cufon-yui.js"></script>
<script type="text/javascript" src="tmp_js/arial.js"></script>
<script type="text/javascript" src="tmp_js/cuf_run.js"></script>
<link rel="stylesheet" href="css/base/jquery.ui.all.css">
<script src="js/jquery-1.7.1.min.js"></script>
<script src="js/external/jquery.bgiframe-2.1.1.js"></script>
<script src="js/ui/jquery.ui.core.js"></script>
<script src="js/ui/jquery.ui.widget.js"></script>
<script src="js/ui/jquery.ui.mouse.js"></script>
<script src="js/ui/jquery.ui.button.js"></script>
<script src="js/ui/jquery.ui.draggable.js"></script>
<script src="js/ui/jquery.ui.position.js"></script>
<script src="js/ui/jquery.ui.resizable.js"></script>
<script src="js/ui/jquery.effects.core.js"></script>
<script src="js/ui/jquery.effects.blind.js"></script>
<script src="js/ui/jquery.effects.fade.js"></script>
<script src="js/jquery.effects.explode.js"></script>
<script src="js/jquery.effects.shake.js"></script>
<script src="js/jquery.rollover.js"></script>
<script src="js/ui/jquery.ui.dialog.js"></script>
<script src="js/jquery.cookie.js"></script>
<script type="text/javascript">
	var edited_Flag=0;
	var timerlength="<?=TIMEOUTLENGTH?>";
	var timerId;
	var timeOutNow=false;

	clearInterval(timerId);
	timerId = setInterval('user_timeout()', timerlength);

	function user_timeout() {
		var button_enable="<?=$button_enable?>";
		clearInterval(timerId);
		if (button_enable==true && edited_Flag==1) {
			timeOutNow=true;
			var agree = confirm("タイムアウトしました。\n保存しますか？");
		    if(agree==true) {
		    	$("#timeout").val("timeout");
		    	checkConfirm();
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

<script type="text/javascript">
var moveCount=0;
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
$(function() {
    $(".displayBox").mousemove(function(){
        if (moveCount>100) {
			clearInterval(timerId);
			timerId = setInterval('user_timeout()', timerlength);
			moveCount=0;
	        return false;
        }
        moveCount++;
   });
});
</script>

<link href="css/main.css" rel="stylesheet" type="text/css" />
</head>
<body>

<script src="js/jquery.ui.droppable.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="css/jquery.treeview.css">

<script type="text/javascript" src="js/jquery.tipTip.js"></script>
<link href="js/tipTip.css" rel="stylesheet" type="text/css" />
<script src="js/jquery.treeview.js" type="text/javascript"></script>
<link href="css/drag_n_drop.css" type="text/css" rel="stylesheet">
<?php
  if($plan->editable())
	{
?>
<script src="js/drag_n_drop.js" type="text/javascript"></script>
<?php
	}
?>
<script src="js/rp.js" type="text/javascript"></script>

<style>
.make_plan_main_contents{
  width:1220px;
}
.make_plan_main_title{
  width:1220px;
}
.make_plan_main_left{
  width:270px;
  float:left;
}
.make_plan_main_right{
  width:900px;
  float:left;
}
#contents_right{
width:1220px;
}
#contents_wrapper{
width:1200px;
padding:0px;
margin:0px;
}
body{
  background:none;
}
#make_plan_table{
width: 965px;
/*overflow:scroll;
height:680px;*/
}
.title_bar.main_plan{
 width:980px;
}
</style>

<script type="text/javascript">
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
var button_enable="<?=$button_enable?>";
	var conf;
	if (timeOutNow==true) {
		if(button_enable==true && edited_Flag==1)
		document.insert_plan.submit();
	}
	conf = confirm('修正内容を保存しても宜しいですか？');
	if(conf)
	{
		document.insert_plan.submit();
    //window.close();
	}
  
}
function back_to_make_plan() {
  location.href = "cancel_default_plan.php";  
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

function confirmBack(){
	if(edited_Flag==1){
		if(confirm("内容が変更されています。保存しても宜しいですか？")){
			$.post('ajax/insert_plan.php',{'make_plan':'true'}, function (data){
					return true;
				});
		}else{
			$.post('ajax/unset_plan.php',{'make_plan':'true'}, function (data){
					return true;
				});
		}
	}
	window.location = 'make_plan.php';
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
.seat_droppable
{
width:80px;
height:30px;
float:left;
margin:0px;
padding:0px;
}
.vertical {
width: 430px;
height: 180px;
writing-mode: tb-rl;
-webkit-writing-mode : vertical-rl ; 
-moz-writing-mode: vertical-rl;
direction: ltr;
 }
</style>

<div id="contents_wrapper" class="displayBox">

<div id="contents_right">
  <div class="title_bar main_plan">
    <div class="title_bar_txt_L">席次表を編集</div>
<div class="clear"></div></div>
<div class="cont_area"  align="center">
</div>

<div class="make_plan_main_contents" id="con_area_ie">
  <div id="side_area" sytle="padding-right:0px;width:350px;">
  <div align="right"><a href="make_plan_full.php"><image src="img/btn_sort_free_user.jpg"></a></div>
  <div  id="guests_conatiner" style="float:left; height:710px; width:100%; overflow-x:hidden;overflow-y:scroll;" >
				<table width="98%">
					<tr bgcolor="#666666" style="color:#FFFFFF"><th>No</th><th nowrap="nowrap"><a href="make_plan_full.php?sortby=sex&direction=<?=$sort_property["sex_direction"]?>">郎婦↓</a></th><th nowrap="nowrap"><a href="make_plan_full.php?sortby=guest_type&direction=<?=$sort_property["guest_type_direction"]?>">区分↓</a></th><th align="center">&nbsp;&nbsp;姓&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;名&nbsp;&nbsp;</th><th nowrap="nowrap" align="left">卓名</th></tr>
					<?php

				foreach($guests as $guest)
						{
							$no++;
							if($no%2==0)
							{
								$bgcolor="#EEEEEE";
							}
							else
							{
								$bgcolor="#F5F8E5";
							}
						  $tblname="";
							if(in_array($guest['id'],$itemids))
							{
								$class = "dragfalse";
								$src = "img/icon01.jpg";
								$style = "style = 'display:none'";
								$key=array_search($guest['id'],$itemids);

								$seat_details=$obj->getSingleRow("spssp_default_plan_seat"," id=".$seatids[$key]." limit 1");
								$table_details=$obj->getSingleRow("spssp_default_plan_table"," id=".$seat_details['table_id']." limit 1");

								$tbl_row = $obj->getSingleRow("spssp_table_layout"," table_id=".$table_details['id']." and user_id=".(int)$user_id." limit 1");
                /*
								$new_name_row = $obj->getSingleRow("spssp_user_table"," default_table_id=".$tbl_row['id']." and user_id=".(int)$user_id." limit 1");
                print_r($new_name_row);
								if(!empty($new_name_row))
								{
									$tblname = $obj->getSingleData("spssp_tables_name","name","id=".$new_name_row['table_name_id']);
									$tblname=mb_substr ($tblname, 0,1,'UTF-8');
								}
								else
								{
									$tblname = $tbl_row['name'];
									$tblname=mb_substr ($tblname, 0,1,'UTF-8');
                  }*/
                $tblname = $tbl_row['name'];
                $tblname=mb_substr ($tblname, 0,2,'UTF-8');


							}
							else
							{
								$class = "ui-widget-content ui-corner-tr";
								$src = "img/icon02.jpg";
								$style = "style = 'display:block'";
							}
              $rsp = $obj->get_respect($guest["respect_id"]);

							$edited_num = $obj->GetNumRows("spssp_guest", "edit_item_id=".$guest['id']." and user_id=".(int)$user_id);
							//echo '<h1>'.$edited_num.'</h1>';
							if($edited_num > 0)
							{
								$guest_edited = $obj->GetSingleRow("spssp_guest", "edit_item_id=".$guest['id']." and user_id=".$user_id);
								$guest['id']=$guest_edited['id'];
								$guest['sub_category_id']=$guest_edited['sub_category_id'];
								$guest['name']=$guest_edited['name'];
							}
							$gname = $guest['first_name']."sada ".$guest['last_name']." ".$rsp;
							$guest_comment=$guest['comment1']."&nbsp;".$guest['comment2'];
							$name_length = mb_strlen($gname,"utf-8");

							$name_length2 = mb_strlen($guest_comment,"utf-8");

							if($name_length >8)
							{
								if($name_length >=8 && $name_length <= 12)
								{
									$fsize = '68%';

								}
								else if($name_length >=12 && $name_length <= 14)
								{
									$fsize = '60%';

								}
								else if($name_length >=15 && $name_length <= 17)
								{
									$fsize = '50%';

								}
								else if($name_length >=18 && $name_length <= 20)
								{
									$fsize = '45%';

								}
								else if($name_length >=21 && $name_length <= 23)
								{
									$fsize = '40%';

								}
								else if($name_length >=24 && $name_length <= 26)
								{
									$fsize = '35%';

								}
								else if($name_length >=27 && $name_length <= 29)
								{
									$fsize = '30%';

								}
								else if($name_length >=30 && $name_length <= 40)
								{
									$fsize ='25%';

								}
								else if($name_length >=41 && $name_length <= 50)
								{
									$fsize ='20%';

								}
								else
								{
									$fsize ='15%';

								}
							}
							else
							{
								$fsize = '85%';
							}

					?>
					<tr bgcolor="<?=$bgcolor?>">
					<td><?=$no?></td>
              <td><?=($guest['sex']=='Male')?"新郎":(($guest['sex']=='Female')?"新婦":"");?></td>
					<td><?=$types_guest[$guest['guest_type']];?></td>

					<td width="90" align="center" style="text-align:left;">


					 <div  id="tst" style="padding:0;width:100%;text-align:left;">
							<div class="<?=$class?>" id="item_<?=$guest['id']?>"  style="width:100px; padding:0;text-align:left;">
								<a href="javascript:void(0)" style="color:black; display:block;" id="tip_<?=$guest['id']?>" class="tooltip" title="<span style='font-size:14px'><image src='<?php echo $objInfo->get_user_name_image_or_src_from_user_side_make_plan($user_id ,$hotel_id=1, $name='namecard.png',$extra='guests/'.$guest['id'].'/');?>'></span>" >
								<input type="hidden" value="<?php echo $objInfo->get_user_name_image_or_src_from_user_side_make_plan($user_id ,$hotel_id=1, $name="full_comment.png",$extra="guests/".$guest['id']."/thumb1");?>" class="comeent1_hidden" />
								<input type="hidden" value="<?php echo $objInfo->get_user_name_image_or_src_from_user_side_make_plan($user_id ,$hotel_id=1, $name="guest_fullname.png",$extra="guests/".$guest['id']."/thumb1");?>" class="guest_name_hidden" />
              <image src='<?php echo $objInfo->get_user_name_image_or_src_from_user_side_make_plan($user_id ,$hotel_id=1, $name="guest_fullname.png",$extra="guests/".$guest['id']."/thumb2");?>' />
								</a>
							</div>

						</div>

					</td>
					<td id="tablename_<?=$guest['id']?>"><?=$tblname?></td></tr>

					<?php
						}
					?>
				</table>




        </div>


  </div>

  <div class="make_plan_main_right">
  	<form action="insert_default_plan.php?user_id=<?=(int)$user_id?>&plan_id=<?=$plan_id?>" method="post" id="insert_plan" name="insert_plan">
<input type="hidden" name="query" value="<?php echo $_SERVER["QUERY_STRING"];?>">
  			 <div align="right">
<?php
if($objInfo->get_editable_condition($plan_row))
	{
?>
<image src="img/btn_save_user.jpg" id="button" onclick="checkConfirm()"/>
<image src="img/btn_rollback_user.jpg" id="button" onclick="back_to_make_plan()"/>
<?php
  }
?>
<image src="img/btn_back_user.jpg" id="button" onclick="confirmBack();"/>
            </div>
			<?php

                       $table_rows = $obj->getRowsByQuery("select * from spssp_table_layout where user_id = ".(int)$user_id." and row_order=".$tblrows[0]['row_order']." order by  column_order asc");
                       $ralign = $obj->GetSingleData("spssp_table_layout", "align"," row_order=".$tblrows[0]['row_order']." and user_id=".$user_id." limit 1");

                       $num_first = $obj->GetSingleData("spssp_table_layout", "column_order "," display=1 and user_id=".$user_id." and row_order=".$tblrows[0]['row_order']." order by column_order limit 1");
                       $num_last = $obj->GetSingleData("spssp_table_layout", "column_order "," display=1 and user_id=".$user_id." and row_order=".$tblrows[0]['row_order']." order by column_order desc limit 1");
                       $num_max = $obj->GetSingleData("spssp_table_layout", "column_order "," user_id=".$user_id." and row_order=".$tblrows[0]['row_order']." order by column_order desc limit 1");
                       $num_none = $num_max-$num_last+$num_first-1;
                       $width = $num_max*200;



$takasago_guest_obj = $plan->get_takasago_guest_obj();
foreach($takasago_guest_obj as $guest){
  $namecard_url = $guest->get_image("namecard.png");
  $fullname_url = $guest->get_image("thumb1/guest_fullname.png");  
  $guest->_td_html = '<td align="center" class="tooltip" title="<image src=\''.$namecard_url.'\'>" valign="middle" style="text-align:center; padding:7px;width:100px;"><image src="'.$fullname_url.'"></td>';
}

$html.='
<table align="center" cellspacing="2"><tr>
'.($takasago_guest_obj[3]?$takasago_guest_obj[3]->_td_html:"").'
'.($takasago_guest_obj[1]?$takasago_guest_obj[1]->_td_html:"").'
'.($takasago_guest_obj["left"]?$takasago_guest_obj["left"]->_td_html:"").'
'.($takasago_guest_obj[5]?$takasago_guest_obj[5]->_td_html:"").'
'.($takasago_guest_obj["right"]?$takasago_guest_obj["right"]->_td_html:"").'
'.($takasago_guest_obj[2]?$takasago_guest_obj[2]->_td_html:"").'
'.($takasago_guest_obj[4]?$takasago_guest_obj[4]->_td_html:"").'
</tr></table>';

$tableData = $obj->get_table_data($user_id);

$layoutname = $tableData["layoutname"];

?>
<br/>

    <div id="make_plan_table">

  			<div id="room" style="float:left; width:<?=$width?>px; ">
			<div align="center" style="text-align:center; margin:0 auto; font-size:13px; font-size:13px">
				<?=$html?>
			</div><br/>
			<div align="center" style="height:20px; text-align:center; padding:5px; margin:0 auto; font-size:13px">
  <div style="width:500px; border:1px solid black;margin:auto;">
				<?=$layoutname?>
</div>
			</div>

            	<div id="toptst" style="float:left; width:100%; ">
					<?php
                    foreach($tblrows as $tblrow)
                    {
                       $table_rows = $obj->getRowsByQuery("select * from spssp_table_layout where user_id = ".(int)$user_id." and row_order=".$tblrow['row_order']." order by  column_order asc");
                       $ralign = $obj->GetSingleData("spssp_table_layout", "align"," row_order=".$tblrow['row_order']." and user_id=".$user_id." limit 1");

                       $num_first = $obj->GetSingleData("spssp_table_layout", "column_order "," display=1 and user_id=".$user_id." and row_order=".$tblrow['row_order']." order by column_order limit 1");
                       $num_last = $obj->GetSingleData("spssp_table_layout", "column_order "," display=1 and user_id=".$user_id." and row_order=".$tblrow['row_order']." order by column_order desc limit 1");
                       $num_max = $obj->GetSingleData("spssp_table_layout", "column_order "," user_id=".$user_id." and row_order=".$tblrow['row_order']." order by column_order desc limit 1");
                       $num_none = $num_max-$num_last+$num_first-1;
                       $width = $num_max*215;

                       if($ralign == 'C')
                         {

                           if($num_none > 0)
                             {
                               $con_width = $width -((int)($num_none*198));
                             }
                           else
                             {
                               $con_width = $width;
                             }
                           $pos = 'margin:0 auto; width:'.$con_width.'px';
                         }
                       else if($ralign=='R' && $align_term==1)
                         {
                           $pos = 'float:right;';
                         }
                       else
                         {
                           $pos = 'float:left;width:'.$width.'px;';

                         }
                    ?>
                	<div class="rows" id="row_<?=$tblrow['row_order']?>" style="width:<?php echo $width;?>px;">
                		<input type="hidden" id="rowcenter_<?=$tblrow['row_order']?>" value="<?=$ralign?>" />
                		<div class="row_conatiner" id="rowcon_<?=$tblrow['row_order']?>" style="<?=$pos;?>">
                    	<?php

                       $index = 0;
							foreach($table_rows as $table_row)
							{
                $tblname = $table_row["name"];
                /*spssp_user_tableの役割が分からないため一度コメントアウト。
								$new_name_row = $obj->GetSingleRow("spssp_user_table", "user_id = ".(int)$user_id." and default_table_id=".$table_row['id']);
                
								if(isset($new_name_row) && $new_name_row['id'] !='')
								{
									$tblname_row = $obj->GetSingleRow("spssp_tables_name","id=".$new_name_row['table_name_id']);
									$tblname = $tblname_row['name'];

									$len=mb_strlen($tblname,'UTF-8');
                  
									$tblname1=mb_substr ($tblname, 0,1,'UTF-8');

									$tblname2=mb_substr ($tblname, 1,$len,'UTF-8');

								}
								else
								{
									$tblname = $table_row['name'];
									$len=mb_strlen($tblname,'UTF-8');
									$tblname1=mb_substr ($tblname, 0,1,'UTF-8');

									$tblname2=mb_substr ($tblname, 1,$len,'UTF-8');
                  }*/

                if($table_row["display"] == 1){
                  $disp = 'display:block;';
									$class = 'droppable';
                  //テーブルの色について
                  ++$index;
                }else if($num_first <= $table_row["column_order"] && $table_row["column_order"]<=$num_last){
									$disp = 'visibility:hidden;';
                  $class = 'seat_droppable';
                }else if($ralign == "N"){
                  $disp = 'visibility:hidden;';
                  $class = 'seat_droppable';
                }else{
									$disp = 'display:none;';
									$class = 'droppable';
                }
                    		?>
                        	<div class="tables" id="tid_<?=$table_row['id']?>" style=" <?=$disp?>margin-left:15px;" >
                                <p align="center" style="text-align:center" id="p_<?=$table_row['id']?>">

                                    <b>&nbsp;</b>
                                </p>

                            	<?php


                                //echo $disp;
                                $seats = $obj->getRowsByQuery("select * from spssp_default_plan_seat where table_id =".$table_row['table_id']." order by id asc limit 0,$room_seats");



								$rowspan=ceil(count($seats)/4);
								$j=1;
								$jor=0;
                                foreach($seats as $seat)
                                {
                                ?>
                                    <div id="<?=$seat['id']?>" class="<?=$class?>" style="background-color:
<?php
//座席表の色の指定
if($index % 2 == 1){
  echo "#F5F8E5";
}else{
  echo "#e5b9b9";
}
?>
;" >
                                        <?php
                                        $key = $seat['id']."_input";
                                        if(isset($cart[$key]) && $cart[$key] != '')
                                        {
                                            $itemArray = explode("_", $cart[$key]);

                                            $item = $itemArray[1];
                                            $item_info =  $obj->GetSingleRow("spssp_guest", " id=".$item." and id in(SELECT id FROM `spssp_guest` WHERE user_id=".$user_id." and self!=1 and stage_guest=0)");

											include("admin/inc/main_dbcon.inc.php");
                                            $rspct = $obj->GetSingleData(" spssp_respect", "title"," id=".$item_info['respect_id']);
											include("admin/inc/return_dbcon.inc.php");
                                            //echo $item_info['id'].'<br>';
                                            $edited_nums = $obj->GetNumRows("spssp_guest", "edit_item_id=".$item_info['id']." and user_id=".(int)$user_id." and id in(SELECT id FROM `spssp_guest` WHERE  self!=1 and stage_guest=0)");
                                            //echo '<p>'.$edited_nums.'</p>';

                                            if($edited_nums > 0)
                                            {
                                                $guest_editeds = $obj->GetSingleRow("spssp_guest", "edit_item_id=".$item_info['id']." and user_id=".(int)$user_id." and id in(SELECT id FROM `spssp_guest` WHERE  self!=1 and stage_guest=0)");
                                                $item_info['id']=$guest_editeds['id'];

                                                $item_info['name']=$guest_editeds['name'];

                                            }


                                        ?>


										<div id="abc_<?=$seat['id']?>" class="gallery ui-helper-reset ui-helper-clearfix">


											<div class="ui-widget-content ui-corner-tr" id="item_<?=$item_info['id']?>"   style="width:80px; height:30px;border:0;background-color:
<?php
//座席表の色の指定
if($index % 2 == 1){
  echo "#F5F8E5";
}else{
  echo "#e5b9b9";
}
?>
;">

													<?php
													$gname=$item_info['first_name']." ".$item_info['last_name']." ".$rspct;
													$guest_comment=$item_info['comment1']."&nbsp;".$item_info['comment2'];



													if($gname)
													{
														$name_length = mb_strlen($gname,"utf-8");
														$name_length2 = mb_strlen($guest_comment,"utf-8");



																if($name_length >8)
																{
																	if($name_length >=8 && $name_length <= 12)
																	{
																		$fsize = '68%';

																	}
																	else if($name_length >=12 && $name_length <= 14)
																	{
																		$fsize = '60%';

																	}
																	else if($name_length >=15 && $name_length <= 17)
																	{
																		$fsize = '50%';

																	}
																	else if($name_length >=18 && $name_length <= 20)
																	{
																		$fsize = '45%';

																	}
																	else if($name_length >=21 && $name_length <= 23)
																	{
																		$fsize = '40%';

																	}
																	else if($name_length >=24 && $name_length <= 26)
																	{
																		$fsize = '35%';

																	}
																	else if($name_length >=27 && $name_length <= 29)
																	{
																		$fsize = '30%';

																	}
																	else if($name_length >=30 && $name_length <= 40)
																	{
																		$fsize ='25%';

																	}
																	else if($name_length >=41 && $name_length <= 50)
																	{
																		$fsize ='20%';

																	}
																	else
																	{
																		$fsize ='15%';

																	}
																}
																else
																{
																	$fsize = '85%';
																}
														}

													?>
													<a href="javascript:void(0)" id="ahref_<?=$seat['id']?>" style="color:black; display:block;" class="tooltip" title="<image src='<?php echo $objInfo->get_user_name_image_or_src_from_user_side_make_plan($user_id ,$hotel_id=1, $name="namecard.png",$extra="guests/".$item_info['id']."/");?>'>">												
                          <image src="<?php echo $objInfo->get_user_name_image_or_src_from_user_side_make_plan($user_id ,$hotel_id=1, $name="guest_fullname.png",$extra="guests/".$item_info['id']."/thumb2");?>"/></a>
                                               </div>
											  </div>

                                        <?php
                                        }
                                    ?>

                                    </div>

                                <?php
									if($jor%2==0)
									{
										if($j==1) 
										{
											?>
                      <div style="float:left;text-align:center; width:25px; height:30px;">
											<div  class="tate-area" rowspan="<?php echo count($seats)/2;?>" style='direction:rtl;margin-right:8px;'>
											  <div align="center" class="tate-line" id="table_<?=$table_row['id']?>">
                                    				<span class="font08"><?=$tblname?></span>
                               				 </div>
											 </div>
                       </div>
											<?php


										}
										else
										{
											echo "<div style='float:left; width:25px; height:30px'></div>";
										}

										$j++;


									}
									$jor++;

                                }
                            	?>
                        	</div>
						  <?php
                           }
                          ?>
                	  </div>
                	</div>
					<?php
                    }
                    ?>
            	</div>

            <!-- <div id="make_plan_submit">
				<div align="left">

					  <input type="button"  id="button" value="保存" onclick="checkConfirm()" />
					 <input type="button"  id="button2" value="リセット" onClick="resetFormItems()" />
					  <input type="button"  id="button3" value="戻る"  onClick="javascript:history.go(-1);" />

				</div>
         	</div>-->
			<div class="clear" style="float:left; clear:both; height:10px;">&nbsp;</div>
    </div>
    <input type="hidden" id="timeout" name="timeout" value="" />
	</form>
  </div>
  <div class="clear" style="float:left; clear:both; height:10px;"></div></div></div>
  </div>
</html>


