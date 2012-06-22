<?php

class Core_Code{
  static public function convert_shiftjis($text){
    $text = chop($text);
    if($text=="") return "";
    return mb_convert_encoding($text,"SJIS","UTF8");
  }
  
  static public function split($str,$delimiter){
    mb_regex_encoding('SJIS');
    mb_internal_encoding("SJIS"); 
    $strArray = mb_split($delimiter,$str);
    mb_regex_encoding('UTF8');
    mb_internal_encoding("UTF8");
    return $strArray;
  }
  
  static public function insert_gaijicode($str,$gaiji_objs){
    $strArray = Core_Code::split($str,Core_Code::convert_shiftjis("＊"));
    //explodeの場合shift jisの場合、問題が起きることがある(梶本)
    //$strArray = explode(s("＊"),$str);
    $returnStr = "";
    for($i=0;$i<count($strArray)-1;++$i){
      preg_match("/(.*?)\.(.*?)/",$gaiji_objs[$i]["gu_char_img"],$matches);
      $first = substr($matches[1],0,2);
      $second = substr($matches[1],2,2);
      $data = pack("c*",hexdec($first),hexdec($second));
      $returnStr .= $strArray[$i].$data;
    }
    $returnStr .= $strArray[count($strArray)-1];
    return $returnStr;
  }
}
