<?php
	include_once("admin/inc/class_information.dbo.php");
	include_once("inc/checklogin.inc.php");
	$obj = new DBO();
	$objInfo = new InformationClass();
	$get = $obj->protectXSS($_GET);
	$user_id = (int)$_SESSION['userid'];
	include_once("inc/new.header.inc.php");

	$plan_info = $obj ->GetSingleRow("spssp_plan"," user_id=".(int)$_SESSION['userid']);
	$num_layouts = $obj->GetNumRows("spssp_table_layout","user_id= ".(int)$user_id);

	$editable=$objInfo->get_editable_condition($plan_info);

	if($_POST['user_layout_title']="user_layout_title")
	{
		unset($_POST['user_layout_title']);
		unset($_POST['submit']);
		$obj->UpdateData("spssp_plan",$_POST," user_id=".$user_id);
	}

if($_POST['ajax']=="ajax")
	{
		unset($_POST['ajax']);
		$obj->UpdateData("spssp_plan",$_POST," user_id=".$user_id);
		exit;
	}
// UCHIDA EDIT 11/08/03 披露宴会場名の取得
	$user_data = $obj->GetSingleRow("spssp_user", " id=".$user_id);
	$rooms = $obj->GetAllRow("spssp_room");

	if(is_array($rooms))
	{
		foreach($rooms as $room)
		{
			if($room['id']==$user_data['room_id'])
			{
			   $roomName = $room['name'];
			}
		}
	}
?>

<link href="css/choose_plan.css" rel="stylesheet" type="text/css" />

<script>
 var title=$("title");
 $(title).html("テーブルの配置 - ウエディングプラス");
$(function(){

$("ul#menu li").removeClass();
$("ul#menu li:eq(1)").addClass("active");

$(".rows").filter(":first").css("border-top","1px solid #666666");

var msg_html=$("#msg_rpt").html();

	if(msg_html!='')
	{
		$("#msg_rpt").fadeOut(5000);
	}

});
function checkGuest(tid,cid)
{
	$.post('ajax/check_plan_guest.php',{'tid':tid}, function(data){
		if(parseInt(data) > 0)
		{
			$("#"+cid).attr('checked','checked');
			alert('There are Guests on this Table. Please remove them first.');
		}
	});
}
//FAHIM EDIT 11/07/30 FULL NEW FUNCTION
function validForm(num)
{
var layoutname_ajax  = $("#layoutname_ajax").val();
var table_array=new Array();
//卓名空白は非許可
for(var loop=1;loop<=num;loop++)
{
	var tId="tableId_"+loop;
	var table_name=	$("#"+tId).val();

	if(table_name=="") {
		alert("卓名を入力してください");
 		document.getElementById(tId).focus();
 		return false;
	}
}
var sendObject = {};
for(var loop=1;loop<=num;loop++)
  {
    var tId="tableId_"+loop;
    var table_name=	$("#"+tId).val();
    var valueid="hiddenid_"+loop;
    var table_id=	$("#"+valueid).val();
    sendObject["name_"+loop] = table_name;
    sendObject["id_"+loop] = table_id;
  }

$.post('table_layout.php',{'layoutname':layoutname_ajax,'ajax':"ajax"}, function(data){
		if (layoutname_ajax=="" || !isset(layoutname_ajax)) layoutname_ajax = "　　　";
		$("#user_layoutname").html(layoutname_ajax);
		$("#default_layout_title").html(layoutname_ajax);
		$("#img_default_layout_title").val(layoutname_ajax);
	});
sendObject["total_table"] = num;

$.post('ajax/plan_table_name_update_all.php',sendObject, function(data){
    location.href = "";
	});


}

function user_layout_title_input_show(id)
	{
		$("#"+id).fadeOut();
		$("#input_user_layoutname").fadeIn(500);

	}
</script>
<style>

	.tables
	{
		height:40px;
		width: 40px;
		float:left;
		
		text-align:left; 
		background-image:url(img/circle_small.jpg);
		background-repeat: no-repeat;
		background-position: center center;

	}

	.tables
	{

		margin:5px 5px;

	}
	.tables p
	{

		margin-top:5px;
		margin-left:5px;

	}

	.tables p a
	{
	text-decoration:none;
	cursor:default;

	}
</style>

<div id="contents_wrapper">
<div id="nav_left">
    <div class="step_bt"><img src="img/step_head_bt01_on.jpg" width="150" height="60" border="0"/></div>
    <div class="step_bt"><a href="hikidemono.php"><img src="img/step_head_bt02.jpg" width="150" height="60" border="0" class="on" /></a></div>
    <div class="step_bt"><a href="my_guests.php"><img src="img/step_head_bt03.jpg" width="150" height="60" border="0" class="on" /></a></div>
    <div class="step_bt"><a href="make_plan.php"><img src="img/step_head_bt04.jpg" width="150" height="60" border="0" class="on" /></a></div>
	  <div class="step_bt"><a href="order.php"><img src="img/step_head_bt05.jpg" width="150" height="45" border="0" class="on" /></a></div>
	  <div class="clear"></div>
</div>

<div id="contents_right">
  <div class="title_bar">
  <div class="title_bar_txt_L">披露宴会場のテーブルのレイアウト、卓名をご確認いただけます</div>
  <div class="title_bar_txt_R"></div>
  <div class="clear"></div>
</div>
  <div class="cont_area">
    <div class="info_box">
    <div class="info_area_L">

		<table width="75%" border="0" >
		  <tr>
			  <td width="100%" nowrap align="left" > ■　テーブルレイアウトは右の形になります。</td>
		  </tr>
		</table>
		<table width="75%" border="0" >
		  <tr>
		    <td width="30%" >披露宴会場　　　：</td>
		    <td width="50%" ><?=$roomName;?></td>
		  </tr>
		  <tr>
			<?
			$seat_number = $obj->GetSingleData("spssp_plan", "seat_number"," user_id =".$user_id);
			$roomid = $obj->GetSingleData("spssp_user","room_id"," id= ".$user_id);
			$max_seats = $obj->GetSingleData("spssp_room","max_seats"," id= ".$roomid);
			?>
		    <td>一卓の最大人数　：</td>
		    <td><?php if($seat_number){echo $seat_number;}else{echo $max_seats;}?> 名まで。</td>
		  </tr>
		</table>
        <br />
       <?php
		$user_tables = $obj->getRowsByQuery("select * from spssp_table_layout where user_id = ".$user_id." and display=1");
    //rename_tableがtrueになる条件は、
		$permission_table_edit = $obj->GetSingleData("spssp_plan", "rename_table"," user_id =".$user_id);
		$layoutname = $obj->getSingleData("spssp_plan", "layoutname"," user_id= $user_id");
		$default_layout_title = $obj->GetSingleData("spssp_options" ,"option_value" ," option_name='default_layout_title'");

	   if($permission_table_edit['rename_table']) {?>
        <table width="410" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td width="12" valign="top" nowrap="nowrap">■</td>
            <td width="378">テーブル名は任意の名前で変更できます。<br />
              変更する場合は、下記のフォームに入力して保存ボタンを押してください。<br />
              （右のプレビューは先頭文字のみ表示）
              </td>
          </tr>
      </table>

        <?php

		?>
        <?php
	   }
		if(isset($_GET['msg']) && $_GET['msg'] !='')
		{
			$obj->GetSuccessMsg((int)$_GET['msg']);
		}
		else if(isset($_GET['err']) && $_GET['err'] !='')
		{
			$obj->GetErrorMsg((int)$_GET['err']);
		}

	  ?>
		<div id='msg_rpt' style='display:none;text-align:center;margin-bottom:20px;background:#E1ECF7;border:1px solid #3681CB;padding:7px 10px;color:green;font-weight:bold;font-size:13px;'>
		Table name succesfully updated.
		</div>
		<div id='msg_rpt_err' style='display:none;text-align:center;margin-bottom:20px;background:#E1ECF7;border:1px solid #3681CB;padding:7px 10px;color:red;font-weight:bold;font-size:13px;'>
		Table name could not updated.
		</div>
		<? if($permission_table_edit['rename_table']) {
		  if($permission_table_edit==1)
		  {
			?>

		<?php }
			?>
		<form name="table_entry_form" id="table_entry_form" action="" method="post"	>

		<table width="410" border="0" cellspacing="0" cellpadding="5">
		<tr>
		<td colspan="4" align="center" style="display:none;" id="loading_td">
		<img src="images/loading.gif" alt="loading..." />
		</td>
		</tr>
		<br />
		<br />
		<tr>
			<td colspan="4" align="center">
			高砂卓名： <!-- UCHIDA EDIT 11/08/04  -->
			<?php
			$_readonly="";
			if (!$editable) {
				$_readonly=" readonly='readonly'";
			}
			if($layoutname!="")
			{
				$name_input=$layoutname;
				echo "<input type='text' id='layoutname_ajax' name='layoutname_ajax'".$_readonly." value='".$name_input."'>";
			}
			else
			{
				$name_input=$default_layout_title;
				echo "<input type='text' id='layoutname_ajax' name='layoutname_ajax'".$_readonly." value='".$name_input."'>";
			}
			?>
			　　　　　</td>
		</tr>
         <tr>
		  <?php
		  $k=1;

			foreach($user_tables as $user_table_row)
			{

				if($user_table_row['table_id'])
				{
				?>

           <td width="14" align="center" valign="middle" nowrap="nowrap"><strong><?=$k?></strong></td>
           <td width="104" nowrap="nowrap">
		   <input name="tableName_<?=$k?>" type="text" id="tableId_<?=$k?>" value="<?=$user_table_row['name']?>" size="15" <?=$_readonly?> />
		   <input name="hiddenid_<?=$k?>" type="hidden" id="hiddenid_<?=$k?>" value="<?=$user_table_row['id']?>" size="15" />
		   </td>
          <?php




		  	if($k%2==0)
			{
				echo "</tr><tr>";
			}

		  ?>


         <?php $k++;}} ?>
        </tr>


		<?php
				if($permission_table_edit==1 && $editable)
				  {?>
				  <tr>
				  <td colspan="4">

					&nbsp;<br />
					<a href="javascript:void(0)" onclick="validForm(<?=$k-1?>);" name="edit">
		        	<img src="img/btn_save_user.jpg" border="0" />
		        	</a>
					&nbsp;
					<a href="table_layout.php" name="cancel">
		        	<img src="img/btn_rollback_user.jpg" border="0" />
		        	</a>
					</td>
				  </tr>

				  <?php
				  }
				  ?>
		</table>
		</form>
		<? } ?>

      </div>
      <div class="info_area_R" style="">■　テーブルのレイアウト<br />

      		<div style="width:100%; float:left; text-align:center; ">
       		<?php
      $tblrows = $obj->getRowsByQuery("select distinct row_order from spssp_table_layout where user_id= ".(int)$user_id);
      $num_tables = $obj->getSingleData("spssp_plan", "column_number"," user_id= $user_id");
      $rw_width = (int)($num_tables* 51);
			?>
			<div>
	<?php

		if($layoutname!="")
		{
			echo "<div id='user_layoutname'  style='display:block;text-align:center;width:100px;margin:0 auto;border:1px solid gray;'>".$layoutname."</div>";
		}
		elseif($default_layout_title!="")
		{
			echo "<div id='default_layout_title' style='display:block;text-align:center;width:100px;margin:0 auto;border:1px solid gray;'>".$default_layout_title."</div>";
		}
		else
		{
			echo'<table width="10" border="0" cellspacing="0" cellpadding="0" align="center">
			  <tr>
				<td style="text-align:center;"><div id="img_default_layout_title"  style="text-align:center"><div style="border:1px solid #000000; width:60px;">　　　</div></div></td>
			  </tr>
			</table>';
		}

		echo "<div id='input_user_layoutname' style='display:none;'>
		<form action='table_layout.php' method='post'>
		<input type='hidden' name='user_layout_title' value='user_layout_title'>
		<input type='text' name='layoutname' id='layoutname' value='".$name_input."'>
		<input type='submit' name='submit' value='保存'>
		</form>
		</div>";
		?>
			</div>
        	<div align="center" style="width:<?=$rw_width?>px; margin:10px auto;"  id="table_information">
            	<?php
				$z=count($tblrows);
				$i=0;
				$ct=0; // UCHIDA EDIT 11/07/28

				foreach($tblrows as $tblrow)
				{
					$i++;

						$ralign = $obj->GetSingleData("spssp_table_layout", "align"," row_order=".$tblrow['row_order']." and user_id=".(int)$user_id." limit 1");
						$num_hidden_table = $obj->GetNumRows("spssp_table_layout","user_id = $user_id and display = 0 and row_order=".$tblrow['row_order']);

            $num_first = $obj->GetSingleData("spssp_table_layout", "column_order "," display=1 and user_id=".$user_id." and row_order=".$tblrow['row_order']." order by column_order limit 1");
            $num_last = $obj->GetSingleData("spssp_table_layout", "column_order "," display=1 and user_id=".$user_id." and row_order=".$tblrow['row_order']." order by column_order desc limit 1");
            $num_max = $obj->GetSingleData("spssp_table_layout", "column_order "," user_id=".$user_id." and row_order=".$tblrow['row_order']." order by column_order desc limit 1");
            $num_none = $num_max-$num_last+$num_first-1;

						if($ralign == 'L' || $ralign == "N")
						{
							$pos = 'float:left;';
						}
						else if($ralign=='R')
						{
							$pos = 'float:right;';
						}
						else
						{
							$wd = $rw_width - ($num_none*51);
							$pos = 'margin:0 auto; width:'.$wd.'px';
						}

				?>
    			<div  style="float:left;width:100%; border:1px solid black;<?php if($i!=$z) {?> border-bottom:none; <?php } ?>" id="row_<?=$tblrow['row_order']?>">
            	<input type="hidden" id="rowcenter_<?=$tblrow['row_order']?>" value="<?=$ralign?>" />

            		<div class="row_conatiner" id="rowcon_<?=$tblrow['row_order']?>" style="<?=$pos;?>">
				<?php
             $table_rows = $obj->getRowsByQuery("select * from spssp_table_layout where user_id = ".(int)$user_id." and row_order=".$tblrow['row_order']." order by  column_order asc");


                    foreach($table_rows as $table_row)
                    {
                    	$new_name_row = $obj->GetSingleRow("spssp_user_table", " user_id = ".(int)$user_id." and default_table_id=".$table_row['id']);

                      $tblname='';
                      if($table_row['name']!='')
                        {
                          $tblname = $table_row['name'];
                        }
                        elseif(is_array($new_name_row) && $new_name_row['id'] !='')
                        {
                            $tblname_row = $obj->GetSingleRow("spssp_tables_name","id=".$new_name_row['table_name_id']);
                            $tblname = $tblname_row['name'];
                        }

                        if($table_row["display"] == 1){
                          $disp = 'display:block;';
                          $ct++;
                          $ctm=$ct;
                          if ($ct<10) $ctm="&nbsp;&nbsp;".$ct; 
                        }else if(($num_first <= $table_row["column_order"] && $table_row["column_order"]<=$num_last) || $ralign == "N" ){
                          $disp = "visibility:hidden";
                        }else{
                          $disp = "display:none";
                        }
                        if ($tblname=="") $tblname="　";
                    ?>
                    <div class="tables" style="<?=$disp?>">
                        <p id="table_<?=$table_row['id']?>">
                        	<font style='font-size:75%' color="#ff0000"><?echo $ctm?></font>
                            <font style='font-size:100%'><b  style="height:30px; line-height:30px;"> <?=mb_substr ($tblname, 0,1,'UTF-8');?></b></font>
                        </p>
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


        </div>


	  </div>
    </div> <div class="clear"></div>
</div>
  <div style="clear:both;"></div>
</div>
  <div style="clear:both;"></div>
</div>

<?php
include("inc/new.footer.inc.php");
?>
