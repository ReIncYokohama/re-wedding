<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<?php

include_once("inc/class_information.dbo.php");
include_once("inc/checklogin.inc.php");
require_once("inc/imageclass.inc.php");
include_once("../inc/gaiji.image.wedding.php");

$obj = new DBO();

$post = $obj->protectXSS($_POST);
$get = $obj->protectXSS($_GET);

$user_id = (int)$get['user_id'];

// ユーザ一括情報の整理
	unset($post['editUserGiftItemsUpdate']);
	unset($post['editUserGiftGroupsUpdate']);
	unset($post['editUserMenuGroupsUpdate']);

	unset($gift_post);
	unset($group_post);
	unset($menu_post);

	for($i=1;$i<=7;$i++) $gift_post[$i] = $post['name_gift'.$i];
	for($i=1;$i<=7;$i++) $group_post[$i] = $post['name_group'.$i];
	for($i=1;$i<=3;$i++) $menu_post[$i] = $post['menu_child'.$i];

	for($i=1;$i<=7;$i++) $gift_post_id[$i] = $post['gift_fieldId'.$i];
	for($i=1;$i<=7;$i++) $group_post_id[$i] = $post['group_fieldId'.$i];
	for($i=1;$i<=3;$i++) $menu_post_id[$i] = $post['menu_child_id'.$i];

	for($i=1;$i<=7;$i++) unset($post['name_gift'.$i]);
	for($i=1;$i<=7;$i++) unset($post['name_group'.$i]);
	for($i=1;$i<=3;$i++) unset($post['menu_child'.$i]);

	for($i=1;$i<=7;$i++) unset($post['gift_fieldId'.$i]);
	for($i=1;$i<=7;$i++) unset($post['group_fieldId'.$i]);
	for($i=1;$i<=3;$i++) unset($post['menu_child_id'.$i]);

	$plan_column_number = $post['column_number'];
	$plan_row_number = $post['row_number'];
	$plan_seat_number = $post['seat_number'];
	$plan_rename_table = $post['rename_table'];
	$plan_product_name = $post['product_name'];
	$plan_dowload_options = $post['dowload_options'];
	$plan_print_size = $post['print_size'];
	$plan_print_type = $post['print_type'];
	$plan_party_day_for_confirm = $post['party_day_for_confirm'];
	$plan_print_company = $post['print_company'];
	$room_id = $post['room_id'];

	unset($post['column_number']);
	unset($post['row_number']);
	unset($post['seat_number']);
	unset($post['rename_table']);
	unset($post['product_name']);
	unset($post['dowload_options']);
	unset($post['print_size']);
	unset($post['print_type']);
	unset($post['party_day_for_confirm']);
	unset($post['print_company']);

if(isset($user_id) && $user_id > 0)
  {
    //更新時に必要なデータだけ送信する。
    $gaizi_detail_sql = "delete from spssp_gaizi_detail_for_user where gu_id=" .$user_id. ";";
    mysql_query($gaizi_detail_sql);

    unset($post['male_first_gaiji_img']);
		unset($post['male_first_gaiji_gid']);
		unset($post['male_first_gaiji_gsid']);
		unset($post['male_last_gaiji_img']);
		unset($post['male_last_gaiji_gid']);
		unset($post['male_last_gaiji_gsid']);

		unset($post['female_first_gaiji_img']);
		unset($post['female_first_gaiji_gid']);
		unset($post['female_first_gaiji_gsid']);
		unset($post['female_last_gaiji_img']);
		unset($post['female_last_gaiji_gid']);
		unset($post['female_last_gaiji_gsid']);

    $party_rooms = $obj->GetFields("spssp_user",'party_room_id'," id=".$user_id);

    if($party_rooms[0]['party_room_id']==0 || $party_rooms[0]['party_room_id']=="")
    {
        $sql ="insert into spssp_party_room (religion_id,name) values (".$post['religion'].",'".$post['party_room_id']."')";
        mysql_query($sql);
        $post['party_room_id']= mysql_insert_id();
    }
    else
    {
    	if ($obj->GetRowCount("spssp_party_room"," id=".$party_rooms[0]['party_room_id'])>0) {
	        $sql = "update spssp_party_room set name ='".$post['party_room_id']."', religion_id='".$post['religion']."' where id=".$party_rooms[0]['party_room_id'];
	        mysql_query($sql);
	        $post['party_room_id'] = $party_rooms[0]['party_room_id'];
    	}
    	else {
            $sql ="insert into spssp_party_room (religion_id,name) values (".$post['religion'].",'".$post['party_room_id']."')";
            mysql_query($sql);
            $post['party_room_id']= mysql_insert_id();
       	}
    }

    $post['marriage_day_with_time'] =  $post['marriage_hour'].":".($post['marriage_minute']?$post["marriage_minute"]:"00");
    $post['party_day_with_time'] = $post['party_hour'].":".$post['party_minute'];

    $current_room_id = $post['current_room_id'];
    if($post['current_room_id']!=$post['room_id'])
    {
        $obj->DeleteRow("spssp_table_layout","user_id= ".(int)$user_id);
        $user_plan =  $obj->GetSingleRow("spssp_plan"," user_id=".(int)$user_id);
        $obj->DeleteRow("spssp_plan_details"," plan_id='".(int)$user_plan['id']."'");

        $obj->DeleteRow("spssp_plan","user_id= ".(int)$user_id);
    }
    unset($post['marriage_hour']);
    unset($post['marriage_minute']);
    unset($post['party_hour']);
    unset($post['party_minute']);

  	unset($post['current_room_id']);
    unset($post['con_mail']);
    unset($post['layoutname']);

    $obj->UpdateData("spssp_user",$post," id=".$user_id);

    //EDIT USER AS GUEST
    $guest_array['first_name']=$post['man_firstname'];
    $guest_array['last_name']=$post['man_lastname'];
    $guest_array['furigana_last']=$post['man_furi_lastname'];
    $guest_array['furigana_first']=$post['man_furi_firstname'];
    $guest_array['comment1']="新郎";

    $man_guest_row = $obj->GetSingleRow(" spssp_guest"," user_id=".$user_id." and sex='Male' and self=1");
    $man_guest_id = $man_guest_row["id"];
    $obj->UpdateData("spssp_guest",$guest_array," id=".$man_guest_id);

    $guest_array2['first_name']=$post['woman_firstname'];
    $guest_array2['last_name']=$post['woman_lastname'];
    $guest_array2['furigana_last']=$post['woman_furi_lastname'];
    $guest_array2['furigana_first']=$post['woman_furi_firstname'];
    $guest_array2['comment1']="新婦";

    $woman_guest_row = $obj->GetSingleRow(" spssp_guest"," user_id=".$user_id." and sex='Female' and self=1");
    $woman_guest_id = $woman_guest_row["id"];
    $obj->UpdateData("spssp_guest",$guest_array2," id=".$woman_guest_id);

    set_user_gaiji_position($user_id,$post["man_firstname"],0,$_POST["male_first_gaiji_img"],$_POST["male_first_gaiji_gsid"]);
    set_user_gaiji_position($user_id,$post["man_lastname"],1,$_POST["male_last_gaiji_img"],$_POST["male_last_gaiji_gsid"]);
    set_user_gaiji_position($user_id,$post["woman_firstname"],2,$_POST["female_first_gaiji_img"],$_POST["female_first_gaiji_gsid"]);
    set_user_gaiji_position($user_id,$post["woman_lastname"],3,$_POST["female_last_gaiji_img"],$_POST["female_last_gaiji_gsid"]);

    //外字およびpdf生成に必要なファイルの作成
    make_user_images($user_id,$post["man_lastname"],$post["man_firstname"],$post["woman_lastname"],$post["woman_firstname"],$_POST["male_last_gaiji_img"],$_POST["male_first_gaiji_img"],$_POST["female_last_gaiji_img"],$_POST["female_first_gaiji_img"]);
    //ゲストとして新郎を登録
    make_guest_images($user_id,$man_guest_id,$post["man_lastname"],$post["man_firstname"],$guest_array["comment1"],"","様",
                      $_POST["male_last_gaiji_img"],$_POST["male_first_gaiji_img"],array(),array());

    //ゲストとして新婦を登録
    make_guest_images($user_id,$woman_guest_id,$post["woman_lastname"],$post["woman_firstname"],$guest_array2["comment1"],"","様",
                      $_POST["female_last_gaiji_img"],$_POST["female_first_gaiji_img"],array(),array());



	$menu_groups = $obj->GetAllRowsByCondition("spssp_gift","user_id=".$user_id);
	$count_item = count($gift_post);
    for($i=1;$i<=$count_item;$i++)
	{
		unset($array);
		$array['name'] = $gift_post[$i];
		$obj->UpdateData("spssp_gift", $array," user_id=".$user_id." and id=".(int)$gift_post_id[$i]);
	}
	$menu_groups = $obj->GetAllRowsByCondition("spssp_gift_group","user_id=".$user_id);

	$count_group = count($group_post);
	for($i=1;$i<=$count_group;$i++)
	{
		unset($array);
		$array['name'] = $group_post[$i];
    if(!$group_post_id[$i] || $group_post_id[$i] == ""){
  	  $array['user_id']=$user_id;
      $obj->InsertData("spssp_gift_group", $array);
    }else{
      $obj->UpdateData("spssp_gift_group", $array," user_id=".$user_id." and id=".(int)$group_post_id[$i]);
      if($array["name"]==""){
        Model_Guestgroup::delete_by_user_id($user_id,$group_post_id[$i]);
      }
    }
	}
	$menu_groups = $obj->GetAllRowsByCondition("spssp_menu_group","user_id=".$user_id);
	$count_child = count($menu_post);
	for($i=1;$i<=$count_child;$i++)
	{
		unset($array);
		$array['name'] = $menu_post[$i];
		$obj->UpdateData("spssp_menu_group", $array," user_id=".$user_id." and id=".(int)$menu_post_id[$i].";");
	}

    //redirect("user_info_allentry.php?user_id=".$user_id");
  }
else
  {
		unset($post['male_first_gaiji_img']);
		unset($post['male_first_gaiji_gid']);
		unset($post['male_first_gaiji_gsid']);
		unset($post['male_last_gaiji_img']);
		unset($post['male_last_gaiji_gid']);
		unset($post['male_last_gaiji_gsid']);

		unset($post['female_first_gaiji_img']);
		unset($post['female_first_gaiji_gid']);
		unset($post['female_first_gaiji_gsid']);
		unset($post['female_last_gaiji_img']);
		unset($post['female_last_gaiji_gid']);
		unset($post['female_last_gaiji_gsid']);

	$sql ="insert into spssp_party_room (religion_id,name) values (".$post['religion'].",'".$post['party_room_id']."')";
    mysql_query($sql);
    $post['party_room_id']= mysql_insert_id();
    $post['creation_date'] = date("Y-m-d");
    $post['stuff_id'] = (int)$post['stuff_id'];

    $post['marriage_day_with_time'] =  $post['marriage_hour'].":".($post['marriage_minute']?$post["marriage_minute"]:"00");
    $post['party_day_with_time'] = $post['party_hour'].":".$post['party_minute'];

    unset($post['marriage_hour']);
    unset($post['marriage_minute']);
    unset($post['party_hour']);
    unset($post['party_minute']);
    unset($post['commail']);
	unset($post['current_room_id']);
	unset($post['con_mail']);

    $user_login_name = "aa".rand();
    $post['user_id'] = $user_login_name;
    $post['password'] = rand();

    if((int)$post['confirm_day_num']==0) $post['confirm_day_num'] = $obj->GetSingleData("spssp_options" ,"option_value" ," option_name='confirm_day_num'");
    if((int)$post['limitation_ranking']==0) $post['limitation_ranking'] = $obj->GetSingleData("spssp_options" ,"option_value" ," option_name='limitation_ranking'");
    if((int)$post['order_deadline']==0) $post['order_deadline'] = $obj->GetSingleData("spssp_options" ,"option_value" ," option_name='order_deadline'");

	$last_id = $obj->InsertData("spssp_user",$post);
    $user_id = $last_id;

    set_user_gaiji_position($user_id,$post["man_firstname"],0,$_POST["male_first_gaiji_img"],$_POST["male_first_gaiji_gsid"]);
    set_user_gaiji_position($user_id,$post["man_lastname"],1,$_POST["male_last_gaiji_img"],$_POST["male_first_gaiji_gsid"]);
    set_user_gaiji_position($user_id,$post["woman_firstname"],2,$_POST["female_first_gaiji_img"],$_POST["male_first_gaiji_gsid"]);
    set_user_gaiji_position($user_id,$post["woman_lastname"],3,$_POST["female_last_gaiji_img"],$_POST["male_first_gaiji_gsid"]);

    make_user_images($user_id,$post["man_lastname"],$post["man_firstname"],$post["woman_lastname"],$post["woman_firstname"],$_POST["male_last_gaiji_img"],$_POST["male_first_gaiji_img"],$_POST["female_last_gaiji_img"],$_POST["female_first_gaiji_img"]);

    //insert USER AS GUEST
    $guest_array['first_name']=$post['man_firstname'];
    $guest_array['last_name']=$post['man_lastname'];
    $guest_array['furigana_last']=$post['man_furi_lastname'];
    $guest_array['furigana_first']=$post['man_furi_firstname'];

    $guest_array['sex']='Male';
    $guest_array['self']=1;
    $guest_array['stage']=1;
    $guest_array['user_id']=$last_id;
    $guest_array['comment1']="新郎";
    $man_guest_id = $obj->InsertData("spssp_guest",$guest_array);

    //ゲストとして新郎を登録
    make_guest_images($user_id,$man_guest_id,$post["man_lastname"],$post["man_firstname"],$guest_array["comment1"],"","様",
                      $_POST["male_last_gaiji_img"],$_POST["male_first_gaiji_img"],array(),array());


    $guest_array2['first_name']=$post['woman_firstname'];
    $guest_array2['last_name']=$post['woman_lastname'];

    $guest_array2['furigana_last']=$post['woman_furi_lastname'];
    $guest_array2['furigana_first']=$post['woman_furi_firstname'];
    $guest_array2['sex']='Female';
    $guest_array2['self']=1;
    $guest_array2['stage']=1;
    $guest_array2['comment1']="新婦";
    $guest_array2['user_id']=$last_id;
    $woman_guest_id = $obj->InsertData("spssp_guest",$guest_array2);
    //ゲストとして新婦を登録
    make_guest_images($user_id,$woman_guest_id,$post["woman_lastname"],$post["woman_firstname"],$guest_array2["comment1"],"","様",
                      $_POST["female_last_gaiji_img"],$_POST["female_first_gaiji_img"],array(),array());

    if(isset($last_id) && $last_id!="" && $last_id >0)
      {
        userGiftGroup($last_id, $group_post);
        userGiftItem($last_id, $gift_post);
        userMenuGroup($last_id, $menu_post);
      }

    if($last_id > 0)
      {
        //redirect("manage.php?msg=1");
      }
    else
      {
        //redirect("newuser.php?err=1");
      }

  }

//CHECK EMAIL DUPLICACY
function checkEmail($user_id,$mail)
{
  $obj = new DBO();
  return $nm = $obj->GetRowCount("spssp_user"," id!='".$user_id."' and mail='".$mail."'");
}
//CHECK UserID DUPLICACY
function checkUserID($user_id,$user_name)
{
  $obj = new DBO();
  return $nm = $obj->GetRowCount("spssp_user"," id!='".$user_id."' and user_id='".$user_name."'");
}


//ENTRY USER GIFT GROUP
function userGiftGroup($user_id, $group_post)
{
  $obj = new DBO();
  $gift_criteria_data_row = $obj->GetAllRow("spssp_gift_criteria");
  $count_gift=$gift_criteria_data_row[0]['num_gift_groups'];

  for($i=1;$i<=$count_gift;$i++) {
  	unset($vl);
  	$vl['user_id']=$user_id;
  	$vl['name']=$group_post[$i];
	$lid = $obj->InsertData("spssp_gift_group", $vl);
  }
}

//ENTRY usER GIFT ITEM
function userGiftItem($user_id, $gift_post)
{
  $obj = new DBO();
  $gift_criteria_data_row = $obj->GetAllRow("spssp_gift_criteria");
  $count_gift=$gift_criteria_data_row[0]['num_gift_items'];

  for($i=1;$i<=$count_gift;$i++) {
  	unset($vl);
  	$vl['user_id']=$user_id;
  	$vl['name']=$gift_post[$i];
  	$lid = $obj->InsertData("spssp_gift", $vl);
  }
}
function userMenuGroup($user_id, $menu_post)
{
  $obj = new DBO();
  $menu_criteria_data_row = $obj->GetAllRow("spssp_menu_criteria");
  $count_child = (int)$menu_criteria_data_row[0]['num_menu_groups'];

  for($i=1;$i<=$count_child;$i++) {
  	unset($vl);
  	$vl['user_id']=$user_id;
  	$vl['name']=$menu_post[$i];
  	$lid = $obj->InsertData("spssp_menu_group", $vl);
  }
}

function get_font_size($font_type,$hotel_id){
  $result_font_size = 0;

  $query = "select gc_nval_0 as val from spssp_gaizi_cfg where gc_cfg_type = 4 and gc_cscode = ".$hotel_id;
  $result = mysql_query($query );
  $num = mysql_num_rows($result);
  if($num>0){
    while($fetchedRow = mysql_fetch_assoc($result)){
      $result_font_size = (int)$fetchedRow["val"];
    }
  }
  mysql_free_result($result);
  //
  return $result_font_size;
}

?>
<div>
 <form action="insert_user_info_plan.php?user_id=<?=$user_id?>" method="post" name="user_info_plan">
        <input type="hidden" name="column_number" id="column_number" value="<?=$plan_column_number?>" />
        <input type="hidden" name="row_number" id="row_number" value="<?=$plan_row_number?>" />
        <input type="hidden" name="seat_number" id="seat_number" value="<?=$plan_seat_number?>" />
        <input type="hidden" name="rename_table" id="rename_table" value="<?=$plan_rename_table?>" />
        <input type="hidden" name="product_name" id="product_name" value="<?=$plan_product_name?>" />
        <input type="hidden" name="dowload_options" id="dowload_options" value="<?=$plan_dowload_options?>" />
        <input type="hidden" name="print_size" id="print_size" value="<?=$plan_print_size?>" />
        <input type="hidden" name="print_type" id="print_type" value="<?=$plan_print_type?>" />
        <input type="hidden" name="party_day_for_confirm" id="party_day_for_confirm" value="<?=$plan_party_day_for_confirm?>" />
        <input type="hidden" name="print_company" id="print_company" value="<?=$plan_print_company?>" />
        <input type="hidden" name="room_id" id="room_id" value="<?=$room_id?>" />
        <input type="hidden" name="current_room_id" id="current_room_id" value="<?=$current_room_id?>" />
 </form>
 </div>
<?php
  echo "　　   ";
  echo "　　   ";
  echo "<script type='text/javascript'>";
  echo "document.user_info_plan.submit();";
  echo "</script>";
?>
</head>
</html>