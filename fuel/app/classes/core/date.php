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
  public function equal_date($day=0){
    $date = getdate();
    if(mktime(0,0,0,$date["mon"],$date["mday"]+$day,$date["year"]) <= $this->timestamp
      and $this->timestamp < mktime(0,0,0,$date["mon"],$date["mday"]+$day+1,$date["year"])) return true;
    return false;
  }
  public function past_month($month=0){
    $date = getdate();
    if(mktime(0,0,0,$date["mon"]+$month,$date["mday"],$date["year"])<=$this->timestamp) return true;
    return false;
  }
  public function past_month_last_day($month=0){
    $date = getdate();
    if($month>0){
      $month=$month+1;
    }else if($month<0){
      $month=$month-1;
    }
    if(mktime(0,0,0,$date["mon"]+$month,-1,$date["year"])<=$this->timestamp) return true;
    return false;
  }
  public function get_wday(){
    $wday = date("w",$this->timestamp);
    $day = array("日", "月", "火", "水", "木", "金", "土");
    return $day[$wday];
  }
  static public function convert_month_and_date($date_text){
    $arr=explode("-",$date_text);
		return $arr[1]."/".$arr[2];
  }
  static public function create_from_date_string($date_text){
    $arr=explode("-",$date_text);
    return Core_Date::create_from_string( $arr[1]."/".$arr[2]."/".$arr[0],"us");
  }
  public function format_string_date(){
    $date = getdate($this->timestamp);
    $wday = array("日","月","火","水","木","金","土");
    return $date["mon"]."月".$date["mday"]."日(".$wday[$date["wday"]].")";
  }
}
