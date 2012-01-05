<?php
define("DEBUG", "yes");

require_once(dirname(__file__)."/../admin/inc/include_class_files.php");
include_once(dirname(__file__)."/../admin/inc/dbcon.inc.php");

$obj = new dbo;
include("../admin/inc/main_dbcon.inc.php");
$hcode=$HOTELID;
$hotel_name = $obj->GetSingleData(" super_spssp_hotel ", " hotel_name ", " hotel_code=".$hcode);
include("../admin/inc/return_dbcon.inc.php");	

$objMsg = new MessageClass();

$query_string="SELECT * FROM spssp_user where party_day >= '".date("Y-m-d")."' order by party_day asc;";
$data_rows=mysql_query($query_string);

if (DEBUG!=NULL) echo "ミッドナイトメール　：　".$hotel_name." :  ID=".$HOTELID." : ".date("Y-m-d")."path ".__FILE__."<br />\n";
if (DEBUG!=NULL) echo "-------------------------------------<br />\n";

while($row=mysql_fetch_array($data_rows))
	{
 		//$ret = $objMsg->send_day_limit_message($row['id']);
		if ($ret != NULL && DEBUG!=NULL) echo $row['id']." : ".$row['party_day']." : ".$row['man_lastname']." : ".$row['woman_lastname']." : ".$row['mail']." : ".$ret."<br />\n";
		$ret = $objMsg->send_hikidemono_day_limit_message($row['id']);
		if ($ret != NULL && DEBUG!=NULL) echo $row['id']." : ".$row['party_day']." : ".$row['man_lastname']." : ".$row['woman_lastname']." : ".$row['mail']." : ".$ret."<br />\n";
	}
if (DEBUG!=NULL) echo "<br />\n";
?>
