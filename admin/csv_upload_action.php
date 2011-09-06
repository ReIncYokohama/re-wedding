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

if($_GET["force"]){
  $user_id = $_SESSION["csv_user_id"];
  $csv = $_SESSION["csv"];
  $force = true;
}
$user_id = $_POST["user_id"]?$_POST["user_id"]:$user_id;


if(!$user_id) $user_id = $_GET["user_id"];
if(!$user_id) return;

if(!$force){
  //姓名が同じ人がいれば確認alertを出す。
  for($i=0;$i<count($csv);++$i){
    $csv[$i] = $obj->protectXSS($csv[$i]);
    $data = array();
    $last_name = $csv[$i][1];
    $first_name = $csv[$i][3];
    
    $user_row = $obj->GetSingleRow("spssp_guest"," last_name = '$last_name' and first_name = '$first_name' and user_id = '$user_id'");
    if($user_row){
      $_SESSION["csv"] = $csv;
      $_SESSION["csv_user_id"] = $user_id;
      ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>招待者リストcsv一括アップロード</title>
  <link rel="stylesheet" type="text/css" href="css/csv_upload.css" media="all" />
  
  <script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
</head>
<body>

<br />

<h2>招待者リストcsv一括アップロード</h2>
    <div class="top_box1">
    <script>

   if(window.confirm('姓名が同じ人が既に登録されています。追加してよろしいですか？（同姓同名の場合はOKを押下してください）')){
     location.href = location.href+"?force=true";
   }else{
     location.href = "csv_upload.php?user_id=<?=$user_id?>";
   }

   </script>
</div>

</body>
</html>

      
      <?php

      exit();
    }
  }
}

$hotel_id=1;
$user_folder_base = sprintf("../%s",get_image_db_directory($hotel_id));
mkdir($user_folder_base);
$user_folder_base .= "/user_name";
mkdir($user_folder_base);
$user_folder_base = $user_folder_base."/".$user_id;
mkdir($user_folder_base);
$colorArray = array(0x00,0x00,0x00);
//if($_POST["stage"] == 1) $colorArray = array(255,0,0);
$user_folder_base = $user_folder_base."/";
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
  $data["comment2"] = $csv[$i][7];
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
  
  $savefile = sprintf("%s/user_name/%d/%s/%d/%s",get_image_db_directory($hotel_id),$user_id,"guest",$guest_id,"namecard.png");

  make_name_plate_save($data["last_name"],$data["first_name"],$data["comment1"],$data["comment2"],
                       array(),array(),
                       array(),array(),dirname(__FILE__)."/../".$savefile,$colorArray,$respect_title);
}

//ホテル管理者と新郎新婦にメール
$admin_row = $obj->GetSingleRow("spssp_admin"," permission='333'");
$admin_email = $admin_row["email"];
$user_row = $obj->GetSingleRow("spssp_user"," id='$user_id'");
$user_email = $user_row["mail"];
if($user_row["subcription_mail"] === '0' && $user_email){
  confirm_guest_register($user_email,"ゲストが登録されました。","ゲストが登録されました。");
}
if($admin_email){
  
  confirm_guest_register($admin_email,"ゲストが登録されました。","ゲストが登録されました。");
}

function confirm_guest_register($to,$subject,$mailbody){
	$from='r.kubonaga@resonanceinc.com';
  $header='From:'.$from." \r\n";
  $header.='Content-Type:text/plain; charset=utf-8'."\r\n";
  //$header1.= "Cc: k.okubo@re-inc.jp\r\n";

  $subject = base64_encode(mb_convert_encoding($subject,"JIS","UTF8"));
  $usersubject = '=?ISO-2022-JP?B?'.$subject.'?=';

  $user_body=$mailbody;

		///////MAIL TO USER /////////////
  if(@mail($to, $usersubject, $user_body, $header))
		{
			return 1;
		}
		else
		{
			return 2;
		}
}

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
  <title>招待者リストcsv一括アップロード</title>
  <link rel="stylesheet" type="text/css" href="css/csv_upload.css" media="all" />
  
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