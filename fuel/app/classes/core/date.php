<?php
class Core_Date extends Date{
  //今日の日付からx日と比較する
  public function past_date($day=0){
    if(time()+DAY*$day<$this->timestamp) return true;
    return false;
  }
}
