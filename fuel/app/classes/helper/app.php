<?php

class Helper_App{

  // $arr have to need value and text
  public static function options($arr, $target = false){
    $return = "";
    foreach($arr as $obj){
      $return .= "<option value=\"".$obj["value"]."\"";
      if($target and $target == $obj["value"]) $return .= " selected ";
      $return .= ">".$obj["text"]."</option>";
    }
    return $return;
  }

  public static function redirect($url, $text){
    echo "<script>alert(\"".$text."\");window.location = \"".$url."\"</script>";
  }


}