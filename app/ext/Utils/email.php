<?php
//logを残すようにしたい。

class Email{
  public $subject;
  public $body;
  public $to;
  public $from;
  public $fromName;
  public $noneFromName = false;
  public function __construct($to,$subject,$body){
    $this->to = $to;
    $this->subject = $subject;
    $this->body = $body;
  }
  public function send(){
    mb_language("ja");
    mb_internal_encoding("UTF-8");
    $this->to = "sekiduka@re-inc.jp";
    if($this->fromName and $this->from){
      $mailFrom = "From: " . mb_encode_mimeheader (mb_convert_encoding($this->fromName,"JIS","UTF8")) . "<" . $this->from . ">";
      mb_send_mail($this->to,$this->subject,$this->body,$mailFrom);
    }else{
      mb_send_mail($this->to,$this->subject,$this->body);
    }
    
  }
}