<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
require("inc/gaiji.image.wedding.php");
/*
class Gaiji(){
  
}*/

$last_name = $_GET["last_name"];
$first_name = $_GET["first_name"];
$comment1 = $_GET["comment1"];
$comment2 = $_GET["comment2"];
$respect = $_GET["respect"];
$memo1 = $_GET["memo1"];
$memo2 = $_GET["memo2"];
$memo3 = $_GET["memo3"];

//make_user_tategaki_view("新郎",$first_name);
//make_name_plate_view($last_name,$first_name,$comment1,$comment2,array("gaiji/upload/img_ans/FAB1.png","gaiji/upload/img_ans/FAB1.png"),array(),array(),array(),array(0x00,0x00,0x00),$respect);
//make_text_view($last_name,array("gaiji/upload/img_ans/FAB1.png"));
//print check_sjis($last_name);
//make_name_plate_full_view("ヤ","隆","","","A","引き出物1","アルコール抜",array(),array(),array(),array(),array(0x00,0x00,0x00),"ちゃん");
//make_pdf_guest_info(0,"安部田",array(),"仲間",array(),7,1,8,10);

make_name_plate_right_view("テ＊","テスト","コメント1","コメント2",array(dirname(__file__)."/../gaiji-image/img_ans/FAB1.png"),array(),array(),array(),array(0x00,0x00,0x00),"様");

//nothing update here