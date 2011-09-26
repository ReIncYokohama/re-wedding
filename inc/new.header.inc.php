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
	$row1 = mysql_fetch_array($resultl);
	$_SESSION['lastlogintime'] =$row1['login_time'];



$last_log_date = str_replace("-", "/",$_SESSION['lastlogintime']);

if ($_SESSION['userid_admin']) $messege_url="admin_messages.php"; else $messege_url="user_messages.php";
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
<script type="text/javascript">
	$(function() {
		$("#change_pass").dialog({
			autoOpen: false,
			height: 200,
			width: 420,
			show: "shake",
			hide: "explode",
			modal: true,
			buttons: {
				"送信": function() {

						var cur_pass = $("#cur_password").val();
						var password = $("#password").val();
						var conf_pass = $("#conf_password").val();

						if(!cur_pass || !password || !conf_pass)
						{
							alert("Please Fill All Fields");
							return false;
						}
						if(password != conf_pass)
						{
							alert("New password does not matched");
							return false;
						}
						var flag = 0;
						var abc = $( this );
						$.post('ajax/change_password.php',{'cur_pass':cur_pass,'action':'check_user'}, function (data){
							if(parseInt(data) == 0)
							{
								flag = 1;
								alert('Plese enter correct current password');
								return false;
							}
							else
							{
								$.post('ajax/change_password.php', {'cur_pass': cur_pass,'password':password,'action':'change_pass'},
								function(data) {
									abc.dialog( "close");
									//inform_user();
										//alert("Password changed successfully");



								});
							}

						});

				},
				キャンセル: function() {

					$( this ).dialog( "close" );
				},
				閉じる:function() {

					$( this ).dialog( "close" );
				}
			}
		});

	});

	function change_password()
	{
		$("#cur_password").val("");
		$("#password").val("");
		$("#conf_password").val("");
		$("#change_pass").dialog("open");
	}

</script>
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
                <div class="logo"> <div><img src="img/logo.jpg" width="200" height="57" border="0" /></div>
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
