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

$usert_id = $_SESSION['userid'];
$obj = new DBO();
$objInfo = new InformationClass();

//$last_log_date = $obj->GetSingleData("spssp_user_log", "max(login_time) lt ","user_id=".$usert_id);*/

	$query_string = "SELECT * from spssp_user_log WHERE user_id= '".$usert_id."' and admin_id='0' order by id desc limit 0,2";

	$resultl = mysql_query( $query_string );
	$row1 = mysql_fetch_array($resultl);
	$_SESSION['lastlogintime'] =$row1['login_time'];



$last_log_date = str_replace("-", "/",$_SESSION['lastlogintime']);

if ($_SESSION['userid_admin']) $messege_url="admin_messages.php"; else $messege_url="user_messages.php";

$__plan_info = $obj ->GetSingleRow("spssp_plan"," user_id=".(int)$_SESSION['userid']);
$__editable=$objInfo->get_editable_condition($__plan_info);
$__jobend=false;
if ($__plan_info['admin_to_pcompany']==3) $__jobend=true;
?>

<?php
$user_row = $obj->GetSingleRow("spssp_user", " id=".(int)$_SESSION['userid']);

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>

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

<script type="text/javascript">
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
</script>

<link href="css/main.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="layout">
	<div class="main">
		<div class="header">
			<div class="header_resize" style="display:relative;vertical-align:middle;">
            	<div id="inform_user" style="text-align:center; font-size:15px; font-weight:bold; color:#006600;"></div>
                <div class="logo"> 
                <div><img src="img/logo.jpg" width="200" height="57" border="0" align="absbottom" />
                <font style="font-size:20px; font-weight:bold; margin-left:130px; color:#0099ff;"> <?php if (!$__editable && $__jobend==false) echo "印刷イメージ依頼中のため編集できません"; else if (!$__editable && $__jobend==true) echo "印刷依頼済みのため編集できません"; ?></font>
                </div>

                <div style="font-size:11px;height:12px;vertical-align:top;">
    			<?php  echo $objInfo->get_user_name_image_or_src_from_user_side($usert_id ,$hotel_id=1, $name="guest_page.png",$extra=".");?>
				</div>
                </div>
                <div id="head_right">
                  <div id="login_date">前回のログイン　<?=$last_log_date?></div>
                  <div id="logout_area"> <a href="logout.php" <?=$make_plan?>>ログアウト</a>　｜　<a href="javascript:;" onclick="MM_openBrWindow('support/operation_u.html','','scrollbars=yes,width=620,height=600')">ヘルプ</a></div>
                </div>
  				<div class="clr"></div>
  				<div class="menu_nav" >
                  <ul id="menu">
                    <li class="active"><a href="dashboard.php" <?=$make_plan?>><div class="nv_pd"><img src="images/space.gif" width="100" height="10" /></div>
                    Home</a></li>
                    <li><a href="table_layout.php" <?=$make_plan?>><div class="nv_pd"><img src="images/space.gif" width="100" height="10" /></div>テーブルの配置</a></li>
                    <li><a href="hikidemono.php" <?=$make_plan?>>引出物・料理の<br>
                      登録</a></li>
                    <li><a href="my_guests.php" <?=$make_plan?>>招待者リスト<br>
                      の作成</a></li>
                    <li><a href="make_plan.php"><div class="nv_pd"><img src="images/space.gif" width="100" height="10" /></div>席次表</a></li>
                    <!--<li><a href="menu_group.php">Guest Menus</a></li>-->
                    <!--<li><a href="gifts.php">Guest Gifts</a></li>-->
                    <li><a href="order.php" <?=$make_plan?>><div class="nv_pd"><img src="images/space.gif" width="100" height="10" /></div>発注</a></li>
                    <!--<li><a href="dummy.php">Preview</a></li>-->
                    <li><a href="download.php" <?=$make_plan?>>招待者リスト<br>
                      ダウンロード</a></li>
                    <!--<li><a href="dummy.php">About Me</a></li>-->
                    <li><a href="user_info.php" <?=$make_plan?>><div class="nv_pd"><img src="images/space.gif" width="100" height="10" /></div>お客様情報</a></li>
                    <li><a href="<?=$messege_url?>" <?=$make_plan?>><div class="nv_pd"><img src="images/space.gif" width="100" height="10" /></div>メッセージ</a></li>
                  </ul>
    		  </div>
  				<div class="clr"></div>
			</div>
		</div>
	</div>
	<div id="main_contents">
