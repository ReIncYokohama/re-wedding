<?php
include_once("../admin/inc/dbcon.inc.php");
include_once("../admin/inc/class.dbo.php");
include_once("../inc/gaiji.image.wedding.php");
//fuelのパッケージがcommandの場合うまく働かないため直接指定。
include_once(dirname(__FILE__)."/../fuel/app/classes/core/image.php");
include_once(dirname(__FILE__)."/../fuel/app/classes/core/plan.php");
include_once(dirname(__FILE__)."/../fuel/app/classes/core/clicktime.php");
$_SERVER["SCRIPT_FILENAME"] = __FILE__;

$obj = new DBO();

$users = $obj->GetAllRow("spssp_user");

foreach($users as $user){
  $plan = Model_Plan::find_one_by_user_id($user["id"]);
  if($plan->admin_to_pcompany == 2){
    $plan->admin_to_pcompany = 3;
    $plan->save();
  }
  $clicktime = Model_Clicktime::find_one_by_user_id($user["id"]);
  if($clicktime->hon_hachu != "0000-00-00 00:00:00"){
    $plan->admin_to_pcompany = 2;
    $plan->save();
  }
}
