<?php
class Controller_Admin extends Controller_Common
{
  public $template = "templates/admin";
  public function before()
  {
    $config = Config::load("sampli");
    $hotel = Model_Hotel::find_one_by_hotel_code($config["hcode"]);
    $hotel_name = $hotel["hotel_name"];
    $this->var["hotel_name"] = $hotel_name;
    $this->var["is_super"] = Core_Session::is_super();
    $this->var["staff_name"] = Core_Session::get_staff_name();
  }

	public function action_index()
	{
    $information_arr = array();
    if(!Core_Session::is_super()){
      $staff_id = Core_Session::get_staff_id();
      $users = Model_User::find(array("where"=>array(array("stuff_id","=",$staff_id),
                                                     array("party_day",">=",date("Y-m-d")))));
      foreach($users as $user){
        $arr = $user->get_hotel_message();
        $information_arr = array_merge($information_arr,$arr);
      }
    }
	}

}