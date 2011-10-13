<?php
include_once(dirname(__file__).'/dbcon.inc.php');
include_once(dirname(__file__)."/class.dbo.php");
class DataClass extends DBO{
  public function DataClass()
  {
  }
  
  public function set_guest_data_update($set_obj,$user_id,$guest_id,$admin_id){
    $guest_row = $this->GetSingleRow(" spssp_guest ", " id=".(int)$guest_id);
    
    $before_data = array();
    $after_data = array();
    $chagne_log = false;
    foreach($set_obj as $key => $value){
      if($guest_row[$key] != $value and !($guest_row[$key] == 0 and $value == "") ){
        $before_data[$key] = $guest_row[$key];
        $after_data[$key] = $value;
        $chagne_log = true;
      }
    }
    
    if($chagne_log)
      {
        $before=json_encode($before_data);
        $after=json_encode($after_data);
        
        $update_array['date']=date("Y-m-d H:i:s");
        $update_array['guest_id']=$guest_id;
        $update_array['user_id']=$user_id;
        $update_array['previous_status']=$before;
        $update_array['current_status']=$after;
        $update_array['admin_id']=$admin_id;
        $update_array['type']=2;
        $lastids = $this->InsertData("spssp_change_log", $update_array);
      }
    $this->UpdateData("spssp_guest",$set_obj," id=".(int)$guest_id);
  }
  //招待客情報なし
  /*
    layoutname
    plan_id
    seat_num
    rows 0 columns 0 name,table_id
           ralign,num_first,num_last,num_none,display_rate,display_num
  */
  public function get_table_data($user_id){
    $returnArray = array("layoutname" => "","rows"=>array());
    //テーブル情報の配列
    $table_arr = $this->getRowsByQuery("select * from spssp_table_layout where user_id=".$user_id);
    
    //plan_info column_number,row_number
    $plan_info = $this->GetSingleRow("spssp_plan"," user_id = ".$user_id);
    $returnArray["plan_id"] = $plan_info["id"];
    $returnArray["seat_num"] = $plan_info["seat_number"];
    //行の最大数を取得
    $table_rows_num = $this->get_table_max_row($table_arr);
    //列の最大数を取得
    $table_columns_num = $plan_info["column_number"];
    
    for($i=1;$i<=$table_rows_num;++$i){
      $row = array("columns"=>array());
      //一列目に必要なテーブルを順番に取得
      $row["columns"] = $this->get_table_rows_by_row_number($table_arr,$i);
      list($row["ralign"],$row["num_first"],$row["num_last"],$row["num_none"],$row["columns"]) = $this->get_columns_param($row["columns"]);
      $row['display_num'] = count($row["columns"])-$row["num_none"];
      $row["display_rate"] = $row["display_num"]/count($row["columns"]);
      array_push($returnArray["rows"],$row);
    }
    return $returnArray;
  }
  
  //招待客情報あり
  //以下を追加
  //rows 0 columns 0 seats 0 guest_id,table_id,guest_detail
  //                 name
  //guests
  public function get_table_data_detail($user_id){
    $table_data = $this->get_table_data($user_id);
    $guest_rows = $this->getRowsByQuery("select * from spssp_guest where user_id = ".$user_id." and self!=1 and stage_guest=0");
    for($i=0;$i<count($table_data["rows"]);++$i){
      $row = $table_data["rows"][$i];
      for($j=0;$j<count($row["columns"]);++$j){
        $colum = $row["columns"][$j];
        if(!$colum) continue;
        $row["columns"][$j]["name"] = $this->get_table_name($row["columns"][$j]["table_id"],$user_id);
        $seats = $this->getRowsByQuery("select * from spssp_default_plan_seat where table_id = ".$colum["table_id"]." order by id asc");
        for($k=0;$k<count($seats);++$k){
          $seat_detail = $this->GetSingleRow("spssp_plan_details"," seat_id=".$seats[$k]["id"]." and plan_id = ".$table_data["plan_id"]);
          for($l=0;$l<count($guest_rows);++$l){
            if($guest_rows[$l]["id"] == $seat_detail["guest_id"]){
              $guest_detail = $this->GetSingleRow("spssp_guest"," id=".$seat_detail["guest_id"]." and self!=1 and stage_guest=0 and user_id=".$user_id);
              $seats[$k]["guest_id"] = $seat_detail["guest_id"];
              //グループの具体的なバリューを代入して受け渡す。
              $guest_detail = $this->set_guest_property_value($guest_detail);
              $seats[$k]["guest_detail"] = $guest_detail;
              $guest_rows[$l]["unset"] = true;
            }
          }
        }
        $table_data["rows"][$i]["columns"][$j]["seats"] = $seats;
      }
    }
    $guest_rows = $this->set_guest_property_values($guest_rows);
    $table_data["guests"] = $guest_rows;
    return $table_data;
  }
  
  //ゲスト情報に加えて引出物情報を追加。
  //columns 0 gifts key,num
  //          seats 0 gift,menu
  public function get_table_data_complete($user_id){
    $table_data_detail = $this->get_table_data($user_id);
    
    for($i=0;$i<count($table_data["rows"]);++$i){
      $row = $table_data["rows"][$i];
      for($j=0;$j<count($row["columns"]);++$j){
        $colum = $row["columns"][$j];
        if(!$colum) continue;
        
        for($k=0;$k<count($colum["seats"]);++$k){
          
        }

          $seat_detail = $this->GetSingleRow("spssp_plan_details"," seat_id=".$seats[$k]["id"]." and plan_id = ".$table_data["plan_id"]);
          for($l=0;$l<count($guest_rows);++$l){
            if($guest_rows[$l]["id"] == $seat_detail["guest_id"]){
              $guest_detail = $this->GetSingleRow("spssp_guest"," id=".$seat_detail["guest_id"]." and self!=1 and stage_guest=0 and user_id=".$user_id);
              $seats[$k]["guest_id"] = $seat_detail["guest_id"];
              //グループの具体的なバリューを代入して受け渡す。
              $guest_detail = $this->set_guest_property_value($guest_detail);
              $seats[$k]["guest_detail"] = $guest_detail;
              $guest_rows[$l]["unset"] = true;
            }
          }
        }
        $table_data["rows"][$i]["columns"][$j]["seats"] = $seats;
      }
  }
  
  public function set_guest_property_values($guest_rows){
    for($i=0;$i<count($guest_rows);++$i){
      $guest_rows[$i] = $this->set_guest_property_value($guest_rows[$i]);
    }
    return $guest_rows;
  }
  
  public function set_guest_property_value($guest_obj){
    $guest_obj["guest_type_value"] = $this->get_guest_type($guest_obj["guest_type"]);
    $guest_obj["name_plate"] = $this->get_guest_image_url($guest_obj["user_id"],$guest_obj["id"],"namecard.png");
    return $guest_obj;
  }
  
  public function get_guest_image_url($user_id,$guest_id,$name){
    return BASE_URL."name_image/hotel1/user_name/".$user_id."/guest/".$guest_id."/".$name;
  }
  public function get_guest_detail($user_id,$guest_id){
    $guest_detail = $this->GetSingleRow("spssp_guest"," id=".$guest_id." and user_id=".$user_id);
    return $guest_detail;
  }
  
  public function get_table_max_row($table_arr){
    $max = 0;
    for($i=0;$i<count($table_arr);++$i){
      if($max < $table_arr[$i]["row_order"]){
        $max = $table_arr[$i]["row_order"];
      }
    }
    return $max;
  }
  
  //rows,num_first,num_last,align,columns
  //columns で並び替えして返す
  public function get_table_rows_by_row_number($table_arr,$row_number){
    $align = "";
    $columns = array();
    for($i=0;$i<count($table_arr);++$i){
      if($table_arr[$i]["row_order"] == $row_number){
        $insert = false;
        for($j=0;$j<count($columns);++$j){
          if($columns[$j]["colum_order"]>$table_arr[$i]["colum_order"]){
            array_splice($columns,$j,0,$table_arr[$i]);
            $insert = true;
            break;
          }
        }
        if(!$insert) array_push($columns,$table_arr[$i]);
      }
    }
    return $columns;
  }
  //columnsに含まれるvisibilityは使ってない。visibleを利用する。
  public function get_columns_param($columns){
    $first = false;
    $last = 0;
    for($i=0;$i<count($columns);++$i){
      if($columns[$i]["display"] == 1){
        if($first===false) $first = $i+1;
        $last = $i+1;
      }
    }
    for($i=0;$i<count($columns);++$i){
      if(($columns[$i]["align"] == "C" && $first-1<=$i && $last>$i && $columns[$i]["display"]!=1)||
         ($columns[$i]["align"] == "N" && $columns[$i]["display"]!=1)
         ){
        $columns[$i]["visible"] = true;
      }
    }
    return array($columns[0]["align"],$first,$last,count($columns)-$last+$first-1,$columns);
  }
  
  public function set_guest_data_insert($set_obj,$user_id,$admin_id){
    $guest_id=$this->InsertData("spssp_guest",$set_obj);
    
    $update_array['date']=date("Y-m-d H:i:s");
    $update_array['guest_id']=$guest_id;
    $update_array['user_id']=$user_id;
    $update_array['admin_id']=$admin_id;
    $update_array['type']=4;
    $this->InsertData("spssp_change_log", $update_array);

    return $guest_id;
  }
  
  public function set_log_guest_delete($user_id,$guest_id,$admin_id){
		$update_array['date']=date("Y-m-d H:i:s");
		$update_array['guest_id']=$guest_id;
		$update_array['user_id']=$user_id;
		$update_array['guest_name']=$this->get_guest_name($guest_id);
		$update_array['admin_id']=$admin_id;
		$update_array['type']=3;
	  $this->InsertData("spssp_change_log", $update_array);
  }
  
  //席次表のログ情報を取得する。
  //アクセス日時、ログイン名、アクセス画面名、修正対象者、修正種別、変更項目名、変更前情報、変更後情報
  //acccess_time,login_name,screen_name,target_user,kind,target_category,before_data,after_data
  public function get_all_log($user_id)
  {
    $data_rows = $this->getRowsByQuery("select * from spssp_change_log where user_id = $user_id order by date desc");
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
        return "招待者リスト";
    }
  }
  //席次表の更新を行う。
  public function set_guest_seats($upd){
    $state = $upd["state"];
    switch($state){
      case "insert":
        $this->InsertData("spssp_plan_details",
          array("plan_id"=>$upd["plan_id"],"guest_id"=>$upd["guest_id"],"seat_id"=>$upd["seat_id"]));
        break;
      case "move":
        $this->UpdateData("spssp_plan_details",
          array("seat_id"=>$upd["seat_id"])," plan_id=".$upd["plan_id"]." and guest_id=".$upd["guest_id"]);
        break;
      case "delete":
        $this->deleteRow("spssp_plan_details"," plan_id=".$upd["plan_id"]." and guest_id=".$upd["guest_id"]);
        break;
    }
  }
  
  //ログの対象ユーザの取得
  public function get_target_user_by_log_type($type_id,$guest_id,$guest_name = "")
  {
    if($type_id!=3){
      $guest_name = $this->get_guest_name($guest_id);
      return $guest_name;
    }
    return $guest_name;
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
      $now_before_data_arr = json_decode($before_data,true);
      $now_after_data_arr = json_decode($after_data,true);
      
      //デコードに失敗した場合は、NULLなので、連想配列に統一する。
      //古いコードの場合、表示されない。
      if(!$now_before_data_arr) $now_before_data_arr = array();
      if(!$now_after_data_arr) $now_after_data_arr = array();
      
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
    $tblname = $this->get_table_name($table_id,$user_id);
    
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
  
  public function get_table_name($table_id,$user_id){
    $table_details=$this->getSingleRow("spssp_default_plan_table"," id=".$table_id." limit 1");
    $tbl_row = $this->getSingleRow("spssp_table_layout"," table_id=".$table_id." and user_id=".$user_id." limit 1");
    $new_name_row = $this->getSingleRow("spssp_user_table"," default_table_id=".$tbl_row['id']." and user_id=".$user_id." limit 1");
    
    if(!empty($new_name_row))
      {
        $tblname = $this->getSingleData("spssp_tables_name","name","id=".$new_name_row['table_name_id']);    
      }
    else
      {
        $tblname = $tbl_row['name'];
      }
    return $tblname;
  }

  //編項目と変更データの配列の取得
  public function get_log_guest_profile($before_data_arr,$after_data_arr){
    $return_before_data_arr = array();
    $return_after_data_arr = array();
    $return_category_data_arr = array();
    
    foreach($before_data_arr as $key => $b_val)
    {
      if($b_val != $after_data_arr[$key])
      {
        $a_val = $after_data_arr[$key];
        switch($key){
        case "sex":
          array_push($return_before_data_arr,$this->get_host_area($b_val));
          array_push($return_after_data_arr,$this->get_host_area($a_val));
          array_push($return_category_data_arr,"新郎新婦側");
          break;
        case "last_name":
          array_push($return_before_data_arr,$b_val);
          array_push($return_after_data_arr,$a_val);
          array_push($return_category_data_arr,"姓");
          break;
        case "furigana_last":
          array_push($return_before_data_arr,$b_val);
          array_push($return_after_data_arr,$a_val);
          array_push($return_category_data_arr,"ふりがな姓");
          break;
        case "first_name":
          array_push($return_before_data_arr,$b_val);
          array_push($return_after_data_arr,$a_val);
          array_push($return_category_data_arr,"名");
          break;
        case "furigana_first":
          array_push($return_before_data_arr,$b_val);
          array_push($return_after_data_arr,$a_val);
          array_push($return_category_data_arr,"ふりがな名");
          break;
        case "respect_id":
          array_push($return_before_data_arr,$this->get_respect($b_val));
          array_push($return_after_data_arr,$this->get_respect($a_val));
          array_push($return_category_data_arr,"敬称");
          break;
        case "guest_type":
          array_push($return_before_data_arr,$this->get_guest_type($b_val));
          array_push($return_after_data_arr,$this->get_guest_type($a_val));
          array_push($return_category_data_arr,"区分");
          break;
        case "comment1":
          array_push($return_before_data_arr,$b_val);
          array_push($return_after_data_arr,$a_val);
          array_push($return_category_data_arr,"肩書1");
          break;
        case "comment2":
          array_push($return_before_data_arr,$b_val);
          array_push($return_after_data_arr,$a_val);
          array_push($return_category_data_arr,"肩書2");
          break;
        case "memo":
          array_push($return_before_data_arr,$b_val);
          array_push($return_after_data_arr,$a_val);
          array_push($return_category_data_arr,"特記");
          break;
        case "gift_group_id":
          array_push($return_before_data_arr,$this->get_gift_group($b_val,$user_id));
          array_push($return_after_data_arr,$this->get_gift_group($a_val,$user_id));
          array_push($return_category_data_arr,"引出物");
          break;
        case "menu_grp":
          array_push($return_before_data_arr,$this->get_menu_group($b_val,$user_id));
          array_push($return_after_data_arr,$this->get_menu_group($a_val,$user_id));
          array_push($return_category_data_arr,"料理");
          break;
        case "stage":
          array_push($return_before_data_arr,$this->get_seat_type($b_val));
          array_push($return_after_data_arr,$this->get_seat_type($a_val));
          array_push($return_category_data_arr,"席種別");
          break;
        case "stage_guest":
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
    $guest_row_name = $this->GetSingleRow(" spssp_guest ", " id=".$guest_id);
    $guest_name = $guest_row_name["last_name"]."&nbsp;".$guest_row_name["first_name"];
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
    if($guest_sex=="Male")  return "新郎側";
    if($guest_sex=="Female")  return "新婦側";
    return "";
  }
  //敬称をテキストで返す。
  public function get_respect($respect_id){
    include(dirname(__file__)."/main_dbcon.inc.php");
    $respect = $this->GetSingleData(" spssp_respect ", "title", " id='".$respect_id."'");
    include(dirname(__file__)."/return_dbcon.inc.php");
    
    return $respect;
  }
  //区分をテキストで返す。
  public function get_guest_type($guest_type_id){
    include(dirname(__file__)."/main_dbcon.inc.php");
    $guest_type = $this->GetSingleData(" spssp_guest_type ", "name", " id='".$guest_type_id."'");
    include(dirname(__file__)."/return_dbcon.inc.php");
    return $guest_type;
  }
  //ギフトグループのデータをテキストで返す。
  public function get_gift_group($gift_group_id,$user_id){
    $gift_name = $this->GetSingleData(" spssp_gift_group ", "name", " id='".$gift_group_id."' and  user_id = ".$user_id);
    return $gift_name;
  }
  //ギフトグループのデータをすべて取得
  public function get_gift_groups($user_id){
    $gift_group_arr = $this->getRowsByQuery("select * from spssp_gift_group where user_id=".$user_id);
    return $gift_group_arr;
  }
  //メニューグループのデータをテキストで返す。
  public function get_menu_group($menu_group_id,$user_id){
    $menu_name = $this->GetSingleData(" spssp_menu_group ", "name", " id='".$menu_group_id."' and user_id = ".$user_id);
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
  //guest_idからギフト名を返す
  public function get_gift_name($user_id,$guest_id){
    $gift_id = $this->GetSingleData(" spssp_guest_gift ", "group_id", " guest_id=".$guest_id." and user_id = ".$user_id);
    $gift_name='';
		if((int)$gift_id > 0)
		{
			$gift_group = $this->GetSingleData(" spssp_gift_group ", "name", " id=".$gift_id);
      $gift_name = $gift_group;
		}
    return $gift_name;
  }
  //料理名を返す
  public function get_menu_name($user_id,$guest_id){
    $menu_id = $this->GetSingleData(" spssp_guest_menu ", "menu_id", " guest_id=".$guest_id." and user_id = ".$user_id);
		$menu_name='';
		if($menu_id > 0)
		{
			$menu_name = $this->GetSingleData(" spssp_menu_group ", "name", " id=".$menu_id." and user_id = ".$user_id);
		}
    return $menu_name;
  }
  public function check_user_data($user_obj,$line_num){
    $messageArray = array();
    $top_message = "";
    if($line_num || $line_num === 0){
      //通常0行目からだから。
      $top_message = ($line_num+1)."行目 ".$user_obj["last_name"]." ".$user_obj["first_name"]."様:";
    }
    if(!$this->haveString($user_obj["last_name"])){
      array_push($messageArray,$top_message."姓を入力してください。[".$user_obj["last_name"]."]");
    }
    if(!$this->haveString($user_obj["first_name"])){
      array_push($messageArray,$top_message."名を入力してください。[".$user_obj["first_name"]."]");
    }
    if(!$this->haveFurigana($user_obj["furigana_last"])){
      array_push($messageArray,$top_message."姓のふりがなは平仮名で入力してください[".$user_obj["furigana_last"]."]");
    }
    if(!$this->haveFurigana($user_obj["furigana_first"])){
      array_push($messageArray,$top_message."名のふりがなは平仮名で入力してください[".$user_obj["furigana_first"]."]");
    }
    if(!$this->haveString($user_obj["respect"]) && !$this->haveRespect($user_obj["respect"])){
      array_push($messageArray,$top_message."正しい敬称を入力してください。[".$user_obj["respect"]."]");
    }
    if(!$this->haveSex($user_obj["sex"])){
      array_push($messageArray,$top_message."新郎新婦側は新郎もしくは新婦で入力してください。[".$user_obj["sex"]."]");
    }
    return $messageArray;
  }
  //入力チェック
  public function haveString($str){
    if(!$str){
      return false;
    }
    return true;
  }
  public function haveFurigana($str){
    mb_regex_encoding("UTF-8");
    if ($str == "" || preg_match("/^[ぁ-ん]*$/u", $str)) {
      return true;
    }
    return false;
  }
  public function haveRespect($respect_text){
    include(dirname(__file__)."/main_dbcon.inc.php");
    $respect = $this->GetSingleData(" spssp_respect ", "id", " title='".$respect_text."'");
    include(dirname(__file__)."/return_dbcon.inc.php");
    if($respect){
      return true;
    }else{
      return false;
    }
  }
  public function haveSex($sex_text){
    if($sex_text == "新郎" || $sex_text == "新婦" || $sex_text == ""){
      return true;
    }else{
      return false;
    }
  }
  
}
