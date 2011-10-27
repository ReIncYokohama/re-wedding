<?php
	@session_start();
	include_once("../admin/inc/include_class_files.php");

if($_SESSION['printid'] =='')
{
  redirect("index.php");exit;
}
$obj = new DBO();
$objInfo = new InformationClass();

$user_id =$objInfo->get_user_id_md5( $_GET['user_id']);

if($user_id>0)
{
	redirect("../plan_pdf_small.php?user_id=".$user_id);exit;
}
else
{
	exit;
}
?>