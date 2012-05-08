<?php
include_once("update_staff_log.php");
	$fileName = STAFF_LOGIN_FILENAME;
	if (!file_exists($fileName)) {
		session_regenerate_id();
		$_SESSION["regenerate_id"] = session_id();
		update_staff_log($fileName);
	}
	else {
		$cont = file_get_contents($fileName);
		$fn = explode("#", $cont);
		$reg_id = $fn[0];
		$dt = $fn[1];
		$nowDate = strtotime(date("Y/m/d H:i:s"));
		$accDate = strtotime($dt);
		if ($reg_id!="") {
			if ($reg_id!=$_SESSION['regenerate_id']) {
				if (($nowDate-$accDate)<(int)STAFF_LOGIN_TIMEOUT) {
					echo "<script> alert('管理者ＩＤで既にログインされています');window.close(); </script>";
					$_SESSION['regenerate_id'] = "";
					redirect("logout.php");
				}
				else {
					session_regenerate_id();
					$_SESSION["regenerate_id"] = session_id();
					update_staff_log($fileName);
				}
			}
			else {
				update_staff_log($fileName);
			}
		}
		else {
			session_regenerate_id();
			$_SESSION["regenerate_id"] = session_id();
			update_staff_log($fileName);
		}
	}
?>
