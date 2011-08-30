<?php
ini_set('auto_detect_line_endings', 1);

include_once("inc/dbcon.inc.php");
include_once("inc/class.dbo.php");
include_once("../inc/gaiji.image.wedding.php");

$obj = new DBO();

include("inc/main_dbcon.inc.php");

$respects = $obj->GetAllRow(" spssp_respect");
$guest_types = $obj->GetAllRow(" spssp_guest_type");

include("inc/return_dbcon.inc.php");

$tmp = fopen($_FILES['csv']['tmp_name'], "r");
while ($csv[] = fgetcsv($tmp, "1024")) {}

// 配列 $csv の文字コードをSJIS-winからUTF-8に変換
mb_convert_variables("UTF-8", "SJIS-win", $csv);

$post = $obj->protectXSS($_POST);

$user_id = $_POST["user_id"];

if(!$user_id) $user_id = $_GET["user_id"];
if(!$user_id) return;

$hotel_id=1;
$user_folder_base = sprintf("../%s/user_name/%d/",get_image_db_directory($hotel_id),$user_id);

mkdir($user_folder_base."guest");
$colorArray = array(0x00,0x00,0x00);
//if($_POST["stage"] == 1) $colorArray = array(255,0,0);
mkdir($user_folder_base."/guest");

for($i=0;$i<count($csv);++$i){
  $csv[$i] = $obj->protectXSS($csv[$i]);

  if($csv[$i][0] == "") continue;
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
  $respect_title = $respects[0]["title"];
  for($j=0;$j<count($respects);++$j){
    if($respects[$j]["title"] == $csv[$i][5]){
      $data["respect_id"] = $respects[$j]["id"];
      $respect_title = $respects[$j]["title"];
      break;
    }
  }

  $data["comment1"] = $csv[$i][6];
  $data["comment2"] = "";
  $data["user_id"] = $user_id;


  $guest_id = $obj->InsertData("spssp_guest",$data);
  mkdir($user_folder_base."/guest/".$guest_id);
  mkdir($user_folder_base."/guest/".$guest_id."/thumb1");
  mkdir($user_folder_base."/guest/".$guest_id."/thumb2");
  $user_folder = $user_folder_base."guest/".$guest_id."/";

  make_text_save($data["last_name"],array(),$user_folder."last_name.png",15,150,$colorArray);
  
  make_text_save($data["last_name"].$respect_title,array(),$user_folder."last_name_respect.png",15,150,$colorArray);
  make_text_save($data["first_name"],array(),$user_folder."first_name.png",15,150,$colorArray);
  make_text_save($data["comment1"],array(),$user_folder."comment1.png",15,150,$colorArray);
  make_text_save($data["comment2"],array(),$user_folder."comment2.png",15,150,$colorArray);
  make_text_save($data["comment1"].$data["comment2"],array(),$user_folder."full_comment.png",15,150,$colorArray);
  make_text_save($data["last_name"]." ".$data["first_name"]." ".$respect_title,array(),$user_folder."guest_fullname.png",15,150,$colorArray);
  make_text_save($guest_respect,array(),$user_folder."guest_respect.png",15,150,$colorArray);
  
  make_text_save($data["last_name"].$respect_title,array(),$user_folder."thumb1/last_name_respect.png",11,150,$colorArray);  
  make_text_save($data["comment1"],array(),$user_folder."thumb1/comment1.png",11,150,$colorArray);
  make_text_save($data["comment2"],array(),$user_folder."thumb1/comment2.png",11,150,$colorArray);
  make_text_save($data["comment1"].$comment2,array(),$user_folder."thumb1/full_comment.png",11,150,$colorArray);
  make_text_save($data["last_name"]." ".$data["first_name"]." ".$respect_title,array(),$user_folder."thumb1/guest_fullname.png",11,150,$colorArray);

  make_text_save($data["last_name"].$respect_title,array(),$user_folder."thumb2/last_name_respect.png",9,100,$colorArray);  
  make_text_save($data["comment1"],array(),$user_folder."thumb2/comment1.png",9,100,$colorArray);
  make_text_save($data["comment2"],array(),$user_folder."thumb2/comment2.png",9,100,$colorArray);
  make_text_save($data["comment1"].$data["comment2"],array(),$user_folder."thumb2/full_comment.png",9,100,$colorArray);
  make_text_save($data["last_name"]." ".$data["first_name"]." ".$respect_title,array(),
                 $user_folder."thumb2/guest_fullname.png",9,100,$colorArray);
                 //print_r($data);
}


//ホテル管理者と新郎新婦にメール

function get_image_db_directory($hotel_id){
  $result_image_db_dir = "";
  $query = "select gc_sval_0 as val from spssp_gaizi_cfg where gc_cfg_type = 3 and gc_cscode = ".$hotel_id;
  $result = mysql_query($query );
  $num = mysql_num_rows($result);
  if($num>0){
    while($fetchedRow = mysql_fetch_assoc($result)){
      $result_image_db_dir = (string)$fetchedRow["val"];
    }
  }
  mysql_free_result($result);
  $result_image_db_dir = explode("../",$result_image_db_dir);

  return $result_image_db_dir[1];
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>gaiji_palette</title>
  <link rel="stylesheet" type="text/css" href="css/common.css" media="all" />
  
  <script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
</head>
<body>

<br />

<h2>招待者リストcsv一括アップロード</h2>
    <div class="top_box1">
  アップロードに成功しました。
  
</div>

</body>
</html>
