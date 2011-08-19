<?php
@session_start();

if($_SERVER['HTTP_HOST']=='localhost')
	{
		$sqlhost='localhost';
		$sqluser='root';
		$sqlpassword="";
		$sqldatabase="spssp";
	}
	else
	{
		$sqlhost='localhost';
		$sqluser='dev2_hotel1';
		$sqlpassword="dev2_123456";
		$sqldatabase="dev2_hotel1";

	}


$link = mysql_connect($sqlhost, $sqluser,$sqlpassword)
    	or die("COULD NOT CONNECT : " . mysql_error());

mysql_select_db($sqldatabase) or die("COULD NOT SELECT DATABASE");
mysql_query("SET CHARACTER SET 'utf8'");
mysql_query("SET NAMES 'utf8'");

//$link2 = mysql_connect($sqlhost2, $sqluser2,$sqlpassword2)  	or die("COULD NOT CONNECT : " . mysql_error());

$Client_site_url="http://re-dev.sakura.ne.jp/dev2/hotel11/";
$Admin_site_url="http://re-dev.sakura.ne.jp/dev2/hotel11/admin/";
function curPageURL()
 {
		 $pageURL = 'http';
		 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		 $pageURL .= "://";
		 if ($_SERVER["SERVER_PORT"] != "80") {
		  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		 } else {
		  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		 }
		 return $pageURL;
	}

function jp_encode($string)
	{
		$string=trim($string);
		$string=stripslashes($string);
		//$string=mb_convert_encoding($string, "UTF8", "SJIS");
		return $string;
	}

function jp_decode($string)
	{
		$string=trim($string);
		$string=stripslashes($string);
		//$string=mb_convert_encoding($string, "SJIS", "UTF8");
		return $string;
	}

function redirect($url)
	{
		if(!headers_sent())
			header("location:".$url);
		else
			echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
	}

	function mysubstr($string, $limit, $break=".", $pad="")
{
  // return with no change if string is shorter than $limit
  if(mb_strlen($string,"EUC-JP") <= $limit) return $string;

  // is $break present between $limit and the end of the string?
  if(false !== ($breakpoint = mb_strpos($string, $break, $limit,"EUC-JP"))) {
    if($breakpoint < mb_strlen($string,"EUC-JP") - 1) {
      ///$string = substr($string, 0,$limit, $breakpoint) . $pad;
	  $string =  mb_substr($string,0,$breakpoint,"EUC-JP" ). $pad;
    }
  }

  return $string;
}

function restoreTags($input)
{
  $opened = $closed = array();

  // tally opened and closed tags in order
  if(preg_match_all("/<(\/?[a-zA-Z]+)>/i", $input, $matches)) {
    foreach($matches[1] as $tag) {
      if(preg_match("/^[a-zA-Z]+$/i", $tag, $regs)) {
        $opened[] = $regs[0];
      } elseif(preg_match("/^\/([a-zA-Z]+)$/i", $tag, $regs)) {
        $closed[] = $regs[1];
      }
    }
  }

  // use closing tags to cancel out opened tags
  if($closed) {
    foreach($opened as $idx => $tag) {
      foreach($closed as $idx2 => $tag2) {
        if($tag2 == $tag) {
          unset($opened[$idx]);
          unset($closed[$idx2]);
          break;
        }
      }
    }
  }

  // close tags that are still open
  if($opened) {
    $tagstoclose = array_reverse($opened);
    foreach($tagstoclose as $tag) $input .= "</$tag>";
  }

  return $input;
}

function randtext($length)
	{
	  $random= "";
	  srand((double)microtime()*1000000);
	  $strset  = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	  $strset .= "abcdefghijklmnopqrstuvwxyz";
	  $strset .= "1234567890";
	  // Add the special characters to $strset if needed

	  for($i = 0; $i < $length; $i++)
		  {
			$random .= substr($strset,(rand()%(strlen($strset))), 1);
		  }
	  return $random;
	}


function ob_sid_rewrite($buffer){
    $replacements = array(
        '/<\s*(a|link|script)\s[^>]*(href|src)\s*=\s*"([^"]*)"/',
        '/<\s*(a|link|script)\s[^>]*(href|src)\s*=\s*\'([^\'<>]*)\'/',
        );

   $buffer = preg_replace_callback($replacements, "pa_sid_rewriter", $buffer);

   $buffer = preg_replace('/<form\s[^>]*>/',
        '\0<input type="hidden" name="' . session_name() . '" value="' . session_id() . '"/>', $buffer);

   return $buffer;
}

function pa_sid_rewriter($matches){
    $buf = $matches[0];
    $url = $matches[3];
    $url_orig=$url;
    if ($url[0]=='/' || $url[0]=='#' || preg_match('/^[A-Za-z0-9]*:/', $url))
        return $buf;

    $ses_name = session_name();
    if (strstr($url, "$session_name="))
        return $buf;

    $p = strpos($url, "#");
    $ref = false;
    if($p){
        $ref = substr($url, $p);
        $url = substr($url, 0, $p);
    }
    if (strlen($url)==0)
        return $buf;
    if (!strstr($url, "?"))
        $url.="?";
    else
        $url.="&amp;";
    $url.=session_name() ."=".session_id();
    if($ref)
        $url.=$ret;
    return str_replace($url_orig, $url, $buf);
}

function dumpvar($str)
{
  echo "<pre>";
  print_r($str);
}

function registration_mail($to,$mailbody,$mailbody2)
	{

		//$to_admin='kumar@re-inc.jp';

		$header='From:'.$to_admin." \r\n";
		$header.='Content-Type:text/plain; charset=shift_jis'."\r\n";

		$header1='From:'.$to." \r\n";
		$header1.='Content-Type:text/plain; charset=shift_jis'."\r\n";
        //$header1.= "Cc: k.okubo@re-inc.jp\r\n";


		$subject1='Registration mail';
		$subject11 = base64_encode(mb_convert_encoding($subject1,"JIS","SJIS"));
		$usersubject = '=?ISO-2022-JP?B?'.$subject11.'?=';

		$subject0='Registration mail';
		$subject00 = base64_encode(mb_convert_encoding($subject0,"JIS","SJIS"));
		$adminsubject = '=?ISO-2022-JP?B?'.$subject00.'?=';


	    $user_body=$mailbody;

		$admin_body=$mailbody2;


		//// MAIL TO ADMIN////////////////
		if(@mail($to_admin, $adminsubject, $admin_body, $header1))
			{
				//////MAIL TO USER /////////////
				if(@mail($to, $usersubject, $user_body, $header))
				{
					//header('location:thanks.html');
				}
			}


	}
function forgetPassword_mail($to,$mailbody)
	{

		//$to_admin='kumar@re-inc.jp';

		//$header='From:'.$to_admin." \r\n";
		//$header.='Content-Type:text/plain; charset=shift_jis'."\r\n";

		$header1='From:'.$to." \r\n";
		$header1.='Content-Type:text/plain; charset=utf-8'. "\r\n";
        //$header1.= "Cc: k.okubo@re-inc.jp\r\n";


		//$subject1='パスワードを忘れる';
		//$subject11 = base64_encode(mb_convert_encoding($subject1,"JIS","SJIS"));
		//$usersubject = '=?ISO-2022-JP?B?'.$subject11.'?=';

		$subject0='パスワードを送信しました';
		$charset = 'UTF-8';
		$usersubject = "=?$charset?B?" . base64_encode($subject0) . "?=\n";



	   $user_body=$mailbody;

		//////MAIL TO USER /////////////
		if(@mail($to, $usersubject, $user_body, $header1))
			{
				return 1;
			}





	}

function hotelPassword_mail($to,$mailbody)
	{

		$to_admin='kumar@re-inc.jp';

		$header='From:'.$to_admin." \r\n";
        $header.='Content-Type:text/plain; charset=utf-8'. "\r\n";
		$header1='From:'.$to." \r\n";
		$header1.='Content-Type:text/plain; charset=utf-8'. "\r\n";
        //$header1.= "Cc: k.okubo@re-inc.jp\r\n";

		$subject0='パスワードを忘れる';
		$charset = 'UTF-8';
		$usersubject = "=?$charset?B?" . base64_encode($subject0) . "?=\n";


		$subject1='パスワードを忘れる1';
		$charset = 'UTF-8';
		$adminsubject = "=?$charset?B?" . base64_encode($subject1) . "?=\n";


	   $user_body=$mailbody;
	   $admin_body=$mailbody;


		//// MAIL TO ADMIN////////////////
		if(@mail($to_admin, $adminsubject, $admin_body, $header1))
			{
				//////MAIL TO USER /////////////
				if(@mail($to, $usersubject, $user_body, $header))
				{
					//header('location:thanks.html');
				}
			}


	}


function order_mail($to,$mailbody,$mailbody2)
	{

		$to_admin='kumar@re-inc.jp';


		$header='From:'.$to_admin." \r\n";
		$header.='Content-Type:text/plain; charset=shift_jis'."\r\n";

		$header1='From:'.$to." \r\n";
		$header1.='Content-Type:text/plain; charset=shift_jis'."\r\n";
        //$header1.= "Cc: k.okubo@re-inc.jp\r\n";


		$subject1='Order mail';
		$subject11 = base64_encode(mb_convert_encoding($subject1,"JIS","SJIS"));
		$usersubject = '=?ISO-2022-JP?B?'.$subject11.'?=';

		$subject0='Order mail';
		$subject00 = base64_encode(mb_convert_encoding($subject0,"JIS","SJIS"));
		$adminsubject = '=?ISO-2022-JP?B?'.$subject00.'?=';


	    $user_body=$mailbody;

		$admin_body=$mailbody2;


		//// MAIL TO ADMIN////////////////
		if(@mail($to_admin, $adminsubject, $admin_body, $header1))
			{
				//////MAIL TO USER /////////////
				if(@mail($to, $usersubject, $user_body, $header))
				{
					//header('location:thanks.html');
				}
			}


	}

function planorder_mail($to,$mailbody,$mailbody2)
	{

		$to_admin='kumar@re-inc.jp';


		$header='From:'.$to_admin." \r\n";
		$header.='Content-Type:text/plain; charset=shift_jis'."\r\n";

		$header1='From:'.$to." \r\n";
		$header1.='Content-Type:text/plain; charset=shift_jis'."\r\n";
        //$header1.= "Cc: k.okubo@re-inc.jp\r\n";


		$subject1='planorder_mail';
		$subject11 = base64_encode(mb_convert_encoding($subject1,"JIS","SJIS"));
		$usersubject = '=?ISO-2022-JP?B?'.$subject11.'?=';

		$subject0='planorder_mail';
		$subject00 = base64_encode(mb_convert_encoding($subject0,"JIS","SJIS"));
		$adminsubject = '=?ISO-2022-JP?B?'.$subject00.'?=';


	    $user_body=$mailbody;

		$admin_body=$mailbody2;


		//// MAIL TO ADMIN////////////////
		if(@mail($to_admin, $adminsubject, $admin_body, $header1))
			{
				//////MAIL TO USER /////////////
				if(@mail($to, $usersubject, $user_body, $header))
				{
					//header('location:thanks.html');
				}
			}


	}



function inquery_mail($to,$mailbody,$mailbody2)
	{

		$to_admin='kumar@re-inc.jp';


		$header='From:'.$to_admin." \r\n";
		$header.='Content-Type:text/plain; charset=shift_jis'."\r\n";

		$header1='From:'.$to." \r\n";
		$header1.='Content-Type:text/plain; charset=shift_jis'."\r\n";
        //$header1.= "Cc: k.okubo@re-inc.jp\r\n";


		$subject1='inquery_mail';
		$subject11 = base64_encode(mb_convert_encoding($subject1,"JIS","SJIS"));
		$usersubject = '=?ISO-2022-JP?B?'.$subject11.'?=';

		$subject0='inquery_mail';
		$subject00 = base64_encode(mb_convert_encoding($subject0,"JIS","SJIS"));
		$adminsubject = '=?ISO-2022-JP?B?'.$subject00.'?=';


	    $user_body=$mailbody;

		$admin_body=$mailbody2;


		//// MAIL TO ADMIN////////////////
		if(@mail($to_admin, $adminsubject, $admin_body, $header1))
			{
				//////MAIL TO USER /////////////
				if(@mail($to, $usersubject, $user_body, $header))
				{
					//header('location:thanks.html');
				}
			}


	}


	if($_GET[catid])
	{
	$loginurl="login.php?catid=".$_GET[catid];
	$logouturl="logout.php?catid=".$_GET[catid];
	$mypageurl="mypage.php?catid=".$_GET[catid];
	$displayplanurl="details_plan.php?catid=".$_GET[catid];
	$registrationurl="registration.php?catid=".$_GET[catid];
	$registrationconfirmurl="registration_confirm.php?catid=".$_GET[catid];
	$registrationthanksurl="registration_thanks.php?catid=".$_GET[catid];
	}
	else
	{
	$loginurl="login.php";
	$logouturl="logout.php";
	$mypageurl="mypage.php";
	$displayplanurl="details_plan.php";
	$registrationurl="registration.php";
	$registrationconfirmurl="registration_confirm.php";
	$registrationthanksurl="registration_thanks.php";

	}

		function contactmail($from,$mailbody)
	{

		$to_admin='kumar@re-inc.jp';
		$header='From:'.$to_admin." \r\n";
		$header.='Content-Type:text/plain; charset=utf-8'. "\r\n";



		$subject1='メールオーダーフォーム';
		$charset = 'UTF-8';
		$usersubject = "=?$charset?B?" . base64_encode($subject1) . "?=\n";


		$subject0='メールオーダーフォーム';
		$adminsubject = "=?$charset?B?" . base64_encode($subject0) . "?=\n";



		$to_admin='kumar@re-inc.jp';

		$header='From:'.$to_admin." \r\n";
		$header.='Content-Type:text/plain; charset=utf-8'. "\r\n";

		$header1='From:'.$from." \r\n";
		$header1.='Content-Type:text/plain; charset=utf-8'. "\r\n";
		//$header1.= "Cc: k.okubo@re-inc.jp\r\n";

		$subject1='仮予約ありがとうございます';
		$charset = 'UTF-8';
		//$subject11 = base64_encode(mb_convert_encoding($subject1,"JIS","SJIS"));
		$usersubject = "=?$charset?B?" . base64_encode($subject1) . "?=\n";

		$subject0='仮予約のお申し込みがありました';
		$adminsubject = "=?$charset?B?" . base64_encode($subject0) . "?=\n";


		$user_body=$mailbody;

		$admin_body=$mailbody;


				//// MAIL TO ADMIN////////////////
				if(@mail($to_admin, $adminsubject, $admin_body, $header1))
					{
						//////MAIL TO USER /////////////
						if(@mail($from, $usersubject, $user_body, $header))
						{
							//header('location:thanks.html');
						}
					}
				else echo 'エラー';

	}



	if($_SESSION['adminid'] !='' && $_SESSION['user_type'] !='')
	{
	$sql="update spssp_admin set updatetime='".date("Y-m-d H:i:s")."' WHERE id='".$_SESSION['adminid']."';";
	mysql_query($sql);
	}

	$currentdate = date('Y-m-d H:i:s');
	$query = "update `spssp_admin` set sessionid='' WHERE (TIMESTAMPDIFF(MINUTE,updatetime,'".$currentdate."')) > 2";
	mysql_query($query);




function uploadFile($path,$file,$filename ,$extension='')
{
	$ext = strtoupper(end(explode(".", $file)));
	$samplefile=time().".".$ext;

	if($extension)
	{
		if(ucwords($ext) == 'PDF' || ucwords($ext) == 'CSV')
		{

			@move_uploaded_file($_FILES[$filename]["tmp_name"],$path.$samplefile);
			return $message = $path.$samplefile;

		}

	}
	else
	{
	  @move_uploaded_file($_FILES[$filename]["tmp_name"],$path.$samplefile);
	  return $message = $path.$samplefile;

	}
}



	$message_array  =  array(1 => "保存されました", //Saved
				  			 2 => "変更されました", // Was modified
							 3 => "削除されました", //Was removed
							 4 => "更新されました", //Updated
							 5 => "Your sub-order has been placed to the admin.Soon admin will send you a message.",
							 6 => "User sub-order has been e-mailed to the Print Comany.",
							 7 => "Your gift day limit request has been send to the admin.",
							 8 => "Your final order has been placed to the admin.Soon admin will send you a message.",
							 9 => "User final order has been e-mailed to the Print Comany.",
							10 => "Your Day limit over request has been placed to the admin.Soon admin will send you a message.",
							11 => "Your request has been proccessed.",
							   );
	$error_array  =  array(	  1  =>  "データベースエラー", //Database Error
				              2	 =>  "権限を選択してください", //Please select privilege
				              3  =>  "氏名が重複しております。", //We have duplicate names.
							  4  => "情報を変更できる期間を過ぎております。",//Expired, we can change the information.
 							  5  => "入力したユーザーは既に存在します",//User entered already exists
							  6  =>"",
							  7  => "行番号は、部屋で許可されているを超える",//Row number exceeds that the room allows
							  8  => "列番号は、部屋で許可されているを超える",//Column number exceeds that the room allows//
							  9  => "座席番号は、部屋で許可されているを超えています。",//Seat number exceeds that the room allows.
							  10 => "管理者ユーザーは既に存在します",//Admin user already exists
							  11 => "不明なエラーです",///Unknown error
							  12 => "あなたは計画の1つのゲストを少なくともするために必要な",//You must have to atleast one guest in the plan
							  13 => "あなたはまず、宿泊プランを修正することがあります",// You First Have Fix Your Room Plan
							  14 => "1つのゲストを少なくともするために必要な ",//You must have to atleast one guest
							  15 => "あなたが最初の計画の基準を定義する必要があります。ウェブマスターに連絡してください",//You first have to define plan criteria. Please contact to webmaster
							  16 => "Please enter correct ログインID / パスワード",//Login ID / password
							  17 => "アカウントの有効期限が切れました",//Account Expired
							  18 => "このメールアドレスで新規登録ができません。メールアドレス入力してください。",//Not valid your email address. Please enter your email address.
							  19 => "同じ卓名のため変更されませんでした。",//Duplicate entries. Please try another.
							  20 => "同じ卓名のため変更されませんでした。",//Duplicate entries. Please try another.
							  21 => "",
							  22 => "",
							  23 => "",
							  24 => "",
							  25 => "",
							  26 => "席次表の詳細設定がされておりません。担当者へご連絡ください",
							  27 => "Your staff email address is wrong.Please contact to your admin.",
							  28 => "There is an unexpecter situation on mail sending.Please contact to your admin.",
							  29 => "Your admin message sending error",
							  30 => "The user did not select any print company.Please select one or contact to the user.",
				             );

define('SCRIPT_VERSION', '4');
define('ADMIN_LINK', 'http://re-dev.sakura.ne.jp/dev2/hotel11/admin/');     //this link is used in email system
define('MAIN_LINK', 'http://re-dev.sakura.ne.jp/dev2/hotel11/');           //this link is used in email system
define('PRINT_COMPANY_LINK', 'http://re-dev.sakura.ne.jp/dev2/hotel11/print/');     //this link is used in email system
define('GIFT_GROUP_NAME', '');
define('GIFT_ITEM_NAME', 'Item');
define('MENU_GROUP_NAME', 'Menu');
define('MENU_GROUP_DESCRIPTION', 'This Menu is for children.');
define('LAST_GIFT_PRESENT_DATE_MSG', '締切予定日: ');
define('LAST_GIFT_PRESENT_DATE_EXCEED_MSG', '校了予定日 :');


//Admin side display text for order,print request

define('INFO_A', '様から仮発注依頼がありました。');
define('INFO_D', '様から印刷依頼がありました。');
define('INFO_F', '様から引出物発注依頼がありました。');
define('INFO_B', '様向け印刷イメージが出来上がりました。');
define('INFO_E', '様は席次表本発注締切日を過ぎています。');
define('INFO_G', '様は引出物本発注締切日を過ぎています。');


//USER side display text for order,print request

define('INFO_C', '印刷イメージが出来上がりました。');
define('INFO_H', '引出物の締切日を過ぎております。至急担当までご連絡の上、発注作業をお願いします。');
define('INFO_I', '席次表の印刷締切日を過ぎております。至急担当までご連絡の上、確認作業をお願いします。');
define('INFO_J', '席次表の印刷締切日が近づいております。早めにご確認をお願いします。');
define('INFO_K', '引出物の締切日が近づいております。早めにご確認をお願いします。');



?>