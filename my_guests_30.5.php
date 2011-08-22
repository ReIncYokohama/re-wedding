<?php
	include_once("admin/inc/dbcon.inc.php");
	include_once("admin/inc/class.dbo.php");
	include_once("inc/checklogin.inc.php");
	$obj = new DBO();
	$get = $obj->protectXSS($_GET);
	$user_id = (int)$_SESSION['userid'];
	include_once("inc/new.header.inc.php");
	
	if(isset($_GET['action']) && $_GET['action'] == 'delete' )
	{
		$id = (int)$_GET['guest_id'];

		$obj->DeleteRow('spssp_guest', 'id='.$id);
	}
	
	$respects = $obj->GetAllRow("spssp_respect");
	
	$guest_types = $obj->GetAllRow("spssp_guest_type");	
	
	$table='spssp_guest';
	$where = " user_id =".(int)$_SESSION['userid'];
	$data_per_page=2;
	$current_page=(int)$_GET['page'];
	$redirect_url = "my_guests.php";
	
	//$order = ' last_name asc ';
	$order = ' id asc ';
		
	if(isset($_GET['option']) && $_GET['option'] != '')
	{

			if($_GET['option'] == 'sex')
			{
				$order = ' sex desc';
			}
			else if($_GET['option'] == 'name')
			{
				$order = 'last_name DESC';
			}
			else if($_GET['option'] == 'guest_type')
			{
				$order = ' guest_type desc';
			}

	}
	
	
	$sql = "select count(*) AS total_record from $table where $where";
	$db_result=mysql_query($sql);
	if($db_row=mysql_fetch_array($db_result))
	{
		$total_record=$db_row['total_record'];
	}
	$total_record  = (int)$total_record;
	$total_page=ceil($total_record/$data_per_page);
	$last_page = $total_page-1;
	//echo $total_page;
	$current_page=(int)$current_page;
	
	if($current_page>$total_page) $current_page=$total_page;
	
	//if($current_page<=0) $current_page=1;
	
	if($current_page+1 >=$total_page)
		$next=$current_page;
	else
		$next=$current_page+1;
	
	if($current_page<=0)
		$prev=0;
	else
		$prev=$current_page-1;
	if(strpos($redirect_url,'?') !== false)
	{
		$url_sign = '&';
	}
	else
	{
		$url_sign = '?';
	}	
	$pagination = '';
	if($total_page >= 1)
	{

			$pagination.= '<td width="29" rowspan="3" align="center">最初<br/> <a href="'.$redirect_url.$url_sign.'page=0"><img src="img/arrow_first.jpg" width="19" height="14" border="0" /></a></td>  <td width="29" rowspan="3" align="center">前へ<br/> <a href="'.$redirect_url.$url_sign.'page='.$prev.'"><img src="img/arrow_prev.jpg" width="10" height="14" border="0" /></a></td>';

			$pagination .= '<td width="29" rowspan="3" align="center">次へ<br/><a href="'.$redirect_url.$url_sign.'page='.$next.'"><img src="img/arrow_next.jpg" width="10" height="14" border="0" /></a> </td> <td width="50" rowspan="3" align="center">最後<br/><a href="'.$redirect_url.$url_sign.'page='.$last_page.'"><img src="img/arrow_last.jpg" width="19" height="14" border="0" /></a></td>';

	}
	
	//$query_string="SELECT * FROM $table where $where  ORDER BY $order  LIMIT ".((int)($current_page)*$data_per_page).",".((int)$data_per_page).";";
	$query_string="SELECT * FROM $table where $where  ORDER BY $order";
		
	$guests = $obj->getRowsByQuery($query_string);

	

?>

<style>

</style>
<script type="text/javascript">
	function gsearch()
	{
		var sex = $("#sex").attr('checked');
		var name =$("#last_name").attr("checked");
		var guset_type = $("#guset_type").attr("checked");
		var url = "my_guests.php?page=<?=(int)$_GET['page']?>";
		if(sex)
		{
			url += '&option=sex';
		}
		else if(name)
		{
			url += '&option=name';
		}
		else if(guset_type)
		{
			url += '&option=guest_type';
		}
		else
		{
			alert("オプションを選択する");
			return false;
		}
		window.location = url;
	}
	
	$(function(){
		$("ul#menu li").removeClass();
		$("ul#menu li:eq(3)").addClass("active");
		
		var msg_html=$("#msg_rpt").html();
	
		if(msg_html!='')
		{
			$("#msg_rpt").fadeOut(5000);
		}
	});
	
	function edit_guest(gid)
	{
		window.location = "my_guests.php?gid="+gid;
	}
	
	function toggle_new()
	{
		$("#new_guest").toggle(500);
	}
	function cancel_div(did)
	{
		$("#"+did).hide(500);
	}
	function confirmDelete(urls)
	{
		var agree = confirm("削除しても宜しいですか？");
		if(agree)
		{
			window.location = urls;
		}
	}

	function validForm()
	{
		var last_name = $("#last_name").val();
		var first_name = $("#first_name").val();
		var respect_id = $("#respect_id").val();
		var guest_type = $("#guest_type").val();
		var comment1 = $("#comment1").val();
		var comment2 = $("#comment2").val();
		var memo = $("#memo").val();
		
		//var sub_category_id = $("#sub_category_id").val();
		

		if(last_name == '')
		{
			alert("姓を入力してください");
			$("#last_name").focus();
			return false;
		}
		if(first_name == '')
		{
			alert("名を入力してください");
			$("#first_name").focus();
			return false;
		}
		var url = 'ajax/newguest.php?last_name='+last_name+'&first_name='+first_name+'&respect_id='+respect_id+'&guest_type='+guest_type+'&comment1='+comment1+'&comment2='+comment2+'&memo='+memo;
		//alert(url);
		document.newguest.submit();
		/*$.get(url, function(data) {window.location='my_guests.php'});
		
	  $("#first_name").val('');
	  $("#last_name").val('');
	  $("#respect_id").val('');
	  $("#guest_type").val('');
	  $("#comment1").val('');
	  $("#comment2").val('');
	  $("#id").val('');
	  $("#memo").val('');
	  $("#newgustform").animate({height: 'hide', opacity: 'hide'}, 'slow');    
	  $("#newgustform").css("display","none");*/
	
	}
	
	function cancelldiv()
	{
	  
	  $("#newgustform").animate({height: 'hide', opacity: 'hide'}, 'slow'); 
	  $("#newgustform").css("display","none");   	  
	}
	function newguestShow()
	{
	  $("#first_name").val('');
	  $("#last_name").val('');
	  $("#respect_id").val('');
	  $("#guest_type").val('');
	  $("#comment1").val('');
	  $("#comment2").val('');
	  $("#id").val('');
	  $("#memo").val('');
	  $("#newgustform").animate({height: 'show', opacity: 'show'}, 'slow');    
	  $("#newgustform").css("display","block");
	}
</script>
<div id="step_bt_area">
  <div class="step_bt"><a href="table_layout.php"><img src="img/step_head_bt01.jpg" width="155" height="60" border="0" class="on" /></a></div>
  <div class="step_flow_img"><img src="img/step_flow.gif" width="25" height="60" /></div>
  <div class="step_bt"><a href="hikidemono.php"><img src="img/step_head_bt02.jpg" width="155" height="60" border="0" class="on" /></a></div>
  <div class="step_flow_img"><img src="img/step_flow.gif" width="25" height="60" /></div>
  <div class="step_bt"><img src="img/step_head_bt03_on.jpg" width="155" height="60" border="0"/></div>
  <div class="step_flow_img"><img src="img/step_flow.gif" width="25" height="60" /></div>
  <div class="step_bt"><a href="make_plan.php"><img src="img/step_head_bt04.jpg" width="155" height="60" border="0" class="on" /></a></div>
  <div class="step_flow_img"><img src="img/step_flow.gif" width="25" height="60" /></div>
  <div class="step_bt"><a href="order.php"><img src="img/step_head_bt05.jpg" width="155" height="60" border="0" class="on" /></a></div>
  <div class="clear"></div></div>

<div id="main_contents">
  <div class="title_bar">
    <div class="title_bar_txt_L">招待者リストの作成を行います。</div>
    <div class="title_bar_txt_R"></div>
<div class="clear"></div></div>
	<?php
		if(isset($get['gid']) && (int)$get['gid'] > 0)
		{
			$guest_row = $obj->GetSingleRow(" spssp_guest ", " id=".(int)$get['gid']);
			
		}
		$respects = $obj->GetAllRow("spssp_respect");
	    $guest_types = $obj->GetAllRow("spssp_guest_type");
	?>
	<div id="newgustform" style="width:873px; margin:auto; min-height:100px; padding-top:5px; display:block">
	 <form id="newguest" name="newguest" method="post" action="new_guest.php?page="<?=$_GET['page']?>">
	 <input type="hidden" name="id" id="id" value="<?=$_GET['gid']?>" />
	   <table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td width="100" valign="middle" height="30" style="width:70px; text-align:right">新婦側</td>
			<td valign="middle" height="30" align="right">
				<select id="sex" name="sex" style="width:150px;">
					<option value="Male" <?php if($guest_row['sex']=="Male"){ echo "Seleceted='Selected'"; }?> >新郎側</option>
					<option value="Female" <?php if($guest_row['sex']=="Female"){ echo "Seleceted='Selected'"; }?> >新婦側</option>
				</select>
			</td>
			<td valign="middle" height="30" style="width:70px;" align="right" >名:</td>
			<td valign="middle" height="30" align="right"><input type="text" style="width:150px;" name="first_name" id="first_name" value="<?=$guest_row['first_name']?>" /></td>
			<td valign="middle" height="30" style="width:70px;" align="right">姓:</td>
			<td valign="middle" height="30" ><input type="text" style="width:150px;" name="last_name" id="last_name" value="<?=$guest_row['last_name']?>" /></td>
			<td valign="middle" height="30" >敬称:</td>
			 <td valign="middle" height="30" >
				<select id="respect_id" name="respect_id" style="width:150px;" >
				<option value="">なし</option>
				<?php
					foreach($respects as $respect)
					{
						if($guest_row['respect_id'] == $respect['id'])
						{
							$sel = "Selected='Selected'";
						}
						else
						{
							$sel = " ";
						}
						
						echo "<option value='".$respect['id']."'  $sel >".$respect['title']."</option>";
					}
				?>
				</select>
			</td>
		  </tr>
		
		  <tr>
			
			<td valign="middle" height="30" align="right">区分:</td>
			 <td valign="middle" height="30" >
				<select id="guest_type" name="guest_type" style="width:150px;">
					<?php
						foreach($guest_types as $guest_type)
						{
							if($guest_row['guest_type'] == $guest_type['id'])
							{
								$sel = "Selected='Selected'";
							}
							else
							{
								$sel = " ";
							}
							echo "<option value='".$guest_type['id']."' $sel>".$guest_type['name']."</option>";
						}
					?>
				</select>
			</td>
			<td valign="middle" height="30" align="right">肩書 1:</td>
			 <td valign="middle" height="30" ><input style="width:150px;" type="text" name="comment1" id="comment1" maxlength="40" value="<?=$guest_row['comment1']?>" /></td>
			<td valign="middle" height="30" align="right">肩書 2:</td>
			 <td valign="middle" height="30" ><input style="width:150px;" type="text" name="comment2" id="comment2" maxlength="40" value="<?=$guest_row['comment2']?>" /></td>
			 <td valign="middle" height="30" align="right">特記:</td>
			 <td valign="middle" height="30" ><input style="width:150px;" type="text" name="memo" id="memo" maxlength="40" value="<?=$guest_row['memo']?>" /></td>
		  </tr>
		<tr>
		<td valign="middle" height="30" align="right"> 引出物 </td>
                        <td> 
                        	<?php
                            	$gift_groups = $obj->GetAllRowsByCondition(" spssp_gift_group "," user_id=".$user_id);
								if((int)$_GET['gid'])								
								 $guest_gifts = $obj->GetAllRowsByCondition(" spssp_guest_gift "," user_id=".$user_id." and guest_id='".$_GET['gid']."'");
								
								$gg_arr = array();
							    if(is_array($guest_gifts))
								{
									foreach($guest_gifts as $gg)
									{
										$gg_arr[] = $gg['group_id'];
									}
									
								}
								
								
								echo "<select id='gift_group'  name='gift_group' style='width:150px;'>";
								echo "<option value='' >なし</option>";
								foreach($gift_groups as $gg)
								{
									$selected = (in_array($gg['id'],$gg_arr))?"selected":"";
									echo "<option ".$selected." value='".$gg['id']."' $sel >".$gg['name']."</option>";
								}
								echo "</select>";
							?>
                        </td>
                        
                        <td align="right"> 料理 </td>
                        <td>
                        	<?php
                            	$menus = $obj->GetAllRowsByCondition(" spssp_menu_group "," user_id=".$user_id);
								if((int)$_GET['gid'])
								$guest_menus = $obj->GetAllRowsByCondition(" spssp_guest_menu "," user_id=".$user_id." and guest_id=".$_GET['gid']);
							
								$gm_arr = array();
								if(is_array($guest_menus))
								{
									foreach($guest_menus as $gm)
									{
										$gm_arr[] = $gm['menu_id'];
									}
								}
								echo "<select id='menu_grp' name='menu_grp' style='width:150px;'>";
								echo "<option value='' >なし</option>";
							    
								foreach($menus as $m)
								{	
									$selected = (in_array($m['id'],$gm_arr))?"selected":"";
									echo "<option ".$selected." value='".$m['id']."' >".$m['name']."</option>";
								}
								echo "</select>";
								
							?>
                        </td>
		</tr>
		  <tr>
			<td valign="middle" height="30" >&nbsp;</td>
			<td valign="middle" height="30" ><input type="button" value="保存" onclick="validForm()" /><!--<input type="button" value="戻る" onclick="cancelldiv()" />--></td>
		  </tr>
		</table>

	 </form>
	</div>
  <form id="form1" name="form1" method="post" action="">

    <div class="cont_area">
	<?php
        	$user_row = $obj->GetSingleRow("spssp_user", " id=".(int)$_SESSION['userid']);
	?>
  	<font size="4">新郎 : <?=$user_row['man_firstname']?> <?=$user_row['man_lastname']?> 様 
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;新婦 : <?=$user_row['woman_firstname']?> <?=$user_row['woman_lastname']?> 様 </font>
<br>

	<div class="guests_area_L">
    
    ■ 招待者名を入力のうえ、各項目の情報を入力してください。

	<table width="863" border="0" cellpadding="3" cellspacing="1" bgcolor="#999999" style="padding-top:1px">
    	<tr>
            <td width="71" align="center" bgcolor="#FFFFFF">新郎新婦側</td>
            <td width="60" align="center" bgcolor="#FFFFFF">姓</td>
            <td width="60" align="center" bgcolor="#FFFFFF">名</td>
            
            <td width="36" align="center" bgcolor="#FFFFFF">敬称</td>
            <td width="65" align="center" bgcolor="#FFFFFF">区分</td>
            <td width="126" align="center" bgcolor="#FFFFFF">肩書</td>
            <td width="43" align="center" bgcolor="#FFFFFF">卓名</td>
            <td width="48" align="center" bgcolor="#FFFFFF">引出物</td>
            <td width="96" align="center" bgcolor="#FFFFFF">料理</td>
            <td width="69" align="center" bgcolor="#FFFFFF">特記</td>
         
			<td width="111" align="center" bgcolor="#FFFFFF">&nbsp;</td>
        </tr>
      <?php
      	foreach($guests as $guest)
		{
		  // echo'<pre>';
		  // print_r($guest);
			$respect = $obj->GetSingleData(" spssp_respect ", "title", " id=".$guest['respect_id']);
			if($respect == '')
			{
				$respect ='×';
			}
			
			$guest_type = $obj->GetSingleData(" spssp_guest_type ", "name", " id=".$guest['guest_type']);
			
			$gift_id = $obj->GetSingleData(" spssp_guest_gift ", "group_id", " guest_id=".$guest['id']." and user_id = ".$user_id);
			$gift_name='';
			if((int)$gift_id > 0)
			{
				$gift_name = $obj->GetSingleData(" spssp_gift_group ", "name", " id=".$gift_id." and  user_id = ".$user_id);
			}
			
			$menu_id = $obj->GetSingleData(" spssp_guest_menu ", "menu_id", " guest_id=".$guest['id']." and user_id = ".$user_id);
			$menu_name='';
			if($menu_id > 0)
			{
				$menu_name = $obj->GetSingleData(" spssp_menu_group ", "name", " id=".$menu_id." and user_id = ".$user_id);
			}
			

	  ?>
		<tr>
      		<td bgcolor="#FFFFFF">
            	<input  type="radio" value="" disabled="disabled" <?php if($guest['sex'] == 'Male'){ echo "checked='checked'";} ?> />    新郎<br />
          		<input  type="radio" value="" disabled="disabled" <?php if($guest['sex'] == 'Female'){ echo "checked='checked'";} ?> />    新婦
            </td>
            <td align="left" valign="middle" bgcolor="#FFFFFF"><?=$guest['first_name']?></td>
            <td align="left" valign="middle" bgcolor="#FFFFFF"><?=$guest['last_name']?></td>       	
        	<td align="center" valign="middle" bgcolor="#FFFFFF"> <?=$respect?> </td>
        	<td align="center" valign="middle" bgcolor="#FFFFFF"> <?=$guest_type?> </td>
        	<td align="left" valign="middle" bgcolor="#FFFFFF"> <?=$guest['comment1']?> &nbsp; <?=$guest['comment2']?> </td>
			<?php
				$plan_details=$obj->getSingleRow("spssp_plan_details"," guest_id=".$guest['id']." limit 1");
				$seat_details=$obj->getSingleRow("spssp_default_plan_seat"," id=".$plan_details['seat_id']." limit 1");
				$table_details=$obj->getSingleRow("spssp_default_plan_table"," id=".$seat_details['table_id']." limit 1");
				
				$tbl_row = $obj->getSingleRow("spssp_table_layout"," table_id=".$table_details['id']." and user_id=".(int)$user_id." limit 1");
				$new_name_row = $obj->getSingleRow("spssp_user_table"," default_table_id=".$tbl_row['id']." and user_id=".(int)$user_id." limit 1");
				if(!empty($new_name_row))
				{
					$tblname = $obj->getSingleData("spssp_tables_name","name","id=".$new_name_row['table_name_id']);
				}
				else
				{
					$tblname = $tbl_row['name'];
				}
				?>
        	<td align="center" valign="middle" bgcolor="#FFFFFF"><?=$tblname?></td>
        	<td align="center" valign="middle" bgcolor="#FFFFFF"> <?=$gift_name?> </td>
        	<td align="center" valign="middle" bgcolor="#FFFFFF"> <?=$menu_name?>  </td>
		
        	<td align="left" valign="middle" bgcolor="#FFFFFF"><?=$guest['memo']?> </td>
        	<td valign="middle" bgcolor="#FFFFFF">
            	<input type="button" name="button" id="button" value="編集" onclick="edit_guest(<?=$guest['id']?>)" /> &nbsp;
                <input name="button" type="button" value="削除" onclick="confirmDelete('my_guests.php?guest_id=<?=$guest['id']?>&action=delete&page=<?=(int)$_GET['page']?>')" />
            </td>
        </tr>
        <?php
        }
		?>
    </table>
    </div>
    
</div>
    <div class="cont_area">
      <div class="line01"></div>
      <table width="860" border="0" cellspacing="1" cellpadding="3">
        <tr>
          <td width="45" align="center">新郎側</td>
          <td width="34">
          	<?php
            	$male_guest_num = $obj->GetNumRows("spssp_guest","user_id=".(int)$_SESSION['userid']." and sex='Male'");
				echo $male_guest_num;
			?>
          </td>
          <td width="96" rowspan="3" align="center"><a href="#">使い方</a></td>
          <td width="102" rowspan="3" align="center"><a href="#" onclick="newguestShow();">新規登録</a></td>
          <td width="87" rowspan="3" align="center"><a href="#">外字検索</a></td>
          <td width="84" rowspan="3"><input type="radio" name="search_guest" id="sex" />新郎新婦側<br />
            <input type="radio" name="search_guest" id="last_name" />姓<br />
            <input type="radio" name="search_guest" id="guset_type" />区分</td>
          <td width="114" rowspan="3" valign="middle"><input type="button" onclick="gsearch();" value="ソート" /></td>
          <td width="76" rowspan="3" align="center"><a href="#">リスト印刷</a></td>

          <?php
		
          	//echo $pagination;
		  
		  ?>
        </tr>
        <tr>
          <td align="center">新婦側</td>
          <td>
          	<?php
            	$female_guest_num = $obj->GetNumRows("spssp_guest","user_id=".(int)$_SESSION['userid']." and sex='Female'");
				echo $female_guest_num;
			?>
          </td>
          </tr>
        <tr>
          <td align="center">計</td>
          <td><?=$total_record?></td>
          </tr>
      </table>
      <div class="line01"></div>
    </div>
    <div class="cont_area">
      <div class="guests_area_L">■ 引出物 商品数
        <table width="500" border="0" cellspacing="1" cellpadding="3" bgcolor="#999999">
          <tr>
            <td width="114" align="center" bgcolor="#FFFFFF">商品名</td>
            <?php
            	$group_rows = $obj->GetAllRowsByCondition("spssp_gift_group"," user_id=".$user_id);
				$gift_rows = $obj->GetAllRowsByCondition("spssp_gift"," user_id=".$user_id);
				foreach($group_rows as $grp)
				{
				
			?>
            <td width="42" align="center" bgcolor="#FFFFFF"><?=$grp['name']?></td>
            <?php
            	}
			?>
            
            <td width="42" align="center" bgcolor="#FFFFFF">×</td>
            <td width="60" align="center" bgcolor="#FFFFFF">予備</td>
            <td width="59" align="center" bgcolor="#FFFFFF">合計</td>
          </tr>
          <?php
		  	foreach($gift_rows as $gift)
			{
		  ?>
          <tr>
            <td bgcolor="#FFFFFF" width="116"><?=$gift['name']?></td>
            <?php
				
				$num_gifts = 0;
            	foreach($group_rows as $grp)
				{
					$gift_ids = $obj->GetSingleData("spssp_gift_group_relation","gift_id", "user_id= $user_id and group_id = ".$grp['id']);	
					$gift_arr = explode("|",$gift_ids);
					$groups = array();
					if(in_array($gift['id'],$gift_arr))
					{
						$htm = "<img src='./admin/img/tick.jpg' style='border:0; width:40px; height:35px' />";
						array_push($groups,$grp['id']);
					}
					else
					{
						$htm = '&nbsp;';
						
					}
					
					if(!empty($groups))
					{
						foreach($groups as $grp)
						{
							$num_guests_groups = $obj->GetNumRows(" spssp_guest_gift "," user_id = $user_id and group_id = ".$grp);
							$num_gifts += $num_guests_groups;
						}
						unset($groups);
					}
			?>
            <td width="42" align="center" bgcolor="#FFFFFF"> <?=$htm?> </td>
            <?php
            	}
			?>
           	<td width="42" align="center" bgcolor="#FFFFFF">&nbsp;</td>
            <td width="60" align="center" bgcolor="#FFFFFF">&nbsp;</td>
            <td width="59" align="center" bgcolor="#FFFFFF"><?=$num_gifts?></td>
          </tr>
          <?php
          	}
		  ?>
          <tr>
            <td align="center" bgcolor="#FFFFFF">グループ数</td>
            <?php
				$total = 0;
            	foreach($group_rows as $grp)
				{
					$num_guests_groups = $obj->GetNumRows(" spssp_guest_gift "," user_id = $user_id and group_id = ".$grp['id']);
					$total += $num_guests_groups;
					echo "<td bgcolor='#FFFFFF'> $num_guests_groups </td>";
				}
			?>
            <td width="42" align="center" bgcolor="#FFFFFF">
				<?php
                	$num_guests = $obj->GetNumRows(" spssp_guest "," user_id = $user_id ");
					$not_gifted = $num_guests - $total;
					echo $not_gifted;
					 
				?>
             </td>
            <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
            <td bgcolor="#FFFFFF"><?=$num_guests?></td>
          </tr>
        </table>
      </div>
      <div class="guests_area_R">■ 引出物　グループ内容
        <table width="243" border="0" cellspacing="1" cellpadding="3" bgcolor="#999999">
        <?php
            	foreach($group_rows as $grp)
				{
					$gift_ids = $obj->GetSingleData("spssp_gift_group_relation","gift_id", "user_id= $user_id and group_id = ".$grp['id']);
					$gift_arr = explode("|",$gift_ids);
					$gift_ids = implode(',',$gift_arr);
					$item_names = $obj->GetSingleData("spssp_gift" , "group_concat(name separator ' ・ ') as names" , " id in ( $gift_ids )");
					
					echo "<tr><td bgcolor='#FFFFFF' width='30' align='center'>".$grp['name']."</td><td align='letf' width='200' bgcolor='#FFFFFF'>".$item_names."</td></tr>";
				}
			?>
          
          <tr>
            <td bgcolor="#FFFFFF">　×</td>
            <td bgcolor="#FFFFFF">&nbsp;</td>
          </tr>
        </table>
      </div>
<div class="clear"></div>
    </div>
<div class="cont_area">

■ 料理数
  <table width="398" border="0" cellspacing="1" cellpadding="3" bgcolor="#999999" style="padding-top:1px">
  <?php
  	$menu_groups = $obj->GetAllRowsByCondition("spssp_menu_group","user_id=".(int)$user_id);
	$num_groups = count($menu_groups);
	foreach($menu_groups as $mg)
	{
		$num_menu_guest = $obj->GetNumRows("spssp_guest_menu","user_id=$user_id and menu_id=".$mg['id']);	
		
  ?>
    <tr>
      <td width="124" align="center" bgcolor="#FFFFFF"><?=$mg['name']?></td>
      
      <td width="124" align="center" bgcolor="#FFFFFF"><?=$num_menu_guest?></td>
    </tr>
   <?php
   	}
   ?>
<!--    <tr>
      <td bgcolor="#FFFFFF">子供1</td>
      <td align="center" bgcolor="#FFFFFF">5,000円</td>
      <td align="center" bgcolor="#FFFFFF">3</td>
    </tr>
    <tr>
      <td bgcolor="#FFFFFF">子供2</td>
      <td align="center" bgcolor="#FFFFFF">3,000円</td>
      <td align="center" bgcolor="#FFFFFF">2</td>
    </tr>
    <tr>
      <td bgcolor="#FFFFFF">料理なし</td>
      <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
      <td align="center" bgcolor="#FFFFFF">1</td>
    </tr>
    <tr>
      <td align="center" bgcolor="#FFFFFF">計</td>
      <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
      <td align="center" bgcolor="#FFFFFF">71</td>
    </tr>-->
  </table>

</div>
  </form> 
 <?php
include("inc/new.footer.inc.php");
?>

<?php
        	if(isset($_GET['err']) && $_GET['err']!='')
			{
				if($_GET['err']==1)
				{
					echo '<script type="text/javascript"> alert("データベースエラー"); </script>';
				}

			}
			else if(isset($_GET['msg']) && $_GET['msg']!='')
			{
				if($_GET['msg']==1)
				{
					echo '<script type="text/javascript"> alert("保存されました"); </script>';
				}
				if($_GET['msg']==2)
				{
					echo '<script type="text/javascript"> alert("変更されました"); </script>';
				}
			}
		?>
