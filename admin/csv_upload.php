<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>招待者リストcsv一括アップロード</title>
  <link rel="stylesheet" type="text/css" href="css/csv_upload.css" media="all" />
      <style type="text/css" media="all">.borderitem {border-style: solid;}</style>
  <script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
  <script>
  $(document).ready(function(){
    $("#myfile").change(function(){
        var filename = $(this).val();
        var filenameArray = filename.split("\\");
        filename = filenameArray[filenameArray.length-1];
        $("#csv_text").val(filename);
    });
  });
function fileOpen(){
  $("#myfile").click();
}
var lock = false;
function submit(){
  if(!lock){
    document.uploaddoc.submit();
    $("#loading").show();
    lock = true;
  }
}

  </script>
<style>
div.fileinputs {
position: relative;
height: 30px;
width: 300px;
}
input.file {
width: 300px;
margin: 0;
}
</style>


</head>
<body>

<br />

<h2>招待者リストcsv一括アップロード</h2>
   
   
<?php
include("../fuel/load_classes.php");
$user = Model_User::find_by_pk($_GET["user_id"]);
$plan = Model_Plan::find_one_by_user_id($user->id);
if($plan->is_kari_hatyu_irai() or $plan->is_kari_hatyu()){
?>
    <div class="top_box2">
お客様は現在仮発注中です<br><br>
現在、お客様は仮発注中なので、招待者リストのアップロードはできません。
<br><br>
      <a href="javascript:void(0);"><img onclick="javascript:window.close();" src="../img/btn_cancel.jpg" alt="閉じる" width="82" height="22" /></a>
    </div>
<?
}else{
?>
    <div class="top_box1">
      <table width="600" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>「参照...」ボタンを押して、アップロードするファイルを選択してください。</td>
          <td rowspan="4">
            <span id="loading" style="display:none;"><img src="img/common/nowloading.gif"></span>
          </td>
        </tr>
        <tr>
          <td>　※アップロードは時間がかかります。完了メッセージが出るまで画面を閉じないでください。</td>
        </tr>
        <tr><td><br><br></td></tr>
        <tr>
          <td>
            <form method="post" enctype="multipart/form-data" action="csv_upload_action.php" name="uploaddoc">
              <input type="file" size="50" id="myfile" name="csv" class="file">
              <input type="hidden" name="user_id" value="<?=$_GET["user_id"]?>" />
            </form>
          </td>
        </tr>
      </table>
    </div>

    <div class="top_box2">
      <a href="javascript:void(0);"><img onclick="javascript:submit();" src="img/btn_upload_list.jpg" alt="アップロード" width="82" height="22" /></a>
      <a href="javascript:void(0);"><img onclick="javascript:window.close();" src="../img/btn_cancel.jpg" alt="閉じる" width="82" height="22" /></a>
      　</div>

<?php
    }
?>
</body>
</html>
