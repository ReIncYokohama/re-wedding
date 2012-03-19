<?php
class Model_Usertablelog extends Model_Crud{
  static $_table_name = "spssp_change_log";
  static $_fields = array("id","plan_id","user_id","admin_id","guest_id","previous_status","current_status","type","date","guest_name");
  protected static $_primary_key = 'id';

  //ログ(spssp_change_log)のtypeタイプから対象の画面のテキストを取得
  static function get_screen_name_by_log_type($type){
    switch($type){
      case 1:
        return "席次表情報";
      case 5:
        return "招待者リストの作成";
      default:
        return "招待者リスト";
    }
  }
  //ログの修正種類の取得
  static function get_kind_by_log_type($type,$table_prev="",$table_next=""){
    switch($type){
    case 1:
      if($table_prev == "") return "新規";
      if($table_next == "") return "削除";
      return "移動";
    case 2:
      return "変更";
    case 3:
      return "削除";
    case 4:
      return "新規";
    case 5:
      return "新規";
    }
  }
}

