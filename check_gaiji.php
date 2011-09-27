<?php
$str = $_GET["text"];

$gaiji_arr = get_gaiji_code($str);

echo json_encode($gaiji_arr);

function get_gaiji_code($str){
  $num = mb_strlen($str);
  //$str = mb_convert_encoding($str, "SJIS", "UTF-8");
  $info = gd_info();
  if($info['JIS-mapped Japanese Font Support']){
    $str = $str;
  }else{
    $str = mb_convert_encoding($str,"utf-8");
  }
  $sjis = mb_convert_encoding($str,"Shift-JIS","utf-8");
  $len = mb_strlen($str,'utf-8');
  
  $gaiji_arr = array();
  for($i=0;$i<$len;++$i){
    print mb_substr($str,$i,1,"UTF-8");
    $charcode = (int)hexdec(bin2hex(mb_substr($str,$i,1)));
    //$charcode = bin2hex(mb_substr($str,$i,1));
    //print $charcode;
    //if (($charcode >= EE8080 and $charcode <= EFA3BF)|
    //   ($charcode >= F3B08080 and $charcode <= F3BFBFBE)){
    print ",".",".hexdec(EDBFBF).",".hexdec(EFA3BF).",";
    if ($charcode >= hexdec(EDBFBF) and $charcode <= hexdec(EFA3BF)){
      array_push($gaiji_arr,mb_substr($str,$i,1,"UTF-8"));
    }
  }


  return $gaiji_arr;
}

