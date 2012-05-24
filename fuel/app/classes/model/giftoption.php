<?php
class Model_Giftoption extends Model_Crud{
  static $_table_name = "spssp_gift_criteria";
  static public function data(){
    $option=Model_Giftoption::find_by_pk(1);
    return $option;
  }
}
