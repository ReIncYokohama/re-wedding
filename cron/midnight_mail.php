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

$query_string="SELECT * FROM spssp_user where party_day >= '".strftime("%Y-%m-%d", strtotime("+1 day", mktime()))."' order by party_day asc;";

$data_rows=mysql_query($query_string);

if (DEBUG!=NULL) echo "ミッドナイトメール<br/>\n";
if (DEBUG!=NULL) echo "ID=".$HOTELID." :  name=".$hotel_name." : ".date("Y-m-d H:i")."path ".__FILE__."<br />\n";
if (DEBUG!=NULL) echo "-------------------------------------<br />\n";

$objMail = new MailClass();
print $confirm_day_num."<br>\n";
while($row=mysql_fetch_array($data_rows))
	{
    $user_id = $row["id"];
    $plan = Model_Plan::find_one_by_user_id($user_id);
    $user = Model_User::find_by_pk($user_id);
    if($user->past_deadline_honhatyu($user_id)){
      //if($plan->sent_sekijihyo_limit_mail()) continue;
      if (DEBUG!=NULL) echo "send mail user_id = ".$row["id"].",".$plan->sekiji_email_send_today_check.",".$row["party_day"].",".$row["name"]."\n";
      //print_r($plan);
      //$post['sekiji_email_send_today_check'] = date("Y/m/d");
      //$this->UpdateData('spssp_plan',$post," user_id=".$user_id);
      //$objMail -> sekiji_day_limit_over_admin_notification_mail($user_id);
      //$objMail -> sekiji_day_limit_over_user_notification_mail($user_id);
      if (DEBUG!=NULL) echo $row['id']." : ".$row['party_day']." : ".$row['man_lastname']." : ".$row['woman_lastname']." : ".$row['mail']." : ".$ret."<br />\n";
    }
		//$ret = $objMsg->send_hikidemono_day_limit_message($row['id']);
		//if ($ret != NULL && DEBUG!=NULL) echo $row['id']." : ".$row['party_day']." : ".$row['man_lastname']." : ".$row['woman_lastname']." : ".$row['mail']." : ".$ret."<br />\n";
	}
if (DEBUG!=NULL) echo "<br />\n";
?>
