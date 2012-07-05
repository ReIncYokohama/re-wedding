<?php
include_once(dirname(__FILE__)."/../inc/gaiji.image.wedding.php");
include_once(dirname(__FILE__)."/../fuel/load_classes.php");
//fuelのパッケージがcommandの場合うまく働かないため直接指定。
include_once(dirname(__FILE__)."/../fuel/app/classes/core/image.php");
$_SERVER["SCRIPT_FILENAME"] = __FILE__;

$users = Model_User::find_all();

foreach($users as $user){
  echo $user->id.":".$user->man_lastname;
  list($man_firstname_gaijis,$man_lastname_gaijis,$woman_firstname_gaijis,$woman_lastname_gaijis)
    = $user->get_gaiji_gu_char_img_arr();

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