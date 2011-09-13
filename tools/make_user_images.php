<?php
include_once("../admin/inc/dbcon.inc.php");
include_once("../admin/inc/class.dbo.php");
include_once("../inc/gaiji.image.wedding.php");

$obj = new DBO();

$users = $obj->GetAllRow("spssp_user");

function get_gaiji_ima_arr($gaijis){
  $return_arr = array();
  for($i=0;$i<count($gaijis);++$i){
    array_push($return_arr,$gaijis["gu_char_img"]);
  }
  return $return_arr;
}

foreach($users as $user){
  $user_respect = "";
  echo $user["id"].":".$user["man_lastname"];
  
  $query_string = "SELECT * FROM spssp_gaizi_detail_for_user where gu_id = ".$user["id"];
  $man_firstname_gaijis = getObjectArrayToArray($obj->getRowsByQuery($query_string." and gu_trgt_type=0 order by gu_char_position"),"gu_char_img");
  $man_lastname_gaijis = getObjectArrayToArray($obj->getRowsByQuery($query_string." and gu_trgt_type=1 order by gu_char_position"),"gu_char_img");
  $woman_firstname_gaijis = getObjectArrayToArray($obj->getRowsByQuery($query_string." and gu_trgt_type=2 order by gu_char_position"),"gu_char_img");
  $woman_lastname_gaijis = getObjectArrayToArray($obj->getRowsByQuery($query_string." and gu_trgt_type=3 order by gu_char_position"),"gu_char_img");

  //外字の画像を生成する。
  make_user_images($user["id"],$user["man_lastname"],$user["man_firstname"],$user["woman_lastname"],$user["woman_firstname"],
                    $man_lastname_gaijis,$man_firstname_gaijis,$woman_lastname_gaijis,$woman_firstname_gaijis);
}
