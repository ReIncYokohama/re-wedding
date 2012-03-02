<?php
	include_once("admin/inc/dbcon.inc.php");
	include_once("admin/inc/class.dbo.php");
	include_once("inc/checklogin.inc.php");
	$obj = new DBO();
	$get = $obj->protectXSS($_GET);
	$user_id = (int)$_SESSION['userid'];

$TITLE = "メッセージ送信 - ウエディングプラス";
include_once("inc/new.header.inc.php");


	$num=$obj->GetNumRows("spssp_message", " user_id=".(int)$user_id);
	$count_messege=$num+1;

	$table='spssp_message';
	$where = " user_id=".$user_id;
	$data_per_page=5;
	$current_page=(int)$_GET['page'];
	$redirect_url = 'user_messages.php?page='.$current_page;

	$pageination = $obj->pagination($table, $where, $data_per_page,$current_page,$redirect_url);

	if($_GET['action']=='delete' && (int)$_GET['id'] > 0)
	{
		$sql = "delete from spssp_message where id=".(int)$_GET['id'];
		mysql_query($sql);
		//redirect('user_messages.php?page='.$_GET['page']);
	}
	if($_POST{'insert'})
	{
		unset($_POST['file1']);
		$basepath='./upload/';
		@mkdir($basepath);

		$basepath='./upload/attach/';
		@mkdir($basepath);
		$basepath='./upload/attach/'.$_GET['id'].'/';
		@mkdir($basepath);

		if($_POST['title']&&$_POST['description'])
		{
			$filename= basename($_FILES["upfile"]["name"]);
			if(!empty($filename))
			{
				$ext = strtoupper(end(explode(".", $filename)));
				$size=$_FILES['upfile']['size'];
				if($ext=="exe" || $ext=="EXE")
				{
					?>
					<script type="text/javascript" language="javascript">
						alert("メッセージは送信できませんでした\nexeファイルは送信できません");
					</script>
					<?php
				}
				else if($size>8000000)
				{
					?>
					<script type="text/javascript" language="javascript">
						alert("メッセージは送信できませんでした\n添付の容量は8MBまでです");
					</script>
					<?php
				}


			}
			if($ext!="exe" && $ext!="EXE")
				{
					$post['user_id']=$user_id;
					$post['title']=$_POST['title'];
					$post['description']=nl2br($_POST['description']);
					$post['creation_date'] = date("Y-m-d H:i:s");
					$post['display_order']= time();
					$lastid = $obj->InsertData('spssp_message',$post);
					if($lastid)
					{
						if(!empty($filename))
						{
							$samplefile=$filename;

							$basepath='./upload/attach/'.$lastid."/";
							@mkdir($basepath);

							@move_uploaded_file($_FILES['upfile']["tmp_name"],$basepath.$samplefile);
						}

						if($samplefile)
						{
							$values['attach']='1';
							$values['attach_file']=$samplefile;
						}
						else
						$values['attach']=0;

						$whereCloser =' id='.$lastid;
						$obj->UpdateData('spssp_message',$values,$whereCloser);
						redirect("user_messages.php?page=".$current_page);
					}
					else
					{
						$err=1;
					}
			}
		}
		else
		{
			$err=2;
		}
	}
	if(isset($_GET['action']) && $_GET['action']=='edit_msg')
	{

		$post = $obj->protectXSS($_POST);
		$post['user_id']=(int)$user_id;
		$post['description'] = nl2br($post['description']);
		$where=" id=".$post['edit_msg_id'];
		unset($post['edit_msg_id']);

		$lastid = $obj->UpdateData('spssp_message',$post,$where);
		redirect("user_messages.php?page=".$current_page);
	}





?>
<script type="text/javascript">
function validForm()
{
	var title  = document.getElementById('title').value;
	var description  = document.getElementById('description').value;


	var flag = true;
	if(!title)
	{
		 alert("タイトルが未入力です");
		 document.getElementById('title').focus();
		 return false;
	}
	if(!description)
	{
		 alert("本文が未入力です");
		 document.getElementById('description').focus();
		 return false;
	}

	document.msg_form.submit();
}
function validForm_Edit()
{
	var title  = document.getElementById('edit_title').value;
	var description  = document.getElementById('edit_description').value;


	var flag = true;
	if(!title)
	{
		 alert("タイトルが未入力です");
		 document.getElementById('edit_title').focus();
		 return false;
	}
	if(!description)
	{
		 alert("本文が未入力です");
		 document.getElementById('edit_description').focus();
		 return false;
	}

	document.msg_form_edit.submit();
}
function view_adminitem(id,no){

		$.post("ajax/view_user_message.php",{'id':id},function(data){

			var substrs = data.split('#');
			//$("#v_no").html("No."+no);
			$("#v_title").html(substrs[2]);
			$("#v_desc").html(substrs[3]);

		});


}
function view_user_msg_count(id)
{
	$.post("ajax/user_message_count.php",{'id':id},function(data){
			//alert(data);
			$("#No").attr("value",data);
		});
	$("#insert_msg").toggle("slow");
	$("#update_msg").hide("slow");
}
function edit_admin_msg(msg_id)
{

	$.post("ajax/user_message_edit.php",{'msg_id':msg_id},function(data){

			var substrs = data.split('#');

			$("#edit_title").attr("value",substrs[0]);
			$("#edit_description").attr("value",substrs[1]);
			$("#edit_msg_id").val(msg_id);
		});

	var temp=$("#update_msg").css("display");
	if(temp!="block")
	{
		$("#update_msg").toggle("slow");
	}
	$("#insert_msg").hide("slow");

}
function cancel_insert()
{
	$("#title").attr("value","");
	$("#description").attr("value","");
	$("#upfile").attr("value","");
	$("#file1").attr("value","");
	//$("#insert_msg").hide("slow");
}
function cancel_update()
{
	$("#edit_title").attr("value","");
	$("#edit_description").attr("value","");
	$("#update_msg").hide("slow");
}
function confirmDelete(urls)
	{
		var agree = confirm("削除しても宜しいですか？");
		if(agree)
		{
			window.location = urls;
		}
	}

	$(function(){
		$("ul#menu li").removeClass();
		$("ul#menu li:eq(8)").addClass("active");
	});

 function button1_onclick() {
		document.msg_form.upfile.click();
	}
function filename_change() {
	document.msg_form.file1.value = document.msg_form.upfile.value;
}

</script>
<div id="main_contents" class="displayBox">
  <div class="title_bar">
    <div class="title_bar_txt_L">お客様とホテルとのメッセージの送受信を行います</div>
    <div class="title_bar_txt_R"></div>
<div class="clear"></div></div>
  <div class="cont_area">
    
        
	<div class="clear"></div>
	<br />
    <div><!--<a href="#" onclick="view_user_msg_count(<?=$user_id?>);"><img src="img/btn_message_user.jpg" width="112" height="22" /></a>--></div>

	<div id="insert_msg">
		<form action="user_messages.php?page=<?=(int)$_GET['page']?>" method="post" name="msg_form" enctype="multipart/form-data">
			<input type="hidden" name="insert" value="insert">
			<input type="hidden" name="admin_id" value="<?=$_SESSION['adminid']?>">
			<table align="left" border="0" >
				<tr>
				  <td width="40" align="left" valign="middle" nowrap="nowrap" style="text-align:left;">タイトル</td>
					<td width="5" valign="middle" nowrap="nowrap" style="text-align:center;">：</td>
				  <td colspan="2" valign="middle" style="text-align:left;" > <input type="text" name="title" id="title" style="border-style:inset;width: 775px;" /></td>
				</tr>
				<tr>
				  <td width="40" align="left" valign="middle" nowrap="nowrap" style="text-align:left;">本文</td>
					<td width="5" valign="middle" nowrap="nowrap" style="text-align:center;">：</td>
				  <td colspan="2" valign="middle" style="text-align:left;"><textarea name="description" id="description" cols="83" rows="5" style="border-style: inset;width: 775px;"></textarea></td>
				</tr>
				<tr>
				  <td width="40" align="left" valign="middle" nowrap="nowrap" style="text-align:left;">添付</td>
					<td width="5" valign="middle" nowrap="nowrap" style="text-align:center;">：</td>
					<td align="left" valign="middle" style="text-align:left;">
<!-- 					  <input name="file1" type="text" id="file1" size="40" style="border-style: inset;" readonly /> -->
<!--						<input id="upfile" type="file" name="upfile" onchange="filename_change();" style="display: none"> -->
						<input id="upfile" type="file" name="upfile">
					
<!--					<a href="javascript:void(0);" name="file2" onclick="button1_onclick();"><img src="img//btn_attach_user.jpg" alt="参照" width="82" height="22" /></a>	 -->
				  </td>
				</tr>
				<tr>
				  <td align="left">&nbsp;</td>
					<td>&nbsp;</td>
					<td align="left" style="text-align:left">
<a href="javascript:void(0);" name="insert" onclick="validForm();"/><img src="img/btn_send_user.jpg" alt="送信" width="82" height="22" /></a>&nbsp;&nbsp;&nbsp;
					<a href="javascript:void(0);" name="reset" onclick="cancel_insert();"/><img src="img/btn_clear_user.jpg" alt="クリア" width="82" height="22" /></a></td>

				</tr>
			</table>
		</form>
	</div>
	  <br />
      <div id="update_msg" style="display:none;">
		<h2> &nbsp;編集メッセージ</h2>
		<form action="user_messages.php?action=edit_msg&page=<?=(int)$_GET['page']?>" method="post" name="msg_form_edit">

            <input type="hidden" id="edit_msg_id" value="" name="edit_msg_id" />

			<table align="center" border="0" width="100%">
				<tr>
					<td style="text-align:right; width:50px;">タイトル</td>
					<td style="text-align:left;">
						<input type="text" name="title" id="edit_title" style="width:250px;"/>
					</td>
				</tr>
				<tr>
					<td width="5%" style="text-align:right;">本文</td>
					<td style="text-align:left;">
						<textarea name="description" id="edit_description" cols="70" rows="5"></textarea>
					</td>
				</tr>
		<tr>
					<td>&nbsp;</td>
					<td>
						<input type="button" name="Edit" value="更新" onclick="validForm_Edit();"/> &nbsp; <input type="reset" value="キャンセル" onclick="cancel_update();" />
					</td>
				</tr>
			</table>
		</form>
	</div>
	<?php if($err){$obj->GetErrorMsg($err);}?>
<?php if($_GET['msg']){$obj->GetSuccessMsg($_GET['msg']);}?>
	<!--<div class="page_next">< ?php echo $pageination;?></div>-->
<br /><br /><br /><br /><br /><br /><br /><br /><br />
	<div class="message_area">
    
    <div class="message_bt"><img src="img/soushin_img_on.jpg" width="71" height="22" /></div>
    <div class="message_bt"><a href="admin_messages.php?page=<?=(int)$_GET['page']?>"><img src="img/jushin_img.jpg" width="71" height="22" class="on" /></a></div>
    
      <table width="854" border="0" cellspacing="1" cellpadding="3" bgcolor="#CCCCCC">
        <tr>
<!--           <td width="28" align="center" nowrap="nowrap" bgcolor="#FFFFFF">No.</td> -->
          <td width="130" align="center" nowrap="nowrap" bgcolor="#FFFFFF">送信日時</td>
          <td width="200" align="center" nowrap="nowrap" bgcolor="#FFFFFF">タイトル</td>
		  <td width="28" align="center" nowrap="nowrap" bgcolor="#FFFFFF">添付</td>
          <td width="120" align="center" nowrap="nowrap" bgcolor="#FFFFFF">&nbsp;</td>
        </tr>
	<?php
		//$query_string="SELECT * FROM ".$table." where user_id=".$user_id." ORDER BY display_order DESC LIMIT ".((int)($current_page)*$data_per_page).",".((int)$data_per_page).";";
		$query_string="SELECT * FROM ".$table." where user_id=".$user_id." ORDER BY display_order DESC ;";
		$data_rows = $obj->getRowsByQuery($query_string);
		//echo "<pre>";
		//print_r($data_rows);exit;
		$i=0;$j=$current_page*$data_per_page+1;


		foreach($data_rows as $row)
		{
	?>


        <tr>
<!--           <td width="28" align="center" nowrap="nowrap" bgcolor="#FFFFFF"><?=$j?></td> -->
          <td width="130"  align="center" bgcolor="#FFFFFF"><?=$row['creation_date']?></td>
          <td width="200" align="left" bgcolor="#FFFFFF"><?=$row['title']?></td>
		  <td width="28" align="center" bgcolor="#FFFFFF">
		  	<?php  if($row['attach_file']) { ?>
			  <a href="download_attach.php?download_file=<?=$row['attach_file']?>&id=<?=$row['id']?>"><img src="img/btn_clip_attachment.gif" width="17" height="17" /></a>
			  <?php } else { ?>
			  <img src="admin/img/common/file_no.gif" width="6" height="5" />
			  <?php } ?>
		</td>
         <td width="120" bgcolor="#FFFFFF" align="center">
		  <input type="button" name="button1" id="button1" value="本文表示" onclick="view_adminitem(<?=$row['id']?>,<?=$j?>);"/>
		  <!--<input type="button" name="button2" id="button2" value="本文" onclick="edit_admin_msg(< ?=$row['id']?>);"/>-->
		  <!--<input type="button" name="button3" id="button3" value="削除" onclick="confirmDelete('user_messages.php?page=< ?=(int)$_GET['page']?>&action=delete&id=< ?=$row['id']?>');"/>-->
		  </td>
        </tr>


	    <?php $i++;$j++; }?>

      </table>
    </div>
    <div class="clear"></div></div>
  <div class="cont_area">
    <hr />
    <div class="clear"></div>
  </div>
  <div class="cont_area"><span id="v_no"></span>　<span id="v_title"></span><br />
    メッセージ本文：<br />
    <div class="message_area2">
      <p id="v_desc"></p>

    </div>

<div class="clear"></div>
  </div>
  </div>
<?php
include("inc/new.footer.inc.php");
?>
