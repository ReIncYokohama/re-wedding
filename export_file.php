<?php
@session_start();
include_once("admin/inc/class_data.dbo.php");
include_once("inc/checklogin.inc.php");
include_once("fuel/load_classes.php");

$obj = new DataClass();
$get = $obj->protectXSS($_GET);

$user_id = Core_Session::get_user_id();
$user = Model_User::find_by_pk($user_id);
$plan = Model_Plan::find_one_by_user_id($user_id);

$table_data = $obj->get_table_data_detail_with_hikidemono($user_id);
$guest_models_takasago = Model_Guest::find_by_takasago($user_id);
$takasago_guests = Core_Arr::func($guest_models_takasago,"to_array");
$guestArray = array_merge($takasago_guests,$table_data["guests"]);

function cmp($a, $b) {
  if ((int)$a["id"] == (int)$b["id"]) {
    return 0;
  }
  return ( intval($a["id"]) <  intval($b["id"])) ? -1 : 1;
}
usort($guestArray, 'cmp');

$html .='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>

<body>';

$html .= '<table border="1">';
$html .= '<tr>
<td>郎婦</td>
<td>姓</td>
<td>名</td>
<td>姓ふりがな</td>
<td>名ふりがな</td>
<td>敬称</td>
<td> 区分</td>
<td>肩書１</td>
<td>肩書２</td>
<td>卓名</td>
<td>引出物グループ</td>
<td>料理</td>
<td>特記</td>
</tr>';

for($i=0;$i<count($guestArray);++$i){
  if($guestArray[$i]["self"]==1) continue;
  if($guestArray[$i]["stage"]==1){
    $table_name = $plan->get_layoutname();
    //$table_name = $obj->get_takasago_seat_name($guestArray[$i]["stage_guest"]);
  }else{
    $seat_id = $obj->get_seat_id($plan->id,$guestArray[$i]["id"]);
    $table_id = $obj->get_table_id_by_seat_id($seat_id);
    $table_name = $obj->get_table_name($table_id,$user_id);
  }
  $html .= '<tr>
<td>'.$obj->get_host_sex_name($guestArray[$i]["sex"]).'</td>
<td>'.$guestArray[$i]['last_name'].'</td>
<td>'.$guestArray[$i]['first_name'].'</td>
<td>'.$guestArray[$i]['furigana_last'].'</td>
<td>'.$guestArray[$i]['furigana_first'].'</td>
<td>'.$obj->get_respect($guestArray[$i]["respect_id"]).'</td>
<td>'.$obj->get_guest_type($guestArray[$i]["guest_type"]).'</td>
<td>'.$guestArray[$i]['comment1'].'</td>
<td>'.$guestArray[$i]['comment2'].'</td>
<td>'.$table_name.'</td>
<td>'.$obj->get_gift_name($user_id,$guestArray[$i]["id"]).'</td>
<td>'.$obj->get_menu_name($user_id,$guestArray[$i]["id"]).'</td>
<td>'.$guestArray[$i]['memo'].'</td>
</tr>';

}
$html .= '</table></body></html>';

//test code
//print $html;exit;

$date_array = explode('-', $user->party_day);

$this_name= "リスト".$date_array[1].$date_array[2]."".$user->man_lastname."_".$user->woman_lastname;

$File = "cache/Yourexcel.html";
$Handle = fopen($File, 'w');
fwrite($Handle, $html);
fclose($Handle);

include_once('admin/inc/ExportToExcel.class.php');

$exp=new ExportToExcel();
include_once("app/ext/Utils/browser.php");

if(Browser::isIE()){
  $this_name = mb_convert_encoding($this_name, "SJIS", "UTF-8");
}

$exp->exportWithPage($File,$this_name.".xls");


?>


