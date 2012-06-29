<?php
/**
 * @group App
 * @group Model
 */

class Test_Model_User extends PHPUnit_Framework_TestCase
{

  public function testPast_deadline_honhatyu(){
    $user = new Model_User();
    //今日 2010年 5月10日
    //披露宴日 2012年 5月22日
    $user->party_day =  date("Y-m-d", strtotime("+12 day"));

    //本発注締切日は5日前
    //2012年 5月10日
    $user->confirm_day_num = 12;
    $this->assertEquals($user->past_deadline_honhatyu(), false);

    //2012年 5月11日
    $user->confirm_day_num = 11;
    $this->assertEquals($user->past_deadline_honhatyu(), false);

    //2012年 5月09日
    $user->confirm_day_num = 13;
    $this->assertEquals($user->past_deadline_honhatyu(), true);
  }

  public function testPast_deadline_honhatyu_alert(){
    $user = new Model_User();
    //今日 2010年 5月10日
    //披露宴日 2012年 5月22日
    $user->party_day =  date("Y-m-d", strtotime("+12 day"));

    //本発注締切日は5日前+7
    //2012年 5月10日
    $user->confirm_day_num = 5;
    $this->assertEquals($user->past_deadline_honhatyu_alert(), true);

    //2012年 5月11日
    $user->confirm_day_num = 4;
    $this->assertEquals($user->past_deadline_honhatyu_alert(), false);

    //2012年 5月12日
    $user->confirm_day_num = 6;
    $this->assertEquals($user->past_deadline_honhatyu_alert(), true);
  }
}