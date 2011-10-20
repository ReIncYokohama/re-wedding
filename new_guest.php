<?php
include_once("admin/inc/dbcon.inc.php");
include_once("admin/inc/class.dbo.php");
require_once("admin/inc/imageclass.inc.php");
include_once("inc/checklogin.inc.php");
include_once("inc/gaiji.image.wedding.php");
include_once("admin/inc/class_data.dbo.php");

$obj = new DBO();
$post = $obj->protectXSS($_POST);
$user_id = (int)$_SESSION['userid'];
$guest_id = (int)$_POST['id'];

$data_obj = new DataClass;

$timeoutCheck = $post['timeout'];
unset($post['timeout']);

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

$guest_respect = $data_obj->get_respect($post["respect_id"]);

$hotel_id=1;
$user_folder = sprintf("%s/user_name/%d/",get_image_db_directory($hotel_id),$user_id);

//新郎新婦かどうかのフラグ
$self = true;

if($guest_id >0 && $post["self"]!=1)
  { 
    $guest_info = $data_obj->get_guest_detail($user_id,$guest_id);
    //新郎新婦は情報が少ないため省く
    if($guest_info["self"] != 1){
      //更新時にゲストの外字のデータをすべて削除する。
      //    $obj->DeleteRow("spssp_gaizi_detail_for_guest","");
      $gaizi_detail_sql = "delete from spssp_gaizi_detail_for_guest where guest_id=".(int)$guest_id.";";  
      mysql_query($gaizi_detail_sql);
      unset($post['id']);
      $data_obj->set_guest_data_update($post,$user_id,$guest_id,$_SESSION["adminid"]);
      $self = false;
    }
  }
else
  { 
    $post['user_id']=$user_id;
    $data_obj->set_guest_data_insert($post,$user_id,$_SESSION["adminid"]);
    $self = false;
  }
//新郎新婦は情報が少ないため省く
if(!$self){
  //gidにはshiftjisのcodeを代入している。
　　set_guest_gaiji_position($user_id,$guest_id,$post["last_name"],1,$_POST["male_last_gaiji_img"],$_POST["male_last_gaiji_gid"]);
　　set_guest_gaiji_position($user_id,$guest_id,$post["first_name"],0,$_POST["male_first_gaiji_img"],$_POST["male_first_gaiji_gid"]);
　　set_guest_gaiji_position($user_id,$guest_id,$post["comment1"],2,$_POST["comment1_gaiji_img"],$_POST["comment1_gaiji_gid"]);
　　set_guest_gaiji_position($user_id,$guest_id,$post["comment2"],3,$_POST["comment2_gaiji_img"],$_POST["comment2_gaiji_gid"]);
}


//guest_idが生成される必要があるため、引き出物等の登録は後で実行する必要がある。
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


if(!$self){
  //外字の画像を生成する。
  //引き出物のデータも利用するため、最後に生成
  make_guest_images($user_id,$guest_id,$post["last_name"],$post["first_name"],$post["comment1"],$post["comment2"],$guest_respect,
                  $_POST["male_last_gaiji_img"],$_POST["male_first_gaiji_img"],$_POST["comment1_gaiji_img"],$_POST["comment2_gaiji_img"]);
}else{
	$query_string = "SELECT * FROM spssp_gaizi_detail_for_user WHERE gu_id = $user_id";
	$man_firstname_gaijis = $obj->getRowsByQuery($query_string." and gu_trgt_type=0");
	$man_lastname_gaijis = $obj->getRowsByQuery($query_string." and gu_trgt_type=1");
	$woman_firstname_gaijis = $obj->getRowsByQuery($query_string." and gu_trgt_type=2");
	$woman_lastname_gaijis = $obj->getRowsByQuery($query_string." and gu_trgt_type=3");
  
  function getGaijisImgArray($gaiji_objs){
    $return_arr = array();
    for($i=0;$i<count($gaiji_objs);++$i){
      array_push($return_arr,$gaiji_objs[$i]["gu_char_img"]);
		 }
    return $return_arr;
  }
  
  if($guest_info["sex"]=="Male"){
    //新郎
      make_guest_images($user_id,$guest_id,$guest_info["last_name"],$guest_info["first_name"],$guest_info["comment1"],$guest_info["comment2"],"様",
                        getGaijisImgArray($man_lastname_gaijis),getGaijisImgArray($man_firstname_gaijis),array(),array());
  }else{
    //新婦
      make_guest_images($user_id,$guest_id,$guest_info["last_name"],$guest_info["first_name"],$guest_info["comment1"],$guest_info["comment2"],"様",
                        getGaijisImgArray($woman_lastname_gaijis),getGaijisImgArray($woman_firstname_gaijis),array(),array());
  }
}
if ($timeoutCheck=="timeout")	redirect("logout.php");
else 							redirect("my_guests.php?page=".$_GET['guest_id']."&option=".$_GET['option']);
