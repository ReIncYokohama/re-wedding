<?php
include_once("../admin/inc/dbcon.inc.php");
include_once("../admin/inc/class.dbo.php");
include_once("../inc/gaiji.image.wedding.php");

$obj = new DBO();

$guests = $obj->GetAllRow("spssp_guest");
include("../admin/inc/main_dbcon.inc.php");
$respects = $obj->GetAllRow(" spssp_respect");
include("../admin/inc/return_dbcon.inc.php");

$query_string = "SELECT * FROM spssp_gaizi_detail_for_guest WHERE guest_id = ".$get['gid'];
$firstname_gaijis = $obj->getRowsByQuery($query_string." and gu_trgt_type=0 order by gu_char_position");
$lastname_gaijis = $obj->getRowsByQuery($query_string." and gu_trgt_type=1 order by gu_char_position");
$comment1_gaijis = $obj->getRowsByQuery($query_string." and gu_trgt_type=2 order by gu_char_position");
$comment2_gaijis = $obj->getRowsByQuery($query_string." and gu_trgt_type=3 order by gu_char_position");

function get_gaiji_ima_arr($gaijis){
  $return_arr = array();
  for($i=0;$i<count($gaijis);++$i){
    array_push($return_arr,$gaijis["gu_char_img"]);
  }
  return $return_arr;
}

foreach($guests as $guest){
  $guest_respect = "";
  echo $guest["user_id"].":".$guest["last_name"];
  foreach($respects as $respect)
    {
      if($guest['respect_id'] == $respect['id'])
        {
          $guest_respect = $respect['title'];
        }
    }
  //外字の画像を生成する。
  make_guest_images($guest["user_id"],$guest["id"],$guest["last_name"],$guest["first_name"],$guest["comment1"],$guest["comment2"],$guest_respect,
                    array(),array(),array(),array());
}
