
<?php 
require_once("inc/class.dbo.php");

$obj = new DBO();
$get = $obj->protectXSS($_GET);

$msg = "印刷会社へ本発注を行います。宜しいですか？";
$busuu=(int)$get['busuu'];
$user_id=(int)$get['user_id'];

/*
if((int)$get['sekiji']>0 || (int)$get['sekifuda']>0) {
	$sekiji = (int)$get['sekiji'];
	$sekifuda = (int)$get['sekifuda'];
	$sql = "update spssp_plan set day_limit_1_to_print_com=".$sekiji.", day_limit_2_to_print_com=".$sekifuda." where user_id=".(int)$user_id;
	mysql_query($sql);
	echo "<script>";
	echo "window.returnValue='OK';";
	echo "window.close();";
	echo "</script>";
}
*/
?>
<html>
<body>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>本発注の確認</title>
</head>
<script type="text/javascript">
function cl(vl){
	var busuu = "<?=$busuu?>";
	var user_id="<?=$user_id?>";
	var v1 = 0;
	var v2 = 0;
	
	if (busuu==1 || busuu==3)   v1 = document.seki_input.sekiji.value;
	if (busuu==2 || busuu==3)   v2 = document.seki_input.sekifuda.value;
	if (busuu==1 || busuu==3) {
		if (v1 == "") {
			alert ("席次表印刷部数を入力してください");
			document.seki_input.sekiji.focus();
			return false;
		}
		if (isNaN(v1) == true) {
			alert ("席次表印刷部数には半角数字を入力してください");
			document.seki_input.sekiji.focus();
			return false;
		}
	}
	if (busuu==2 || busuu==3) {
		if (v2 == "") {
			alert ("席札印刷部数を入力してください");
			document.seki_input.sekifuda.focus();
			return false;
		}
		if (isNaN(v2) == true) {
			alert ("席札印刷部数には半角数字を入力してください");
			document.seki_input.sekifuda.focus();
			return false;
		}
	}
    window.returnValue=vl+","+v1+","+v2;
    window.close();
//	if (busuu==1)	window.location.href = "confirm_order.php?user_id="+user_id+"&sekiji="+v1;
//	if (busuu==2)	window.location.href = "confirm_order.php?user_id="+user_id+"&sekifuda="+v2;
//	if (busuu==3)	window.location.href = "confirm_order.php?user_id="+user_id+"&sekiji="+v1+"&sekifuda="+v2;
}
function cl2(vl) {
    window.returnValue=vl;
    window.close();
}
window.onkeydown = function(event) {
    if(event.keyCode == 13)	return false;
    else 					return true;
}
</script>
<body bgcolor="#FCFEFF">
	<form action="confirm_order.php?user_id=<?=$user_id?>" method="post" name="seki_input">
	<?php if ($busuu==1 || $busuu==3) { ?>
	席次表印刷部数　:　
 	<input type="text" name="sekiji" id="sekiji" style="width:100px; text-align:right;"/>　部<br />
 	<?php } 
 	if ($busuu==2 || $busuu==3) {
 	?>
 	席札印刷部数　:　
 	<input type="text" name="sekifuda" id="sekifuda" style="width:100px; text-align:right;"/>　部<br />
 	<?php } ?>
 	<br />
 	<?php echo $msg; ?><br /><br />
 	 	　　　　　　　　　<!-- 位置を右にずらす -->
    <input type="button" value="　　OK　　" name="submit" onClick="cl('OK')" onkeydown="if (event.keyCode == 13) { cl('OK'); }">
    <input type="button" value="キャンセル" name="submit" onClick="cl2('CANCEL')" onkeydown="if (event.keyCode == 13) { cl2('CANCEL'); }">
    <input type="hidden" name="update" value="update" />
    </form>
<?php if ($busuu==1 || $busuu==3) { ?>
    <script type="text/javascript"> document.seki_input.sekiji.focus(); </script>
<?php } 
if ($busuu==2) {
?>
    <script type="text/javascript"> document.seki_input.sekifuda.focus(); </script>
<?php } ?>
</body>
</html>