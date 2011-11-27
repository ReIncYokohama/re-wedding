<?php
@session_start();
include_once("admin/inc/class_data.dbo.php");
include_once("inc/checklogin.inc.php");

$obj = new DataClass();
$get = $obj->protectXSS($_GET);
$user_id = (int)$_SESSION['userid'];

$user_info =  $obj->GetSingleRow("spssp_user", " id=".$user_id);
$plan_info =  $obj->GetSingleRow("spssp_plan", " user_id=".$user_id);
$default_layout_title = $obj->GetSingleData("spssp_options" ,"option_value" ," option_name='default_layout_title'");

$plan_id = $obj->GetSingleData("spssp_plan", "id","user_id=".$user_id);
$plan_row = $obj->GetSingleRow("spssp_plan", " id =".$plan_id);

$room_rows = $plan_row['row_number'];
$room_tables = $plan_row['column_number'];
$room_seats = $plan_row['seat_number'];
$num_tables = $room_rows * $room_tables;

$guestArray = $obj->get_guestdata($user_id);
$userArray = $obj->get_userdata($user_id);

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
<td>肩書</td>
<td>肩書１</td>
<td>卓名</td>
<td>引出物グループ</td>
<td>料理</td>
<td>特記</td>
</tr>';

for($i=0;$i<count($guestArray);++$i){
  $html .= '<tr>
<td>'.$guestArray[$i]['sex_text'].'</td>
<td>'.$guestArray[$i]['last_name'].'</td>
<td>'.$guestArray[$i]['first_name'].'</td>
<td>'.$guestArray[$i]['furigana_last'].'</td>
<td>'.$guestArray[$i]['furigana_first'].'</td>
<td>'.$guestArray[$i]['respect_text'].'</td>
<td>'.$guestArray[$i]['guest_type_text'].'</td>
<td>'.$guestArray[$i]['comment1'].'</td>
<td>'.$guestArray[$i]['comment1'].'</td>
<td>'.$guestArray[$i]['table_name'].'</td>
<td>'.$guestArray[$i]['gift_group_text'].'</td>
<td>'.$guestArray[$i]['menu_text'].'</td>
<td>'.$guestArray[$i]['memo'].'</td>
</tr>';

}

   $html .= '</table></body></html>';

// YYYYMMDD_HHMMSS の形式で現在日付時間を取得する
$today = date("md");
$this_name= "リスト".$today."".$userArray[0]["last_name"]."_".$userArray[1]["last_name"];	// 最終形式に整形する

// UCHIDA EDIT 11/07/28 ↑

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


