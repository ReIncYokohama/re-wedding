<?php
/**
 * @group App
 * @group User
 * @group Controller
 */

class Test_Controller_User extends Core_Test
{
  public function testCreate(){
    //新規ユーザの生成

    //最新のユーザ情報を取得
    $user = Model_User::recent();

    //ログイン

    //文字の見え方を確認


  }
}
