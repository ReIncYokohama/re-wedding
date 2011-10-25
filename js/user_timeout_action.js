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
	timerId2 = setInterval('user_access_update()', logUpdateLength);
	user_access_file_update();
}