$(function(){
    $(".displayBox").mousemove(function(){
    	resetTimeOut();
        return true;
   });
});

function setChangeAction() {
	changeAction = true;
	resetTimeOut();
}
function keyDwonAction(event) {
	if (event.keyCode!=9) changeAction = true; // Tab Key
	alert("key");
	resetTimeOut();
}
function clickAction () {
	resetTimeOut();
}
function resetTimeOut() {
	clearInterval(timerId);
	timerId = setInterval('user_timeout()', timeOutLength);
}
function user_timeout() { // 編集ページはこのfunctionをオーバライト
	clearInterval(timerId);
	alert("タイムアウトしました");
	window.location = "logout.php";	
}

function user_access_update() { // 一定時間でログのアクセス時間を更新
	clearInterval(timerId2);
//	ajaxでファイル作成
//	$cont = $_SESSION["regenerate_id"]."#";
//	$cont .= date("Y/m/d H:i:s");
//	$cont .= $_SESSION['adminid']."#";
//	$cont .= ($_SESSION["super_user"]==true)? "P":"S";
//	$cont .= $_SESSION["user_log_id"];
//	file_put_contents(USER_LOGIN_FILENAME.$_SESSION['user_id']."log", $cont);
	timerId2 = setInterval('user_access_update()', 5000);
}
