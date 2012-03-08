<?php
class Model_Room extends Model_Crud{
  static $_table_name = "spssp_room";
  static $_fields = array("id","name","max_rows","max_columns","max_seats","hotel_room_title",
                          "hotel_room_date","hotel_free_text","display_order","creation_date","status");
}
