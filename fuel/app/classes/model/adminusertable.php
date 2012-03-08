<?php
class Model_Adminusertable extends Model_Crud{
  static $_table_name = "spssp_user_table";
  static $_fields = array("id","user_id","default_table_id","table_name_id");
}