<?php
class Model_User extends Model_Crud{
  static $_table_name = "spssp_user";
  private $_room;
  //state 仮発注時に日付を登録
  static $_fields = array("id","marriage_day","man_firstname","man_lastname",
    "woman_firstname","woman_lastname","man_firstname_eng","man_lastname_eng",
    "woman_firstname_eng","woman_lastname_eng","marriage_day_with_time","room_id",
    "room_name","party_day_with_time","party_room_id","religion","contact_name",
    "zip","address","fax","mail","confirm_day_num","limitation_ranking","order_deadline",
    "user_id","password","stuff_id","user_code","creation_date","status","mail_check_number",
    "man_respect_id","woman_respect_id","subcription_mail","is_activated","man_furi_lastname",
    "man_furi_firstname","woman_furi_lastname","woman_furi_firstname","man_furi_firstname_eng",
    "man_furi_lastname_eng","woman_furi_firstname_eng","woman_furi_lastname_eng","party_day",
    "zip1","zip2","state","city","street","buildings","tel","mukoyoshi");

  //userが存在するか確認が必要な関数は、staticにしない。
  public function past_deadline_sekijihyo(){
    $date = Core_Date::create_from_string($this->party_day,"%Y-%m-%d");
    if($date->past_date($this->limitation_ranking)) return false;
    return true;
  }
  public function past_deadline_sekijihyo_plus_day($day=7){
    $date = Core_Date::create_from_string($this->party_day,"%Y-%m-%d");
    if($date->past_date($this->limitation_ranking+$day)) return false;
    return true;
  }

  public function get_deadline_honhatyu(){
    $date = Core_Date::create_from_string($this->party_day,"%Y-%m-%d");
    return new Core_Date($date->get_timestamp()-$this->confirm_day_num*60*60*24);
  }
  public function output_deadline_honhatyu(){
    $date = $this->get_deadline_honhatyu();
    return $date->format("%Y/%m/%d")."(".$date->get_wday().")";
  }

  public function get_deadline_sekijihyo(){
    $date = Core_Date::create_from_string($this->party_day,"%Y-%m-%d");
    return new Core_Date($date->get_timestamp()-$this->limitation_ranking*60*60*24);
  }
  
  public function output_deadline_sekijihyo(){
    $date = $this->get_deadline_sekijihyo();
    return $date->format("%Y/%m/%d")."(".$date->get_wday().")";
  }
  public function output_deadline_sekijihyo2(){
    $date = $this->get_deadline_sekijihyo();
    return $date->format("%Y年%m月%d日");
  }
  
  public function get_gaiji_arr(){
    return Model_Gaijiuser::get_by_user_id($this->id);
  }
  
  public function get_room_name(){
    $name = $this->room_name;
    if(!$name || $name == ""){
      $room = $this->get_room();
      return $room->name;
    }
    return $name;
  }
  
  public function get_room(){
    if($this->_room) return $this->_room;
    $this->_room = Model_Room::find_by_pk($this->room_id);
    return $this->_room;
  }
  
  public function get_staffname(){
    $admin = Model_Admin::find_by_pk($this->stuff_id);
    return $admin->name;
  }
  
  //お客様ID利用期限日
  public function past_deadline_access(){
    $limit = Model_Option::get_deadline_access();
    $date = Core_Date::create_from_string($this->party_day,"%Y-%m-%d");
    //締切日を過ぎた日なので１日足している。
    if($date->past_date(-$limit)) return false;
    return true;
  }
  
  //本発注締切日
  public function past_deadline_honhatyu(){
    if($this->confirm_day_num and $this->confirm_day_num != ""){
      $limit = $this->confirm_day_num;
    }else{
      $limit = Model_Option::get_deadline_honhatyu();
    }
    $date = Core_Date::create_from_string($this->party_day,"%Y-%m-%d");
    //締切日を過ぎた日なので１日足している。
    if($date->past_date($limit)) return false;
    return true;
  }

  //本発注締切日から７日前
  public function past_deadline_honhatyu_alert(){
    if($this->confirm_day_num and $this->confirm_day_num != ""){
      $limit = $this->confirm_day_num;
    }else{
      $limit = Model_Option::get_deadline_honhatyu();
    }
    //締切日を過ぎた日なので１日足しているので、8日前で
    $limit += 8;
    $date = Core_Date::create_from_string($this->party_day,"%Y-%m-%d");
    if($date->past_date($limit)) return false;
    return true;
  }

  
  //引出物締切日
  public function past_deadline_hikidemono(){
    if($this->order_deadline and $this->order_deadline != ""){
      $limit = $this->order_deadline;
    }else{
      $limit = Model_Option::get_deadline_hikidemono();
    }
    $date = Core_Date::create_from_string($this->party_day,"%Y-%m-%d");
    if($date->past_date($limit)) return false;
    return true;
  }
  //引出物締切日から７日前
  public function past_deadline_hikidemono_alert(){
    if($this->order_deadline and $this->order_deadline != ""){
      $limit = $this->order_deadline;
    }else{
      $limit = Model_Option::get_deadline_hikidemono();
    }
    //締切日を過ぎた日なので１日足しているので、8日前で
    $limit += 8;
    $date = Core_Date::create_from_string($this->party_day,"%Y-%m-%d");
    if($date->past_date($limit)) return false;
    return true;
  }

  
  static public function get_image($name,$user_id){
    $file = Core_Image::get_user_image_dir_relative($user_id).$name;
    if(is_file($file))
		{
      return $file;
    }
    //task log
    return false;
  }
  //外字のための画像を取得
  public function get_image_html($name,$opt=array()){
    $file = static::get_image($name,$this->id);
    if(array_key_exists("width",$opt)){
      return "<img src=\"".$file."\" width=\"".$opt["width"]."\" />";    
    }else if(array_key_exists("height",$opt)){
      return "<img src=\"".$file."\" height=\"".$opt["height"]."\" />";
    }else{
      return "<img src=\"".$file."\" />";
    }
  }
  //
  public function get_messages_for_hotel(){
    return Model_Message::get_by_admin_and_user_id($this->id);
  }
  //お知らせ情報の取得
  public function get_hotel_message(){
    $user_id = $this->id;
    $plan = Model_Plan::find_one_by_user_id($user_id);
		$user_plan_info = $plan->to_array();
    $party_day = Core_Date::convert_month_and_date($this->party_day);

    $man_name = $this->get_image_html("thumb2/man_lastname.png");
    $woman_name = $this->get_image_html("thumb2/woman_lastname.png");
    $user_name = $man_name."・".$woman_name;
    
    $msg_arr = array();
    //仮発注は、仮発注依頼がなくても可能なため、お知らせのフラグとcan_kari_hatyuのフラグは異なる。
    if($plan->is_kari_hatyu_irai() and !$plan->is_kari_hatyu()){
			$msg_text = "<div><a href='guest_gift.php?user_id=".$user_id."'>".
        $party_day."  ".$user_name."  様から仮発注依頼がありました。</a></div>";
      array_push($msg_arr,$msg_text);
    }
    if($plan->uploaded_image() and !$plan->read_uploaded_image()){
      $msg_text  = "<div id=msg_hide1><a href=\"ajax/pdf_readed.php?user_id=".$user_id.
        "&filename=".$plan->p_company_file_up.
        "\" target=_blank  onclick='hide_this(\"msg_hide1\");'>".$party_day.
        " ".$user_name."様向け印刷イメージが出来上がりました。</a></div>";
      array_push($msg_arr,$msg_text);
		}
    if($plan->is_hon_hatyu_irai() and !$plan->is_hon_hatyu()){
			$msg_text = "<div><a href='guest_gift.php?user_id=".$user_id."'>".$party_day."  ".
        $user_name." 様から印刷依頼がありました。</a></div>";
      array_push($msg_arr,$msg_text);
    }
    if($this->past_deadline_honhatyu() and !$plan->is_hon_hatyu()){
      $msg_text = "<div><a href='guest_gift.php?user_id=".$user_id."' style='color:blue;'>".
        $party_day."  ".$user_name." 様は席次表本発注締切日を過ぎています。</a></div>";
      array_push($msg_arr,$msg_text);
    }
    
    if($plan->is_hikidemono_hatyu_irai() and !$plan->is_hikidemono_hatyu() and !$plan->is_hon_hatyu()){
      $msg_text = "<div><a href='guest_gift.php?user_id=".$user_id."'>".
        $party_day."  ".$user_name." 様から引出物発注依頼がありました。</a></div>";
      array_push($msg_arr,$msg_text);
    }
    if($this->past_deadline_hikidemono() and !$plan->is_hikidemono_hatyu()){
      $msg_text = "<div><a href='guest_gift.php?user_id=".$user_id."' style='color:blue;'>".
        $party_day."  ".$user_name."  様は引出物本発注締切日を過ぎています。</a></div>";
      array_push($msg_arr,$msg_text);
    }
		return $msg_arr;
  }
  public function get_user_message(){
    $user_id = $this->id;    
    $plan = Model_Plan::find_one_by_user_id($this->id);

    $msg_arr = array();
    if($this->past_deadline_honhatyu() and !$plan->is_hon_hatyu_irai()){
			$msg_text = "<div><a href='order.php' style='color:blue;'>席次表の印刷締切日を過ぎております。至急担当までご連絡の上、確認作業をお願いします。</a></div>";
      array_push($msg_arr,$msg_text);
    }else if($this->past_deadline_honhatyu_alert() and !$plan->is_hon_hatyu_irai()){
			$msg_text = "<div><a href='order.php' style='color:blue;'>席次表の印刷締切日が近づいております。早めにご確認をお願いします。</a></div>";
      array_push($msg_arr,$msg_text);
		}
    if($plan->uploaded_image() and !$plan->read_uploaded_image_for_user()){
      $href = $plan->p_company_file_up;
      $msg_text  = "<div id=msg_hide1><a href=\"admin/ajax/pdf_readed.php?user_id=".
        $user_id."&filename=".$href."&userpage=ture\" target=\"_blank\" onclick='hide_this(\"msg_hide1\");'>".
        "印刷イメージが出来上がりました。</a></div>";
      array_push($msg_arr,$msg_text);
    }
    if($this->past_deadline_hikidemono() and !($plan->is_hikidemono_hatyu_irai() or $plan->is_hikidemono_hatyu() )){
      $msg_text = "<div><a href='order.php' style='color:blue;'>引出物の発注依頼の締切日を過ぎています。至急担当までご連絡の上、確認作業をお願いします。</a></div>";
      array_push($msg_arr,$msg_text);
    }else if($this->past_deadline_hikidemono_alert() and !($plan->is_hikidemono_hatyu_irai() or $plan->is_hikidemono_hatyu())){
      $msg_text = "<div><a href='order.php' style='color:blue;'>引出物の発注依頼の締切日が近づいています。早めにご確認をお願いします。</a></div>";
      array_push($msg_arr,$msg_text);
    }
		return $msg_arr;    
  }  
  //席次表のフラグ
  public function get_sekijihyo_status(){
    $plan = Model_Plan::find_one_by_user_id($this->id);
    if($this->past_deadline_honhatyu() and !$plan->is_hon_hatyu()){
      $msg_opt = "<img src='img/common/msg/untreated.gif' border = '0'>";
    }
    if($plan->is_kari_hatyu()){
			$msg_opt = "<img src='img/common/msg/provisional_order.gif' border = '0'>";
		}else if($plan->uploaded_image()){
			$msg_opt = "<img src='img/common/msg/up.gif' border = '0'>";
		}else if($plan->is_hon_hatyu()){
			$msg_opt = "<img src='img/common/msg/processed.gif' border = '0' alt='processed'>";
		}else if($plan->is_hon_hatyu_irai()){
      $msg_opt = "<img src='img/common/msg/print_request.gif' border = '0' alt='print_request'>";
    }else if($plan->is_kari_hatyu_irai()){
      $msg_opt = "<img src='img/common/msg/provisional_order_request.gif' border = '0' alt='nn'>";
    }
    return $msg_opt;
  }
  //引き出物のフラグ
  public function get_hikidemono_status(){
    $plan = Model_Plan::find_one_by_user_id($this->id);
    if($plan->is_hikidemono_hatyu_irai()){
      return "<img src='img/common/msg/order_request.gif' border = '0'>";
    }else if($this->past_deadline_hikidemono() and !$plan->is_hikidemono_hatyu()){
      return "<img src='img/common/msg/untreated.gif' border = '0'>";
    }else if($plan->is_hikidemono_hatyu()){
      return "<img src='img/common/msg/processed.gif' border = '0'>";
    }
    return "";
  }

  public function past_delete_account($hcode){
    $hotel = Model_Hotel::find_one_by_hotel_code($hcode);
    $hotel_name = $hotel["hotel_name"];
    $date = Core_Date::create_from_string($this->party_day,"%Y-%m-%d");
    //締切日を過ぎた日なので１日足している。
    if($date->past_date(-100)) return false;
    return true;
  }
  public function past_delete_users($hcode){
    $hotel = Model_Hotel::find_one_by_hotel_code($hcode);
    $date = Core_Date::create_from_string($this->party_day,"%Y-%m-%d");
    if($date->past_month_last_day(-1*$hotel["delete_weeding"])) return false;
    return true;
  }
  public function past_delete_guests($hcode){
    $hotel = Model_Hotel::find_one_by_hotel_code($hcode);
    $date = Core_Date::create_from_string($this->party_day,"%Y-%m-%d");
    //締切日を過ぎた日なので１日足している。
    if($date->past_month_last_day(-1*$hotel["delete_guest"])) return false;
    return true;
  }
  
  
  public function pre_delete(){
    $plan = Model_Plan::find_one_by_user_id($this->id);
    
    DB::query('delete from spssp_plan_details where plan_id='.$plan->id)->execute();
    DB::query('delete from guest_csv_upload_log where user_id='.$this->id)->execute();
    DB::query('delete from spssp_admin_messages where user_id='.$this->id)->execute();
    DB::query('delete from spssp_change_log where user_id='.$this->id)->execute();
    DB::query('delete from spssp_clicktime where user_id='.$this->id)->execute();
    DB::query('delete from spssp_gift where user_id='.$this->id)->execute();
    DB::query('delete from spssp_gift_group where user_id='.$this->id)->execute();
    DB::query('delete from spssp_gift_group_relation where user_id='.$this->id)->execute();
    DB::query('delete from spssp_guest where user_id='.$this->id)->execute();
    DB::query('delete from spssp_guest_gift where user_id='.$this->id)->execute();
    DB::query('delete from spssp_guest_menu where user_id='.$this->id)->execute();
    DB::query('delete from spssp_guest_orderstatus where user_id='.$this->id)->execute();
    DB::query('delete from spssp_menu_rgoup where user_id='.$this->id)->execute();
    DB::query('delete from spssp_message where user_id='.$this->id)->execute();
    DB::query('delete from spssp_table_layout where user_id='.$this->id)->execute();
    DB::query('delete from spssp_user_log where user_id='.$this->id)->execute();
    DB::query('delete from spssp_user_table where user_id='.$this->id)->execute();
    
    $plan->delete();
  }

  public function is_read_by_admin(){
    $messages = Model_Message::find_by_user_id($this->id);
    $read = true;
    foreach($messages as $message){
      if(!$message->is_read()){
        $read = false;
        break;
      }
    }
    return $read;
  }
  public function recent(){
    $users = Model_User::find(array("order_by"=>array("id"=>"desc"),"limit"=>1));
    return $users[0];
  }

}
