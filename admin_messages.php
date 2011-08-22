<?php
	include_once("admin/inc/dbcon.inc.php");
	include_once("admin/inc/class.dbo.php");
	include_once("inc/checklogin.inc.php");
	$obj = new DBO();
	$get = $obj->protectXSS($_GET);
	$user_id = (int)$_SESSION['userid'];
	include_once("inc/new.header.inc.php");

	$table='spssp_admin_messages';
	$where = " user_id='".$user_id."'";
	$data_per_page=5;
	$current_page=(int)$_GET['page'];
	$redirect_url = 'admin_messages.php?page='.$current_page;

	$pageination = $obj->pagination($table, $where, $data_per_page,$current_page,$redirect_url);

?>
<script type="text/javascript">
function view_adminitem(id,no){

		$.post("ajax/admin_message_edit.php",{'id':id},function(data){
			//alert(data);
			var substrs = data.split('#');
			$("#v_no").html("No."+no);
			$("#v_title").html(substrs[2]);
			$("#v_desc").html(substrs[3]);

			$("#view_"+id).hide();

		});


}
$(function(){
		$("ul#menu li").removeClass();
		$("ul#menu li:eq(8)").addClass("active");
	});

	 var title=$("title");
 $(title).html("メッセージ受信 - ウエディングプラス");
</script>
<div id="main_contents">
  <div class="title_bar">
    <div class="title_bar_txt_L">お客様とホテルとのメールの送受信を行います。</div>
    <div class="title_bar_txt_R"></div>
<div class="clear"></div></div>
  <div class="cont_area">
    <div class="message_bt"><a href="user_messages.php?page=<?=(int)$_GET['page']?>"><img src="img/message_bt01.jpg" width="59" height="30" class="on" /></a></div>
    <div class="message_bt"><img src="img/message_bt02.jpg" width="59" height="30"  /></div>

<!--	<div class="page_next">< ?php #echo $pageination;?></div>-->
	<div class="message_area">
      <table width="854" border="0" cellspacing="1" cellpadding="3" bgcolor="#CCCCCC">
        <tr>
          <td width="28" align="center" nowrap="nowrap" bgcolor="#FFFFFF">No.</td>
          <td width="100" align="center" nowrap="nowrap" bgcolor="#FFFFFF">受信日時</td>
          <td width="200" align="center" nowrap="nowrap" bgcolor="#FFFFFF">タイトル</td>
		  <td width="28" align="center" nowrap="nowrap" bgcolor="#FFFFFF">添付</td>
          <td width="70" align="center" nowrap="nowrap" bgcolor="#FFFFFF"></td>
        </tr>
	<?php
		//$query_string="SELECT * FROM ".$table." ORDER BY display_order DESC LIMIT ".((int)($current_page)*$data_per_page).",".((int)$data_per_page).";";
		$query_string="SELECT * FROM ".$table." where $where ORDER BY display_order DESC ;";
		$data_rows = $obj->getRowsByQuery($query_string);
		//echo "<pre>";
		//print_r($data_rows);exit;
		$i=0;$j=$current_page*$data_per_page+1;


		foreach($data_rows as $row)
		{
	?>




        <tr>
          <td  width="28"  align="center" nowrap="nowrap" bgcolor="#FFFFFF"><?=$j?></td>
          <td width="102"  align="center" bgcolor="#FFFFFF"><?=$row['creation_date']?></td>
          <td  width="200" align="left" bgcolor="#FFFFFF" align="left">

		  <?php if($row['user_view']==0){?><a href="javascript:void(0)" onclick="view_adminitem(<?=$row['id']?>,<?=$j?>);" ><img src="admin/img/common/btn_midoku.gif" id="view_<?=$row['id']?>" width="42" height="17" /></a>&nbsp;&nbsp;<?php }?><?=$row['title']?></td>
		  <td width="28" align="center" bgcolor="#FFFFFF">
		  	<?php  if($row['attach_file']) { ?>
			  <a href="download_attach.php?download_file=<?=$row['attach_file']?>&id=<?=$row['id']?>"><img src="img/btn_clip_attachment.gif" width="17" height="17" /></a>
			  <?php } else { ?>
			  <img src="admin/img/common/file_no.gif" width="6" height="5" />
			  <?php } ?>
			</td>
          <td  width="70"  bgcolor="#FFFFFF" align="center">
		  <input type="button" name="button1" id="button1" value="本文表示" onclick="view_adminitem(<?=$row['id']?>,<?=$j?>);"/>
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
    メッセージ本文<br />
    <div class="message_area2">
      <p id="v_desc"></p>

    </div>

<div class="clear"></div>
  </div>
<?php
include("inc/new.footer.inc.php");
?>
