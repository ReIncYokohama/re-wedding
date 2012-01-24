<?php

class Controller_User extends Controller
{

	public function action_index()
	{
		return Response::forge(View::forge('welcome/index'));
	}

	public function action_make_plan_full()
	{
    $contents["test"] = "testd";
    $user_id = Core_Session::get_user_id();
    if(!Model_TableLayout::exist($user_id)){
      Response::redirect("table_layout.php?err=13");
    }
    if(!Model_Guest::exist($user_id)){
      Response::redirect("table_layout.php?err=14");
    }
    $plan = Model_Plan::find_by_user_id($user_id);
    if(!$plan){
      Response::redirect("table_layout.php?err=15");
    }
    
    
    
    return Response::forge(View::forge('user/make_plan_full',$contents));
	}

	public function action_404()
	{
		return Response::forge(ViewModel::forge('welcome/404'), 404);
	}
}
