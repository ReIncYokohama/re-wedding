<?php

    include_once("inc/dbcon.inc.php");  
    include_once("inc/checklogin.inc.php");
    require_once("inc/class.dbo.php");
	 require_once("inc/imageclass.inc.php");
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
        if(stripos($_POST['man_firstname'], "＊")!==false)
		{			
			$male_first_gaiji_img = $_POST['male_first_gaiji_img'];
			$male_first_gaiji_gid = $_POST['male_first_gaiji_gid'];
			$male_first_gaiji_gsid = $_POST['male_first_gaiji_gsid'];
			
			make_name_png("man_firstname.png",$user_id,$_POST['man_firstname'],1,$male_first_gaiji_img,$male_first_gaiji_gid,$male_first_gaiji_gsid);
			
		}
		else
		{			
			make_name_png("man_firstname.png",$user_id,$_POST['man_firstname'],1);
		}
		
		if(stripos($_POST['man_lastname'], "＊")!==false)
		{
			
			$male_last_gaiji_img = $_POST['male_last_gaiji_img'];
			$male_last_gaiji_gid = $_POST['male_last_gaiji_gid'];
			$male_last_gaiji_gsid = $_POST['male_last_gaiji_gsid'];
			
			make_name_png("man_lastname.png",$user_id,$_POST['man_lastname'],1,$male_last_gaiji_img,$male_last_gaiji_gid,$male_last_gaiji_gsid);
			
		}
		else
		{
			
			make_name_png("man_lastname.png",$user_id,$_POST['man_lastname'],1);
		}
		
        //make_name_png("man_firstname.png",$last_id,$post['man_firstname'],1);
       // make_name_png("man_lastname.png",$last_id,$post['man_lastname'],1);
		$fullname = sprintf("%s %s %s",$_POST['man_firstname'],$_POST['man_lastname'],"様");
error_log(sprintf("src text=%s\n",$fullname), 3, './gaizi.log');
       if(stripos($fullname, "＊")!==false)
		{
			
			if(is_array($_POST['male_first_gaiji_img']) && is_array($_POST['male_last_gaiji_img']))
				{$male_name_gaiji_img = array_merge($_POST['male_first_gaiji_img'],$_POST['male_last_gaiji_img']);}
			else if(is_array($_POST['male_first_gaiji_img']) && !is_array($_POST['male_last_gaiji_img']))
				{$male_name_gaiji_img = $_POST['male_first_gaiji_img'];}
			else if(!is_array($_POST['male_first_gaiji_img']) && is_array($_POST['male_last_gaiji_img']))	
				{$male_name_gaiji_img = $_POST['male_last_gaiji_img'];}
			
			if(is_array($_POST['male_first_gaiji_gid']) && is_array($_POST['male_last_gaiji_gid']))
				{$male_name_gaiji_gid = array_merge($_POST['male_first_gaiji_gid'],$_POST['male_last_gaiji_gid']);			}
			else if(is_array($_POST['male_first_gaiji_gid']) && !is_array($_POST['male_last_gaiji_gid']))
				{$male_name_gaiji_gid = $_POST['male_first_gaiji_gid'];}
			else if(!is_array($_POST['male_first_gaiji_gid']) && is_array($_POST['male_last_gaiji_gid']))	
				{$male_name_gaiji_gid = $_POST['male_last_gaiji_gid'];}
				
			if(is_array($_POST['male_first_gaiji_gsid']) && is_array($_POST['male_last_gaiji_gsid']))
				$male_name_gaiji_gsid = array_merge($_POST['male_first_gaiji_gsid'],$_POST['male_last_gaiji_gsid']);	
			else if(is_array($_POST['male_first_gaiji_gsid']) && !is_array($_POST['male_last_gaiji_gsid']))
				$male_name_gaiji_gsid = $_POST['male_first_gaiji_gsid'];
			else if(!is_array($_POST['male_first_gaiji_gsid']) && is_array($_POST['male_last_gaiji_gsid']))	
				$male_name_gaiji_gsid = $_POST['male_last_gaiji_gsid'];
				
					
			make_name_png("man_respect.png",$user_id,"様",1);
			make_fullname_png("man_fullname.png","man_firstname","man_lastname","man_respect",$user_id,$hotel_id=1);
			
			//make_name_png("man_fullname.png",$user_id,$fullname,1,$male_name_gaiji_img,$male_name_gaiji_gid,$male_name_gaiji_gsid);
			
		}
		else
		{			
			make_name_png("man_respect.png",$user_id,"様",1);
			make_fullname_png("man_fullname.png","man_firstname","man_lastname","man_respect",$user_id,$hotel_id=1);
			//make_name_png("man_fullname.png",$user_id,$fullname,1);
		}

	    //make_name_png("man_fullname.png",$last_id,$fullname,1);
		if(stripos($_POST['woman_firstname'], "＊")!==false)
		{
			$female_first_gaiji_img = $_POST['female_first_gaiji_img'];
			$female_first_gaiji_gid = $_POST['female_first_gaiji_gid'];
			$female_first_gaiji_gsid = $_POST['female_first_gaiji_gsid'];
			
			make_name_png("woman_firstname.png",$user_id,$_POST['woman_firstname'],1,$female_first_gaiji_img,$female_first_gaiji_gid,$female_first_gaiji_gsid);
			
		}
		else
		{
			make_name_png("woman_firstname.png",$user_id,$_POST['woman_firstname'],1);
		}	
		if(stripos($_POST['woman_lastname'], "＊")!==false)
		{
			$female_last_gaiji_img = $_POST['female_last_gaiji_img'];
			$female_last_gaiji_gid = $_POST['female_last_gaiji_gid'];
			$female_last_gaiji_gsid = $_POST['female_last_gaiji_gsid'];
			
			make_name_png("woman_lastname.png",$user_id,$_POST['woman_lastname'],1,$female_last_gaiji_img,$female_last_gaiji_gid,$female_last_gaiji_gsid);
			
		}
		else
		{
			make_name_png("woman_lastname.png",$user_id,$_POST['woman_lastname'],1);
		}	 
        //make_name_png("woman_firstname.png",$last_id,$post['woman_firstname'],1);
        //make_name_png("woman_lastname.png",$last_id,$post['woman_lastname'],1);
		$fullname = sprintf("%s %s %s",$_POST['woman_firstname'],$_POST['woman_lastname'],"様");
error_log(sprintf("src text=%s\n",$fullname), 3, './gaizi.log');
        if(stripos($fullname, "＊")!==false)
		{
			
			if(is_array($_POST['female_first_gaiji_img']) && is_array($_POST['female_last_gaiji_img']))
				$female_name_gaiji_img = array_merge($_POST['female_first_gaiji_img'],$_POST['female_last_gaiji_img']);	
			else if(is_array($_POST['female_first_gaiji_img']) && !is_array($_POST['female_last_gaiji_img']))
				$female_name_gaiji_img = $_POST['female_first_gaiji_img'];
			else if(!is_array($_POST['female_first_gaiji_img']) && is_array($_POST['female_last_gaiji_img']))	
				$female_name_gaiji_img = $_POST['female_last_gaiji_img'];
				
			if(is_array($_POST['female_first_gaiji_gid']) && is_array($_POST['female_last_gaiji_gid']))
				$female_name_gaiji_gid = array_merge($_POST['female_first_gaiji_gid'],$_POST['female_last_gaiji_gid']);	
			else if(is_array($_POST['female_first_gaiji_gid']) && !is_array($_POST['female_last_gaiji_gid']))
				$female_name_gaiji_gid = $_POST['female_first_gaiji_gid'];
			else if(!is_array($_POST['female_first_gaiji_gid']) && is_array($_POST['female_last_gaiji_gid']))	
				$female_name_gaiji_gid = $_POST['female_last_gaiji_gid'];
			
			if(is_array($_POST['female_first_gaiji_gsid']) && is_array($_POST['female_last_gaiji_gsid']))
				$female_name_gaiji_gsid = array_merge($_POST['female_first_gaiji_gsid'],$_POST['female_last_gaiji_gsid']);	
			else if(is_array($_POST['female_first_gaiji_gsid']) && !is_array($_POST['female_last_gaiji_gsid']))
				$female_name_gaiji_gsid = $_POST['female_first_gaiji_gsid'];
			else if(!is_array($_POST['female_first_gaiji_gsid']) && is_array($_POST['female_last_gaiji_gsid']))	
				$female_name_gaiji_gsid = $_POST['female_last_gaiji_gsid'];
			
			make_name_png("woman_respect.png",$user_id,"様",1);
			make_fullname_png("woman_fullname.png","woman_firstname","woman_lastname","woman_respect",$user_id,$hotel_id=1);			
			//make_name_png("woman_fullname.png",$user_id,$fullname,1,$female_name_gaiji_img,$female_name_gaiji_gid,$female_name_gaiji_gsid);
			
		}
		else
		{
			make_name_png("woman_respect.png",$user_id,"様",1);
			make_fullname_png("woman_fullname.png","woman_firstname","woman_lastname","woman_respect",$user_id,$hotel_id=1);
			//make_name_png("woman_fullname.png",$user_id,$fullname,1);
		}	
        
        redirect("user_info.php?user_id=$user_id");
    
    }
    else
    {   
       /* if(checkEmail($user_id,$post['mail'])>0)
        {
            redirect("manage.php?err=18");
        }*/
       
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
        //$last_id = 233;
        //make any user name image .png.
		if(stripos($_POST['man_firstname'], "＊")!==false)
		{			
			$male_first_gaiji_img = $_POST['male_first_gaiji_img'];
			$male_first_gaiji_gid = $_POST['male_first_gaiji_gid'];
			$male_first_gaiji_gsid = $_POST['male_first_gaiji_gsid'];
			
			make_name_png("man_firstname.png",$last_id,$_POST['man_firstname'],1,$male_first_gaiji_img,$male_first_gaiji_gid,$male_first_gaiji_gsid);
		}
		else
		{			
			make_name_png("man_firstname.png",$last_id,$_POST['man_firstname'],1);
		}
		
		if(stripos($_POST['man_lastname'], "＊")!==false)
		{
			
			$male_last_gaiji_img = $_POST['male_last_gaiji_img'];
			$male_last_gaiji_gid = $_POST['male_last_gaiji_gid'];
			$male_last_gaiji_gsid = $_POST['male_last_gaiji_gsid'];
			if(is_array($male_last_gaiji_img) && is_array($male_last_gaiji_gid) && is_array($male_last_gaiji_gsid))
			{
				make_name_png("man_lastname.png",$last_id,$_POST['man_lastname'],1,$male_last_gaiji_img,$male_last_gaiji_gid,$male_last_gaiji_gsid);
				make_name_png("man_lastname_respect.png",$last_id,$_POST['man_lastname']."様",1,$male_last_gaiji_img,$male_last_gaiji_gid,$male_last_gaiji_gsid);
			}
		}
		else
		{			
			make_name_png("man_lastname.png",$last_id,$_POST['man_lastname'],1);
			make_name_png("man_lastname_respect.png",$last_id,$_POST['man_lastname']."様",1);
		}
		
        //make_name_png("man_firstname.png",$last_id,$post['man_firstname'],1);
       // make_name_png("man_lastname.png",$last_id,$post['man_lastname'],1);
		$fullname = sprintf("%s %s %s",$_POST['man_firstname'],$_POST['man_lastname'],"様");
error_log(sprintf("src text=%s\n",$fullname), 3, './gaizi.log');
       if(stripos($fullname, "＊")!==false)
		{
			if(is_array($_POST['male_first_gaiji_img']) && is_array($_POST['male_last_gaiji_img']))
				$male_name_gaiji_img = array_merge($_POST['male_first_gaiji_img'],$_POST['male_last_gaiji_img']);	
			else if(is_array($_POST['male_first_gaiji_img']) && !is_array($_POST['male_last_gaiji_img']))
				$male_name_gaiji_img = $_POST['male_first_gaiji_img'];
			else if(!is_array($_POST['male_first_gaiji_img']) && is_array($_POST['male_last_gaiji_img']))	
				$male_name_gaiji_img = $_POST['male_last_gaiji_img'];
			
			
			if(is_array($_POST['male_first_gaiji_gid']) && is_array($_POST['male_last_gaiji_gid']))
				$male_name_gaiji_gid = array_merge($_POST['male_first_gaiji_gid'],$_POST['male_last_gaiji_gid']);	
			else if(is_array($_POST['male_first_gaiji_gid']) && !is_array($_POST['male_last_gaiji_gid']))
				$male_name_gaiji_gid = $_POST['male_first_gaiji_gid'];
			else if(!is_array($_POST['male_first_gaiji_gid']) && is_array($_POST['male_last_gaiji_gid']))	
				$male_name_gaiji_gid = $_POST['male_last_gaiji_gid'];
				
			if(is_array($_POST['male_first_gaiji_gsid']) && is_array($_POST['male_last_gaiji_gsid']))
				$male_name_gaiji_gsid = array_merge($_POST['male_first_gaiji_gsid'],$_POST['male_last_gaiji_gsid']);
			else if(is_array($_POST['male_first_gaiji_gsid']) && !is_array($_POST['male_last_gaiji_gsid']))
				$male_name_gaiji_gsid = $_POST['male_first_gaiji_gsid'];
			else if(!is_array($_POST['male_first_gaiji_gsid']) && is_array($_POST['male_last_gaiji_gsid']))	
				$male_name_gaiji_gsid = $_POST['male_last_gaiji_gsid'];
			
			make_name_png("man_respect.png",$last_id,"様",1);
			make_fullname_png("man_fullname.png","man_firstname","man_lastname","man_respect",$last_id,$hotel_id=1);
		
			
		}
		else
		{
			make_name_png("man_respect.png",$last_id,"様",1);
			make_fullname_png("man_fullname.png","man_firstname","man_lastname","man_respect",$last_id,$hotel_id=1);
			//make_name_png("man_fullname.png",$last_id,$fullname,1);
		}
     //make_name_png("man_fullname.png",$last_id,$fullname,1);
		if(stripos($_POST['woman_firstname'], "＊")!==false)
		{
			$female_first_gaiji_img = $_POST['female_first_gaiji_img'];
			$female_first_gaiji_gid = $_POST['female_first_gaiji_gid'];
			$female_first_gaiji_gsid = $_POST['female_first_gaiji_gsid'];
			
			make_name_png("woman_firstname.png",$last_id,$_POST['woman_firstname'],1,$female_first_gaiji_img,$female_first_gaiji_gid,$female_first_gaiji_gsid);
			
		}
		else
		{
			make_name_png("woman_firstname.png",$last_id,$_POST['woman_firstname'],1);
		}	
		if(stripos($_POST['woman_lastname'], "＊")!==false)
		{
			$female_last_gaiji_img = $_POST['female_last_gaiji_img'];
			$female_last_gaiji_gid = $_POST['female_last_gaiji_gid'];
			$female_last_gaiji_gsid = $_POST['female_last_gaiji_gsid'];
			
			make_name_png("woman_lastname.png",$last_id,$_POST['woman_lastname'],1,$female_last_gaiji_img,$female_last_gaiji_gid,$female_last_gaiji_gsid);
			make_name_png("woman_lastname_respect.png",$last_id,$_POST['woman_lastname']."様",1,$female_last_gaiji_img,$female_last_gaiji_gid,$female_last_gaiji_gsid);
			
		}
		else
		{
			make_name_png("woman_lastname.png",$last_id,$_POST['woman_lastname'],1);
			make_name_png("woman_lastname_respect.png",$last_id,$_POST['woman_lastname']."様",1);
		}	 
        //make_name_png("woman_firstname.png",$last_id,$post['woman_firstname'],1);
        //make_name_png("woman_lastname.png",$last_id,$post['woman_lastname'],1);
		$fullname = sprintf("%s %s %s",$_POST['woman_firstname'],$_POST['woman_lastname'],"様");
error_log(sprintf("src text=%s\n",$fullname), 3, './gaizi.log');
        if(stripos($fullname, "＊")!==false)
		{
			if(is_array($_POST['female_first_gaiji_img']) && is_array($_POST['female_last_gaiji_img']))
				$female_name_gaiji_img = array_merge($_POST['female_first_gaiji_img'],$_POST['female_last_gaiji_img']);	
			else if(is_array($_POST['female_first_gaiji_img']) && !is_array($_POST['female_last_gaiji_img']))
				$female_name_gaiji_img = $_POST['female_first_gaiji_img'];
			else if(!is_array($_POST['female_first_gaiji_img']) && is_array($_POST['female_last_gaiji_img']))	
				$female_name_gaiji_img = $_POST['female_last_gaiji_img'];
			
			if(is_array($_POST['female_first_gaiji_gid']) && is_array($_POST['female_last_gaiji_gid']))
				$female_name_gaiji_gid = array_merge($_POST['female_first_gaiji_gid'],$_POST['female_last_gaiji_gid']);	
			else if(is_array($_POST['female_first_gaiji_gid']) && !is_array($_POST['female_last_gaiji_gid']))
				$female_name_gaiji_gid = $_POST['female_first_gaiji_gid'];
			else if(!is_array($_POST['female_first_gaiji_gid']) && is_array($_POST['female_last_gaiji_gid']))	
				$female_name_gaiji_gid = $_POST['female_last_gaiji_gid'];
				
			if(is_array($_POST['female_first_gaiji_gsid']) && is_array($_POST['female_last_gaiji_gsid']))
				$female_name_gaiji_gsid = array_merge($_POST['female_first_gaiji_gsid'],$_POST['female_last_gaiji_gsid']);	
			else if(is_array($_POST['female_first_gaiji_gsid']) && !is_array($_POST['female_last_gaiji_gsid']))
				$female_name_gaiji_gsid = $_POST['female_first_gaiji_gsid'];
			else if(!is_array($_POST['female_first_gaiji_gsid']) && is_array($_POST['female_last_gaiji_gsid']))	
				$female_name_gaiji_gsid = $_POST['female_last_gaiji_gsid'];
			
			make_name_png("woman_respect.png",$last_id,"様",1);
			make_fullname_png("woman_fullname.png","woman_firstname","woman_lastname","woman_respect",$last_id,$hotel_id=1);
			//make_name_png("woman_fullname.png",$last_id,$fullname,1,$female_name_gaiji_img,$female_name_gaiji_gid,$female_name_gaiji_gsid);
			
		}
		else
		{
			make_name_png("woman_respect.png",$last_id,"様",1);
			make_fullname_png("woman_fullname.png","woman_firstname","woman_lastname","woman_respect",$last_id,$hotel_id=1);
			//make_name_png("woman_fullname.png",$last_id,$fullname,1);
		}	
		
		//make_name_png("woman_fullname.png",$last_id,$fullname,1);
        
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
				 $gaizi_detail_sql = "delete from spssp_gaizi_detail_for_user where gu_target_type =" .$target_type . "and gu_char_position=" .$n. "and gu_id=" .$user_id. ";";
				 mysql_query($gaizi_detail_sql);
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
			
			if($prefix=="woman_lastname_respect.png" || $prefix=="man_lastname_respect.png")
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
		$image->output($thumb1);
		
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
    function get_image_db_directory($hotel_id){
        $result_image_db_dir = "";
        $query = "select gc_sval_0 as val from spssp_gaizi_cfg where gc_cfg_type = 3 and gc_cscode = ".$hotel_id;
        $result = mysql_query($query );
        $num = mysql_num_rows($result);
        if($num>0){
            while($fetchedRow = mysql_fetch_assoc($result)){
                $result_image_db_dir = (string)$fetchedRow["val"];
            }
        }
        mysql_free_result($result);
        //
        return $result_image_db_dir;
    }
?>
