<?php
@session_start();
include_once("../admin/inc/include_class_files.php");
include_once("../fuel/load_classes.php");
$obj = new DBO();
$objInfo = new InformationClass();

$user_id = $objInfo->get_user_id_md5( $_GET['user_id']);

if($user_id  && $printCompany_id )
{
	$_SESSION['printid'] = $printCompany_id;
}
else if($_SESSION['printid'] =='')
{
   redirect("index.php");exit;
}

$user_info = $objInfo->get_user_info($user_id);
$mailDate=$user_info['state'];
$limitDate=date("Y/m/d",strtotime("-5 day"));
$downloadOK=false;
if ($mailDate > $limitDate) $downloadOK=true;
$user_plan_info = $objInfo->get_user_plan_info($user_id);

if(!$downloadOK)
	 {
	 	redirect("index.php?action=mailDateLimit");
  exit;
	 }

     $row = $objInfo->get_user_info($user_id);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>ダウンロード - 印刷会社様画面 - ウエディングプラス</title>
<link href="css/common.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="js/jquery.rollover.js"></script>
<script>
function click_check(url) {
		window.location.href=url;
}
</script>
<style type="text/css">
.datepicker {cursor:pointer;
}

</style>
</head>

<body>
<div id="wrapper">
  <div id="header"><img src="img/common/logo.jpg" alt="席次表システム" width="246" height="43" /></div>
  <div id="topnavi">
<h1>印刷会社向け画面</h1>
<div id="top_btn"> <a href="logout.php"><img src="img/common/btn_logout.jpg" alt="ログアウト" width="102" height="19" /></a></div>
  </div>
  <div id="container">
    <div id="contents">
<h2>ダウンロード</h2>
    <div class="top_searchbox1">
      <table width="420" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>PDF、または、CSVファイルをダウンロードできます。</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          </tr>
        </table>
      <form id="form1" name="form1" method="post" action="">
        ・席次表　PDFファイル
      </form>

    </div>
     <div class="top_selectbox2">
	  <a href="call_plan_pdf_small.php?user_id=<?=$_GET['user_id']?>&file=pdf" target="_blank"><img src="img/common/btn_download_pdf.jpg" alt="検索" width="152" height="22" /></a>
	  　</div>
    <div class="top_searchbox1">
      <table width="420" border="0" cellspacing="0" cellpadding="0">
        <tr>

        </tr>
        <tr>
          <td>&nbsp;</td>
          </tr>
        </table>
      <form id="form1" name="form1" method="post" action="">
        ・席次表　CSVファイル
      </form>
    </div>
		<div class="top_selectbox2">
		<a href="javascript:void(0);" onclick="click_check('csvdownload.php?user_id=<?=$_GET['user_id']?>&file=csv');" ><img src="img/common/btn_download_csv.jpg" alt="検索" width="152" height="22" /></a>
		　</div>
    <p>&nbsp;</p>
    </div>
  </div>
  <div id="sidebar">
    <ul class="nav">
      <li>■ホテル名：
<?php
include("../admin/inc/main_dbcon.inc.php");
$hcode=$HOTELID;
$hotel_name = $obj->GetSingleData(" super_spssp_hotel ", " hotel_name ", " hotel_code=".$hcode);
include("../admin/inc/return_dbcon.inc.php");
?>
        <p><?=$hotel_name?></p>
        <a href="download.php"></a>■新郎新婦氏名：
        <p><?=jp_decode($row['man_lastname'].' '.$row['man_firstname'].' 様<br />'.$row['woman_lastname'].' '.$row['woman_firstname']) ;?> 様 </p>
        <a href="download.php"></a>■披露宴日：
        <p><?=strftime('%Y年%m月%d日',strtotime($row['party_day'])) ;?></p>
      </li>
    </ul>
  </div>

  <div id="footer">
    <p>Copyright (C) 株式会社サンプリンティングシステム All rights reserved.
</p>
  </div>
</div>
</body>
</html>
