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
while($row=mysql_fetch_array($data_rows))
	{
    $user_id = $row["id"];
    $plan = Model_Plan::find_one_by_user_id($user_id);
    $user = Model_User::find_by_pk($user_id);
    if($user->past_deadline_honhatyu($user_id)){
      if(!$plan->sent_sekijihyo_limit_mail()){
        if (DEBUG!=NULL) echo "sekijihyo_limit_mail::".$row['id']." : ".$row['party_day']." : ".$row['man_lastname']." : ".$row['woman_lastname']." : ".$row['mail']." : ".$row["party_day"]."<br />\n";
        $objMail -> sekiji_day_limit_over_admin_notification_mail($user_id);
        $objMail -> sekiji_day_limit_over_user_notification_mail($user_id);      
				unset($post);
        $post['sekiji_email_send_today_check'] = date("Y/m/d");
        $obj->UpdateData('spssp_plan',$post," user_id=".$user_id);
      }
      
    }
    if($user->past_deadline_hikidemono($user_id)){
      if(!$plan->sent_hikidemono_limit_mail()){
        if (DEBUG!=NULL) echo "hikidemono_limit_mail::".$row['id']." : ".$row['party_day']." : ".$row['man_lastname']." : ".$row['woman_lastname']." : ".$row['mail']." : ".$row["party_day"]."<br />\n";
				$objMail -> hikidemono_day_limit_over_admin_notification_mail($user_id);
				$objMail -> hikidemono_day_limit_over_user_notification_mail($user_id);
				unset($post);
				$post['gift_daylimit']=2;
				$obj->UpdateData('spssp_plan',$post," user_id=".$user_id);
      }

    }
	}
if (DEBUG!=NULL) echo "<br />\n";
?>
