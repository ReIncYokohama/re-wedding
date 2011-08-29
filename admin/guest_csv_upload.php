<?php
include_once("admin/inc/dbcon.inc.php");


include("admin/inc/main_dbcon.inc.php");
$respects = $obj->GetAllRow(" spssp_respect");
$guest_types = $obj->GetAllRow(" spssp_guest_type");
include("admin/inc/return_dbcon.inc.php");

$tmp = fopen($_FILES['csv']['tmp_name'], "r");
while ($csv[] = fgetcsv($tmp, "1024")) {}
// 配列 $csv の文字コードをSJIS-winからUTF-8に変換
mb_convert_variables("UTF-8", "SJIS-win", $csv);

$obj = new DBO();

$post = $obj->protectXSS($_POST);

$user_id = $_POST["user_id"];

for($i=0;$i<count($csv);++$i){
  $csv[$i] = $obj->protectXSS($csv[$i]);
  $data = array();
  if($csv[$i][0] == "新婦"){
    $data["sex"] = "Female";
  }else{
    $data["sex"] = "Male";
  }
  $data["last_name"] = $csv[$i][1];
  $data["furigana_last"] = $csv[$i][2];
  $data["first_name"] = $csv[$i][3];
  $data["furigana_first"] = $csv[$i][4];
  $data["respect_id"] = $respects[0]["id"];
  for($j=0;$j<count($respects);++$k){
    if($respects[$j]["title"] == $csv[$i][5]){
      $data["respect_id"] = $respects[$j]["id"];
    }
  }
  $data["comment1"] = $csv[$i][6];
  $data["user_id"] = $user_id;
  $guest_id = $obj->InsertData("spssp_guest",$data);
  
}

print_r ($csv);
