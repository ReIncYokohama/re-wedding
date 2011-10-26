<?php
include_once("../admin/inc/class.dbo.php");
include_once("../admin/inc/dbcon.inc.php");
$obj = new DBO();
include_once("update_user_log.php");

	$fileName = USER_LOGIN_DIRNAME.$_SESSION['userid'].".log";
	if (!file_exists($fileName)) {
		session_regenerate_id();
		$_SESSION["regenerate_user_id"] = session_id();
		update_user_log($fileName);
	}
	else {
		$cont = file_get_contents($fileName);
		$fn = split("#", $cont);
		$reg_id = $fn[0];
		$dt = $fn[1];
		$nowDate = strtotime(date("Y/m/d H:i:s"));
		$accDate = strtotime($dt);
		if ($reg_id!="") {
			if ($reg_id!=$_SESSION['regenerate_user_id']) {
				if (($nowDate-$accDate)<(int)USER_LOGIN_TIMEOUT) {
						echo "<script> alert('既にログインされています'); </script>";
						$_SESSION['regenerate_user_id'] = "";
						redirect("logout.php");
				}
				else {
					// DBログの更新
					unset($user_log);
					$user_log['logout_time'] = date("Y/m/d H:i:s",$accDate);
					$obj->UpdateData("spssp_user_log", $user_log, " id=".(int)$fn[4]);
					// 新たにセッションを開始
					session_regenerate_id();
					$_SESSION["regenerate_user_id"] = session_id();
					update_user_log($fileName);
				}
			}
			else {
				update_user_log($fileName);
			}
		}
		else {
			session_regenerate_id();
			$_SESSION["regenerate_user_id"] = session_id();
			update_user_log($fileName);
		}
	}
?>
