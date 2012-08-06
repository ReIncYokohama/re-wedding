<?php
//Model_Roomと構造が似ている。重複している可能性高い
class Model_Adminplan extends Model_Crud{
  static $_table_name = "spssp_default_plan";
  static $_fields = array("id","name","room_id","row_number",
                          "column_number","seat_number","create_date","display_order");
  
}
