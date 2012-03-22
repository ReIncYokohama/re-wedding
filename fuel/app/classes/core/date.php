<?php
class Core_Date extends Date{
  //今日の日付からx日と比較する
  //+3 今日から３日後と比較
  //-3 対象の日の３日後と今日を比較 3日後含む
  public function past_date($day=0){
    $date = getdate();
    if(mktime(0,0,0,$date["mon"],$date["mday"]+$day,$date["year"])<=$this->timestamp) return true;
    return false;
  }
  public function get_wday(){
    $wday = date("w",$this->timestamp);
    $day = array("日", "月", "火", "水", "木", "金", "土");
    return $day[$wday];
  }
}
