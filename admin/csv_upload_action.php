<?php

//ini_set('auto_detect_line_endings', 1);
include_once("inc/dbcon.inc.php");
include_once("inc/class.dbo.php");
include_once("../inc/gaiji.image.wedding.php");
include_once("inc/class_data.dbo.php");
include_once("../app/ext/Utils/email.php");
include_once("../fuel/load_classes.php");

$obj = new DataClass();

include("inc/main_dbcon.inc.php");

$respects = $obj->GetAllRow(" spssp_respect");
$guest_types = $obj->GetAllRow(" spssp_guest_type");

include("inc/return_dbcon.inc.php");
$user_id = $_POST["user_id"]?$_POST["user_id"]:$user_id;

if(!$user_id) $user_id = $_GET["user_id"];
if(!$user_id) return;
$user = Model_User::find_by_pk($user_id);
$plan = Model_Plan::find_one_by_user_id($user_id);
if($plan->is_kari_hatyu_irai() or $plan->is_kari_hatyu()){
  print '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>招待者リストcsv一括アップロード</title>
  <link rel="stylesheet" type="text/css" href="css/csv_upload.css" media="all" />
  
  <script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
</head>
<body>

仮発注中です<br><img onclick="javascript:window.close();" src="../img/btn_close.jpg" alt="閉じる" width="82" height="22" />
</body>
</html>

';
  exit;
}
// 配列 $csv の文字コードをSJIS-winからUTF-8に変換
if($_FILES["csv"]["tmp_name"]){
  $tmp = fopen($_FILES['csv']['tmp_name'], "r");
  
  while ($text = fgets($tmp, "1024")) {
    $datas = explode(",",$text);
    $line = array();
    foreach($datas as $data){
      mb_convert_variables("UTF-8","SJIS-win",$data);
      $line[] = $data;
    }
    $csv[] = $line;
  }
}

if(count($csv)==0 && !$_GET["force"]){
  print '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>招待者リストcsv一括アップロード</title>
  <link rel="stylesheet" type="text/css" href="css/csv_upload.css" media="all" />
  
  <script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
</head>
<body>

ファイルが添付されていません。<br><img onclick="javascript:window.close();" src="../img/btn_close.jpg" alt="閉じる" width="82" height="22" />
</body>
</html>
';
  exit;

}

$post = $obj->protectXSS($_POST);

if($_GET["force"]){
  $user_id = $_SESSION["csv_user_id"];
  $csv = $_SESSION["csv"];
  $force = true;
}

//新郎新婦の情報を取得
$user_row = $obj->GetSingleRow("spssp_user"," id='$user_id'");
$user_plan_row = $obj->GetSingleRow("spssp_plan"," user_id=$user_id");

$plan_id = $user_plan_row["id"];

if(!$force){
  //正規表現
  $error = false;
  $messageArray = array();
  //印刷会社が選択されていない。
  if(!$user_plan_row["print_company"]){
    array_push($messageArray,"印刷会社が選択されていません。");
    $error = true;
  }
  //spssp_userのデータチェック
  for($i=0;$i<count($csv);++$i){
    if(!$csv[$i] || implode("",$csv[$i])=="") continue;
    $thisMessageArray = $obj->check_user_data(
      array("last_name"=>$csv[$i][1],"first_name"=>$csv[$i][3],"furigana_last"=>$csv[$i][2],
            "furigana_first"=>$csv[$i][4],"respect"=>$csv[$i][5],"sex"=>$csv[$i][0]),$i);
    $messageArray = array_merge($messageArray,$thisMessageArray);
  }
  if(count($messageArray)>0) $error = true;
  for($i=0;$i<count($messageArray);++$i){
    $messageArray[$i] = "<p>".$messageArray[$i]."</p>";
  }
  $messageText = implode("",$messageArray);

  //テスト用のチェック
  //print_r($messageArray);
  //exit();

  //ユーザが印刷会社を選択していない場合アラート。
  if($error){

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
    <div id="message"><?=$messageText?>
    </div>
      
</div>
<div align="center" class="top_box1">
  <div align="center"><a href="javascript:void(0);"><img onclick="javascript:window.close();" src="../img/btn_close.jpg" alt="閉じる" width="82" height="22" /></a>
  </div>
</div>
</body>
</html>
      
      <?php

      exit();
    
  }
  
  $same_user = false;
  for($i=0;$i<count($csv);++$i){
    $csv[$i] = $obj->protectXSS($csv[$i]);
    $data = array();
    $last_name = $csv[$i][1];
    $first_name = $csv[$i][3];
    
    $user_row2 = $obj->GetSingleRow("spssp_guest"," last_name = '$last_name' and first_name = '$first_name' and user_id = '$user_id'");
    if($user_row2){
      $_SESSION["csv"] = $csv;
      $_SESSION["csv_user_id"] = $user_id;
      $same_user = true;
      break;
    }
  }
  
  if($same_user){
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
     location.href = location.href+"?force=true&user_id=<?php echo $user_id;?>";
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

for($i=0;$i<count($csv);++$i){
  $csv[$i] = $obj->protectXSS($csv[$i]);
  
  //データが空白の場合、カラムの代入をしない。
  if($csv[$i][0] == "" && (!$csv[$i][1] || $csv[$i][1] == "")) continue;

  $data = array();
  if($csv[$i][0] == "新婦"){
    $data["sex"] = "Female";
  }else{
    $data["sex"] = "Male";
  } 
  if($csv[$i][0] == "") $data["sex"] = null;
  $data["last_name"] = check_sjis($csv[$i][1]);
  $data["furigana_last"] = $csv[$i][2];
  $data["first_name"] = check_sjis($csv[$i][3]);
  $data["furigana_first"] = $csv[$i][4];
  $haveRespect = false;
  for($j=0;$j<count($respects);++$j){
    if($respects[$j]["title"] == $csv[$i][5]){
      $data["respect_id"] = $respects[$j]["id"];
      $respect_title = $respects[$j]["title"];
      $haveRespect = true;
      break;
    }
  }
  if(!$haveRespect){
    $csv[$i][5] = "なし";
    for($j=0;$j<count($respects);++$j){
      if($respects[$j]["title"] == $csv[$i][5]){
        $data["respect_id"] = $respects[$j]["id"];
        $respect_title = $respects[$j]["title"];
        break;
      }
    }
  }
  if($respect_title=="なし") $respect_title = "";
  
  $data["comment1"] = check_sjis($csv[$i][6]);
  $data["comment2"] = check_sjis($csv[$i][7]);
  $data["user_id"] = $user_id;
  
  $guest_id = $obj->InsertData("spssp_guest",$data);

  make_guest_images($user_id,$guest_id,$data["last_name"],$data["first_name"],$data["comment1"],$data["comment2"],$respect_title,
                  array(),array(),array(),array());
}

$man_last_name = $user_row["man_lastname"];
$woman_last_name = $user_row["woman_lastname"];
$party_date = $obj->japanyDateFormate_for_mail($user_row['party_day']);

//ホテル管理者と新郎新婦にメール
$staff_id = $user_row["stuff_id"];
$admin_row = $obj->GetSingleRow("spssp_admin"," id='".$staff_id."'");

$admin_email = $admin_row["email"];
$admin_name = $admin_row["name"];

$BASE_URL = BASE_URL;
$title = "［ウエディングプラス］招待客リストデータがアップロードされました";
if($admin_row["subcription_mail"]==1) $admin_email = false;
if($admin_email){
//if(Core_Validation::checkEmail($admin_email)){
$body = <<<_EOT_
${admin_name}様

いつもお世話になっております。
{$party_date} {$man_last_name}({$user_row['man_furi_lastname']})・{$woman_last_name}({$user_row['woman_furi_lastname']}) 様向け招待者リストデータがアップロードされました。
ご確認をお願いいたします。
  URL ${BASE_URL}admin/

▼ このメールは、システムによる自動配信メールとなっております。
心当たりのない場合、その他ご不明な点がございましたら、お手数ですが下記よりご連絡いただけますようお願い申し上げます。
株式会社サンプリンティングシステム info@wedding-plus.net

▼ このアドレスは配信専用となります。このメールに対しての返信につきましては対応いたしかねます旨ご了承ください。

-----------------------------------------------------------
ウエディングプラス
株式会社サンプリンティングシステム
E-mail：info@wedding-plus.net
URL: $BASE_URL

_EOT_;
  $email = new Email($admin_email,$title,$body);
  $email->from = "wedding-plus@wedding-plus.net";
  $email->fromName = "Wedding Plus";
  $email->noneFromName = true;
  $email->send();
}

//印刷会社さんにメール
/*
$printing_company_row = $obj->GetSingleRow("spssp_printing_company"," where id = ".$user_plan_row["printing_company"]);
$printing_company_mail = $printing_company_row["email"];
$printing_company_name = $printing_company_row["company_name"];
$printing_company_mail = "kubonagarei@gmail.com";
*/

$mail = $user_row["mail"];
if($user_row["subcription_mail"]==1) $mail = false;

$title = "［ウエディングプラス］招待客リストデータが追加されました。";

if($mail){

$body = <<<_EOT_
${man_last_name}({$user_row["man_furi_lastname"]})・${woman_last_name}({$user_row["woman_furi_lastname"]}) 様

このたびはウェディングプラスをご利用いただき、ありがとうございます。
お客様からご提供いただきました招待客リストデータが追加されました。
ご確認をお願いします。
URL $BASE_URL

▼ このメールは、システムによる自動配信メールとなっております。
心当たりのない場合、その他ご不明な点がございましたら、お手数ですが下記よりご連絡いただけますようお願い申し上げます。
株式会社サンプリンティングシステム info@wedding-plus.net

▼ このアドレスは配信専用となります。このメールに対しての返信につきましては対応いたしかねます旨ご了承ください。

-----------------------------------------------------------
  ウエディングプラス(株式会社サンプリンティングシステム)
E-mail：info@wedding-plus.net
URL： $BASE_URL

_EOT_;
$email = new Email($mail,$title,$body);
$email->from = "wedding-plus@wedding-plus.net";
$email->fromName = "Wedding Plus";
$email->noneFromName = true;
$email->send();

}

/*
csv uploadのログを残す
*/
//csv uploadのお知らせの記録
Model_Csvuploadlog::log($user_id);
//csvアップロード時のログを記録
$obj->set_log_csv_guest($user_id,$plan_id);

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
      <div align="center"><a href="javascript:void(0);"><img onclick="javascript:window.close();" src="../img/btn_close.jpg" alt="閉じる" width="82" height="22" /></a></div>

</body>
</html>
