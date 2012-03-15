<?php
class Model_Userseat extends Model_Crud{
  //spssp_default_plan_details have to destory
  static $_table_name = "spssp_plan_details";
  static $_fields = array("id","seat_id","guest_id","plan_id");
  //検索するときは、seat_id and plan_id , guest_id and plan_idでする必要あり。
  //cartを利用して保存する際に重複が大量にあるもよう。
}