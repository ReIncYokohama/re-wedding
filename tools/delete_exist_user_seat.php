<?php

//fuelのパッケージがcommandの場合うまく働かないため直接指定。
include_once(dirname(__FILE__)."/../fuel/load_classes.php");
$_SERVER["SCRIPT_FILENAME"] = __FILE__;

$users = Model_User::find_all();
foreach($users as $user){
  $usertables = Model_Usertable::find_by_user_id($user->id);
  foreach($usertables as $usertable){
    if($usertable->display==0){
      $seats = $usertable->get_user_seats();
      $usertable->delete_user_seats();
    }
  }
}

