<?php
@session_start();

// Connecting, selecting database 

/*if (defined('SID')){ // use trans sid as its available
        ini_set("session.use_cookies", "0");
        ini_set("session.use_trans_sid", "true");
        ini_set("url_rewriter.tags", "a=href,area=href,script=src,link=href,"
. "frame=src,input=src,form=fakeentry");
    }else{
        ob_start('ob_sid_rewrite');
    }*/
	
	

if($_SERVER['HTTP_HOST']=='localhost')
	{
		$sqlhost='localhost';
		$sqluser='root';
		$sqlpassword="";
		$sqldatabase="deccs";
	}
else
	{
		$sqlhost='mysql106.db.sakura.ne.jp';
		$sqluser='dec-mankai';
		$sqlpassword="123456";
		$sqldatabase="dec-mankai";
	}


$link = mysql_connect($sqlhost, $sqluser,$sqlpassword)
    	or die("COULD NOT CONNECT : " . mysql_error());
mysql_select_db($sqldatabase) or die("COULD NOT SELECT DATABASE");
mysql_query("SET CHARACTER SET 'utf8'"); 
mysql_query("SET NAMES 'utf8'"); 

function jp_encode($string)
	{
		$string=trim($string);
		$string=stripslashes($string);
		$string=mb_convert_encoding($string, "UTF8", "SJIS");
		return $string;
	}

function jp_decode($string)
	{
		$string=trim($string);
		$string=stripslashes($string);
		$string=mb_convert_encoding($string, "SJIS", "UTF8");
		return $string;
	}
	
	
	
	
function leftside($categoryid='1')
	{
		 $sqldefault="select * from deccs_step where categoryid='".$categoryid."' LIMIT 0,1";
		$db_result=mysql_query($sqldefault);
		$leftstring="<table><tr>";
		if($db_row=mysql_fetch_array($db_result))
		{
		$leftstring.="<td><img src='image.php?f=category/step/".$db_row['thumb2']."&w=100' ><img style='display:none;' src='image.php?f=category/step/".$db_row['thumb1']."&w=100' ></td>";
		
		}
			echo $leftstring.="</table></tr>";
	
	
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
	    
		$to_admin='k.okubo@re-inc.jp';


		$header='From:'.$to_admin." \r\n";
		$header.='Content-Type:text/html; charset=shift_jis'."\r\n";

		$header1='From:'.$to." \r\n";
		$header1.='Content-Type:text/html; charset=shift_jis'."\r\n";
        //$header1.= "Cc: k.okubo@re-inc.jp\r\n";	

	
		$subject1='「DECCS  マイホームシミュレーション」マイページにご登録ありがとうございました。';
		$subject11 = base64_encode(mb_convert_encoding($subject1,"JIS","SJIS"));
		$usersubject = '=?ISO-2022-JP?B?'.$subject11.'?=';
	
		$subject0='「DECCS  マイホームシミュレーション」マイページにご登録ありがとうございました。';
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
	

function order_mail($to,$mailbody,$mailbody2)
	{
	
		//$to_admin='kumar@re-inc.jp';
	    
		$to_admin='k.okubo@re-inc.jp';

		

		$header='From:'.$to_admin." \r\n";
		$header.='Content-Type:text/html; charset=shift_jis'."\r\n";

		$header1='From:'.$to." \r\n";
		$header1.='Content-Type:text/html; charset=shift_jis'."\r\n";
        //$header1.= "Cc: k.okubo@re-inc.jp\r\n";	

	
		$subject1='「DECCS  マイホームシミュレーション」あなたが作成したプランを保存しました。';
		$subject11 = base64_encode(mb_convert_encoding($subject1,"JIS","SJIS"));
		$usersubject = '=?ISO-2022-JP?B?'.$subject11.'?=';
	
		$subject0='「DECCS  マイホームシミュレーション」あなたが作成したプランを保存しました。';
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
	
		//$to_admin='kumar@re-inc.jp';
	    
		$to_admin='k.okubo@re-inc.jp';

		

		$header='From:'.$to_admin." \r\n";
		$header.='Content-Type:text/html; charset=shift_jis'."\r\n";

		$header1='From:'.$to." \r\n";
		$header1.='Content-Type:text/html; charset=shift_jis'."\r\n";
        //$header1.= "Cc: k.okubo@re-inc.jp\r\n";	

	
		$subject1='「DECCS  マイホームシミュレーション」ご注文ありがとうございます。';
		$subject11 = base64_encode(mb_convert_encoding($subject1,"JIS","SJIS"));
		$usersubject = '=?ISO-2022-JP?B?'.$subject11.'?=';
	
		$subject0='「DECCS  マイホームシミュレーション」ご注文ありがとうございます。';
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
	
	
	function consult_mail($to,$mailbody,$mailbody2)
	{
	
		//$to_admin='kumar@re-inc.jp';
	    
		$to_admin='k.okubo@re-inc.jp';

		

		$header='From:'.$to_admin." \r\n";
		$header.='Content-Type:text/html; charset=shift_jis'."\r\n";

		$header1='From:'.$to." \r\n";
		$header1.='Content-Type:text/html; charset=shift_jis'."\r\n";
        //$header1.= "Cc: k.okubo@re-inc.jp\r\n";	

	
		$subject1='「DECCS  マイホームシミュレーション」説明・面談をご希望いただきありがとうございます。';
		$subject11 = base64_encode(mb_convert_encoding($subject1,"JIS","SJIS"));
		$usersubject = '=?ISO-2022-JP?B?'.$subject11.'?=';
	
		$subject0='「DECCS  マイホームシミュレーション」説明・面談をご希望いただきありがとうございます。';
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


	function research_mail($to,$mailbody,$mailbody2)
	{
	
		//$to_admin='kumar@re-inc.jp';
	    
		$to_admin='k.okubo@re-inc.jp';

		

		$header='From:'.$to_admin." \r\n";
		$header.='Content-Type:text/html; charset=shift_jis'."\r\n";

		$header1='From:'.$to." \r\n";
		$header1.='Content-Type:text/html; charset=shift_jis'."\r\n";
        //$header1.= "Cc: k.okubo@re-inc.jp\r\n";	

	
		$subject1='「DECCS  マイホームシミュレーション」土地調査をご希望いただきありがとうございます。';
		$subject11 = base64_encode(mb_convert_encoding($subject1,"JIS","SJIS"));
		$usersubject = '=?ISO-2022-JP?B?'.$subject11.'?=';
	
		$subject0='「DECCS  マイホームシミュレーション」土地調査をご希望いただきありがとうございます。';
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
	
		//$to_admin='kumar@re-inc.jp';
	    
		$to_admin='k.okubo@re-inc.jp';

		

		$header='From:'.$to_admin." \r\n";
		$header.='Content-Type:text/html; charset=shift_jis'."\r\n";

		$header1='From:'.$to." \r\n";
		$header1.='Content-Type:text/html; charset=shift_jis'."\r\n";
        //$header1.= "Cc: k.okubo@re-inc.jp\r\n";	

	
		$subject1='「DECCS  マイホームシミュレーション」お問い合わせありがとうございます。';
		$subject11 = base64_encode(mb_convert_encoding($subject1,"JIS","SJIS"));
		$usersubject = '=?ISO-2022-JP?B?'.$subject11.'?=';
	
		$subject0='「DECCS  マイホームシミュレーション」お問い合わせありがとうございます。';
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
		$sql_category_default="select * from deccs_category where autoid='".$_GET[catid]."'";
		$db_result_default=mysql_query($sql_category_default);
				if($db_row_dcategory=mysql_fetch_array($db_result_default))
					{
					$db_row_dcategory['defaultvalue'];
					if($db_row_dcategory['defaultvalue']!='1')
						{
							$package='true';
							$cattext='<a href="package.php">Package</a>';
						}
					else
						{
							$cattext='<a href="category.php">Category</a>';
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
	

?>
