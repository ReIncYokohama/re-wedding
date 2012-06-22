<?php

namespace Fuel\Tasks;

class Test{
  public static function start(){
    system('java -jar app/vendor/selenium/selenium-server-standalone-2.23.1.jar');
  }
}
