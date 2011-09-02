<?php
include_once("inc/dbcon.inc.php");
require_once("inc/class.dbo.php");

$user_id = $_GET["user_id"];
if(!$user_id) return;
$last_id = $user_id;
if(isset($last_id) && $last_id!="" && $last_id >0)
  {
    userGiftGroup($last_id);
    userGiftItem($last_id);
    userMenuGroup($last_id);
  }


//ENTRY USER GIFT GROUP
function userGiftGroup($user_id)
{
  $obj = new DBO();
  $query_string="SELECT * FROM spssp_gift_group_default  ORDER BY id ASC";
  $data_rows = $obj->getRowsByQuery($query_string);

  $num_user_gift_group = $obj->GetNumRows("spssp_gift_group","user_id = ".$user_id);
  if((int)$num_user_gift_group <=0)
    {

      foreach($data_rows as $gr)
        {
          unset($gr['id']);
          $gr['user_id'] = $user_id;
          $lid = $obj->InsertData("spssp_gift_group", $gr);
        }
    }
}
//ENTRY usER GIFT ITEM
function userGiftItem($user_id)
{
  $obj = new DBO();
  $query_string="SELECT * FROM spssp_gift_item_default  ORDER BY id ASC";
  $gift_rows = $obj->getRowsByQuery($query_string);
  $num_user_gift = $obj->GetNumRows("spssp_gift","user_id = ".$user_id);
  if((int)$num_user_gift <= 0)
    {
      foreach($gift_rows as $gf)
        {
          unset($gf['id']);
          $gf['user_id'] = $user_id;
          $values['name'] = '';
          $values['user_id'] = $user_id;

          //$lgid = $obj->InsertData("spssp_gift", $gf);
          $lgid = $obj->InsertData("spssp_gift", $values);
        }
    }
}
function userMenuGroup($user_id)
{
  $obj = new DBO();
  $query_string="SELECT * FROM spssp_menu_criteria  ORDER BY id ASC limit 1";
  $menu_criteria = $obj->GetFields("spssp_menu_criteria",'num_menu_groups '," id =1" );

  $num_user_gift = $obj->GetNumRows("spssp_menu_group","user_id = ".$user_id);
  if((int)$num_user_gift<=0)
    {
      for($i=1;$i<=$menu_criteria[0]['num_menu_groups'];$i++)
        {
          /*$gf['name'] = MENU_GROUP_NAME." ".$i ;
            $gf['description'] = MENU_GROUP_DESCRIPTION ;
            $gf['user_id'] = $user_id;
            $lgid = $obj->InsertData("spssp_menu_group", $gf);*/
          $gfvalue['name'] = '' ;
          $gfvalue['description'] = MENU_GROUP_DESCRIPTION ;
          $gfvalue['user_id'] = $user_id;
          $lgid = $obj->InsertData("spssp_menu_group", $gfvalue);
        }
    }
}
