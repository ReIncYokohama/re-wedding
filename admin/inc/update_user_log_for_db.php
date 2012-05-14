<?php
include_once(dirname(__FILE__)."/../../fuel/load_classes.php");
function update_user_log_for_db($tmOut, $obj, $id_arry) {
	$i=0;
	while (isset($id_arry[$i])) {
    $user_id = $id_arr[$i];
		$fileName = "../".USER_LOGIN_DIRNAME.$id_arry[$i].".log";
		if (file_exists($fileName)) {
			$cont = file_get_contents($fileName);
			$fn = split("#", $cont);
			$nowDate = strtotime(date("Y/m/d H:i:s"));
			$accDate = strtotime($fn[1]);
			if ($nowDate-$accDate>$tmOut) {
				$user_log['logout_time'] = $fn[1];
				$obj->UpdateData("spssp_user_log", $user_log, " id=".(int)$fn[4]);
				unlink($fileName);
			}
		}
		$i++;
	}
}
?>