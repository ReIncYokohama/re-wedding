<?php
include_once(dirname(__FILE__)."/gaiji.image.util.php");
include_once(dirname(__FILE__)."/../admin/inc/class_data.dbo.php");

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

/*name_plate
########################
comment1             memo1
comment2             memo2
last_name first_name memo3
########################
*/
function make_name_plate_full_save($last_name,$first_name,$comment1="",$comment2="",$memo1,$memo2,$memo3,
                         $gaiji_last_name_arr = array(),$gaiji_first_name_arr = array(),
                                   $gaiji_comment1_arr = array(),$gaiji_comment2_arr = array(),$file="file.png",$file2="file.png",$color = array(0x00,0x00,0x00),$respect = ""){
  //右側にコメント
  $image = get_image_name_plate_full($last_name,$first_name,$comment1,$comment2,$memo1,$memo2,$memo3,
                                $gaiji_last_name_arr,$gaiji_first_name_arr,$gaiji_comment1_arr,$gaiji_comment2_arr,$color,$respect);
  imagefilter($image, IMG_FILTER_COLORIZE, $color[0], $color[1], $color[2]);
  imagepng($image,$file);
  imagedestroy($image);

  //左側にコメント
  $image = get_image_name_plate_full2($last_name,$first_name,$comment1,$comment2,$memo1,$memo2,$memo3,
                                $gaiji_last_name_arr,$gaiji_first_name_arr,$gaiji_comment1_arr,$gaiji_comment2_arr,$color,$respect);
  imagefilter($image, IMG_FILTER_COLORIZE, $color[0], $color[1], $color[2]);
  imagepng($image,$file2);
  imagedestroy($image);
}

function make_name_plate_full_view($last_name,$first_name,$comment1="",$comment2="",$memo1,$memo2,$memo3,
                         $gaiji_last_name_arr = array(),$gaiji_first_name_arr = array(),
                              $gaiji_comment1_arr = array(),$gaiji_comment2_arr = array(),$color = array(0x00,0x00,0x00),$respect = ""){
  $image = get_image_name_plate_full($last_name,$first_name,$comment1,$comment2,$memo1,$memo2,$memo3,
                                $gaiji_last_name_arr,$gaiji_first_name_arr,$gaiji_comment1_arr,$gaiji_comment2_arr,$color,$respect);
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
  $name_max_fontsize = "12";

  $font = dirname(__FILE__)."/../fonts/msmincho.ttc";
  $width_px = "150";

  $image = imagecreatetruecolor($width_px,45) or die("Cannot Initialize new GD image stream");
  $col_g = imagecolorallocate($image,0xff,0xff,0xff);
  $col_t = imagecolorallocate($image,0x00,0x00,0x00);
  imagefill($image,0,0,$col_g);
  

  get_image_name_plate_data($image,$width_px,$first_left,
                            $last_name,$first_name,$comment1,$comment2,
                         $gaiji_last_name_arr,$gaiji_first_name_arr,
                              $gaiji_comment1_arr,$gaiji_comment2_arr,$color,$respect);
  return $image;
}

function get_image_name_plate_data($image,$width_px,$first_left,
                                   $last_name,$first_name,$comment1="",$comment2="",
                         $gaiji_last_name_arr = array(),$gaiji_first_name_arr = array(),
                                   $gaiji_comment1_arr = array(),$gaiji_comment2_arr = array(),$color = array(0x00,0x00,0x00),$respect=""){
  $comment_max_fontsize = "8";
  $name_max_fontsize = "12";

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
  $name_fontsize = get_image_font_size($name_max_fontsize,$name,$font,$width_px,
                                       $gaiji_name_arr);
  
  $nowLeft = gaiji_imagettftext($image,$name_fontsize,0,$first_left,45,$col_t,$font,$name,$gaiji_name_arr);

  if(mb_strlen($respect,"utf-8") <= 3){
    gaiji_imagettftext($image,$name_fontsize,0,$nowLeft,45,$col_t,$font,$respect,array());
  }else if(mb_strlen($respect,"utf-8")>4){
    gaiji_imagettftext($image,$name_fontsize,0,$nowLeft,45,$col_t,$font,$respect,array(),50);
  }
  
  
}

function get_image_name_plate_full($last_name,$first_name,$comment1="",$comment2="",$memo1="",$memo2="",$memo3="",
                         $gaiji_last_name_arr = array(),$gaiji_first_name_arr = array(),
                              $gaiji_comment1_arr = array(),$gaiji_comment2_arr = array(),$color = array(0x00,0x00,0x00),$respect=""){

  $comment_max_fontsize = "8";
  $name_max_fontsize = "12";

  $font = dirname(__FILE__)."/../fonts/msmincho.ttc";
  $width_px = "150";
  $memo_px = "50";

  $image = imagecreatetruecolor($width_px+$memo_px,45) or die("Cannot Initialize new GD image stream");
  $col_g = imagecolorallocate($image,0xff,0xff,0xff);
  $col_t = imagecolorallocate($image,0x00,0x00,0x00);
  imagefill($image,0,0,$col_g);
  
  $first_left = 0;
  get_image_name_plate_data($image,$width_px,$first_left,
                            $last_name,$first_name,$comment1,$comment2,
                         $gaiji_last_name_arr,$gaiji_first_name_arr,
                              $gaiji_comment1_arr,$gaiji_comment2_arr,$color,$respect);
  
  $memo1_fontsize = get_image_font_size($comment_max_fontsize,$memo1,$font,$memo_px,
                                        array());
  gaiji_imagettftext($image,$memo1_fontsize,0,150,12,$col_t,$font,$memo1,array());  
  
  $memo2_fontsize = get_image_font_size($comment_max_fontsize,$memo2,$font,$memo_px,
                                        array());
  gaiji_imagettftext($image,$memo2_fontsize,0,150,26,$col_t,$font,$memo2,array());
  
  $memo3_fontsize = get_image_font_size($comment_max_fontsize,$memo3,$font,$memo_px,
                                        array());
  gaiji_imagettftext($image,$memo3_fontsize,0,150,45,$col_t,$font,$memo3,array());

  return $image;
}

function get_image_name_plate_full2($last_name,$first_name,$comment1="",$comment2="",$memo1="",$memo2="",$memo3="",
                         $gaiji_last_name_arr = array(),$gaiji_first_name_arr = array(),
                              $gaiji_comment1_arr = array(),$gaiji_comment2_arr = array(),$color = array(0x00,0x00,0x00),$respect=""){

  $comment_max_fontsize = "8";
  $name_max_fontsize = "12";

  $font = dirname(__FILE__)."/../fonts/msmincho.ttc";
  $width_px = "150";
  $memo_px = "50";

  $image = imagecreatetruecolor($width_px+$memo_px,45) or die("Cannot Initialize new GD image stream");
  $col_g = imagecolorallocate($image,0xff,0xff,0xff);
  $col_t = imagecolorallocate($image,0x00,0x00,0x00);
  imagefill($image,0,0,$col_g);
  
  $memo1_fontsize = get_image_font_size($comment_max_fontsize,$memo1,$font,$memo_px,
                                        array());
  gaiji_imagettftext($image,$memo1_fontsize,0,0,12,$col_t,$font,$memo1,array());  
  
  $memo2_fontsize = get_image_font_size($comment_max_fontsize,$memo2,$font,$memo_px,
                                        array());
  gaiji_imagettftext($image,$memo2_fontsize,0,0,26,$col_t,$font,$memo2,array());
  
  $memo3_fontsize = get_image_font_size($comment_max_fontsize,$memo3,$font,$memo_px,
                                        array());
  gaiji_imagettftext($image,$memo3_fontsize,0,0,45,$col_t,$font,$memo3,array());


  $first_left = $memo_px;
  get_image_name_plate_data($image,$width_px,$first_left,
                            $last_name,$first_name,$comment1,$comment2,
                         $gaiji_last_name_arr,$gaiji_first_name_arr,
                              $gaiji_comment1_arr,$gaiji_comment2_arr,$color,$respect);
  
  return $image;
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
  $hotel_id=1;
  $user_folder = sprintf("%s/user_name/%d/",get_image_db_directory($hotel_id),$user_id);
  @mkdir(get_image_db_directory($hotel_id));
  @mkdir(get_image_db_directory($hotel_id)."/user_name");
  @mkdir($user_folder);
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
  $hotel_id=1;
  $user_folder = sprintf("%s/user_name/%d/",get_image_db_directory($hotel_id),$user_id);
  @mkdir($user_folder);
  @mkdir($user_folder."guest");
  $colorArray = array(0x00,0x00,0x00);
  //if($_POST["stage"] == 1) $colorArray = array(255,0,0);
  @mkdir($user_folder."guest");
  @mkdir($user_folder."guest/".$guest_id);
  @mkdir($user_folder."guest/".$guest_id."/thumb1");
  @mkdir($user_folder."guest/".$guest_id."/thumb2");
  $user_folder = $user_folder."guest/".$guest_id."/";
  
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
  $savefile = sprintf("%s/user_name/%d/%s/%d/%s",get_image_db_directory($hotel_id),$user_id,"guest",$guest_id,"namecard.png");
  make_name_plate_save($last_name,$first_name,$comment1,$comment2,
                       $lastname_gaiji_pathArray,$firstname_gaiji_pathArrays,
                       $comment1_gaiji_pathArray,$comment2_gaiji_pathArray,$savefile,$colorArray,$respect);

  //引き出物を含んだ画像を作成
  $dataClass = new DataClass();
  $gift_name = $dataClass->get_gift_name($user_id,$guest_id);
  $menu_name = $dataClass->get_menu_name($user_id,$guest_id);
  $guest_detail = $dataClass->get_guest_detail($user_id,$guest_id);
  $memo = $guest_detail["memo"];
  $savefile = sprintf("%s/user_name/%d/%s/%d/%s",get_image_db_directory($hotel_id),$user_id,"guest",$guest_id,"namecard_memo.png");
  $savefile2 = sprintf("%s/user_name/%d/%s/%d/%s",get_image_db_directory($hotel_id),$user_id,"guest",$guest_id,"namecard_memo2.png");

  make_name_plate_full_save($last_name,$first_name,$comment1,$comment2,$gift_name,$menu_name,$memo,
                       $lastname_gaiji_pathArray,$firstname_gaiji_pathArrays,
                            $comment1_gaiji_pathArray,$comment2_gaiji_pathArray,$savefile,$savefile2,$colorArray,$respect);
}

function get_image_db_directory($hotel_id){
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
