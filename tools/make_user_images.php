<?php
include_once("../admin/inc/dbcon.inc.php");
include_once("../admin/inc/class.dbo.php");
include_once("../inc/gaiji.image.wedding.php");
include_once("../fuel/load_classes.php");
//fuelのパッケージがcommandの場合うまく働かないため直接指定。
include_once(dirname(__FILE__)."/../fuel/app/classes/core/image.php");
$_SERVER["SCRIPT_FILENAME"] = __FILE__;

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
  $user = Model_User::find_by_pk($user["id"]);
  $user_row = $user->to_array();
  $user_respect = "";
  echo $user_row["id"].":".$user_row["man_lastname"];
  list($man_firstname_gaijis,$man_lastname_gaijis,$woman_firstname_gaijis,$woman_lastname_gaijis) 
    = $user->get_gaiji_arr();
  make_user_images($user->id,$user->man_lastname,$user->man_firstname,$user->woman_lastname,
    $user->woman_firstname,$man_lastname_gaijis,$man_firstname_gaijis,$woman_lastname_gaijis,$woman_firstname_gaijis);
    //ゲストとして新郎を登録
  $man_guest = Model_Guest::find_by(array(array("user_id","=",$user->id),array("sex","=","Male"),array("self","=","1")));
  make_guest_images($user->id,$man_guest->id,$user->man_lastname,$user->man_firstname,$man_guest->comment1,"","様",
                    $man_lastname_gaijis,$man_firstname_gaijis,array(),array());

    //ゲストとして新婦を登録    
  $woman_guest = Model_Guest::find_by(array(array("user_id","=",$user->id),array("sex","=","Female"),array("self","=","1")));
  make_guest_images($user->id,$woman_guest->id,$user->woman_lastname,$user->woman_firstname,$woman_guest->comment1,"","様",
                    $woman_lastname_gaijis,$woman_firstname_gaijis,array(),array());
}
