<?php
include_once(dirname(__FILE__)."/../inc/gaiji.image.wedding.php");
include_once(dirname(__FILE__)."/../fuel/load_classes.php");
//fuelのパッケージがcommandの場合うまく働かないため直接指定。
include_once(dirname(__FILE__)."/../fuel/app/classes/core/image.php");
$_SERVER["SCRIPT_FILENAME"] = __FILE__;

$users = Model_User::find_all();

foreach($users as $user){
  echo $user->id.":".$user->man_lastname;
  $user->make_image();
}