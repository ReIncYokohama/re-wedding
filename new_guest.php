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

//pdf用の変数及び関数の準備

function getGaijiPathArray($gaiji_img){
  $pathArray = array();
  for($i=0;$i<count($gaiji_img);++$i){
    array_push($pathArray,"gaiji/upload/img_ans/".$gaiji_img[$i]);
  }
  return $pathArray;
}

$lastname_gaiji_pathArray = getGaijiPathArray($_POST["male_last_gaiji_img"]);
$firstname_gaiji_pathArray = getGaijiPathArray($_POST["male_first_gaiji_img"]);
$comment1_gaiji_pathArray = getGaijiPathArray($_POST["comment1_gaiji_img"]);
$comment2_gaiji_pathArray = getGaijiPathArray($_POST["comment2_gaiji_img"]);
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
mkdir($user_folder."guest");
$colorArray = array(0x00,0x00,0x00);
//if($_POST["stage"] == 1) $colorArray = array(255,0,0);
mkdir($user_folder."/guest");
mkdir($user_folder."/guest/".$guest_id);
mkdir($user_folder."/guest/".$guest_id."/thumb1");
mkdir($user_folder."/guest/".$guest_id."/thumb2");
$user_folder = $user_folder."/guest/".$guest_id."/";

//gidにはshiftjisのcodeを代入している。
set_guest_gaiji_position($user_id,$guest_id,$post["last_name"],1,$_POST["male_last_gaiji_img"],$_POST["male_last_gaiji_gsid"],$_POST["male_last_gaiji_gid"]);
set_guest_gaiji_position($user_id,$guest_id,$post["first_name"],0,$_POST["male_first_gaiji_img"],$_POST["male_first_gaiji_gsid"],$_POST["male_last_gaiji_gid"]);
set_guest_gaiji_position($user_id,$guest_id,$post["comment1"],2,$_POST["comment1_gaiji_img"],$_POST["comment1_gaiji_gsid"],$_POST["comment1_gaiji_gid"]);
set_guest_gaiji_position($user_id,$guest_id,$post["comment2"],3,$_POST["comment2_gaiji_img"],$_POST["comment2_gaiji_gsid"],$_POST["comment2_gaiji_gid"]);

make_text_save($post["last_name"],$lastname_gaiji_pathArray,$user_folder."last_name.png",15,150,$colorArray);
make_text_save($post["last_name"].$guest_respect,$lastname_gaiji_pathArray,$user_folder."last_name_respect.png",15,150,$colorArray);
make_text_save($post["first_name"],$firstname_gaiji_pathArray,$user_folder."first_name.png",15,150,$colorArray);
make_text_save($post["comment1"],$comment1_gaiji_pathArray,$user_folder."comment1.png",15,150,$colorArray);
make_text_save($post["comment2"],$comment2_gaiji_pathArray,$user_folder."comment2.png",15,150,$colorArray);
$comment_gaiji_pathArray = array_merge($comment1_gaiji_pathArray,$comment2_gaiji_pathArray);
$fullname_gaiji_pathArray = array_merge($lastname_gaiji_pathArray,$firstname_gaiji_pathArray);
make_text_save($post["comment1"].$post["comment2"],$comment_gaiji_pathArray,$user_folder."full_comment.png",15,150,$colorArray);
make_text_save($post["last_name"]." ".$post["first_name"]." ".$guest_respect,$comment_gaiji_pathArray,$user_folder."guest_fullname.png",15,150,$colorArray);
make_text_save($guest_respect,array(),$user_folder."guest_respect.png",15,150,$colorArray);

make_text_save($post["last_name"].$guest_respect,$lastname_gaiji_pathArray,$user_folder."thumb1/last_name_respect.png",11,150,$colorArray);  
make_text_save($post["comment1"],$comment1_gaiji_pathArray,$user_folder."thumb1/comment1.png",11,150,$colorArray);
make_text_save($post["comment2"],$comment2_gaiji_pathArray,$user_folder."thumb1/comment2.png",11,150,$colorArray);
make_text_save($post["comment1"].$comment2,$comment_gaiji_pathArray,$user_folder."thumb1/full_comment.png",11,150,$colorArray);
make_text_save($post["last_name"]." ".$post["first_name"]." ".$guest_respect,$fullname_gaiji_pathArray,$user_folder."thumb1/guest_fullname.png",11,150,$colorArray);

make_text_save($post["last_name"].$guest_respect,$lastname_gaiji_pathArray,$user_folder."thumb2/last_name_respect.png",9,100,$colorArray);  
make_text_save($post["comment1"],$comment1_gaiji_pathArray,$user_folder."thumb2/comment1.png",9,100,$colorArray);
make_text_save($post["comment2"],$comment2_gaiji_pathArray,$user_folder."thumb2/comment2.png",9,100,$colorArray);
make_text_save($post["comment1"].$comment2,$comment_gaiji_pathArray,$user_folder."thumb2/full_comment.png",9,100,$colorArray);
make_text_save($post["last_name"]." ".$post["first_name"]." ".$guest_respect,$fullname_gaiji_pathArray,$user_folder."thumb2/guest_fullname.png",9,100,$colorArray);

//pdf用の画像を生成。

$savefile = sprintf("%s/user_name/%d/%s/%d/%s",get_image_db_directory($hotel_id),$user_id,"guest",$guest_id,"namecard.png");
make_name_plate_save($post["last_name"],$post["first_name"],$post["comment1"],$post["comment2"],
                     $lastname_gaiji_pathArray,$firstname_gaiji_pathArray,
                     $comment1_gaiji_pathArray,$comment2_gaiji_pathArray,$savefile,$colorArray,$guest_respect);
    
$gift_group = $_POST['gift_group_id'];
$menu_grp = $_POST['menu_grp'];
    
if(isset($gift_group) && $gift_group != '')
  {
    $query_string="INSERT INTO spssp_guest_gift (guest_id,group_id,user_id) VALUES ('".$guest_id."','".$gift_group."','".$user_id."');";
    mysql_query($query_string);   
  }
  
if(isset($menu_grp) && $menu_grp != '')
  {
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

function get_image_db_directory($hotel_id){
  $result_image_db_dir = "";
  $query = "select gc_sval_0 as val from spssp_gaizi_cfg where gc_cfg_type = 3 and gc_cscode = ".$hotel_id;
  $result = mysql_query($query );
  $num = mysql_num_rows($result);
  if($num>0){
    while($fetchedRow = mysql_fetch_assoc($result)){
      $result_image_db_dir = (string)$fetchedRow["val"];
    }
  }
  mysql_free_result($result);
  $result_image_db_dir = explode("../",$result_image_db_dir);

  return $result_image_db_dir[1];
}
