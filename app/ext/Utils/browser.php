<?php

class Browser {
  
  public function isIE(){
    $Agent = self::getAgent();
    if( ereg( "MSIE", $Agent) ){
      return true;
    }
    return false;
  }
  public function getAgent(){
    return getenv( "HTTP_USER_AGENT" );
  }
}
