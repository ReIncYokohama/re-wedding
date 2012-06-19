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
    $this->url = $this->config["url"];
    
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
                 $this->config["super"],$this->config["super_pass"]);
  }

  public function adminLogin($session = false){
    $this->login($session,"adminid","adminpass",
                 $this->config["admin"],$this->config["admin_pass"]);
  }

  public function staffLogin($session = false){
    $this->login($session,"adminid","adminpass",
                 $this->config["staff"],$this->config["staff_pass"]);
  }

  public function adminLogout(){
    $this->_session->element('id','logout_button')->click();
  }

  
  public function login($session,$username_el_id,$password_el_id,$username,$password){
    if(!$session){
      $session = $this->_session;
    }
    //ログイン画面にアクセス
    $session->open($this->url."/admin");
    
    //ユーザ名とパスワードを入力する
    $this->text($username_el_id,$username);
    $this->text($password_el_id,$password);

    //送信ボタンをクリック
    $session->element('id','login_submit')->click();
  }
  
  public function ajaxDateClick(
    $input_element_id,$day = false,$month = false,$year = false,$session = false){
    
    if(!$session){
      $session = $this->_session;
    }
    
    $session->element('id',$input_element_id)->click();
    
    $today = getdate();
    if(!$day){
      $day = $today["mday"];
    }
    if(!$month){
      $month = $today["mon"];
    }
    if(!$year){
      $year = $today["year"];
    }
    //$navbuttonElements
    //0 prev year 1 prev month 2 today 3 next month 4 next month
    if($month>$today["mon"]){
      for($i=0;$i<$month-$today["mon"];++$i){
        $navbuttonElements = $session->elements('class name',"navbutton");
        $navbuttonElements[3]->click();
      }
    }else if($month<$today["mon"]){
      for($i=0;$i<$today["mon"]-$month;++$i){
        $navbuttonElements = $session->elements('class name',"navbutton");
        $navbuttonElements[1]->click();
      }
    }

    if($year>$today["year"]){
      for($i=0;$i<$year-$today["year"];++$i){
        $navbuttonElements = $session->elements('class name',"navbutton");
        $navbuttonElements[4]->click();
      }
    }else if($year<$today["year"]){
      for($i=0;$i<$today["year"]-$year;++$i){
        $navbuttonElements = $session->elements('class name',"navbutton");
        $navbuttonElements[0]->click();
      }
    }
    
    $elements = $session->elements('class name',"day");
    $clicked = false;
    for($i=0;$i<count($elements);++$i){
      $elements = $session->elements('class name',"day");
      $element = $elements[$i];
      if($element->text() == $day){
        $elements = $session->elements('class name',"day");
        $element = $elements[$i];
        $element->click();
        $clicked = true;
        break;
      }
    }
    //日付がクリックできたかどうか判定
    $this->assertEquals(true, $clicked);
    
  }

  public function text($id,$text,$session = false){
    if(!$session){
      $session = $this->_session;
    }
    $session->element("id",$id)->value(Core_Test::split_keys($text));
  }
}
