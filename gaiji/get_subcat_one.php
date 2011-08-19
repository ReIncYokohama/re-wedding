<?php
include('gaijidmin/inc/dbcon.inc.php');


	$catId = $_REQUEST['catId'];
	
	$sql="select id, g_image_name from gaiji_option where id='".$catId."'";
	$result=mysql_query($sql);
	$row=mysql_fetch_assoc($result)

	

?>



	<img src="upload/img_select/<?=$row['g_image_name']?>" id="select" alt="選択漢字" />
