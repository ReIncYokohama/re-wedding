<?php
//admin/roomtableeditから入力
class Model_Table extends Model_Crud{
  static $_table_name = "spssp_default_plan_table";
  static $_fields = array("id","name","room_id");
}