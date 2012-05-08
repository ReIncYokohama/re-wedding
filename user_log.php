<?php
@session_start();
require_once("admin/inc/class.dbo.php");
include_once("admin/inc/dbcon.inc.php");
include_once("inc/checklogin.inc.php");
include_once("../fuel/load_classes.php");

$TITLE = "お客様画面アクセスログ - お客様情報 - ウエディングプラス";
include("inc/new.header.inc.php");
$obj = new DBO();

	$user_id = (int)$_SESSION['userid'];
	$table='spssp_user_log';
	$where = " user_id=".$user_id;
	$data_per_page=10;
	$current_page=(int)$_GET['page'];
	$redirect_url = 'user_log.php?user_id='.$user_id;
	$query_string="SELECT * FROM spssp_user_log where user_id=".$user_id." ORDER BY login_time ASC ";
	$data_rows = $obj->getRowsByQuery($query_string);

	$q1 = "SELECT COUNT(*) FROM spssp_user_log where user_id=".$user_id.""; //全体件数を取得する
	$rnum1 = mysql_query($q1);
	list($num) = mysql_fetch_row($rnum1);


?>

<script>

$(function(){
		$("ul#menu li").removeClass();
		$("ul#menu li:eq(7)").addClass("active");
	});
</script>

<div id="main_contents" class="displayBox">
 <div class="title_bar">
    <div class="title_bar_txt_L">お客様画面アクセスログ</div>
    <div class="title_bar_txt_R"></div>
<div class="clear"></div></div>


<div style="text-align:center">

<div id="contents">

        <div class="box_table">

      		<div style="text-align:left; width:875px">&nbsp;</div>

			<div style="text-align:right;"><?=$pageination?></div>

      		<div class="box4">
                <table border="0" width="875" align="center" cellpadding="3" cellspacing="1">
                    <tr align="center">
                        <td align="center" width="5%" bgcolor="#00C6FF" style="color:#FFFFFF">No.</td>
                        <td align="left" width="22%" bgcolor="#00C6FF" style="color:#FFFFFF">アクセス日時</td>
						<td align="left" width="22%" bgcolor="#00C6FF" style="color:#FFFFFF">ログイン名</td>
                        <td align="left" width="22%" bgcolor="#00C6FF" style="color:#FFFFFF">状態</td>
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
					if ($row[admin_id]>10000)  $stuff_name="印刷会社";
					else $stuff_name = $obj->GetSingleData(" spssp_admin ", "name", " id='".$row[admin_id]."'");
				?>
                    <div class="<?=$class?>">
                        <table width="875"  border="0" align="center" cellpadding="1" cellspacing="1">
                            <tr align="center">
							<?php
								if($stuff_name == "") {
									$msg = "お客様";
								}
								else {
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
									}
								}
							?>
                           </tr>
                        </table>
                    </div>
             <?php
			 	$i++;$j++;
			 	}
			 ?>
        </div>
        <div align="center"> 
        <table width="875px" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><div style="height:20px; text-align:right"><a href="#"><font size="3">▲ページ上へ</font></a></div></td>
  </tr>
  <tr>
    <td align="center"><a href="user_info.php?user_id=<?=$user_id?>&stuff_id=<?=$stuff_id?>"><img src="img/btn_back_user.gif" width="43" height="17" alt="戻る" /></a></td>
  </tr>
</table></div>
</div>

</div><br /><br />
</div>
<?php
include("inc/new.footer.inc.php");
?>
