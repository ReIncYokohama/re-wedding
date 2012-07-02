<?php
require_once("../inc/class.dbo.php");
include_once("../../fuel/load_classes.php");

if(!Core_Session::is_super()){
  $message = Model_Message::find_by_pk($_POST["id"]);
  $message->read(Core_Session::get_staff_id();
}