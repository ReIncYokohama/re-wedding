<?php
//hotel卓名から入力
class Model_Tablename extends Model_Crud{
  static $_table_name = "spssp_tables_name";
  static $_fields = array("id","name","display_order");
}