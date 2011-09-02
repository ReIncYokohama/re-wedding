<?php
define("DEBUG", "yes");
	require_once("../admin/inc/include_class_files.php");
	include_once("../admin/inc/dbcon.inc.php");

	$objMsg = new MessageClass();

//	$query_string="SELECT * FROM spssp_user where party_day >= '".date("Y-m-d")."' and mail != '' and subcription_mail=0;";
	$query_string="SELECT * FROM spssp_user where party_day >= '".date("Y-m-d")."' order by party_day asc;";
	$data_rows=mysql_query($query_string);

	if (DEBUG!=NULL) echo "ミッドナイトメール　：　".date("Y-m-d")."<br />\n";
	if (DEBUG!=NULL) echo "-------------------------------------<br />\n";
	while($row=mysql_fetch_array($data_rows))
	{
 		$ret = $objMsg->admin_side_user_list_new_status_notification_image_link_system($row['id'], DEBUG);
		if ($ret != NULL && DEBUG!=NULL) echo $row['id']." : ".$row['party_day']." : ".$row['man_lastname']." : ".$row['woman_lastname']." : ".$row['mail']." : ".$ret."<br />\n";
//		if (DEBUG!=NULL) echo "席次表：　".$row['id']." : ".$row['party_day']." : ".$row['man_lastname']." : ".$row['woman_lastname']." : ".$row['mail']." : ".$ret."<br />\n";
		$ret = $objMsg->admin_side_user_list_gift_day_limit_notification_image_link_system($row['id'], DEBUG);
		if ($ret != NULL && DEBUG!=NULL) echo $row['id']." : ".$row['party_day']." : ".$row['man_lastname']." : ".$row['woman_lastname']." : ".$row['mail']." : ".$ret."<br />\n";
//		if (DEBUG!=NULL) echo "引出物：　".$row['id']." : ".$row['party_day']." : ".$row['man_lastname']." : ".$row['woman_lastname']." : ".$row['mail']." : ".$ret."<br />\n<br />\n";
	}
?>
