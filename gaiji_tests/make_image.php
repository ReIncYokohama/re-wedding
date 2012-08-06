<?php
include(dirname(__FILE__)."/../inc/gaiji.image.wedding.php");

$last_name = $_GET["last_name"];
$first_name = $_GET["first_name"];
$comment1 = $_GET["comment1"];
$comment2 = $_GET["comment2"];
$respect = $_GET["respect"];
$gift_name = $_GET["gift_name"];
$menu_name = $_GET["menu_name"];
$memo = $_GET["memo"];

$gaiji_arr = array(dirname(__file__)."/../gaiji-image/img_ans/FAB1.png",
                   dirname(__file__)."/../gaiji-image/img_ans/FAB1.png");

switch($_GET["view"]){
  case "comment_plate":
    make_comment_plate_view($comment1,$comment2,$gaiji_arr,$gaiji_arr);
    break;
  case "name_plate":
    make_name_plate_view($last_name,$first_name,$comment1,$comment2,
                         $gaiji_arr,$gaiji_arr,$gaiji_arr,$gaiji_arr);
    break;
  case "full_plate":
    make_name_plate_full_view($last_name,$first_name,$comment1,$comment2,$gift_name,$menu_name,$memo,
                              $gaiji_arr,$gaiji_arr,$gaiji_arr,$gaiji_arr,array(0x00,0x00,0x00),
                              $respect,$comment1." ".$comment2,$gaiji_arr
                         );
    break;
}


