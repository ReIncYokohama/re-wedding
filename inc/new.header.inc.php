<?php
session_start();
include_once("admin/inc/dbcon.inc.php");
include_once("inc/checklogin.inc.php");
include_once("admin/inc/class_information.dbo.php");

$pos = strpos($_SERVER[SCRIPT_URL], "make_plan.php");
if($pos===false)
$make_plan="";
else
$make_plan=" onclick=\"return make_plan_check();\" ";

$user_id = Core_Session::get_user_id();

$obj = new DBO();
$objInfo = new InformationClass();

$query_string = "SELECT * from spssp_user_log WHERE user_id= '".$user_id."' and admin_id='0' order by id desc limit 0,2";

$resultl = mysql_query( $query_string );
$row1 = mysql_fetch_array($resultl);
if ($row1['logout_time']=="0000-00-00 00:00:00" || $row1['login_time']==$row1['logout_time']) $row1 = mysql_fetch_array($resultl);
$_SESSION['lastlogintime'] =$row1['login_time'];

$last_log_date = str_replace("-", "/",$_SESSION['lastlogintime']);

if ($_SESSION['userid_admin']) $messege_url="admin_messages.php"; else $messege_url="user_messages.php";

$plan = Model_Plan::find_one_by_user_id($user_id);
$__plan_info = $plan->to_array();

$user = Model_User::find_by_pk($user_id);
$user_row = $user->to_array();

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title><?php echo $TITLE;?></title>

<link href="css/tmpl.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="tmp_js/cufon-yui.js"></script>
<script type="text/javascript" src="tmp_js/arial.js"></script>
<script type="text/javascript" src="tmp_js/cuf_run.js"></script>
<script language="javascript" type="text/javascript" src="js/jquery.js"></script>
<link rel="stylesheet" href="css/base/jquery.ui.all.css">
<script src="js/jquery-1.4.2.js"></script>
<script src="js/external/jquery.bgiframe-2.1.1.js"></script>
<script src="js/ui/jquery.ui.core.js"></script>
<script src="js/ui/jquery.ui.widget.js"></script>
<script src="js/ui/jquery.ui.mouse.js"></script>
<script src="js/ui/jquery.ui.button.js"></script>
<script src="js/ui/jquery.ui.draggable.js"></script>
<script src="js/ui/jquery.ui.position.js"></script>
<script src="js/ui/jquery.ui.resizable.js"></script>
<script src="js/ui/jquery.effects.core.js"></script>
<script src="js/ui/jquery.effects.blind.js"></script>
<script src="js/ui/jquery.effects.fade.js"></script>
<script src="js/jquery.effects.explode.js"></script>
<script src="js/jquery.effects.shake.js"></script>
<script src="js/jquery.rollover.js"></script>
<script src="js/ui/jquery.ui.dialog.js"></script>
<script src="js/jquery.cookie.js"></script>
<script src="js/util.js"></script>

<script>
var timeOutLength = "<?=TIMEOUTLENGTH?>";
var timerId = setInterval('user_timeout()', timeOutLength);
var changeAction = false;
var timeOutNow=false;
var logUpdateLength = "<?=USER_LOGFILE_UPDTAE?>"
var timerId2 = setInterval('user_access_update()', logUpdateLength);

function user_access_file_update() {
//	alert("user_access_file_update OK");
	if(timeOutNow==false) {
		$.ajax({
		    type: 'POST',
		    url: 'inc/user_access_update.php',
		    data: 'id=id', // Dummy
		    success: function(data) {
		     //alert(data);
		    }
		});
	}
}
</script>
<script src="js/user_timeout_action.js"></script>

<script type="text/javascript">
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
$(window).load(function () {
$("#close_window").hover(function(){
    $("img",this).attr("src","image/closewindow_r1_c4_s2.jpg");
  },function(){
    $("img",this).attr("src","image/closewindow_r1_c4.jpg");
  });
  });
</script>

<link href="css/main.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="layout">
	<div class="main">
		<div class="header">
                <div id="head_right2" style="position:absolute;right:0px;width:220px;text-align:right;padding-left:70px;">
  <div id="close_window" ><img name="closewindow_r1_c4" src="image/closewindow_r1_c4.jpg" width="186" height="44" border="0" id="closewindow_r1_c4" alt=""></div>
                  <div id="login_date" style="font-size:10px;background:#dbdddf;width:186px;margin-left:34px;text-align:center;">前回のログイン　<?=$last_log_date?><br><a href="logout.php" <?=$make_plan?> ><image src="image/bt_logout.gif"></a></div>
                </div>
			<div class="header_resize" style="display:relative;vertical-align:middle;">
            	<div id="inform_user" style="text-align:center; font-size:15px; font-weight:bold; color:#006600;"></div>
                <div class="logo"> 
<div><img src="img/logo.jpg" width="200" height="57" border="0" align="absbottom" />
<font style="display:inline;font-size:20px; font-weight:bold; margin-left:130px; color:#0099ff;"> 
<?php

  if($plan->is_hon_hatyu_irai() or $plan->is_hon_hatyu()) echo "印刷依頼済みのため編集できません";
  else if (Core_Session::is_admin() && $plan->is_kari_hatyu_irai()) echo "お客様が印刷イメージを依頼中です";
  else if($plan->is_kari_hatyu_irai()) echo "印刷イメージ依頼中のため編集できません"; 
 else if($user->past_deadline_sekijihyo()) echo "席次表編集利用制限日が過ぎています";
?>
</font>
</div>
                <div style="font-size:11px;height:12px;vertical-align:top;">
    			<?php  echo $objInfo->get_user_name_image_or_src_from_user_side($user_id ,$hotel_id=1, $name="guest_page.png",$extra=".");?>
				</div>
                </div>
  <div style="clear: both;display:none;"></div>
  				<div class="clr"></div>
  				<div class="menu_nav" >
                  <ul id="menu">
                    <li  <?php echo ($tab_home==true)?"class='active'":"";?>><a href="dashboard.php" <?=$make_plan?>><div class="nv_pd"><img src="images/space.gif" width="100" height="10" /></div>
                    Home</a></li>
                    <li  <?php echo ($tab_table_layout==true)?"class='active'":"";?>><a href="table_layout.php" <?=$make_plan?>><div class="nv_pd"><img src="images/space.gif" width="100" height="10" /></div>テーブルの配置</a></li>
                    <li  <?php echo ($tab_hikidemono==true)?"class='active'":"";?>><a href="hikidemono.php" <?=$make_plan?>>引出物・料理の<br>
                      登録</a></li>
                    <li  <?php echo ($tab_my_guests==true)?"class='active'":"";?>><a href="my_guests.php" <?=$make_plan?>>招待者リスト<br>
                      の作成</a></li>
                    <li  <?php echo ($tab_make_plan==true)?"class='active'":"";?>><a href="make_plan.php"><div class="nv_pd"><img src="images/space.gif" width="100" height="10" /></div>席次表</a></li>
                    <li <?php echo ($tab_order==true)?"class='active'":"";?>><a href="order.php" <?=$make_plan?>><div class="nv_pd"><img src="images/space.gif" width="100" height="10" /></div>発注</a></li>
                    <li  <?php echo ($tab_download==true)?"class='active'":"";?>><a href="download.php" <?=$make_plan?>>招待者リスト<br>
                      ダウンロード</a></li>
                    <li  <?php echo ($tab_user_info==true)?"class='active'":"";?>><a href="user_info.php" <?=$make_plan?>><div class="nv_pd"><img src="images/space.gif" width="100" height="10" /></div>お客様情報</a></li>
<?php  if(!$IgnoreMessage){
?>
                    <li <?php echo ($tab_message==true)?"class='active'":"";?>><a href="<?=$messege_url?>" <?=$make_plan?>><div class="nv_pd"><img src="images/space.gif" width="100" height="10" /></div>メッセージ</a></li>
                       <?php  }
?>
                  </ul>
    		  </div>
  				<div class="clr"></div>
			</div>
		</div>
	</div>
