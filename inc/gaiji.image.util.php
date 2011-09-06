<?php


/* widthの大きさを返す
   gaijiはfontのheightに大きさを調整する
   。
 */
function get_image_text_width($fontsize,$str,$fontfile,$gaiji_image_url_arr = array(),$gaiji_str = "＊"
                              ,$angle = 0
                              ){
  //空白を[あ]に置き換えて幅を計算する。
  //もう少し丁寧に空白の調整を行うことも可能。
  $str = str_replace(" ","く",$str);
  $str_not_gaiji_arr = explode($gaiji_str,$str);
  $gaiji_num = count($str_not_gaiji_arr)-1;
  $str_not_gaiji = implode("",$str_not_gaiji_arr);
  
  $image_arr = imagettfbbox($fontsize,$angle,$fontfile,"あ".$str_not_gaiji);
  $height = $image_arr[1]-$image_arr[5];
  $image_arr = imagettfbbox($fontsize,$angle,$fontfile,"".$str_not_gaiji);
  $width = $image_arr[2]-$image_arr[0];

  $width_sum = $width;
  //イメージがないときにエラー処理を入れたい。
  for($i=0;$i<$gaiji_num;++$i){
    list($gaiji_image_width,$gaiji_image_height) = getimagesize($gaiji_image_url_arr[$i]);
    $width_sum += $gaiji_image_width*$height/$gaiji_image_height;
  }
  //文字が切れないように余裕を持って作成
  return $width_sum+10;
}

function get_image_text_height($fontsize,$font,$text,$angle=0){
  $image_arr = imagettfbbox($fontsize,$angle,$font,"あ".$text);
  $height = $image_arr[1]-$image_arr[5];
  return $height;
}

function text_imagettftext($image,$insert_height,$angle,$left,$bottom,$color,$font,$text,$width_compression=100){
  $fontsize = 15;
  $text = mb_ereg_replace("　", " ", $text);
  $image_arr = imagettfbbox($fontsize,$angle,$font,$text);
  $height = $image_arr[1] - $image_arr[5];
  $width = $image_arr[2] - $image_arr[0];
  
  //半角スペースが最後のとき、通常の倍の長さを取得している。
  if(mb_substr($text,mb_strlen($text)-1) == " ") $width-=10;
  $insert_width = $width*$insert_height/$height;
  $im = imagecreatetruecolor($width,$height);
  imagealphablending($im, false); 
  imagesavealpha($im, true);

  $white = ImageColorClosest($im, 255, 255, 255);
  ImageColorTransparent($im, $white); 
  imagefill($im,0,0,$white);

  $im_image_arr = imagettftext($im,$fontsize,$angle,0,$height-2,$color,$font,$text);
  $insert_width = $insert_width*$width_compression/100;
  //$height = $im_image_arr[1] - $im_image_arr[5];
  //$width = $im_image_arr[2] - $im_image_arr[0];

  imagecopyresampled($image,$im,$left,$bottom-$insert_height,0,0,
                       $insert_width,$insert_height,
                     $width,$height);
  
  return $insert_width;
  
}
/* 
   
 */

function gaiji_imagettftext($image,$fontsize,$angle,$left,$bottom,$color,
                            $font,$str,$gaiji_image_url_arr=array(),$width_compression=100,$gaiji_str="＊"){
  $str_not_gaiji_arr = explode($gaiji_str,$str);
  $gaiji_num = count($str_not_gaiji_arr);
  $str = mb_ereg_replace("　", " ", $str);

  $image_arr = imagettfbbox($fontsize,$angle,$font,"あ".implode("",$str_not_gaiji_arr));
  $height = $image_arr[1]-$image_arr[5];

  $nowLeft = $left;
  for($i=0;$i<$gaiji_num;++$i){
    //$image_arr = imagettftext($image,$fontsize,$angle,$nowLeft,$bottom,$color,$font,$str_not_gaiji_arr[$i]);
    //$text_width = $image_arr[2]-$image_arr[0];
    $text_width = text_imagettftext($image,$height,$angle,$nowLeft,$bottom,$color,$font,$str_not_gaiji_arr[$i],$width_compression);
    $nowLeft += $text_width;
    if($i+1!=$gaiji_num){
      list($gaiji_image_width,$gaiji_image_height) = getimagesize($gaiji_image_url_arr[$i]);
      $gaiji_image = imagecreatefrompng($gaiji_image_url_arr[$i]);
      $gaiji_image_insert_width = (int)($gaiji_image_width*$height/$gaiji_image_height);
      $leftTop = $bottom-$height+1;
      //高さの調整のために2px足している
      //横幅調整のために1px足した。
      
      imagecopyresampled($image,$gaiji_image,$nowLeft-0.5,$leftTop-1,0,0,
                       $gaiji_image_insert_width,$height+1,
                       $gaiji_image_width,$gaiji_image_height);
      $nowLeft += $gaiji_image_insert_width;
    }
  }
  return $nowLeft;
}



/*指定されたwidthにあう形でフォントの大きさを調節し、そのフォントのサイズを返す。
 */
function get_image_font_size($maxfontsize,$str,$fontfile,$width,$gaiji_image_url_arr = array(),
                             $gaiji_str = "＊",$angle = 0){
  $fontsize = $maxfontsize;
  $nowWidth = get_image_text_width($fontsize,$str,$fontfile,$gaiji_image_url_arr,$gaiji_str,$angle);

  while($nowWidth>$width && $fontsize>5){
    $fontsize -= 0.5;
    $nowWidth = get_image_text_width($fontsize,$str,$fontfile,$gaiji_image_url_arr,$gaiji_str,$angle);
  }
  return $fontsize;
}