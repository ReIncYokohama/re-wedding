<?php

	include_once("inc/dbcon.inc.php");	
	include_once("inc/checklogin.inc.php");
	require_once("inc/class.dbo.php");
	$obj = new DBO();
	
	$post = $obj->protectXSS($_POST);
	$get = $obj->protectXSS($_GET);
	
	$user_id = (int)$get['user_id'];
	//echo "party_day_with_time";
	//echo "<pre>";
	//print_r($post);
	//exit;
	
	
	
	
	if(isset($user_id) && $user_id > 0)
	{
		if($post['mail'])
		{
			if(checkEmail($user_id,$post['mail'])>0)
			{
				redirect("user_info.php?user_id=$user_id&err=18");
				exit;
			}
		}
		if(checkUserID($user_id,$post['user_id'])>0)
		{
			redirect("user_info.php?user_id=$user_id&err=20");
			exit;
		}
		
		
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
		
		//$post['marriage_day'] = $post['marriage_year']."-".$post['marriage_month']."-".$post['marriage_day'];
		$post['marriage_day_with_time'] =  $post['marriage_hour'].":".$post['marriage_minute'];
		//$post['party_day'] = $post['party_year']."-".$post['party_month']."-".$post['party_day'];
		$post['party_day_with_time'] = $post['party_hour'].":".$post['party_minute'];
		
		//unset($post['marriage_year']);
		//unset($post['marriage_month']);
		
		if($post['current_room_id']!=$post['room_id'])
		{
			$obj->DeleteRow("spssp_table_layout","user_id= ".(int)$user_id);
			$user_plan =  $obj->GetSingleRow("spssp_plan"," user_id=".(int)$user_id);
			$obj->DeleteRow("spssp_plan_details"," plan_id='".(int)$user_plan['id']."'");
			
			$obj->DeleteRow("spssp_plan","user_id= ".(int)$user_id);
			
		}
		unset($post['marriage_hour']);
		unset($post['marriage_minute']);
		//unset($post['party_year']);
		//unset($post['party_month']);
		unset($post['party_hour']);
		unset($post['party_minute']);	
		
		unset($post['current_room_id']);	
		
		unset($post['commail']);
		
		
		$obj->UpdateData("spssp_user",$post," id=".$user_id);
		
		unset($post['confirm_day_num']);
		
		//EDIT USER AS GUEST
		$guest_array['first_name']=$post['man_firstname'];
		$guest_array['last_name']=$post['man_lastname'];
		
		
		$obj->UpdateData("spssp_guest",$guest_array," user_id=".$user_id." and sex='Male' and self=1");
		 
		 
		
		$guest_array2['first_name']=$post['woman_firstname'];
		$guest_array2['last_name']=$post['woman_lastname'];
		
		
		$obj->UpdateData("spssp_guest",$guest_array2," user_id=".$user_id." and sex='Female' and self=1");
		
		//END EDIT USER AS GUEST
		
		
		redirect("user_info.php?user_id=$user_id");
	
	}
	else
	{	
		if(checkEmail($user_id,$post['mail'])>0)
		{
			redirect("manage.php?err=18");
		}
		
		$sql ="insert into spssp_party_room (religion_id,name) values (".$post['religion'].",'".$post['party_room_id']."')";
		mysql_query($sql);
		$post['party_room_id']= mysql_insert_id();
		$post['creation_date'] = date("Y-m-d");
		$post['stuff_id'] = (int)$_SESSION['adminid'];
		
		//$post['marriage_day'] = $post['marriage_year']."-".$post['marriage_month']."-".$post['marriage_day'];
		$post['marriage_day_with_time'] =  $post['marriage_hour'].":".$post['marriage_minute'];
		//$post['party_day'] = $post['party_year']."-".$post['party_month']."-".$post['party_day'];
		$post['party_day_with_time'] = $post['party_hour'].":".$post['party_minute'];
		
		//unset($post['marriage_year']);
		//unset($post['marriage_month']);
		unset($post['marriage_hour']);
		unset($post['marriage_minute']);
		//unset($post['party_year']);
		//unset($post['party_month']);
		unset($post['party_hour']);
		unset($post['party_minute']);	
		unset($post['commail']);
		
		$user_login_name = "aa".rand();
		$post['user_id'] = $user_login_name;
		$post['password'] = rand();
		$post['confirm_day_num'] = $obj->GetSingleData("spssp_options" ,"option_value" ," option_name='confirm_day_num'");
		
		$last_id = $obj->InsertData("spssp_user",$post);
		
		//make any user name image .png.
//		make_name_png("man_firstname",$last_id,$post['man_firstname'],1)
//		make_name_png("man_lastname",$last_id,$post['man_lastname'],1)
//		make_name_png("woman_firstname",$last_id,$post['woman_firstname'],1)
//		make_name_png("woman_lastname",$last_id,$post['woman_lastname'],1)
		
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
			userGiftGroup($last_id);
			userGiftItem($last_id);
			userMenuGroup($last_id);
		}
		
		
		
		
		
		if($last_id > 0)
		{
			redirect("manage.php?msg=1");
		}
		else
		{
			redirect("newuser.php?err=1");
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
	function make_name_png($prefix,$user_id,$user_name,$hotel_id)
	{
	/*
		//for debug font size = small and hotelid = 1
		//font size [small = 4,midium = 5,large=6]
		$size_trgt     = get_font_size(4,1);
		$base_line_top = (int)($size_trgt / 1.3);
		$font_size     = (int)($base_line_top / 1.2);
		$font = "/www/7073707073dev/fonts/msmincho.ttc";
		$file = sprintf("%s/user_name/%d/%s",get_image_db_directory(1),$user_id,$prefix);
		
		$info = gd_info();
		if($info['JIS-mapped Japanese Font Support']){
			$str = $user_name;
		}else{
			$str = mb_convert_encoding($user_name,"utf-8");
		}
		$len = mb_strlen($str,'utf-8');
		if ($len > 0){
			$image = imagecreatetruecolor((int)($len * $size_trgt),(int)$size_trgt) or die("Cannot Initialize new GD image stream");
			$col_g = imagecolorallocate($image,0xff,0xff,0xff);
			$col_t = imagecolorallocate($image,0x00,0x00,0x00);
			imagefill($image,0,0,$col_g);
			imagerectangle($image,2,2,$len * $size_trgt - 2,$size_trgt - 2,$col_t);
			
            $n = 0;
            for($n = 0;$n < $len;$n++){
              $charcode = (int)hexdec(bin2hex(mb_substr($str,$n,1)));
              if ($charcode >= 0xEE8080 and $charcode <= 0xEFA3BF){
                  imagettftext($image,$font_size,0,$n * $size_trgt,$base_line_top,$col_t,$font,"–");
//                imagestring($image,$font_size,$n * $size_trgt,0,"–",$col_t);
              }else{
                  imagettftext($image,$font_size,0,$n * $size_trgt,$base_line_top,$col_t,$font,mb_substr($str,$n,1));
//                imagestring($image,$font_size,$n * $size_trgt,0,mb_substr($str,$n,1),$col_t);
              }
            }
            imagepng($image,$file);
            imagecolordeallocate($image,$col_g);
            imagecolordeallocate($image,$col_t);
            imagedestroy($image);
		}
		*/
		return(0);
	}
	
	function get_font_size($font_type,$hotel_id){
		$result_font_size = 0;
		
		$obj = new DBO();
		$record_count= $obj->GetNumRows("spssp_gaizi_cfg","gc_cfg_type = 4 and gc_cscode = ".$hotel_id);
		if((int)$record_count>0){
			$result_font_size = $obj->GetFields("spssp_gaizi_cfg",'gc_nval_0',"gc_cfg_type = 4 and gc_cscode = ".$hotel_id);
		}
		return($result_font_size);
	}
	function get_image_db_directory($hotel_id){
		$result_image_db_dir = "/www/spssp_nogaiji/name_image/default/";
		
		$obj = new DBO();
		$record_count= $obj->GetNumRows("spssp_gaizi_cfg","gc_cfg_type = 3 and gc_cscode = ".$hotel_id);
		if((int)$record_count>0){
			$result_image_db_dir = $obj->GetFields("spssp_gaizi_cfg",'gc_sval_0',"gc_cfg_type = 3 and gc_cscode = ".$hotel_id);
		}
		return($result_image_db_dir);
	}
?>
