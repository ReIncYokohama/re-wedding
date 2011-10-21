<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>招待者リストcsv一括アップロード</title>
  <link rel="stylesheet" type="text/css" href="css/csv_upload.css" media="all" />
  <!--[if IE]>
      <style type="text/css" media="all">.borderitem {border-style: solid;}</style>
      <![endif]-->
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


  </script>
<style>
input.file.hidden {
  position: relative;
  text-align: left;
  -moz-opacity: 0;
  filter: alpha(opacity: 0);
  opacity: 0;
  z-index: 999;
width:400px;
height:30px;
}
div.fakefile {
  position: absolute;
  top: 0px;
  left: 0px;
  width: 350px;
  padding: 0;
  margin: 0;
  z-index: 1;
}
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
   
   
    <div class="top_box1">
      <table width="600" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>「参照...」ボタンを押して、アップロードするファイルを選択してください。</td>
        </tr>
        <tr>
          <td>　※アップロードは時間がかかります。完了メッセージが出るまで画面を閉じないでください。</td>
        </tr>
                <tr>
          <td> 　</td>
        </tr>
      </table>

 
</div>

    <div class="top_box1">
      <? if($message)
           {
             $mes =($message =='1')?"アップロードに成功しました":"アップロードできませんでした。";
           ?>
        <table width="600" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="center"><font color="#FF0000"><?=$mes?></td>
        </tr>
      </table>
        <? }?>
          <table width="500" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <form method="post" enctype="multipart/form-data" action="csv_upload_action.php" name="uploaddoc">
  <div class="fileinputs">
              <input type="file" size="50" id="myfile" name="csv" class="file hidden" >
              <div class="fakefile">
                <image src="../img/btn_attach.jpg"/>
                <input type="text" id="csv_text" size="40"/>
              </div></div>
              <input type="hidden" name="user_id" value="<?=$_GET["user_id"]?>" />
            </form>
          </td>
        </tr>
      </table>

    </div>

    <div class="top_box2">
      <a href="javascript:void(0);"><img onclick="javascript:document.uploaddoc.submit();" src="img/btn_upload_list.jpg" alt="アップロード" width="82" height="22" /></a>
      <a href="javascript:void(0);"><img onclick="javascript:window.close();" src="../img/btn_cancel.jpg" alt="アップロード" width="82" height="22" /></a>
      　</div>
</body>
</html>
