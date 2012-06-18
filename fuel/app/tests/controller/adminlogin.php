<?php
/**
 * @group App
 * @group Login
 * @group Controller
 */

require_once APPPATH."vendor/php-webdriver/__init__.php";

class Test_Controller_Adminlogin extends PHPUnit_Framework_TestCase
{

  /** 
   * @var WebDriverSession
   */
  protected $_session;
  
  public function setUp()
  {
    parent::setUp();
    $this->config = Config::load("test");
    
  }
  
  public function testLogintrue(){
    //ブラウザの設定
    $web_driver = new WebDriver();    
    $this->_session = $web_driver->session($this->config["browser"]);
    
    //ログインを制御しているファイルを削除する
    Core_Adminlogin::force_delete_file();
    //ログイン画面にアクセス
    $url = $this->config["url"];
    $this->_session->open($url."/admin");
    //送信ボタンをクリック
    $this->_session->element('id','login_submit')->click();
    //“ログインIDが未入力です”とアラートが表示される
    $this->assertEquals("ログインIDが未入力です", $this->_session->alert_text());
    //アラートをOKする
    $this->_session->accept_alert();
    
    function split_keys($toSend){
      $payload = array("value" => preg_split("//u", $toSend, -1, PREG_SPLIT_NO_EMPTY));
      return $payload;
    }
    //ユーザ名とパスワードを入力する
    $this->_session->element("id","adminid")->value(split_keys("adminsunpri"));
    $this->_session->element("id","adminpass")->value(split_keys("n3s8n7m9"));
    //送信ボタンをクリック
    $this->_session->element('id','login_submit')->click();
    //manager画面に移動したことを確認
    $this->assertEquals($url."/admin/manage.php", $this->_session->url());

    $web_driver2 = new WebDriver();    
    $this->_session2 = $web_driver2->session($this->config["browser2"]);
    //ログイン画面にアクセス
    $this->_session2->open($url."/admin");
    //ユーザ名とパスワードを入力する
    $this->_session2->element("id","adminid")->value(split_keys("adminsunpri"));
    $this->_session2->element("id","adminpass")->value(split_keys("n3s8n7m9"));
    //送信ボタンをクリック
    $this->_session2->element('id','login_submit')->click();
    //“ログイン画面に遷移していることを確認”とアラートが表示される
    $this->assertEquals("既に同じIDでログインされています。", $this->_session2->alert_text());
    $this->_session2->close();

    //ログアウトボタンをクリック
    $this->_session->element('id','logout_button')->click();    
    //manager画面に移動したことを確認
    $this->assertEquals($url."/admin/index.php", $this->_session->url());
    
    $this->_session->close();
  }
}
