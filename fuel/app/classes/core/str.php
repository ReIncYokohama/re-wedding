<?php
class Core_Str
{
  function mbStringToArray ($sStr, $sEnc='UTF-8') {
    $aRes = array();
    while ($iLen = mb_strlen($sStr, $sEnc)) 
      {
        array_push($aRes, mb_substr($sStr, 0, 1, $sEnc));
        $sStr = mb_substr($sStr, 1, $iLen, $sEnc);
      }
    return $aRes;
  }
  public function add_str($str,$margin_num = 2,$delimiter = "<br>"){
    $return = "";
    foreach(Core_Str::mbStringToArray($str) as $s){
      $return .= $s."<br>";
    }
    /* 半角の場合は２文字
    $now = 0;
    foreach(Core_Str::mbStringToArray($str) as $s){
      if (preg_match("/^[a-zA-Z0-9]+$/",$s)) {
        $now += 1;
      }else{
        $now += $margin_num;
      }
      if($now>$margin_num){
        $now = 0;
        $return .= "<br>".$s;
      }else if($now==$margin_num){
        $now = 0;
        $return .= $s."<br>";
      }else{
        $return .= $s;
      }
      }*/
    return $return;
  }
}