<?php
@session_start();
include_once("../admin/inc/include_class_files.php");
include_once("../fuel/load_classes.php");

$obj = new DBO();
$objInfo = new InformationClass();
$objMail = new MailClass();
$objMsg = new MessageClass();
if($_SESSION['printid'] =='')
{
  redirect("index.php");exit;
}
$user_id = $objInfo->get_user_id_md5( $_GET['user_id']);
//$user_id = $_GET["user_id"];
if($user_id>0)
{
	//OK
}
else
{
	redirect("index.php?msg=3");exit;
}
$printCompany_id = $_SESSION['printid'];

$clicktime = Model_Clicktime::find_one_by_user_id($user_id);
if($clicktime->past_print_upload()){
  redirect("index.php?action=pastPrintUpload");  
  exit;
}

$user_info = $objInfo->get_user_info($user_id);
unset($post);
$post['ul_print_com_times'] = 1;
$res = $obj->UpdateData('spssp_plan',$post,"user_id=".$user_id);

if(isset($_POST['sub']))
{
	$basepath='../upload/';
	@mkdir($basepath);

	$basepath='../upload/'.$user_id.'/';
	@mkdir($basepath,0777);

	$filename= basename($_FILES["upfile"]["name"]);
  
	if(!empty($filename))
	{
		$ext = strtoupper(end(explode(".", $filename)));
		$message = uploadFile($basepath,$filename,'upfile',$ext);
		if($message)
		{
			unset($post);
      $plan = Model_Plan::find_one_by_user_id($user_id);
      $plan->p_company_file_up=$message;
      $plan->upload_sekizihyo();
			if($res)
			{
				$objMail->printCompany_upload_admin_notification_mail($user_id,$printCompany_id);
				$objMail->printCompany_upload_user_notification_mail($user_id,$printCompany_id);
			}
			$message =1;
		}
	}
}

$user = Model_User::find_by_pk($user_id);
$row = $user->to_array();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>アップロード - 印刷会社様画面 - ウエディングプラス</title>
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
  <div id="header"><img src="img/common/logo.jpg" alt="席次表システム" width="246" height="43" /></div>
  <div id="topnavi">
<h1>印刷会社向け画面</h1>
<div id="top_btn"> <a href="logout.php"><img src="img/common/btn_logout.jpg" alt="ログアウト" width="102" height="19" /></a></div>
  </div>
  <div id="container">
    <div id="contents">
<h2>アップロード</h2>


    <div>
      <table width="480" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>「ファイルを選択」ボタンを押して、アップロードするファイルを選択してください。<br />その後、「アップロード」ボタンを押してください。</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          </tr>
        </table>

    </div>
     <form action="upload.php?user_id=<?=$_GET['user_id']?>" name="uploaddoc" method="post" enctype="multipart/form-data">
    <div class="top_searchbox1">
      <? if($message)
	  {
		if($message == 1) 	echo "<script> alert('アップロードに成功しました'); </script>";
		else 				echo "<script> alert('アップロードできませんでした'); </script>";

    }?>
	  <table width="420" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
             <input type="file" name="upfile" size="50">
			 <input type="hidden" name="sub" value="1" />
		 </td>
          </tr>
        </table>

    </div>
    <div class="top_searchbox2">
	<?php
	 $user_plan_info = $objInfo->get_user_plan_info($user_id);
	 if($user_plan_info['ul_print_com_times']==1)
	 {	//NEED TO CHECK THE DAY LIMIT
	 ?>
	<a href="javascript:void(0);"><img onclick="javascript:document.uploaddoc.submit();" src="img/common/btn_upload.jpg" alt="検索" width="152" height="22" /></a>
	 <?php }else{?>
	 <img src="img/common/btn_upload.jpg" alt="検索" width="152" height="22" />
	  <?php }?>

	</div>
    </form>
	<p>&nbsp;<br /><br /><br /></p>

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
        <p><?=jp_decode($row['man_lastname'].' '.$row['man_firstname'].' 様<br /> '.$row['woman_lastname'].' '.$row['woman_firstname']) ;?> 様 </p>
        <a href="download.php"></a>■披露宴日：
        <p><?=strftime('%Y年%m月%d日',strtotime($row['party_day'])) ;?></p>
		</li>
		<li> </li>
    </ul>
  </div>

  <div id="footer">
    <p>Copyright (C) 株式会社サンプリンティングシステム ALL Rights reserved.
</p>
  </div>
</div>
</body>
</html>
