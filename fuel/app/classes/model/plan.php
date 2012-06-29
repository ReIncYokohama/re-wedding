<?php
class Model_Plan extends Model_Crud{
  static $_table_name = "spssp_plan";
  /*
    order 1 仮発注依頼
          2 本発注依頼
          3 イメージアップロード済み
    admin_to_pcompany
          1 仮発注
          2 本発注
          3 イメージアップロード済み
    gift_daylimit
          1 引出物発注依頼
          2 引出物発注締切日過ぎ、メールを送信
          3 引出物発注
   */

  static $_fields = array("id","user_id","name","layoutname","room_id","row_number","column_number","seat_number",
                          "creation_date","datetime","default_plan_id","rename_table","confirm_date","product_name",
                          "dowload_options","print_company","dl_print_com_times","ul_print_com_times","day_limit_1_to_print_com",
                          "day_limit_2_to_print_com","admin_to_pcompany","order","gift_daylimit","print_size","print_type",
                          "staff_id","final_proof","sekiji_email_send_today_check","p_company_file_up");
  public $cart;

  public $_user;

  public function get_user(){
    if($this->_user) return $_user;
    $this->_user = Model_User::find_by_pk($this->user_id);
    return $this->_user;
  }

  public function authority_rename_table(){
    $editable = $this->editable();
    if($this->rename_table==1 && $editable) return true;
    return false;;
  }

  //印刷依頼済み
  public function completed(){
    if($this->admin_to_pcompany==2) return true;
    return false;
  }

  //@編集不可の条件
  //管理者でなく、席次表の編集期間を過ぎている
  //印刷依頼を受けている
  public function editable(){
    $user = Model_User::find_by_pk($this->user_id);
    if($user->past_deadline_sekijihyo() && Core_Session::is_user()) return false;
    if($this->is_kari_hatyu_irai() or $this->is_hon_hatyu_irai()){
      return false;
    }else{
			return true;
    }
  }

  //古い。使わない
  public function pre_ordering(){
    if ($this->order == 1) return false;
    return true;
  }

  static public function find_obj_by_user_id($user_id){
    $plan = static::find_by_user_id($user_id);
    return new static($plan);
  }

  public function get_seat_data_in_session(){
    if($this->_seat_data) return $this->_seat_data;
    $seat_data = Core_Session::get_seat_data();
    if(!$seat_data){
      $plan_details = Model_Plandetails::find_by_plan_id($this->id);
      $seat_data = array();
      foreach($plan_details as $plandetail)
        {
          //if(!$plandetail["guest_id"]) continue;
          /*         $seat = Model_Seat::find_by_pk($plandetail["seat_id"]);
          $table = Model_Table::find_by_pk($seat->table_id);
          $user_table = Model_Usertable::find_one_by(array(array("table_id","=",$table->id),array("user_id","=",$this->user_id)));
          if($user_table->display != 1) continue;*/
          $skey= $plandetail['seat_id'].'_input';
          $sval = '#'.$plandetail['seat_id'].'_'.$plandetail['guest_id'];
          $seat_data[$skey]=$sval;
        }
      Core_Session::set_seat_data($seat_data);
    }
    $this->_seat_data = $seat_data;
    return $seat_data;
  }
  //for make_plan_full
  public function get_seat_data_ids(){
    $cart = $this->get_seat_data_in_session();
    $itemids = array();
    $seatids = array();
    foreach($cart as $item)
      {
        if($item)
          {
            $itemArr = explode("_",$item);
            $seatids[]=str_replace("#","",$itemArr[0]);
            $itemids[] = $itemArr[1];
          }
      }
    return array($itemids,$seatids);
  }

  function get_tables(){
    if($this->_tables) return $this->_tables;
    $table_arr = Model_Usertable::find_by_user_id($this->user_id);
    $this->_tables = $table_arr;
    return $table_arr;
  }

  function get_layoutname(){
    $layoutname = $this->layoutname;
    if($layoutname == ""){
      $option = Model_Option::find_one_by_option_name("default_layout_title");
      $layoutname = $option->option_value;
    }
    if($layoutname=="null") $layoutname = "";
    return $layoutname;
  }

  function get_takasago_guest_obj(){
    $takasago_arr = Model_Guest::find_by_takasago($this->user_id);
    $obj = array();
    $mukoyoshi = $this->get_user()->mukoyoshi;
    for($i=0;$i<count($takasago_arr);++$i){
      $takasago = $takasago_arr[$i];
      if($takasago->self==1){
        if((!$mukoyoshi && $takasago["sex"] == "Male") || ($mukoyoshi && $takasago["sex"]=="Female")){
          $obj["left"] = $takasago;
        }
        if((!$mukoyoshi && $takasago["sex"] == "Female") || ($mukoyoshi && $takasago["sex"]=="Male")){
          $obj["right"] = $takasago;
        }
      }else{
        $obj[$takasago->stage_guest] = $takasago;
      }
    }
    return $obj;
  }
  //仮発注依頼
  public function do_kari_hatyu_irai(){
    $this->order = 1;
    $this->save();
  }
  //仮発注
  public function do_kari_hatyu(){
    $this->admin_to_pcompany = 1;
    $this->order = 1;
    $this->ul_print_com_times="";
    $this->save();
  }
  //本発注依頼
  public function do_hon_hatyu_irai(){
    $this->order = 2;
    $this->save();
  }
  //本発注
  public function do_hon_hatyu(){
    $this->admin_to_pcompany = 2;
    $this->order = 2;

    $this->gift_daylimit=3;
    $this->dl_print_com_times="";
    $this->ul_print_com_times=1;

    $this->save();
  }
  public function do_reset(){
    if($this->admin_to_pcompany == 3){
      $this->order = 3;
    }else{
      $this->order = 0;
    }
    $this->dl_print_com_times=0;
    $this->ul_print_com_times=0;
    $this->sekiji_email_send_today_check = "";

    $this->save();
  }

  //印刷会社が席次表をアップロード
  public function upload_sekizihyo(){
    $this->order = 3;
    $this->admin_to_pcompany = 3;
    $this->off_read_uploaded_image();
    $this->off_read_uploaded_image_for_user();
    $this->save();
  }

  public function can_kari_hatyu_irai(){
    $flag = true;
    if($this->order==1 or $this->order==2){
      $flag = false;
    }
    if($this->order == 3){
      $flag = true;
    }
    return $flag;
  }
  public function can_kari_hatyu(){
    if($this->is_kari_hatyu() or $this->is_hon_hatyu()){
      return false;
    }
    return true;
  }
  public function can_hon_hatyu_irai(){
    if($this->is_hon_hatyu_irai() or $this->is_kari_hatyu_irai() or $this->is_kari_hatyu()){
      return false;
    }
    return true;
  }
  public function can_hon_hatyu(){
    if($this->is_hon_hatyu()){
      return false;
    }
    return true;
  }
  public function can_reset(){
    if($this->is_hon_hatyu() or $this->is_kari_hatyu()){
      return false;
    }
    return true;
  }
  public function can_hikidemono_hatyu_irai(){
    if($this->gift_daylimit == 0){
      return true;
    }
    return false;
  }
  public function can_hikidemono_hatyu(){
    if($this->gift_daylimit < 3){
      return true;
    }
    return false;
  }


  public function is_kari_hatyu_irai(){
    if($this->order == 1){
      return true;
    }
    return false;
  }
  public function is_kari_hatyu(){
    if($this->admin_to_pcompany == 1){
      return true;
    }
    return false;
  }
  public function is_hon_hatyu_irai(){
    if($this->order == 2){
      return true;
    }
    return false;
  }
  public function is_hon_hatyu(){
    if($this->admin_to_pcompany == 2){
      return true;
    }
    return false;
  }
  public function is_hikidemono_hatyu_irai(){
    return ($this->gift_daylimit == 1);
  }
  public function is_hikidemono_hatyu(){
    return ($this->gift_daylimit == 3);
  }

  public function uploaded_image(){
    if($this->order == 3){
      return true;
    }
    return false;
  }
  public function read_uploaded_image(){
    if($this->dl_print_com_times != "" and $this->dl_print_com_times != 0){
      return true;
    }
    return false;
  }
  public function read_uploaded_image_for_user(){
    if($this->ul_print_com_times != "" and $this->ul_print_com_times != 0){
      return true;
    }
    return false;
  }

  //donot save
  public function on_read_uploaded_image(){
    $this->dl_print_com_times = 1;
  }
  public function off_read_uploaded_image(){
    $this->dl_print_com_times = "";
  }
  public function on_read_uploaded_image_for_user(){
    $this->ul_print_com_times = 1;
  }
  public function off_read_uploaded_image_for_user(){
    $this->ul_print_com_times = "";
  }


  public function sent_sekijihyo_limit_mail(){
    if(!$this->sekiji_email_send_today_check && $this->sekiji_email_send_today_check == ""){
      return false;
    }
    return true;
  }

  public function sent_hikidemono_limit_mail(){
    if($this->gift_daylimit == 2){
      return true;
    }
    return false;
  }

  public function can_send_sekijihyo_deadline_mail(){
    $user = Model_User::find_by_pk($this->user_id);
    if($user->past_deadline_honhatyu($user_id) and !$this->is_hon_hatyu()){
      if(!$this->sent_sekijihyo_limit_mail()){
        return true;
      }
    }
    return false;
  }

  public function can_send_hidemono_deadline_mail(){
    if($user->past_deadline_hikidemono($user_id) and !$plan->is_hikidemono_hatyu()){
      if(!$plan->sent_hikidemono_limit_mail()){
        return true;
      }
    }
    return false;
  }


  public function do_hikidemono_hatyu_irai(){
    $this->gift_daylimit = 1;
  }
  public function do_hikidemono_hatyu(){
    $this->gift_daylimit = 3;
  }
  public function exist_print_company(){
    return ($this->print_company>0);
  }

  public function get_sekijihyo_sekifuda_num(){
    if($this->dowload_options == 1){
      return array($this->day_limit_1_to_print_com,"");
    }
    if($this->dowload_options == 2){
      return array("",$this->day_limit_2_to_print_com);
    }
    if($this->dowload_options == 3){
      return array($this->day_limit_1_to_print_com,$this->day_limit_2_to_print_com);
    }
  }

}
