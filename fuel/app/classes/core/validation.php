<?php

class Core_Validation extends Validation{
  static public function checkEmail($email){
    $val = static::forge();
    $val->add("email",$email);
    $val->add_rule("valid_email");
    if($val->run()){
      return true;
    }
    return false;
  }
}
