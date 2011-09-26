<?php
require_once("inc/include_class_files.php");
include_once("inc/dbcon.inc.php");
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
        $party_rooms_name = $obj->GetSingleData("spssp_party_room","name"," id=".$party_rooms[0]['party_room_id']);
        if($party_rooms_name!="")
          {
            $sql = "update spssp_party_room set name ='".$post['party_room_id']."' where id=".$party_rooms[0]['party_room_id'];
            mysql_query($sql);
            $post['party_room_id'] = $party_rooms[0]['party_room_id'];
          }
        else
          {
            $sql ="insert into spssp_party_room (religion_id,name) values (".$post['religion'].",'".$post['party_room_id']."')";
            mysql_query($sql);
            $post['party_room_id']= mysql_insert_id();
          }
      }

    $post['marriage_day_with_time'] =  $post['marriage_hour'].":".$post['marriage_minute'];
    $post['party_day_with_time'] = $post['party_hour'].":".$post['party_minute'];

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

    $obj->UpdateData("spssp_guest",$guest_array," user_id=".$user_id." and sex='Male' and self=1");

    $guest_array2['first_name']=$post['woman_firstname'];
    $guest_array2['last_name']=$post['woman_lastname'];

    $obj->UpdateData("spssp_guest",$guest_array2," user_id=".$user_id." and sex='Female' and self=1");

    set_user_gaiji_position($user_id,$post["man_firstname"],0,$_POST["male_first_gaiji_img"],$_POST["male_first_gaiji_gsid"]);
    set_user_gaiji_position($user_id,$post["man_lastname"],1,$_POST["male_last_gaiji_img"],$_POST["male_last_gaiji_gsid"]);
    set_user_gaiji_position($user_id,$post["woman_firstname"],2,$_POST["female_first_gaiji_img"],$_POST["female_first_gaiji_gsid"]);
    set_user_gaiji_position($user_id,$post["woman_lastname"],3,$_POST["female_last_gaiji_img"],$_POST["female_last_gaiji_gsid"]);

    //外字およびpdf生成に必要なファイルの作成
    make_user_images($user_id,$post["man_lastname"],$post["man_firstname"],$post["woman_lastname"],$post["woman_firstname"],$_POST["male_last_gaiji_img"],$_POST["male_first_gaiji_img"],$_POST["female_last_gaiji_img"],$_POST["female_first_gaiji_img"]);

	$menu_groups = $obj->GetAllRowsByCondition("spssp_gift","user_id=".$user_id);
	$count_item = count($menu_groups);
    for($i=1;$i<=$count_item;$i++)
	{
		unset($array);
		$array['name'] = $gift_post[$i];
		$obj->UpdateData("spssp_gift", $array," user_id=".$user_id." and id=".(int)$gift_post_id[$i]);
	}
	$menu_groups = $obj->GetAllRowsByCondition("spssp_gift_group","user_id=".$user_id);
	$count_group = count($menu_groups);
	for($i=1;$i<=$count_group;$i++)
	{
		$array['name'] = $group_post[$i];
		$obj->UpdateData("spssp_gift_group", $array," user_id=".$user_id." and id=".(int)$group_post_id[$i]);
	}
	$menu_groups = $obj->GetAllRowsByCondition("spssp_menu_group","user_id=".$user_id);
	$count_child = count($menu_groups);
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
    $post['stuff_id'] = (int)$_SESSION['adminid'];

    $post['marriage_day_with_time'] =  $post['marriage_hour'].":".$post['marriage_minute'];
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
//	$post['subcription_mail'] = 1; // UCHIDA EDIT 11/08/08 受信しないをデフォルト

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
    $guest_array['sex']='Male';
    $guest_array['self']=1;
    $guest_array['stage']=1;
    $guest_array['user_id']=$last_id;

    $obj->InsertData("spssp_guest",$guest_array);

    $guest_array2['first_name']=$post['woman_firstname'];
    $guest_array2['last_name']=$post['woman_lastname'];
    $guest_array2['sex']='Female';
    $guest_array2['self']=1;
    $guest_array2['stage']=1;
    $guest_array2['user_id']=$last_id;
    $obj->InsertData("spssp_guest",$guest_array2);
    //insert EDIT USER AS GUEST


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
      // 印刷会社へメール送信
      include("inc/main_dbcon.inc.php");
	  $hcode=$HOTELID;
	  $hotel_name = $obj->GetSingleData(" super_spssp_hotel ", " hotel_name ", " hotel_code=".$hcode);
	  include("inc/return_dbcon.inc.php");
      $objMail = new MailClass();
      $r=$objMail->process_mail_user_newentry($user_id, $plan_print_company, $plan_product_name, $plan_dowload_options, $plan_print_size, $plan_print_type, $hotel_name);
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
 /*
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
*/
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
/*
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
*/
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
/*
  $query_string="SELECT * FROM spssp_menu_criteria  ORDER BY id ASC limit 1";
  $menu_criteria = $obj->GetFields("spssp_menu_criteria",'num_menu_groups '," id =1" );

  $num_user_gift = $obj->GetNumRows("spssp_menu_group","user_id = ".$user_id);
  if((int)$num_user_gift<=0)
    {
      for($i=1;$i<=$menu_criteria[0]['num_menu_groups'];$i++)
        {
          $gfvalue['name'] = '' ;
          $gfvalue['description'] = MENU_GROUP_DESCRIPTION ;
          $gfvalue['user_id'] = $user_id;
          $lgid = $obj->InsertData("spssp_menu_group", $gfvalue);
        }
    }
*/
}

//////////////////////////////////////////////
//create image from text.Function's
//////////////////////////////////////////////
function make_name_png($prefix,$user_id,$user_name,$hotel_id,$male_first_gaiji_img = false,$male_first_gaiji_gid = false,$male_first_gaiji_gsid = false){
  ///////////////////////////////
  //debug comment.
  ///////////////////////////////
  // font size = small
  //font size [small = 4,midium = 5,large=6]
  //
  if(stripos($user_name, "＊")!==false && !is_array($male_first_gaiji_img))
    {
      return false;
    }

  if($prefix == "man_firstname.png")
    $target_type = 0;
  else if($prefix == "man_lastname.png")
    $target_type = 1;
  else if($prefix == "woman_firstname.png")
    $target_type = 2;
  else if($prefix == "woman_lastname.png")
    $target_type = 3;

  $size_trgt     = get_font_size(4,$hotel_id);

  $base_line_top = (int)($size_trgt / 1.3);
  $font_size     = (int)($base_line_top / 1.2);
  $font_size_array = array(); 			// Mr tsugisawa san

  $font = "../fonts/msmincho.ttc";
  $file = sprintf("%s/user_name/%d/%s",get_image_db_directory($hotel_id),$user_id,$prefix);

  $thumb1 = sprintf("%s/user_name/%d/thumb1/%s",get_image_db_directory($hotel_id),$user_id,$prefix);
  $thumb2 = sprintf("%s/user_name/%d/thumb2/%s",get_image_db_directory($hotel_id),$user_id,$prefix);

  $dir  = sprintf("%s/user_name/%d",get_image_db_directory($hotel_id),$user_id);
  $dir1 = sprintf("%s/user_name/%d/thumb1",get_image_db_directory($hotel_id),$user_id);
  $dir2 = sprintf("%s/user_name/%d/thumb2",get_image_db_directory($hotel_id),$user_id);

  mkdir($dir);
  mkdir($dir1);
  mkdir($dir2);


  $info = gd_info();
  if($info['JIS-mapped Japanese Font Support']){
    $str = $user_name;
  }else{
    $str = mb_convert_encoding($user_name,"utf-8");
  }
  $len = mb_strlen($str,'utf-8');

  //caluclate total width.			// Mr tsugisawa san
  $total_width = 0;
  for($i=0; $i<strlen($text); $i++){
    //$text_to_write=urldecode(substr($text,$i,1)."%0D_");
    $dimensions = imagettfbbox($font_size, 0, $font, mb_substr($str,$i,1));
    $total_width+=($dimensions[2]); 			// Mr tsugisawa san
    $font_size_array[] = (int)($dimensions[2]);
  }


  if ($len > 0){
    $image = imagecreatetruecolor((int)($len * $size_trgt),(int)$size_trgt) or die("Cannot Initialize new GD image stream");
    $col_g = imagecolorallocate($image, 255, 255, 255);//imagecolorallocate($image,0xff,0xff,0xff);
    $col_t = imagecolorallocate($image,000,000,000);
    $col_b = imagecolorallocate($image,255, 255, 255);

    //imagecolortransparent($image, $col_g);
    imagefill($image,0,0,$col_g);

    //imagerectangle($image,2,2,$len * $size_trgt - 2,$size_trgt - 2,$col_g);

    error_log(sprintf("imagettftext=%s\n",$str), 3, './gaizi.log');
    $i=0;
    $n = 0;
    $draw_start_left = 0;// Mr tsugisawa san

    for($n = 0;$n < $len;$n++){
      $charcode = (int)hexdec(bin2hex(mb_substr($str,$n,1)));
      error_log(sprintf("for debug charsetcoe=(%d)%s[%d]\n",$n,$str,$charcode), 3, './gaizi.log');
      if ($charcode >= 0xEE8080 and $charcode <= 0xEFA3BF){
        //////////////////////////////////////
        //TODO:comment
        //gaizi id =gaizi file

        error_log(sprintf("is gaizi char =(%d)%s\n",$n,mb_substr($str,$n,1)), 3, './gaizi.log');
        imagettftext($image,$font_size,0,$n * $size_trgt,$base_line_top,$col_t,$font,"??");
      }
      else if(mb_substr($str,$n,1)=="＊" && is_array($male_first_gaiji_img))
        {
          //echo "../gaiji/upload/img_ans/".$male_first_gaiji_img[$i];

          //and insert
          $gaizi_detail_sql = "insert into spssp_gaizi_detail_for_user(gu_id,gu_trgt_type,gu_char_position,gu_char_img,gu_char_setcode) values(" .$user_id. "," .$target_type. "," .$n. ",'".$male_first_gaiji_img[$i]."'," .$charcode. ");";
          mysql_query($gaizi_detail_sql);


          $image_bottom_info = getimagesize("../gaiji/upload/img_ans/".$male_first_gaiji_img[$i]);

          $image_bottom_type = $image_bottom_info[2];
          $image_bottom_width = $image_bottom_info[0];
          $image_bottom_height = $image_bottom_info[1];


          if($image_bottom_type==IMAGETYPE_PNG)
            {
              $bottom= imagecreatefrompng("../gaiji/upload/img_ans/".$male_first_gaiji_img[$i]);

              $thumb = imagecreate(25, 25);


              imagecopyresized($thumb, $bottom, 0, 0, 0, 0, 25, 25, $image_bottom_width, $image_bottom_height);


              imagecopy($image, $thumb, $n * $size_trgt, 3,0, 0, 25, 25);
            }
          $i++;
        }
      else{
        imagettftext($image,$font_size,0,$n * $size_trgt,$base_line_top,$col_t,$font,mb_substr($str,$n,1));
        error_log(sprintf("is normal char=(%d)%s\n",$n,mb_substr($str,$n,1)), 3, './gaizi.log');
      }
      $draw_start_left += $font_size_array[$n];

    }

    // imagecolordeallocate($image,$col_g);
    //imagecolordeallocate($image,$col_g);
    imagepng($image,$file);
    imagedestroy($image);

    if($prefix=="woman_lastname_respect.png" || $prefix=="man_lastname_respect.png" || $prefix=="man_lastname.png" ||
       $prefix=="woman_lastname.png")
      {
        $width1 = $len*12;
        $width2 = $len*10;


        $image = new Image($file);
        $image->scale($width1, 15,array('force' => true));
        $image->output($thumb1);

        $image2 = new Image($file);
        $image->scale($width2, 15,array('force' => true));
        //$image2->width=70;
        $image2->output($thumb2);
      }
  }
}

function make_fullname_png($prefix,$firstname,$lastname,$repect,$user_id,$hotel_id=1)
{
  $file = sprintf("%s/user_name/%d/%s",get_image_db_directory($hotel_id),$user_id,$prefix);

  $thumb1 = sprintf("%s/user_name/%d/thumb1/%s",get_image_db_directory($hotel_id),$user_id,$prefix);

  $thumb2 = sprintf("%s/user_name/%d/thumb2/%s",get_image_db_directory($hotel_id),$user_id,$prefix);

  $dir1 = sprintf("%s/user_name/%d/thumb1",get_image_db_directory($hotel_id),$user_id);

  $dir2 = sprintf("%s/user_name/%d/thumb2",get_image_db_directory($hotel_id),$user_id);
  $dir  = sprintf("%s/user_name/%d",get_image_db_directory($hotel_id),$user_id);
  mkdir($dir);
  mkdir($dir1);
  mkdir($dir2);



  $image_bottom_info1 = getimagesize($dir."/".$firstname.".png");
  $image_bottom_info2 = getimagesize($dir."/".$lastname.".png");
  $image_bottom_info3 = getimagesize($dir."/".$repect.".png");

  $image_bottom_type1 = $image_bottom_info1[2];
  $image_bottom_width1 = $image_bottom_info1[0];
  $image_bottom_height1 = $image_bottom_info1[1];

  $image_bottom_type2 = $image_bottom_info2[2];
  $image_bottom_width2 = $image_bottom_info2[0];
  $image_bottom_height2 = $image_bottom_info2[1];

  $image_bottom_type3 = $image_bottom_info3[2];
  $image_bottom_width3 = $image_bottom_info3[0];
  $image_bottom_height3 = $image_bottom_info3[1];



  $info = gd_info();
  $image = imagecreatetruecolor((int)($image_bottom_width1+$image_bottom_width2+$image_bottom_width3+10),$image_bottom_height1) or die("Cannot Initialize new GD image stream");
  $col_g = imagecolorallocate($image,0xff,0xff,0xff);
  $col_t = imagecolorallocate($image,0x00,0x00,0x00);
  imagefill($image,0,0,$col_g);


  $bottom1= imagecreatefrompng($dir."/".$firstname.".png");
  $bottom2= imagecreatefrompng($dir."/".$lastname.".png");
  $bottom3= imagecreatefrompng($dir."/".$repect.".png");


  imagecopy($image, $bottom1, 0, 0,0, 0, $image_bottom_width1, $image_bottom_height1);
  imagecopy($image, $bottom2, $image_bottom_width1+4, 0,0, 0, $image_bottom_width2, $image_bottom_height1);
  imagecopy($image, $bottom3, $image_bottom_width1+4+$image_bottom_width2+4, 0,0, 0, $image_bottom_width3, $image_bottom_height1);

  imagecolordeallocate($image,$col_g);
  imagecolordeallocate($image,$col_t);
  imagepng($image,$file);
  imagedestroy($image);

  $image = new Image($file);
  //$image->width=100;
  $image->scale(100, 17,array('force' => true));
  $image->output($thumb1);;

  $image2 = new Image($file);
  //$image2->width=70;
  $image->scale(79, 15,array('force' => true));
  $image2->output($thumb2);

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
 </form>
 </div>
<?php
echo "　　";
	echo "<script type='text/javascript'>";
	echo "document.user_info_plan.submit();";
	echo "</script>";
?>