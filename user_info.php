<?php
@session_start();
include_once("admin/inc/dbcon.inc.php");
include_once("admin/inc/class_data.dbo.php");
include_once("inc/checklogin.inc.php");

$obj = new DataClass();
$get = $obj->protectXSS($_GET);
$user_id = (int)$_SESSION['userid'];
$table_data = $obj->get_table_data($user_id);  

//tabの切り替え
$tab_user_info = true;

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
		margin-left:-5px;
		margin-top:6px;
	}

	.tables p a
	{
	text-decoration:none;
	cursor:default;

	}

</style>
<div id="main_contents" class="displayBox">
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
		              <?php echo $objInfo->get_user_name_image_or_src_from_user_side($user_id ,$hotel_id=1, $name="man_fullname.png",$extra="thumb1");?>
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
		             <?php echo $objInfo->get_user_name_image_or_src_from_user_side($user_id ,$hotel_id=1, $name="woman_fullname.png",$extra="thumb1");?>
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
                    <?php
                        if(is_array($rooms))
                        {
                            foreach($rooms as $room)
                            {
								if($room['id']==$user_row['room_id'])
								{
								   $room_name = $room['name'];
								   $roomName = $room['name'];
								}


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
        &nbsp;

    <td width="619" style="width:450px; vertical-align:top">

        <div class="sekiji_table_L" id="plan_preview">

		<table width="80%" border="0" cellspacing="10" cellpadding="0">
		  <tr>
			<td nowrap="nowrap" style="border-bottom:1px solid #3681CB; text-align:left; "><strong>席次表</strong></td>
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
<div id="table_view" style="margin:auto;width :100%;">
<div style="margin:0 auto;width:100px;display:block;">
<div  style="margin: auto;width:80x;border:1px solid gray;white-space:nowrap;text-align:center; ">
<?php if ($table_data["layoutname"]!="") print $table_data["layoutname"]; else echo "&nbsp;&nbsp;&nbsp;";?>
</div>
              </div>
<?php
  $table_height = "31";
  for($i=0;$i<count($table_data["rows"]);++$i){
    $row = $table_data["rows"][$i];
    $width_all = 35*count($row["columns"]);
    $width = 31;
?>
<div style="width:<?php echo $width_all; ?>px;display:left;position:relative;margin:auto;">
    <div style="position:relative;overflow:hidden;<?php echo ($row['ralign']=='C')?'width:'.($width_all*$row['display_rate']).'px;margin:auto;':'';?>">
<?php
    for($j=0;$j<count($row["columns"]);++$j){
      $column = $row["columns"][$j];
      $column_num = $row['display_num'];
      $table_name = $column["name"];
      $table_id = $column["id"];
      $visible = $column["visible"];
      if($row["ralign"] == "C" && $column["display"] == 0 && !$visible) continue;
?>
      <div id="table<?=$table_id?>" class="tables" style="width:<?=$width;?>px;height:<?=$table_height?>px;float:left;<?php echo ($column["visible"])?"visibility:hidden":""?>;margin:2px;"><p>
      <?php
		$_nm2 = mb_substr($column["name"], 0,2,'UTF-8');
		$_han = 1;
		if (preg_match("/^[a-zA-Z0-9]+$/", $_nm2)) $_han = 2; // 先頭の２文字が全て半角
		
		echo mb_substr ($column["name"], 0,$_han,'UTF-8');
      ?>
      </p>
</div>
<?
    }
?>
    </div>
</div>
<?php
  }
?>
</div>
				</td>
              </tr>
            </table>
<br clear="all">
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
			<td nowrap="nowrap" style="border-bottom:1px solid #3681CB; text-align:left; "><strong>引出物</strong></td>
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
			<td align="left" nowrap="nowrap">締切日は披露宴の&nbsp;<?=$user_row['order_deadline']?> &nbsp;日前となります</td>
		  </tr>
			<?php
				$day = strftime('%d',strtotime($user_row['party_day']));
				$month = strftime('%m',strtotime($user_row['party_day']));
				$year = strftime('%Y',strtotime($user_row['party_day']));
				$lastmonth = mktime(0, 0, 0, $month, $day-$user_row['order_deadline'], $year);
				$dateBeforeparty = date("Y-m-d",$lastmonth);
			?>
		  <tr>
			<td align="left" nowrap="nowrap">締切予定日は&nbsp; <?=$obj->japanyDateFormate($dateBeforeparty)?>となります</td>
		  </tr>
		</table>

		<table width="80%" border="0" cellspacing="10" cellpadding="0">
		  <tr>
			<td nowrap="nowrap" style="border-bottom:1px solid #3681CB; text-align:left; "><strong>商品</strong></td>
		  </tr>
		</table>
        <table width="90%" border="0" cellspacing="10" cellpadding="0">
            <tr>
                <td width="10%" align="left" nowrap="nowrap">商品区分</td>
				<td width=" 5%" nowrap="nowrap" style="text-align:center"><font color="#2052A3"></font>：</td>
                <td width="85%" nowrap="nowrap" style="text-align:left">

                    	<?php if($user_plan_row['dowload_options'] == 1){?> 席次表<? }?>
                        <?php if($user_plan_row['dowload_options'] == 2){?> 席札<? }?>
                        <?php if($user_plan_row['dowload_options'] == 3){?> 席次表・席札<? }?>

                </td>
           </tr>
            <tr>
                <td align="left" nowrap="nowrap">商品名</td>
				<td nowrap="nowrap" style="text-align:center"><font color="#2052A3"></font>：</td>
                <td nowrap="nowrap" style="text-align:left"><?=$user_plan_row['product_name']?></td>
            </tr>

            <tr>
                <td align="left" nowrap="nowrap">サイズ</td>
				<td nowrap="nowrap" style="text-align:center"><font color="#2052A3"></font>：</td>
                <td nowrap="nowrap" style="text-align:left">
					<?php if($user_plan_row['print_size'] == 1){?>A3<? }?>
					<?php if($user_plan_row['print_size'] == 2){ ?>B4<? }?>
					<?php if($user_plan_row['print_type'] == 1){ ?>横<? }?>
					<?php if($user_plan_row['print_type'] == 2) { ?>縦<? }?>
				</td>
            </tr>
		</table>

	</div>
		<div>&nbsp;</div>
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
