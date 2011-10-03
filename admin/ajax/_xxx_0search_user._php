<?php
@session_start();
include_once('../inc/dbcon.inc.php');
include_once('../inc/class.dbo.php');
$obj = new DBO();

$post = $obj->protectXSS($_POST);

if($post['date_from'] != '')
{
	$date_from = $post['date_from'];
	$date_to = $post['date_to'];
}
else
{
	$date_from = '';
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

$table = 'spssp_user';

$qry = "select * from $table where 1= 1 ";

if($date_from != '' && $date_to != '')
{
	$qry .= " and marriage_day >= '".$date_from."' and marriage_day <= '".$date_to."'";
}

if($mdate !='')
{
	$qry .= " and marriage_day = '".$mdate."'";
}

if($mname != '')
{
	$qry .= " and ( UPPER(man_lastname) like '%".strtoupper($mname)."%' or UPPER(man_firstname) like '%".strtoupper($mname)."%')";
}

if($wname != '')
{
	$qry .= " and (UPPER(woman_lastname) like '%".strtoupper($wname)."%' or UPPER(woman_firstname) like '%".strtoupper($wname)."%')";
}

if(isset($post['today']) && $post['today'] != '')
{
	$qry = " select * from $table where marriage_day='".date('Y-m-d')."'";
}

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
if(empty($rows))
{
	echo "<h2 style='text-align:center; color:red; font-weight:bold;'>該当するデータはありません</h2>";
}
else
{
?>
<div class="box_table">
    <p>&nbsp;</p>
    <div
    <div class="box4">
        <table border="0" align="center" cellpadding="1" cellspacing="1">
            <tr align="center">
            	<td>編集</td>
                <td>挙式日</td>
                <td>新郎氏名</td>
                <td>新婦氏名</td>
                <td>&nbsp;</td>
                <td>スタッフ</td>
                <td>メッセージ</td>
                <td>&nbsp;</td>
                <td>最終アクセス</td>
                <td>席次表</td>
                <td>引出物</td>
                
                <td>削除</td>
            </tr>
        </table>
    </div>
    <div class="box_table" style="<?=$styles?>">
    <?php
			$i=0;
			foreach($rows as $row)
			{	
				$roomname =  $obj->GetSingleData(" spssp_room", " name", " id=".(int)$data_rows['room_id']);
				$party_roomname = $obj->GetSingleData(" spssp_room", " name", " id=".(int)$data_rows['party_room_id']);
				
				$man_respect = $obj->GetSingleData(" spssp_respect", " title", " id=".(int)$data_rows['man_respect_id']);
				$woman_respect = $obj->GetSingleData(" spssp_respect", " title", " id=".(int)$data_rows['woman_respect_id']);
				
				$staff_name = $obj->GetSingleData("spssp_admin","name"," id=".$row['stuff_id']);
				
				if($i%2==0)
				{
					$class = 'box5';
				}
				else
				{
					$class = 'box6';
				}
				$last_login = $obj->GetSingleData("spssp_user_log","max(login_time)"," id=".$row['id']);
				
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
						$plan_link = "<a href='make_plan.php?plan_id=".$plan_row['id']."&user_id=".$row['id']."'><img src='img/common/btn_syori.gif' height='17' width='42' border='0' /></a>";					
						
					}
					else
					{
						if(!empty($user_guests))
						{
							$plan_link = "<a href='make_plan.php?plan_id=".$plan_row['id']."&user_id=".$row['id']."'><img src='img/common/btn_syori.gif' height='17' width='42' border='0' /></a>";
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
            <div class="<?=$class?>">
                <table border="0" align="center" cellpadding="1" cellspacing="1">
                    <tr align="center">
                    	<td><a href="user_info.php?user_id=<?=$row['id']?>"><img src="img/common/btn_edit.gif" width="42" height="17" /></a></td>
                        <td><?=$row['marriage_day'].",".date("D",mktime($row['marriage_day']))?></td>
                        <td><?=$row['man_firstname']." ".$row['man_lastname'].' 様';?></td>
                        <td><?=$row['woman_firstname']." ".$row['woman_lastname'].' 様';?></td>
                        
                        <td class="txt1">
                        	<a href="user_dashboard.php?user_id=<?=$row['id']?>" target="_blank"><img src="img/common/btn_info.gif" width="42" height="17" /></a>
                        </td>
                        <td> <?=$staff_name?></td>
                        <td> <?=$msg_opt;?> </td>
                        <td>
                        	<?=$layout_link?>
                        </td>
                        <td><?=date("Y-m-d", mktime($last_login));?></td>
                        <td>
                        	<?=$plan_link;?>
                        </td>
                        <td><a href="gift_user.php?user_id=<?=$row['id'];?>"><img src="img/common/btn_kentou.gif" width="42" height="17" /></a></td>
                        <td>
                        	<a href="javascript:void(0);" onclick="confirmDelete('users.php?action=delete_user&page=<?=(int)$_GET['page']?>&id=<?=$row['id']?>')">
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
