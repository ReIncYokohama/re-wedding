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

<div id="contents_wrapper">
<div id="nav_left">
  <div class="step_bt"><a href="table_layout.php" onclick="return make_plan_check();"><img src="img/step_head_bt01.jpg" width="150" height="60" border="0" class="on" /></a></div>
  <div class="step_bt"><a href="hikidemono.php" onclick="return make_plan_check();"><img src="img/step_head_bt02.jpg" width="150" height="60" border="0" class="on"/></a></div>
  <div class="step_bt"><a href="my_guests.php" onclick="return make_plan_check()"><img src="img/step_head_bt03.jpg" width="150" height="60" border="0" class="on" /></a></div>
  <div class="step_bt"><img src="img/step_head_bt04_on.jpg" width="150" height="60" border="0"/></div>
  <div class="step_bt"><a href="order.php" onclick="return make_plan_check()"><img src="img/step_head_bt05.jpg" width="150" height="45" border="0" class="on" /></a></div>
  <div class="clear"></div></div>

<div id="contents_right">
  <div class="title_bar">
    <div class="title_bar_txt_L">席次表を編集し、プレビューで席次表・引出物の確認をします。</div>
    <div class="title_bar_txt_R"></div>
<div class="clear"></div></div>
<div class="cont_area"  align="center">
  <div id="make_plan_bt_area">

<!-- UCHIDA EDIT 11/08/02 ボタンを整理
	<div class="make_plan_bt"><a href="make_plan.php"><img src="img/make_plan_bt01.jpg" width="155" height="60" class="on" /></a></div>
	<div class="make_plan_bt"><a href="javascript:void();" onclick="checkConfirm();"><img src="img/make_plan_bt02.jpg" width="155" height="60" class="on" /></a></div>
    <div class="make_plan_bt"><a href="plan_pdf_small.php" target="_blank"><img src="img/make_plan_bt03.jpg" width="155" height="60" class="on" /></a></div>
    <div class="make_plan_bt"><a href="plan_pdf.php" target="_blank"><img src="img/make_plan_bt04.jpg" width="155" height="60" class="on" /></a></div>
    <div class="make_plan_bt2"><a href="#"><img src="img/make_plan_bt05.jpg" width="155" height="60" class="on" /></a></div>
 -->
<!-- UCHIDA EDIT 11/08/05 ボタンをセンタに移動 ↓ -->
	<div align="left">
	  <table border="0" cellpadding="0" align="center" >
	    <tr >
	    <td class="make_plan_bt"><a href="plan_pdf_small.php" target="_blank"><img src="img/make_plan_bt03.jpg" width="154" class="on" /></a></td>
	   	<td width="65" height="60" >&nbsp;</td>
    	<td width="1"></td>
    	<td class="make_plan_bt"><a href="plan_pdf.php" target="_blank"><img src="img/make_plan_bt04.jpg" width="154" class="on" /></a></td>
 <!--    	<td width="1"></td>
	    <td class="make_plan_bt"><a href="#"><img src="img/make_plan_bt05.jpg" width="154" class="on" /></a></td> -->
	    </tr>
	  </table>
	</div>
<!-- UCHIDA EDIT 11/08/05 ボタンをセンタに移動 ↑ -->

  </div>

<div class="clear"></div>

<?php

	if(isset($_GET['err']) && $_GET['err'] !='')
	{
		$obj->GetErrorMsg((int)$_GET['err']);
	}

  ?>
</div>


<div class="cont_area" id="con_area_ie">
  <div id="side_area">


  <div  id="guests_conatiner" style="float:left; height:400px; width:100%; overflow-x:auto;overflow-y:visible;" >


				<table width="98%">
				<?php
				$guest_type_sort=($_GET['guest_type_sort']=='desc' || $_GET['guest_type_sort']=='' )?"asc":"desc";
				$guest_sex_sort=($_GET['guest_sex_sort']=='desc' || $_GET['guest_sex_sort']=='' )?"asc":"desc";
				?>
					<tr bgcolor="#666666" style="color:#FFFFFF"><th>No</th><th nowrap="nowrap"><a href="make_plan.php?sortby=sex&guest_sex_sort=<?=$guest_sex_sort?>">郎婦↓</a></th><th nowrap="nowrap"><a href="make_plan.php?sortby=guest_type&guest_type_sort=<?=$guest_type_sort?>">区分↓</a></th><th align="center">&nbsp;&nbsp;姓&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;名&nbsp;&nbsp;</th><th align="left">卓名</th></tr>
					<?php
					$types_guest=array();
					include("admin/inc/main_dbcon.inc.php");
					$guest_types = $obj->GetAllRow( "spssp_guest_type");

					include("admin/inc/return_dbcon.inc.php");
					foreach($guest_types as $types)
					{
					$types_guest[$types['id']]=$types['name'];
					}
					$no=0;
				if($_GET[sortby]=="")
				$guests = $obj->getRowsByQuery("SELECT * FROM `spssp_guest` WHERE user_id=".$user_id." and id not in (select edit_item_id from spssp_guest where user_id=".(int)$user_id.") and self!=1 and stage_guest=0 order by display_order DESC");
				else if($_GET[sortby]=="sex")
				$guests = $obj->getRowsByQuery("SELECT * FROM `spssp_guest` WHERE user_id=".$user_id." and id not in (select edit_item_id from spssp_guest where user_id=".(int)$user_id.") and self!=1 and stage_guest=0 order by sex ".$_GET['guest_sex_sort']);
				else if($_GET[sortby]=="guest_type")
				$guests = $obj->getRowsByQuery("SELECT * FROM `spssp_guest` WHERE user_id=".$user_id." and id not in (select edit_item_id from spssp_guest where user_id=".(int)$user_id.") and self!=1 and stage_guest=0 order by guest_type ".$_GET['guest_type_sort']);

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
								$new_name_row = $obj->getSingleRow("spssp_user_table"," default_table_id=".$tbl_row['id']." and user_id=".(int)$user_id." limit 1");
								if(!empty($new_name_row))
								{
									$tblname = $obj->getSingleData("spssp_tables_name","name","id=".$new_name_row['table_name_id']);
									$tblname=mb_substr ($tblname, 0,1,'UTF-8');
								}
								else
								{
									$tblname = $tbl_row['name'];
									$tblname=mb_substr ($tblname, 0,1,'UTF-8');
								}

							}
							else
							{
								$class = "ui-widget-content ui-corner-tr";
								$src = "img/icon02.jpg";
								$style = "style = 'display:block'";
							}

							include("admin/inc/main_dbcon.inc.php");
							$rsp = $obj->GetSingleData(" spssp_respect", "title"," id=".$guest['respect_id']);
							include("admin/inc/return_dbcon.inc.php");

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
							$name_length = mb_strlen($gname);

							$name_length2 = mb_strlen($guest_comment);



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
					<td><?=($guest['sex']=='Male')?"新郎":"新婦";?></td>
					<td><?=$types_guest[$guest['guest_type']];?></td>

					<td width="90" align="center" style="text-align:left;">


					 <div  id="tst" style="padding:0;width:100%;text-align:left;">
							<div class="<?=$class?>" id="item_<?=$guest['id']?>"  style="width:100px; padding:0;text-align:left;">
								<a href="javascript:void(0)" style="color:black; display:block;" id="tip_<?=$guest['id']?>" class="tooltip"
                   title="<span style='font-size:14px'><?php echo $objInfo->get_user_name_image_or_src_from_user_side_make_plan($user_id ,$hotel_id=1, $name="namecard.png",$extra="guest/".$guest['id']."/");?></span>" >
								<input type="hidden" value="<?php echo $objInfo->get_user_name_image_or_src_from_user_side_make_plan($user_id ,$hotel_id=1, $name="full_comment.png",$extra="guest/".$guest['id']."/thumb1");?>" class="comeent1_hidden" />

								<input type="hidden" value="<?php echo $objInfo->get_user_name_image_or_src_from_user_side_make_plan($user_id ,$hotel_id=1, $name="guest_fullname.png",$extra="guest/".$guest['id']."/thumb1");?>" class="guest_name_hidden" />
								<!--<span style="display:none;font-size:<?=$fsize?>; text-align:left; "><?=$guest['comment1']?>&nbsp;<?=$guest['comment2']?></span><br/>-->
								<?php echo $objInfo->get_user_name_image_or_src_from_user_side_make_plan($user_id ,$hotel_id=1, $name="guest_fullname.png",$extra="guest/".$guest['id']."/thumb2");?>
								<!--<img src="user_name/thumb2/man_fullname.png" alt="<?=$guest['first_name']." ".$guest['last_name']." ".$rsp ;?>"  />-->
								<!--<span style="font-size:<?=$fsize?>; text-align:left;"><?=$guest['first_name']." ".$guest['last_name']." ".$rsp ;?> </span>-->
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

  <div id="make_plan_area">
  	<form action="insert_default_plan.php?user_id=<?=(int)$user_id?>&plan_id=<?=$plan_id?>" method="post" id="insert_plan" name="insert_plan">
  			 <!--<div align="left">

                  <input type="button"  id="button" value="保存" onclick="checkConfirm()" />
                  <!--<input type="button"  id="button2" value="リセット" onClick="resetFormItems()" />
                  <input type="button"  id="button3" value="戻る"  onClick="javascript:history.go(-1);" />

            </div>-->
			<?php
			$main_guest=array();
			$guests_bride = $obj->getRowsByQuery("SELECT * FROM `spssp_guest` WHERE user_id=".$user_id." and self!=1 and stage_guest!='0' and stage_guest!='' order by display_order DESC");

			foreach($guests_bride as $witness_bride)
			{
				$main_guest[$witness_bride[stage_guest]]=$objInfo->get_user_name_image_or_src_from_user_side_make_plan($user_id ,$hotel_id=1, $name="guest_fullname.png",$extra="guest/".$witness_bride['id']."/thumb1");

			}


			$html.='<table style="width:100%; " cellspacing="2"><tr><td align="center"  valign="middle" style="text-align:center;border:1px solid black; padding:7px;">'.$main_guest[3].'</td><td align="center"  valign="middle" style="text-align:center;border:1px solid black;padding:7px; ">'.$main_guest[1].'</td><td align="center"  valign="middle" style="text-align:center;border:1px solid black;padding:7px; ">'.$objInfo->get_user_name_image_or_src_from_user_side_make_plan($user_id ,$hotel_id=1, $name="man_fullname.png",$extra="thumb1").'</td><td align="center"  valign="middle" style="text-align:center;border:1px solid black; padding:7px;">'.$main_guest[5].'</td><td align="center"  valign="middle" style="text-align:center;border:1px solid black; padding:7px;">'.$objInfo->get_user_name_image_or_src_from_user_side_make_plan($user_id ,$hotel_id=1, $name="woman_fullname.png",$extra="thumb1").'</td><td align="center"  valign="middle" style="text-align:center;border:1px solid black; padding:7px;">'.$main_guest[2].'</td><td align="center"  valign="middle" style="text-align:center;border:1px solid black;padding:7px; ">'.$main_guest[4].'</td></tr></table>';

$layoutname = $obj->getSingleData("spssp_plan", "layoutname"," user_id= $user_id");
if($layoutname=="")
$layoutname = $obj->GetSingleData("spssp_options" ,"option_value" ," option_name='default_layout_title'");


?>
<br/>



    <div id="make_plan_table">




  			<div id="room" style="float:left; width:<?=$room_width?>; ">
			<div align="center" style="width:600px;text-align:center; margin:0 auto; font-size:13px; font-size:13px">
				<?=$html?>
			</div><br/>
			<div align="center" style="width:400px; text-align:center; border:1px solid black; padding:5px; margin:0 auto; font-size:13px">
				<?=$layoutname?>
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

                       if($ralign == 'C')
                         {

                           if($num_none > 0)
                             {
                               $con_width = $row_width -((int)($num_none*178));
                             }
                           else
                             {
                               $con_width = $row_width;
                             }

                           $pos = 'margin:0 auto; width:'.$con_width.'px';
                         }
                       else if($ralign=='R' && $align_term==1)
                         {
                           $pos = 'float:right;';
                         }
                       else
                         {
                           $pos = 'float:left;';

                         }





                    ?>
                	<div class="rows" id="row_<?=$tblrow['row_order']?>">
                		<input type="hidden" id="rowcenter_<?=$tblrow['row_order']?>" value="<?=$ralign?>" />
                		<div class="row_conatiner" id="rowcon_<?=$tblrow['row_order']?>" style="<?=$pos;?>">
                    	<?php


							foreach($table_rows as $table_row)
							{
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
								}

                if($table_row["display"] == 1){
                  $disp = 'display:block;';
									$class = 'droppable';
                }else if($num_first <= $table_row["column_order"] && $table_row["column_order"]<=$num_last){
									$disp = 'visibility:hidden;';
                  $class = 'seat_droppable';
                }else{
									$disp = 'display:none;';
									$class = 'seat_droppable';
                }
                    		?>
                        	<div class="tables" id="tid_<?=$table_row['id']?>" style=" <?=$disp?>" >

                                <p align="center" style="text-align:center" id="table_<?=$table_row['id']?>">

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
                                    <div id="<?=$seat['id']?>" class="<?=$class?>" >
                                        <?php
                                        $key = $seat['id']."_input";
                                        if(isset($_SESSION['cart'][$key]) && $_SESSION['cart'][$key] != '')
                                        {
                                            $itemArray = explode("_", $_SESSION['cart'][$key]);

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

											<div class="ui-widget-content ui-corner-tr" id="item_<?=$item_info['id']?>"   style="width:80px; height:30px;background-color:#F5F8E5;border:0;">


													<?php
													$gname=$item_info['first_name']." ".$item_info['last_name']." ".$rspct;
													$guest_comment=$item_info['comment1']."&nbsp;".$item_info['comment2'];



													if($gname)
													{
														$name_length = mb_strlen($gname);
														$name_length2 = mb_strlen($guest_comment);



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
													<a href="javascript:void(0)" id="ahref_<?=$seat['id']?>" style="color:black; display:block;" class="tooltip" title="<?php echo $objInfo->get_user_name_image_or_src_from_user_side_make_plan($user_id ,$hotel_id=1, $name="namecard.png",$extra="guest/".$item_info['id']."/");?></span>">


													<input type="hidden" value="<?php echo $objInfo->get_user_name_image_or_src_from_user_side_make_plan($user_id ,$hotel_id=1, $name="full_comment.png",$extra="guest/".$item_info['id']."/thumb1");?><" class="comeent1_hidden" />

													<input type="hidden" value="<?php echo $objInfo->get_user_name_image_or_src_from_user_side_make_plan($user_id ,$hotel_id=1, $name="guest_fullname.png",$extra="guest/".$item_info['id']."/thumb1");?>" class="guest_name_hidden" />
													<?php echo $objInfo->get_user_name_image_or_src_from_user_side_make_plan($user_id ,$hotel_id=1, $name="guest_fullname.png",$extra="guest/".$item_info['id']."/thumb2");?>

													<!--<img src="user_name/thumb2/man_fullname.png" alt="<?=$guest['first_name']." ".$guest['last_name']." ".$rsp ;?>"  />
														<span style="display:none;font-size:<?=$fsize?>; text-align:left;"><?=$item_info['comment1']?>&nbsp;<?=$item_info['comment2']?></span><br/>
														<span style="font-size:<?=$fsize?>; text-align:left;"><?php echo $item_info['first_name']." ".$item_info['last_name']." ".$rspct;?></span>-->
                                      				 </a>

                                               </div>
											  </div>

                                        <?php
                                        }
                                    ?>

                                    </div>

                                <?php
									if($jor%2==0)
									{
										if($j==$rowspan)
										{
											?>
											<div style='float:left;text-align:center; width:25px; height:30px'>
											  <p align="center" style="text-align:center;width:15px;" id="table_<?=$table_row['id']?>">

                                    				<b><a href="javascript:void(0)" style="cursor:default; text-decoration:none"><span><?=$tblname1?></span><?=$tblname2?></a></b>
                               				 </p>
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
	  </form>
  </div>
  <div class="clear" style="float:left; clear:both; height:10px;"></div></div>
  <div id="makeplanbox_foot">
			<div id="makeplanbox_foot_submit">
			<?php
			if($button_enable==true) {?>
			<div align="right">
			<input id="button" type="button" value="保存" onclick="checkConfirm()">
			</div>
			<?php } ?>
			</div>
			<div style="clear:both;"></div>
	</div>

<?php
include("inc/new.footer.inc.php");
?>
