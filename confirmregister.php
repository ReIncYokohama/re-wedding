<?php
@session_start();
include("admin/inc/dbcon.inc.php");
//dumpvar($_POST);
include("admin/inc/class.dbo.php");
$obj = new DBO();
$post = $obj->protectXSS($_POST);

if(isset($_POST['sub']))
{
    
	/////man name session
	$_SESSION['regs']['man_firstname'] =$post['man_firstname'];
    $_SESSION['regs']['man_lastname'] =$post['man_lastname'];
	$_SESSION['regs']['man_furi_lastname'] =$post['man_furi_lastname'];
    $_SESSION['regs']['man_furi_firstname'] =$post['man_furi_firstname'];
	//$_SESSION['regs']['man_respect_id'] =$post['man_respect_id'];
	//$_SESSION['regs']['woman_respect_id'] =$post['woman_respect_id'];
	
	////Woman name session
    $_SESSION['regs']['woman_firstname'] =$post['woman_firstname'];
    $_SESSION['regs']['woman_lastname'] =$post['woman_lastname'];
	$_SESSION['regs']['woman_furi_lastname'] =$post['woman_furi_lastname'];
    $_SESSION['regs']['woman_furi_firstname'] =$post['woman_furi_firstname'];
	
	$_SESSION['regs']['contact_name'] =$post['contact_name'];
	
	/// marriage day and party day session
    $_SESSION['regs']['marriage_day'] =$post['marriage_day'];
    $_SESSION['regs']['marriage_day_with_time'] =$post['marriage_day_with_time'];
	$_SESSION['regs']['party_day'] =$post['party_day'];
    $_SESSION['regs']['party_day_with_time'] =$post['party_day_with_time'];
	
	
    $_SESSION['regs']['status'] =$post['status'];
    $_SESSION['regs']['religion'] =$post['religion'];
	
	
    
	
	//// Address session
	$_SESSION['regs']['zip1'] =$post['zip1'];
	$_SESSION['regs']['zip2'] =$post['zip2'];
	$_SESSION['regs']['state'] =$post['state'];
	$_SESSION['regs']['city'] =$post['city'];
	$_SESSION['regs']['street'] =$post['street'];
	$_SESSION['regs']['buildings'] =$post['buildings'];
	
	///$_SESSION['regs']['address'] =$post['address'];
	
   	$_SESSION['regs']['tel'] =$post['tel'];
	$_SESSION['regs']['fax'] =$post['fax'];
    $_SESSION['regs']['mail'] =$post['mail'];
    $_SESSION['regs']['user_id'] =$post['user_id'];
	$_SESSION['regs']['password'] =$post['password'];
	$_SESSION['regs']['room_id'] =$post['room_id'];
	$_SESSION['regs']['party_room_id'] =$post['party_room_id'];

	$roomname =  $obj->GetSingleData(" spssp_room", " name", " id=".(int)$_SESSION['regs']['room_id']);
	$party_roomname = $obj->GetSingleData(" spssp_room", " name", " id=".(int)$_SESSION['regs']['party_room_id']);
	
	//$man_respect = $obj->GetSingleData(" spssp_respect ", " title", " id=".(int)$_SESSION['regs']['man_respect_id']);
	//$woman_respect = $obj->GetSingleData(" spssp_respect ", " title", " id=".(int)$_SESSION['regs']['woman_respect_id']);

}
include_once("inc/registration_header.inc.php");
?>

<form name="registerfrm" action="thanksregister.php" method="post">
	<table width="700" border="0" cellspacing="2" cellpadding="2" align="center" style="border:1px double #E4E5E5;padding:10px;">

		<tr>
			<td width="200" bgColor="#FFF4FA" ><font color=red size="-1">※</font>新郎様氏名</td>
			<td  bgcolor="#FFF8FA"><?=$_SESSION['regs']['man_lastname'];?>&nbsp;<?=$_SESSION['regs']['man_firstname'];?></td>
		</tr>

		<tr>
			<td width="200" bgColor="#FFF4FA" ><font color=red size="-1">※</font>ふりがな</td>
			<td  bgcolor="#FFF8FA"><?=$_SESSION['regs']['man_furi_lastname'];?>&nbsp;<?=$_SESSION['regs']['man_furi_firstname'];?></td>
		</tr>
        
        <tr>
			<td width="200" bgColor="#FFF4FA" ><font color=red size="-1">※</font>新婦様氏名</td>
			<td  bgcolor="#FFF8FA"><?=$_SESSION['regs']['woman_lastname'];?>&nbsp;<?=$_SESSION['regs']['woman_firstname'];?></td>
		</tr>

		<tr>
			<td width="200" bgColor="#FFF4FA" ><font color=red size="-1">※</font>ふりがな</td>
			<td  bgcolor="#FFF8FA"><?=$_SESSION['regs']['woman_furi_lastname'];?>&nbsp;<?=$_SESSION['regs']['woman_furi_firstname'];?></td>
		</tr>
        <tr>
			<td width="200" bgColor="#FFF4FA" ><font color=red size="-1">※</font>連絡先氏名</td>
			<td  bgcolor="#FFF8FA"><?=$_SESSION['regs']['contact_name'];?></td>
		</tr>

		<tr>
			<td width="200" bgColor="#FFF4FA" ><font color=red size="-1">※</font>挙式日</td>
			<td  bgcolor="#FFF8FA">
            	<?=$_SESSION['regs']['marriage_day'];?>
            </td>
		</tr>
        
         <tr>
			<td width="200" bgColor="#FFF4FA" ><font color=red size="-1">※</font>挙式開始時間</td>
			<td  bgcolor="#FFF8FA">
            	<?=$_SESSION['regs']['marriage_day_with_time'];?>
            </td>
		</tr>
        
          <tr>
            <td width="200" bgColor="#FFF4FA" ><font color=red size="-1">※</font> 挙式会場</td>
            <td  bgcolor="#FFF8FA">
             <?=$roomname?>          
               </td>
       	</tr>
        <tr>
			<td width="200" bgColor="#FFF4FA" ><font color=red size="-1">※</font>挙式種類</td>
			<td  bgcolor="#FFF8FA"><?=$_SESSION['regs']['religion'];?></td>
		</tr>
        
        <tr>
			<td width="200" bgColor="#FFF4FA" ><font color=red size="-1">※</font>披露宴日</td>
			<td  bgcolor="#FFF8FA">
            	<?=$_SESSION['regs']['party_day'];?>
            </td>
		</tr>
        
        <tr>
			<td width="200" bgColor="#FFF4FA" ><font color=red size="-1">※</font>披露宴開始時間</td>
			<td  bgcolor="#FFF8FA">
            	<?=$_SESSION['regs']['party_day_with_time'];?>
            </td>
		</tr>
        
         <tr>
            <td width="200" bgColor="#FFF4FA" ><font color=red size="-1">※</font> 披露宴会場</td>
            <td  bgcolor="#FFF8FA">
             <?=$party_roomname?>          
               </td>
       	</tr>

		<tr>
			<td width="200" bgColor="#FFF4FA" ><font color=red size="-1">※</font>選択してください</td>
			<td  bgcolor="#FFF8FA"><?=$_SESSION['regs']['status']?></td>
		</tr>

		

		<tr>
			<td align="left" bgColor="#FFF4FA" ><font color=red size="-1">※</font> 住所</td>
			<td align="left"  bgcolor="#FFF8FA">
				<table width="100%" border="0" cellspacing="1" cellpadding="1">
					<tr>
						<td  width="90" nowrap="nowrap"><font size="-1"><font color=red size="-1">※</font>郵便番号：</font></td>
						<td>
							<?=($_SESSION['regs']['zip1']);?>-<?=($_SESSION['regs']['zip2']);?>
				
                         </td>
					</tr>
                    <tr>
						<td nowrap="nowrap"><font size="-1"><font color=red size="-1">※</font> 都道府県：</font></td>
						<td><?=($_SESSION['regs']['state'])?></td>
					</tr>
                     <tr>
						<td nowrap="nowrap"><font size="-1"><font color=red size="-1">※</font> 市区町村：</font></td>
						<td><?=($_SESSION['regs']['city'])?></td>
					</tr>
                     <tr>
						<td nowrap="nowrap"><font size="-1"><font color=red size="-1">※</font> 番地：</font></td>
						<td><?=($_SESSION['regs']['street'])?></td>
					</tr>
                     <tr>
						<td nowrap="nowrap"><font size="-1"> ビル、マンション名：</font></td>
						<td><?=($_SESSION['regs']['buildings'])?></td>
					</tr>
				</table>
             </td>
		</tr>
         <tr>
			<td bgColor="#FFF4FA" align="left"> 電話番号</td>
			<td bgColor="#FFF8FA" align="left">
				<?=$_SESSION['regs']['tel']?>
             </td>
		</tr>
        <tr>
			<td bgColor="#FFF4FA" align="left"> FAX番号</td>
			<td bgColor="#FFF8FA" align="left">
				<?=$_SESSION['regs']['fax']?>
             </td>
		</tr>
		<tr>
			<td bgColor="#FFF4FA" align="left"><font color=red size="-1">※</font> メールアドレス</td>
			<td bgColor="#FFF8FA" align="left">
				<?=$_SESSION['regs']['mail']?>
             </td>
		</tr>
        

        
        <tr>
			<td bgColor="#FFF4FA" align="left"><font color=red size="-1">※</font>ログインID</td>
			<td align="left"  bgColor="#FFF8FA" ><?=$_SESSION[regs]['user_id']?></td>
		</tr>
        
		<tr>
			<td bgColor="#FFF4FA" align="left"><font color=red size="-1">※</font> パスワード</td>
			<td bgColor="#FFF8FA" align="left">
				<?=$_SESSION['regs']['password']?> 		</td>
		</tr>
		<tr>
		
			<td bgColor="#FFF4FA" align="left"><font color=red size="-1">※</font>担当名</td>
			<td align="left"   bgColor="#FFF8FA">320</td>
		</tr>        
		<tr>
				
				<td align="center" colspan="2"   bgColor="#FFF1FA">
                	&nbsp;<input type="submit" name="sub" value="送信" />
				&nbsp;<input type="button" value="戻る"  onclick="javascript:window.location='register.php'" />
				</td>
		</tr>
	</table>
 </form>

<?php

include_once("inc/new_footer.php");
?>
