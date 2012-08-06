<?php
require_once(dirname(__file__)."/../fuel/load_classes.php");

//注意今は実行できないようにしている。
$sampli=Config::load("sampli");
$hcode=$sampli["hcode"];
$users = \Model_User::find_all();

$fp = fopen("rm.sh","a");

$hcode = 1;

foreach($users as $user){
  if($user->past_delete_users($hcode)){
    print "delete user where user_id = "$user->id;
    $path = dirname(__file__)."/../name_image/user/".$user->id;
    try{
      if(is_dir($path)){
        fwrite($fp,"rm -rf {$path}\n");
      }
    }catch(Exception $e){
      print_r($e);
    }
    $user->delete();
  }
}
fclose($fp);
