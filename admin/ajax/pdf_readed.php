<?php
	require_once("../inc/include_class_files.php");
	include_once("../inc/checklogin.inc.php");
    $objinfo = new InformationClass();

	$user_id = $_GET['user_id'];
    $filename = $_GET['filename'];
    $v = $_GET['vset'];

    $objinfo->pdf_readed($user_id, $v);

    redirect("../".$filename);
?>
