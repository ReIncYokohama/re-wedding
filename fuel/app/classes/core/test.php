<?php

require_once APPPATH."vendor/php-webdriver/__init__.php";

class Core_Test extends PHPUnit_Framework_TestCase{
  /** 
   * @var WebDriverSession
   */
  protected $_session;

  
  public function setUp()
  {
    parent::setUp();
    $this->config = Config::load("test");
    
    //ブラウザの設定
    $web_driver = new WebDriver();    
    $this->_session = $web_driver->session($this->config["browser"]);

    //ログインを制御しているファイルを削除する
    Core_Adminlogin::force_delete_file();
  }


  static public function split_keys($toSend){
    $payload = array("value" => preg_split("//u", $toSend, -1, PREG_SPLIT_NO_EMPTY));
    return $payload;
  }

  public function superLogin($session = false){
    $this->login($session,"adminid","adminpass",
                 Core_Test::split_keys($this->config["super"]),
                 Core_Test::split_keys($this->config["super_pass"]));
  }

  public function adminLogin($session = false){
    $this->login($session,"adminid","adminpass",
                 Core_Test::split_keys($this->config["admin"]),
                 Core_Test::split_keys($this->config["admin_pass"]));
  }

  public function staffLogin($session = false){
    $this->login($session,"adminid","adminpass",
                 Core_Test::split_keys($this->config["staff"]),
                 Core_Test::split_keys($this->config["staff_pass"]));
  }

  public function adminLogout(){
    $this->_session->element('id','logout_button')->click();
  }

  
  public function login($session,$username_el_id,$password_el_id,$username,$password){
    if(!$session){
      $session = $this->_session;
    }
    //ログイン画面にアクセス
    $url = $this->config["url"];
    $session->open($url."/admin");
    
    //ユーザ名とパスワードを入力する
    $session->element("id","adminid")->value(Core_Test::split_keys("adminsunpri"));
    $session->element("id","adminpass")->value(Core_Test::split_keys("n3s8n7m9"));

    //送信ボタンをクリック
    $session->element('id','login_submit')->click();
  }

}
