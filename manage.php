<?php
	require_once("inc/class.dbo.php");
	include_once("inc/checklogin.inc.php");
	$obj = new DBO();
	$post = $obj->protectXSS($_POST);

	if(isset($_POST['ajax']) && $_POST['ajax'] != '' && $_POST['id'] != '')
	{
		$super_msg_row = $obj->GetSingleRow("super_admin_message", "id=".(int)$_POST['id']);
		$des = str_replace('<br />','',$super_msg_row['description']);
		echo $super_msg_row['title'].','.$des;
		exit;
	}

	include_once("inc/header.inc.php");
	if($_GET['delete_view'] && (int)$_GET['msg_id'] > 0)
	{
		$edit_arr['show_it'] = 0;
		$obj->UpdateData("super_admin_message", $edit_arr, " id = ".(int)$_GET['msg_id']);
	}
	if(isset($post['save_super']) && $post['save_super'] != ''  && $post['edit_sp'] == '')
	{
		if($post['title'] != '')
		{
			unset($post['save_super']);
			unset($post['edit_sp']);
			$post['display_order'] = time();
			$post['description'] = nl2br($post['description']);
			$lsid = $obj->InsertData("super_admin_message", $post);
		}

	}
	else if(isset($post['edit_sp']) && $post['edit_sp'] != '' )
	{

		$edit_arr['title'] = $post['title'];
		$edit_arr['description'] = nl2br($post['description']);

		$obj->UpdateData("super_admin_message", $edit_arr, " id = ".(int)$post['edit_sp']);

	}

	if(isset($_GET['action']) && $_GET['action'] == 'delete' && (int)$_GET['smsg_id'] > 0 )
	{
		$obj->DeleteRow("super_admin_message"," id =".(int)$_GET['smsg_id']);
	}
?>
<style>
.new_super_message
{
	width:300px;
	display:none;
}
.super_desc
{
padding:5px 5px 5px 147px;;
display:none;
color:#000000;
font-weight:normal;
}
</style>

  <div id="topnavi">
    <h1>サンプリンティングシステム 　管理    </h1>
    <div id="top_btn"> <a href="logout.php"><img src="img/common/btn_logout.jpg" alt="ログアウト" width="102" height="19" /></a></div>
  </div>
  <div id="container">
    <div id="contents">
      <h2>ホテルへのメッセージ編集</h2>
      <div class="txt2">
       <!-- <p><a href="javascript:void()" onclick="add_supper_message();">メッセージ作成 </a></p>-->
        <p>メッセージ作成 </p>
		<form action="manage.php" method="post" name="super_msg_frm">
		<!--<table class="new_super_message" cellpadding="5" cellspacing="1" border="0" align="left">-->
        <table cellpadding="5" cellspacing="1" border="0" align="left">
 <!--
			<tr>
				<?php
					$nums = $obj->GetNumRows("super_admin_message"," 1 = 1");
				?>
				<td width="10%" align="left">&nbsp;&nbsp;No.</td>
				<td>&nbsp;<input type="text" value="<?=$nums?>" readonly="readonly" size="1" style="border: #ffffff;" /></td>
			</tr>
 -->
			<tr>
				<td width="10%" align="left">&nbsp;&nbsp;タイトル </td><td> <input type="text" name="title" id="super_title" style="width: 732px;"/></td>
			</tr>
			<tr>
				<td align="left">&nbsp;&nbsp;本文 </td>
				<td> <textarea name="description" id="super_description" cols="40" style="width: 732px;"  /></textarea><br /><br /></td>
			</tr>
			<tr>
				<td>&nbsp; </td>
				<td>
					<img alt="登録" src="img/common/btn_send_admin.jpg" onclick="save_super_message();">
					<!--<input type="button" value="送信" onclick="save_super_message();" /> -->&nbsp;
                   <img border="0" src="img/common/btn_clear_admin.jpg" alt="ｸﾘｱ" onclick="clearSubmit();">
				   <!-- <input type="reset" value="キャンセル" />-->
					<!--<input type="button" value="キャンセル" onclick="cancel_super_message();" />-->
					<input type="hidden" name="save_super" value="save_super" id="save_super" />
					<input type="hidden" id="edit_sp" value="" name="edit_sp" />
				<br />
				<br />
				</td>
			</tr>
		</table>
		</form>
        <p></p>
        <ul class="ul3" id="message_BOX" style="overflow:auto;">
		<?php
			//<a href='message_admin.php?id=".$msg['id']."'> ".$msg['title']."</a>
			$super_messeges = $obj->GetAllRowsByCondition(" super_admin_message "," 1 = 1 order by id desc");

			if(is_array($super_messeges)) {
				foreach($super_messeges as $msg)
				{
					echo "<li>";
					echo '<table cellpadding="5" cellspacing="1" border="0" align="left">';
					echo "<tr>";

					echo "<td width='15%'><span class='date2'>".date('Y/m/d',$msg['display_order'])."</span> &nbsp; &nbsp; &nbsp; &nbsp</td>
					   <td width='35%'><a href='javascript:void(0);' onclick='view_dsc_super(".$msg['id'].")' id='super_title_".$msg['id']."'> ".$msg['title']."</a> &nbsp; &nbsp; &nbsp; &nbsp;</td>";

					if($msg['show_it']) {
					echo "<td width='20%'><a href='manage.php?delete_view=1&msg_id=".$msg['id']."'><img src='img/common/hotel_delete.png' alt='ホテル画面から削除' /></a></td>";
					} else {
					echo "<td width='20%'><img src='img/common/hotel_delete_economics.png' alt='ホテル画面から削除済み' /></td>";
					}
					echo "</tr>";
					echo "</table>";

					echo "
					<p class='super_desc' id='super_desc_".$msg['id']."'><span>".$msg['description']."</span> &nbsp; &nbsp; &nbsp;
					<a href='javascript:void();' onclick='edit_super_msg(".$msg['id'].")'> 編集 </a> &nbsp; &nbsp; &nbsp;
					<a href='javascript:void();' onclick=\"confirmDelete('manage.php?action=delete&smsg_id=".$msg['id']."')\"> 削除 </a></p>";

					echo "</li>";
				}
			}
		?>
		</ul>
      </div>
    </div>
  </div>
<?php
  	include_once("inc/sidebar.inc.php");
	include_once("inc/footer.inc.php");
?>
<script type="text/javascript">
/*function add_supper_message()
{
	$j(".new_super_message").toggle("slow");
	$j("#edit_sp").val('');
}*/
function save_super_message()
{
	var title = $j("#super_title").val()
	var desc = $j("#super_description").val();
	if(title == '')
	{
		alert("タイトルが未入力です");
		$j("#super_title").focus();
		return false;
	}
	if(desc == '')
	{
		alert("本文が未入力です");
		$j("#super_description").focus();
		return false;
	}

	document.super_msg_frm.submit();
}
/*function cancel_super_message()
{
	$j("#edit_sp").val('');
	$j(".new_super_message").fadeOut(500);
}
*/
function view_dsc_super(id)
{
	$j("#super_desc_"+id).toggle();

}
function edit_super_msg(id)
{

	var mid = id;
	$j.post('manage.php',{'ajax':'ajax','id':mid}, function(data){

		$j("#edit_sp").val(id);
		$j("#save_super").val('');
		var arr = data.split(",");
		$j("#super_title").val(arr[0]);
		$j("#super_description").val(arr[1]);
		$j("#super_desc_"+id).fadeOut(100);
		$j(".new_super_message").fadeIn(500);
	});


}

function viewMsg(id)
{
	$j.post('ajax/view_user_message.php', {'id':id},function(data) {

	});

	user_a_id= $j("#desc_"+id+" input").val();

	$j("#desc_"+id).dialog("open");

}


function clearSubmit()
{
   document.getElementById('super_title').value='';
   document.getElementById('super_description').value='';
}

</script>