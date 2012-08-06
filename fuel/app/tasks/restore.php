<?php

namespace Fuel\Tasks;

class Restore{
  //$path db.sqlのpath
  public static function run($path,$email){
    $sql_query = @fread(@fopen($path, 'r'), @filesize($path)) or die('problem ');
    $sql_query = \Core_Mysql::remove_comments($sql_query);
    $sql_query = \Core_Mysql::remove_remarks($sql_query);
    $sql_querys = \Core_Mysql::split_sql_file($sql_query, ';');
    foreach($sql_querys as $sql){
      \DB::query($sql)->execute();
    }
    //ユーザ、ホテルスタッフ、印刷会社のアドレスを自分に
    \DB::query("update spssp_user set mail = '".$email."'")->execute();
    \DB::query("update spssp_admin set email = '".$email."'")->execute();
    \DB::query("update spssp_printing_comapny set email = '".$email."'")->execute();
    Restore::deleteImages();
    Restore::createImages();
  }
  public static function deleteImages(){
    system('rm -rf ../name_image/user/*');
  }
  public static function createImages(){
    system('php ../tools/make_user_images.php');
    system('php ../tools/make_guest_images.php');
  }
}
