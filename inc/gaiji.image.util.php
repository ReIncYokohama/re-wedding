<?php


/* width�̑傫����Ԃ�
   gaiji��font��height�ɑ傫���𒲐�����B
 */
function get_image_text_width($fontsize,$str,$fontfile,$gaiji_image_url_arr = array(),$gaiji_str = "��"
                              ,$angle = 0
                              ){
  //�󔒂�[��]�ɒu�������ĕ����v�Z����B
  //�����������J�ɋ󔒂̒������s�����Ƃ��\�B
  $str = str_replace(" ","��",$str);
  $str_not_gaiji_arr = explode($gaiji_str,$str);
  $gaiji_num = count($str_not_gaiji_arr)-1;
  $str_not_gaiji = implode("",$str_not_gaiji_arr);
  
  $image_arr = imagettfbbox($fontsize,$angle,$fontfile,"��".$str_not_gaiji);
  $height = $image_arr[1]-$image_arr[5];
  //�傫��������Ȃ��̂ňꎞ�I�ɕ������𑝂₷���ƂőΉ��B
  if(mb_strlen($str,"UTF-8")>8){
    $image_arr = imagettfbbox($fontsize,$angle,$fontfile,"������".$str_not_gaiji);
  }else{
    $image_arr = imagettfbbox($fontsize,$angle,$fontfile,$str_not_gaiji); 
  }
  
  $width = $image_arr[2]-$image_arr[0];
  $width_sum = $width;
  //�C���[�W���Ȃ��Ƃ��ɃG���[��������ꂽ���B
  for($i=0;$i<$gaiji_num;++$i){
    if(!$gaiji_image_url_arr[$i]) continue;
    list($gaiji_image_width,$gaiji_image_height) = getimagesize($gaiji_image_url_arr[$i]);
    $width_sum += $gaiji_image_width*$height/$gaiji_image_height;
  }
  
  //�������؂�Ȃ��悤�ɗ]�T�������č쐬
  return $width_sum;
}

function get_image_text_height($fontsize,$font,$text,$angle=0){
  $image_arr = imagettfbbox($fontsize,$angle,$font,"��".$text);
  $height = $image_arr[1]-$image_arr[5];
  return $height;
}

function text_imagettftext($image,$insert_height,$angle,$left,$bottom,$color,$font,$text,$width_compression=100){
  $fontsize = 15;
  if($text == "") return 0;
  
  $text = mb_ereg_replace("�@", " ", $text);
  $image_arr = imagettfbbox($fontsize,$angle,$font,$text);
  $height = $image_arr[1] - $image_arr[5];
  $width = $image_arr[2] - $image_arr[0];
  
  //���p�X�y�[�X���Ō�̂Ƃ��A�ʏ�̔{�̒������擾���Ă���B
  if(mb_substr($text,mb_strlen($text,"UTF-8")-1,1,"UTF-8") == " ") $width-=10;
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


function text_imagettftext_align_right($image,$insert_height,$angle,$left,$bottom,$color,$font,$text,$width_compression=100){
  $fontsize = 15;
  if($text == "") return 0;
  
  $text = mb_ereg_replace("�@", " ", $text);
  $image_arr = imagettfbbox($fontsize,$angle,$font,$text);
  $height = $image_arr[1] - $image_arr[5];
  $width = $image_arr[2] - $image_arr[0];
  
  //���p�X�y�[�X���Ō�̂Ƃ��A�ʏ�̔{�̒������擾���Ă���B
  if(mb_substr($text,mb_strlen($text,"UTF-8")-1,1,"UTF-8") == " ") $width-=10;
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
  
  imagecopyresampled($image,$im,$left-$insert_width,$bottom-$insert_height,0,0,
                       $insert_width,$insert_height,
                     $width,$height);
  return $insert_width;
}



/* 
   
 */

function gaiji_imagettftext($image,$fontsize,$angle,$left,$bottom,$color,
                            $font,$str,$gaiji_image_url_arr=array(),$width_compression=100,$gaiji_str="��"){
  $str_not_gaiji_arr = explode($gaiji_str,$str);
  $gaiji_num = count($str_not_gaiji_arr);
  $str = mb_ereg_replace("�@", " ", $str);

  $image_arr = imagettfbbox($fontsize,$angle,$font,"��".implode("",$str_not_gaiji_arr));
  $height = $image_arr[1]-$image_arr[5];

  $nowLeft = $left;
  for($i=0;$i<$gaiji_num;++$i){
    //$image_arr = imagettftext($image,$fontsize,$angle,$nowLeft,$bottom,$color,$font,$str_not_gaiji_arr[$i]);
    //$text_width = $image_arr[2]-$image_arr[0];
    $text_width = text_imagettftext($image,$height,$angle,$nowLeft,$bottom,$color,$font,$str_not_gaiji_arr[$i],$width_compression);
    $nowLeft += $text_width;
    if($i+1!=$gaiji_num){
      if(!$gaiji_image_url_arr[$i]) continue;
      list($gaiji_image_width,$gaiji_image_height) = getimagesize($gaiji_image_url_arr[$i]);
      $gaiji_image = imagecreatefrompng($gaiji_image_url_arr[$i]);
      $gaiji_image_insert_width = (int)($gaiji_image_width*$height/$gaiji_image_height);
      $leftTop = $bottom-$height+1;
      //�����̒����̂��߂�2px�����Ă���
      //���������̂��߂�1px�������B
      imagealphablending($gaiji_image, false); 
      imagesavealpha($gaiji_image, true);

      imagecopyresampled($image,$gaiji_image,$nowLeft-0.5,$leftTop-1,0,0,
                       $gaiji_image_insert_width,$height+1,
                       $gaiji_image_width,$gaiji_image_height);
      
      $nowLeft += $gaiji_image_insert_width;
    }
  }
  return $nowLeft;
}


function gaiji_imagettftext_align_right($image,$fontsize,$angle,$right,$bottom,$color,
                            $font,$str,$gaiji_image_url_arr=array(),$width_compression=100,$gaiji_str="��"){
  $str_not_gaiji_arr = explode($gaiji_str,$str);
  $gaiji_num = count($str_not_gaiji_arr);
  $str = mb_ereg_replace("�@", " ", $str);

  $image_arr = imagettfbbox($fontsize,$angle,$font,"��".implode("",$str_not_gaiji_arr));
  $height = $image_arr[1]-$image_arr[5];

  $nowRight = $right;

  for($i=$gaiji_num;$i>=0;--$i){
    //$image_arr = imagettftext($image,$fontsize,$angle,$nowLeft,$bottom,$color,$font,$str_not_gaiji_arr[$i]);
    //$text_width = $image_arr[2]-$image_arr[0];
    $text_width = text_imagettftext_align_right($image,$height,$angle,$nowRight,$bottom,$color,$font,$str_not_gaiji_arr[$i],$width_compression);
    $nowRight -= $text_width;
    if($i!=$gaiji_num){
      $gaiji = $gaiji_image_url_arr[$i-1];
      //$gaiji = $gaiji_image_url_arr[count($gaiji_image_url_arr)-$i];
      //if(!$gaiji) continue;
      list($gaiji_image_width,$gaiji_image_height) = getimagesize($gaiji);
      $gaiji_image = imagecreatefrompng($gaiji);
      $gaiji_image_insert_width = (int)($gaiji_image_width*$height/$gaiji_image_height);
      $leftTop = $bottom-$height+1;
      //�����̒����̂��߂�2px�����Ă���
      //���������̂��߂�1px�������B
      imagealphablending($gaiji_image, false); 
      imagesavealpha($gaiji_image, true);
      
      imagecopyresampled($image,$gaiji_image,$nowRight-0.5-$gaiji_image_insert_width,$leftTop-1,0,0,
                       $gaiji_image_insert_width,$height+1,
                       $gaiji_image_width,$gaiji_image_height);
      
      $nowRight -= $gaiji_image_insert_width;
    }

  }
  return $nowRight;
}


function gaiji_imagettftext_tategaki($image,$fontsize,$angle,$left,$top,$color,$font,$str,$gaiji_image_url="",$gaiji_str="��"){
  if(!$str or $str == "") return 0;

  $image_arr = imagettfbbox($fontsize,$angle,$font,"��");
  $height = $image_arr[1]-$image_arr[5];
  
  if($str == $gaiji_str){
    list($gaiji_image_width,$gaiji_image_height) = getimagesize($gaiji_image_url);
    $gaiji_image = imagecreatefrompng($gaiji_image_url);
    $gaiji_image_insert_width = (int)($gaiji_image_width*$height/$gaiji_image_height);

    //�����̒����̂��߂�2px�����Ă���
    //���������̂��߂�1px�������B
    
    imagecopyresampled($image,$gaiji_image,$left,$leftTop-1,0,0,
                       $gaiji_image_i1nsert_width,$height+1,
                       $gaiji_image_width,$gaiji_image_height);
    return $leftTop+$gaiji_image_height;
  }else{
    $image_arr = imagettfbbox($fontsize,$angle,$font,$str);
    $height = $image_arr[1]-$image_arr[5];
    $width = $image_arr[2]-$image_arr[0];
    $bottom = $top+$height;
    if($str == "�X"){
      $text_width = text_imagettftext($image,$height,$angle,$left+3,$bottom,$color,$font,$str);
    }else if(ctype_lower($str)){
      if($fontsize<10){
        $text_width = text_imagettftext($image,$height,$angle,$left+3,$bottom,$color,$font,$str);
      }else{
        $text_width = text_imagettftext($image,$height,$angle,$left+5,$bottom,$color,$font,$str);
      }
    }else{
      $text_width = text_imagettftext($image,$height,$angle,$left,$bottom,$color,$font,$str);
    }
  }
  return $bottom;
}



/*�w�肳�ꂽwidth�ɂ����`�Ńt�H���g�̑傫���𒲐߂��A���̃t�H���g�̃T�C�Y��Ԃ��B
 */
function get_image_font_size($maxfontsize,$str,$fontfile,$width,$gaiji_image_url_arr = array(),
                             $gaiji_str = "��",$angle = 0){
  $fontsize = $maxfontsize;
  $nowWidth = get_image_text_width($fontsize,$str,$fontfile,$gaiji_image_url_arr,$gaiji_str,$angle);

  while($nowWidth>$width && $fontsize>1){
    $fontsize -= 0.5;
    $nowWidth = get_image_text_width($fontsize,$str,$fontfile,$gaiji_image_url_arr,$gaiji_str,$angle);
  }
  return $fontsize;
}


function error_include_gaiji($str,$gaiji_str = "��"){
  $str_not_gaiji_arr = explode($gaiji_str,$str);
  if($str_not_gaiji_arr<=1) return true;
  return false;
}