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

// 配列 $csv の文字コードをSJIS-winからUTF-8に変換
if($_FILES["csv"]["tmp_name"]){
  $tmp = fopen($_FILES['csv']['tmp_name'], "r");
  while ($csv[] = fgetcsv($tmp, "1024")) {}
  mb_convert_variables("UTF-8", "SJIS-win", $csv);
}
$post = $obj->protectXSS($_POST);

if($_GET["force"]){
  $user_id = $_SESSION["csv_user_id"];
  $csv = $_SESSION["csv"];

  $force = true;
}
$user_id = $_POST["user_id"]?$_POST["user_id"]:$user_id;

if(!$user_id) $user_id = $_GET["user_id"];
if(!$user_id) return;

//新郎新婦の情報を取得
$user_row = $obj->GetSingleRow("spssp_user"," id='$user_id'");
$user_plan_row = $obj->GetSingleRow("spssp_plan"," user_id=$user_id");

if(!$force){
  //姓名が同じ人がいれば確認alertを出す。
  if(!$user_plan_row["print_company"]){

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
      alert('先に印刷会社を選択してください。');
    //window close
    window.close();
   </script>
</div>

</body>
</html>

      
      <?php

      exit();
    
  }

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
//$user_folder_base = sprintf("../%s",get_image_db_directory($hotel_id));
$user_folder_base = get_image_db_directory($hotel_id);
@mkdir($user_folder_base);
$user_folder_base .= "/user_name";
@mkdir($user_folder_base);
$user_folder_base = $user_folder_base."/".$user_id;
@mkdir($user_folder_base);
$colorArray = array(0x00,0x00,0x00);
//if($_POST["stage"] == 1) $colorArray = array(255,0,0);
$user_folder_base = $user_folder_base."/";
@mkdir($user_folder_base."/guest");

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
  @mkdir($user_folder_base."/guest/".$guest_id);
  @mkdir($user_folder_base."/guest/".$guest_id."/thumb1");
  @mkdir($user_folder_base."/guest/".$guest_id."/thumb2");
  
  make_guest_images($user_id,$guest_id,$data["last_name"],$data["first_name"],$data["comment1"],$data["comment2"],$respect_title,
                  array(),array(),array(),array());
}

$man_last_name = $user_row["man_lastname"];
$woman_last_name = $user_row["woman_lastname"];
$party_date = $obj->japanyDateFormate_for_mail($user_row['party_day']);

//ホテル管理者と新郎新婦にメール
$admin_row = $obj->GetSingleRow("spssp_admin"," permission='333'");
$admin_email = $admin_row["email"];
$admin_name = $admin_row["name"];

if($admin_email){

$body = <<<_EOT_
$admin_name様

いつもお世話になっております。
$party_date $man_last_name･$woman_last_name 様から仮発注依頼がありました。
仮発注処理をお願いいたします。
URL $BASE_URL

▼ このメールは、システムによる自動配信メールとなっております。
心当たりのない場合、その他ご不明な点がございましたら、お手数ですが下記よりご連絡いただけますようお願い申し上げます。
株式会社サンプリンティングシステム weddingplus@sunpri.comment2
▼ このアドレスは配信専用となります。このメールに対しての返信につきましては対応いたしかねます旨ご了承ください。

-----------------------------------------------------------
  ウエディングプラス(株式会社サンプリンティングシステム)
E-mail：weddingplus@sunpri.comment2URL： （ホテルスタッフログイン画面）

_EOT_;

  confirm_guest_register($admin_email,$title,$body);
}

//印刷会社さんにメール
$printing_company_row = $obj->GetSingleRow("spssp_printing_company"," where id = ".$user_plan_row["printing_company"]);
$printing_company_mail = $printing_company_row["email"];
$printing_company_name = $printing_company_row["company_name"];

if($printing_company_mail){

$body = <<<_EOT_
$printing_company_name様

いつもお世話になっております。
$party_date $man_last_name ･$woman_last_name 様から仮発注依頼がありました。
仮発注処理をお願いいたします。
URL $BASE_URL

▼ このメールは、システムによる自動配信メールとなっております。
心当たりのない場合、その他ご不明な点がございましたら、お手数ですが下記よりご連絡いただけますようお願い申し上げます。
株式会社サンプリンティングシステム weddingplus@sunpri.comment2
▼ このアドレスは配信専用となります。このメールに対しての返信につきましては対応いたしかねます旨ご了承ください。

-----------------------------------------------------------
  ウエディングプラス(株式会社サンプリンティングシステム)
E-mail：weddingplus@sunpri.comment2URL： （ホテルスタッフログイン画面）

_EOT_;

  confirm_guest_register($printing_company_mail,$title,$body);
}



//新郎新婦にメール
//$user_row = $obj->GetSingleRow("spssp_user"," id='$user_id'");
//$user_email = $user_row["mail"];
//$user_name = "";

/*
$title = "［ウエディングプラス］仮発注依頼がありました";

if($user_row["subcription_mail"] === '0' && $user_email){

$body = < <<_EOT_
（スタッフ名）様

いつもお世話になっております。
mm/dd ○○･○○様から仮発注依頼がありました。
仮発注処理をお願いいたします。
URL（ホテルスタッフログイン画面）

▼ このメールは、システムによる自動配信メールとなっております。
心当たりのない場合、その他ご不明な点がございましたら、お手数ですが下記よりご連絡いただけますようお願い申し上げます。
株式会社サンプリンティングシステム weddingplus@sunpri.comment2
▼ このアドレスは配信専用となります。このメールに対しての返信につきましては対応いたしかねます旨ご了承ください。

-----------------------------------------------------------
  ウエディングプラス(株式会社サンプリンティングシステム)
E-mail：weddingplus@sunpri.comment2URL： （ホテルスタッフログイン画面）

_EOT_;

  confirm_guest_register($user_email,$title,$body);
}
*/

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
