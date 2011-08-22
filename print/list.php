<?php
@session_start();
include_once("../admin/inc/include_class_files.php");
if($_SESSION['printid'] =='')
{
   redirect("index.php");exit;
}

$objInfo = new InformationClass();


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>ホテル管理 - 管理画面 - ウエディングプラス</title>
<link href="css/common.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="js/jquery.rollover.js"></script>
<style type="text/css">
.datepicker {cursor:pointer;
}
</style>
</head>

<body>
<div id="wrapper">
  <div id="header"><a href="list.php"><img src="img/common/logo.jpg" alt="席次表システム" width="246" height="43" /></a> </div>
  <div id="topnavi">
<h1>印刷会社様　画面
</h1>
<div id="top_btn"> <a href="logout.php"><img src="img/common/btn_logout.jpg" alt="ログアウト" width="102" height="19" /></a></div>
  </div>
  <div id="container">
    <div id="contents">
      
<h2>お客様一覧</h2>
      <div class="box4">
        <table border="0" align="center" cellpadding="1" cellspacing="1">
          <tr align="center">
            <td><p>ホテル名&#13;</p></td>
            <td><p>披露宴日&#13;</p></td>
            <td>新郎氏名</td>
            <td>新婦氏名</td>
            <td>バージョン</td>
            <td>ダウンロード画面</td>
            <td>アップロード画面</td>
          </tr>
        </table>
      </div>
	  <?
	  $query_string = "SELECT U.man_firstname,U.man_lastname,U.woman_firstname,U.woman_lastname,U.party_day,U.party_day_with_time,P.confirm_date,P.user_id,P.admin_to_pcompany,P.order from spssp_plan AS P, spssp_user AS U  WHERE P.user_id = U.id AND P.print_company=".$_SESSION['printid'];
	 // echo $query_string;
	  $result = mysql_query( $query_string );
	  if(mysql_num_rows($result))
	  {
	    $i=0;
		while($row = mysql_fetch_array($result))
		{
		$class=(($i%2)==0)?"box5":"box6";
		$i++;
		$user_plan_info = $objInfo-> get_user_plan_info($row['user_id']);
		//print_r($user_plan_info);
	  ?>
      <div class="<?=$class?>">
        <table border="0" align="center" cellpadding="1" cellspacing="1">
          <tr align="center">
            <td><p>横浜ロイヤルパークホテル&#13;</p></td>
            <td><p><?=strftime('%Y年%m月%d日（木）',strtotime($row['party_day'])) ;?></p></td>
            <td><?=jp_decode($row['man_firstname'].' '.$row['man_lastname']) ;?></td>
            <td><?=jp_decode($row['woman_firstname'].' '.$row['woman_lastname']) ;?></td>
            <td><?=strftime('%Y.%m.%d',strtotime($row['confirm_date'])) ;?></td>
            <td>
			<?php 
				
				if($user_plan_info['admin_to_pcompany']>0)
				{//NEED TO CHECK THE DAY LIMIT
			?>
				<a href="download.php?user_id=<?=md5($row['user_id'])?>" ><img src="img/common/download_page.gif" alt="削除" width="90" height="17" /></a>
            <?php }else{?>
				<img src="img/common/download_page.gif" alt="削除" width="90" height="17" />
			<?php }?>
			
			</td>
			<td>
			<?php 
			
				if($user_plan_info['admin_to_pcompany']>0)
				{ //NEED TO CHECK THE DAY LIMIT
			?>
			<a href="upload.php?user_id=<?=md5($row['user_id'])?>" ><img src="img/common/upload_page.gif" alt="削除" width="90" height="17" /></a>
			
			<?php }else{?>
				<img src="img/common/upload_page.gif" alt="削除" width="90" height="17" />
			<?php }?>
			</td>
          </tr>
        </table>
      </div>
      <? }
	  }?>
	 <!-- <div class="box6">
        <table border="0" align="center" cellpadding="1" cellspacing="1">
          <tr align="center">
            <td><p>ホテルオークラ東京&#13;</p></td>
            <td><p>2011年6月30日（木）</p></td>
            <td>林浩行<a href="hotel_info.html" onclick="edit_name(13,'ちゃん');"></a></td>
            <td>上戸彩</td>
            <td>2001.06.14 12:51</td>
            <td><a href="download.html" onclick="confirmDelete('respects.php?action=delete&amp;id=13');"><img src="img/common/download_page.gif" alt="削除" width="90" height="17" /></a><a href="hotel_info.html" onclick="confirmDelete('respects.php?action=delete&amp;id=13');"></a></td>
            <td><a href="upload.html" onclick="confirmDelete('respects.php?action=delete&amp;id=13');"><img src="img/common/upload_page.gif" alt="削除" width="90" height="17" /></a><a href="hotel_info.html" onclick="confirmDelete('respects.php?action=delete&amp;id=13');"></a></td>
          </tr>
        </table>
      </div>
      <div class="box5">
        <table border="0" align="center" cellpadding="1" cellspacing="1">
          <tr align="center">
            <td><p>アルモニーテラッセ ウエディングホテル&#13;</p></td>
            <td><p>2011年7月3日（日）</p></td>
            <td>吉田雄哉</td>
            <td>綾瀬はるか</td>
            <td>2001.06.21 04:05</td>
            <td><a href="download.html" onclick="confirmDelete('respects.php?action=delete&amp;id=13');"><img src="img/common/download_page.gif" alt="削除" width="90" height="17" /></a><a href="hotel_info.html" onclick="confirmDelete('respects.php?action=delete&amp;id=13');"></a></td>
            <td><a href="upload.html" onclick="confirmDelete('respects.php?action=delete&amp;id=13');"><img src="img/common/upload_page.gif" alt="削除" width="90" height="17" /></a><a href="hotel_info.html" onclick="confirmDelete('respects.php?action=delete&amp;id=13');"></a></td>
          </tr>
        </table>
      </div>
      <div class="box6">
        <table border="0" align="center" cellpadding="1" cellspacing="1">
          <tr align="center">
            <td><p>ウェスティンホテル</p></td>
            <td><p>2011年7月3日（日）</p></td>
            <td>池田金作<a href="hotel_info.html" onclick="edit_name(13,'ちゃん');"></a></td>
            <td>あき竹城</td>
            <td>2001.06.25 10:40</td>
            <td><a href="download.html" onclick="confirmDelete('respects.php?action=delete&amp;id=13');"><img src="img/common/download_page.gif" alt="削除" width="90" height="17" /></a><a href="hotel_info.html" onclick="confirmDelete('respects.php?action=delete&amp;id=13');"></a></td>
            <td><a href="upload.html" onclick="confirmDelete('respects.php?action=delete&amp;id=13');"><img src="img/common/upload_page.gif" alt="削除" width="90" height="17" /></a><a href="hotel_info.html" onclick="confirmDelete('respects.php?action=delete&amp;id=13');"></a></td>
          </tr>
        </table>
      </div>-->
</div>
  </div>
  <div id="sidebar">
    <ul class="nav">
      <li><a href="list.php"><img src="img/common/nav_list.gif" alt="お客様一覧" width="148" height="30" class=on /></a></li>
      <li><a href="list.php">
    </ul>
  </div>
  <div id="footer">
    <p>Copyright (C) 株式会社サンプリンティングシステム ALL Rights reserved.
</p>
  </div>
</div>
</body>
</html>
