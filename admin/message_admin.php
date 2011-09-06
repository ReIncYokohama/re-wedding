<?php
	require_once("inc/class.dbo.php");
	require_once("inc/include_class_files.php");
	include_once("inc/checklogin.inc.php");

	include_once("inc/new.header.inc.php");
	$obj = new DBO();
	$objInfo = new InformationClass();
	$user_id = $_GET['user_id'];
	$stuff_id = $_GET['stuff_id'];
	$user_row = $obj->GetSingleRow("spssp_user"," id= $user_id");
	$table='spssp_admin_messages';
	$where = " user_id='".$user_id."'";
	$data_per_page=5;
	$current_page=(int)$_GET['page'];
	$redirect_url = 'message_admin.php?user_id='.$user_id.'&stuff_id='.$stuff_id;
	$pageination = $obj->pagination($table, $where, $data_per_page,$current_page,$redirect_url);

	if($_SESSION['user_type'] == 222)
	{
		$msg_where = " admin_id=".(int)$_SESSION['adminid']." and  user_id='".$user_id."'";
	}
	else
	{
		$msg_where = "   user_id='".$user_id."'";
	}

	$obj = new DBO();
	$num=$obj->GetNumRows("spssp_admin_messages",$msg_where);
	$count_messege=$num+1;


	if($_POST{'insert'})
	{
		unset($_POST['file1']);
		$basepath='../upload/';
		@mkdir($basepath);

		$basepath='../upload/attach/';
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
					$error_gn=1;

				}
				else if($size>8000000)
				{
					$error_gn=2;

				}


			}
			if($error_gn==1)
			{
					?>
					<script type="text/javascript" language="javascript">
					alert("メッセージは送信できませんでした\nexeファイルは送信できません");
					window.location="message_admin.php?user_id=<?=$_GET['user_id']?>&page=<?=$current_page?>&stuff_id=<?=$stuff_id?>";
					</script>
					<?php
			}
			else if($error_gn==2)
			{
					?>
					<script type="text/javascript" language="javascript">
					alert("メッセージは送信できませんでした\n添付の容量は8MBまでです");
					window.location="message_admin.php?user_id=<?=$_GET['user_id']?>&page=<?=$current_page?>&stuff_id=<?=$stuff_id?>";
					</script>
					<?php
			}
			else
			{

				$post['title']=$_POST['title'];
				$post['user_id']=$user_id;
				$post['description']=nl2br($_POST['description']);
				$post['creation_date'] = date("Y-m-d H:i:s");
				$post['display_order']= time();
				$post['admin_id']= $_POST['admin_id'];


				$lastid = $obj->InsertData('spssp_admin_messages',$post);
				if($lastid)
				{
					if(!empty($filename))
					{
						$samplefile=$filename;

						$basepath='../upload/attach/'.$lastid."/";
						@mkdir($basepath);

						@move_uploaded_file($_FILES['upfile']["tmp_name"],$basepath.$samplefile);
					}

					if($samplefile)
					{

						$values['attach_file']=$samplefile;
					}
					else
					$values['attach_file']="";
					$whereCloser =' id='.$lastid;
					$obj->UpdateData('spssp_admin_messages',$values,$whereCloser);
					redirect("message_admin.php?user_id=".$user_id."&page=".$current_page."&stuff_id=".$stuff_id);
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
		exit;
	}

	if($_GET['action']=='delete' && (int)$_GET['id'] > 0)
	{
		$sql = "delete from spssp_admin_messages where id=".(int)$_GET['id'];
		mysql_query($sql);
		//redirect('messages_admin.php?page='.$_GET['page']);
		redirect("message_admin.php?user_id=".$_GET['user_id']."&page=".$current_page."&stuff_id=".$stuff_id);
	}
	if(isset($_GET['action']) && $_GET['action']=='edit_msg')
	{

		$post = $obj->protectXSS($_POST);
		$post['admin_id']=(int)$_SESSION['adminid'];
		$post['description'] = nl2br($post['description']);
		$where=" id=".$post['edit_msg_id'];
		unset($post['edit_msg_id']);

		$lastid = $obj->UpdateData('spssp_admin_messages',$post,$where);

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

function button1_onclick() {
	document.msg_form.upfile.click();
}
function filename_change() {
	document.msg_form.file1.value = document.msg_form.upfile.value;
}
</script>

<div id="topnavi">
    <?php
include("inc/main_dbcon.inc.php");
$hcode=$HOTELID;
$hotel_name = $obj->GetSingleData(" super_spssp_hotel ", " hotel_name ", " hotel_code=".$hcode);
?>
<h1><?=$hotel_name?></h1>
<?
include("inc/return_dbcon.inc.php");
?>

    <div id="top_btn">
        <a href="logout.php"><img src="img/common/btn_logout.jpg" alt="ログアウト" width="102" height="19" /></a>　
        <a href="javascript:;" onclick="MM_openBrWindow('../support/operation_h.html','','scrollbars=yes,width=620,height=600')"><img src="img/common/btn_help.jpg" alt="ヘルプ" width="82" height="19" /></a>
    </div>
</div>
<div id="container">
    <div id="contents">
    <div style="font-size:11px; width:250px;">

  <?php  echo $objInfo->get_user_name_image_or_src($user_row['id'] ,$hotel_id=1, $name="man_lastname.png",$extra="thumb1",$height=20);?>
・
  <?php  echo $objInfo->get_user_name_image_or_src($user_row['id'] ,$hotel_id=1, $name="woman_lastname.png",$extra="thumb1",$width=20);?>
  様

    </div>




	 <h2> <div style="width:400px">
<!--             	 <a href="users.php">お客様一覧</a> &raquo; <a href="user_info.php?user_id=<?=$user_id?>">お客様挙式情報</a> &raquo; メッセージ</div> -->
                 <a href="manage.php">ＴＯＰ</a> &raquo; メッセージ</div>
        </h2>
		<div  style="width:800px;"><div class="navi"><a href="user_info.php?user_id=<?=$user_id?>&stuff_id=<?=$stuff_id?>"><img src="img/common/navi01.jpg" class="on" /></a></div>
      <div class="navi"><img src="img/common/navi02_on.jpg" /></div>
      <div class="navi"><a href="user_dashboard.php?user_id=<?=$user_id?>" target="_blank"><img src="img/common/navi03.jpg" class="on" /></a></div>
      <div class="navi"><a href="guest_gift.php?user_id=<?=$user_id?>&stuff_id=<?=$stuff_id?>"><img src="img/common/navi04.jpg" class="on" /></a></div>
      <div style="clear:both;"></div></div>
      <br />
      <p class="txt3"><!--<a href="#" onclick="view_admin_msg_count(<?=$user_id?>);"><img src="img/common/btn_message.jpg" width="112" height="22" /></a>--></p>
	  <div id="insert_msg">
<!-- 		<div  style="width:800px;"><h2> &nbsp;新規メッセージ</h2></div> UCHIDA EDIT 11/08/09 表示を無効 -->
		<form action="message_admin.php?user_id=<?=$user_id?>&page=<?=$current_page?>&stuff_id=<?=$stuff_id?>" method="post" name="msg_form"  enctype="multipart/form-data">
			<input type="hidden" name="insert" value="insert">
			<input type="hidden" name="admin_id" value="<?=$_SESSION['adminid']?>">

			<table align="center" border="0" cellspacing="6" cellpadding="4">
<!-- 				<tr>
					<td width="7%" style="text-align:left;">No.　　　：</td>
					<td style="text-align:left;">
						<?=$count_messege?>
					</td>
				</tr> -->
				<tr>
					<td width="7%" style="text-align:left;">タイトル&nbsp;&nbsp;：</td>
					<td style="text-align:left;">
						<input type="text" name="title" id="title" style="width:579px;"/>
					</td>
				</tr>
				<tr>
					<td width="7%" style="text-align:left;">本文　　&nbsp;&nbsp;：</td>
					<td style="text-align:left;">
						<textarea name="description" id="description" cols="70" rows="5"></textarea>
					</td>
				</tr>
				<tr>
					<td width="7%" style="text-align:left;">添付　　&nbsp;&nbsp;：</td>
					<td style="text-align:left;" >
						<input type="text" id="file1" name="file1" readonly style="margin-bottom:2px;"/>

						<input id="upfile" type="file" name="upfile" onchange="filename_change();" style="display: none">
						<a href="javascript:void(0);" name="file2" onclick="button1_onclick();"/><img src="img/common/btn_attach_user.jpg" alt="参照" /></a>
					</td>
				</tr>
		<tr>
					<td>&nbsp;</td>
					<td>
						&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" name="insert" onclick="validForm();"/><img src="img/common/btn_send_user.jpg" alt="送信" /></a> &nbsp;
						&nbsp;&nbsp;<a href="javascript:void(0);" name="reset" onclick="cancel_insert();"/><img src="img/common/btn_cancel_user.jpg" alt="チャンセル" /></a>
					</td>
				</tr>
			</table>
		</form>
	</div>
	<div id="update_msg" style="display:none;">
		<div  style="width:800px;"><h2> &nbsp;メッセージ編集</h2></div>
		<form action="message_admin.php?action=edit_msg&user_id=<?=$user_id?>&page=<?=$current_page?>&stuff_id=<?=$stuff_id?>" method="post" name="msg_form_edit">

            <input type="hidden" id="edit_msg_id" value="" name="edit_msg_id" />

			<table align="center" border="0" style="width:800px;">

				<tr>
					<td width="7%" style="text-align:right;">タイトル</td>
					<td style="text-align:left;">
						<input type="text" name="title" id="edit_title" style="width:250px;"/>
					</td>
				</tr>
				<tr>
					<td width="7%" style="text-align:right;">内容</td>
					<td style="text-align:left;">
						<textarea name="description" id="edit_description" cols="70" rows="5"></textarea>
					</td>
				</tr>
		<tr>
					<td>&nbsp;</td>
					<td>
						<input type="button" name="Edit" value="送信" onclick="validForm_Edit();"/> &nbsp; <input type="reset" value="キャンセル"  onclick="cancel_update();" />
					</td>
				</tr>
			</table>
		</form>
	</div>
      <br />
      <div style="width:600px;">
      <div class="navi"><img src="img/common/soushin_img_on.jpg" width="71" height="22" /></div>
      <div class="navi"><a href="message_user.php?user_id=<?=$user_id?>&stuff_id=<?=$stuff_id?>"><img src="img/common/jushin_img.jpg" width="71" height="22" class="on" /></a></div>
      <div style="clear:both;"></div></div>
	  <div id="box_table" style="width:800px;">
	 <!-- <div class="page_next">< ?php echo $pageination;?></div>-->
      <div class="box4">
          <table border="0" align="center" cellpadding="1" cellspacing="1">
            <tr align="center">
<!--               <td>No.</td> -->
              <td>日　付</td>
              <td>&nbsp;</td>
              <td>タイトル</td>
              <td>添付</td>
              <td>本文</td>
              <!--<td>削除</td>-->
            </tr>
          </table>
        </div>

	   <?php

		if($_SESSION['user_type'] == 222)
		{
			$msg_where = " admin_id=".(int)$_SESSION['adminid']." and  user_id='".$user_id."'";
		}
		else
		{
			$msg_where = "   user_id='".$user_id."'";
		}

		//$query_string="SELECT * FROM spssp_admin_messages where $msg_where ORDER BY display_order DESC LIMIT ".((int)($current_page)*$data_per_page).",".((int)$data_per_page).";";
		$query_string="SELECT * FROM spssp_admin_messages where $msg_where ORDER BY display_order DESC ;";
		$data_rows = $obj->getRowsByQuery($query_string);
		//echo "<pre>";
		//print_r($data_rows);exit;
		$i=0;$j=$current_page*$data_per_page+1;

		foreach($data_rows as $row)
		{
		if($i%2==0)
		{
			$class = 'box5';
		}
		else
		{
			$class = 'box6';
		}
	?>
	 <div class="<?=$class?>">
	 	<table border="0" align="center" cellpadding="1" cellspacing="1">
            <tr align="center">
<!--               <td><?=$j?></td> -->
              <td><?=strftime('%Y/%m/%d',strtotime($row['creation_date']))?></td>
              <td></td>
              <!--< ?php $data_user_name = $obj->GetSingleData("spssp_admin", "username","id=".$row['admin_id']);?>
			  <td>< ?=$data_user_name?></td>-->
              <td align="left"><b><?=$row['title']?></b></td>
              <td>
			   <?php  if($row['attach_file']) { ?>
			  <a href="download_attach.php?download_file=<?=$row['attach_file']?>&id=<?=$row['id']?>"><img src="../img/btn_clip_attachment.gif" width="17" height="17" /></a>
			  <?php } else { ?>
			  <img src="img/common/file_no.gif" width="6" height="5" />
			  <?php } ?>
			  </td>
			  <td><a href="#" onclick="view_adminitem(<?=$row['id']?>);"><img src="img/common/btn_honbun.gif" width="42" height="17" /></a></td>
            </tr>
          </table>
	 <div id="viewadmin<?=$row['id']?>" style="display:<?php if($_GET['id']==$row['id']){echo "block";}else{echo "none";}?>;padding-top:10px;">

			<?php echo jp_decode($row['description']);?>
	</div>
	 </div>
        <?php $i++;$j++; }?>


	</div>
	</div>
</div>
<script>

function view_adminitem(id){
$("#viewadmin"+id).toggle("slow");

}

function view_admin_msg_count(id)
{
	$.post("ajax/admin_message_count.php",{'user_id':id},function(data){
			//alert(data);
			$("#No").attr("value",data);
		});
	$("#insert_msg").toggle("slow");
	$("#update_msg").hide("slow");

	$("#title").attr("value",""); // UCHIDA EDIT 11/08/02
	$("#description").attr("value",""); // UCHIDA EDIT 11/08/02
}
function edit_admin_msg(msg_id)
{
	$.post("ajax/admin_message_edit.php",{'msg_id':msg_id},function(data){
			//alert(data);
			var substrs = data.split('#');
			//alert(substr[0]);
			$("#edit_title").attr("value",substrs[0]);
			$("#edit_description").attr("value",substrs[1]);
			$("#edit_msg_id").val(msg_id);
		});
	if($("#update_msg").css('display') == 'none')
	{
		$("#update_msg").toggle("slow");
	}
	else
	{
		$("#update_msg").fadeOut(100);
		$("#update_msg").fadeIn(500);
	}
	$("#insert_msg").hide("slow");
}
function cancel_insert()
{
	$("#title").attr("value","");
	$("#description").attr("value","");
	$("#upfile").attr("value","");
	$("#file1").attr("value","");
//	$("#insert_msg").hide("slow");
}
function cancel_update()
{
	$("#edit_title").attr("value","");
	$("#edit_description").attr("value","");
	$("#update_msg").hide("slow");
}

</script>
<?php
	include_once("inc/left_nav.inc.php");
	include_once("inc/new.footer.inc.php");
?>

