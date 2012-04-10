<?php
include_once(dirname(__file__)."/dbcon.inc.php");
include_once(dirname(__file__)."/class.dbo.php");
include_once(dirname(__file__)."/../../fuel/load_classes.php");
class DataClass extends DBO{
  public function DataClass()
 {
  }
  
  public function set_guest_data_update($set_obj,$user_id,$guest_id,$admin_id){
    $guest = Model_Guest::find_by_pk($guest_id);
    $guest_row = $guest->to_array();
    
    $before_data = array();
    $after_data = array();
    $chagne_log = false;
    //高砂席が招待者席に変わったときに高砂席の卓名も削除する。
    if($guest_row["stage"]!=0 && $set_obj["stage"]==0){
      $set_obj["stage_guest"] = 0;
      $guest->delete_seat();
    }
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
        
        $update_array['admin_id']=$_SESSION["super_user"]?10000:$admin_id;
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
    $layoutname = $this->getSingleData("spssp_plan", "layoutname"," user_id= $user_id");
    if($layoutname==""){
      $layoutname = $this->GetSingleData("spssp_options" ,"option_value" ," option_name='default_layout_title'");
    }
    if($layoutname=="null") $layoutname = "";
    $returnArray["layoutname"] = $layoutname;
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
    $plan = Model_Plan::find_one_by_user_id($user_id);
    $table_data = $this->get_table_data($user_id);
    $guest_rows = $this->getRowsByQuery("select * from spssp_guest where user_id = ".$user_id." and self!=1 and stage_guest=0");
    $man_num = 0;
    $woman_num = 0;
    $attend_num = 0;
    $attend_guests = array();
    for($i=0;$i<count($table_data["rows"]);++$i){
      $row = $table_data["rows"][$i];
      for($j=0;$j<count($row["columns"]);++$j){
        $colum = $row["columns"][$j];
        if(!$colum) continue;
        if($colum["display"]==0) continue;
        $table_data["rows"][$i]["columns"][$j]["name"] = $this->get_table_name($row["columns"][$j]["table_id"],$user_id);
        $seats = $this->getRowsByQuery("select * from spssp_default_plan_seat where table_id = ".$colum["table_id"]." order by id asc");
        for($k=0;$k<count($seats);++$k){
          $seat_detail = $this->get_seat_detail($seats[$k]["id"],$table_data["plan_id"]);
          if(!$seat_detail["guest_id"]) continue;
          for($l=0;$l<count($guest_rows);++$l){
            if($guest_rows[$l]["id"] == $seat_detail["guest_id"]){
              $guest_detail = $this->GetSingleRow("spssp_guest"," id=".$seat_detail["guest_id"]." and self!=1 and stage_guest=0 and user_id=".$user_id);
              $seats[$k]["guest_id"] = $seat_detail["guest_id"];
              //グループの具体的なバリューを代入して受け渡す。
              $guest_detail = $this->set_guest_property_value($guest_detail);
              $seats[$k]["guest_detail"] = $guest_detail;
              $guest_rows[$l]["unset"] = true;
              $seat_id = $seat_detail["id"];
              $guest_rows[$l]["seat_id"] = $seat_id;
              $guest_rows[$l]["table_id"] = $colum["table_id"];
              $guest_rows[$l]["table_name"] = $colum["name"];
              if($guest_detail["sex"] == "Male"){
                ++$man_num;
              }else if($guest_detail["sex"] == "Female"){
                ++$woman_num;
              }
              ++$attend_num;
              array_push($attend_guests,$guest_rows[$l]);
            }
          }
        }
        $table_data["rows"][$i]["columns"][$j]["seats"] = $seats;
      }
    }
    $guest_rows = $this->set_guest_property_values($guest_rows);
    $table_data["guests"] = $guest_rows;
    $table_data["attend_guests"] = $attend_guests;
    $table_data["man_num"] = $man_num;
    $table_data["woman_num"] = $woman_num;
    $table_data["attend_num"] = $attend_num;
    return $table_data;
  }  

  //招待客情報あり
  //以下を追加
  //rows 0 columns 0 seats 0 guest_id,table_id,guest_detail,guests
  //                         guests_num
  //                 name
  //
  public function get_table_data_detail_with_hikidemono($user_id){
    $table_data = $this->get_table_data_detail($user_id);
    $table_data["menu_table"] = $this->get_menu_table($table_data["plot_guests"],$user_id);
    for($i=0;$i<count($table_data["rows"]);++$i){
      $guests_num = 0;
      $columns = $table_data["rows"][$i]["columns"];

      for($j=0;$j<count($columns);++$j){
        $column = $columns[$j];
        $seats = $column["seats"];
        $guests = array();
        $menu_num = 0;
        for($k=0;$k<count($seats);++$k){
          if($seats[$k]["guest_id"]){
            array_push($guests,$seats[$k]["guest_detail"]);
            if($seats[$k]["guest_detail"]["menu_grp"]>0) $menu_num+=1;
          }
        }
        $table_data["rows"][$i]["columns"][$j]["child_menu_num"] = $menu_num;
        $table_data["rows"][$i]["columns"][$j]["guests"] = $guests;
        $gifts = $this->get_gift_table($guests,$user_id);
        $table_data["rows"][$i]["columns"][$j]["gifts"] = $gifts;
      }
    }
    return $table_data;
  }
  
  public function get_seat_detail($seat_id,$plan_id){
    return $this->GetSingleRow("spssp_plan_details"," seat_id=".$seat_id." and plan_id = ".$plan_id);
  }
  public function get_table_id_by_seat_id($seat_id){
    $plan_detail = $this->GetSingleRow("spssp_default_plan_seat"," id=".$seat_id);
    return $plan_detail["table_id"];
  }
  
  //ゲスト情報に加えて引出物情報を追加。
  //columns 0 gifts key,num
  //          seats 0 gift,menu
  public function get_table_data_complete($user_id){
    $table_data = $this->get_table_data($user_id);
    for($i=0;$i<count($table_data["rows"]);++$i){
      $row = $table_data["rows"][$i];
      for($j=0;$j<count($row["columns"]);++$j){
        $colum = $row["columns"][$j];
        if(!$colum) continue;
          $seat_detail = $this->get_seat_detail($seats[$k]["id"],$table_data["plan_id"]);
          if(!$seat_detail["guest_id"]) continue;
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
    $guest_obj["namecard_memo"] = $this->get_guest_image_url($guest_obj["user_id"],$guest_obj["id"],"namecard_memo.png");
    $guest_obj["namecard_memo2"] = $this->get_guest_image_url($guest_obj["user_id"],$guest_obj["id"],"namecard_memo2.png");

    return $guest_obj;
  }
  
  public function get_guest_image_url($user_id,$guest_id,$name){
    $path = Core_Image::get_guest_image_dir_relative($user_id,$guest_id);
    return $path.$name;
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
         ($columns[$i]["align"] != "C" && $columns[$i]["display"]!=1)
         
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
    $update_array['admin_id']=$_SESSION["super_user"]?10000:$admin_id;
    $update_array['type']=4;
    $this->InsertData("spssp_change_log", $update_array);

    return $guest_id;
  }
  //array of man and woman data
  // first_name,last_name,menu_grp,gift_group_id.menu_text,gift_group_text,sex_text,table_name
  public function get_guestdata($user_id){
    $plan_id = $this->get_plan_id($user_id);
    $guestArray = $this->getRowsByQuery("select * from spssp_guest where user_id = $user_id and self != 1 order by sex desc");
    for($i=0;$i<count($guestArray);++$i){
      $guestArray[$i] = $this->get_guest_data_detail($guestArray[$i],$user_id,$plan_id);
    }
    return $guestArray;
  }
  //高砂席のみ取得する
  public function get_guestdata_in_takasago($user_id){
    $plan_id = $this->get_plan_id($user_id);
    $guestArray = $this->getRowsByQuery("SELECT * FROM `spssp_guest` WHERE user_id=".$user_id." and self!=1 and stage_guest!='0' and stage_guest!='' order by display_order DESC");
    for($i=0;$i<count($guestArray);++$i){
      $guestArray[$i] = $this->get_guest_data_detail($guestArray[$i],$user_id,$plan_id);
    }
    return $guestArray;
  }
  public function get_all_guests_num($user_id){
    $plan_id = $this->get_plan_id($user_id);
    $guestArray = $this->getRowsByQuery("SELECT * FROM `spssp_guest` WHERE user_id=".$user_id." order by display_order DESC");
    $num = 0;
    for($i=0;$i<count($guestArray);++$i){
      $guestArray[$i] = $this->get_guest_data_detail($guestArray[$i],$user_id,$plan_id);
      if($guestArray[$i]["self"] == 1){
        $num+=1;
        continue;
      }
      if($guestArray[$i]["stage_guest"] == 1 && $guestArray[$i]["stage_guest"] > 0){
        $num+=1;
        continue;
      }
      if($guestArray[$i]["seat_id"] && $guestArray[$i]["seat_id"] != 0){
        $num+=1;
        continue;
      }

    }
    //print_r($guestArray);    
    return $num;

  }                                     

  //高砂席のみ取得する
  public function get_guestdata_in_host($user_id){
    $plan_id = $this->get_plan_id($user_id);
    $guestArray = $this->getRowsByQuery("SELECT * FROM `spssp_guest` WHERE user_id=".$user_id." and self=1 order by display_order DESC");
    for($i=0;$i<count($guestArray);++$i){
      $guestArray[$i] = $this->get_guest_data_detail($guestArray[$i],$user_id,$plan_id);
    }
    return $guestArray;
  }

  //高砂席のみ取得する
  public function get_guestdata_in_host_for_small_pdf($user_id,$width=110){
    $plan_id = $this->get_plan_id($user_id);
    $guestArray = $this->getRowsByQuery("SELECT * FROM `spssp_guest` WHERE user_id=".$user_id." and self=1 order by sex DESC");
    $returnArray = array();

    include_once(dirname(__file__)."/class_information.dbo.php");
    $infoobj = new InformationClass();

    for($i=0;$i<count($guestArray);++$i){
      $guest = $this->get_guest_data_detail($guestArray[$i],$user_id,$plan_id);
      $returnArray[$i] = "<img src=\"".$infoobj->get_user_name_image_or_src_from_user_side($user_id ,$hotel_id=1, $name="namecard.png",$extra="guests/".$guest['id'],$width,"src")."\" width=\"".$width."\"  />";
    }
    return $returnArray;
  }


  //高砂席のみ取得する
  public function get_guestdata_in_host_for_pdf($user_id,$width=130){
    $plan_id = $this->get_plan_id($user_id);
    $guestArray = $this->getRowsByQuery("SELECT * FROM `spssp_guest` WHERE user_id=".$user_id." and self=1 order by sex DESC");
    $returnArray = array();

    include_once(dirname(__file__)."/class_information.dbo.php");
    $infoobj = new InformationClass();

    for($i=0;$i<count($guestArray);++$i){
      $guest = $this->get_guest_data_detail($guestArray[$i],$user_id,$plan_id);
      $returnArray[$i] = "<img src=\"".$infoobj->get_user_name_image_or_src_from_user_side($user_id ,$hotel_id=1, $name="namecard_memo.png",$extra="guests/".$guest['id'],$width,"src")."\"   width=\"".$width."\" />";
    }
    return $returnArray;
  }


  public function get_guestdata_in_takasago_for_pdf($user_id,$width=130){
    $returnArray = array();
    $guests = $this->get_guestdata_in_takasago($user_id);

    include_once(dirname(__file__)."/class_information.dbo.php");
    $infoobj = new InformationClass();

    foreach($guests as $guest){
      $returnArray[$guest["stage_guest"]] = "<img src=\"".$infoobj->get_user_name_image_or_src_from_user_side($user_id ,$hotel_id=1, $name="namecard_memo.png",$extra="guests/".$guest['id'],$width,"src")."\" width=\"".$width."\" />";
    }
    return $returnArray;
  }
  public function get_guestdata_in_takasago_for_small_pdf($user_id,$width){
    $returnArray = array();
    $guests = $this->get_guestdata_in_takasago($user_id);

    include_once(dirname(__file__)."/class_information.dbo.php");
    $infoobj = new InformationClass();

    foreach($guests as $guest){
      $returnArray[$guest["stage_guest"]] = "<img src=\"".$infoobj->get_user_name_image_or_src_from_user_side($user_id ,$hotel_id=1, $name="namecard.png",$extra="guests/".$guest['id'],$width,"src")."\"   width=\"".$width."px\" />";
    }
    return $returnArray;
  }

  public function get_guest_data_detail($guest_detail,$user_id,$plan_id){
    $guest_detail["menu_text"] = $this->get_menu_group($guest_detail["menu_grp"],$user_id);
    $guest_detail["gift_group_text"] = $this->get_gift_group($guest_detail["gift_group_id"],$user_id);
    $guest_detail["guest_type_text"] = $this->get_guest_type($guest_detail["guest_type"]);
    $guest_detail["sex_text"] = $this->get_host_sex_name($guest_detail["sex"]);
    $seat_id = $this->get_seat_id($plan_id,$guest_detail["id"]);
    $table_id = $this->get_table_id_by_seat_id($seat_id);
    $table_name = $this->get_table_name($table_id,$user_id);
    $guest_detail["table_id"] = $table_id;
    $guest_detail["seat_id"] = $seat_id;
    $guest_detail["table_name"] = $table_name;
    $guest_detail["respect_text"] = $this->get_respect($guest_detail["respect_id"]);
    
    return $guest_detail;
  }
  
  //array of man and woman data
  // first_name,last_name,menu_grp,gift_group_id.menu_text,gift_group_text,sex_text,table_name
  public function get_userdata($user_id){
    $guestArray = $this->getRowsByQuery("select * from spssp_guest where user_id = $user_id and self = 1 order by sex desc");
    $mukoyoshi = $this->GetSingleData("spssp_user","mukoyoshi"," id = ".$user_id);
    include_once(dirname(__file__)."/class_information.dbo.php");
    $infoobj = new InformationClass();
    for($i=0;$i<count($guestArray);++$i){
      $guestArray[$i]["menu_text"] = $this->get_menu_group($guestArray[$i]["menu_grp"],$user_id);
      $guestArray[$i]["gift_group_text"] = $this->get_gift_group($guestArray[$i]["gift_group_id"],$user_id);
      $guestArray[$i]["sex_text"] = $this->get_host_sex_name($guestArray[$i]["sex"]);
      $guestArray[$i]["table_name"] = $this->get_host_table_name($guestArray[$i]["sex"],$mukoyoshi);
      if($guestArray[$i]["sex"]=="Male"){
        $guestArray[$i]["name_image"] = $infoobj->get_user_name_image_or_src_from_user_side($user_id ,$hotel_id=1, $name="man_fullname_only.png",$extra="thumb1");
        $guestArray[$i]["namecard_memo"] = $infoobj->get_user_name_image_or_src_from_user_side($user_id ,$hotel_id=1, $name="namecard_memo.png",$extra="guest/".$guestArray[$i]["id"]."/");
        $guestArray[$i]["fullname"] = $infoobj->get_user_name_image_or_src_from_user_side($user_id ,$hotel_id=1, $name="man_fullname.png",$extra="/thumb2");
      }else{
        $guestArray[$i]["name_image"] = $infoobj->get_user_name_image_or_src_from_user_side($user_id ,$hotel_id=1, $name="woman_fullname_only.png",$extra="thumb1");
        $guestArray[$i]["fullname"] = $infoobj->get_user_name_image_or_src_from_user_side($user_id ,$hotel_id=1, $name="woman_fullname.png",$extra="/thumb2");
        $guestArray[$i]["namecard_memo"] = $infoobj->get_user_name_image_or_src_from_user_side($user_id ,$hotel_id=1, $name="namecard_memo.png",$extra="guest/".$guestArray[$i]["id"]."/");
      }
    }
    return $guestArray;
  }
  
  public function set_log_guest_delete($user_id,$guest_id,$admin_id){
		$update_array['date']=date("Y-m-d H:i:s");
		$update_array['guest_id']=$guest_id;
		$update_array['user_id']=$user_id;
		$update_array['guest_name']=$this->get_guest_name($guest_id);
		$update_array['admin_id']=$_SESSION["super_user"]?10000:$admin_id;
		$update_array['type']=3;
	  $this->InsertData("spssp_change_log", $update_array);
  }
  
  
  //席次表のログ情報を取得する。
  //アクセス日時、ログイン名、アクセス画面名、修正対象者、修正種別、変更項目名、変更前情報、変更後情報
  //acccess_time,login_name,screen_name,target_user,kind,targe_category,before_data,after_data
  public function get_all_log($user_id)
  {
    //date desc  降順にする場合。新しいログを上にする。
    $data_rows = $this->getRowsByQuery("select * from spssp_change_log where user_id = $user_id order by date");
    //削除したユーザのログが見えないようにするために、現在いるゲストのデータのみ受け取る。
    //もしくは削除(type=3)のデータをうけとる。
    //$data_rows = $this->getRowsByQuery("select * from spssp_change_log where user_id = $user_id and( guest_id in (select id from spssp_guest where user_id = $user_id)) or type= 3 order by date ASC");
    
    $returnArray = array();

    foreach($data_rows as $row)
    {
      $line = array();
      $line["access_time"] = $this->get_time_convert($row["date"]);
      $line["login_name"] = $this->get_access_user_name($row["admin_id"]);
      $line["screen_name"] = $this->get_screen_name_by_log_type($row["type"]);
      $line["target_user"] = $this->get_target_user_by_log_type($row["type"],$row["guest_id"],$row["guest_name"]);
      $line["kind"] = $this->get_kind_by_log_type($row["type"],$row["previous_status"],$row["current_status"]);
      list($line["target_category"],$line["previous_status"],$line["current_status"])
        = $this->get_log_change_contents($row["type"],$row["previous_status"],$row["current_status"],$row["user_id"]);
      array_push($returnArray,$line);
    }
    return $returnArray;
  }
  
  //ログ(spssp_change_log)のtypeタイプから対象の画面のテキストを取得
  public function get_screen_name_by_log_type($type_id){
    return Model_Usertablelog::get_screen_name_by_log_type($type_id);
  }
  //席次表の更新を行う。
  public function set_guest_seats($upd){
    $state = $upd["state"];
    $guest_id = $upd["guest_id"];
    $seat_id = $upd["seat_id"];
    $plan_id = $upd["plan_id"];
    $update_array = array();
    $update_array['date']=date("Y-m-d H:i:s");
    $update_array['guest_id']=$upd["guest_id"];
    $update_array['user_id']=$_SESSION["userid"];
    $update_array['guest_name']=$this->get_guest_name($guest_id);
    $update_array['admin_id']=$_SESSION["adminid"];
    $update_array['type']=1;
    $update_array['plan_id']=$plan_id;
    switch($state){
      case "insert":
        $this->InsertData("spssp_plan_details",
          array("plan_id"=>$plan_id,"guest_id"=>$guest_id,"seat_id"=>$seat_id));
        $update_array["current_status"]=$seat_id;
        $this->InsertData("spssp_change_log", $update_array);
        break;
      case "move":
        $oldData = $this->GetSingleRow("spssp_plan_details"," plan_id=".$plan_id." and guest_id=".$guest_id);
        $this->UpdateData("spssp_plan_details",
          array("seat_id"=>$upd["seat_id"])," plan_id=".$plan_id." and guest_id=".$guest_id);
        $update_array["current_status"]=$seat_id;
        $update_array["previous_status"]=$oldData["seat_id"];
        $this->InsertData("spssp_change_log", $update_array);
        break;
      case "delete":
        $oldData = $this->GetSingleRow("spssp_plan_details"," plan_id=".$plan_id." and guest_id=".$guest_id);
        $update_array["previous_status"]=$oldData["seat_id"];
        $this->deleteRow("spssp_plan_details"," plan_id=".$plan_id." and guest_id=".$guest_id);
        $this->InsertData("spssp_change_log", $update_array);
        break;
    }
  }
  
  public function set_log_csv_guest($user_id,$plan_id){
    $update_array = array();
    $update_array['date']=date("Y-m-d H:i:s");
    $update_array['user_id']=$user_id;
    $update_array['admin_id']=$_SESSION["super_user"]?10000:$_SESSION["adminid"];
    $update_array['type']=5;
    $update_array['plan_id']=$plan_id;
    $this->InsertData("spssp_change_log", $update_array);
  }
  
  //ログの対象ユーザの取得
  public function get_target_user_by_log_type($type_id,$guest_id,$guest_name = "")
  {
    if($type_id==5) return "リストアップロード";
    if($type_id!=3){
      $guest_name = $this->get_guest_name($guest_id);
      return $guest_name;
    }
    return $guest_name;
  }
  
  //ログの修正種類の取得
  public function get_kind_by_log_type($type_id,$table_prev,$table_next){
    return Model_Usertablelog::get_kind_by_log_type($type_id,$table_prev,$table_next);
  }
  
  //編集内容および編集箇所のデータの取得
  //return target_category_arr,before_data_arr,after_data_arr
  //席次表の移動は、テーブル
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
      return array("<div class='littlebox'>".implode("</div><div class='littlebox'>",$target_category_arr)."</div>",
                   "<div class='littlebox'>".implode("</div><div class='littlebox'>",$before_data_arr)."</div>",
                   "<div class='littlebox'>".implode("</div><div class='littlebox'>",$after_data_arr)."</div>");
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
  public function get_seat_id($plan_id,$guest_id){
    return $this->GetSingleData("spssp_plan_details","seat_id"," plan_id=".$plan_id." and guest_id=".$guest_id);
  }
  public function get_plan_id($user_id){
    return $this->GetSingleData("spssp_plan", "id","user_id=".$user_id);
  }
  
  public function get_table_name($table_id,$user_id){
    $usertable = Model_Usertable::find_one_by(array("where" => array("table_id","=",$table_id),array("user_id","=",$user_id)));
    if($usertable)
      return $usertable->get_table_name();
    else
      return "";
  }

  //編項目と変更データの配列の取得
  public function get_log_guest_profile($before_data_arr,$after_data_arr,$user_id){
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
    if($admin_id==10000) return "印刷会社";
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
  //新郎新婦側をテキストで返す。
  public function get_host_sex_name($guest_sex){
    if($guest_sex=="Male")  return "新郎";
    if($guest_sex=="Female")  return "新婦";
    return "";
  }
  //新郎新婦側をテキストで返す。
  public function get_host_table_name($guest_sex,$mukoyoshi){
    if(($guest_sex=="Male" && $mukoyoshi == 0) || 
       ($guest_sex=="Female" && $mukoyoshi == 1))  return "高砂1";
    if(($guest_sex=="Male" && $mukoyoshi == 1) || 
       ($guest_sex=="Female" && $mukoyoshi == 0))  return "高砂2";
    return "";
  }
  //敬称をテキストで返す。
  public function get_respect($respect_id){
    include(dirname(__file__)."/main_dbcon.inc.php");
    $respect = $this->GetSingleData(" spssp_respect ", "title", " id='".$respect_id."'");
    include(dirname(__file__)."/return_dbcon.inc.php");
    if($respect=="なし") return "";
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
  //メニューグループのデータをテキストで返す。
  public function get_menu_groups($user_id){
    $menu_arr = $this->getRowsByQuery(" select * from spssp_menu_group where user_id = ".$user_id);
    return $menu_arr;
  }

  //座席の種類をテキストで返す。
  public function get_seat_type($seat_type_id){
    return ($seat_type_id==0)?"招待席":"高砂席";
  }
  //高砂席の種類を返す。
  public function get_takasago_seat_name($takasago_seat_type_id){
    $stage_guest = "";
    switch($takasago_seat_type_id){
    case "1":
      return "媒酌人1";
    case "2":
      return "媒酌人2";
    case "3":
      return "媒酌人3";
    case "4":
      return "媒酌人4";
    case "5":
      return "お子様";
    }
    return "";
  }
  //guest_idからギフト名を返す
  public function get_gift_name($user_id,$guest_id){
    $gift_id = $this->GetSingleData(" spssp_guest_gift ", "group_id", " guest_id=".$guest_id." and user_id = ".$user_id);
    $gift_name='';
		if((int)$gift_id > 0)
		{
      $gift_name = $this->get_gift_name_by_gift_id($gift_id);
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
    $user_obj = str_replace(" ", "", $user_obj);
    $user_obj = str_replace("　", "", $user_obj);
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
    if($user_obj["furigana_last"] != "" && $this->haveKana($user_obj["furigana_last"])){
      array_push($messageArray,$top_message."姓のふりがなはカタカナでなはく、平仮名で入力してください[".$user_obj["furigana_last"]."]");
    }else if(!$this->haveFurigana($user_obj["furigana_last"])){
      array_push($messageArray,$top_message."姓のふりがなは平仮名で入力してください[".$user_obj["furigana_last"]."]");
    }
    if($user_obj["furigana_last"] != "" && $this->haveKana($user_obj["furigana_first"])){
      array_push($messageArray,$top_message."名のふりがなはカタカナではなく、平仮名で入力してください[".$user_obj["furigana_first"]."]");
    }else if(!$this->haveFurigana($user_obj["furigana_first"])){
      array_push($messageArray,$top_message."名のふりがなは平仮名で入力してください[".$user_obj["furigana_first"]."]");
    }

    if($user_obj["respect"] != "" && !$this->haveRespect($user_obj["respect"])){
      array_push($messageArray,$top_message."正しい敬称を入力してください。s[".$user_obj["respect"]."]");
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
    if ($str == "" || preg_match("/^[ぁ-んー]*$/u", $str)) {
      return true;
    }
    return false;
  }
  public function haveKana($str){
    mb_regex_encoding("UTF-8");
    if ($str == "" || preg_match("/^[ァ-ンー]*$/u", $str)) {
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
    //全角および半角の空白の場合は許可する。
    $sex_text = str_replace(" ","",$sex_text);
    $sex_text = str_replace("　","",$sex_text);
    if($sex_text == "新郎" || $sex_text == "新婦" || $sex_text == ""){
      return true;
    }else{
      return false;
    }
  }
  //受発注後は保存できないように。
  public function get_table_editable($user_id){
    $permission_table_edit = $this->GetSingleData("spssp_plan", "rename_table"," user_id =".$user_id);
    $plan_info = $this->GetSingleRow("spssp_plan"," user_id=".$user_id);
    include_once(dirname(__file__)."/class_information.dbo.php");
    $infoobj = new InformationClass();
    $editable=$infoobj->get_editable_condition($plan_info);
    return $editable;
    if($permission_table_edit==1 && $editable) return true; else return false;
  }
  public function get_download_num($user_id,$admin_id){
    $data = $this->GetSingleRow("download_num"," user_id = '".(int)$user_id."' and admin_id = '".(int)$admin_id."'");
    if(!$data){
      $this->InsertData("download_num",array("num"=>1,"user_id" => (int)$user_id,"admin_id"=>(int)$admin_id));
      return $this->get_num_in_digit(1,2);
    }else{
      $num = $data["num"]+1; 
      $this->UpdateData("download_num",array("num"=>$num)," id=".$data["id"]);
      return $this->get_num_in_digit($num,2);
    }
  }
  public function get_num_in_digit($num,$digit){
    return sprintf("%0".$digit."d", $num);
  }
  //A B C D E
  //3 4 2 2 2
  public function get_gift_table($guestDetailArray,$user_id){
    $giftGroups = $this->get_gift_groups($user_id);
    $returnArray = array();
    for($i=0;$i<count($giftGroups);++$i){
      $num = 0;
      $name = $this->get_gift_name_by_gift_id($giftGroups[$i]["id"]);
      for($j=0;$j<count($guestDetailArray);++$j){
        if($guestDetailArray[$j]["gift_group_id"] == $giftGroups[$i]["id"]) ++$num;
      }
      if($name=="" and $num == 0) continue;
      array_push($returnArray,array("name"=>$name,"num"=>$num,"id"=>$giftGroups[$i]["id"]));
    }
    return $returnArray;
  }
  
  //name,num
  public function get_menu_table($guestDetailArray,$user_id){
    $menuGroups = $this->get_menu_groups($user_id);
    $returnArray = array();
    for($i=0;$i<count($menuGroups);++$i){
      $num = 0;
      $name = $menuGroups[$i]["name"];
      if($name=="") continue;
      for($j=0;$j<count($guestDetailArray);++$j){
        if($guestDetailArray[$j]["menu_grp"] == $menuGroups[$i]["id"]) ++$num;
      }
      array_push($returnArray,array("name"=>$name,"num"=>$num));
    }
    return $returnArray;
  }

  public function get_gift_table_html($guestDetailArray,$user_id){
    $gift_table = $this->get_gift_table($guestDetailArray,$user_id);
    $html = "<table>";
    $nameTr = "<tr>";
    $numTr = "<tr>";
    
    for($i=0;$i<count($gift_table);++$i){
      $nameTr .= "<td align=\"center\" style=\" border:1px solid black;\">".$gift_table[$i]["name"]."</td>";
      $numTr .= "<td align=\"center\"  style=\" border:1px solid black;\">".$gift_table[$i]["num"]."</td>";
    }
    $menu_num = 0;
    for($i=0;$i<count($guestDetailArray);++$i){
      if($guestDetailArray[$i]["menu_grp"]>0){
        ++$menu_num;
      }
    }
    $nameTr .= "<td align=\"center\" style=\" border:1px solid black;\">子</td>";
    $numTr .= "<td align=\"center\"  style=\" border:1px solid black;\">".$menu_num."</td>";
    $nameTr .= "</tr>";
    $numTr .= "</tr>";
    $html.= $nameTr.$numTr."</table>";
    return $html;
  }
  public function get_gift_name_by_gift_id($gift_id){
    return $this->GetSingleData(" spssp_gift_group ", "name", " id=".$gift_id);
  }
  public function set_pdf_data($user_id){
    
  }

}
