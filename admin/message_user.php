<?php
	require_once("inc/class.dbo.php");
    require_once("inc/include_class_files.php");
	include_once("inc/checklogin.inc.php");
	include_once("inc/new.header.inc.php");
	$obj = new DBO();
	$objInfo = new InformationClass();
	$user_id = $_GET['user_id'];
	$user_row = $obj->GetSingleRow("spssp_user"," id= $user_id");
	$table='spssp_message';
	if($user_id > 0) {
		$where = " user_id=".$user_id;
	}
	else {
		$where = " 1= 1";
	}

// UCHIDA EDIT 11/08/18 管理者用お客様一覧から各スタッフのメールを確認する
	$modify = 0;
	$stuff_id = $_GET['stuff_id'];
	if ($stuff_id=="" || $stuff_id=="0") {
		$modify = 1; // 未読を既読にする
//		$stuff_id = (int)$_SESSION['adminid'];
	}
	$user_type = $_SESSION['user_type'];

/*
	if($user_type == 222 || $user_type == 333)
	{
		$stuff_users = $obj->GetAllRowsByCondition("spssp_user", "stuff_id=".$stuff_id);

		if ($stuff_id>0) {
			foreach($stuff_users as $su)
			{
				$user_id_arr[] = $su['id'];
			}
			if(!empty($user_id_arr))
			{
				$stuff_users_string = implode(",",$user_id_arr);
				$where.= " user_id in ( $stuff_users_string ) ";
			}
		}
	}
*/

//echo "<script> alert('$stuff_id $where'); </script>";

	$data_per_page=5;
	$current_page=(int)$_GET['page'];
	$redirect_url = 'message_user.php?user_id='.$user_id.'$stuff_id'.$stuff_id;

	$pageination = $obj->pagination($table, $where, $data_per_page,$current_page,$redirect_url);

	if($_POST{'insert'})
	{
		if($_POST['title']&&$_POST['description'])
		{
			$post['user_id']= $_GET['user_id'];
			$post['title']=$_POST['title'];
			$post['description']=nl2br($_POST['description']);
			$post['creation_date'] = date("Y-m-d H:i:s");
			$post['display_order']= time();
			$post['admin_id']= $_POST['admin_id'];
			$lastid = $obj->InsertData('spssp_message',$post);
			if($lastid)
			{
				redirect("message_user.php?user_id=".$_GET['user_id']."&page=".$current_page."&stuff_id=".$stuff_id);
			}
			else
			{
				$err=1;
			}
		}
		else
		{
			$err=2;
		}
	}

	if($_GET['action']=='delete' && (int)$_GET['id'] > 0)
	{
		$sql = "delete from spssp_message where id=".(int)$_GET['id'];
		mysql_query($sql);
		//redirect('message_user.php?user_id='.$_GET['user_id'].'&page='.$_GET['page']);
         redirect("message_user.php?user_id=".$_GET['user_id']."&page=".$current_page."&stuff_id=".$stuff_id);
	}

	if(isset($_GET['action']) && $_GET['action']=='edit_msg')
	{

		$post = $obj->protectXSS($_POST);
		//$post['user_id']=(int)$_GET['user_id'];
		$post['description'] = nl2br($post['description']);
		$where=" id=".$post['edit_msg_id'];
		unset($post['edit_msg_id']);

		$lastid = $obj->UpdateData('spssp_message',$post,$where);

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
</script>
<script type="text/javascript"><!--
function m_win(url,windowname,width,height) {
 var features="location=no, menubar=no, status=yes, scrollbars=yes, resizable=yes, toolbar=no";
 if (width) {
  if (window.screen.width > width)
   features+=", left="+(window.screen.width-width)/2;
  else width=window.screen.width;
  features+=", width="+width;
 }
 if (height) {
  if (window.screen.height > height)
   features+=", top="+(window.screen.height-height)/2;
  else height=window.screen.height;
  features+=", height="+height;
 }
 window.open(url,windowname,features);
// window.showModalDialog(url,windowname,"dialogTop:400px; dialogLeft:600px; dialogwidth:"+width+"px; dialogheight:"+height+"px;");
}

// --></script>
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

  <?php  echo $objInfo->get_user_name_image_or_src($user_row['id'] ,$hotel_id=1, $name="man_lastname.png",$extra="thumb1");?>
・
  <?php  echo $objInfo->get_user_name_image_or_src($user_row['id'] ,$hotel_id=1, $name="woman_lastname.png",$extra="thumb1");?>
  様
    </div>

	 <h4><div style="width:400px">
<!--                  <a href="users.php">お客様一覧</a> &raquo; <a href="user_info.php?user_id=<?=$user_id?>">お客様挙式情報 </a>&raquo; メッセージ</div> -->
		<?php
		if($stuff_id==0) {
            echo '<a href="manage.php">ＴＯＰ</a> &raquo; お客様挙式情報 &raquo; メッセージ';
		}
		else {
            echo '<a href="users.php">管理者用お客様一覧</a> &raquo; お客様挙式情報 &raquo; メッセージ';
		}
		?>
		</div>

        </h4>
        <div  style="width:800px;"><div class="navi"><a href="user_info_allentry.php?user_id=<?=$user_id?>&stuff_id=<?=$stuff_id?>"><img src="img/common/navi01.jpg" class="on" onMouseOver="this.src='img/common/navi01_over.jpg'"onMouseOut="this.src='img/common/navi01.jpg'" /></a></div>
        <div class="navi"><img src="img/common/navi02_on.jpg" /></div>
        <div class="navi"><a href="guest_gift.php?user_id=<?=$user_id?>&stuff_id=<?=$stuff_id?>"><img src="img/common/navi04.jpg" onMouseOver="this.src='img/common/navi04_over.jpg'"onMouseOut="this.src='img/common/navi04.jpg'" /></a></div>
            <div class="navi">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
        	<div class="navi">
        	<a href="javascript:void(0);" onClick="windowUserOpen('user_dashboard.php?user_id=<?=$user_id?>')">
        		<img src="img/common/navi03.jpg" onMouseOver="this.src='img/common/navi03_on.jpg'"onMouseOut="this.src='img/common/navi03.jpg'" />
        	</a>
            </div><div class="navi2">
        
<?php if($_SESSION["super_user"]){ ?>
          <div class="navi"><a href="csv_upload.php?user_id=<?=$user_id?>"  onclick="m_win(this.href,'mywindow7',700,200); return false;">
            <img src="img/common/navi05.jpg" onMouseOver="this.src='img/common/navi05_over.jpg'"onMouseOut="this.src='img/common/navi05.jpg'" /></a></div>
<?php } ?></div>
        <div style="clear:both;"></div>
        </div>
      <br />
     <!-- <p class="txt3"><a href="#" onclick="view_user_msg_count(<?=$user_id?>);"><img src="img/common/btn_message.jpg" width="112" height="22" /></a></p>-->
       <div id="insert_msg" style="display:none;">
		<div  style="width:800px;"><h2> &nbsp;新着メッセージ</h2></div>
		<form action="message_user.php?user_id=<?=$_GET['user_id']?>&page=<?=(int)$_GET['page']?>&stuff_id=<?=$stuff_id?>" method="post" name="msg_form">
			<input type="hidden" name="insert" value="insert">
<!-- 			<input type="hidden" name="admin_id" value="<?=$_SESSION['adminid']?>"> -->
			<input type="hidden" name="admin_id" value="<?=$stuff_id?>">
			<table align="center" border="0" style="width:800px;">
				<tr>
					<td width="5%" style="text-align:right;">No.：</td>
					<td style="text-align:left;">
						<input name="No" type="text" id="No" value="" size="1" />
					</td>
				</tr>
				<tr>
					<td width="5%" style="text-align:right;">タイトル</td>
					<td style="text-align:left;">
						<input type="text" name="title" id="title" style="width:250px;"/>
					</td>
				</tr>
				<tr>
					<td width="5%" style="text-align:right;">本文</td>
					<td style="text-align:left;">
						<textarea name="description" id="description" cols="70" rows="5"></textarea>
					</td>
				</tr>
		<tr>
					<td>&nbsp;</td>
					<td>
						<input type="button" name="insert" value="送信" onclick="validForm();"/> &nbsp; <input type="reset" value="キャンセル"  onclick="cancel_insert();" />
					</td>
				</tr>
			</table>
		</form>
	</div>
	  <br />
      <div id="update_msg" style="display:none;">
		<div  style="width:800px;"><h2> &nbsp;メッセージ編集</h2></div>
		<form action="message_user.php?action=edit_msg&user_id=<?=$user_id?>&page=<?=(int)$_GET['page']?>&stuff_id=<?=$stuff_id?>" method="post" name="msg_form_edit">

            <input type="hidden" id="edit_msg_id" value="" name="edit_msg_id" />

			<table align="center" border="0" style="width:800px;">

				<tr>
					<td width="5%" style="text-align:right;">タイトル</td>
					<td style="text-align:left;">
						<input type="text" name="title" id="edit_title" style="width:250px;"/>
					</td>
				</tr>
				<tr>
					<td width="5%" style="text-align:right;">内容</td>
					<td style="text-align:left;">
						<textarea name="description" id="edit_description" cols="70" rows="5"></textarea>
					</td>
				</tr>
		<tr>
					<td>&nbsp;</td>
					<td>
						<input type="button" name="Edit" value="保存" onclick="validForm_Edit();"/> &nbsp; <input type="reset" value="キャンセル" onclick="cancel_update();" />
					</td>
				</tr>
			</table>
		</form>
	</div>
	  <div>
      <div class="navi"><a href="message_admin.php?user_id=<?=$user_id?>&stuff_id=<?=$stuff_id?>"><img src="img/common/soushin_img.jpg" width="71" height="22" class="on" /></a></div>
      <div class="navi"><img src="img/common/jushin_img_on.jpg" width="71" height="22" /></div>
      <div style="clear:both;"></div></div>

      <div id="box_table" style="width:800px;">
	  <!--<div class="page_next"><?php echo $pageination;?></div>-->
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
		//$query_string="SELECT * FROM spssp_message where ".$where." ORDER BY display_order DESC LIMIT ".((int)($current_page)*$data_per_page).",".((int)$data_per_page).";";
		$query_string="SELECT * FROM spssp_message where ".$where." ORDER BY display_order DESC ;";
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
		if($row['admin_viewed']==0){
			$view=1;
		}else
		{
			$view=0;
		}
	?>
		<div class="<?=$class?>">
			<table border="0" align="center" cellpadding="1" cellspacing="1">
            <tr align="center">
<!--               <td><?=$j?></td> -->
              <td><?=strftime('%Y/%m/%d',strtotime($row['creation_date']))?></td>
              <td><div id="view_<?=$row['id']?>"><?php if($row['admin_viewed']==0){?><a href="javascript:void(0)" onclick="view_adminitem(<?=$row['id']?>,<?=$view?>,<?=$modify?>);"><img src="img/common/btn_midoku.gif" id="view<?=$row['id']?>" width="42" height="17" /></a><?php }?></td>
              <!-- < ?php if($row['admin_id']){
			  $data_user = $obj->GetSingleRow("spssp_admin", "id=".$row['admin_id']);
			  ?>
			  <td>< ?=$data_user['username']."-".$data_user['name']?></td>
			  < ?php }else{
			  $data_user = $obj->GetSingleRow("spssp_user", "id=".$row['user_id']);
			  ?>
			   <td>< ?=$data_user['man_lastname']."-".$data_user['man_firstname']?></td>
			  < ?php }?>-->

              <td align="left"><b><?=$row['title']?></b></td>
              <td>
			  <?php  if($row['attach_file']) { ?>
			  <a href="download_attach.php?download_file=<?=$row['attach_file']?>&id=<?=$row['id']?>"><img src="../img/btn_clip_attachment.gif" width="17" height="17" /></a>
			  <?php } else { ?>
			  <img src="img/common/file_no.gif" width="6" height="5" />
			  <?php } ?>
			  </td>
			  <!--<td><a href="#" onclick="edit_admin_msg(< ?=$row['id']?>);"><img src="img/common/btn_edit.gif" width="42" height="17" /></a></td>-->
              <td><a href="#" onclick="view_adminitem(<?=$row['id']?>,<?=$view?>,<?=$modify?>);"><img src="img/common/btn_honbun.gif" width="42" height="17" /></a></td>
              <!--<td><a href="javascript:void(0);" onClick="confirmDelete('message_user.php?page=<?=(int)$_GET['page']?>&action=delete&id=<?=$row['id']?>&user_id=<?=$user_id?>');"><img src="img/common/btn_deleate.gif" width="42" height="17" /></a></td>-->
            </tr>
          </table>
		  <div id="viewadmin<?=$row['id']?>" style="display:none;padding-top:10px;">
			<?php echo jp_decode($row['description']);?>
		  </div>
		</div>
        <?php $i++;$j++; }?>

		</div>
	</div>
</div>
<script>

function view_adminitem(id,view,modify){ // UCHIDA EDIT 11/08/19 既読にするしないを通知
	if(view==1)
	{
		$.post("ajax/view_user_message.php",{'id':id,'modify':modify},function(data){

			//alert(data);
		});
		if(modify==1) $("#view_"+id).html(""); // UCHIDA EDIT 11/08/19 既読の場合だけ、未読アイコンを消す
	}
$("#viewadmin"+id).toggle("slow");

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
	$("#insert_msg").hide("slow");
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
