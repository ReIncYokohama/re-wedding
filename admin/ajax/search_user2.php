<?php
@session_start();
require_once("../inc/include_class_files.php");
$obj = new DBO();
$objMsg = new MessageClass();
$objinfo = new InformationClass();

$post = $obj->protectXSS($_POST);

if($post['date_from'] != '')
{
	$date_from = $post['date_from'];
}
else
{
	$date_from = '';
}
if($post['date_to'] != '')
{
	$date_to = $post['date_to'];
}
else
{
	$date_to = '';
}

$table = 'spssp_user';
if($date_from != '' or $date_to != '')
{
	$qry = "select * from $table where stuff_id = ".$_SESSION['adminid'];
}
else
{
	$qry = "select * from $table where stuff_id = ".$_SESSION['adminid']." and  party_day >= '".date("Y-m-d")."'";
}
$mname = $post['mname'];
$wname = $post['wname'];


if($date_from != '' && $date_to != '')
{
	$qry .= " and party_day >= '".$date_from."' and party_day <= '".$date_to."'";
}

if($date_from != '' && $date_to == '')
{
	$qry .= " and party_day >= '".$date_from."'";
}
if($date_from == '' && $date_to != '')
{

	$qry .= " and party_day <= '".$date_to."'";
}
if($date_from == '' && $date_to == '')
{
	$qry .= "";
}

if($mname != '')
{
	$qry .= " and ( UPPER(man_lastname) like '%".strtoupper($mname)."%' or UPPER(man_firstname) like '%".strtoupper($mname)."%')";
}

if($wname != '')
{
	$qry .= " and (UPPER(woman_lastname) like '%".strtoupper($wname)."%' or UPPER(woman_firstname) like '%".strtoupper($wname)."%')";
}

if($_SESSION['user_type'] == 222)
{
	if ($qry!="") $qry.=" and stuff_id=".$_SESSION['adminid']; else $qry.=" stuff_id=".$_SESSION['adminid'];
}

if ($post['sortOptin']==NULL) 	$qry .=" order by party_day asc , party_day_with_time asc ";
else							$qry .=" order by ".$post['sortOptin'];

$rows = $obj->getRowsByQuery($qry);
//echo $qry;
$count_rows = count($rows);
if($count_rows > 5)
{
	$styles = "height:360px; overflow-y:auto;";
}
else
{
	$styles = "";
}
if(empty($rows))
{
?>
<!-- UCHIDA EDIT 11/08/08 検索数０でもタイトルだけは表示する  -->
<div class="box_table">
    <p>&nbsp;</p>

    <div class="box4">
        <table border="0" align="center" cellpadding="1" cellspacing="1">
                    <tr align="center">
                        <td width="70">披露宴日</td>
                        <td width="150">新郎氏名</td>
                        <td width="150">新婦氏名</td>
                    	<td width="60">詳細</td>
                        <td width="80">スタッフ</td>
                        <td width="60">メッセージ</td>
                        <td width="80">最終アクセス</td>
                        <td width="60">&nbsp;</td>
                        <td width="40">席次表</td>
                        <td width="40">引出物</td>
                        <td  width="40">削除</td>
                    </tr>
                </table>
    </div>
    </div>
<?php
}
else
{
?>
<div class="box_table" style="height:360px; overflow-y:auto;">
    <p>&nbsp;</p>

            <div class="box4" style="width:1000px;">
                <table width="100%" border="0" align="center" cellpadding="1" cellspacing="1" >
                    <tr align="center">
                        <td width="70">披露宴日<span class="txt1">
                        	<a href="javascript:void(0);" onclick="sortAction('party_day asc');">▲</a>
                        	<a href="javascript:void(0);" onclick="sortAction('party_day desc');">▼</a></span></td>
                        <td width="150" > 新郎氏名<span class="txt1">
                        	<a href="javascript:void(0);" onclick="sortAction('man_furi_lastname asc');">▲</a>
                        	<a href="javascript:void(0);" onclick="sortAction('man_furi_lastname desc');">▼</a></span>
                         </td>
                        <td width="150" align="center" >新婦氏名<span class="txt1">
                        	<a href="javascript:void(0);" onclick="sortAction('woman_furi_lastname asc');">▲</a>
                        	<a href="javascript:void(0);" onclick="sortAction('woman_furi_lastname desc');">▼</a></span>
                        </td>
                    	<td width="60" >詳細</td>
                        <td width="80" >スタッフ</td>
                        <td width="60" >メッセージ</td>
                        <td width="80" >最終アクセス</td>
                        <td width="60" >&nbsp;</td>
                        <td width="40" >席次表</td>
                        <td width="40" >引出物</td>
                        <td  width="40">削除</td>
                    </tr>
                </table>
            </div>
    <div class="box_table">
    <?php
			$i=0;
			foreach($rows as $row)
			{
				$roomname =  $obj->GetSingleData(" spssp_room", " name", " id=".(int)$data_rows['room_id']);
				$party_roomname = $obj->GetSingleData(" spssp_room", " name", " id=".(int)$data_rows['party_room_id']);

				include("../inc/main_dbcon.inc.php");
				$man_respect = $obj->GetSingleData(" spssp_respect", " title", " id=".(int)$data_rows['man_respect_id']);
				$woman_respect = $obj->GetSingleData(" spssp_respect", " title", " id=".(int)$data_rows['woman_respect_id']);
				include("../inc/return_dbcon.inc.php");

				$staff_name = $obj->GetSingleData("spssp_admin","name"," id=".$row['stuff_id']);

				if($i%2==0)
				{
					$class = 'box5';
				}
				else
				{
					$class = 'box6';
				}

				//$last_login = $obj->GetSingleData("spssp_user_log","max(login_time)"," id=".$row['id']);
				$last_login = $obj->GetSingleRow("spssp_user_log", " user_id=".$row['id']." and admin_id='0' ORDER BY login_time DESC");
				$user_messages = $obj->GetAllRowsByCondition("spssp_message"," user_id=".$row['id']);

				$admin_viewed = true;

				if(!empty($user_messages) )
				{
					foreach($user_messages as $msg)
					{
						if($msg['admin_viewed'] == 0)
						{
							$admin_viewed = false;
						}
					}
					if($admin_viewed== false)
					{
						$msg_opt = "<a href='message_user.php?user_id=".$row['id']."'><img src='img/common/btn_midoku.gif' border = '0'></a>";
					}
					else
					{
						$msg_opt = "<a href='message_user.php?user_id=".$row['id']."'><img src='img/common/btn_zumi.gif' border = '0'></a>";
					}

				}
				else
				{
					$msg_opt="";
				}

				$plan_row = $obj->GetSingleRow("spssp_plan", " user_id=".$row['id']);

				if(!empty($plan_row) && $plan_row['id'] > 0)
				{
					$conf_plan_row = $obj->GetSingleRow("spssp_plan_details", " plan_id=".$plan_row['id']);
					$user_guests = $obj->GetSingleRow("spssp_guest"," user_id=".$row['id']);
					if(!empty($conf_plan_row))
					{
						//$plan_link = "<a href='make_plan.php?plan_id=".$plan_row['id']."&user_id=".$row['id']."'><img src='img/common/btn_syori.gif' height='17' width='42' border='0' /></a>";
						$plan_link = "<img src='img/common/btn_syori.gif' height='17' width='42' border='0' />";
					}
					else
					{
						if(!empty($user_guests))
						{
							//$plan_link = "<a href='make_plan.php?plan_id=".$plan_row['id']."&user_id=".$row['id']."'><img src='img/common/btn_syori.gif' height='17' width='42' border='0' /></a>";
							$plan_link = "<img src='img/common/btn_syori.gif' height='17' width='42' border='0' />";
						}
						else
						{
							$plan_link = "<a href='javascript:void(0);' onclick='guestCheck();'><img src='img/common/btn_kousei.gif' height='17' width='42' border='0' /></a>";
						}
					}

					$layout_link = "<a href='set_table_layout.php?plan_id=".$plan_row['id']."&user_id=".(int)$row['id']."'><img src='img/common/btn_taku_ido.gif' boredr='0' width='52' height='17'> </a>";
				}
				else
				{
					$plan_link = "";
					$layout_link = "";
				}

			?>
            <div class="<?=$class?>" style="width:1000px;">
                  <table border="0" align="center" cellpadding="1" cellspacing="1" width="100%">
                    <tr align="center">
                        <td width="70"><?=$obj->japanyDateFormateShortWithWeek($row['party_day'] )?></td>

                         <td width="150">
						<?php
                          $man_name = $objinfo->get_user_name_image_or_src_from_ajax($row['id'] ,$hotel_id=1, $name="man_fullname.png",$extra="thumb1");
						  if($man_name==false){$man_name = $row['man_firstname']." ".$row['man_lastname'].' 様';}
						  echo $man_name;
					    ?>
						</td>
                        <td width="150">
						<?php
                          $woman_name = $objinfo->get_user_name_image_or_src_from_ajax($row['id'],$hotel_id=1 , $name="woman_fullname.png",$extra="thumb1");
						   if($woman_name==false){$woman_name = $row['woman_firstname']." ".$row['woman_lastname'].' 様';}
						   echo $woman_name;
					   ?>
						</td>
                    	<td width="60"><a href="user_info.php?user_id=<?=$row['id']?>"><img src="img/common/customer_info.gif" /></a></td>

                        <td width="80"> <?=$staff_name?></td>
                        <td width="60"> <?php echo $objMsg->get_admin_side_user_list_new_status_notification_usual($row['id']);?> </td>

                        <td width="80">
						<?php
// UCHIDA EDIT 11/08/04 'ログイン中' → ログイン時間
						if($last_login['login_time'] > "0000-00-00 00:00:00") {
							if($last_login['logout_time'] > "0000-00-00 00:00:00") {
								$dMsg = strftime('%m月%d日',strtotime($last_login['logout_time']));
								echo$dMsg;
							}else {
								$dMsg = strftime('%m月%d日',strtotime($last_login['login_time']));
								echo "<font color='#888888'>$dMsg</font>";
							}
					   	}
						?>
						</td>
                        <td class="txt1"  width="60">
                        	<a href="user_dashboard.php?user_id=<?=$row['id']?>" target="_blank"><img src="img/common/customer_view.gif" /></a>
                        </td>
                        <td width="40">
                        	<?php
							echo $objMsg->admin_side_user_list_new_status_notification_image_link_system($row['id']);
							?>
                        </td>
						<td width="40">
					<?php echo $objMsg->admin_side_user_list_gift_day_limit_notification_image_link_system($row['id']);?>
						</td>
                        <td width="40">
                        	<a href="javascript:void(0);" onclick="<?=$delete_onclick;?>" >
                        		<img src="img/common/btn_deleate.gif" width="42" height="17" />
                            </a>
                        </td>
                        </tr>
            	</table>
            </div>
            <?php
			$i++;
            }
			?>
     </div>
  </div>
  <?php
  	}
  ?>
