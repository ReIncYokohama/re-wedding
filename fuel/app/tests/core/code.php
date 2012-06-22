<?php
/**
 * @group App
 * @group Code
 */
class Test_Core_Code extends PHPUnit_Framework_TestCase
{
  
  public function testArraykeytrue(){
    $string = "梶本";
    $arr = Core_Code::split(Core_Code::convert_shiftjis($string),Core_Code::convert_shiftjis("＊"));
    $this->assertEquals(count($arr), 1);
    
    $string = "＊本";
    $arr = Core_Code::split(Core_Code::convert_shiftjis($string),Core_Code::convert_shiftjis("＊"));
    $this->assertEquals(count($arr), 2);
    
    $string = "*本";
    $arr = Core_Code::split(Core_Code::convert_shiftjis($string),Core_Code::convert_shiftjis("＊"));
    $this->assertEquals(count($arr), 1);
    
    /* explodeの動作 */
    $string = "梶本";
    $arr = explode(Core_Code::convert_shiftjis("＊"),Core_Code::convert_shiftjis($string));
    $this->assertEquals(count($arr), 2);

    $string = "*本";
    $arr = explode(Core_Code::convert_shiftjis("＊"),Core_Code::convert_shiftjis($string));
    $this->assertEquals(count($arr), 1);
        
  }
}

