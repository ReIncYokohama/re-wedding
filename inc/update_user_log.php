<?php
function update_user_log($fileName) {
	$cont = $_SESSION["regenerate_user_id"]."#";
	$cont .= date("Y/m/d H:i:s")."#";
	$cont .= $_SESSION['adminid']."#";
	$cont .= ($_SESSION["super_user"]==true)? "P#":"S#";
	$cont .= $_SESSION["user_log_id"];
	if(!TESTING) file_put_contents($fileName, $cont);
}
?>