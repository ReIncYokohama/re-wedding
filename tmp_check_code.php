<?php

$target = $_GET["d"];
$target2 = mb_convert_encoding($target, 'utf-8', "utf-8");
$codeArray = array();
$code2Array = array();
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
  if($str0!="?"&&$str=="?") print "?";//utf-8からsjis-winに変換できない漢字
  if ((strlen(bin2hex($str)) / 2) == 1) { // 1バイト文字                
    $c = ord($str{0});
    array_push($codeArray,$c);
    array_push($code2Array,dechex($c));
  } else {
    $c = ord($str{0}); // 先頭1バイト
    $c2 = ord($str{1}); // 2バイト目
    $c3 = $c * 0x100 + $c2; // 2バイト分の数値にする。
    array_push($codeArray,$c3);
    array_push($code2Array,dechex($c3));
  }
}
print_r($codeArray);
print_r($code2Array);
