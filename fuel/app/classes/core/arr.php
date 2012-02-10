<?php
class Core_Arr extends Arr
{
  public static function func($array,$func_name){
    $returnArray = array();
    for($i=0;$i<count($array);++$i){
      array_push($returnArray,$array[$i]->$func_name());
    }
    return $returnArray;
  }
  public static function array_key_true($key,$array){
    if(array_key_exists($key,$array)&&$array[$key]){
      return true;
    }
    return false;
  }
  public static function have_key_arr($array,$key,$return_key=false){
    $returnArray = array();
    for($i=0;$i<count($array);++$i){
      if(Core_Arr::array_key_true($key,$array[$i])){
        if($return_key){
          if(Core_Arr::array_key_true($return_key,$array[$i])){
            array_push($returnArray,$array[$i][$return_key]);
          }
        }else{
          array_push($returnArray,$array[$i]);
        }
      }
    }
    return $returnArray;
  }

}
