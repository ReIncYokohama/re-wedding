<?php

class Core_Image {
  static public $_script_file;
  static public function get_image_dir(){
    $now_script = static::get_script_file();
    $this_file = __FILE__;
    $now_script_dir = explode("/",$now_script);
    $this_file_dir = explode("/",$this_file);
    for($i=0;$i<count($now_script_dir);++$i){
      if($now_script_dir[$i]!=$this_file_dir[$i]) break;
    }
    $num = count($now_script_dir)-1-$i;
    if($num==0) return "name_image/";
    return str_repeat('../', $num)."name_image/";
  }
  static public function get_script_file(){
    if(static::$_script_file) return static::$_script_file;
    return $_SERVER["SCRIPT_FILENAME"];
  }
  static public function get_user_image_dir($user_id){
    $path = static::get_image_dir();
    
    $user_dir = dirname(static::get_script_file())."/".$path."user";
    if(!is_dir($user_dir)){
      mkdir($user_dir);
    }
    $user_dir = $user_dir."/".$user_id;
    if(!is_dir($user_dir)){
      mkdir($user_dir);
    }
    return $user_dir."/";
  }
  static public function get_user_image_dir_relative($user_id){
    $path = static::get_image_dir();
    return $path."user/".$user_id."/";
  }

  static public function get_guest_image_dir($user_id,$guest_id){
    $guest_dir = static::get_user_image_dir($user_id)."guests";
    if(!is_dir($guest_dir)){
      mkdir($guest_dir);
    }
    $guest_dir = $guest_dir."/".$guest_id;
    if(!is_dir($guest_dir)){
      mkdir($guest_dir);
    }
    return $guest_dir."/";
  }
  static public function get_guest_image_dir_relative($user_id,$guest_id){
    $path = static::get_user_image_dir_relative($user_id);
    return $path."guests/".$guest_id."/";
  }

}
