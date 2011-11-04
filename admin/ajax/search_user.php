<?php
@session_start();
require_once("../inc/include_class_files.php");
$obj = new DBO();
$objMsg = new MessageClass();
$objinfo = new InformationClass();

$post = $obj->protectXSS($_POST);

//	echo $post['action']." : ".$post['user_id']." : ".$post['sortOptin']." : ".$post['date_from']." : ".$post['date_to']." : ".$post['mname']." : ".$post['wname']." : ".$post['view'];
	if($post['action']=='delete_user' && $post['user_id'] > 0)
	{
		$objinfo->delete_user_relation_table((int)$post['user_id']);
	}

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
if($post['mdate'] != '')
{
	$mdate = $post['mdate'];
}
else
{
	$mdate = '';
}
$mname = $post['mname'];
$wname = $post['wname'];

$current_view = $post['view'];

$qry="SELECT spssp_user.*, spssp_admin.name FROM spssp_user INNER JOIN spssp_admin ON spssp_user.stuff_id = spssp_admin.id ";

$today = date("Y/m/d");

	if($current_view=="before") {
		if ($date_from >= $today && $date_from!="") $date_from = $today;
		if ($date_to >= $today && $date_to!="") $date_to = $today;
	}
	else {
		if ($date_from < $today && $date_from!="") $date_from = $today;
		if ($date_to < $today && $date_to!="") $date_to = $today;
	}

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
	if($current_view=="before") {
		$qry .= " and party_day < '".$today."'";
	}
	else {
		$qry .= " and party_day >= '".$today."'";
	}
}
if($mdate !='')
{
	$qry .= " and party_day = '".$mdate."'";
}

if($mname != '')
{
	$qry .= " and ( UPPER(man_lastname) like '%".strtoupper($mname)."%' or UPPER(man_firstname) like '%".strtoupper($mname)."%')";
}

if($wname != '')
{
	$qry .= " and (UPPER(woman_lastname) like '%".strtoupper($wname)."%' or UPPER(woman_firstname) like '%".strtoupper($wname)."%')";
}
$sortOptin = $post['sortOptin'];
if ($sortOptin==NULL) 	$qry .=" order by party_day asc , party_day_with_time asc ";
else {
	$sortOptin = str_replace("+", ",", $post['sortOptin']); // +を,変換(,はPOST中に消滅する)
	$qry .=" order by ".$sortOptin;
}

//echo $qry." : ".$current_view." : ".$today;
$rows = $obj->getRowsByQuery($qry);

$count_rows = count($rows);
if($count_rows > 5)
{
	$styles = "height:270px; overflow:auto";
}
else
{
	$styles = "";
}

 	if($current_view=="before") {
//		$passPresent = "<a href='users.php'><font color='#2052A3'><strong>本日以降のお客様一覧</strong></font></a>";
		$passPresent = "<a href='users.php'><img src='img/common/btn_honjitsu_on.jpg' /></a>";
		$passPresent .= "<img src='img/common/btn_kako.jpg' /></a>";
 	}
	else {
//		$passPresent = "<a href='users.php?view=before'><font color='#2052A3'><strong>過去のお客様一覧</strong></font></a>";
		$passPresent = "<img src='img/common/btn_honjitsu.jpg' /></a>";
		$passPresent .= "<a href='users.php?view=before'><img src='img/common/btn_kako_on.jpg' /></a>";
	}

if(empty($rows))
{
?>
<div class="box_table">

	<div id="passPresent"> <?php echo $passPresent; ?> </div>
    <div class="box4">
        <table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">
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
 <?php
	if($_SESSION['user_type'] == 111  || $_SESSION['user_type'] == 333)
	{
?>
                        <td  width="40">削除</td>
<?php
	}
?>
                    </tr>
                </table>
    </div>
    </div>
<?php
}
else
{
?>
<div id="passPresent"> <?php echo $passPresent; ?> </div>

<div class="box_table" style="height:485px;">

			<div class="box4" style="width:1000px;" >
                <table width="100%" border="0" align="center" cellpadding="1" cellspacing="1">
                    <tr align="center">
                        <td width="70">披露宴日<span class="txt1">
                        	<a href="javascript:void(0);" onclick="sortAction('party_day asc + party_day_with_time asc');">▲</a>
                        	<a href="javascript:void(0);" onclick="sortAction('party_day desc + party_day_with_time desc');">▼</a></span>
                        </td>
                        <td width="150" > 新郎氏名<span class="txt1">
                        	<a href="javascript:void(0);" onclick="sortAction('man_furi_lastname asc');">▲</a>
                        	<a href="javascript:void(0);" onclick="sortAction('man_furi_lastname desc');">▼</a></span>
                        </td>
                        <td width="150" align="center" >新婦氏名<span class="txt1">
                        	<a href="javascript:void(0);" onclick="sortAction('woman_furi_lastname asc');">▲</a>
                        	<a href="javascript:void(0);" onclick="sortAction('woman_furi_lastname desc');">▼</a></span>
                        </td>
                    	<td width="60">詳細</td>
                        <td  width="80">スタッフ<span class="txt1">
                        	<a href="javascript:void(0);" onclick="sortAction('stuff_id asc');">▲</a>
                        	<a href="javascript:void(0);" onclick="sortAction('stuff_id desc');">▼</a></span>
						</td>                        <td width="60">メッセージ</td>
						<!--<td>ログイン</td>-->
                        <td width="80">最終アクセス</td>
                        <td width="60">&nbsp;</td>
                        <td width="40">席次表</td>
                        <td width="40">引出物</td>
                        <td width="40">削除</td>
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

				//$staff_name = $obj->GetSingleData("spssp_admin","name"," id=".$row['stuff_id']);

				if($i%2==0)
				{
					$class = 'box5';
				}
				else
				{
					$class = 'box6';
				}

				$last_login = $last_login = $obj->GetSingleRow("spssp_user_log", " user_id=".$row['id']." and admin_id='0' ORDER BY login_time DESC");
				$user_messages = $obj->GetAllRowsByCondition("spssp_message"," user_id=".$row['id']);

			?>
            <div class="<?=$class?>" style="width:1000px;">
                  <table border="0" align="center" cellpadding="1" cellspacing="1" width="100%">
                    <tr align="center">
                        <td width="70"><?=$obj->japanyDateFormateShortWithWeek($row['party_day'] )?></td>
                        <td width="150" align="left">
						<?php
                          $man_name = $objinfo->get_user_name_image_or_src_from_ajax($row['id'] ,$hotel_id=1, $name="man_fullname.png",$extra="thumb1");
						  if($man_name==false){$man_name = $row['man_firstname']." ".$row['man_lastname'].' 様';}
						  echo $man_name;
					    ?>
					    </td>
                        <td width="150" align="left">
						<?php
                          $woman_name = $objinfo->get_user_name_image_or_src_from_ajax($row['id'],$hotel_id=1 , $name="woman_fullname.png",$extra="thumb1");
						   if($woman_name==false){$woman_name = $row['woman_firstname']." ".$row['woman_lastname'].' 様';}
						   echo $woman_name;
					    ?>
						</td>
                    	<td width="60"><a href="user_info_allentry.php?user_id=<?=$row['id']?>"><img src="img/common/customer_info.gif" /></a></td>

                        <td width="80"> <?=$row['name']?></td>
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
                        <!--<td><a href="gift_user.php?user_id=<?=$row['id'];?>"><img src="img/common/btn_kentou.gif" width="42" height="17" /></a></td>-->
						<td width="40">
					<?php echo $objMsg->admin_side_user_list_gift_day_limit_notification_image_link_system($row['id']);?>
						</td>
                        <td width="40">
                        	<a href="javascript:void(0);" onclick="confirmDeleteUser(<?=$row['id']?>);" >
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
