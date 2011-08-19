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
/* 高砂卓名の未入力を許可 UCHIDA EDIT 11/08/04
	if(layoutname_ajax=="")
		{
			alert("卓名を入力してください");
			document.getElementById("layoutname_ajax").focus();
			return false;
		}
*/

// 重複した卓名の確認
/* UCHIDA EDIT 11/08/16 重複チェックは仕様から削除
	for(var loop=1;loop<=num;loop++)
	{
		var tId="tableId_"+loop;
		var table_name=	$("#"+tId).val();
		var valueid="hiddenid_"+loop;
		var table_id=	$("#"+valueid).val();

		if(table_name!="") {
			for(var loop2 = loop+1; loop2<=num; loop2++) {
				var tId2="tableId_"+loop2;
				var table_name2 =$("#"+tId2).val();
				if (table_name == table_name2) {
					alert("卓名が重複しています");
			 		document.getElementById(tId2).focus();
			 		return false;
				}
			}
		}
	}
*/
	for(var loop=1;loop<=num;loop++)
		{
			var tId="tableId_"+loop;
			var table_name=	$("#"+tId).val();
			var valueid="hiddenid_"+loop;
			var table_id=	$("#"+valueid).val();
/* 卓名の未入力を許可 UCHIDA EDIT 11/08/04
			if(table_name=="")
				{
					alert("卓名が入力されていない項目があります。卓名を入力してください");
			 		document.getElementById(tId).focus();
			 		return false;
				}
			else
				{
					table_array[loop]="name_"+loop+"="+table_name+"&id_"+loop+"="+table_id;
				}
*/
			table_array[loop]="name_"+loop+"="+table_name+"&id_"+loop+"="+table_id;
		}
	$("#loading_td").show();
	var	table_string=table_array.join("&");
	//alert(table_string);
	$.post('table_layout.php',{'layoutname':layoutname_ajax,'ajax':"ajax"}, function(data){
				$("#user_layoutname").html(layoutname_ajax);
				$("#layoutname").val(layoutname_ajax);
			});

	$.get('ajax/plan_table_name_update_all.php?total_table='+num+"&"+table_string, function(data){

			$("#table_information").html(data);
			$("#loading_td").hide();
			alert("更新されました");
			return false;
			});


}

/*function validForm(num,i)
{
// UCHIDA EDIT 11/07/29 ↓　更新しつつ入力名をチェックしていたので、先頭で未入力をチェックする
	var loop;
	var tId;
	var nm;
	if (i == 1) {

		for(loop=1; loop<num; loop=loop+1) {
			tId="tableId_"+loop;
			nm  = document.getElementById(tId).value;
			// alert ("name  = ["+nm+"]");
			if (nm == "") {
				alert("卓名が入力されていない項目があります。卓名を入力してください");
			 	document.getElementById(tId).focus();
			 	return false;
			}
		}
	}
// UCHIDA EDIT 11/07/29　↑

	if(i==num)
	{

	alert("更新されました");
	return false;

	}
		var tableId="tableId_"+i;
		var valueid="hiddenid_"+i;

		var name  = document.getElementById(tableId).value;
		var id=document.getElementById(valueid).value;
		var showtableid="table_"+id;
		if(i==1)
		{
			var layoutname_ajax  = document.getElementById("layoutname_ajax").value;
			if(layoutname_ajax!="")
			{
			$.post('table_layout.php',{'layoutname':layoutname_ajax,'ajax':"ajax"}, function(data){
				$("#user_layoutname").html(layoutname_ajax);
				$("#layoutname").val(layoutname_ajax);
			});
			}
			else
			{
				alert("卓名を入力してください");
			 	document.getElementById("layoutname_ajax").focus();
			 	return false;
			}
		}


		var flag = true;

		if(!name)
		{
			 alert("卓名を入力してください");
			 document.getElementById(tableId).focus();
			 return false;
		}
		else
		{
			/*
//			alert (name+" : "+id);
//			xx = new String(name);
//			alert(xx.charAt(0)+" : "+data);
*/
/*
//<?php
//$_update = array("name" => name);
//$obj->UpdateData("spssp_table_layout", $_update, " user_id=_user_id and id =id");
//?>

//var _user_id;
//_user_id = "<?php echo $user_id; ?>";
//alert (_user_id);
				$.post('ajax/plan_table_name_update.php',{'name':name,'id':id}, function(data){
				if(data)
				{
					$("#"+showtableid+" b").html(data);
					if(i<num)
					{
					i++;
					validForm(num,i);
					}

					//$("#msg_rpt_err").hide("slow");
				}
				else
				{
					//$("#msg_rpt_err").show("slow");

					//$("#msg_rpt").hide("slow");
				}
		});

	}


}*/
function user_layout_title_input_show(id)
	{
		$("#"+id).fadeOut();
		$("#input_user_layoutname").fadeIn(500);

	}
</script>
<style>

	.tables
	{
		height:31px;
		width: 31px;
		float:left;

		background-image:url(img/circle_small.jpg);

	}

	.tables
	{

		margin:5px 10px;

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
	<!-- UCHIDA EDIT 11/07/26 -->
    <!-- <div class="title_bar_txt_L">テーブルレイアウトをご覧ください。</div> -->
    <div class="title_bar_txt_L">披露宴会場のテーブルのレイアウト、卓名をご確認いただけます。</div>
    <div class="title_bar_txt_R"></div>
<div class="clear"></div></div>
  <div class="cont_area">
    <div class="info_box">
      <div class="info_area_L">
<!-- UCHIDA EDIT 11/08/03 披露宴会場名の表示　↓ -->
<!--
        <table width="410" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td width="12" valign="top" nowrap="nowrap">■</td>
            <td width="378">テーブルレイアウトは右の形になります。<br />
			<?
			$seat_number = $obj->GetSingleData("spssp_plan", "seat_number"," user_id =".$user_id);
			$roomid = $obj->GetSingleData("spssp_user","room_id"," id= ".$user_id);
			$max_seats = $obj->GetSingleData("spssp_room","max_seats"," id= ".$roomid);
			?>
              一卓の最大人数　<?php if($seat_number){echo $seat_number;}else{echo $max_seats;}?> 名まで。</td>
          </tr>
        </table>
 -->
		<table width="75%" border="0" >
		  <tr>
			<td width="100%" nowrap align="left" > ■　テーブルレイアウトは右の形になります。</td>
		  </tr>
		</table>
		<table width="75%" border="0" >
		  <tr>
		    <td width="30%" >披露宴会場　　　　：</td>
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
<!-- UCHIDA EDIT 11/08/03 披露宴会場名の表示　↑ -->

        <br />
       <?php
		$user_tables = $obj->getRowsByQuery("select * from spssp_table_layout where user_id = ".$user_id." and visibility=1 and display=1");
		$permission_table_edit = $obj->GetSingleData("spssp_plan", "rename_table"," user_id =".$user_id);
		$layoutname = $obj->getSingleData("spssp_plan", "layoutname"," user_id= $user_id");
		$default_layout_title = $obj->GetSingleData("spssp_options" ,"option_value" ," option_name='default_layout_title'");

	   if($permission_table_edit['rename_table']) {?>
        <table width="410" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td width="12" valign="top" nowrap="nowrap">■</td> <!-- UCHIDA EDIT 11/08/03 メッセージ変更 -->
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
		<tr>
			<td colspan="4" align="center">
			高砂卓名： <!-- UCHIDA EDIT 11/08/04  -->
			<?php
			if($layoutname!="")
			{
				$name_input=$layoutname;
				echo "<input type='text' id='layoutname_ajax' name='layoutname_ajax' value='".$name_input."'>";
			}
			else
			{
				$name_input=$default_layout_title;
				echo "<input type='text' id='layoutname_ajax' name='layoutname_ajax' value='".$name_input."'>";
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
				 	//$default_table_name = $obj->GetSingleData("spssp_default_plan_table", "name"," id=".$user_table_row['table_id']);
				?>

           <td width="14" align="center" valign="middle" nowrap="nowrap"><strong><?=$k?></strong></td>
           <td width="104" nowrap="nowrap">
		   <input name="tableName_<?=$k?>" type="text" id="tableId_<?=$k?>" value="<?=$user_table_row['name']?>" size="10" />
		   <input name="hiddenid_<?=$k?>" type="hidden" id="hiddenid_<?=$k?>" value="<?=$user_table_row['id']?>" size="10" />
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
				  <td colspan="4"><input type="button" name="edit" value="保存" onclick="validForm(<?=$k-1?>);"></td>
				  </tr>

				  <?php
				  }
				  ?>
		</table>
		</form>
		<? } ?>
		<!--<table width="410" border="0" cellspacing="0" cellpadding="5">
         <tr>
		  <?php
		  /*$k=1;

			foreach($user_tables as $user_table_row)
			{
				$optionvalue='';
				foreach($user_tables as $key => $user_table_row1)
				{
				    $keyvalue= $key+1;
				    $selected =($k == $keyvalue)?"selected":"";
					$optionvalue .='<option '.$selected.' value="'.$user_table_row1['name'].'">'.$user_table_row1['name'].'</option>';
				}

				if($user_table_row['table_id'])
				{
				 	//$default_table_name = $obj->GetSingleData("spssp_default_plan_table", "name"," id=".$user_table_row['table_id']);
				  if($permission_table_edit==1)
				  {?>
				  <form name="tableEdit_form_<?=$k?>" method="post" action="table_layout.php">
				 <?php }
			?>

           <td width="14" align="center" valign="middle" nowrap="nowrap"><strong><?=$k?></strong></td>
           <td width="104" nowrap="nowrap">
		   <select name="tableName_<?=$k?>" id="tableId_<?=$k?>" style="width:100px;" onchange="validForm(<?=$k?>,<?=$user_table_row['id']?>);">
		   <?=$optionvalue?>
		   </select>

		   </td>
          <?php
		   if($permission_table_edit==1)
				  {?>
				  <td><input type="button" name="edit" value="保存" onclick="validForm(<?=$k?>,<?=$user_table_row['id']?>);"></td>
				  </form>
				 <?php }


		  	if($k%2==0)
			{
				echo "</tr><tr>";
			}

		  ?>


         <?php $k++;} */?>
        </tr>
		</table>-->

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
			$name_input=$layoutname;
			echo "<div id='user_layoutname'  style='display:block;text-align:center;width:100px;margin:0 auto;border:1px solid gray;'>".$layoutname."</div>";
		}
		elseif($default_layout_title!="")
		{
			$name_input=$default_layout_title;
			echo "<div id='default_layout_title' style='display:block;text-align:center;width:100px;margin:0 auto;border:1px solid gray;'>".$default_layout_title."</div>";
		}
		else
		{
			echo'<table width="10" border="0" cellspacing="0" cellpadding="0" align="center">
			  <tr>
				<td style="text-align:center;"><div id="img_default_layout_title"  style="text-align:center"><div style="border:1px solid #000000; width:60px;"> &nbsp;</div></div></td>
			  </tr>
			</table>';

			//echo "<p id='img_default_layout_title'  style='text-align:center'><img src='admin/img/sakiji_icon/icon_takasago.gif' width='102' height='22' /></p>";
			//echo "<div id='img_default_layout_title'  style='text-align:center'><div style='border:1px solid #000000; width:60px;'> &nbsp;</div></div>";
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
						if($ralign == 'L')
						{
							$pos = 'float:left;';
						}
						else if($ralign=='R')
						{
							$pos = 'float:right;';
						}
						else
						{
							$wd = $rw_width - ($num_hidden_table*51);
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
							//print_r($new_name_row);//exit;
                            if($table_row['name']!='')
                            {
$tblname = $table_row['name'];
					//			echo'<pre>';
				//print_r($tblname_row);
                            }
                            elseif(is_array($new_name_row) && $new_name_row['id'] !='')
                            {

							    $tblname_row = $obj->GetSingleRow("spssp_tables_name","id=".$new_name_row['table_name_id']);

								$tblname = $tblname_row['name'];
                            }



                            if($table_row['visibility']==1 && $table_row['display']==1)
                            {

								$ct++; // UCHIDA EDIT 11/07/28
                            	$disp = 'display:block;';

                            }
                            else if($table_row['visibility']==0 && $table_row['display']==1)
                            {
								$ct++;
                                $disp = 'visibility:hidden;';
                            }
                            else if($table_row['display']==0 && $table_row['visibility']==0)
                            {
                                $disp = 'display:none;';
                            }
                    ?>
                    <div class="tables" style="<?=$disp?>">
                        <p align="left" style="text-align:center;" id="table_<?=$table_row['id']?>">
                        	<font style='font-size:60%' color="#ff0000"><?echo $ct?></font> <!-- UCHIDA EDIT 11/07/28 -->
                            <b> <?=mb_substr ($tblname, 0,1,'UTF-8');?></b>
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
