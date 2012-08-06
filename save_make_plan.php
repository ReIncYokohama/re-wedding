<?php

include_once("admin/inc/class_data.dbo.php");

$data_class = new DataClass;
$user_id = $_SESSION["userid"];
$admin_id = $_SESSION["adminid"];

$dataArray = json_decode($_POST["data"],true);

for($i=0;$i<count($dataArray);++$i){
  $data_class->set_guest_seats($dataArray[$i]);
}
