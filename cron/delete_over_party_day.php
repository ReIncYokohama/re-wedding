<?php
require_once(dirname(__file__)."/../fuel/load_classes.php");
return;
$users = \Model_User::find_all();

foreach($users as $user){
  if($user->past_delete_account()){
    $user->delete();
  }
}
