<?php
include_once("admin/inc/dbcon.inc.php");
include_once("admin/inc/class.dbo.php");
require_once("admin/inc/imageclass.inc.php");
include_once("inc/checklogin.inc.php");
include_once("inc/gaiji.image.wedding.php");

$obj = new DBO();

$post = $obj->protectXSS($_POST);
$user_id = (int)$_SESSION['userid'];
$guest_id = (int)$_POST['id'];

/*
print_r($_POST);
print_r($post);
exit();
*/
$orderst = $obj->GetSingleRow(" spssp_guest_orderstatus ", " user_id =".(int)$user_id);
if($orderst)
  {
    $value1['orderstatus']=  $post['gender_status'];
    $obj->UpdateData("spssp_guest_orderstatus", $value1," user_id=".$user_id);
    unset($post['gender_status']);
  }
else
  {
    $query_string="INSERT INTO spssp_guest_orderstatus (orderstatus,user_id) VALUES ('".$post['gender_status']."','".$user_id."');";
    mysql_query($query_string);
    unset($post['gender_status']);
  }

    
unset($post['male_first_gaiji_img']);
unset($post['male_first_gaiji_gid']);
unset($post['male_first_gaiji_gsid']);
unset($post['male_last_gaiji_img']);
unset($post['male_last_gaiji_gid']);
unset($post['male_last_gaiji_gsid']);

unset($post['comment1_gaiji_img']);
unset($post['comment1_gaiji_gid']);
unset($post['comment1_gaiji_gsid']);
unset($post['comment2_gaiji_img']);
unset($post['comment2_gaiji_gid']);
unset($post['comment2_gaiji_gsid']);

//ダブルクリック回避


include("admin/inc/main_dbcon.inc.php");
$respects = $obj->GetAllRow(" spssp_respect");

include("admin/inc/return_dbcon.inc.php");
foreach($respects as $respect)
      {
        if($post['respect_id'] == $respect['id'])
          {
            $guest_respect = $respect['title'];
          }
      }

//$hotel_id = $HOTELID;

$hotel_id=1;
$user_folder = sprintf("%s/user_name/%d/",get_image_db_directory($hotel_id),$user_id);

if($guest_id >0)
  { 
    
    //更新時にゲストの外字のデータをすべて削除する。
    //    $obj->DeleteRow("spssp_gaizi_detail_for_guest","");
    $gaizi_detail_sql = "delete from spssp_gaizi_detail_for_guest where guest_id=".(int)$guest_id.";";  
    mysql_query($gaizi_detail_sql);
    
    unset($post['id']);
   
    
    $obj->UpdateData("spssp_guest",$post," id=".(int)$guest_id);
    unset($post['gender_status']);
    $guest_row = $obj->GetSingleRow(" spssp_guest ", " id=".(int)$guest_id);
    
    
    $chagne_log=false;
    foreach($post as $key=>$value)
      {
        if($value==0 && $guest_row[$key]=="")
          {
            $guest_row[$key]=0;
          }
        if($value=="" && $guest_row[$key]==0)
          {
            $guest_row[$key]="";
          }
    
        if($value!=$guest_row[$key])
          {
            $chagne_log=true;
      
          }
        $change_string[$key]=$guest_row[$key];
      }
  
    if($chagne_log)
      {
        $before=implode("|",$change_string);
        $after=implode("|",$post);
  
        $update_array['date']=date("Y-m-d H:i:s");
        $update_array['guest_id']=$_POST['id'];
        $update_array['user_id']=$user_id;
        $update_array['previous_status']=$before;
        $update_array['current_status']=$after;
        $update_array['admin_id']=$_SESSION['adminid'];
        $update_array['type']=2;
        $lastids = $obj->InsertData("spssp_change_log", $update_array);
    
      }
  }
else
  { 
    //mark
    $post['user_id']=$user_id;
    $guest_id=$obj->InsertData("spssp_guest",$post);
  }

  //gidにはshiftjisのcodeを代入している。
set_guest_gaiji_position($user_id,$guest_id,$post["last_name"],1,$_POST["male_last_gaiji_img"],$_POST["male_last_gaiji_gsid"],$_POST["male_last_gaiji_gid"]);
set_guest_gaiji_position($user_id,$guest_id,$post["first_name"],0,$_POST["male_first_gaiji_img"],$_POST["male_first_gaiji_gsid"],$_POST["male_last_gaiji_gid"]);
set_guest_gaiji_position($user_id,$guest_id,$post["comment1"],2,$_POST["comment1_gaiji_img"],$_POST["comment1_gaiji_gsid"],$_POST["comment1_gaiji_gid"]);
set_guest_gaiji_position($user_id,$guest_id,$post["comment2"],3,$_POST["comment2_gaiji_img"],$_POST["comment2_gaiji_gsid"],$_POST["comment2_gaiji_gid"]);

//外字の画像を生成する。
make_guest_images($user_id,$guest_id,$post["last_name"],$post["first_name"],$post["comment1"],$post["comment2"],$post["respect"],
                  $_POST["male_last_gaiji_img"],$_POST["male_first_gaiji_img"],$_POST["comment1_gaiji_img"],$_POST["comment2_gaiji_img"]);

$gift_group = $_POST['gift_group_id'];
$menu_grp = $_POST['menu_grp'];
    
if(isset($gift_group) && $gift_group != '')
  {
    $query_string="delete from spssp_guest_gift where guest_id = '".$guest_id."' and user_id = '".$user_id."'";
    mysql_query($query_string);
    $query_string="INSERT INTO spssp_guest_gift (guest_id,group_id,user_id) VALUES ('".$guest_id."','".$gift_group."','".$user_id."');";
    mysql_query($query_string);
  }
  
if(isset($menu_grp) && $menu_grp != '')
  {
    $query_string="delete from spssp_guest_menu where guest_id = '".$guest_id."' and user_id = '".$user_id."'";
    mysql_query($query_string);
    $query_string="INSERT INTO spssp_guest_menu (guest_id,menu_id,user_id) VALUES ('".$guest_id."','".$menu_grp."','".$user_id."');";
    mysql_query($query_string);
  }

$update_array['date']=date("Y-m-d H:i:s");
$update_array['guest_id']=$guest_id;
$update_array['user_id']=$user_id;
$update_array['admin_id']=$_SESSION['adminid'];
$update_array['type']=4;
$lastids = $obj->InsertData("spssp_change_log", $update_array); 
  
redirect("my_guests.php?page=".$_GET['guest_id']);


