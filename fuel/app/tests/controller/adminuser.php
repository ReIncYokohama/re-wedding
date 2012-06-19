<?php
/**
 * @group App
 * @group User
 * @group Controller
 */

class Test_Controller_Adminuser extends Core_Test
{
  public function testCreate(){
    //印刷会社でログイン
    $this->superLogin();
    
  }
}