<?php
class Core_Date extends Date{
  //¡“ú‚Ì“ú•t‚©‚çx“ú‚Æ”äŠr‚·‚é
  public function past_date($day=0){
    if(time()+DAY*$day<$this->timestamp) return true;
    return false;
  }
}
