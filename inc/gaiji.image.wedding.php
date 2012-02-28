<?php
include_once(dirname(__FILE__)."/gaiji.image.util.php");
include_once(dirname(__FILE__)."/../admin/inc/class_data.dbo.php");
include_once(dirname(__FILE__)."/../fuel/load_classes.php");

/*name_plate
########################
comment1
comment2
last_name first_name
########################
*/
function make_name_plate_save($last_name,$first_name,$comment1="",$comment2="",
                         $gaiji_last_name_arr = array(),$gaiji_first_name_arr = array(),
                              $gaiji_comment1_arr = array(),$gaiji_comment2_arr = array(),$file="file.png",$color = array(0x00,0x00,0x00),$respect = ""){
  $image = get_image_name_plate($last_name,$first_name,$comment1,$comment2,
                                $gaiji_last_name_arr,$gaiji_first_name_arr,$gaiji_comment1_arr,$gaiji_comment2_arr,$color,$respect);

  imagefilter($image, IMG_FILTER_COLORIZE, $color[0], $color[1], $color[2]);
  imagepng($image,$file);
  imagedestroy($image);
}

function make_name_plate_view($last_name,$first_name,$comment1="",$comment2="",
                         $gaiji_last_name_arr = array(),$gaiji_first_name_arr = array(),
                              $gaiji_comment1_arr = array(),$gaiji_comment2_arr = array(),$color = array(0x00,0x00,0x00),$respect = ""){
  $image = get_image_name_plate($last_name,$first_name,$comment1,$comment2,
                                $gaiji_last_name_arr,$gaiji_first_name_arr,$gaiji_comment1_arr,$gaiji_comment2_arr,$color,$respect);
  imagefilter($image, IMG_FILTER_COLORIZE, $color[0], $color[1], $color[2]);
  header("Content-Type: image/png");
  imagepng($image);
  imagedestroy($image);
}
function make_name_plate_right_save($last_name,$first_name,$comment1="",$comment2="",
                         $gaiji_last_name_arr = array(),$gaiji_first_name_arr = array(),
                                    $gaiji_comment1_arr = array(),$gaiji_comment2_arr = array(),$file="file.png",$color = array(0x00,0x00,0x00),$respect = "",$groupNumber){
  $image = get_image_name_plate_right($last_name,$first_name,$comment1,$comment2,
                                      $gaiji_last_name_arr,$gaiji_first_name_arr,$gaiji_comment1_arr,$gaiji_comment2_arr,$color,$respect,$groupNumber);

  imagefilter($image, IMG_FILTER_COLORIZE, $color[0], $color[1], $color[2]);
  imagepng($image,$file);
  imagedestroy($image);
}

function make_name_plate_right_view($last_name,$first_name,$comment1="",$comment2="",
                         $gaiji_last_name_arr = array(),$gaiji_first_name_arr = array(),
                                    $gaiji_comment1_arr = array(),$gaiji_comment2_arr = array(),$color = array(0x00,0x00,0x00),$respect = "",$groupNumber){
  $image = get_image_name_plate_right($last_name,$first_name,$comment1,$comment2,
                                      $gaiji_last_name_arr,$gaiji_first_name_arr,$gaiji_comment1_arr,$gaiji_comment2_arr,$color,$respect,$groupNumber);
  imagefilter($image, IMG_FILTER_COLORIZE, $color[0], $color[1], $color[2]);
  header("Content-Type: image/png");
  imagepng($image);
  imagedestroy($image);
}


/*name_plate
########################
comment1             memo1
comment2             memo2
last_name first_name memo3
########################
*/
function make_name_plate_full_save($last_name,$first_name,$comment1="",$comment2="",$memo1,$memo2,$memo3,
                         $gaiji_last_name_arr = array(),$gaiji_first_name_arr = array(),
                                   $gaiji_comment1_arr = array(),$gaiji_comment2_arr = array(),$file="file.png",$file2="file.png",$color = array(0x00,0x00,0x00),$respect = "",$comment = "",$comment_arr = array()){
  //右側にコメント
  $image = get_image_name_plate_full($last_name,$first_name,$comment1,$comment2,$memo1,$memo2,$memo3,$gaiji_last_name_arr,$gaiji_first_name_arr,$gaiji_comment1_arr,$gaiji_comment2_arr,$color,$respect,$comment,$comment_arr,180,20,2);
  imagefilter($image, IMG_FILTER_COLORIZE, $color[0], $color[1], $color[2]);
  imagepng($image,$file);
  imagedestroy($image);

  //グループを右揃え
  $image = get_image_name_plate_full($last_name,$first_name,$comment1,$comment2,$memo1,$memo2,$memo3,
                                     $gaiji_last_name_arr,$gaiji_first_name_arr,$gaiji_comment1_arr,$gaiji_comment2_arr,$color,$respect,$comment,$comment_arr,180,2,164);
  imagefilter($image, IMG_FILTER_COLORIZE, $color[0], $color[1], $color[2]);
  imagepng($image,$file2);
  imagedestroy($image);


  /* $image = get_image_name_plate_full2($last_name,$first_name,$comment1,$comment2,$memo1,$memo2,$memo3,
     $gaiji_last_name_arr,$gaiji_first_name_arr,$gaiji_comment1_arr,$gaiji_comment2_arr,$color,$respect);*/
  
  /*  $image = get_image_name_plate_right($last_name,$first_name,$memo3,$memo2, $gaiji_last_name_arr,$gaiji_first_name_arr,$gaiji_comment1_arr,$gaiji_comment2_arr,$color,$respect,$memo1,$comment,$comment_arr);
  
  imagefilter($image, IMG_FILTER_COLORIZE, $color[0], $color[1], $color[2]);
  imagepng($image,$file2);
  imagedestroy($image);*/
}

function make_name_plate_full_view($last_name,$first_name,$comment1="",$comment2="",$memo1,$memo2,$memo3,
                         $gaiji_last_name_arr = array(),$gaiji_first_name_arr = array(),
                                   $gaiji_comment1_arr = array(),$gaiji_comment2_arr = array(),$color = array(0x00,0x00,0x00),$respect = "",$comment = "",$comment_arr=array()){

  //$image = get_image_name_plate_full($last_name,$first_name,$comment1,$comment2,$memo1,$memo2,$memo3, $gaiji_last_name_arr,$gaiji_first_name_arr,$gaiji_comment1_arr,$gaiji_comment2_arr,$color,$respect,$comment,$comment_arr,180,4,164);
  $image = get_image_name_plate_full($last_name,$first_name,$comment1,$comment2,$memo1,$memo2,$memo3, $gaiji_last_name_arr,$gaiji_first_name_arr,$gaiji_comment1_arr,$gaiji_comment2_arr,$color,$respect,$comment,$comment_arr,180,20,4);
  
  imagefilter($image, IMG_FILTER_COLORIZE, $color[0], $color[1], $color[2]);
  header("Content-Type: image/png");
  imagepng($image);
  imagedestroy($image);
}


/*name_plate
########################
last_name first_name様
########################
*/
function make_text_save($text,$gaiji_arr = array(),$file="file.png",$fontsize=14,$max_width=150,$color = array(0x00,0x00,0x00)){
  $image = get_image_text($text,$gaiji_arr,$fontsize,$max_width,$color);
  imagefilter($image, IMG_FILTER_COLORIZE, $color[0], $color[1], $color[2]);
  imagepng($image,$file);
  imagedestroy($image);
}

function make_text_view($text,$gaiji_arr=array(),$fontsize=14,$width=150,$color = array(0x00,0x00,0x00)){
  $image = get_image_text($text,$gaiji_arr,$fontsize,$width,$color);
  imagefilter($image, IMG_FILTER_COLORIZE, $color[0], $color[1], $color[2]);
  header("Content-Type: image/png");
  imagepng($image);
  imagedestroy($image);
}

function make_user_tategaki_view($comment,$name,$color = array(0x00,0x00,0x00)){
  $image = get_user_tategaki($comment,$name);
  imagefilter($image, IMG_FILTER_COLORIZE, $color[0], $color[1], $color[2]);
  header("Content-Type: image/png");
  imagepng($image);
  imagedestroy($image);
}

function get_user_tategaki($comment,$name,$gaiji_arr=array(),$angle=0,$color = array(0x00,0x00,0x00)){
  $commentfontsize = "10";
  $maxfontsize = "18";
  $height = "150";
  $commentLeft = "3";
  $nameLeft = "0";

  $font = dirname(__FILE__)."/../fonts/msmincho.ttc";
  
  $image = imagecreatetruecolor(40,$height) or die("Cannot Initialize new GD image stream");
  $col_g = imagecolorallocate($image,0xff,0xff,0xff);
  $col_t = imagecolorallocate($image,0x00,0x00,0x00);
  imagefill($image,0,0,$col_g);
  $top=0;
  $num = mb_strlen($comment,"utf-8");
  for($i=0;$i<$num;++$i){
    $str = mb_substr($comment,$i,1,"UTF-8");
    $top = gaiji_imagettftext_tategaki($image,$commentfontsize,$angle,$commentLeft,$top,$col_t,$font,$str);
  }
  
  $num = mb_strlen($name,"UTF-8");

  if($num>4){
    if($num==6){
      $maxfontsize = 14;
      $nameLeft = 1;
    }
    if($num==7){
      $maxfontsize = 10;
      $nameLeft = 3;
    }
    if($num>8){
      $maxfontsize = 8;
      $nameLeft = 4;
    }
    for($i=0;$i<$num;++$i){
      $str = mb_substr($name,$i,1,"UTF-8");
      $top = gaiji_imagettftext_tategaki($image,$maxfontsize,$angle,$nameLeft,$top,$col_t,$font,$str);
    }
    //およそ１文字25
  }else if($num==1){
    $top = gaiji_imagettftext_tategaki($image,$maxfontsize,$angle,$nameLeft,$top+30,$col_t,$font,mb_substr($name,0,1,"utf-8"));
  }else if($num==2){
    $top = gaiji_imagettftext_tategaki($image,$maxfontsize,$angle,$nameLeft,$top+5,$col_t,$font,mb_substr($name,0,1,"utf-8"));
    $top = gaiji_imagettftext_tategaki($image,$maxfontsize,$angle,$nameLeft,$top+20,$col_t,$font,mb_substr($name,1,1,"utf-8"));
  }else if($num==3){
    $top = gaiji_imagettftext_tategaki($image,$maxfontsize,$angle,$nameLeft,$top+5,$col_t,$font,mb_substr($name,0,1,"utf-8"));
    $top = gaiji_imagettftext_tategaki($image,$maxfontsize,$angle,$nameLeft,$top+10,$col_t,$font,mb_substr($name,1,1,"utf-8"));
    $top = gaiji_imagettftext_tategaki($image,$maxfontsize,$angle,$nameLeft,$top+10,$col_t,$font,mb_substr($name,2,1,"utf-8"));
  }else if($num==4){
    $top = gaiji_imagettftext_tategaki($image,$maxfontsize,$angle,$nameLeft,$top+5,$col_t,$font,mb_substr($name,0,1,"utf-8"));
    $top = gaiji_imagettftext_tategaki($image,$maxfontsize,$angle,$nameLeft,$top+3,$col_t,$font,mb_substr($name,1,1,"utf-8"));
    $top = gaiji_imagettftext_tategaki($image,$maxfontsize,$angle,$nameLeft,$top+3,$col_t,$font,mb_substr($name,2,1,"utf-8"));
    $top = gaiji_imagettftext_tategaki($image,$maxfontsize,$angle,$nameLeft,$top+3,$col_t,$font,mb_substr($name,3,1,"utf-8"));
  }
  

  return $image;
}

function get_image_name_plate($last_name,$first_name,$comment1="",$comment2="",
                         $gaiji_last_name_arr = array(),$gaiji_first_name_arr = array(),
                              $gaiji_comment1_arr = array(),$gaiji_comment2_arr = array(),$color = array(0x00,0x00,0x00),$respect=""){

  $comment_max_fontsize = "8";
  $name_max_fontsize = "11";

  $font = dirname(__FILE__)."/../fonts/msmincho.ttc";
  $width_px = "150";

  $image = imagecreatetruecolor($width_px,45) or die("Cannot Initialize new GD image stream");
  $col_g = imagecolorallocate($image,0xff,0xff,0xff);
  $col_t = imagecolorallocate($image,0x00,0x00,0x00);
  imagefill($image,0,0,$col_g);
  

  get_image_name_plate_data3($image,$width_px,$first_left,
                            $last_name,$first_name,$comment1,$comment2,
                         $gaiji_last_name_arr,$gaiji_first_name_arr,
                              $gaiji_comment1_arr,$gaiji_comment2_arr,$color,$respect);
  return $image;
}


function get_image_name_plate_right($last_name,$first_name,$comment1="",$comment2="",
                         $gaiji_last_name_arr = array(),$gaiji_first_name_arr = array(),
                                    $gaiji_comment1_arr = array(),$gaiji_comment2_arr = array(),$color = array(0x00,0x00,0x00),$respect="",$groupNumber,$comment3,$comment3_arr){
  $comment_max_fontsize = "8";
  $comment_min_fontsize = "5";

  $name_max_fontsize = "11";

  $font = dirname(__FILE__)."/../fonts/msmincho.ttc";
  $width_px = "150";

  $image = imagecreatetruecolor($width_px,45) or die("Cannot Initialize new GD image stream");
  $col_g = imagecolorallocate($image,0xff,0xff,0xff);
  $col_t = imagecolorallocate($image,0x00,0x00,0x00);
  imagefill($image,0,0,$col_g);
  

  get_image_name_plate_data_right($image,$width_px,$width_px,
                            $last_name,$first_name,$comment1,$comment2,
                         $gaiji_last_name_arr,$gaiji_first_name_arr,
                                  $gaiji_comment1_arr,$gaiji_comment2_arr,$color,
                                  $respect,$groupNumber,$comment3,$comment3_arr);
  return $image;
}


function get_image_name_plate_data($image,$width_px,$first_left,
                                   $last_name,$first_name,$comment1="",$comment2="",
                         $gaiji_last_name_arr = array(),$gaiji_first_name_arr = array(),
                                   $gaiji_comment1_arr = array(),$gaiji_comment2_arr = array(),$color = array(0x00,0x00,0x00),$respect=""){
  $comment_max_fontsize = "8";
  $name_max_fontsize = "11";

  $font = dirname(__FILE__)."/../fonts/msmincho.ttc";
  
  $name = $last_name." ".$first_name." ";
  
  if(!$comment1 || $comment1 == "") $comment1 = " ";
  if($comment2 != ""){
    $comment1_fontsize = get_image_font_size($comment_max_fontsize,$comment1,$font,$width_px,
                                           $gaiji_comment1_arr);
    gaiji_imagettftext($image,$comment1_fontsize,0,$first_left,12,$col_t,$font,$comment1,$gaiji_comment1_arr);
  }else{  
    $comment2 = $comment1;
  }
  $comment2_fontsize = get_image_font_size($comment_max_fontsize,$comment2,$font,$width_px,
                                           $gaiji_comment2_arr);
  
  gaiji_imagettftext($image,$comment2_fontsize,0,$first_left,26,$col_t,$font,$comment2,$gaiji_comment2_arr);



  $gaiji_name_arr = array_merge((array)$gaiji_last_name_arr, (array)$gaiji_first_name_arr);
  $name_for_fontsize = $name.$respect;
  if(mb_strlen($name,"utf-8")<9){
    $name_fontsize = 11;
  }else{
    $name_fontsize = get_image_font_size($name_max_fontsize,$name_for_fontsize,$font,$width_px,
                                       $gaiji_name_arr);
  }
  $nowLeft = gaiji_imagettftext($image,$name_fontsize,0,$first_left,45,$col_t,$font,$name,$gaiji_name_arr);

  if(mb_strlen($respect,"utf-8") <= 1){
    gaiji_imagettftext($image,$name_fontsize,0,$nowLeft,45,$col_t,$font,$respect,array());
  }else if(mb_strlen($respect,"utf-8")>1){
    gaiji_imagettftext($image,$name_fontsize,0,$nowLeft,45,$col_t,$font,$respect,array(),50);
  }
  
  
}

//coment1 とcomment2を足し、サイズを調整する。
function get_image_name_plate_data3($image,$width_px,$first_left,
                                   $last_name,$first_name,$comment1="",$comment2="",
                         $gaiji_last_name_arr = array(),$gaiji_first_name_arr = array(),
                                    $gaiji_comment1_arr = array(),$gaiji_comment2_arr = array(),$color = array(0x00,0x00,0x00),$respect=""){
  $comment_min_fontsize = "5";
  $comment_max_fontsize = "9";
  $name_max_fontsize = "11";

  $font = dirname(__FILE__)."/../fonts/msmincho.ttc";
  
  $name = $last_name." ".$first_name." ";

  $comment = $comment1." ".$comment2;
  $comment_num = mb_strlen($comment,"utf-8");
  if($comment_num<11){
    /*$comment_fontsize = get_image_font_size($comment_max_fontsize,$comment,$font,$width_px,
      $gaiji_comment2_arr);*/
    $gaiji_comment_arr = array_merge((array)$gaiji_comment1_arr, (array)$gaiji_comment2_arr);
    gaiji_imagettftext($image,$comment_max_fontsize,0,$first_left,26,$col_t,$font,$comment,$gaiji_comment_arr);
  }else if($comment_num<20){
    $gaiji_comment_arr = array_merge((array)$gaiji_comment1_arr, (array)$gaiji_comment2_arr);
    gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,26,$col_t,$font,$comment,$gaiji_comment_arr);
  }else{
    $gaiji_comment_arr = array_merge((array)$gaiji_comment1_arr, (array)$gaiji_comment2_arr);
    list($second,$first,$gaiji_second_arr,$gaiji_first_arr) = get_gaiji_image_split2($comment,$gaiji_comment_arr,15);
    gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,12,$col_t,$font,$first,$gaiji_first_arr);
    gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,26,$col_t,$font,$second,$gaiji_second_arr);
  }
  
  $gaiji_name_arr = array_merge((array)$gaiji_last_name_arr, (array)$gaiji_first_name_arr);
  $name_for_fontsize = $name.$respect;
  if(mb_strlen($name,"utf-8")<9){
    $name_fontsize = 11;
  }else{
    $name_fontsize = get_image_font_size($name_max_fontsize,$name_for_fontsize,$font,$width_px,
                                       $gaiji_name_arr);
  }
  $nowLeft = gaiji_imagettftext($image,$name_fontsize,0,$first_left,45,$col_t,$font,$name,$gaiji_name_arr);

  if(mb_strlen($respect,"utf-8") <= 1){
    gaiji_imagettftext($image,$name_fontsize,0,$nowLeft,45,$col_t,$font,$respect,array());
  }else if(mb_strlen($respect,"utf-8")>1){
    gaiji_imagettftext($image,$name_fontsize,0,$nowLeft,45,$col_t,$font,$respect,array(),50);
  }
  
  
}


function get_image_name_plate_data_right($image,$width_px,$first_left,
                                   $last_name,$first_name,$comment1="",$comment2="",
                         $gaiji_last_name_arr = array(),$gaiji_first_name_arr = array(),
                                         $gaiji_comment1_arr = array(),$gaiji_comment2_arr = array(),$color = array(0x00,0x00,0x00),$respect="",$groupNumber,$comment3,$comment3_arr){
  $comment_max_fontsize = "8";
  $comment_min_fontsize = "6";
  $name_max_fontsize = "11";

  $font = dirname(__FILE__)."/../fonts/msmincho.ttc";
  $name = $last_name." ".$first_name." ";

  $none_comment1 = (!$comment1 || $comment1 == "")?true:false;
  $none_comment2 = (!$comment2 || $comment2 == "")?true:false;
  $none_comment3 = (!$comment3 || $comment3 == "")?true:false;

  if(!$none_comment1 && !$none_comment2 && !$none_comment3){
    $comment1 = mb_substr($comment1,0,15,"utf8");
    $comment2 = mb_substr($comment2,0,15,"utf8");
    $comment3 = mb_substr($comment3,0,15,"utf8");
    gaiji_imagettftext_align_right($image,$comment_min_fontsize,0,$first_left,10,$col_t,$font,$comment3,$gaiji_comment3_arr);
    gaiji_imagettftext_align_right($image,$comment_min_fontsize,0,$first_left,20,$col_t,$font,$comment1,$gaiji_comment1_arr);
    gaiji_imagettftext_align_right($image,$comment_min_fontsize,0,$first_left,30,$col_t,$font,$comment2,$gaiji_comment2_arr);

  }else if($none_comment1 && !$none_comment2 && !$none_comment3){
    if(mb_strlen($comment3,"utf8") > 11){
      $comment2 = mb_substr($comment2,0,15,"utf8");
      $comment3 = mb_substr($comment3,0,15,"utf8");
      gaiji_imagettftext_align_right($image,$comment_min_fontsize,0,$first_left,20,$col_t,$font,$comment3,$gaiji_comment3_arr);
      gaiji_imagettftext_align_right($image,$comment_min_fontsize,0,$first_left,30,$col_t,$font,$comment2,$gaiji_comment2_arr);
    }else{
      $comment2 = mb_substr($comment2,0,15,"utf8");
      gaiji_imagettftext_align_right($image,$comment_max_fontsize,0,$first_left,20,$col_t,$font,$comment3,$gaiji_comment3_arr);
      gaiji_imagettftext_align_right($image,$comment_min_fontsize,0,$first_left,30,$col_t,$font,$comment2,$gaiji_comment2_arr);
    }
  }else if(!$none_comment1 && $none_comment2 && !$none_comment3){
    if(mb_strlen($comment3,"utf8") > 11){
      $comment1 = mb_substr($comment1,0,15,"utf8");
      $comment3 = mb_substr($comment3,0,15,"utf8");
      gaiji_imagettftext_align_right($image,$comment_min_fontsize,0,$first_left,20,$col_t,$font,$comment3,$gaiji_comment3_arr);
      gaiji_imagettftext_align_right($image,$comment_min_fontsize,0,$first_left,30,$col_t,$font,$comment1,$gaiji_comment1_arr);
    }else{
      $comment1 = mb_substr($comment1,0,15,"utf8");
      gaiji_imagettftext_align_right($image,$comment_max_fontsize,0,$first_left,20,$col_t,$font,$comment3,$gaiji_comment3_arr);
      gaiji_imagettftext_align_right($image,$comment_min_fontsize,0,$first_left,30,$col_t,$font,$comment1,$gaiji_comment1_arr);
    }
  }else if(!$none_comment1 && !$none_comment2 && $none_comment3){
    if(mb_strlen($comment1,"utf8") > 11){
      $comment1 = mb_substr($comment1,0,15,"utf8");
      $comment2 = mb_substr($comment2,0,15,"utf8");
      gaiji_imagettftext_align_right($image,$comment_min_fontsize,0,$first_left,20,$col_t,$font,$comment1,$gaiji_comment1_arr);
      gaiji_imagettftext_align_right($image,$comment_min_fontsize,0,$first_left,30,$col_t,$font,$comment2,$gaiji_comment2_arr);
    }else{
      $comment2 = mb_substr($comment2,0,15,"utf8");
      gaiji_imagettftext_align_right($image,$comment_max_fontsize,0,$first_left,20,$col_t,$font,$comment1,$gaiji_comment1_arr);
      gaiji_imagettftext_align_right($image,$comment_min_fontsize,0,$first_left,30,$col_t,$font,$comment2,$gaiji_comment2_arr);
    }
  }else if($none_comment1 && $none_comment2 && !$none_comment3){
    if(mb_strlen($comment3,"utf8") > 11){
      $comment3 = mb_substr($comment3,0,15,"utf8");
      gaiji_imagettftext_align_right($image,$comment_min_fontsize,0,$first_left,30,$col_t,$font,$comment3,$gaiji_comment3_arr);
    }else{
      gaiji_imagettftext_align_right($image,$comment_max_fontsize,0,$first_left,30,$col_t,$font,$comment3,$gaiji_comment3_arr);
    }    
  }else if($none_comment1 && !$none_comment2 && $none_comment3){
    if(mb_strlen($comment2,"utf8") > 11){
      $comment2 = mb_substr($comment2,0,15,"utf8");
      gaiji_imagettftext_align_right($image,$comment_min_fontsize,0,$first_left,30,$col_t,$font,$comment2,$gaiji_comment2_arr);
    }else{
      gaiji_imagettftext_align_right($image,$comment_max_fontsize,0,$first_left,30,$col_t,$font,$comment2,$gaiji_comment2_arr);
    }    
  }else if(!$none_comment1 && $none_comment2 && $none_comment3){
    if(mb_strlen($comment1,"utf8") > 11){
      $comment1 = mb_substr($comment1,0,15,"utf8");
      gaiji_imagettftext_align_right($image,$comment_min_fontsize,0,$first_left,30,$col_t,$font,$comment1,$gaiji_comment1_arr);
    }else{
      gaiji_imagettftext($image,$comment_max_fontsize,0,$first_left,30,$col_t,$font,$comment1,$gaiji_comment1_arr);
    }
  }  

  $gaiji_name_arr = array_merge((array)$gaiji_last_name_arr, (array)$gaiji_first_name_arr);
  if(mb_strlen($name,"utf-8")<9){
    $name_fontsize = 11;
  }else{
    $name_for_fontsize = $name."あ";
    if($groupNumber) $name_for_fontsize = $name_for_fontsize."あ";
    $name_fontsize = get_image_font_size($name_max_fontsize,$name_for_fontsize,$font,$width_px,
                                       $gaiji_name_arr);
  }

  $nowRight = $first_left;
  if($groupNumber){
    $nowRight = gaiji_imagettftext_align_right($image,$name_fontsize,0,$first_left,45,$col_t,$font," ".$groupNumber,array());
  }
  if(mb_strlen($respect,"utf-8") <= 1){
    $nowRight = gaiji_imagettftext_align_right($image,$name_fontsize,0,$nowRight,45,$col_t,$font,$respect,array());
  }else if(mb_strlen($respect,"utf-8")>1){
    $nowRight = gaiji_imagettftext_align_right($image,$name_fontsize,0,$nowRight,45,$col_t,$font,$respect,array(),50);
  }

  $gaiji_name_arr = array_merge((array)$gaiji_last_name_arr, (array)$gaiji_first_name_arr);
  
  gaiji_imagettftext_align_right($image,$name_fontsize,0,$nowRight,45,$col_t,$font,$name,$gaiji_name_arr);
}

function get_image_name_plate_data2($image,$width_px,$first_left,
                                   $last_name,$first_name,$comment1="",$comment2="",
                         $gaiji_last_name_arr = array(),$gaiji_first_name_arr = array(),
                                    $gaiji_comment1_arr = array(),$gaiji_comment2_arr = array(),$color = array(0x00,0x00,0x00),$respect="",$groupNumber){
  $comment_max_fontsize = "8";
  $comment_min_fontsize = "5";
  $name_max_fontsize = "11";

  $font = dirname(__FILE__)."/../fonts/msmincho.ttc";
  
  $name = $last_name." ".$first_name." ";
  
  if(!$comment1 || $comment1 == "") $comment1 = " ";
  if($comment1==" "){
    $comment2_fontsize = $comment_max_fontsize;
  }else if($comment2 != ""){
    $comment1_fontsize = get_image_font_size($comment_max_fontsize,$comment1,$font,$width_px,
                                           $gaiji_comment1_arr);
    if($comment1_fontsize == $comment_max_fontsize){
      gaiji_imagettftext($image,$comment_max_fontsize,0,$first_left,15,$col_t,$font,$comment1,$gaiji_comment1_arr);
    }else{
      if($comment1_fontsize>$comment_min_fontsize){
        gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,16,$col_t,$font,$comment1);
      }else{
        $str_num = mb_strlen($comment1,"utf8");
        $split_num = ceil($str_num/2);
        $second_num = $str_num-$split_num;
        $first_str = mb_substr($comment1,0,$split_num,"utf8");
        $second_str = mb_substr($comment1,$split_num,$second_num,"utf8");
        gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,8,$col_t,$font,$first_str);
        gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,16,$col_t,$font,$second_str);
      }
    }
    $comment2_fontsize = $comment_min_fontsize;    
  }else{  
    $comment2 = $comment1;
    $comment2_fontsize = $comment_max_fontsize;
  }
  
  $comment2_fontsize = get_image_font_size($comment2_fontsize,$comment2,$font,$width_px,
                                           $gaiji_comment1_arr);
  if($comment2_fontsize==$comment_max_fontsize){
    gaiji_imagettftext($image,$comment_max_fontsize,0,$first_left,26,$col_t,$font,$comment2,$gaiji_comment2_arr);
  }else{
    if($comment2_fontsize>=$comment_min_fontsize){
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,28,$col_t,$font,$comment2);
    }else{
      $str_num = mb_strlen($comment2,"utf8");
      $split_num = ceil($str_num/2);
      $second_num = $str_num-$split_num;
      $first_str = mb_substr($comment2,0,$split_num,"utf8");
      $second_str = mb_substr($comment2,$split_num,$second_num,"utf8");
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,22,$col_t,$font,$first_str);
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,30,$col_t,$font,$second_str);
    }
  }

  $gaiji_name_arr = array_merge((array)$gaiji_last_name_arr, (array)$gaiji_first_name_arr);
  $name_for_fontsize = $name.$respect;

  if(mb_strlen($name,"utf-8")<9){
    $name_fontsize = 11;
  }else{
    $name_fontsize = get_image_font_size($name_max_fontsize,$name_for_fontsize,$font,$width_px,
                                       $gaiji_name_arr);
  }
  
  $nowLeft = gaiji_imagettftext($image,$name_fontsize,0,$first_left,45,$col_t,$font,$name,$gaiji_name_arr);

  if(mb_strlen($respect,"utf-8") <= 1){
    gaiji_imagettftext($image,$name_fontsize,0,$nowLeft,45,$col_t,$font,$respect,array());
  }else if(mb_strlen($respect,"utf-8")>1){
    gaiji_imagettftext($image,$name_fontsize,0,$nowLeft,45,$col_t,$font,$respect,array(),50);
  }
}


function get_image_name_plate_full($last_name,$first_name,$comment1="",$comment2="",$memo1="",$memo2="",$memo3="",
                         $gaiji_last_name_arr = array(),$gaiji_first_name_arr = array(),
                                   $gaiji_comment1_arr = array(),$gaiji_comment2_arr = array(),$color = array(0x00,0x00,0x00),$respect="",$comment3="",$comment3_arr=array(),$width_px,$first_left,$group_left){

  $comment_max_fontsize = "8";
  $name_max_fontsize = "11";

  $font = dirname(__FILE__)."/../fonts/msmincho.ttc";

  $image = imagecreatetruecolor($width_px,50) or die("Cannot Initialize new GD image stream");
  $col_g = imagecolorallocate($image,0xff,0xff,0xff);
  $col_t = imagecolorallocate($image,0x00,0x00,0x00);
  imagefill($image,0,0,$col_g);

  get_image_name_plate_data5($image,$width_px,$first_left,
                            $last_name,$first_name,$memo3,$memo2,
                         $gaiji_last_name_arr,$gaiji_first_name_arr,
                             array(),array(),$color,$respect,$comment3,$comment3_arr,$memo1,$group_left);

  return $image;
}

function get_image_name_plate_full2($last_name,$first_name,$comment1="",$comment2="",$memo1="",$memo2="",$memo3="",
                         $gaiji_last_name_arr = array(),$gaiji_first_name_arr = array(),
                              $gaiji_comment1_arr = array(),$gaiji_comment2_arr = array(),$color = array(0x00,0x00,0x00),$respect=""){

  $comment_max_fontsize = "8";
  $name_max_fontsize = "11";

  $font = dirname(__FILE__)."/../fonts/msmincho.ttc";
  $width_px = "15";
  $memo_px = "30";

  $image = imagecreatetruecolor($width_px+$memo_px,45) or die("Cannot Initialize new GD image stream");
  $col_g = imagecolorallocate($image,0xff,0xff,0xff);
  $col_t = imagecolorallocate($image,0x00,0x00,0x00);
  imagefill($image,0,0,$col_g);
  
   $memo1_fontsize = get_image_font_size(12,$memo1,$font,$memo_px,
                                      array());
  gaiji_imagettftext($image,$memo1_fontsize,0,0,47,$col_t,$font,$memo1,array());

  $first_left = 0;
  get_image_name_plate_data2($image,$width_px,$first_left,
                            "  ".$last_name,$first_name,$memo3,$memo2,
                         $gaiji_last_name_arr,$gaiji_first_name_arr,
                              $gaiji_comment1_arr,$gaiji_comment2_arr,$color,$respect);
  
  return $image;
}

//肩書きcomment3、特記comment1、子供料理comment2、のみっつを表示させる。
//fontsize6 で23文字。fontsize9で16文字。
function get_image_name_plate_data4($image,$width_px,$first_left,
                                   $last_name,$first_name,$comment1="",$comment2="",
                         $gaiji_last_name_arr = array(),$gaiji_first_name_arr = array(),
                                    $gaiji_comment1_arr = array(),$gaiji_comment2_arr = array(),$color = array(0x00,0x00,0x00),$respect="",$comment3="",$gaiji_comment3_arr = array()){
  $comment_max_fontsize = "9";
  $comment_min_fontsize = "6";
  $name_max_fontsize = "11";

  $font = dirname(__FILE__)."/../fonts/msmincho.ttc";
  $name = $last_name." ".$first_name." ";
  
  $none_comment1 = (!$comment1 || $comment1 == "")?true:false;
  $none_comment2 = (!$comment2 || $comment2 == "")?true:false;
  $none_comment3 = (!$comment3 || $comment3 == "")?true:false;

  if(!$none_comment1 && !$none_comment2 && !$none_comment3){
    $comment1 = mb_substr($comment1,0,15,"utf8");
    $comment2 = mb_substr($comment2,0,15,"utf8");
    $comment3 = mb_substr($comment3,0,15,"utf8");
    gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,10,$col_t,$font,$comment3,$gaiji_comment3_arr);
    gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,20,$col_t,$font,$comment1,$gaiji_comment1_arr);
    gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,30,$col_t,$font,$comment2,$gaiji_comment2_arr);

  }else if($none_comment1 && !$none_comment2 && !$none_comment3){
    if(mb_strlen($comment3,"utf8") > 11){
      $comment2 = mb_substr($comment2,0,15,"utf8");
      $comment3 = mb_substr($comment3,0,15,"utf8");
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,20,$col_t,$font,$comment3,$gaiji_comment3_arr);
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,30,$col_t,$font,$comment2,$gaiji_comment2_arr);
    }else{
      $comment2 = mb_substr($comment2,0,15,"utf8");
      gaiji_imagettftext($image,$comment_max_fontsize,0,$first_left,20,$col_t,$font,$comment3,$gaiji_comment3_arr);
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,30,$col_t,$font,$comment2,$gaiji_comment2_arr);
    }
  }else if(!$none_comment1 && $none_comment2 && !$none_comment3){
    if(mb_strlen($comment3,"utf8") > 11){
      $comment1 = mb_substr($comment1,0,15,"utf8");
      $comment3 = mb_substr($comment3,0,15,"utf8");
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,20,$col_t,$font,$comment3,$gaiji_comment3_arr);
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,30,$col_t,$font,$comment1,$gaiji_comment1_arr);
    }else{
      $comment1 = mb_substr($comment1,0,15,"utf8");
      gaiji_imagettftext($image,$comment_max_fontsize,0,$first_left,20,$col_t,$font,$comment3,$gaiji_comment3_arr);
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,30,$col_t,$font,$comment1,$gaiji_comment1_arr);
    }
  }else if(!$none_comment1 && !$none_comment2 && $none_comment3){
    if(mb_strlen($comment1,"utf8") > 11){
      $comment1 = mb_substr($comment1,0,15,"utf8");
      $comment2 = mb_substr($comment2,0,15,"utf8");
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,20,$col_t,$font,$comment1,$gaiji_comment1_arr);
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,30,$col_t,$font,$comment2,$gaiji_comment2_arr);
    }else{
      $comment2 = mb_substr($comment2,0,15,"utf8");
      gaiji_imagettftext($image,$comment_max_fontsize,0,$first_left,20,$col_t,$font,$comment1,$gaiji_comment1_arr);
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,30,$col_t,$font,$comment2,$gaiji_comment2_arr);
    }
  }else if($none_comment1 && $none_comment2 && !$none_comment3){
    if(mb_strlen($comment3,"utf8") > 11){
      $comment3 = mb_substr($comment3,0,15,"utf8");
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,30,$col_t,$font,$comment3,$gaiji_comment3_arr);
    }else{
      gaiji_imagettftext($image,$comment_max_fontsize,0,$first_left,30,$col_t,$font,$comment3,$gaiji_comment3_arr);
    }    
  }else if($none_comment1 && !$none_comment2 && $none_comment3){
    if(mb_strlen($comment2,"utf8") > 11){
      $comment2 = mb_substr($comment2,0,15,"utf8");
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,30,$col_t,$font,$comment2,$gaiji_comment2_arr);
    }else{
      gaiji_imagettftext($image,$comment_max_fontsize,0,$first_left,30,$col_t,$font,$comment2,$gaiji_comment2_arr);
    }    
  }else if(!$none_comment1 && $none_comment2 && $none_comment3){
    if(mb_strlen($comment1,"utf8") > 11){
      $comment1 = mb_substr($comment1,0,15,"utf8");
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,30,$col_t,$font,$comment1,$gaiji_comment1_arr);
    }else{
      gaiji_imagettftext($image,$comment_max_fontsize,0,$first_left,30,$col_t,$font,$comment1,$gaiji_comment1_arr);
    }
  }

  $gaiji_name_arr = array_merge((array)$gaiji_last_name_arr, (array)$gaiji_first_name_arr);
  $name_for_fontsize = $name.$respect;
  if(mb_strlen($name,"utf-8")<9){
    $name_fontsize = 11;
  }else{
    $name_fontsize = get_image_font_size($name_max_fontsize,$name_for_fontsize,$font,$width_px,
                                       $gaiji_name_arr);
  }
  $nowLeft = gaiji_imagettftext($image,$name_fontsize,0,$first_left,45,$col_t,$font,$name,$gaiji_name_arr);

  if(mb_strlen($respect,"utf-8") <= 1){
    gaiji_imagettftext($image,$name_fontsize,0,$nowLeft,45,$col_t,$font,$respect,array());
  }else if(mb_strlen($respect,"utf-8")>1){
    gaiji_imagettftext($image,$name_fontsize,0,$nowLeft,45,$col_t,$font,$respect,array(),50);
  }
  
}

function get_image_name_plate_data5($image,$width_px,$first_left,
                                   $last_name,$first_name,$comment1="",$comment2="",
                         $gaiji_last_name_arr = array(),$gaiji_first_name_arr = array(),
                                    $gaiji_comment1_arr = array(),$gaiji_comment2_arr = array(),$color = array(0x00,0x00,0x00),$respect="",$comment3="",$gaiji_comment3_arr = array(),$group = "",$group_left){
  $comment_max_fontsize = "6";
  $comment_min_fontsize = "6";
  $name_max_fontsize = "11";
  
  $font = dirname(__FILE__)."/../fonts/msmincho.ttc";
  $name = $last_name." ".$first_name." ";
  $comment_max_size = 16;
  
  $none_comment1 = (!$comment1 || $comment1 == "" || $comment1 == ' ')?true:false;
  $none_comment2 = (!$comment2 || $comment2 == "" || $comment2 == ' ')?true:false;
  $none_comment3 = (!$comment3 || $comment3 == "" || $comment3 == ' ')?true:false;
  
  if(!$none_comment1 && !$none_comment2 && !$none_comment3){
    $comment1 = mb_substr($comment1,0,$comment_max_size,"utf8");
    $comment2 = mb_substr($comment2,0,$comment_max_size,"utf8");
    $comment3 = mb_substr($comment3,0,$comment_max_size,"utf8");

    gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,10,$col_t,$font,$comment1,array());
    gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,20,$col_t,$font,$comment2,array());
    gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,30,$col_t,$font,$comment3,$gaiji_comment3_arr);
  }else if($none_comment1 && !$none_comment2 && !$none_comment3){
    if(mb_strlen($comment2,"utf8") > $comment_max_size){
      $comment2_2 = mb_substr($comment2,$comment_max_size,$comment_max_size,"utf8");
      $comment2 = mb_substr($comment2,0,$comment_max_size,"utf8");
      $comment3 = mb_substr($comment3,0,$comment_max_size,"utf8");
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,10,$col_t,$font,$comment2,array());
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,20,$col_t,$font,$comment2_2,array());
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,30,$col_t,$font,$comment3,$gaiji_comment3_arr);
    }else if(mb_strlen($comment3,"utf8") > $comment_max_size){
      $comment2 = mb_substr($comment2,0,$comment_max_size,"utf8");
      list($comment3_2,$comment3,$gaiji_comment3_arr2,$gaiji_comment3_arr) = get_gaiji_image_split2($comment3,$gaiji_comment3_arr,$comment_max_size);
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,10,$col_t,$font,$comment2,array());
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,20,$col_t,$font,$comment3,$gaiji_comment3_arr);
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,30,$col_t,$font,$comment3_2,$gaiji_comment3_arr2);
    }else{
      $comment2 = mb_substr($comment2,0,$comment_max_size,"utf8");
      $comment3 = mb_substr($comment3,0,$comment_max_size,"utf8");
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,20,$col_t,$font,$comment2,array());
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,30,$col_t,$font,$comment3,$gaiji_comment3_arr);
      
    }
  }else if(!$none_comment1 && $none_comment2 && !$none_comment3){
    if(mb_strlen($comment1,"utf8") > $comment_max_size){
      $comment1_2 = mb_substr($comment1,$comment_max_size,$comment_max_size,"utf8");
      $comment1 = mb_substr($comment1,0,$comment_max_size,"utf8");
      $comment3 = mb_substr($comment3,0,$comment_max_size,"utf8");
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,10,$col_t,$font,$comment1,array());
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,20,$col_t,$font,$comment1_2,array());
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,30,$col_t,$font,$comment3,$gaiji_comment3_arr);
    }else if(mb_strlen($comment3,"utf8") > $comment_max_size){
      $comment1 = mb_substr($comment1,0,$comment_max_size,"utf8");
      list($comment3_2,$comment3,$gaiji_comment3_arr2,$gaiji_comment3_arr) = get_gaiji_image_split2($comment3,$gaiji_comment3_arr,$comment_max_size);
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,10,$col_t,$font,$comment1,array());
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,20,$col_t,$font,$comment3,$gaiji_comment3_arr);
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,30,$col_t,$font,$comment3_2,$gaiji_comment3_arr2);
    }else{
      $comment1 = mb_substr($comment1,0,$comment_max_size,"utf8");
      $comment3 = mb_substr($comment3,0,$comment_max_size,"utf8");
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,20,$col_t,$font,$comment1,array());
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,30,$col_t,$font,$comment3,$gaiji_comment3_arr);
    }
  }else if(!$none_comment1 && !$none_comment2 && $none_comment3){
    if(mb_strlen($comment1,"utf8") > $comment_max_size){
      $comment1_2 = mb_substr($comment1,$comment_max_size,$comment_max_size,"utf8");
      $comment1 = mb_substr($comment1,0,$comment_max_size,"utf8");
      $comment2 = mb_substr($comment2,0,$comment_max_size,"utf8");
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,10,$col_t,$font,$comment1,array());
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,20,$col_t,$font,$comment1_2,array());
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,30,$col_t,$font,$comment2,array());
    }else if(mb_strlen($comment2,"utf8") > $comment_max_size){
      $comment1 = mb_substr($comment1,0,$comment_max_size,"utf8");
      $comment2_2 = mb_substr($comment2_2,0,$comment_max_size,"utf8");
      $comment2 = mb_substr($comment2,$comment_max_size,$comment_max_size,"utf8");
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,10,$col_t,$font,$comment1,array());
      //外字が含まれているか含まれている場合はそれをarrayから外す。
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,20,$col_t,$font,$comment2,array());
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,30,$col_t,$font,$comment2_2,array());
    }else{
      $comment1 = mb_substr($comment1,0,$comment_max_size,"utf8");
      $comment2 = mb_substr($comment2,0,$comment_max_size,"utf8");
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,20,$col_t,$font,$comment1,array());
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,30,$col_t,$font,$comment2,array());
    }
  }else if($none_comment1 && $none_comment2 && !$none_comment3){
    if(mb_strlen($comment3,"utf8") > $comment_max_size*2){
      list($comment_3,$comment_2,$comment_1,$gaiji_comment3_arr3,$gaiji_comment3_arr2,$gaiji_comment3_arr) = 
        get_gaiji_image_split3($comment3,$gaiji_comment3_arr,$comment_max_size);
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,10,$col_t,$font,$comment_1,$gaiji_comment3_arr);
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,20,$col_t,$font,$comment_2,$gaiji_comment3_arr2);
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,30,$col_t,$font,$comment_3,$gaiji_comment3_arr3);
    }else if(mb_strlen($comment3,"utf8") > $comment_max_size){
      list($comment_2,$comment_1,$gaiji_comment3_arr2,$gaiji_comment3_arr) = get_gaiji_image_split2($comment3,$gaiji_comment3_arr,$comment_max_size);
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,20,$col_t,$font,$comment_1,$gaiji_comment3_arr);
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,30,$col_t,$font,$comment_2,$gaiji_comment3_arr2);
    }else{
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,30,$col_t,$font,$comment3,$gaiji_comment3_arr);
    }
  }else if($none_comment1 && !$none_comment2 && $none_comment3){
    if(mb_strlen($comment2,"utf8") > $comment_max_size*2){
      $comment_3 = mb_substr($comment2,$comment_max_size*2,$comment_max_size,"utf8");
      $comment_2 = mb_substr($comment2,$comment_max_size,$comment_max_size,"utf8");
      $comment_1 = mb_substr($comment2,0,$comment_max_size,"utf8");
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,10,$col_t,$font,$comment_1,array());
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,20,$col_t,$font,$comment_2,array());
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,30,$col_t,$font,$comment_3,array());
    }else if(mb_strlen($comment2,"utf8") > $comment_max_size){
      $comment_2 = mb_substr($comment2,$comment_max_size,$comment_max_size,"utf8");
      $comment_1 = mb_substr($comment2,0,$comment_max_size,"utf8");

      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,20,$col_t,$font,$comment_1,array());
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,30,$col_t,$font,$comment_2,array());
    }else{
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,30,$col_t,$font,$comment2,array());
    }
  }else if(!$none_comment1 && $none_comment2 && $none_comment3){
    if(mb_strlen($comment1,"utf8") > $comment_max_size*2){
      $comment_3 = mb_substr($comment1,$comment_max_size*2,$comment_max_size,"utf8");
      $comment_2 = mb_substr($comment1,$comment_max_size,$comment_max_size,"utf8");
      $comment_1 = mb_substr($comment1,0,$comment_max_size,"utf8");
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,10,$col_t,$font,$comment_1,array());
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,20,$col_t,$font,$comment_2,array());
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,30,$col_t,$font,$comment_3,array());
    }else if(mb_strlen($comment1,"utf8") > $comment_max_size){
      $comment_2 = mb_substr($comment1,$comment_max_size,$comment_max_size,"utf8");
      $comment_1 = mb_substr($comment1,0,$comment_max_size,"utf8");
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,20,$col_t,$font,$comment_1,array());
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,30,$col_t,$font,$comment_2,array());
    }else{
      gaiji_imagettftext($image,$comment_min_fontsize,0,$first_left,30,$col_t,$font,$comment1,array());
    }
  }

  $gaiji_name_arr = array_merge((array)$gaiji_last_name_arr, (array)$gaiji_first_name_arr);
  $name_for_fontsize = $name.$respect;
  if(mb_strlen($name,"utf-8")<9){
    $name_fontsize = 14;
  }else{
    $name_fontsize = get_image_font_size($name_max_fontsize,$name_for_fontsize,$font,$width_px,
                                       $gaiji_name_arr);
  }
  $group = mb_convert_kana($group,"a","utf8");
  $nowLeft = gaiji_imagettftext($image,14,0,$group_left,50,$col_t,$font,$group);
  $nowLeft = gaiji_imagettftext($image,$name_fontsize,0,$first_left,50,$col_t,$font,$name,$gaiji_name_arr);

  if(mb_strlen($respect,"utf-8") <= 1){
    gaiji_imagettftext($image,$name_fontsize,0,$nowLeft,50,$col_t,$font,$respect,array());
  }else if(mb_strlen($respect,"utf-8")>1){
    gaiji_imagettftext($image,$name_fontsize,0,$nowLeft,50,$col_t,$font,$respect,array(),50);
  }
  
}

//list($comment_2,$comment_1,$comment_2_gaiji_arr,$comment_1_gaiji_arr) = 
function get_gaiji_image_split2($comment,$comment_arr,$comment_max_size){
  $comment_2 = mb_substr($comment,$comment_max_size,$comment_max_size,"utf8");
  $comment_1 = mb_substr($comment,0,$comment_max_size,"utf8");
  $comment_2_gaiji_num = mb_substr_count($comment_2,"＊",'utf8');
  $comment_1_gaiji_num = mb_substr_count($comment_1,"＊",'utf8');
  $comment_2_gaiji_arr = array_slice($comment_arr,$comment_1_gaiji_num,$comment_2_gaiji_num);
  $comment_1_gaiji_arr = array_slice($comment_arr,0,$comment_1_gaiji_num);
  return array($comment_2,$comment_1,$comment_2_gaiji_arr,$comment_1_gaiji_arr);
}

function get_gaiji_image_split3($comment,$comment_arr,$comment_max_size){
  $comment_3 = mb_substr($comment,$comment_max_size*2,$comment_max_size,"utf8");
  $comment_2 = mb_substr($comment,$comment_max_size,$comment_max_size,"utf8");
  $comment_1 = mb_substr($comment,0,$comment_max_size,"utf8");
  $comment_3_gaiji_num = mb_substr_count($comment_3,"＊",'utf8');
  $comment_2_gaiji_num = mb_substr_count($comment_2,"＊",'utf8');
  $comment_1_gaiji_num = mb_substr_count($comment_1,"＊",'utf8');
  $comment_3_gaiji_arr = array_slice($comment_arr,$comment_1_gaiji_num+$comment_2_gaiji_num,$comment_3_gaiji_num);
  $comment_2_gaiji_arr = array_slice($comment_arr,$comment_1_gaiji_num,$comment_2_gaiji_num);
  $comment_1_gaiji_arr = array_slice($comment_arr,0,$comment_1_gaiji_num);
  return array($comment_3,$comment_2,$comment_1,$comment_3_gaiji_arr,$comment_2_gaiji_arr,$comment_1_gaiji_arr);
}



function get_image_text($text,$gaiji_arr = array(),$fontsize=15,$max_width=150,$col_t_arr = array(0x00,0x00,0x00)){

  if(!$text || $text == "") $text = " ";
  $font = dirname(__FILE__)."/../fonts/msmincho.ttc";
  $height = get_image_text_height($fontsize,$font,$text);
  $width = get_image_text_width($fontsize,$text,$font,$gaiji_arr);

  if($width>$max_width){
    $fontsize = get_image_font_size($fontsize,$text,$font,$max_width,$gaiji_arr);
    $width = $max_width;
  }

  //一時的に微調整
  $image = imagecreatetruecolor($width+2,$height) or die("Cannot Initialize new GD image stream");
  $col_g = imagecolorallocate($image,0xff,0xff,0xff);
  $col_t = imagecolorallocate($image,$col_t_arr[0],$col_t_arr[1],$col_t_arr[2]);
  imagefill($image,0,0,$col_g);
  
  gaiji_imagettftext($image,$fontsize,0,0,$height*0.9,$col_t,$font,$text,$gaiji_arr);
  return $image;
}

/* 
   
 */
function set_guest_gaiji_position($user_id,$guest_id,$str,$target_type,$gaiji_file_name_arr=array(),$gaiji_code_arr=array(),$gaiji_str="＊"){
  $len = mb_strlen($str,'utf-8');
  if($len==0) return;
  $k = 0;
  for($i=0;$i<$len;++$i){
    $charcode = (int)hexdec(bin2hex(mb_substr($str,$i,1,"utf-8")));
    if(mb_substr($str,$i,1,"utf-8")==$gaiji_str){
      if(!$gaiji_code_arr[$k]) continue;
      //$gaiji_detail_sql = "insert into spssp_gaizi_detail_for_guest(gu_id,guest_id,gu_trgt_type,gu_char_position,gu_char_img,gu_char_setcode,gu_sjis_code)  values(" .$user_id. "," .$guest_id.  "," .$target_type. "," .$i. ",'".$gaiji_file_name_arr[$k] ."','" .$gaiji_code_arr[$k]."'," .$gaiji_sjis_code_arr[$k]. ");";
      $gaiji_detail_sql = "insert into spssp_gaizi_detail_for_guest(gu_id,guest_id,gu_trgt_type,gu_char_position,gu_char_img,gu_char_setcode)  values(" .$user_id. "," .$guest_id.  "," .$target_type. "," .$i. ",'".$gaiji_file_name_arr[$k] ."','" .$gaiji_code_arr[$k]."');";
        $test = mysql_query($gaiji_detail_sql);
        ++$k;
    }
  }
}

function set_user_gaiji_position($user_id,$str,$target_type,$gaiji_file_name_arr=array(),$gaiji_code_arr=array(),$gaiji_sjis_code_arr=array(),$gaiji_str="＊"){
  $len = mb_strlen($str,'utf-8');
  if($len==0) return;
  $k = 0;
  for($i=0;$i<$len;++$i){
    $charcode = (int)hexdec(bin2hex(mb_substr($str,$i,1,"utf-8")));
    if(mb_substr($str,$i,1,"utf-8")==$gaiji_str){
      $gaiji_detail_sql = "insert into spssp_gaizi_detail_for_user(gu_id,gu_trgt_type,gu_char_position,gu_char_img,gu_char_setcode)  values(" .$user_id. "," .$target_type. "," .$i. ",'".$gaiji_file_name_arr[$k]."','" .$gaiji_code_arr[$k]. "');";
          $test = mysql_query($gaiji_detail_sql);
          ++$k;
          mysql_error();
    }
  }
}

function make_user_images($user_id,$man_last_name,$man_first_name,$woman_last_name,$woman_first_name,$man_last_name_img,$man_first_name_img,$woman_last_name_img,$woman_first_name_img){
  $user_folder = Core_Image::get_user_image_dir($user_id);
  @mkdir($user_folder."/thumb1");
  @mkdir($user_folder."/thumb2");

  $man_lastname_gaiji_pathArray = getGaijiPathArray($man_last_name_img);
  $man_firstname_gaiji_pathArray = getGaijiPathArray($man_first_name_img);
  $woman_lastname_gaiji_pathArray = getGaijiPathArray($woman_last_name_img);
  $woman_firstname_gaiji_pathArray = getGaijiPathArray($woman_first_name_img);

  make_text_save($man_last_name,$man_lastname_gaiji_pathArray,$user_folder."man_lastname.png");
  make_text_save($man_last_name."様",$man_lastname_gaiji_pathArray,$user_folder."man_lastname_respect.png");
  make_text_save($man_first_name,$man_firstname_gaiji_pathArray,$user_folder."man_firstname.png");
  $man_fullname_gaiji_pathArray = array_merge($man_lastname_gaiji_pathArray,$man_firstname_gaiji_pathArray);
  make_text_save($man_last_name." ".$man_first_name." 様",$man_fullname_gaiji_pathArray,$user_folder."man_fullname.png");
  make_text_save($man_last_name." ".$man_first_name,$man_fullname_gaiji_pathArray,$user_folder."man_fullname_only.png");
  
  make_text_save($man_last_name,$man_lastname_gaiji_pathArray,$user_folder."thumb1/man_lastname.png",11);
  make_text_save($man_last_name."様",$man_lastname_gaiji_pathArray,$user_folder."thumb1/man_lastname_respect.png",11);
  make_text_save($man_last_name." ".$man_first_name." 様",$man_fullname_gaiji_pathArray,$user_folder."thumb1/man_fullname.png",11);
  make_text_save($man_last_name." ".$man_first_name,$man_fullname_gaiji_pathArray,$user_folder."thumb1/man_fullname_only.png",11);

  make_text_save($man_last_name,$man_lastname_gaiji_pathArray,$user_folder."thumb2/man_lastname.png",9,100);
  make_text_save($man_last_name."様",$man_lastname_gaiji_pathArray,$user_folder."thumb2/man_lastname_respect.png",9,100);
  make_text_save($man_last_name." ".$man_first_name." 様",$man_fullname_gaiji_pathArray,$user_folder."thumb2/man_fullname.png",9,100);
  make_text_save($man_last_name." ".$man_first_name,$man_fullname_gaiji_pathArray,$user_folder."thumb2/man_fullname_only.png",9,100);

  make_text_save($woman_last_name,$woman_lastname_gaiji_pathArray,$user_folder."woman_lastname.png");
  make_text_save($woman_last_name."様",$woman_lastname_gaiji_pathArray,$user_folder."woman_lastname_respect.png");
  make_text_save($woman_first_name,$woman_firstname_gaiji_pathArray,$user_folder."woman_firstname.png");
  $woman_fullname_gaiji_pathArray = array_merge($woman_lastname_gaiji_pathArray,$woman_firstname_gaiji_pathArray);
  make_text_save($woman_last_name." ".$woman_first_name." 様",$woman_fullname_gaiji_pathArray,$user_folder."woman_fullname.png");
  make_text_save($woman_last_name." ".$woman_first_name,$woman_fullname_gaiji_pathArray,$user_folder."woman_fullname_only.png");

  make_text_save($woman_last_name,$woman_lastname_gaiji_pathArray,$user_folder."thumb1/woman_lastname.png",11);
  make_text_save($woman_last_name."様",$woman_lastname_gaiji_pathArray,$user_folder."thumb1/woman_lastname_respect.png",11);
  make_text_save($woman_last_name." ".$woman_first_name." 様",$woman_fullname_gaiji_pathArray,$user_folder."thumb1/woman_fullname.png",11);
  make_text_save($woman_last_name." ".$woman_first_name,$woman_fullname_gaiji_pathArray,$user_folder."thumb1/woman_fullname_only.png",11);

  make_text_save($woman_last_name,$woman_lastname_gaiji_pathArray,$user_folder."thumb2/woman_lastname.png",9,100);
  make_text_save($woman_last_name."様",$woman_lastname_gaiji_pathArray,$user_folder."thumb2/woman_lastname_respect.png",9,100);
  make_text_save($woman_last_name." ".$woman_first_name." 様",$woman_fullname_gaiji_pathArray,$user_folder."thumb2/woman_fullname.png",9,100);
  make_text_save($woman_last_name." ".$woman_first_name,$woman_fullname_gaiji_pathArray,$user_folder."thumb2/woman_fullname_only.png",9,100);

  $guest_page_gaiji_pathArray = array_merge($man_lastname_gaiji_pathArray,$woman_lastname_gaiji_pathArray);

  make_text_save($man_last_name."・".$woman_last_name."様専用ページ",$guest_page_gaiji_pathArray,$user_folder."guest_page.png",13,250);
}

function make_guest_images($user_id,$guest_id,$last_name,$first_name,$comment1,$comment2,$respect,$last_name_gaiji_img,$first_name_gaiji_img,$comment1_gaiji_img,$comment2_gaiji_img){
  $user_folder = Core_Image::get_guest_image_dir($user_id,$guest_id);
  $colorArray = array(0x00,0x00,0x00);
  @mkdir($user_folder."thumb1");
  @mkdir($user_folder."thumb2");
  
  $lastname_gaiji_pathArray = getGaijiPathArray($last_name_gaiji_img);
  $firstname_gaiji_pathArray = getGaijiPathArray($first_name_gaiji_img);
  $comment1_gaiji_pathArray = getGaijiPathArray($comment1_gaiji_img);
  $comment2_gaiji_pathArray = getGaijiPathArray($comment2_gaiji_img);

  make_text_save($last_name,$lastname_gaiji_pathArray,$user_folder."last_name.png",15,150,$colorArray);
  make_text_save($last_name.$respect,$lastname_gaiji_pathArray,$user_folder."last_name_respect.png",15,150,$colorArray);
  make_text_save($first_name,$firstname_gaiji_pathArray,$user_folder."first_name.png",15,150,$colorArray);
  make_text_save($comment1,$comment1_gaiji_pathArray,$user_folder."comment1.png",15,150,$colorArray);
  make_text_save($comment2,$comment2_gaiji_pathArray,$user_folder."comment2.png",15,150,$colorArray);
  $comment_gaiji_pathArray = array_merge($comment1_gaiji_pathArray,$comment2_gaiji_pathArray);
  $fullname_gaiji_pathArray = array_merge($lastname_gaiji_pathArray,$firstname_gaiji_pathArray);
  make_text_save($comment1.$comment2,$comment_gaiji_pathArray,$user_folder."full_comment.png",15,150,$colorArray);
  make_text_save($last_name." ".$first_name." ".$respect,$comment_gaiji_pathArray,$user_folder."guest_fullname.png",15,150,$colorArray);
  make_text_save($respect,array(),$user_folder."guest_respect.png",15,150,$colorArray);
  make_text_save($last_name." ".$first_name,$comment_gaiji_pathArray,$user_folder."guest_fullname_only.png",15,150,$colorArray);

  make_text_save($last_name.$respect,$lastname_gaiji_pathArray,$user_folder."thumb1/last_name_respect.png",11,150,$colorArray);  
  make_text_save($comment1,$comment1_gaiji_pathArray,$user_folder."thumb1/comment1.png",11,150,$colorArray);
  make_text_save($comment2,$comment2_gaiji_pathArray,$user_folder."thumb1/comment2.png",11,150,$colorArray);
  make_text_save($comment1.$comment2,$comment_gaiji_pathArray,$user_folder."thumb1/full_comment.png",11,150,$colorArray);
  make_text_save($last_name." ".$first_name." ".$respect,$fullname_gaiji_pathArray,$user_folder."thumb1/guest_fullname.png",11,150,$colorArray);
  make_text_save($last_name." ".$first_name,$fullname_gaiji_pathArray,$user_folder."thumb1/guest_fullname_only.png",11,150,$colorArray);
  
  make_text_save($last_name.$respect,$lastname_gaiji_pathArray,$user_folder."thumb2/last_name_respect.png",9,100,$colorArray);  
  make_text_save($comment1,$comment1_gaiji_pathArray,$user_folder."thumb2/comment1.png",9,100,$colorArray);
  make_text_save($comment2,$comment2_gaiji_pathArray,$user_folder."thumb2/comment2.png",9,100,$colorArray);
  make_text_save($comment1.$comment2,$comment_gaiji_pathArray,$user_folder."thumb2/full_comment.png",9,100,$colorArray);
  make_text_save($last_name." ".$first_name." ".$respect,$fullname_gaiji_pathArray,$user_folder."thumb2/guest_fullname.png",9,100,$colorArray);
  make_text_save($last_name." ".$first_name,$fullname_gaiji_pathArray,$user_folder."thumb2/guest_fullname_only.png",9,100,$colorArray);
  
  //pdf用の画像を生成。
  $savefile = $user_folder."namecard.png";
  make_name_plate_save($last_name,$first_name,$comment1,$comment2,
                       $lastname_gaiji_pathArray,$firstname_gaiji_pathArray,
                       $comment1_gaiji_pathArray,$comment2_gaiji_pathArray,$savefile,$colorArray,$respect);

  //引き出物を含んだ画像を作成
  $dataClass = new DataClass();
  $gift_name = $dataClass->get_gift_name($user_id,$guest_id);
  $menu_name = $dataClass->get_menu_name($user_id,$guest_id);
  $guest_detail = $dataClass->get_guest_detail($user_id,$guest_id);
  $memo = $guest_detail["memo"];
  $savefile = $user_folder."namecard_memo.png";
  $savefile2 = $user_folder."namecard_memo2.png";
  
  make_name_plate_full_save($last_name,$first_name,$comment1,$comment2,$gift_name,$menu_name,$memo,
                       $lastname_gaiji_pathArray,$firstname_gaiji_pathArray,
                       $comment1_gaiji_pathArray,$comment2_gaiji_pathArray,$savefile,$savefile2,$colorArray,$respect,$comment1." ".$comment2,$comment_gaiji_pathArray);
}

function get_image_db_directory(){
  $result_image_db_dir = "";
  $query = "select gc_sval_0 as val from spssp_gaizi_cfg where gc_cfg_type = 3 and gc_cscode = ".$hotel_id;
  $result = mysql_query($query );
  $num = mysql_num_rows($result);
  if($num>0){
    while($fetchedRow = mysql_fetch_assoc($result)){
      $result_image_db_dir = (string)$fetchedRow["val"];
    }
  }
  mysql_free_result($result);
  $result_image_db_dir = explode("/../",$result_image_db_dir);

  return dirname(__FILE__)."/".$result_image_db_dir[0];
}

//pdf用の変数及び関数の準備
function getGaijiPathArray($gaiji_img){
  $pathArray = array();
  for($i=0;$i<count($gaiji_img);++$i){
    array_push($pathArray,dirname(__FILE__)."/../../gaiji-image/img_ans/".$gaiji_img[$i]);
  }
  return $pathArray;
}

/**__**
*title
JISの半角および、第１、２水準文字であることのチェック。
*arguments
$target    検査する文字列
$enc    使用しているエンコード
*return
*memo
return    ""：OK、以外:NG文字たち
*/

function check_sjis_1($target, $check_line,$enc='utf-8'){
  $rtn = "";
  // UTF-8にしてから処理する。
  $target2 = mb_convert_encoding($target, 'utf-8', $enc);
  for($idx = 0; $idx < mb_strlen($target2, 'utf-8'); $idx++){
    $str0 = mb_substr($target2, $idx, 1, 'utf-8');

    // 1文字をUTF-16にする。
    $utf_16 = mb_convert_encoding($str0,"utf-16", 'utf-8');
    $change = false;
    for($i=0;$i<count($check_line);++$i){
      //改行が含まれるので削除
      $check_line[$i] = preg_replace("/\r|\n/","",$check_line[$i]);
      $check_line[$i] = strtolower($check_line[$i]);
      if($check_line[$i] == bin2hex($utf_16)){
        $rtn .= $str0;
        $change = true;
        break;
      }
    }
    if($change) continue;
    // 1文字をSJISにする。
    $str = mb_convert_encoding($str0, "sjis-win", 'utf-8');
    //utf-8からsjis-winに変換できない漢字
    if($str0!="?"&&$str=="?") $rtn .= $str0;
    if ((strlen(bin2hex($str)) / 2) == 1) { // 1バイト文字                
      $c = ord($str{0});
    } else {
      $c = ord($str{0}); // 先頭1バイト
      $c2 = ord($str{1}); // 2バイト目
      $c3 = $c * 0x100 + $c2; // 2バイト分の数値にする。

      if ((($c3 >= 0x8140) && ($c3 <= 0x853D)) || // 2バイト文字
          (($c3 >= 0x889F) && ($c3 <= 0x988F)) // 第一水準
           || (($c3 >= 0x9890) && ($c3 <= 0x9FFF)) // 第二水準
           ||(($c3 >= 0xE040) && ($c3 <= 0xEAFF))//  第二水準
          ) { 
      } else {
        $rtn .= $str0;
      }
    }
  }
  return $rtn;
}

function check_sjis($str){
 
  $file = file(dirname(__file__)."/ms_gaiji_sjis1.csv");
  $sjis = check_sjis_1($str,$file);
  $file_num = mb_strlen($sjis,"utf8");
  for($i=0;$i<$file_num;++$i){
    $char = mb_substr($sjis,$i,1,"utf8");
    $str = str_replace($char,"●",$str);
  }
  return $str;
}
function set_arr($arr)
{
  if(is_array($arr)) return $arr;
  return array();
}
function make_pdf_guest_info($user_id,$man_last_name,$man_lastname_gaiji_pathArray,$woman_last_name,$woman_lastname_gaiji_pathArray,$man_guest_sum,$woman_guest_sum,$guest_sum,$sum){
  $user_folder = Core_Image::get_user_image_dir($user_id);
  $colorArray = array(0x00,0x00,0x00);
  $gaiji_arr = array_merge((array)$man_lastname_gaiji_pathArray, (array)$woman_lastname_gaiji_pathArray);
  make_text_save("新郎様側:".$man_last_name."家  列席者数：".$man_guest_sum."名様  新婦様側:".
                 $woman_last_name."家  列席者数：".$woman_guest_sum."名様 列席者数合計：".
                 $guest_sum."名様  合計人数：".$sum."名様  "
                 ,$gaiji_arr,$user_folder."pdf_hikidemono_head.png",14,500);
}
