<?php
include_once(dirname(__FILE__)."/gaiji.image.util.php");

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

function get_image_name_plate($last_name,$first_name,$comment1="",$comment2="",
                         $gaiji_last_name_arr = array(),$gaiji_first_name_arr = array(),
                              $gaiji_comment1_arr = array(),$gaiji_comment2_arr = array(),$color = array(0x00,0x00,0x00),$respect){

  $comment_max_fontsize = "8";
  $name_max_fontsize = "11";

  $font = dirname(__FILE__)."/../fonts/msmincho.ttc";
  $width_px = "150";

  $image = imagecreatetruecolor($width_px,40) or die("Cannot Initialize new GD image stream");
  $col_g = imagecolorallocate($image,0xff,0xff,0xff);
  $col_t = imagecolorallocate($image,0x00,0x00,0x00);
  imagefill($image,0,0,$col_g);

  $name = $last_name." ".$first_name." ".$respect;
  if(!$comment1 || $comment1 == "") $comment1 = " ";
  if(!$comment2 || $comment2 == "") $comment2 = " ";
  $comment1_fontsize = get_image_font_size($comment_max_fontsize,$comment1,$font,$width_px,
                                           $gaiji_comment1_arr);
  $comment2_fontsize = get_image_font_size($comment_max_fontsize,$comment2,$font,$width_px,
                                           $gaiji_comment2_arr);
  gaiji_imagettftext($image,$comment1_fontsize,0,0,11,$col_t,$font,$comment1,$gaiji_comment1_arr);
  gaiji_imagettftext($image,$comment2_fontsize,0,0,22,$col_t,$font,$comment2,$gaiji_comment2_arr);
  
  $gaiji_name_arr = array_merge($gaiji_last_name_arr, $gaiji_first_name_arr);
  $name_fontsize = get_image_font_size($name_max_fontsize,$name,$font,$width_px,
                                       $gaiji_name_arr);

  gaiji_imagettftext($image,$name_fontsize,0,0,37,$col_t,$font,$name,$gaiji_name_arr);
  //imagettftext($image,$name_fontsize,0,0,34,$col_t,$font,$name);
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
function set_guest_gaiji_position($user_id,$guest_id,$str,$target_type,$gaiji_file_name_arr=array(),$gaiji_code_arr=array(),$gaiji_sjis_code_arr=array(),$gaiji_str="＊"){
  $len = mb_strlen($str,'utf-8');
  if($len==0) return;
  $k = 0;
  for($i=0;$i<$len;++$i){
    $charcode = (int)hexdec(bin2hex(mb_substr($str,$i,1)));
    if(mb_substr($str,$i,1)==$gaiji_str){
      if(!$gaiji_code_arr[$k] || !$gaiji_file_name_arr[$k]) continue;
      $gaiji_detail_sql = "insert into spssp_gaizi_detail_for_guest(gu_id,guest_id,gu_trgt_type,gu_char_position,gu_char_img,gu_char_setcode,gu_sjis_code)  values(" .$user_id. "," .$guest_id.  "," .$target_type. "," .$i. ",'".$gaiji_file_name_arr[$k] ."'," .$gaiji_code_arr[$k]."," .$gaiji_sjis_code_arr[$k]. ");";
          $test = mysql_query($gaiji_detail_sql);
          ++$k;
    }
  }
}

function set_user_gaiji_position($user_id,$str,$target_type,$gaiji_file_name_arr=array(),$gaiji_code_arr=array(),$gaiji_str="＊"){
  $len = mb_strlen($str,'utf-8');
  if($len==0) return;
  $k = 0;

  for($i=0;$i<$len;++$i){
    $charcode = (int)hexdec(bin2hex(mb_substr($str,$i,1)));
    if(mb_substr($str,$i,1)==$gaiji_str){
      $gaiji_detail_sql = "insert into spssp_gaizi_detail_for_user(gu_id,gu_trgt_type,gu_char_position,gu_char_img,gu_char_setcode)  values(" .$user_id. "," .$target_type. "," .$i. ",'".$gaiji_file_name_arr[$k]."'," .$gaiji_code_arr[$k]. ");";
          $test = mysql_query($gaiji_detail_sql);
          ++$k;
    }
  }
}
/* Guest 用の画像を作成
 */
function make_image_for_guest(){
  
}
