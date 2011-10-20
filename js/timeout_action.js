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