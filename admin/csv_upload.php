<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>gaiji_palette</title>
  <link rel="stylesheet" type="text/css" href="css/common.css" media="all" />
  <!--[if IE]>
      <style type="text/css" media="all">.borderitem {border-style: solid;}</style>
      <![endif]-->
  <script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
</head>
<body>

<br />

<h2>招待者リストcsv一括アップロード</h2>
   
   
    <div class="top_box1">
      <table width="420" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>「参照...」ボタンを押して、アップロードするファイルを選択してください。</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table>

 
</div>

    <div class="top_box1">
      <? if($message)
           {
             $mes =($message =='1')?"アップロードに成功しました":"アップロードできませんでした。";
           ?>
        <table width="420" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="center"><font color="#FF0000"><?=$mes?></td>
        </tr>
      </table>
        <? }?>
          <table width="420" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <form method="post" enctype="multipart/form-data" action="csv_upload_action.php" name="uploaddoc">            
              <input type=file name="csv" size="50">
              <input type="hidden" name="user_id" value="<?=$_GET["user_id"]?>" />
            </form>
          </td>
        </tr>
      </table>
   
    </div>
    <br />
    <div class="top_box2">
      <a href="javascript:void(0);"><img onclick="javascript:document.uploaddoc.submit();" src="img/btn_upload_list.jpg" alt="アップロード" width="82" height="22" /></a>
      　</div>




</body>
</html>
