<?php
require_once(dirname(__file__)."/../fuel/load_classes.php");

//注意今は実行できないようにしている。
return;
$users = \Model_User::find_all();

$fp = fopen("rm.sh","a");

foreach($users as $user){
  if($user->past_delete_account()){
    $path = dirname(__file__)."/../name_image/user/".$user->id;
    try{
      if(is_dir($path)){
        fwrite($fp,"rm -rf {$path}");
      }
    }catch(Exception $e){
      print_r($e);
    }
    $user->delete();
  }
}
fclose($fp);
