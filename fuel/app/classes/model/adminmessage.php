<?php
class Model_Adminmessage extends Model_Crud{
  static $_table_name = "spssp_admin_messages";
  static $_fields = array( "id","admin_id","title","description","message_no","field_1",
                           "field_2","creation_date","display_order","attach_file","user_view","user_id");
}
