<?php

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
        $rtn .= $str0."(MS異形文字)";
        $change = true;
        break;
      }
    }
    if($change) continue;
    // 1文字をSJISにする。
    $str = mb_convert_encoding($str0, "sjis-win", 'utf-8');
    if ((strlen(bin2hex($str)) / 2) == 1) { // 1バイト文字                
      $c = ord($str{0});
    } else {
      $c = ord($str{0}); // 先頭1バイト
      $c2 = ord($str{1}); // 2バイト目
      $c3 = $c * 0x100 + $c2; // 2バイト分の数値にする。

      if ((($c3 >= 0x8140) && ($c3 <= 0x853D)) || // 2バイト文字
          (($c3 >= 0x889F) && ($c3 <= 0x988F)) // 第一水準
          // || (($c3 >= 0x9890) && ($c3 <= 0x9FFF)) // 第二水準
          // ||(($c3 >= 0xE040) && ($c3 <= 0xEAFF))//  第二水準
          ) { 
      } else {
        $rtn .= $str0."(外字)";
      }
    }
  }
  return $rtn;
}


$char = $_GET["d"];
$file = file(dirname(__file__)."/ms_gaiji_sjis1.csv");
$sjis2 = check_sjis_1($char,$file);

print $sjis2.$ms_sjis;
