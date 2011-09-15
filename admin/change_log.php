<?php
	require_once("inc/class.dbo.php");
	include_once("inc/checklogin.inc.php");
	include_once("inc/new.header.inc.php");
	$obj = new DBO();

	$user_id=$_GET[user_id];
	$table='spssp_change_log';
	$where = " user_id=".$user_id." and (guest_id in (select id from spssp_guest where user_id=".$user_id.") or type='3')";
	$stuff_id= (int)$_GET['stuff_id'];

	if($_GET['guest_id'])
	{
		$where.= " and guest_id='".(int)$_GET['guest_id']."'";
	}

	if($_GET['date'])
	{
		$where.= " and date like '".$_GET['date']."%'";
	}

	$data_per_page=10;
	$current_page=(int)$_GET['page'];
	$redirect_url = 'change_log.php?guest_id='.$_GET['guest_id'].'&date='.$_GET['date'];

	//$pageination = $obj->pagination($table, $where, $data_per_page,$current_page,$redirect_url);



	$order="date ASC";
	//$query_string="SELECT * FROM spssp_guest_log where $where ORDER BY $order LIMIT ".((int)($current_page)*$data_per_page).",".((int)$data_per_page).";";
	$query_string="SELECT * FROM spssp_change_log where $where ORDER BY $order ;";
	$data_rows = $obj->getRowsByQuery($query_string);


?>
<style>
.datepickerControl table
{
width:200px;
}
.input_text
{
	width:100px;
}
.select
{
	width:122px;
}
.datepicker
{
width:100px;
}
.timepicker
{
width:100px;
}
</style>
<script src="../js/noConflict.js" type="text/javascript"></script>
<script type="text/javascript" src="calendar/calendar.js"></script>


<script type="text/javascript" language="javascript" src="../datepicker/prototype-1.js"></script>

<script type="text/javascript" language="javascript" src="../datepicker/prototype-date-extensions.js"></script>
<script type="text/javascript" language="javascript" src="../datepicker/behaviour.js"></script>

<script type="text/javascript" language="javascript" src="../datepicker/datepicker.js"></script>
<script type="text/javascript">

Control.DatePicker.Locale['ahad'] = { dateTimeFormat: 'yyyy-MM-dd HH:mm', dateFormat: 'yyyy-MM-dd', firstWeekDay: 1, weekend: [0,6], language: 'ahad'};

Control.DatePicker.Language['ahad'] = { months: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'], days:[  '日', '月','火', '水', '木', '金','土'], strings: { 'Now': '今度', 'Today': '今日', 'Time': '時間', 'Exact minutes': '正確な分', 'Select Date and Time': '閉じる', 'Open calendar': 'オープンカレンダー' } };



</script>

<link rel="stylesheet" href="../datepicker/datepicker.css">
<script type="text/javascript" language="javascript" src="../datepicker/behaviors.js"></script>

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
<!-- UCHIDA EDIT 11/08/04
        <a href="users.php">お客様一覧 </a> &gt;&gt; <a href="user_info.php?user_id=<?=$user_id?>">お客様挙式情報 </a> &gt;&gt; 席次データ修正ログ
 -->
		<?php
		if($stuff_id==0) {?>
            <a href="manage.php">ＴＯＰ</a> &raquo; <a href="user_info.php?user_id=<?=$user_id?>&stuff_id=<?=$stuff_id?>">お客様挙式情報 </a> &raquo; 席次表データ修正ログ
		<?php }
		else {?>
            <a href="users.php">管理者用お客様一覧</a> &raquo; <a href="user_info.php?user_id=<?=$user_id?>&stuff_id=<?=$stuff_id?>">お客様挙式情報 </a> &raquo; 席次表データ修正ログ
		<?php }
		?>

     </h4>
	 <h2>席次表データ修正ログ</h2>

		<!--<form action="change_log.php" method="get">
		<input type="hidden" name="user_id" value="< ?=$_GET[user_id]?>"  />
           <table  cellpadding="5" cellspacing="5" style="width:600px;" >
		   <tr>
				<td  style="width:200px;"  valign="top"><label for="Guest Name">招待者名:</label>

					<select name="guest_id" id="guest_id">
					<option value="">選択</option>
					< ?php
					$query_string="SELECT last_name,first_name,id FROM spssp_guest where user_id=".$user_id.";";
					$guest_rows = $obj->getRowsByQuery($query_string);
					foreach($guest_rows as $guest)
						{
					?>
							<option value="< ?=$guest[id]?>" < ?php if($_GET['guest_id']==$guest[id]) { ?> selected="selected" < ?php } ?>>< ?=$guest[last_name]." ".$guest[first_name]?></option>
					< ?php
						}
					?>
					</select>
			　	</td>

				<td  style="width:200px;"  valign="top"><label for="Date">日付</label>
						<input type="text" name="date" value="< ?=$_GET['date']?>" id="date"  style="background: url('img/common/icon_cal.gif') no-repeat scroll right center rgb(255, 255, 255); padding-right: 20px;" class="datepicker"/>

				　	</td>
				</td>

				<td width="100" valign="top">
				<input type="submit" name="submit" value="検索" />

				　</td>


			</tr>

		</table>
		</form>
		-->

        <div class="box_table">

			<!--<div style="text-align:right;">< ?=$pageination?></div>-->

      		<div class="box4">
                <table border="0" width="1000px" align="center" cellpadding="1" cellspacing="1">
                    <tr align="center">

                        <!--<td  width="10%">Plan name</td>-->
                        <td  width="15%" bgcolor="#2252A3" style="color:#FFFFFF">アクセス日時</td>
                        <td  width="15%" bgcolor="#2252A3" style="color:#FFFFFF">ログイン名</td>
                        <td  width="13%" bgcolor="#2252A3" style="color:#FFFFFF">アクセス画面名</td>
                        <td  width="13%" bgcolor="#2252A3" style="color:#FFFFFF">修正対象者</td>
						<td  width="13%" bgcolor="#2252A3" style="color:#FFFFFF">修正種別</td>
						<td  width="10%" bgcolor="#2252A3" style="color:#FFFFFF">変更項目名</td>
						<td  width="10%" bgcolor="#2252A3" style="color:#FFFFFF">変更前情報</td>
						<td  width="10%" bgcolor="#2252A3" style="color:#FFFFFF">変更後情報</td>


                    </tr>
                </table>
        	</div>
            <?php
				$i=0;$j=$current_page*$data_per_page+1;
				foreach($data_rows as $row)
				{
					if($i%2==0)
					{
//						$bgcolor="#CEFFFF"; // UCHIDA EDIT 11/08/10 色は変えない
						$bgcolor="#FFFFFF";
					}
					else
					{
						$bgcolor="#FFFFFF";
					}
					$guest_name2=$obj->GetSingleData("spssp_guest","last_name"," id='".$row['guest_id']."'");
					$guest_name=$obj->GetSingleData("spssp_guest","first_name"," id='".$row['guest_id']."'");

					$guest_name.="&nbsp;".$guest_name2;
//					$stuff_name = $obj->GetSingleData(" spssp_admin ", "username", " id='".$row[admin_id]."'"); // UCHIDA EDIT 11/08/04
					$stuff_name = $obj->GetSingleData(" spssp_admin ", "name", " id='".$row[admin_id]."'");
			?>
                    <div  style="background:<?=$bgcolor?>">
                        <table width="1000px"  border="0" align="center" cellpadding="4" cellspacing=4">
                            <tr align="left">
                           <td  width="15%">
                            	&nbsp;<?=$row[date]?>
                            </td>
							<td  width="15%">
<!-- UCHIDA EDIT 11/08/04  -->
<!--                             	<?php if($stuff_name!="") echo $stuff_name; else echo "お客様"; ?> -->
								<?php
								if($stuff_name == "") {
									$msg = "お客様";
								}
								else {
// UCHIDA EDIT 11/08/09
//									if($obj->GetSingleData(" spssp_admin ", "permission", " id='".$row[admin_id]."'") == "111")
//										$msg = "$stuff_name (SPS Stuff)"; else $msg = "$stuff_name (Hotel Stuff)";
									$msg = $stuff_name;
								}
								echo $msg;
								?>
                            </td>
							<td  width="13%">
							<?php if($row[type]==1) { ?>
                            	席次表編集
							<?php } else { ?>
								招待者リストの作成
							<?php } ?>
                            </td>

                            <td  width="13%">
							<?php if($row[type]!=3) { ?>
							<?=$guest_name?>
							<?php } else { ?>

							<?=$row['guest_name']?>

							<?php } ?>
							</td>
							<?php if($row[type]==3) { ?>
							<td  width="13%">
							削除
							</td>
							<td   width="10%">
							<!--<font color="#FF0000">Blank</font>-->
							&nbsp;
                            </td>
							<td   width="10%">
							<!--<font color="#FF0000">Blank</font>-->
							&nbsp;
							</td>
							<td   width="10%">
							<!--<font color="#FF0000">Blank</font>-->
							&nbsp;
							</td>
							<?php } else if($row[type]==4) { ?>
							<td  width="13%">
							新規
							</td>
							<td   width="10%">
							<!--<font color="#FF0000">Blank</font>-->
							&nbsp;
                            </td>
							<td   width="10%">
							<!--<font color="#FF0000">Blank</font>-->
							&nbsp;
							</td>
							<td   width="10%">
							<!--<font color="#FF0000">Blank</font>-->
							&nbsp;
							</td>
							<?php } else if($row[type]==2) { ?>
							<td  width="13%">
							変更
							</td>
                            <td   width="10%">

								<?php
									$before_log_array=explode("|",$row['previous_status']);
									$after_log_array=explode("|",$row['current_status']);
									foreach($before_log_array as $key=>$value)
									{
										if($value!=$after_log_array[$key])
										{
											if($key==0)
											{
												echo "新郎新婦側<br/>";
											}
											if($key==1)
											{
												echo "姓<br/>";
											}
											if($key==2)
											{
												echo "ふりがな姓<br/>";
											}

											if($key==3)
											{
												echo "名<br/>";
											}
											if($key==4)
											{
												echo "ふりがな名<br/>";
											}
											if($key==5)
											{

												echo "敬称<br/>";
											}
											if($key==6)
											{

												echo "特記<br/>";
											}
											if($key==7)
											{

												echo "高砂席名<br/>";
											}


											if($key==8)
											{

												echo "区分<br/>";
											}
											if($key==9)
											{
												echo "肩書 1<br/>";
											}
											if($key==10)
											{
												echo "肩書 2<br/>";
											}

											if($key==11)
											{

												echo "引出物 <br/>";
											}
											if($key==12)
											{

												echo "料理 <br/>";
											}
											if($key==13)
											{

												echo "席種別<br/>";
											}
										}
									}
								?>
							</td>
							 <td   width="10%">
								<?php
									$before_log_array=explode("|",$row['previous_status']);
									$after_log_array=explode("|",$row['current_status']);
									foreach($before_log_array as $key=>$value)
									{
										if($value!=$after_log_array[$key])
										{
											if($key==0)
											{
												if($value=="Male")
												echo "新郎側<br/>";
												else
												echo "新婦側<br/>";


											}
											if($key==1)
											{
												echo $value."<br/>";
											}

											if($key==2)
											{
												echo $value."<br/>";
											}
											if($key==3)
											{
												echo $value."<br/>";
											}
											if($key==4)
											{
												echo $value."<br/>";
											}
											if($key==5)
											{
												include("inc/main_dbcon.inc.php");
												$respect = $obj->GetSingleData(" spssp_respect ", "title", " id='".$value."'");
												include("inc/return_dbcon.inc.php");
												echo $respect."<br/>";
											}
											if($key==6)
											{
												echo $value."<br/>";


											}
											if($key==7)
											{
												if($value=="1")
												$stage_guest="媒酌人1";
												if($value=="2")
												$stage_guest="媒酌人2";
												if($value=="3")
												$stage_guest="媒酌人3";
												if($value=="4")
												$stage_guest="媒酌人4";
												if($value=="5")
												$stage_guest="お子様";
												echo $stage_guest."<br/>";

											}
											if($key==8)
											{
												include("inc/main_dbcon.inc.php");
												$guest_type = $obj->GetSingleData(" spssp_guest_type ", "name", " id='".$value."'");
												include("inc/return_dbcon.inc.php");
												echo $guest_type."<br/>";
											}
											if($key==9)
											{

												echo $value."<br/>";
											}
											if($key==10)
											{
												echo $value."<br/>";


											}
											if($key==11)
											{
												$gift_name = $obj->GetSingleData(" spssp_gift_group ", "name", " id='".$value."' and  user_id = ".$user_id);
												echo $gift_name."<br/>";


											}
											if($key==12)
											{
												$menu_name = $obj->GetSingleData(" spssp_menu_group ", "name", " id='".$value."' and user_id = ".$user_id);
												echo $menu_name."<br/>";
											}
											if($key==13)
											{
												$stage=($value==0)?"招待席":"高砂席";
												echo $stage."<br/>";

											}
										}
									}
								?>
							</td>
                            <td  width="10%">
							<?php
									$before_log_array=explode("|",$row['previous_status']);
									$after_log_array=explode("|",$row['current_status']);
									foreach($after_log_array as $key=>$value)
									{
										if($value!=$before_log_array[$key])
										{
											if($key==0)
											{
												if($value=="Male")
												echo "新郎側<br/>";
												else
												echo "新婦側<br/>";
											}
											if($key==1)
											{
												echo $value."<br/>";
											}

											if($key==2)
											{
												echo $value."<br/>";
											}
											if($key==3)
											{
												echo $value."<br/>";
											}

											if($key==4)
											{
												echo $value."<br/>";
											}
											if($key==5)
											{
												include("inc/main_dbcon.inc.php");
												$respect = $obj->GetSingleData(" spssp_respect ", "title", " id='".$value."'");
												include("inc/return_dbcon.inc.php");
												echo $respect."<br/>";
											}
											if($key==6)
											{
												echo $value."<br/>";


											}
											if($key==7)
											{
												if($value=="1")
												$stage_guest="媒酌人1";
												if($value=="2")
												$stage_guest="媒酌人2";
												if($value=="3")
												$stage_guest="媒酌人3";
												if($value=="4")
												$stage_guest="媒酌人4";
												if($value=="5")
												$stage_guest="お子様";
												echo $stage_guest."<br/>";

											}
											if($key==8)
											{
												include("inc/main_dbcon.inc.php");
												$guest_type = $obj->GetSingleData(" spssp_guest_type ", "name", " id='".$value."'");
												include("inc/return_dbcon.inc.php");
												echo $guest_type."<br/>";
											}
											if($key==9)
											{

												echo $value."<br/>";
											}
											if($key==10)
											{
												echo $value."<br/>";


											}
											if($key==11)
											{
												$gift_name = $obj->GetSingleData(" spssp_gift_group ", "name", " id='".$value."' and  user_id = ".$user_id);
												echo $gift_name."<br/>";


											}
											if($key==12)
											{
												$menu_name = $obj->GetSingleData(" spssp_menu_group ", "name", " id='".$value."' and user_id = ".$user_id);
												echo $menu_name."<br/>";
											}
											if($key==13)
											{
												$stage=($value==0)?"招待席":"高砂席";
												echo $stage."<br/>";

											}
										}
									}
								?>

							</td>
							<?php } else {
							if($row[previous_status])
								{
									$table_id=$obj->GetSingleData("spssp_default_plan_seat","table_id"," id=".$row[previous_status]." limit 1");

									$table_details=$obj->getSingleRow("spssp_default_plan_table"," id=".$table_id." limit 1");



									$tbl_row = $obj->getSingleRow("spssp_table_layout"," table_id=".$table_details['id']." and user_id=".(int)$row['user_id']." limit 1");



									$new_name_row = $obj->getSingleRow("spssp_user_table"," default_table_id=".$tbl_row['id']." and user_id=".(int)$row['user_id']." limit 1");


									if(!empty($new_name_row))
									{
										$tblname_prev = $obj->getSingleData("spssp_tables_name","name","id=".$new_name_row['table_name_id']);

									}
									else
									{
										$tblname_prev = $tbl_row['name'];

									}


									$seats = $obj->getRowsByQuery("select * from spssp_default_plan_seat where table_id =".$table_id." order by id asc ");

									$j=1;
									foreach($seats as $seat)
												{

													if($seat['id']==$row[previous_status])
													{
														$seat_pos_prev=$j;
														break;
													}
													$j++;
												}
								}
								if($row[current_status])
								{
									$table_id=$obj->GetSingleData("spssp_default_plan_seat","table_id"," id=".$row[current_status]." limit 1");

									$table_details=$obj->getSingleRow("spssp_default_plan_table"," id=".$table_id." limit 1");

									$tbl_row = $obj->getSingleRow("spssp_table_layout"," table_id=".$table_details['id']." and user_id=".(int)$row['user_id']." limit 1");
									$new_name_row = $obj->getSingleRow("spssp_user_table"," default_table_id=".$tbl_row['id']." and user_id=".(int)$row['user_id']." limit 1");
									if(!empty($new_name_row))
									{
										$tblname_current = $obj->getSingleData("spssp_tables_name","name","id=".$new_name_row['table_name_id']);

									}
									else
									{
										$tblname_current = $tbl_row['name'];

									}

									$seats = $obj->getRowsByQuery("select * from spssp_default_plan_seat where table_id =".$table_id." order by id asc ");

									$j=1;
									foreach($seats as $seat)
												{
													if($seat['id']==$row[current_status])
													{
														$seat_pos_current=$j;
														break;
													}
													$j++;
												}
								}

							?>
							<td  width="13%">
							<?php
							if($tblname_prev=="")
							echo "新規";
							else
							echo "移動";
							?>
							</td>
							<td  width="10%">席次表</td>
                            <td   width="10%">
							<?php
							if($tblname_prev!="")
							{
							?>
								<?=$tblname_prev; ?>/<?=$seat_pos_prev;?>
							<?php
								}
								else
								{
							?>
								<!--<font color="#FF0000">Blank</font>-->
							&nbsp;
								<?php
									}
								?>
							</td>
                            <td  width="10%">
							<?php
								if($tblname_current!="")
								{
								?>
								<?=$tblname_current; ?>/<?=$seat_pos_current;?>
							<?php
								}
								else
								{
							?>
							<!--<font color="#FF0000">Blank</font>-->
							&nbsp;
								<?php
									}
								?>
							</td>
							<?php } ?>



                           </tr>
                        </table>
                    </div>
             <?php
			 	$i++;$j++;
			 	}
			 ?>

        </div>
   <div align="center"><a href="user_info.php?user_id=<?=$user_id?>&stuff_id=<?=$stuff_id?>">&lt;&lt;戻る</a></div> <!--   -->
    </div>
<div style="height:18px; text-align:right"><a href="#">▲ページ上へ</a></div>
</div>

<?php
	include_once("inc/left_nav.inc.php");
?>


<?php
	include_once("inc/new.footer.inc.php");
?>
