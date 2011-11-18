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
   	var agree = confirm("環境の確認させていただきました。ありがとうございました。");
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
	text-align:left;
	}



#tujibox{
	float:left;
	width:170px;
	height:280px;
	margin-top:0px;
	margin-left: 0px;
	margin-right: 0px;
	text-align:center;
}


#tuji{
	margin:10px auto;
	height:280px;
	padding-top:50px;
	font-size: 140px;
	}

 </style>

</head>

<body style="color: #000000;font: 13px/1.6em 'ＭＳ Ｐゴシック', Osaka, 'ヒラギノ角ゴ Pro W3',Arial,Helvetica,sans-serif;">
	<div style="width:1000px;align:center;margin:0 auto;border:0px solid;">
		<div id="header" style="height:66px;border-bottom: 8px solid #00C6FF;">
			<img border="0" src="img/logo.jpg">
		</div>
		<div id="welcome_area">



<table style="display: inline-table;" border="0" cellpadding="0" cellspacing="0" width="754">
  <tr>
<!-- Shim row, height 1. -->
   <td><img src="images/spacer.gif" width="17" height="1" border="0" alt="" /></td>
   <td><img src="images/spacer.gif" width="138" height="1" border="0" alt="" /></td>
   <td><img src="images/spacer.gif" width="15" height="1" border="0" alt="" /></td>
   <td><img src="images/spacer.gif" width="244" height="1" border="0" alt="" /></td>
   <td><img src="images/spacer.gif" width="152" height="1" border="0" alt="" /></td>
   <td><img src="images/spacer.gif" width="12" height="1" border="0" alt="" /></td>
   <td><img src="images/spacer.gif" width="21" height="1" border="0" alt="" /></td>
   <td><img src="images/spacer.gif" width="153" height="1" border="0" alt="" /></td>
   <td><img src="images/spacer.gif" width="1" height="1" border="0" alt="" /></td>
   <td><img src="images/spacer.gif" width="1" height="1" border="0" alt="" /></td>
   <td><img src="images/spacer.gif" width="1" height="1" border="0" alt="" /></td>
  </tr>

  <tr><!-- row 1 -->
   <td colspan="4" rowspan="3" align="left" valign="top"><p style="margin:0px">初回ログインの際<br />
お客様のパソコン環境の確認をさせていただきます。<br />
（パソコンにより文字の字形が変わるものがあります）<br />
<br />
左の太字は、お客様のパソコンの文字を表示しています。<br />
しんにょうの点の数が同じものを右から選んで、文字をクリックしてください。</p></td>
   <td colspan="3"><img name="n_r1_c5" src="img/welcome_01g.jpg" width="185" height="107" border="0" id="n_r1_c5" alt="" /></td>
   <td rowspan="3" colspan="3" valign="top"><p style="margin:0px"></p></td>
   <td><img src="images/spacer.gif" width="1" height="107" border="0" alt="" /></td>
  </tr>
  <tr><!-- row 2 -->
   <td colspan="3" valign="top"><p style="margin:0px"></p></td>
   <td><img src="images/spacer.gif" width="1" height="21" border="0" alt="" /></td>
  </tr>
  <tr><!-- row 3 -->
   <td rowspan="4"><a href="javascript:void(0);" onClick="confirmDelete('welcome.php?code=1');"><img src="img/welcome_2.png" width="150" height="150" border="0" align="absmiddle" style="border:0px solid;"
onmouseover="this.src='img/welcome_2_on.png';" onmouseout="this.src='img/welcome_2.png'" /></a></td>
   <td rowspan="4" valign="top"><p style="margin:0px"></p></td>
   <td valign="top"><p style="margin:0px"></p></td>
   <td><img src="images/spacer.gif" width="1" height="47" border="0" alt="" /></td>
  </tr>
  <tr><!-- row 4 -->
   <td valign="top"><p style="margin:0px"></p></td>
   <td align="center" valign="top"><p style="margin:0px">お客様のパソコンで<br />
表示される文字の形</p></td>
   <td colspan="2" valign="top"><p style="margin:0px"></p></td>
   <td colspan="4" rowspan="2" align="left" valign="middle"><p style="margin:0px">しんにょうが１点の場合は<br />
こちらの文字をクリック</p></td>
   <td><img src="images/spacer.gif" width="1" height="45" border="0" alt="" /></td>
  </tr>
  <tr><!-- row 5 -->
   <td rowspan="6" colspan="3" valign="top"><p style="margin:0px">
   
   <div id="tujibox">
     <div id="tuji">辻</div>
		  </div>
   
   </p></td>
   <td rowspan="7" valign="top"><img name="n_r5_c4" src="img/welcome_02g.jpg" width="244" height="174" border="0" id="n_r5_c4" alt="" /></td>
   <td><img src="images/spacer.gif" width="1" height="8" border="0" alt="" /></td>
  </tr>
  <tr><!-- row 6 -->
   <td colspan="4" valign="top"><p style="margin:0px"></p></td>
   <td><img src="images/spacer.gif" width="1" height="52" border="0" alt="" /></td>
  </tr>
  <tr><!-- row 7 -->
   <td valign="top"><p style="margin:0px"></p></td>
   <td rowspan="2" valign="top"><p style="margin:0px"></p></td>
   <td rowspan="2" colspan="3" valign="top"><p style="margin:0px"></p></td>
   <td rowspan="7" valign="top"><p style="margin:0px"></p></td>
   <td><img src="images/spacer.gif" width="1" height="47" border="0" alt="" /></td>
  </tr>
  <tr><!-- row 8 -->
   <td rowspan="6" valign="top"><a href="javascript:void(0);"  onClick="confirmDelete('welcome.php?code=2');">
						<img src="img/welcome_3.png" width="150" height="150" border="0" align="absmiddle" style="border:0px solid;margin-top:0px;"
onmouseover="this.src='img/welcome_3_on.png';" onmouseout="this.src='img/welcome_3.png'" /> </a></td>
   <td><img src="images/spacer.gif" width="1" height="1" border="0" alt="" /></td>
  </tr>
  <tr><!-- row 9 -->
   <td rowspan="5" valign="top"><p style="margin:0px"></p></td>
   <td colspan="3" valign="top"><p style="margin:0px"></p></td>
   <td><img src="images/spacer.gif" width="1" height="42" border="0" alt="" /></td>
  </tr>
  <tr><!-- row 10 -->
   <td colspan="2" rowspan="3" align="left" valign="middle"><p style="margin:0px">しんにょうが２点の場合は<br />
こちらの文字をクリック</p></td>
   <td rowspan="4" valign="top"><p style="margin:0px"></p></td>
   <td><img src="images/spacer.gif" width="1" height="20" border="0" alt="" /></td>
  </tr>
  <tr><!-- row 11 -->
   <td rowspan="3" colspan="3" valign="top"><p style="margin:0px"></p></td>
   <td><img src="images/spacer.gif" width="1" height="4" border="0" alt="" /></td>
  </tr>
  <tr><!-- row 12 -->
   <td rowspan="2" valign="top"><p style="margin:0px"></p></td>
   <td><img src="images/spacer.gif" width="1" height="35" border="0" alt="" /></td>
  </tr>
  <tr><!-- row 13 -->
   <td colspan="2" valign="top"><p style="margin:0px"></p></td>
   <td><img src="images/spacer.gif" width="1" height="50" border="0" alt="" /></td>
  </tr>

</table>
			</div>


		<div class="footer" style="text-align: center;">
	    <div style="border-bottom: 8px solid #00C6FF;"></div>
			<div id="footer">			Copyright (C) 株式会社サンプリンティングシステム ALL Rights reserved.
			</div>
		</div>
		</div>

	</div>
</body>
</html>
