<?php
	if(trim($_SESSION['super_adminid'])=='')
		{
			
			@session_destroy();
			redirect("index.php?action=required");
		}
?>
