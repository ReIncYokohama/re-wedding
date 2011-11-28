<?php
include_once(__FILE__."/../conf/conf.php");
include_once("dbcon.inc.php");
include_once("class.dbo.php");

class HotelDBO extends DBO
{
  public function HotelClass(){
  }
  public function getNextHotelCode(){
    //次のホテルコードの取得
    $now_hotelcode = $this->GetSingleData("super_spssp_hotel","hotel_code","1=1 order by hotel_code DESC LIMIT 1");
    $next_hotelcode = (int)$now_hotelcode;
    ++$next_hotelcode;
    return $this->getStr($next_hotelcode,4);
  }
  public function getStr($str,$num){
    $str = (String)$str;
    $strlen = strlen($str);
    for($i=0;$i<$num-$strlen;++$i){
      $str = "0".$str;
    }
    return $str;
  }
  public function getAdminCode($hotel_code){
    $str = $this->getStr($hotel_code,8);
    return "AA".$str;
  }
}


