<?php
@session_start();
include_once("admin/inc/dbcon.inc.php");
include_once("admin/inc/class.dbo.php");
include_once("inc/checklogin.inc.php");

$obj = new DBO();
$get = $obj->protectXSS($_GET);
$user_id = (int)$_SESSION['userid'];
include("inc/new.header.inc.php");

$user_row = $obj->GetSingleRow("spssp_user"," id= $user_id");
$rooms = $obj->GetAllRow("spssp_room");

$user_plan_row = $obj->GetSingleRow("spssp_plan"," user_id= $user_id");
$staff_name = $obj->GetSingleData("spssp_admin", "name"," id=".$user_row['stuff_id']);
?>
<script>

var title=$("title");
$(title).html("お客様情報 - ウエディングプラス");

$(function(){
		$("ul#menu li").removeClass();
		$("ul#menu li:eq(7)").addClass("active");
	});
</script>
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
		background-image:url(./admin/img/circle_small.jpg);

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
<div id="main_contents">
<div class="title_bar">
    <div class="title_bar_txt_L">お客様情報</div>
    <div class="title_bar_txt_R"></div>
<div class="clear"></div></div>


<div style=" text-align:center">

  <table width="85%" align="center" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="714" style="width:400px" valign="top" >

        <table width="90%" border="0" cellspacing="10" cellpadding="0">

<!-- UCHIDA EDIT 11/07/28 レイアウト変更 ↓ -->
            <tr>
                <td width="20%" align="left" valign="top" nowrap="nowrap">挙式日</td>
                <td width="10%" style="text-align:center"><font color="#2052A3"></font>：</td>
                <td width="70%"nowrap="nowrap" colspan="3" style="text-align:left">
                	<?=$obj->japanyDateFormate($user_row['marriage_day']) ?>
                </td>
			</tr>
			<tr>
                <td align="left" nowrap="nowrap">挙式時間</td>
                <td style="text-align:center"><font color="#2052A3"></font>：</td>
                <td nowrap="nowrap" colspan="3" style="text-align:left"><?=date("H:i",strtotime($user_row['marriage_day_with_time']))?>
                </td>
            </tr>
            <tr>
                <td align="left" valign="top" nowrap="nowrap">新郎氏名</td>
                <td style="text-align:center"><font color="#2052A3"></font>：</td>
                <td nowrap="nowrap" colspan="3" style="text-align:left">
                    <?=$user_row['man_lastname']?><?=$user_row['man_firstname']?> 様
                </td>
            </tr>
            <tr>
            	<td align="left" valign="top" nowrap="nowrap">ふりがな</td>
                <td style="text-align:center"><font color="#2052A3"></font>：</td>
           		<td nowrap="nowrap"  colspan="3" style="text-align:left">
            		<?=$user_row['man_furi_lastname']?><?=$user_row['man_furi_firstname']?>
                </td>
            </tr>
            <tr>
            	<td align="left" valign="top" nowrap="nowrap">新婦氏名</td>
                <td style="text-align:center"><font color="#2052A3"></font>：</td>
            	<td nowrap="nowrap"  colspan="3" style="text-align:left">
                	<?=$user_row['woman_lastname']?><?=$user_row['woman_firstname']?> 様
                </td>
            </tr>
            <tr>
            	<td align="left" valign="top" nowrap="nowrap">ふりがな</td>
                <td style="text-align:center"><font color="#2052A3"></font>：</td>
            	<td nowrap="nowrap"  colspan="3" style="text-align:left">
            		<?=$user_row['woman_furi_lastname']?><?=$user_row['woman_furi_firstname']?>
                </td>
            </tr>
            <tr>
                <td align="left" nowrap="nowrap">挙式種類</td>
                <td style="text-align:center"><font color="#2052A3"></font>：</td>
                <td nowrap="nowrap" colspan="3" style="text-align:left">

                        <?php
							$religions = $obj->GetAllRowsByCondition("spssp_religion", " 1=1 order by title asc");
							//print_r($religions);
							foreach($religions as $rel)
							{
								if($user_row['religion'] == $rel['id'])
								{
								   echo $rel['title'];
								}
							}
						?>
                </td>
				</tr>
				<tr>
				<td align="left" valign="top" nowrap="nowrap">挙式会場</td>
                <td style="text-align:center"><font color="#2052A3"></font>：</td>
                <td nowrap="nowrap" colspan="3" style="text-align:left">
                	<?php
						$party_rooms_name = $obj->GetSingleData("spssp_party_room","name"," id=".$user_row['party_room_id']);
					?>
					<?=$party_rooms_name?>

                </td>
            </tr>
            <tr>
                <td align="left" valign="top" nowrap="nowrap">披露宴日</td>
                <td style="text-align:center"><font color="#2052A3"></font>：</td>
                <td nowrap="nowrap" colspan="3" style="text-align:left">
                	<?=$obj->japanyDateFormate($user_row['party_day']);//strftime('%Y/%m/%d',strtotime($user_row['party_day']))?>
                </td>
				</tr>
				<tr>
                <td align="left" nowrap="nowrap">披露宴時間</td>
                <td style="text-align:center"><font color="#2052A3"></font>：</td>
                <td nowrap="nowrap" colspan="3" style="text-align:left"><?=date("H:i", strtotime($user_row['party_day_with_time']))?>
                </td>
            </tr>
            <tr>
                <td align="left" valign="top" nowrap="nowrap">披露宴会場</td>
                <td style="text-align:center"><font color="#2052A3"></font>：</td>
                <td nowrap="nowrap" colspan="3" style="text-align:left">
				<!--<select name="room_id" id="room_id">-->
                    <?php
                        if(is_array($rooms))
                        {
                            foreach($rooms as $room)
                            {
								if($room['id']==$user_row['room_id'])
								{
								  // echo $room['name'];
								   $room_name = $room['name'];
								   $roomName = $room['name'];
								}
								//else
								// echo "<option value ='".$room['id']."'> ".$room['name']."</option>";

                            }
                        }
						echo  $roomName;
                    ?>
                </td>
            </tr>
            <tr>
                <td align="left" valign="top" nowrap="nowrap">メールアドレス</td>
                <td style="text-align:center"><font color="#2052A3"></font>：</td>
                <td colspan="3" nowrap="nowrap" style="text-align:left"><?=$user_row['mail']?>

				</td>
            </tr>

            <tr>
                <td align="left" valign="top" nowrap="nowrap">ログインID</td>
                <td style="text-align:center"><font color="#2052A3"></font>：</td>
                <td nowrap="nowrap" colspan="3" nowrap="nowrap" style="text-align:left"><?=$user_row['user_id']?>

				</td>
            </tr>

            <tr>
                <td align="left" valign="top" nowrap="nowrap">担当</td>
                <td style="text-align:center"><font color="#2052A3"></font>：</td>
                <td colspan="3" nowrap="nowrap" style="text-align:left"><?=$staff_name?></td>
            </tr>
        </table>
		<table cellspacing="0" cellpadding="6">
		<?php 	if ($_SESSION['userid_admin'] == "") {?>
            <tr>
                <td  align="left" colspan="0" nowrap="nowrap">
                	<a href="changepassword.php" ><img src="img/btn_change_password_user.jpg" /></a>
                </td>
            </tr>
        <?php } ?>
            <tr>
            	<td  align="left" colspan="0" nowrap="nowrap">
                	<a href="user_log.php"><img src="img/btn_access_user.jpg" width="172" height="22" /></a>
                    <a href="change_log_all.php"><img src="img/btn_data_user.jpg" width="172" height="22" /></a>
                </td>
            </tr>
        </table>
        &nbsp;</td>

    <td width="619" style="width:450px; vertical-align:top">

        <div class="sekiji_table_L" id="plan_preview">

		<table width="80%" border="0" cellspacing="10" cellpadding="0">
		  <tr>
			<td style="border-bottom:1px solid #3681CB; text-align:left; "><strong>席次表</strong></td>
		  </tr>
		</table>

 		<table width="80%" border="0" cellspacing="10" cellpadding="0">
            <tr>
                <td align="left" valign="top" nowrap="nowrap">卓レイアウト</td>
            </tr>
		</table>

  			<table width="90%" align="left" border="0" cellpadding="10" cellspacing="0">
            	<tr>
                	<td style="border:1px solid #3681CB; padding:5px;" >
			<?php

            if(empty($user_plan_row))
            {

                foreach($room_plan_rows as $plan)
                {

				    $plan_row = $plan;
                    $num_layouts = $obj->GetNumRows("spssp_table_layout"," default_plan_id=".$plan_row['id']);

                   	$row_width = $plan_row['column_number'] *45;
                    echo "<div class='plans' id='plan_".$plan_row['id']."' style='width:".$row_width."px;margin:0 auto;'>";
					$default_layout_title = $obj->GetSingleData("spssp_options" ,"option_value" ," option_name='default_layout_title'");
					if($default_layout_title!="")
					{
						echo "<div style='display:block;text-align:center;width:100px;margin:0 auto;border:1px solid gray;'>".$default_layout_title."</div>";
					}
					else
					{
						echo "<p style='text-align:center'><img src='./admin/img/sakiji_icon/icon_takasago.gif' width='102' height='22' /></p>";
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
                  $tblname = $table['name'];
                }

              if($table["display"] == 1){
                $disp = 'display:block;';
              }else{
                $disp = "display:none";
              }
              
							echo "<div class='tables' style='".$disp."'><p>".mb_substr ($tblname, 0,1,'UTF-8')."</p></div>";
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
                  echo "<div id='user_layoutname' style='display:block;text-align:center;width:100px;margin:0 auto;border:1px solid gray;'>".$user_plan_row['layoutname']."</div>";
                }
              elseif($default_layout_title!="")
                {
                  echo "<div id='default_layout_title' style='display:block;text-align:center;width:100px;margin:0 auto;border:1px solid gray;'>".$default_layout_title."</div>";
                }
              else
                {
                  echo "<p id='img_default_layout_title' onclick='user_layout_title_input_show(\"img_default_layout_title\");' style='text-align:center'><img src='./admin/img/sakiji_icon/icon_takasago.gif' width='102' height='22' /></p>";
                }
              
              $tblrows = $obj->getRowsByQuery("select distinct row_order from spssp_table_layout where user_id= ".(int)$user_id);
              $num_tables = $obj->getSingleData("spssp_plan", "column_number"," user_id= $user_id");
              $rw_width = (int)($num_tables* 51);
              
              //$tblrows = $obj->getRowsByQuery("select distinct row_order from spssp_table_layout where user_id= ".(int)$user_id);
              
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
                      $styles = "width:".$wd."px;margin:0 auto;";
                    }
                  
                  echo "<div class='rows' style='width:100%;float; left; clear:both;'><div style='".$styles."'>";

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
                        }else if(($num_first <= $table_row["column_order"] && $table_row["column_order"]<=$num_last) || $ralign == "N" ){
                          $disp = "visibility:hidden";
                        }else{
                          $disp = "display:none";
                        }
                      echo "<div class='tables' style='".$disp."'><p>".mb_substr ($tblname, 0,1,'UTF-8')."</p></div>";
                    }
                  /*

                  $tables = $obj->getRowsByQuery("select * from spssp_table_layout where user_id= ".$user_id." and row_order=".$tblrow['row_order']);
                  $tblname='';
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
                          $tblname = $table['name'];
                        }
                      
                      
                      if($table['visibility']==1 && $table['display']==1)
                        {
                          
                          $disp = 'display:block;';
                          
                        }
                      else if($table['visibility']==0 && $table['display']==1)
                        {
                          $disp = 'visibility:hidden;';
                        }
                      else if($table['display']==0 && $table['visibility']==0)
                        {
                          $disp = 'display:none;';
                        }
                      
                      echo "<div class='tables' style='".$disp."'><p>".mb_substr ($tblname, 0,1,'UTF-8')."</p></div>";
                    }
                  */
                  echo "</div></div>";
                }
              
              echo "</div>";
              
            }
            ?>
				</td>
              </tr>
            </table>

        <table width="90%" border="0" cellspacing="10" cellpadding="0">

			<?
				$date6 = new DateTime($user_row['party_day']);
				$dateinterval = "-".$user_row['confirm_day_num']."day";
			?>

            <tr>
                <td align="left" nowrap="nowrap">本発注締切日は披露宴の
					<?=$user_row['confirm_day_num']?> 日前となります
                </td>
            </tr>

            <tr>
                <td align="left" nowrap="nowrap">本発注締切日は
                	<?
			            $date6->modify($dateinterval);
						echo $obj->japanyDateFormate($date6->format("Y/m/d")).'&nbsp;&nbsp;となります';
					?>

                </td>
            </tr>
        </table>
		<table width="80%" border="0" cellspacing="10" cellpadding="0">
		  <tr>
			<td style="border-bottom:1px solid #3681CB; text-align:left; "><strong>引出物</strong></td>
		  </tr>
		</table>

	 	<table width="80%" border="0" cellspacing="10" cellpadding="0">
			<?php
            	$gift_groups = $obj->GetAllRowsByCondition("spssp_gift_group","user_id=".(int)$user_id." order by id ASC");
				$gifts = $obj->GetAllRowsByCondition("spssp_gift","user_id=".$user_id." order by id ASC")
			?>
			<?php
			$gift_criteria = $obj->GetSingleRow("spssp_gift_criteria", " id=1");
			?>
		  <tr>
			<td align="left" nowrap="nowrap">締切日は披露宴の&nbsp;<?=$gift_criteria['order_deadline']?> &nbsp;日前となります</td>
		  </tr>
			<?php
				$day = strftime('%d',strtotime($user_row['party_day']));
				$month = strftime('%m',strtotime($user_row['party_day']));
				$year = strftime('%Y',strtotime($user_row['party_day']));
				$lastmonth = mktime(0, 0, 0, $month, $day-$gift_criteria['order_deadline'], $year);
				$dateBeforeparty = date("Y-m-d",$lastmonth);
			?>
		  <tr>
			<td align="left" nowrap="nowrap">締切予定日は&nbsp; <?=$obj->japanyDateFormate($dateBeforeparty)?>となります</td>
		  </tr>
		</table>

		<table width="80%" border="0" cellspacing="10" cellpadding="0">
		  <tr>
			<td style="border-bottom:1px solid #3681CB; text-align:left; "><strong>商品</strong></td>
		  </tr>
		</table>
        <table width="90%" border="0" cellspacing="10" cellpadding="0">
            <tr>
                <td width="10%" align="left" nowrap="nowrap">商品区分</td>
				<td width=" 5%" style="text-align:center"><font color="#2052A3"></font>：</td>
                <td width="85%" nowrap="nowrap" style="text-align:left">

                    	<?php if($user_plan_row['dowload_options'] == 1){?> 席次表<? }?>
                        <?php if($user_plan_row['dowload_options'] == 2){?> 席札<? }?>
                        <?php if($user_plan_row['dowload_options'] == 3){?> 席次表・席札<? }?>

                </td>
           </tr>
            <tr>
                <td align="left" nowrap="nowrap">商品名</td>
				<td style="text-align:center"><font color="#2052A3"></font>：</td>
                <td nowrap="nowrap" style="text-align:left"><?=$user_plan_row['product_name']?></td>
            </tr>

            <tr>
                <td align="left" nowrap="nowrap">サイズ</td>
				<td style="text-align:center"><font color="#2052A3"></font>：</td>
                <td nowrap="nowrap" style="text-align:left">
					<?php if($user_plan_row['print_size'] == 1){?>A3<? }?>
					<?php if($user_plan_row['print_size'] == 2){ ?>B4<? }?>
					<?php if($user_plan_row['print_type'] == 1){ ?>横<? }?>
					<?php if($user_plan_row['print_type'] == 2) { ?>縦<? }?>
				</td>
            </tr>
		</table>

	</div>
		<div">&nbsp;</div>
		<div">&nbsp;</div>
	</td>
<!-- UCHIDA EDIT 11/07/28 レイアウト変更 ↑ -->
  </tr>
   </table>
  <table>
</table>
</div>
</div>
<?php
include("inc/new.footer.inc.php");
?>
