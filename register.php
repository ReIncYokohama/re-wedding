<?php
include_once("admin/inc/dbcon.inc.php");
@session_start();
include_once("inc/registration_header.inc.php");
require_once ('admin/zipcode.inc'); 
require_once("admin/inc/class.dbo.php");
$obj = new DBO();
$rooms = $obj->GetAllRow("spssp_room");
include("admin/inc/main_dbcon.inc.php");
$respects = $obj->GetAllRow("dev2_main.spssp_respect");
include("admin/inc/return_dbcon.inc.php");

?>
<script src="js/noConflict.js" type="text/javascript"></script>
<script type="text/javascript" src="admin/calendar/calendar.js"></script>
<script src="js/registration_validation.js" type="text/javascript"></script>

<script type="text/javascript" language="javascript" src="datepicker/prototype-1.js"></script>

<script type="text/javascript" language="javascript" src="datepicker/prototype-date-extensions.js"></script>
<script type="text/javascript" language="javascript" src="datepicker/behaviour.js"></script>

<script type="text/javascript" language="javascript" src="datepicker/datepicker.js"></script>
<script type="text/javascript">

Control.DatePicker.Locale['ahad'] = { dateTimeFormat: 'yyyy-MM-dd HH:mm', dateFormat: 'yyyy-MM-dd', firstWeekDay: 1, weekend: [0,6], language: 'ahad'};

Control.DatePicker.Language['ahad'] = { months: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'], days: [  '日','月','火', '水', '木', '金', '土'], strings: { 'Now': '今度', 'Today': '今日', 'Time': '時間', 'Exact minutes': '正確な分', 'Select Date and Time': '選択して日付と時刻', 'Open calendar': 'オープンカレンダー' } };
</script>

<link rel="stylesheet" href="datepicker/datepicker.css">
<script type="text/javascript" language="javascript" src="datepicker/behaviors.js"></script>

<form name="registerfrm" action="confirmregister.php" method="post" id="registerfrm">
	<table width="700" border="0" cellspacing="2" cellpadding="2" align="center" style="border:1px double #E4E5E5; padding:10px;">

		<tr>
			<td width="200" bgColor="#FFF4FA" ><font color=red size="-1">※</font>新郎様氏名</td>
			<td  bgcolor="#FFF8FA">&nbsp;姓&nbsp;:&nbsp;<input type="text" name="man_lastname" id="man_lastname"  value="<?=$_SESSION['regs'][man_lastname];?>"/>
                &nbsp;名&nbsp;:&nbsp;<input type="text" name="man_firstname" id="man_firstname"  value="<?=$_SESSION['regs']['man_firstname'];?>"/> &nbsp;
                
               <!-- <select name="man_respect_id" id="man_respect_id">
                	<?php
                    	foreach($respects as $rsp)
						{
							if($_SESSION['regs']['man_respect_id'] == $rsp['id'])
							{
								$sel = "Selected = 'Selected'";
							}
							
							echo "<option value='".$rsp['id']."' $sel>".$rsp['title']."</option>";
						}
					?>
                </select> -->
            </td>
		</tr>
<!-- Man Furigana name start -->
		<tr>
			<td width="200" bgColor="#FFF4FA" ><font color=red size="-1">※</font>ふりがな</td>
			<td  bgcolor="#FFF8FA">&nbsp;姓&nbsp;:&nbsp;<input type="text" name="man_furi_lastname" id="man_furi_lastname"  value="<?=$_SESSION['regs']['man_furi_lastname'];?>"/>
                &nbsp;名&nbsp;:&nbsp;<input type="text" name="man_furi_firstname" id="man_furi_firstname"  value="<?=$_SESSION['regs']['man_furi_firstname'];?>"/> &nbsp;
                
                 <!-- <select name="man_respect_id" id="man_respect_id">
                	<?php
                    	foreach($respects as $rsp)
						{
							if($_SESSION['regs']['man_respect_id'] == $rsp['id'])
							{
								$sel = "Selected = 'Selected'";
							}
							
							echo "<option value='".$rsp['id']."' $sel>".$rsp['title']."</option>";
						}
					?>
                </select> -->
            </td>
		</tr>

        
        <tr>
			<td width="200" bgColor="#FFF4FA" ><font color=red size="-1">※</font>新婦様氏名</td>
			<td  bgcolor="#FFF8FA">&nbsp;姓&nbsp;:&nbsp;<input type="text" name="woman_lastname" id="woman_lastname"  value="<?=$_SESSION['regs']['woman_lastname'];?>"/>
            	
            	&nbsp;名&nbsp;:&nbsp;<input type="text" name="woman_firstname" id="woman_firstname"  value="<?=$_SESSION['regs']['woman_firstname'];?>"/> &nbsp;
                <!--  <select name="woman_respect_id" id="woman_respect_id">
                	<?php
                    	foreach($respects as $rsp)
						{
							if($_SESSION['regs']['woman_respect_id'] == $rsp['id'])
							{
								$sel = "Selected = 'Selected'";
							}
							
							echo "<option value='".$rsp['id']."' $sel>".$rsp['title']."</option>";
						}
					?>
                </select>-->
            </td>
		</tr>

<!-- Woman Furigana name start -->
        <tr>
			<td width="200" bgColor="#FFF4FA" ><font color=red size="-1">※</font>ふりがな</td>
			<td  bgcolor="#FFF8FA">&nbsp;姓&nbsp;:&nbsp;<input type="text" name="woman_furi_lastname" id="woman_furi_lastname"  value="<?=$_SESSION['regs']['woman_furi_lastname'];?>"/>
            	
            	&nbsp;名&nbsp;:&nbsp;<input type="text" name="woman_furi_firstname" id="woman_furi_firstname"  value="<?=$_SESSION['regs']['woman_furi_firstname'];?>"/> &nbsp;
                <!--  <select name="woman_respect_id" id="woman_respect_id">
                	<?php
                    	foreach($respects as $rsp)
						{
							if($_SESSION['regs']['woman_respect_id'] == $rsp['id'])
							{
								$sel = "Selected = 'Selected'";
							}
							
							echo "<option value='".$rsp['id']."' $sel>".$rsp['title']."</option>";
						}
					?>
                </select>-->
            </td>
		</tr>


        <tr>
			<td width="200" bgColor="#FFF4FA" ><font color=red size="-1">※</font>連絡先氏名</td>
			<td  bgcolor="#FFF8FA"><input type="text" name="contact_name" id="contact_name"  value="<?=$_SESSION['regs']['contact_name'];?>"/></td>
		</tr>

		<tr>
			<td width="200" bgColor="#FFF4FA" ><font color=red size="-1">※</font>挙式日</td>
			<td  bgcolor="#FFF8FA">
            	<input type="text" name="marriage_day" id="marriage_day"  value="<?=$_SESSION['regs'][marriage_day];?>" readonly="readonly" style="background: url('datepicker/calendar.png') no-repeat scroll right center rgb(255, 255, 255); padding-right: 20px;" class="datepicker"/>
                &nbsp;<a href="javascript:void(0)" onclick="document.getElementById('marriage_day').value='';">クリア </a>
            </td>
		</tr>
        
         <tr>
			<td width="200" bgColor="#FFF4FA" ><font color=red size="-1">※</font>挙式開始時間</td>
			<td  bgcolor="#FFF8FA">
            	<input type="text" name="marriage_day_with_time" id="marriage_day_with_time"  value="<?=$_SESSION['regs'][marriage_day_with_time];?>" readonly="readonly"  style="background: url('datepicker/calendar.png') no-repeat scroll right center rgb(255, 255, 255); padding-right: 20px;" class="datetimepicker" />&nbsp;
               &nbsp;<a href="javascript:void(0)" onclick="document.getElementById('marriage_day_with_time').value='';">クリア </a>
            </td>
		</tr>
        
        <tr>
            <td width="200" bgColor="#FFF4FA" ><font color=red size="-1">※</font> 挙式会場</td>
            <td  bgcolor="#FFF8FA">
                <select name="room_id" id="room_id">

                    <?php
                        if($rooms)
                        {
                            foreach($rooms as $room)
                            {
                               
								
								if($room['id']==$_SESSION['regs']['room_id'])
								echo "<option value ='".$room['id']."' selected> ".$room['name']." [".$room['max_seats']."]</option>";
								else
								 echo "<option value ='".$room['id']."'> ".$room['name']." [".$room['max_rows']*$room['max_columns']*$room['max_seats']."]</option>";
								
                            }
                        }
                    ?>
                </select>           
               </td>
       	</tr>

		<tr>
			<td width="200" bgColor="#FFF4FA" ><font color=red size="-1">※</font>挙式種類</td>
			<td  bgcolor="#FFF8FA">
              <select name="religion" id="religion">
              <option value=""  <?php if($_SESSION['regs'][religion]=='') {?> selected="selected" <?php } ?>>選択してください</option>
              <option value="キリスト教式" <?php if($_SESSION['regs'][religion]=='キリスト教式"') {?> selected="selected" <?php } ?>>キリスト教式</option>
              <option value="神前式" <?php if($_SESSION['regs'][religion]=='神前式') {?> selected="selected" <?php } ?>>神前式</option>
              <option value="人前式" <?php if($_SESSION['regs'][religion]=='人前式') {?> selected="selected" <?php } ?>>人前式</option>
              <option value="仏前式" <?php if($_SESSION['regs'][religion]=='仏前式') {?> selected="selected" <?php } ?>>仏前式</option>
              <option value="その他" <?php if($_SESSION['regs'][religion]=='その他') {?> selected="selected" <?php } ?>>その他</option>
            </select>            
            </td>
		</tr>

		<!-- party date start -->
        <tr>
			<td width="200" bgColor="#FFF4FA" ><font color=red size="-1">※</font>披露宴日</td>
			<td  bgcolor="#FFF8FA">
            	<input type="text" name="party_day" id="party_day"  value="<?=$_SESSION['regs'][party_day];?>"  readonly="readonly" style="background: url('datepicker/calendar.png') no-repeat scroll right center rgb(255, 255, 255); padding-right: 20px;" class="datepicker" />
                &nbsp;<a href="javascript:void(0)" onclick="document.getElementById('party_day').value='';">クリア </a>
            </td>
		</tr>


        <tr>
			<td width="200" bgColor="#FFF4FA" ><font color=red size="-1">※</font>披露宴開始時間</td>
			<td  bgcolor="#FFF8FA">
                <input type="text" name="party_day_with_time" id="party_day_with_time"  value="<?=$_SESSION['regs'][party_day_with_time];?>" readonly="readonly"   style="background: url('datepicker/calendar.png') no-repeat scroll right center rgb(255, 255, 255); padding-right: 20px;" class="datetimepicker" />
                &nbsp;
                <a href="javascript:void(0)" onclick="document.getElementById('party_day_with_time').value='';">クリア</a>
            </td>
		</tr>

        <tr>
           <td width="200" bgColor="#FFF4FA" ><font color=red size="-1">※</font>披露宴会場</td>
            <td  bgcolor="#FFF8FA">
                <select name="party_room_id" id="party_room_id">

                    <?php
                        if($rooms)
                        {
                            foreach($rooms as $room)
                            {
                               
								
								if($room['id']==$_SESSION['regs']['room_id'])
								echo "<option value ='".$room['id']."' selected> ".$room['name']." [".$room['max_seats']."]</option>";
								else
								 echo "<option value ='".$room['id']."'> ".$room['name']." [".$room['max_rows']*$room['max_columns']*$room['max_seats']."]</option>";
								
                            }
                        }
                    ?>
                </select>           
               </td>
       	</tr>


		<tr>
			<td width="200" bgColor="#FFF4FA" ><font color=red size="-1">※</font>状況</td>
			<td  bgcolor="#FFF8FA"><select name="status" id="status">
              <option value="" selected="selected">選択してください</option>
              <option <?php if($_SESSION['regs'][status]=="COND1") {?> selected="selected" <?php } ?> value="COND1">COND1</option>
              <option <?php if($_SESSION['regs'][status]=="COND2") {?> selected="selected" <?php } ?> value="COND2">COND2</option>
              <option <?php if($_SESSION['regs'][status]=="COND3") {?> selected="selected" <?php } ?> value="COND3">COND3</option>
              <option <?php if($_SESSION['regs'][status]=="COND4") {?> selected="selected" <?php } ?> value="COND4">COND4</option>
              <option <?php if($_SESSION['regs'][status]=="COND5") {?> selected="selected" <?php } ?> value="COND5">COND5</option>
            </select></td>
		</tr>


		<tr>
			<td align="left" bgColor="#FFF4FA" ><font color=red size="-1">※</font> 住所</td>
			<td align="left"  bgcolor="#FFF8FA">
				<table width="100%" border="0" cellspacing="1" cellpadding="1">
					<tr>
						<td width="90" nowrap="nowrap"><font size="-1"><font color=red size="-1">※</font> 郵便番号：</font></td>
						<td>
                       				 <input maxLength="3" size="4" name="zip1" id="zip1"  value="<?=$_SESSION['regs']['zip1'];?>">
									<input maxLength="4" size="4" name="zip2" id="zip2"  value="<?=$_SESSION['regs']['zip2'];?>">
									<span class="link1">&nbsp;<a href="javascript:void(0)" onclick="popupWindow('getzipcode.php')">住所検索</a></span>
															 
							<span class="style1">（半角）（例：123-4567）</span>						
                         </td>
					</tr>
                    <tr>
						<td nowrap="nowrap"><font size="-1"><font color=red size="-1">※</font> 都道府県：</font></td>
						<td>
                        <select name="state" id="state"> 
                                    <option value="" selected="selected">選択</option>
                                    <?php
                                        for ($i = 0; $i < count($z); $i++) {
                                    ?>
                                       <option <?php echo ($z[$i]==$_SESSION['regs']['state'])?'selected':'';?> value="<?=$z[$i]?>"><?=$z[$i]?></option>
                                    <?php		 
                                        }
                                    ?>
						</select>
											
                         </td>
					</tr>
                    <tr>
						<td nowrap="nowrap"><font size="-1"><font color=red size="-1">※</font> 市区町村：</font></td>
						<td>
							<input size=20 name="city" id="city" value="<?=stripslashes($_SESSION['regs']['city']);?>">（例：横浜市南区）					
                         </td>
					</tr>
                     <tr>
						<td nowrap="nowrap"><font size="-1"><font color=red size="-1">※</font> 番地：</font></td>
						<td>
							<input size=20 name="street" id="street" value="<?=stripslashes($_SESSION['regs']['street']);?>">					
                         </td>
					</tr>
                     <tr>
						<td nowrap="nowrap"><font size="-1">ビル、マンション名：</font></td>
						<td>
							<input size=20 name="buildings" id="buildings" value="<?=stripslashes($_SESSION['regs']['buildings'])?>" >					
                         </td>
					</tr>

					
				</table>
             </td>
		</tr>

<!-- tel start-->
        <tr>
            <td bgColor="#FFF4FA" align="left"><font color=red size="-1">※</font>電話番号</td>
            <td  bgcolor="#FFF8FA" align="left">
                <input size="20" name="tel"  id="tel" value="<?=($_SESSION['regs']['tel'])?>">
                <span class="style1">(半角)</span>	</td>
        </tr>

        <tr>
            <td bgColor="#FFF4FA" align="left">FAX番号</td>
            <td  bgcolor="#FFF8FA" align="left">
                <input size="20" name="fax"  id="fax" value="<?=($_SESSION['regs']['fax'])?>">
                <span class="style1">(半角)</span>						</td>
        </tr>

		<tr>
			<td bgColor="#FFF4FA" align="left"><font color=red size="-1">※</font> メールアドレス</td>
			<td  bgcolor="#FFF8FA" align="left">
				<input type="textbox" size="20" name="mail" id="mail"   value="<?=$_SESSION['regs']['mail']?>" onblur="checkDuplicateMail();" />		
                <!--onblur="checkDuplicateMail();"-->	
             </td>
		</tr>
        
        <tr>
			<td bgColor="#FFF4FA" align="left"><font color=red size="-1">※</font>メールアドレス(再確認用)</td>
			<td align="left"  bgcolor="#FFF8FA">
            	<input type="textbox" size="20"   id="conemail" value="<?=$_SESSION[regs]['mail']?>" />
            	<!--onblur="matchMail();"-->
            </td>
		</tr>
        
        <tr>
			<td bgColor="#FFF4FA" align="left"><font color=red size="-1">※</font>ログインID</td>
			<td align="left"  bgcolor="#FFF8FA"><input type="textbox" name="user_id" size=20  id="user_id" value="<?=$_SESSION[regs]['user_id']?>" onblur="checkUser();" >
            	<!--onblur="checkUser();"-->
            </td>
		</tr>
        
		<tr>
			<td bgColor="#FFF4FA" align="left"><font color=red size="-1">※</font> パスワード</td>
			<td  bgcolor="#FFF8FA" align="left">
				<input type="password" size="20" name="password" id="password" value="<?=$_SESSION['regs']['password']?>" >			</td>
		</tr>
		<tr>
		
			<td bgColor="#FFF4FA" align="left"> <font color=red size="-1">※</font> 担当名</td>
			<td align="left"  bgcolor="#FFF8FA"><input type="textbox" name="stuff_id" id="stuff_id" value="320" readonly="readonly"></td>
		</tr>

        
        
		<tr>

				<td align="center" colspan="2" bgColor="#FFF1FA">

				&nbsp;<input type="button" onclick="validForm()" value="送信" />
				&nbsp;<input type="button" value="クリア" onclick="reset_form('registerfrm');"  />
				&nbsp;<input type="button" value="戻る"  onclick="javascript:window.location='policy.php'" />
				<input type="hidden" name="sub" value="1" />			</td>
		</tr>
	</table>
</form>
<?php
include_once("inc/new_footer.php");
?>
