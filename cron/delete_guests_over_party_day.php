<?php
require_once(dirname(__file__)."/../fuel/load_classes.php");

//注意今は実行できないようにしている。
$sampli=Config::load("sampli");
$hcode=$sampli["hcode"];
$users = \Model_User::find_all();

$fp = fopen("rm.sh","a");

foreach($users as $user){
  if($user->past_delete_guests($hcode)){
    $path = dirname(__file__)."/../name_image/user/".$user->id."/guests";
    try{
      if(is_dir($path)){
        fwrite($fp,"rm -rf {$path}\n");
      }
    }catch(Exception $e){
      print_r($e);
    }
    $plan = Model_Plan::find_by_pk($user->plan_id);
  }
}
fclose($fp);
