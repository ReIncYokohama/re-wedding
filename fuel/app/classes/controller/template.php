<?php
class Controller_Template extends Controller
{
  public $auto_render = true;
  
  public $template = "template/index.php";
  public $var = array();
  
  public function before(){
  }
  public function after($response){
    if($this->auto_render === true){
      $request = $this->request;
      $segments = $request->route->segments;
      $contents = $this->var;
      $contents["contents"] = "";
      if(count($segments)>0){
        if(count($segments)==1) $segments[1] = "index";
        try{
          $contents['contents'] = View::forge($segments[0].'/'.$segments[1].'.php');
        }catch(Exception $e){
          print_r($e);
          return;
        }
      }
      $this->template = View::forge($this->template,$contents);

      $response = $this->response;
      $response->body = $this->template;
    }
    return parent::after($response);
  }
}