<?php
class Core_Date extends Date{
  //�����̓��t����x���Ɣ�r����
  public function past_date($day=0){
    if(time()+DAY*$day<$this->timestamp) return true;
    return false;
  }
}
