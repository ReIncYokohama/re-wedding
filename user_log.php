<?php
@session_start();
require_once("admin/inc/class.dbo.php");
include_once("admin/inc/dbcon.inc.php");
include_once("inc/checklogin.inc.php");
include("inc/new.header.inc.php");
$obj = new DBO();

	$user_id = (int)$_SESSION['userid'];
	$table='spssp_user_log';
	$where = " user_id=".$user_id;
	$data_per_page=10;
	$current_page=(int)$_GET['page'];
	$redirect_url = 'user_log.php?user_id='.$user_id;

	//$pageination = $obj->pagination($table, $where, $data_per_page,$current_page,$redirect_url);

	//$query_string="SELECT * FROM spssp_user_log where user_id=".$user_id." ORDER BY date DESC LIMIT ".((int)($current_page)*$data_per_page).",".((int)$data_per_page).";";
	$query_string="SELECT * FROM spssp_user_log where user_id=".$user_id." ORDER BY date ASC ";
	//echo $query_string;
	$data_rows = $obj->getRowsByQuery($query_string);
	//echo '<pre>';
	//print_r($data_rows);

// UCHIDA EDIT 11/07/28 ↓
	$q1 = "SELECT COUNT(*) FROM spssp_user_log where user_id=".$user_id.""; //全体件数を取得する
	$rnum1 = mysql_query($q1);
	list($num) = mysql_fetch_row($rnum1);
// UCHIDA EDIT 11/07/28 ↑

?>

<script>

	var title=$("title");
 $(title).html("お客様画面アクセスログ - お客様情報 - ウエディングプラス");

$(function(){
		$("ul#menu li").removeClass();
		$("ul#menu li:eq(7)").addClass("active");
	});
</script>

<div id="main_contents">
 <div class="title_bar">
    <div class="title_bar_txt_L">お客様画面アクセスログ</div>
    <div class="title_bar_txt_R"></div>
<div class="clear"></div></div>


<div style="text-align:center">

<div id="contents">
<!-- UCHIDA EDIT 11/07/28 ↓ -->
<!--
		<?php $data_user = $obj->GetSingleRow("spssp_user", "id=".$user_id);?>
 -->

        <div class="box_table">
<!--
      		<div style="text-align:left; width:875px"><?=$data_user['man_firstname']."-".$data_user['woman_firstname']?> 様</div>
 -->
      		<div style="text-align:left; width:875px">&nbsp;</div>
<!-- UCHIDA EDIT 11/07/28 ↑ -->

			<div style="text-align:right;"><?=$pageination?></div>

      		<div class="box4">
                <table border="0" width="875" align="center" cellpadding="3" cellspacing="1">
                    <tr align="center">
                        <td align="left" width="5%" bgcolor="#993300" style="color:#FFFFFF">No.</td>
<!--
                        <td align="left" width="22%" bgcolor="#993300" style="color:#FFFFFF">最終ログイン</td>
						<td align="left" width="22%" bgcolor="#993300" style="color:#FFFFFF">ログイン名</td>
                        <td align="left" width="22%" bgcolor="#993300" style="color:#FFFFFF">最後のログアウト</td>
 -->
                        <td align="left" width="22%" bgcolor="#993300" style="color:#FFFFFF">アクセス日時</td>
						<td align="left" width="22%" bgcolor="#993300" style="color:#FFFFFF">ログイン名</td>
                        <td align="left" width="22%" bgcolor="#993300" style="color:#FFFFFF">状態</td>
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
// UCHIDA EDIT 11/07/28
//					$stuff_name = $obj->GetSingleData(" spssp_admin ", "username", " id='".$row[admin_id]."'");
					$stuff_name = $obj->GetSingleData(" spssp_admin ", "name", " id='".$row[admin_id]."'");
				?>
                    <div class="<?=$class?>">
                        <table width="875"  border="0" align="center" cellpadding="1" cellspacing="1">
                            <tr align="center">
<!-- UCHIDA EDIT 11/07/28 ↓ -->
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

								echo "<td align='left' width='5%'>$j</td>";
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
										$j++; echo "<td align='left' width='5%'>$j</td>";
										echo "<td align='left' width='22%'>$time2</td>";
										echo "<td align='left' width='22%'>$msg</td>";
										echo "<td align='left' width='22%'>ログアウト</td>";
										echo "</tr>";
									}
									else {
											echo "<tr align='center'>";
											$j++; echo "<td align='left' width='5%'>$j</td>";
											echo "<td align='left' width='22%'style='font-size:100%;' >----/--/-- --:--:--</td>";
											echo "<td align='left' width='22%'>$msg</td>";
											echo "<td align='left' width='22%'>画面消去</td>";
											echo "</tr>";
									}
								}
							?>
<!-- UCHIDA EDIT 11/07/28 ↑ -->
                           </tr>
                        </table>
                    </div>
             <?php
			 	$i++;$j++;
			 	}
			 ?>
        </div>
    <div align="center"><a href="user_info.php?user_id=<?=$user_id?>">&lt;&lt;戻る</a></div> <!-- UCHIDA EDIT 11/07/28 -->
    </div>
</div>
</div>
<div style="height:18px; text-align:right"><a href="#">▲ページ上へ</a></div>
<?php
include("inc/new.footer.inc.php");
?>