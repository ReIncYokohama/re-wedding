<?php
/**
 * @group App
 * @group Login
 */
class Test_Core_Arr extends PHPUnit_Framework_TestCase
{
  
  public function testArraykeytrue(){

    $stack = array();
    $this->assertEquals(0, count($stack));
 
    array_push($stack, 'foo');
    $this->assertEquals('foo', $stack[count($stack)-1]);
    $this->assertEquals(1, count($stack));
 
    $this->assertEquals('foo', array_pop($stack));
    $this->assertEquals(0, count($stack));
  }
}
