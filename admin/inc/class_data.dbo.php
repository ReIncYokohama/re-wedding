<?php
include_once('dbcon.inc.php');
include_once("class.dbo.php");
class DataClass extends DBO{
  public function DataClass()
  {
  }
  
  //席次表のログ情報を取得する。
  //アクセス日時、ログイン名、アクセス画面名、修正対象者、修正種別、変更項目名、変更前情報、変更後情報
  //acccess_time,login_name,screen_name,target_user,kind,target_category,before_data,after_data
  public function get_all_log($user_id)
  {
    $data_rows = $this->getRowsByQuery("select * from spssp_change_log where user_id = $user_id order by date ASC");
    //削除したユーザのログが見えないようにするために、現在いるゲストのデータのみ受け取る。
    //もしくは削除(type=3)のデータをうけとる。
    //$data_rows = $this->getRowsByQuery("select * from spssp_change_log where user_id = $user_id and( guest_id in (select id from spssp_guest where user_id = $user_id)) or type= 3 order by date ASC");
    
    $returnArray = array();
    foreach($data_rows as $row)
    {
      $line = array();
      $line["access_time"] = $this->get_date_dashes_convert($row["date"]);
      $line["login_name"] = $this->get_access_user_name($row["admin_id"]);
      $line["screen_name"] = $this->get_screen_name_by_log_type($row["type"]);
      $line["target_user"] = $this->get_target_user_by_log_type($row["type"],$row["guest_id"],$row["guest_name"]);
      $line["kind"] = $this->get_kind_by_log_type($row["type"],$row["previous_status"]);
      list($line["target_category"],$line["previous_status"],$line["current_status"])
        = $this->get_log_change_contents($row["type"],$row["previous_status"],$row["current_status"],$row["user_id"]);
      array_push($returnArray,$line);
    }
    return $returnArray;
  }
  
  //ログ(spssp_change_log)のtypeタイプから対象の画面のテキストを取得
  public function get_screen_name_by_log_type($type_id){
    switch($type_id){
      case 1:
        return "席次表情報";
      default:
        return "招待者リストの作成";
    }
  }
  
  //ログの対象ユーザの取得
  public function get_target_user_by_log_type($type_id,$guest_id,$guest_name = "")
  {
    if($type_id!=3){
      $guest_name = $this->get_guest_name($guest_id);
      return $guest_name;
    }
    return $row["guest_name"];
  }
  
  //ログの修正種類の取得
  public function get_kind_by_log_type($type_id,$table_prev){
    switch($type_id){
    case 1:
      if($table_prev == "") return "新規";
      return "移動";
    case 2:
      return "変更";
    case 3:
      return "削除";
    case 4:
      return "新規";
    }
  }
  
  //編集内容および編集箇所のデータの取得
  //return target_category_arr,before_data_arr,after_data_arr
  public function get_log_change_contents($type_id,$before_data,$after_data,$user_id){
    if($type_id >= 2){
      $now_before_data_arr = explode("|",$before_data);
      $now_after_data_arr = explode("|",$after_data);
      list($target_category_arr,$before_data_arr,$after_data_arr)
        = $this->get_log_guest_profile($now_before_data_arr,$now_after_data_arr,$user_id);
      return array(implode("<br>",$target_category_arr),
                   implode("<br>",$before_data_arr),
                   implode("<br>",$after_data_arr));
    }else{
      $before_data = $this->get_seat_and_table_name($before_data,$user_id);
      $after_data = $this->get_seat_and_table_name($after_data,$user_id);
      return array("席次表",$before_data,$after_data);
    }
  }
  
  public function get_seat_and_table_name($seat_id,$user_id)
  {
    if($seat_id == "" || !$seat_id) return "";
    $table_id=$this->GetSingleData("spssp_default_plan_seat","table_id"," id=".$seat_id." limit 1");
    if(!$table_id) return "";
    $table_details=$this->getSingleRow("spssp_default_plan_table"," id=".$table_id." limit 1");
    $tbl_row = $this->getSingleRow("spssp_table_layout"," table_id=".$table_details['id']." and user_id=".$user_id." limit 1");
    $new_name_row = $this->getSingleRow("spssp_user_table"," default_table_id=".$tbl_row['id']." and user_id=".$user_id." limit 1");
    
    if(!empty($new_name_row))
      {
        $tblname = $this->getSingleData("spssp_tables_name","name","id=".$new_name_row['table_name_id']);    
      }
    else
      {
        $tblname = $tbl_row['name'];
        
      }
    
    $seats = $this->getRowsByQuery("select * from spssp_default_plan_seat where table_id =".$table_id." order by id asc ");

    $j=1;
    foreach($seats as $seat)
      {
        if($seat['id']==$seat_id)
          {
            $seat_pos=$j;
            break;
          }
        $j++;
      }
    return $tblname."/".$seat_pos;
  }

  //編項目と変更データの配列の取得
  public function get_log_guest_profile($before_data_arr,$after_data_arr){
    $return_before_data_arr = array();
    $return_after_data_arr = array();
    $return_category_data_arr = array();
    
    foreach($before_data_arr as $key => $value)
    {
      if($b_val != $after_data_arr[$key])
      {
        $a_val = $after_data_arr[$key];
        switch($key){
        case 0:
          array_push($return_before_data_arr,get_host_area($b_val));
          array_push($return_after_data_arr,get_host_area($a_val));
          array_push($return_category_data_arr,"新郎新婦側");
          break;
        case 1:
          array_push($return_before_data_arr,$b_val);
          array_push($return_after_data_arr,$a_val);
          array_push($return_category_data_arr,"姓");
          break;
        case 2:
          array_push($return_before_data_arr,$b_val);
          array_push($return_after_data_arr,$a_val);
          array_push($return_category_data_arr,"ふりがな姓");
          break;
        case 3:
          array_push($return_before_data_arr,$b_val);
          array_push($return_after_data_arr,$a_val);
          array_push($return_category_data_arr,"名");
          break;
        case 4:
          array_push($return_before_data_arr,$b_val);
          array_push($return_after_data_arr,$a_val);
          array_push($return_category_data_arr,"ふりがな名");
          break;
        case 5:
          array_push($return_before_data_arr,$this->get_respect($b_val));
          array_push($return_after_data_arr,$this->get_respect($a_val));
          array_push($return_category_data_arr,"敬称");
          break;
        case 6:
          array_push($return_before_data_arr,$this->get_guest_type($b_val));
          array_push($return_after_data_arr,$this->get_guest_type($a_val));
          array_push($return_category_data_arr,"区分");
          break;
        case 7:
          array_push($return_before_data_arr,$b_val);
          array_push($return_after_data_arr,$a_val);
          array_push($return_category_data_arr,"肩書1");
          break;
        case 8:
          array_push($return_before_data_arr,$b_val);
          array_push($return_after_data_arr,$a_val);
          array_push($return_category_data_arr,"肩書2");
          break;
        case 9:
          array_push($return_before_data_arr,$b_val);
          array_push($return_after_data_arr,$a_val);
          array_push($return_category_data_arr,"特記");
          break;
        case 10:
          array_push($return_before_data_arr,$this->get_gift_group($b_val));
          array_push($return_after_data_arr,$this->get_gift_group($a_val));
          array_push($return_category_data_arr,"引出物");
          break;
        case 11:
          array_push($return_before_data_arr,$this->get_menu_group($b_val));
          array_push($return_after_data_arr,$this->get_menu_group($a_val));
          array_push($return_category_data_arr,"料理");
          break;
        case 12:
          array_push($return_before_data_arr,$this->get_seat_type($b_val));
          array_push($return_after_data_arr,$this->get_seat_type($a_val));
          array_push($return_category_data_arr,"席種別");
          break;
        case 13:
          array_push($return_before_data_arr,$this->get_takasago_seat_name($b_val));
          array_push($return_after_data_arr,$this->get_takasago_seat_name($a_val));
          array_push($return_category_data_arr,"高砂席名");
          break;
        }
      }
    }
    return array($return_category_data_arr,$return_before_data_arr,$return_after_data_arr);
  }
  
  
  public function get_guest_name($guest_id)
  {
    $guest_name2=$this->GetSingleData("spssp_guest","last_name"," id='".$guest_id."'");
    $guest_name=$this->GetSingleData("spssp_guest","first_name"," id='".$guest_id."'");
    $guest_name = $guest_name2."&nbsp;".$guest_name;
    return $guest_name;
  }
  
  //admin_idから管理者名を取得する。
  public function get_access_user_name($admin_id)
  {
    $stuff_name = $this->GetSingleData("spssp_admin ","name"," id='".$admin_id ."'");
    if($stuff_name == "") $stuff_name = "お客様";
    return $stuff_name;
  }
  //新郎新婦側をテキストで返す。
  public function get_host_area($guest_sex){
    return ($guest_sex=="Male")?"新郎側":"新婦側";
  }
  //敬称をテキストで返す。
  public function get_respect($respect_id){
    include("admin/inc/main_dbcon.inc.php");
    $respect = $this->GetSingleData(" spssp_respect ", "title", " id='".$respect_id."'");
    include("admin/inc/return_dbcon.inc.php");
    return $respect;
  }
  //区分をテキストで返す。
  public function get_guest_type($guest_type_id){
    include("admin/inc/main_dbcon.inc.php");
    $guest_type = $this->GetSingleData(" spssp_guest_type ", "name", " id='".$guest_type_id."'");
    include("admin/inc/return_dbcon.inc.php");
    return $guest_type;
  }
  //ギフトグループのデータをテキストで返す。
  public function get_gift_group($gift_group_id,$user_id){
    $gift_name = $this->GetSingleData(" spssp_gift_group ", "name", " id='".$gift_group_id."' and  user_id = ".$user_id);
    return $gift_name;
  }
  //メニューグループのデータをテキストで返す。
  public function get_menu_group($menu_group_id,$user_id){
    $menu_name = $obj->GetSingleData(" spssp_menu_group ", "name", " id='".$menu_group_id."' and user_id = ".$user_id);
    return $menu_name;
  }
  //座席の種類をテキストで返す。
  public function get_seat_type($seat_type_id){
    return ($seat_type_id==0)?"招待席":"高砂席";
  }
  //高砂席の種類を返す。
  public function get_takasago_seat_name($takasago_seat_type_id){
    $stage_guest = "";
    if($value=="1")
      $stage_guest="媒酌人1";
    if($value=="2")
      $stage_guest="媒酌人2";
    if($value=="3")
      $stage_guest="媒酌人3";
    if($value=="4")
      $stage_guest="媒酌人4";
    if($value=="5")
      $stage_guest="お子様";
    return $stage_guest;
  }
  
}
