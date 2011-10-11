<?php
	require_once("inc/class.dbo.php");
	include_once("inc/checklogin.inc.php");
	include_once("inc/new.header.inc.php");
	$obj = new DBO();
	$user_id = $_GET['user_id'];
	$table='spssp_user_log';
	$where = " user_id=".$user_id;
	$data_per_page=10;
	$current_page=(int)$_GET['page'];
	$redirect_url = 'user_log.php?user_id='.$user_id;
	$stuff_id= (int)$_GET['stuff_id'];

	//$pageination = $obj->pagination($table, $where, $data_per_page,$current_page,$redirect_url);

	if($_SESSION['user_type'] == 222)
	{
		$data = $obj->GetAllRowsByCondition("spssp_user"," stuff_id=".(int)$_SESSION['adminid']);
		foreach($data as $dt)
		{
			$staff_users[] = $dt['id'];
		}
		if(!empty($staff_users))
		{
			if(in_array($user_id,$staff_users))
			{
				$var = 1;
			}
			else
			{
				$var = 0;
			}
		}

	}
	else
	{
		$var = 1;
	}


	//$query_string="SELECT * FROM spssp_user_log where user_id=".$user_id." ORDER BY date DESC LIMIT ".((int)($current_page)*$data_per_page).",".((int)$data_per_page).";";
	$query_string="SELECT * FROM spssp_user_log where user_id=".$user_id." ORDER BY login_time ASC ";
	//echo $query_string;
	$data_rows = $obj->getRowsByQuery($query_string);

	//echo '<pre>';
	//print_r($data_rows);

// UCHIDA EDIT 11/08/04 ↓
	$q1 = "SELECT COUNT(*) FROM spssp_user_log where user_id=".$user_id.""; //全体件数を取得する
	$rnum1 = mysql_query($q1);
	list($num) = mysql_fetch_row($rnum1);

// UCHIDA EDIT 11/08/04 ↑
?>
<div id="topnavi">
    <?php
include("inc/main_dbcon.inc.php");
$hcode=$HOTELID;
$hotel_name = $obj->GetSingleData(" super_spssp_hotel ", " hotel_name ", " hotel_code=".$hcode);
?>
<h1><?=$hotel_name?></h1>
<?
include("inc/return_dbcon.inc.php");
?>

    <div id="top_btn">
        <a href="logout.php"><img src="img/common/btn_logout.jpg" alt="ログアウト" width="102" height="19" /></a>　
        <a href="javascript:;" onclick="MM_openBrWindow('../support/operation_h.html','','scrollbars=yes,width=620,height=600')"><img src="img/common/btn_help.jpg" alt="ヘルプ" width="82" height="19" /></a>
    </div>
</div>

<div id="container">
<a href="#top"> </a>
<div style="clear:both;"></div>
	<div id="contents">
    <?php
    $data_user = $obj->GetSingleRow("spssp_user", "id=".$user_id);
	require_once("inc/include_class_files.php");
	$objInfo = new InformationClass();
    ?>
  <?php  echo $objInfo->get_user_name_image_or_src($data_user['id'] ,$hotel_id=1, $name="man_lastname.png",$extra="thumb1",$height=20);?>
・
  <?php  echo $objInfo->get_user_name_image_or_src($data_user['id'] ,$hotel_id=1, $name="woman_lastname.png",$extra="thumb1",$width=20);?>
  様
		 <h4>
<!-- UCHIDA EDIT 11/08/02
            <a href="users.php">お客様一覧 </a> &raquo; <a href="user_info_allentry.php?user_id=<?=$user_id?>">お客様画面挙式情報 </a> &raquo; お客様画面アクセスログ
 -->
		<?php
		if($stuff_id==0) {?>
            <a href="manage.php">ＴＯＰ</a> &raquo; <a href="user_info_allentry.php?user_id=<?=$user_id?>&stuff_id=<?=$stuff_id?>">お客様挙式情報 </a> &raquo; お客様画面アクセスログ
		<?php }
		else {?>
            <a href="users.php">管理者用お客様一覧</a> &raquo; <a href="user_info_allentry.php?user_id=<?=$user_id?>&stuff_id=<?=$stuff_id?>">お客様挙式情報 </a> &raquo; お客様画面アクセスログ
		<?php }
		?>

        </h4>
		<h2>お客様画面アクセスログ</h2>

<!-- UCHIDA EDIT 11/08/04 ↓ -->

        <div class="box_table">

</div>

<!-- UCHIDA EDIT 11/08/04 ↑ -->
			<div style="text-align:right;"><?=$pageination?></div>

      		<div class="box4">
                <table border="0" width="100%" align="center" cellpadding="1" cellspacing="1">
                    <tr align="center">
                        <td align="center" width="4%" bgcolor="#2252A3" style="color:#FFFFFF">No.</td>
<!-- UCHIDA EDIT 11/08/04
                        <td align="left" width="22%" bgcolor="#2252A3" style="color:#FFFFFF">最終ログイン</td>
						<td align="left" width="22%" bgcolor="#2252A3" style="color:#FFFFFF">ログイン名</td>
                        <td align="left" width="22%" bgcolor="#2252A3" style="color:#FFFFFF">最後のログアウト</td>
 -->
                        <td align="left" width="22%" bgcolor="#2252A3" style="color:#FFFFFF">アクセス日時</td>
						<td align="left" width="22%" bgcolor="#2252A3" style="color:#FFFFFF">ログイン名</td>
                        <td align="left" width="22%" bgcolor="#2252A3" style="color:#FFFFFF">状態</td>

                    </tr>
                </table>
        	</div>
            <?php
				$i=0;$j=$current_page*$data_per_page+1;
				foreach($data_rows as $row)
				{
					if($i%2==0)
					{
						$class = 'box5';
					}
					else
					{
						$class = 'box6';
					}
//					$stuff_name = $obj->GetSingleData(" spssp_admin ", "username", " id='".$row[admin_id]."'"); // UCHIDA EDIT 11/08/04
					if ($row[admin_id]>10000)  $stuff_name="印刷会社";
					else $stuff_name = $obj->GetSingleData(" spssp_admin ", "name", " id='".$row[admin_id]."'");
					?>
                    <div>
                        <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
                            <tr align="center">
<!-- UCHIDA EDIT 11/08/04 ↓
                            <td align="center" width="5%"><?=$j?></td>
                            <td align="left" width="22%"><?=str_replace('-','/',$row['login_time'])?></td>
							<td align="left" width="22%"><?php if($stuff_name!="") echo $stuff_name; else echo "お客様"; ?></td>
                            <td align="left" width="22%"><?=str_replace('-','/',$row['logout_time'])?></td>
-->
							<?php
							if($stuff_name == "") {
									$msg = "お客様";
								}
								else {
// UCHIDA EDIT 11/08/09 名前だけの表示に変更
//									if($obj->GetSingleData(" spssp_admin ", "permission", " id='".$row[admin_id]."'") == "111")
//										$msg = "$stuff_name (SPS Stuff)"; else $msg = "$stuff_name (Hotel Stuff)";
									$msg = $stuff_name;
								}

								echo "<td align='center' width='5%'>$j</td>";
								$time = str_replace('-','/',$row['login_time']); echo "<td align='left' width='22%'>$time</td>";
								echo "<td align='left' width='22%'>$msg</td>";
								if ($msg != "お客様") {
									echo "<td align='left' width='22%'>「お客様入力画面」より</td>";
								}
								else {
									echo "<td align='left' width='22%'>ログイン</td>";
								}
                        		if ($i+1 != $num) { // 最後のログアウトはログイン中なので表示対象にしない
									$time2 = str_replace('-','/',$row['logout_time']);
									if ($time2 != "0000/00/00 00:00:00" and $time != $time2) {
										echo "<tr align='center'>";
										$j++; echo "<td align='center' width='5%'>$j</td>";
										echo "<td align='left' width='22%'>$time2</td>";
										echo "<td align='left' width='22%'>$msg</td>";
										echo "<td align='left' width='22%'>ログアウト</td>";
										echo "</tr>";
									}
									else {
											echo "<tr align='center'>";
											$j++; echo "<td align='center' width='5%'>$j</td>";
											echo "<td align='left' width='22%'style='font-size:100%;' >----&nbsp;/&nbsp;--&nbsp;/&nbsp;--&nbsp;&nbsp;--:&nbsp;--:&nbsp;--</td>";
											echo "<td align='left' width='22%'>$msg</td>";
											echo "<td align='left' width='22%'>画面消去</td>";
											echo "</tr>";
									}
								}
							?>
<!-- UCHIDA EDIT 11/08/04 ↑ -->
                        	</tr>
                        </table>
                    </div>
             <?php
             $i++;$j++;
			 	}
			 ?>
        </div>
        <div align="center"> 
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><div style="height:20px; text-align:right"><a href="#">▲ページ上へ</a></div></td>
  </tr>
  <tr>
    <td align="center"><a href="user_info_allentry.php?user_id=<?=$user_id?>&stuff_id=<?=$stuff_id?>"><img src="img/common/btn_back.gif" width="43" height="17" alt="戻る" /></a></td>
  </tr>
</table></div>
</div>

</div></div>

<?php
	include_once("inc/left_nav.inc.php");
?>


<?php
	include_once("inc/new.footer.inc.php");
?>
