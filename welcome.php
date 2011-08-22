<?php
session_start();
include_once("admin/inc/dbcon.inc.php");
include_once("admin/inc/class.dbo.php");
$obj = new DBO();

	$userid=(int)$_SESSION['userid'];

	if($_GET['code']!="")
	{
		if($_GET['code']==1){$code="jis90";}if($_GET['code']==2){$code = "jis04";}
		$post['user_code'] = $code;
		$res = $obj->UpdateData('spssp_user',$post,"id=".$userid);
		if($res)
		{
			redirect("dashboard.php");
		}
	}
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>入力環境確認 - ウエディングプラス</title>
<script type="text/javascript">
function confirmDelete(urls)
{
   	var agree = confirm("文字コード確認させていただきました。ありがとうございました。");
	if(agree)
	{
		window.location = urls;
	}
}



</script>

 <style type="text/css">
#welcome_area{
	height:500px;
	margin:20px 150px;
/*	text-align:center; */
	text-align:left;
	background: url(img/welcome_01g.gif) no-repeat right 190px;
	}



#tujibox{
	float:left;
	width:170px;
	height:170px;
	margin-top:100px;
	margin-left: 70px;
	margin-right: 20px;
}


#tuji{
	margin:50px auto;
	padding-top:25px;
	font-size: 140px;
	}

 </style>

</head>

<body style="color: #959595;font: 12px/1.8em Arial,Helvetica,sans-serif;">
	<div style="width:900px;margin:0 auto;border:1px solid;">
		<div id="header" style="height:66px;border-bottom: 8px solid #00C6FF;">
			<img border="0" src="img/logo.jpg">
		</div>
		<div id="welcome_area">
			<p>
				システムに正確なお客様情報を入力していただくため、<br />
				まずはじめにお客様がお使いの「パソコンの文字コード」を確認させていただきます。<br /><br />
				左側の太字で表示されている漢字「つじ」は、右側のどちらの画像「つじ」でしょうか？<br />
				右側の同じ画数で表示されている画像をクリックしてください。
			</p>

			<div>
				<div id="tujibox">
				  <div id="tuji">辻</div>あなたのパソコンの文字を<br />　　　表示しています。
	      </div>
				<div style="float:left;width:250px;" valign="middle">
				  <div>
						<p>左側にはどちらの「つじ」が表示されていますか？</p>
					</div>
					<div>
						<a href="javascript:void(0);" onClick="confirmDelete('welcome.php?code=1');">
						<img height="150" style="border:1px solid;" border="0" width="150" src="img/welcome_2.png"></a>
					</div>
					<div >
						<a href="javascript:void(0);"  onClick="confirmDelete('welcome.php?code=2');">
						<img height="150" style="border:1px solid;margin-top:20px;" border="0" width="150" src="img/welcome_3.png"></a>
					</div>
				</div>

			</div>

		</div>




		<div class="footer" style="text-align: center;">
	    <div style="border-bottom: 8px solid #00C6FF;"></div>
			<div id="footer">			Copyright (C) 株式会社サンプリンティングシステム ALL Rights reserved.
			</div>
		</div>
	</div>
</body>
</html>
