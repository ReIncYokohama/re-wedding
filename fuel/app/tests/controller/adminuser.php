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
    //招待者の新規作成画面をクリック
    $this->_session->element('id','new_user_button')->click();
    //招待者の新規作成画面をクリックしたことを確認
    $this->assertEquals($this->url."/admin/user_info_allentry.php", $this->_session->url());
    //披露宴日の入力
    $this->ajaxDateClick("party_day",28,7,2012);
    //披露宴日の時間
    $this->text("party_hour","12");
    //挙式日の入力
    $this->ajaxDateClick("marriage_day",28,7,2012);
    
  }
}