<?php
function update_staff_log($fileName) {
	$cont = $_SESSION["regenerate_id"]."#";
	$cont .= date("Y/m/d H:i:s")."#";
	$cont .= $_SESSION['adminid']."#";
	$cont .= ($_SESSION["super_user"]==true)? "P":"S";
	file_put_contents($fileName, $cont);
}
?>