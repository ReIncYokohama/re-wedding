<?php
@session_start();
include("admin/inc/dbcon.inc.php");
include("admin/inc/class.dbo.php");
$obj = new DBO();

$server_root=str_replace("thanksregister.php","","http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);


if(isset($_SESSION['regs']))
{
   $user_arr = $_SESSION['regs'];
   $user_arr['creation_date']= time();
   $rand = rand();
   $user_arr['mail_check_number'] = $rand;
  //echo "<pre>";
  // print_r($user_arr);

	$userid = $obj->InsertData("spssp_user", $user_arr);

	if($userid >0)
	{
		$val = md5($rand);
		//echo $val;exit;
		$link = $server_root."user_confirm.php?value=".$val."&id=".$userid;
	
		$roomname =  $obj->GetSingleData(" spssp_room", " name", " id=".(int)$_SESSION['regs']['room_id']);
		$party_roomname = $obj->GetSingleData(" spssp_room", " name", " id=".(int)$_SESSION['regs']['party_room_id']);
	
		//$man_respect = $obj->GetSingleData(" spssp_respect", " title", " id=".(int)$_SESSION['regs']['man_respect_id']);
		//$woman_respect = $obj->GetSingleData(" spssp_respect", " title", " id=".(int)$_SESSION['regs']['woman_respect_id']);
	
$mail_body=<<<html
■新郎様氏名 : {$user_arr['man_lastname']} {$user_arr['man_firstname']}
■ふりがな : {$user_arr['man_furi_lastname']} {$user_arr['man_furi_firstname']}
■新婦様氏名: {$user_arr['woman_lastname']} {$user_arr['woman_firstname']}
■ふりがな : {$user_arr['woman_furi_lastname']} {$user_arr['woman_furi_firstname']}
■連絡先氏名 : {$user_arr['contact_name']}

■挙式日 : {$user_arr['marriage_day']}
■挙式開始時間 : {$user_arr['marriage_day_with_time']}
■挙式会場 : {$roomname}
■挙式種類 : {$user_arr['religion']}
■披露宴日: {$user_arr['party_day']}
■披露宴開始時間: {$user_arr['party_day_with_time']}
■披露宴会場: {$party_roomname}
■選択してください : {$user_arr['status']}



■郵便番号 : {$user_arr['zip1']}-{$user_arr['zip2']}
■都道府県: {$user_arr['state']}
■市区町村: {$user_arr['city']}
■番地: {$user_arr['street']}
■ビル、マンション名: {$user_arr['buildings']}

■電話番号: {$user_arr['tel']}
■FAX番号: {$user_arr['fax']}

■メールアドレス :  {$user_arr['mail']}
■User Name : {$user_arr['user_id']}
■Password : {$user_arr['password']}



■To Activate Please  : {$link}
(You may need to cut and paste the entire link above into your browser. 
If the URL wraps, be sure to copy it completely, leaving no blank spaces.)
html;

$admin_mail_body=<<<html
■新郎様氏名 : {$user_arr['man_lastname']} {$user_arr['man_firstname']}
■ふりがな : {$user_arr['man_furi_lastname']} {$user_arr['man_furi_firstname']}
■新婦様氏名: {$user_arr['woman_lastname']} {$user_arr['woman_firstname']}
■ふりがな : {$user_arr['woman_furi_lastname']} {$user_arr['woman_furi_firstname']}
■連絡先氏名 : {$user_arr['contact_name']}

■挙式日 : {$user_arr['marriage_day']}
■挙式開始時間 : {$user_arr['marriage_day_with_time']}
■挙式会場 : {$roomname}
■挙式種類 : {$user_arr['religion']}
■披露宴日: {$user_arr['party_day']}
■披露宴開始時間: {$user_arr['party_day_with_time']}
■披露宴会場: {$party_roomname}
■選択してください : {$user_arr['status']}



■郵便番号 : {$user_arr['zip1']}-{$user_arr['zip2']}
■都道府県: {$user_arr['state']}
■市区町村: {$user_arr['city']}
■番地: {$user_arr['street']}
■ビル、マンション名: {$user_arr['buildings']}

■電話番号: {$user_arr['tel']}
■FAX番号: {$user_arr['fax']}

■メールアドレス :  {$user_arr['mail']}
■User Name : {$user_arr['user_id']}
■Password : {$user_arr['password']}

html;

		@contactmail($user_arr['mail'],$mail_body,$admin_mail_body);
		unset($_SESSION['regs']);
	}						
}

?>
<?php include("inc/registration_header.inc.php");?>

	<table width="900" border="0" cellspacing="1" cellpadding="2" align="center" style="border:1px double #E4E5E5; padding:10px;">
		<tr>
			<td  height="300" align="center" valign="middle" style="text-align:center;" bgColor="#FFF4FA" ><b>Thanks for Registration.</b><br />
 Please activate your account from your mail accout<br><a href="index.php">ログイン</a></td>
			
		</tr>
		

	</table>
		<?php include("inc/new_footer.php");?>


