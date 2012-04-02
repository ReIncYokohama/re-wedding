<?php
class Controller_Common extends Controller
{
  public $auto_render = true;
  
  public $template = "templates/index.php";
  public $var = array();

  public function after($response){
    if($this->auto_render === true){
      $request = $this->request;
      $segments = $request->route->segments;
      $contents = $this->var;
      $contents["contents"] = "";
      if(count($segments)>0){
        if(count($segments)==1) $segments[1] = "index";
        
        $contents['contents'] = View::forge($segments[0].'/'.$segments[1]);
      }
      $body = View::forge($this->template,$contents);
      $response = $this->response;
      $response->body = $body;
    }
    return parent::after($response);
  }
}