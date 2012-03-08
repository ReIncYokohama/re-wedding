<?php
class Model_Userseat extends Model_Crud{
  //spssp_default_plan_details have to destory
  static $_table_name = "spssp_plan_details";
  static $_fields = array("id","seat_id","guest_id","plan_id");  
}