<?php
class Model_Plan extends Model_Crud{
  static $_table_name = "spssp_plan";
  public $cart;

  public function authority_rename_table(){
    $editable = $this->editable();
    if($this->rename_table==1 && $editable) return true;
    return false;;
  }
  
  //印刷依頼済み
  public function completed(){
    if($this->admin_to_pcompany==3 or $this->order == 2) return true;
    return false;
  }

  //@編集不可の条件
  //席次表の編集期間を過ぎている
  //印刷依頼を受けている
  public function editable(){
    $clicktime = Model_Clicktime::find_by_user_id($this->user_id);
    if($clicktime){
      $pd = strptime($clicktime['print_irai'],"%Y-%m-%d %H:%M:%S");
      $pidate = mktime($pd[tm_hour],$pd[tm_min],$pd[tm_sec],$pd[tm_mon]+1,$pd[tm_mday],$pd[tm_year] + 1900);
      if(!preg_match('/.*\/(\d*).PDF$/', $this->p_company_file_up , $matches)){
        $matches = array("1");
      }
    }

    if(Model_User::past_deadline_sekijihyo($this->user_id) && !Core_Session::is_admin()) return false;
    
    if ($this->order == 1 && ($this->admin_to_pcompany == 0 || $this->admin_to_pcompany == 1)) return false;
    
    if($this->admin_to_pcompany==2 && $pidate < $matches[1]) return true;

		if(($this->order<=3 && $this->order>0) || ($this->order==2 && $this->admin_to_pcompany==3))
		{
			return false;
		}
		else
		{
			return true;
		}
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
      foreach($plan_details as $pdr)
        {
          $skey= $pdr['seat_id'].'_input';
          $sval = '#'.$pdr['seat_id'].'_'.$pdr['guest_id'];
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
    $table_arr = Model_Tablelayout::find_by_user_id($this->user_id);
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
}
