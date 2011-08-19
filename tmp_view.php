<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
include_once("inc/gaiji.image.wedding.php");
/*
class Gaiji(){
  
}*/

$last_name = $_GET["last_name"];
$first_name = $_GET["first_name"];
$comment1 = $_GET["comment1"];
$comment2 = $_GET["comment2"];

$info = gd_info();


make_name_plate_view($last_name,$first_name,$comment1,$comment2,array("gaiji/upload/img_ans/FAB1.png"));
//make_text_view($last_name,array("gaiji/upload/img_ans/FAB1.png"));
