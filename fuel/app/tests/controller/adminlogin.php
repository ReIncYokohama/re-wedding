<?php
/**
 * @group App
 * @group Login
 * @group Controller
 */

class Test_Controller_Adminlogin extends Core_Test
{
    
  public function testSuperlogintrue(){
    
    //ログイン画面にアクセス
    $url = $this->config["url"];
    $this->_session->open($url."/admin");
    //送信ボタンをクリック
    $this->_session->element('id','login_submit')->click();
    //“ログインIDが未入力です”とアラートが表示される
    $this->assertEquals("ログインIDが未入力です", $this->_session->alert_text());
    //アラートをOKする
    $this->_session->accept_alert();
    
    //印刷会社でログイン
    $this->superLogin();
    //manager画面に移動したことを確認
    $this->assertEquals($url."/admin/manage.php", $this->_session->url());

    $web_driver2 = new WebDriver();
    $this->_session2 = $web_driver2->session($this->config["browser2"]);
    //印刷会社でログイン
    $this->superLogin($this->_session2);
    
    //“ログイン画面に遷移していることを確認”とアラートが表示される
    $this->assertEquals("既に同じIDでログインされています。", $this->_session2->alert_text());
    $this->_session2->close();

    //ログアウトボタンをクリック
    $this->adminLogout();
    //manager画面に移動したことを確認
    $this->assertEquals($url."/admin/index.php", $this->_session->url());
    
    $this->_session->close();
  }

  public function testAdminlogintrue(){
    
  }
}
