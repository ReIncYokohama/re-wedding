<?php
include_once("class_information.dbo.php");

class MessageClass extends InformationClass
{
	public function MessageClass()
	{

	}
	function admin_side_user_list_new_status_notification_image_link_system($user_id, $msg="")
	{
		//SELECT count(*) FROM `spssp_plan` WHERE `user_id` =81 AND `order` !=0
			return $this :: get_admin_side_user_list_new_status_notification_when_ordered($user_id);

	}
	function send_day_limit_message($user_id){
		if($this :: sekiji_day_limit_over_check_for_all_users($user_id))
		{
      if($this :: sekiji_user_day_over_email_send_for_today_check($user_id) && $this :: GetRowCount("spssp_plan"," `order` < 2 and user_id=".$user_id) )
        {
          if ($msg!="") echo $user_id." : [Mail Send 8, 10 ]<br />\n";
          $objMail = new MailClass();
          $objMail -> sekiji_day_limit_over_admin_notification_mail($user_id);//mail 8=>admin
          $objMail -> sekiji_day_limit_over_user_notification_mail($user_id);//mail 10=>user
        }
		}
  }
	function send_hikidemono_day_limit_message($user_id){
		$objMail = new MailClass();
		$user_plan_info = $this :: get_user_plan_info($user_id);
		if($user_plan_info['gift_daylimit']==0) {
			if ($this :: proccesse_gift_day_limit($user_id)) { // 発注締切日を過ぎたか
				if ($msg!="") echo $user_id." : [Mail Send 9, 11 ]<br />\n";
				$objMail -> hikidemono_day_limit_over_admin_notification_mail($user_id); //mail  9=>Stuff
				$objMail -> hikidemono_day_limit_over_user_notification_mail($user_id);  //mail 11=>user
				// UCHIDA EDIT 11/08/10 メール９，１１の送信を記録
				unset($post);
				$post['gift_daylimit']=2;
				$this->UpdateData('spssp_plan',$post," user_id=".$user_id);
			}
		}
  }
// UCHIDA EDIT 11/08/15 締切日が過ぎていても、アクションがあればアイコンを表示する
	function get_admin_side_user_list_new_status_notification_when_ordered($user_id)
	{
// echo $user_id;
		if($this :: sekiji_day_limit_over_check_for_all_users($user_id))
		{

// UCHIDA EDIT 11/08/17 本発注されていれば、メールの送信は行わない
//			if($this :: sekiji_user_day_over_email_send_for_today_check($user_id) )
			$msg_opt = "<img src='img/common/msg/untreated.gif' border = '0'>";
		}

		if( $this :: GetRowCount("spssp_plan"," admin_to_pcompany = 1 and `order` = 1 and user_id=".$user_id) )
		{
			$msg_opt = "<img src='img/common/msg/provisional_order.gif' border = '0'>";
		}
		else if( $this :: GetRowCount("spssp_plan"," admin_to_pcompany = 2 and `ul_print_com_times` < 2 and user_id=".$user_id) )
		{
			$msg_opt = "<img src='img/common/msg/up.gif' border = '0'>";
		}
		else if( $this :: GetRowCount("spssp_plan"," admin_to_pcompany = 3 and user_id=".$user_id) )
		{

			$msg_opt = "<img src='img/common/msg/processed.gif' border = '0' alt='processed'>";
		}
		else if( $this :: GetRowCount("spssp_plan"," `order` = 2 and user_id=".$user_id) )
		{
			$msg_opt = "<img src='img/common/msg/print_request.gif' border = '0' alt='print_request'>";
		}
		else if( $this :: GetRowCount("spssp_plan"," `order` = 1 and user_id=".$user_id) )
		{
			//$user_messages = $this :: GetAllRowsByCondition("spssp_message","  msg_type = 1 and user_id=".$user_id);
			$msg_opt = "<img src='img/common/msg/provisional_order_request.gif' border = '0' alt='nn'>";
		}

		return $msg_opt;

	}
	function get_admin_side_user_list_new_status_notification_usual($user_id, $stuff_id)
	{
		$user_messages = $this :: GetAllRowsByCondition("spssp_message"," user_id=".$user_id);

		$admin_viewed = true;

		if(!empty($user_messages) )
		{
			foreach($user_messages as $msg)
			{
				if($msg['admin_viewed'] == 0)
				{
					$admin_viewed = false;
				}
			}
			if($admin_viewed== false)
			{
				$msg_opt = "<a href='message_user.php?user_id=".$user_id."&stuff_id=".$stuff_id."'><img src='img/common/btn_midoku.gif' border = '0'></a>";
			}
			else
			{
				//$msg_opt = "<a href='message_user.php?user_id=".$user_id."'><img src='img/common/btn_zumi.gif' border = '0'></a>";
			}

		}
		else
		{
			$msg_opt="";
		}
		return $msg_opt;
	}
	function admin_side_user_list_gift_day_limit_notification_image_link_system($user_id, $msg="") // UCHIDA EDIT 11/08/10 引出物 メール、アイコン
	{
		$objMail = new MailClass();
		$user_plan_info = $this :: get_user_plan_info($user_id);

//		if($user_plan_info['gift_daylimit']==2)
//		{
//			if($this :: proccesse_gift_day_limit($user_id))
//			{
//				$objMail -> hikidemono_day_limit_over_admin_notification_mail($user_id);//mail 9=>admin
//				$objMail -> hikidemono_day_limit_over_user_notification_mail($user_id);//mail 11=>user
//			}
//		}

		if( $this :: GetRowCount("spssp_plan"," `gift_daylimit` = 1 and user_id=".$user_id) )
		{
			return $link = "<img src='img/common/msg/order_request.gif' border = '0'>";
		}
//		else if( $this :: GetRowCount("spssp_plan"," `gift_daylimit` = 2 and user_id=".$user_id) )
//		{
//			return $link = "<img src='img/common/msg/processed.gif' border = '0'>";
//		}
// UCHIDA EDIT 11/08/09 gift_daylimit = 3は完了なので、披露宴の７日（デフォルト値）まえで未処理表示
//		else if( $this :: GetRowCount("spssp_plan"," `gift_daylimit` = 3 and user_id=".$user_id) )
//		{
//			return $link = "<img src='img/common/msg/untreated.gif' border = '0'>";
//		}
//		else if( $user_plan_info['gift_daylimit']==2 && $this :: proccesse_gift_day_limit($user_id) )
		else if( $user_plan_info['gift_daylimit']==2)
		{
			return $link = "<img src='img/common/msg/untreated.gif' border = '0'>";
		}
		else if( $this :: GetRowCount("spssp_plan"," `gift_daylimit` = 3 and user_id=".$user_id) )
		{
			return $link = "<img src='img/common/msg/processed.gif' border = '0'>";
		}
	}

// UCHIDA EDIT 11/08/15 表示順位の入れ替え、INFO_Aの表示条件変更
	function get_admin_side_order_print_mail_system_status_msg($user_id)
	{
		$user_info = $this :: get_user_info($user_id);
		$user_plan_info = $this :: get_user_plan_info($user_id);
    $party_day = $this->getMonthAndDate($user_info["party_day"]);

    $man_name = $this::get_user_name_image_or_src($user_id ,$hotel_id=1, $name="man_lastname.png",$extra="thumb2");
    $woman_name = $this::get_user_name_image_or_src($user_id,$hotel_id=1 , $name="woman_lastname.png",$extra="thumb2");
    $user_name = $man_name."・".$woman_name;

		$msg_text = "";
		if( $this :: GetRowCount("spssp_plan"," admin_to_pcompany = 0 and `order` = 1 and user_id=".$user_id) )
				{
			$msg_text = "<li><a href='guest_gift.php?user_id=".$user_id."'>".$party_day."  ".$user_name."  ".INFO_A."</a></li>";
		}

		else if( $this :: GetRowCount("spssp_plan"," admin_to_pcompany = 2 and `ul_print_com_times` < 2 and user_id=".$user_id) )
		{
			$dl = $user_plan_info['dl_print_com_times'];
			if (($dl & 0x200) == 0x00) {// UCHIDA EDIT 11/08/15 スタッフがPDF表示を行っていないか
				$dl = $dl | 0x00; // 0x200;
				$msg_text  = "<div id=msg_hide1>";
				$msg_text .= "<a href=ajax/pdf_readed.php?user_id=".$user_id."&filename=".$user_plan_info[p_company_file_up]."&vset=".$dl." target=_blank";
        //maintでは隠されていた
				$msg_text .= " onclick='hide_this(\"msg_hide1\");'";
				$msg_text .= ">";
				$msg_text .= $party_day." ".$user_name." ".INFO_B."</a></div>";
			}
		}
		else if( $this :: GetRowCount("spssp_plan"," `order` = 2 and `admin_to_pcompany` != 3 and user_id=".$user_id) )
		{
			$msg_text = "<li><a href='guest_gift.php?user_id=".$user_id."'>".$party_day."  ".$user_name."  ".INFO_D."</a></li>";
		}

		else if($this :: sekiji_day_limit_over_check_for_all_users($user_id) && $user_plan_info['admin_to_pcompany']<3) // UCHIDA EDIT 11/08/15 本発注では締切日は無視
		{
			$msg_text .= "<li><a href='guest_gift.php?user_id=".$user_id."' style='color:red;'>".$party_day."  ".$user_name."  ".INFO_E."</a></li>";
		}

		if( $this :: GetRowCount("spssp_plan"," admin_to_pcompany = 1 and `order` = 1 and user_id=".$user_id) )
		{
			$msg_text .= "";
		}
		if( $this :: GetRowCount("spssp_plan"," admin_to_pcompany = 3 and `order` = 2 and user_id=".$user_id) )
		{
			$msg_text .= "";
		}

		return $msg_text;

	}
	function get_admin_side_daylimit_system_status_msg($user_id) // UCHIDA EDIT 11/08/10 スタッフ　引出物確認
	{


    $man_name = $this::get_user_name_image_or_src($user_id ,$hotel_id=1, $name="man_lastname.png",$extra="thumb2");
    $woman_name = $this::get_user_name_image_or_src($user_id,$hotel_id=1 , $name="woman_lastname.png",$extra="thumb2");
    $user_name = $man_name."・".$woman_name;

		$user_plan_info = $this :: get_user_plan_info($user_id);
		$user_info = $this :: get_user_info($user_id);

		$party_date_array=explode("-",$user_info['party_day']);
		$party_day=$party_date_array[1]."/".$party_date_array[2];

// UCHIDA EDIT 11/08/18 席次の状態をチェックしていたので、無効にする
		if($user_plan_info['gift_daylimit']==1) { // UCHIDA EDIT 11/08/10 発注された
			return $link = "<li><a href='guest_gift.php?user_id=".$user_id."'>".$party_day."  ".$user_name."  ".INFO_F."</a></li>";
		}

		if($user_plan_info['gift_daylimit']==0 || $user_plan_info['gift_daylimit']==2) { // UCHIDA EDIT 11/08/10 ０：初期値　２：メール送信済み
			if ($this :: proccesse_gift_day_limit($user_id) == true) { // 発注締切日を過ぎたか
				return $link = "<li><a href='guest_gift.php?user_id=".$user_id."' style='color:red;'>".$party_day."  ".$user_name."  ".INFO_G."</a></li>";
			}
		}

/*
	    if( $this :: GetRowCount("spssp_plan"," `order` = 2 and user_id=".$user_id) )
		{
			//no user msg show
		}
		else
		{
//echo "Gift : ".$user_plan_info['gift_daylimit'];
			if($user_plan_info['gift_daylimit']==1) { // UCHIDA EDIT 11/08/10 発注された
				return $link = "<li><a href='guest_gift.php?user_id=".$user_id."'>".$party_day."  ".$user_info['man_firstname']."・".$user_info['woman_firstname']."  ".INFO_F."</a></li>";
			}

			if($user_plan_info['gift_daylimit']==0 || $user_plan_info['gift_daylimit']==2) { // UCHIDA EDIT 11/08/10 ０：初期値　２：メール送信済み
				if ($this :: proccesse_gift_day_limit($user_id) == true) { // 発注締切日を過ぎたか
					return $link = "<li><a href='guest_gift.php?user_id=".$user_id."' style='color:red;'>".$party_day."  ".$user_info['man_firstname']."・".$user_info['woman_firstname']."  ".INFO_G."</a></li>";
				}
			}

			if($user_plan_info['gift_daylimit']==2)
			{
				if($this :: proccesse_gift_day_limit($user_id))
				{
					return $link = "<li><a href='guest_gift.php?user_id=".$user_id."' style='color:red;'>".$party_day."  ".$user_info['man_firstname']."・".$user_info['woman_firstname']."  ".INFO_G."</a></li>";
				}
			}
			if( $this :: GetRowCount("spssp_plan"," `gift_daylimit` = 1 and user_id=".$user_id) )
			{
				return $link = "<li><a href='guest_gift.php?user_id=".$user_id."'>".$party_day."  ".$user_info['man_firstname']."・".$user_info['woman_firstname']."  ".INFO_F."</a></li>";
			}else if( $this :: GetRowCount("spssp_plan"," `gift_daylimit` = 2 and user_id=".$user_id) )
			{
				return $link = "";
			}
			else if( $this :: GetRowCount("spssp_plan"," `gift_daylimit` = 3 and user_id=".$user_id) )
			{
				return $link = "<li><a href='guest_gift.php?user_id=".$user_id."' style='color:red;'>".$user_id.":::::".$party_day."  ".$user_info['man_firstname']." . ".$user_info['woman_firstname']."  ".INFO_G."</a></li>";
			}
		}
*/
	}

	function get_user_side_order_print_mail_system_status_msg($user_id)
	{
		$user_info = $this :: get_user_info($user_id);
		$user_plan_info = $this :: get_user_plan_info($user_id); // UCHIDA EDIT 11/08/09 アップロードファイルを取得
		$msg_text = "";

		if($this :: sekiji_day_limit_over_check_for_all_users($user_id) && $user_plan_info['order'] < 2) // UCHIDA EDIT 11/08/15 印刷ＯＫまで締切日のメッセージを表示する
		{
			$msg_text .= "<div><a href='order.php'>".INFO_I."</a></div>";
		}elseif($this :: sekiji_day_limit_over_check_for_7days_all_users($user_id) && $user_plan_info['order'] < 2) // UCHIDA EDIT 11/08/15 印刷ＯＫまで７日前のメッセージを表示する
		{
			$msg_text .= "<div><a href='order.php'>".INFO_J."</a></div>";
		}
		if( $this :: GetRowCount("spssp_plan"," admin_to_pcompany = 1 and `order` = 1 and user_id=".$user_id) )
		{
			$msg_text .= "";
		}
		if( $this :: GetRowCount("spssp_plan"," admin_to_pcompany = 2 and `ul_print_com_times` < 2 and user_id=".$user_id) )
		{
			$dl = $user_plan_info['dl_print_com_times'];
			if (($dl & 0x100) == 0x000) { // UCHIDA EDIT 11/08/15 ユーザがPDF表示を行っていないか
				$dl = $dl | 0x100;
				$href = $user_plan_info['p_company_file_up'];
				$msg_text  = "<div id=msg_hide1>";
				$msg_text .= "<a href=admin/ajax/pdf_readed.php?user_id=".$user_id."&filename=".$href."&vset=".$dl." target=_blank";
//				$msg_text .= " onclick='hide_this(\"msg_hide1\");'>"; maint
				$msg_text .= " onclick='hide_this(\"msg_hide1\");'";
				$msg_text .= ">";
				$msg_text .= INFO_C."</a></div>";
			}
		}
		else if( $this :: GetRowCount("spssp_plan"," admin_to_pcompany = 3 and `order` = 2 and user_id=".$user_id) )
		{
			$msg_text .= "";
		}
		else if( $this :: GetRowCount("spssp_plan"," `order` = 2 and user_id=".$user_id) )
		{
			$msg_text .= "";
		}
		else if( $this :: GetRowCount("spssp_plan"," `order` = 1 and user_id=".$user_id) )
		{
			$msg_text .= "";
		}

		return $msg_text;

	}
	function get_user_side_daylimit_system_status_msg($user_id) // UCHIDA EDIT 11/08/10 ユーザ 引出物確認
	{
		$user_plan_info = $this :: get_user_plan_info($user_id);
		$user_info = $this :: get_user_info($user_id);

//		if( $this :: GetRowCount("spssp_plan"," `order` = 2 and user_id=".$user_id) )
//		{
			//no user msg show
//		}
//		else
//		{
			$link="";
			if($user_plan_info['gift_daylimit']==0 || $user_plan_info['gift_daylimit']==2) { // UCHIDA EDIT 11/08/10 ０：初期値　２：メール送信済み
				if($this :: proccesse_gift_day_limit($user_id)) { // 発注締切日を過ぎたか
					$link .= "<div><a href='order.php'>".INFO_H."</a></div>";
				}elseif($this :: proccesse_gift_day_limit_7_days($user_id)) { // 披露宴日７日前か
					$link .= "<div><a href='order.php'>".INFO_K."</a></div>";
				}
			}
			return $link;
/*
			if($user_plan_info['gift_daylimit']==2)
			{
				if($this :: proccesse_gift_day_limit($user_id))
				{
					return $link = "<li><a href='order.php'>".INFO_K."</a></li>";
				}
			}
			if( $this :: GetRowCount("spssp_plan"," `gift_daylimit` = 1 and user_id=".$user_id) )
			{
				return $link = "";
			}else if( $this :: GetRowCount("spssp_plan"," `gift_daylimit` = 2 and user_id=".$user_id) )
			{
				return $link = "";
			}
			else if( $this :: GetRowCount("spssp_plan"," `gift_daylimit` = 3 and user_id=".$user_id) )
			{
				return $link = "<li><a href='order.php'>".INFO_H."</a></li>";
			}
*/
		}
//	}

	function user_suborder_admin_notification_message($user_id)
	{

			$user_info = $this :: get_user_info($user_id);

			$post['title']="Sub order of {$user_info['man_firstname']} {$user_info['man_lastname']}";
			$post['description']=nl2br("This email contain printing Sub-Order of
			{$user_info['man_firstname']} {$user_info['man_lastname']} and {$user_info['woman_firstname']} {$user_info['woman_lastname']}
			User ID = {$user_info['id']}
			User Name = {$user_info['user_id']}
			E-mail = {$user_info['mail']}
			");
			$post['creation_date'] = date("Y-m-d H:i:s");
			$post['display_order']= time();
			$post['admin_id']= $user_info['stuff_id'];
			$post['msg_type']= 1;
			$post['user_id']= $user_id;

			$lastid = $this :: InsertData('spssp_message',$post);

			if($lastid){return 1;/*success*/}else{return 2;/*error*/}
	}

	function printCompany_upload_admin_notification_message( $user_id , $printCompany_id )
	{
		/*THIS MSG WILL BE SEND TO USER'S ADMIN WHEN USER'S PRINT COMPANY UPLOADS SOME FILE FOR USER*/

		$printCompany_info = $this :: get_printing_company_info($printCompany_id);
		$user_info = $this :: get_user_info($user_id);
		$staff_id = $user_info['stuff_id'];
		$admin_info = $this :: get_admin_info($staff_id);

		$post['title']="{$printCompany_info['company_name']} Print Company has uploaded file for {$user_info['man_firstname']} {$user_info['man_lastname']}";
		$post['description']=nl2br("Hello Dear,<br>{$printCompany_info['company_name']} Print Company has uploaded file for.
		{$user_info['man_firstname']} {$user_info['man_lastname']} and {$user_info['woman_firstname']} {$user_info['woman_lastname']}<br>
		User ID = {$user_info['id']}<br>
		User Name = {$user_info['user_id']}<br>
		E-mail = {$user_info['mail']}<br><br>

		Thanks<br>".date("Y-m-d H:i:s")."<br><br>
		");

		$post['creation_date'] = date("Y-m-d H:i:s");
		$post['display_order']= time();
		$post['admin_id']= $staff_id;
		$post['user_id']= $user_id;

		$lastid = $this :: InsertData('spssp_admin_messages',$post);
		if($lastid){return 1;/*success*/}else{return 2;/*error*/}

	}

	function user_print_request_admin_notification_message( $user_id , $printCompany_id )
	{
		/*THIS MSG WILL BE SEND TO USER'S ADMIN WHEN USER SEND PRINT REQUEST*/

		$printCompany_info = $this :: get_printing_company_info($printCompany_id);
		$user_info = $this :: get_user_info($user_id);
		$staff_id = $user_info['stuff_id'];
		$admin_info = $this :: get_admin_info($staff_id);

		$post['title']="Print Request for {$user_info['man_firstname']} {$user_info['man_lastname']}";
		$post['description']=nl2br("Hello Dear,<br>I want to print my order.So please take step for this.
		{$user_info['man_firstname']} {$user_info['man_lastname']} and {$user_info['woman_firstname']} {$user_info['woman_lastname']}<br>
		User ID = {$user_info['id']}<br>
		User Name = {$user_info['user_id']}<br>
		E-mail = {$user_info['mail']}<br><br>

		Thanks<br>".date("Y-m-d H:i:s")."<br><br>
		");

		$post['creation_date'] = date("Y-m-d H:i:s");
		$post['display_order']= time();
		$post['admin_id']= $user_info['stuff_id'];
		$post['msg_type']= 2;
		$post['user_id']= $user_id;

		$lastid = $this :: InsertData('spssp_message',$post);
		if($lastid){return 1;/*success*/}else{return 2;/*error*/}

	}

	function user_gift_daylimit_request_admin_notification_message( $user_id )
	{
		/*THIS MSG WILL BE SEND TO USER'S ADMIN WHEN USER SEND GIFT DAY LIMIT REQUEST*/

		$user_info = $this :: get_user_info($user_id);
		$staff_id = $user_info['stuff_id'];


		$post['title']="Gift day limit Request from {$user_info['man_firstname']} {$user_info['man_lastname']}";
		$post['description']=nl2br("Hello Dear,<br>I want Gift day limit.So please take step for this.
		{$user_info['man_firstname']} {$user_info['man_lastname']} and {$user_info['woman_firstname']} {$user_info['woman_lastname']}<br>
		User ID = {$user_info['id']}<br>
		User Name = {$user_info['user_id']}<br>
		E-mail = {$user_info['mail']}<br><br>

		Thanks<br>".date("Y-m-d H:i:s")."<br><br>
		");

		$post['creation_date'] = date("Y-m-d H:i:s");
		$post['display_order']= time();
		$post['admin_id']= $user_info['stuff_id'];
		$post['user_id']= $user_id;

		$lastid = $this :: InsertData('spssp_message',$post);
		if($lastid){return 1;/*success*/}else{return 2;/*error*/}

	}
// UCHIDA EDIT 11/08/16 クリック日付を記録
	function clicktime_entry_return( $type, $user_id ) {
    $click_info = $this ->GetSingleRow("spssp_clicktime"," user_id=".$user_id);
    
		unset($post);
    $time = time();
		$dt = date("Y-m-d H:i:s");
		switch ($type) {
	    case "print_irai":
	    	$target = $click_info['print_irai'];
			$post['print_irai']=$dt;
	    	break;
	    case "print_ok":
	    	$target = $click_info['print_ok'];
			$post['print_ok']=$dt;
	    	break;
	    case "hikide_irai":
	    	$target = $click_info['hikide_irai'];
			$post['hikide_irai']=$dt;
	    	break;
		case "kari_hachu":
      $target = $click_info['kari_hachu'];
        $post['kari_hachu']=$dt;
	    	break;
	    case "hon_hachu":
	    	$target = $click_info['hon_hachu'];
			$post['hon_hachu']=$dt;
	    	break;
	    case "hikide_zumi":
	    	$target = $click_info['hikide_zumi'];
			$post['hikide_zumi']=$dt;
	        break;
		default:
	    	return "ERROR";
		}

		if ($target == NULL) {
			$sql = "insert into spssp_clicktime ($type, user_id) values('".$dt."','".$user_id."')";
			$result=mysql_query($sql);
		}
		else {
			$this::update_clicktime_info($type, $dt, $user_id);
		}

		return $this::clicktime_format($dt);
	}
// UCHIDA EDIT 11/08/16 クリック日付のフォーマト
	function clicktime_format($target) {
		if ($target == NULL or $target == "0000-00-00 00:00:00") 	return NULL;
//		else														return "[".substr($target, 5, 2 )."/".substr($target, 8, 2 )." ".substr($target, 11, 5 )."]";
		else														return substr($target, 0, 4 )."年".substr($target, 5, 2 )."月".substr($target, 8, 2 )."日 ".substr($target, 11, 5 )." 依頼済";

	}

// UCHIDA EDIT 11/08/17 曜日表示
	function get_youbi_name($dt) {
	$weekjp_array = array('日', '月', '火', '水', '木', '金', '土');

		$strdate = str_replace("/","-",$dt);
		$_y = date("Y", strtotime($strdate));
		$_m = date("m", strtotime($strdate));
		$_d = date("d", strtotime($strdate));
		$ptimestamp = mktime(0, 0, 0, $_m, $_d, $_y);
		$weekno = date('w', $ptimestamp);
		return "(".$weekjp_array[intval($weekno)].")";
	}

/*
データベースにテーブルの追加とセッション値の追加

state　1のとき、まだお知らせの出力をする。0のとき、お知らせを表示しない。
hotel  1のとき、ホテルユーザ用のお知らせ。0のとき、ユーザ用のお知らせ。
  create table guest_csv_upload_log (
                                     id int primary key auto_increment, 
                                     create_at TIMESTAMP default current_timestamp, 
                                     update_at timestamp,
                                     state int default 1, 
                                     user_id int, 
                                     hotel int );

セッションの値について
管理者333(adminid 1),一般ホテルユーザ 222,(adminid 2),一般ユーザ 222(adminid 2)  
これを
管理者333(adminid 1),一般ホテルユーザ 222,(adminid 2),一般ユーザ 222(adminid 0)
に変更した。  
*/
  public function get_message_csv_import_for_user($user_id){
    $logs_arr = $this->GetAllRowsByCondition("guest_csv_upload_log"," user_id = ".$user_id." and hotel=0 and state = 1");
    $text = "";
    for($i=0;$i<count($logs_arr);++$i){
      $text .= "<li><a href='my_guests.php'>招待客リストデータが追加されました。</a></li>";
    }
    return $text;
  }
  public function finish_message_csv_import_for_user($user_id){
    $this->UpdateData("guest_csv_upload_log",array("state" => 0)," hotel=0 and user_id = '".$user_id."'");
  }

  public function get_message_csv_import_for_hotel(){
    $logs_arr = $this->GetAllRowsByCondition("guest_csv_upload_log"," hotel=1 and state = 1");

    $text = "";
    for($i=0;$i<count($logs_arr);++$i){
//      $plan_info = $this->GetSingleRow("spssp_plan"," user_id = ".$logs_arr[$i]["user_id"]);  // spssp_planのstaff_idは信用できない
	    $user_info = $this->GetSingleRow("spssp_user"," id = ".$logs_arr[$i]["user_id"]);
    	//staff_id
//      if($plan_info["staff_id"]==$_SESSION["staff_id"]) {
      if($user_info["stuff_id"]==$_SESSION["staff_id"]) {
//	      $user_info = $this->GetSingleRow("spssp_user"," id = ".$logs_arr[$i]["user_id"]);
	      $man_name = $this->get_user_name_image_or_src($user_info['id'] ,$hotel_id=1, $name="man_lastname.png",$extra="thumb2");
	      $woman_name = $this->get_user_name_image_or_src($user_info['id'],$hotel_id=1 , $name="woman_lastname.png",$extra="thumb2");
	      $party_day = $this->getMonthAndDate($user_info["party_day"]);
	      $text .= "<li><a href='user_dashboard.php?src=my_guests&user_id=".$logs_arr[$i]["user_id"]."' target='_blank'>".$party_day
	        ." ".$man_name."・".$woman_name
	        ."様の招待客リストデータがアップロードされました。</a></li>";
	      }
    }
    return $text;
  }
  public function finish_message_csv_import_for_hotel($user_id){
    $results = $this->getRowsByQuery("select * from guest_csv_upload_log where hotel=1 and user_id=".$user_id);
    $plan_info = $this->GetSingleRow("spssp_plan"," user_id = ".$user_id);
    if($plan_info["staff_id"]!=$_SESSION["staff_id"]) return;
    for($i=0;$i<count($results);++$i){
      $this->UpdateData("guest_csv_upload_log",array("state" => 0)," id = '".$results[$i]["id"]."'");
    }
  }
  //ホテルごとにDBを切っているのでHOTELIDを使って振り分けて、対応をしている。
  public function new_message_csv_import($user_id){
    $this->InsertData("guest_csv_upload_log",array("user_id" => $user_id,"hotel" => 0));
    $this->InsertData("guest_csv_upload_log",array("user_id" => $user_id,"hotel" => 1));
  }
  

}
?>
