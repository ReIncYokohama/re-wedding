<?php
	include_once("inc/dbcon.inc.php");
	
	include_once("inc/checklogin.inc.php");
	
	
	include_once("inc/new.header.inc.php");
	require_once("inc/class.dbo.php");
	$obj = new DBO();
	$rooms = $obj->GetAllRow("spssp_room");
	
	$table='spssp_user';
	$where = " 1=1 ";
	$data_per_page=10;
	$current_page=(int)$_GET['page'];
	
	$redirect_url = 'users.php';	
	
	$pageination = $obj->pagination($table, $where, $data_per_page,$current_page,$redirect_url);
	
	
	/*echo $pageination;
	
	if($_GET['action']=='delete' && (int)$_GET['id'] > 0)
	{
		$sql = "delete from spssp_admin where id=".(int)$_GET['id'];
		mysql_query($sql);
		redirect('users.php?page='.$_GET['page']);
	}
	else 
	*/
	if($_GET['action']=='delete_user' && (int)$_GET['id'] > 0)
	{
		$sql = "delete from spssp_user where id=".(int)$_GET['id'];
		mysql_query($sql);
		
	}
	if(isset($_GET['order_by']) && $_GET['order_by'] != '')
	{
		$orderby = mysql_real_escape_string($_GET['order_by']);
		$dir = mysql_real_escape_string($_GET['asc']);
		
		if($orderby=='mdate')
		{		
			$order=" marriage_day ";
			
		}
		
		else if($orderby=='woman_last_name')
		{			
			$order=" woman_lastname ";			
		}

		else if($orderby=='man_last_name')
		{
			$order=" man_lastname ";
		}
		if($dir == 'true')
		{
			$order.=' asc';
		}
		else
		{
			$order.=' desc';
		}
		
	}
	else
	{
		$order="creation_date DESC";
	}
	
	if($_SESSION['user_type'] == 222)
	{
		$data = $obj->GetAllRowsByCondition("spssp_user"," stuff_id=".(int)$_SESSION['adminid']);
		foreach($data as $dt)
		{
			$staff_users[] = $dt['id'];
		}
		
	}
	
	
	$where =" 1= 1";
	
	
	if(isset($_POST['search_user']) && $_POST['search_user'] > 0)
	{
		if(isset($_POST['chk_woman_lastname']) && trim($_POST['woman_lastname']) != "")
		{
			
				/*$where .= " and ( UPPER(woman_lastname) like '%".strtoupper(trim($_POST['woman_lastname']))."%' or UPPER(woman_firstname) like '%".strtoupper(trim($_POST['woman_lastname']))."%') ";*/
				
				$where .= " and ( woman_lastname like '%".trim($_POST['woman_lastname'])."%' or woman_firstname like '%".trim($_POST['woman_lastname'])."%') ";
			
		}
		if(isset($_POST['chk_man_lastname']) && trim($_POST['man_lastname']) != "")
		{
				//$where .= " and man_lastname like '%".trim($_POST['man_lastname'])."%'";
				/*$where .= " and ( UPPER(man_lastname) like '%".strtoupper(trim($_POST['man_lastname']))."%' or UPPER(man_firstname) like '%".strtoupper(trim($_POST['man_lastname']))."%') ";*/
				
				$where .= " and ( man_lastname like '%".trim($_POST['man_lastname'])."%' or UPPER(man_firstname) like '%".trim($_POST['man_lastname'])."%') ";
			
		}
		if(isset($_POST['chk_marriage_day']) && trim($_POST['marriage_day']) != "")
		{
			
				$where .= " and marriage_day = '".trim($_POST['marriage_day'])."'";
			
		}
		//echo $where;exit;
	}
	
	if($_SESSION['user_type'] == 222)
	{
		$data = $obj->GetAllRowsByCondition("spssp_user"," stuff_id=".(int)$_SESSION['adminid']);
		foreach($data as $dt)
		{
			$staff_users[] = $dt['id'];
		}
		if(!empty($staff_users))
		{
			if(in_array((int)$get['user_id'],$staff_users))
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
	
	
	$query_string="SELECT * FROM spssp_user where $where ORDER BY $order LIMIT ".((int)($current_page)*$data_per_page).",".((int)$data_per_page).";";
	//echo $query_string;
		
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
	width:100px;
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

Control.DatePicker.Language['ahad'] = { months: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'], days: [  '日','月','火', '水', '木', '金', '土'], strings: { 'Now': '今度', 'Today': '今日', 'Time': '時間', 'Exact minutes': '正確な分', 'Select Date and Time': '選択して日付と時刻', 'Open calendar': 'オープンカレンダー' } };



</script>

<link rel="stylesheet" href="../datepicker/datepicker.css">
<script type="text/javascript" language="javascript" src="../datepicker/behaviors.js"></script>

<script type="text/javascript">
function valid_user()
{
	if(document.getElementById("man_firstname").value=='')
	{
		alert("新郎の姓を正しく入力してください");
		document.getElementById('man_firstname').focus();
		return false;
	}

	if(document.getElementById("man_lastname").value=='')
	{
		alert("お客様情報が入力されておりません。");
		document.getElementById('man_lastname').focus();
		return false;
	}
	if(document.getElementById("man_furi_firstname").value=='')
	{
		alert("新郎の姓のフリガナを正しく入力してください");
		document.getElementById('man_furi_firstname').focus();
		return false;
	}
	if(document.getElementById("man_furi_lastname").value=='')
	{
		alert("新郎の名のフリガナを正しく入力してください");
		document.getElementById('man_furi_lastname').focus();
		return false;
	}
	
	if(document.getElementById("woman_firstname").value=='')
	{
		alert("新婦の姓を正しく入力してください");
		document.getElementById('woman_firstname').focus();
		return false;
	}

	if(document.getElementById("woman_lastname").value=='')
	{
		alert("新婦の名を正しく入力してください");
		document.getElementById('woman_lastname').focus();
		return false;
	}
	if(document.getElementById("woman_furi_firstname").value=='')
	{
		alert("新婦の姓のフリガナを正しく入力してください");
		document.getElementById('woman_furi_firstname').focus();
		return false;
	}
	if(document.getElementById("woman_furi_lastname").value=='')
	{
		alert("新婦の名のフリガナを正しく入力してください");
		document.getElementById('woman_furi_lastname').focus();
		return false;
	}

	if(document.getElementById("marriage_day").value=='')
	{
		alert("挙式日を正しく入力してください");
		document.getElementById('marriage_day').focus();
		return false;
	}
	if(document.getElementById("marriage_day_with_time").value=='')
	{
		alert("挙式時間を正しく入力してください");
		document.getElementById('marriage_day_with_time').focus();
		return false;
	}
	if(document.getElementById("room_id").value=='')
	{
		alert("Marriage room must not be empty");
		document.getElementById('room_id').focus();
		return false;
	}
	
	if(document.getElementById("religion").value=='')
	{
		alert("挙式種類を選択してください");
		document.getElementById('religion').focus();
		return false;
	}
	
	if(document.getElementById("party_day").value=='')
	{
		alert("披露宴日を正しく入力してください");
		document.getElementById('party_day').focus();
		return false;
	}
	if(document.getElementById("party_day_with_time").value=='')
	{
		alert("披露宴時間を正しく入力してください");
		document.getElementById('party_day_with_time').focus();
		return false;
	}
	if(document.getElementById("party_room_id").value=='')
	{
		alert("Party room must not be empty");
		document.getElementById('party_room_id').focus();
		return false;
	}
	document.user_form_register.submit();
}
function guestCheck()
{
	alert("Please First Enter Guest Information of this user");
}

function alert_staff_plan()
{
	alert("This User Have Not Any Plan Yet");
	return false;
}

$j(function(){

	var msg_html=$j("#msg_rpt").html();
	
	if(msg_html!='')
	{
		$j("#msg_rpt").fadeOut(5000);
	}


});

</script>
<!--<div id="nav">
	<a href="manage.php" >Home</a>  &raquo; Admin Users  
</div>
<div style="float:left;width:100%;">
	<ul>
		<li style="float:left;list-style:none;padding-right:10px;"><a href='create_user.php?page=".$current_page."'>Create New User</a></li>
		<li style="float:left;list-style:none;padding-right:10px;"><a href='stuff_new.php?page=".$current_page."'>Create New Stuff</a></li>
	</ul>

</div>-->
<div id="topnavi">
    <h1>お客様一覧</h1>
    <div id="top_btn"> 
        <a href="logout.php"><img src="img/common/btn_logout.jpg" alt="ログアウト" width="102" height="19" /></a>　
        <a href="#"><img src="img/common/btn_help.jpg" alt="ヘルプ" width="82" height="19" /></a>
    </div>
</div>
<div id="container">
    <div id="contents"> 
    	<h2>新規登録</h2>
        <?php if($err){$obj->GetErrorMsg($err);}?>
		<?php if($_GET['msg']){$obj->GetSuccessMsg($_GET['msg']);}?>
        <form action="insert_user.php" method="post" name="user_form_register">
        <table width="100%" border="0" cellspacing="10" cellpadding="0">
            <tr>
                <td width="5%" align="right" nowrap="nowrap">新郎氏名：</td>
                <td width="17%" nowrap="nowrap">
                	<input name="man_firstname" type="text" id="man_firstname"  class="input_text"  />
                    <input name="man_lastname" type="text" id="man_lastname"  class="input_text"  /> 様
              	</td>
                <td width="15%" align="right" nowrap="nowrap">新婦氏名：</td>
                <td nowrap="nowrap">
                    <input name="woman_firstname" type="text" id="woman_firstname"  class="input_text"  />
                    <input name="woman_lastname" type="text" id="woman_lastname"  class="input_text"  />  様
                </td>
            </tr>
            <tr>
                <td align="right" nowrap="nowrap">フリガナ：</td>
                <td nowrap="nowrap">
                	
                	<input name="man_furi_firstname" type="text" id="man_furi_firstname"  class="input_text" />
                    <input name="man_furi_lastname" type="text" id="man_furi_lastname"  class="input_text"  /> 様
                </td>

                <td align="right" nowrap="nowrap">フリガナ：</td>
                <td nowrap="nowrap">
                	
                	<input name="woman_furi_firstname" type="text" id="woman_furi_firstname"  class="input_text"  />
                    <input name="woman_furi_lastname" type="text" id="woman_furi_lastname"  class="input_text"  /> 様
                </td>


            </tr>
            <tr>
            	<td align="right" nowrap="nowrap">挙式日：</td>
            	<td nowrap="nowrap">
                	<input name="marriage_day" type="text" id="marriage_day" size="10" readonly="readonly" style="background: url('img/common/icon_cal.gif') no-repeat scroll right center rgb(255, 255, 255); padding-right: 20px;" class="datepicker"/>
                &nbsp;<a href="javascript:void(0)" onclick="document.getElementById('marriage_day').value='';">クリア </a>
            		
                </td>
            	<td align="right" nowrap="nowrap">挙式時間：</td>
            	<td nowrap="nowrap">
                	<input name="marriage_day_with_time" type="text" id="marriage_day_with_time" size="10" readonly="readonly" style="background: url('img/common/icon_cal.gif') no-repeat scroll right center rgb(255, 255, 255); padding-right: 20px;" class="timepicker"/>
                &nbsp;<a href="javascript:void(0)" onclick="document.getElementById('marriage_day_with_time').value='';">クリア </a>
                </td>
            </tr>
            <tr>
            	<td align="right" nowrap="nowrap">挙式会場：</td>
            	<td nowrap="nowrap">
                	<select name="room_id" id="room_id" class="select">

                    <?php
                        if($rooms)
                        {
                            foreach($rooms as $room)
                            {
                               
								
								if($room['id']==$_SESSION['regs']['room_id'])
								echo "<option value ='".$room['id']."' selected> ".$room['name']." [".$room['max_seats']."]</option>";
								else
								 echo "<option value ='".$room['id']."'> ".$room['name']." [".$room['max_rows']*$room['max_columns']*$room['max_seats']."]</option>";
								
                            }
                        }
                    ?>
                	</select>      
                </td>
            	<td align="right" nowrap="nowrap">挙式種類：</td>
            	<td nowrap="nowrap">
                	<select name="religion" id="religion" class="select">
                        <option value=""  <?php if($_SESSION['regs'][religion]=='') {?> selected="selected" <?php } ?>>選択してください</option>
                        <option value="キリスト教式" <?php if($_SESSION['regs'][religion]=='キリスト教式"') {?> selected="selected" <?php } ?>>キリスト教式</option>
                        <option value="神前式" <?php if($_SESSION['regs'][religion]=='神前式') {?> selected="selected" <?php } ?>>神前式</option>
                        <option value="人前式" <?php if($_SESSION['regs'][religion]=='人前式') {?> selected="selected" <?php } ?>>人前式</option>
                        <option value="仏前式" <?php if($_SESSION['regs'][religion]=='仏前式') {?> selected="selected" <?php } ?>>仏前式</option>
                        <option value="その他" <?php if($_SESSION['regs'][religion]=='その他') {?> selected="selected" <?php } ?>>その他</option>
                    </select>  
                </td>
            </tr>
            <tr>
            	<td align="right" nowrap="nowrap">披露宴日：</td>
            	<td nowrap="nowrap">
                	<input name="party_day" type="text" id="party_day" size="10" readonly="readonly" style="background: url('img/common/icon_cal.gif') no-repeat scroll right center rgb(255, 255, 255); padding-right: 20px;" class="datepicker"/>
                &nbsp;<a href="javascript:void(0)" onclick="document.getElementById('party_day').value='';">クリア </a>
                </td>
            	<td align="right" nowrap="nowrap">披露宴時間：</td>
            	<td nowrap="nowrap">
                <input name="party_day_with_time" type="text" id="party_day_with_time" size="10" readonly="readonly" style="background: url('img/common/icon_cal.gif') no-repeat scroll right center rgb(255, 255, 255); padding-right: 20px;" class="timepicker"/>
                &nbsp;<a href="javascript:void(0)" onclick="document.getElementById('party_day_with_time').value='';">クリア </a>
            　	
            披露宴会場：
            	
                <select name="party_room_id" id="party_room_id" class="select">

                    <?php
                        if($rooms)
                        {
                            foreach($rooms as $room)
                            {
                               
								
								if($room['id']==$_SESSION['regs']['room_id'])
								echo "<option value ='".$room['id']."' selected> ".$room['name']." [".$room['max_seats']."]</option>";
								else
								 echo "<option value ='".$room['id']."'> ".$room['name']." [".$room['max_rows']*$room['max_columns']*$room['max_seats']."]</option>";
								
                            }
                        }
                    ?>
                </select>
                
                </td>
            </tr>
            <tr>
            	<td colspan="4" align="left">
                	<a href="javascript:void(0);" onclick="valid_user();"><img src="img/common/btn_regist.jpg" border="0" width="62" height="22" /></a>
                </td>
            </tr>
        </table>
        </form>
       	<br />
        <br />
        <h2>検索・編集・削除</h2>
        
        <p class="txt3">
        	<form action="users.php" method="post">
                
            　	<input name="chk_man_lastname" type="checkbox" id="checkbox2"  />
                新婦姓：
                <input name="man_lastname" type="text" id="新婦姓"  class="input_text"  /> &nbsp; &nbsp;
                
                <input name="chk_woman_lastname" type="checkbox"   />
                <label for="checkbox"></label>
                新郎姓：
                <label for="新郎姓"></label>
                <input name="woman_lastname" type="text" id="新郎姓"  class="input_text"  /> &nbsp; &nbsp;
                     　
                <input type="checkbox" name="chk_marriage_day" id="chk_marriage_day" />
                挙式日：
                <label for="挙式日"></label>
                <!--<input name="marriage_day" type="text" id="marriage_day" size="10" />-->
                <input name="marriage_day" type="text" id="marriage_day" size="10" readonly="readonly" style="background: url('img/common/icon_cal.gif') no-repeat scroll right center rgb(255, 255, 255); padding-right: 20px;" class="datepicker"/>
                &nbsp;<a href="javascript:void(0)" onclick="document.getElementById('marriage_day').value='';">クリア </a>
                <br />
                <input type="hidden" name="search_user" value="1" />
                <input type="image" name="search" src="img/common/bt_search.jpg" width="62" height="22" />
            </form>
        </p>
        <br />
        <div class="bottom_line_box">
        	<p class="txt3"><font color="#2052A3"><strong>席次表設定</strong></font></p>
        </div>
        
        <div class="box_table">
            <p>&nbsp;</p>
            <div class="page_next"><?=$pageination?></div>
            <div class="box4">
                <table border="0" align="center" cellpadding="1" cellspacing="1">
                    <tr align="center">
                        <td>挙式日<span class="txt1"><a href="users.php?order_by=mdate&asc=true">▲</a> <a href="users.php?order_by=mdate&asc=false">▼</a></span></td>
                        <td>新郎氏名<span class="txt1"><a href="users.php?order_by=woman_last_name&asc=true">▲</a> 
                        	<a href="users.php?order_by=woman_last_name&asc=false">▼</a></span>
                         </td>
                        <td>新婦氏名<span class="txt1"><a href="users.php?order_by=man_last_name&asc=true">▲</a> 
                        	<a href="users.php?order_by=man_last_name&asc=false">▼</a></span>
                        </td>
                        <td>&nbsp;</td>
                        <td>スタッフ</td>
                        <td>メッセージ</td>
                        <td>&nbsp;</td>
                        <td>最終アクセス</td>
                        <td>席次表</td>
                        <td>引出物</td>
                        <td>編集</td>
                        <td>削除</td>
                    </tr>
                </table>
            </div>
            <?php
			$i=0;
			foreach($data_rows as $row)
			{	
				$roomname =  $obj->GetSingleData(" spssp_room", " name", " id=".(int)$data_rows['room_id']);
				$party_roomname = $obj->GetSingleData(" spssp_room", " name", " id=".(int)$data_rows['party_room_id']);
				
				include("admin/inc/main_dbcon.inc.php");
				$man_respect = $obj->GetSingleData(" spssp_respect", " title", " id=".(int)$data_rows['man_respect_id']);
				$woman_respect = $obj->GetSingleData(" spssp_respect", " title", " id=".(int)$data_rows['woman_respect_id']);
				include("admin/inc/return_dbcon.inc.php");
				
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
					
					$layout_link = "<a href='set_table_layout.php?plan_id=".$plan_row['id']."&user_id=".(int)$row['id']."'><img src='img/common/btn_taku_edit.gif' boredr='0' width='52' height='17'> </a>";
				}
				else
				{
					$plan_link = "";
					$layout_link = "";
				}
				
				if($_SESSION['user_type'] == 222)
				{
					if(!empty($staff_users))
					{
						if(in_array($row['id'],$staff_users))
						{
							$delete_onclick = "confirmDelete('users.php?action=delete_user&page=".(int)$_GET['page']."&id=".$row['id']."');";
						}
						else
						{
							$delete_onclick = "alert_staff();";
						}
					}
					else
					{
						$delete_onclick = "alert_staff();";
					}
				}
				else
				{
					$delete_onclick = "confirmDelete('users.php?action=delete_user&page=".(int)$_GET['page']."&id=".$row['id']."');";
				}

			?>
            <div class="<?=$class?>">
                <table border="0" align="center" cellpadding="1" cellspacing="1">
                    <tr align="center">
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
                        <td>
						<?php
						if($last_login==""){
						
						}else{
						echo date("Y-m-d", mktime($last_login));
						}
						
						?>
						<? //date("Y-m-d", mktime($last_login));?>
                        </td>
                        <td>
                        	<?php
                            	if($var == 1)
								{
									echo $plan_link ;
								}
								else
								{
									if(isset($conf_plan_row) && !empty($conf_plan_row))
									{
										echo "<a href='view_plan.php?plan_id=".$plan_row['id']."&user_id=".$row['id']."'><img src='img/common/btn_syori.gif' height='17' width='42' border='0' /></a>";
									}
									else
									{
										echo "<a href='javascript:void(0)' onclick='alert_staff_plan();'><img src='img/common/btn_syori.gif' height='17' width='42' border='0' /></a>";
									}
								}
							?>
                        </td>
                        <td><a href="gift_user.php?user_id=<?=$row['id'];?>"><img src="img/common/btn_kentou.gif" width="42" height="17" /></a></td>
                        <td><a href="user_info.php?user_id=<?=$row['id']?>"><img src="img/common/btn_edit.gif" width="42" height="17" /></a></td>
                        <td>
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
</div>

<?php
	include_once('inc/left_nav.inc.php');
	include_once("inc/new.footer.inc.php");
?>
