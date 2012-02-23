<?php
class Core_Date extends Date{
  //今日の日付からx日と比較する
  public function past_date($day=0){
    $date = getdate();
    if(mktime(0,0,0,$date["mon"],$date["mday"],$date["year"])+Core_Date::DAY*$day<$this->timestamp) return true;
    return false;
  }
  public function get_wday(){
    $wday = date("w",$this->timestamp);
    $day = array("日", "月", "火", "水", "木", "金", "土");
    return $day[$wday];
  }
}
