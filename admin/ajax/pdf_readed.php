<?php
include_once("../../fuel/load_classes.php");

$user_id = $_GET['user_id'];
$plan = Model_Plan::find_one_by_user_id($user_id);
if($_GET["userpage"]){
  $plan->on_read_uploaded_image_for_user();
  $plan->save();
}else{
  $plan->on_read_uploaded_image();
  $plan->save();
}

$filename = $_GET['filename'];
Response::redirect("../".$filename);
?>
