<?php
	if(trim($_SESSION['adminid'])=='')
		{
			
			@session_destroy();
			redirect("index.php?action=required");
		}
?>
