<?php
	require_once("inc/class.dbo.php");
	include_once("inc/checklogin.inc.php");
	include_once("inc/new.header.inc.php");

	$obj = new DBO();
	$table='spssp_admin_messages';
	$where = " 1=1";
	$data_per_page=10;
	$current_page=(int)$_GET['page'];
	$redirect_url = 'messages.php';

	$pageination = $obj->pagination($table, $where, $data_per_page,$current_page,$redirect_url);

	if($_GET['action']=='delete' && (int)$_GET['id'] > 0)
	{
		$sql = "delete from spssp_admin_messages where id=".(int)$_GET['id'];
		mysql_query($sql);
		redirect('messages.php?page='.$_GET['page']);
	}
?>
<div id="topnavi">
    <?php
include("inc/main_dbcon.inc.php");
$hcode="0001";
$hotel_name = $obj->GetSingleData(" dev2_main.super_spssp_hotel ", " hotel_name ", " hotel_code=".$hcode);
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
        <h2>管理会社より -News-</h2>
		<div><a href="messages_new.php">新着メッセージ</a></div>
		<div class="page_next"><?php echo $pageination;?></div>
		<!--<div class="box4">
                <table border="0" align="center" cellpadding="1" cellspacing="1">
                    <tr class="box4">
						<td width="5%"> #</td>
						<td width="25%"> タイトル</td>
						<td width="35%" align="center">内容</td>
						<td width="15%" align="center">Date</td>
						<td width="10%" valign="middle" width="5%" nowrap="nowrap"> 編集 </td>
						<td width="10%" valign="middle" width="5%" nowrap="nowrap"> 削除 </td>
					</tr>
                </table>
        </div>-->




   <?php


		$query_string="SELECT * FROM spssp_admin_messages ORDER BY display_order DESC LIMIT ".((int)($current_page)*$data_per_page).",".((int)$data_per_page).";";
		$data_rows = $obj->getRowsByQuery($query_string);
		//echo "<pre>";
		//print_r($data_rows);exit;
		$i=0;$j=1;

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

        	<a href="#" onclick="view_adminitem(<?=$row['id']?>);"><div><b><?php echo "#".$j;?></b>&nbsp;&nbsp;&nbsp;<?=$row['creation_date']?>&nbsp;&nbsp;&nbsp;<b><?=$row['title']?></b></div></a>
            <div id="viewadmin<?=$row['id']?>" style="display:<?php if($_GET['id']==$row['id']){echo "block";}else{echo "none";}?>;padding-top:10px;">
			<?php echo jp_decode($row['description']);?>

			<div><a href="messages_edit.php?id=<?=$row['id']?>&page=<?=(int)$_GET['page']?>">編集</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<a href="javascript:void(0);" onClick="confirmDelete('messages.php?page=<?=(int)$_GET['page']?>&action=delete&id=<?=$row['id']?>');">削除</a>
			</div>
			</div>



            <!--<td width="10%" align="center"><a href="messages_edit.php?id=<?=$row['id']?>&page=<?=(int)$_GET['page']?>">編集</a></td>
            <td width="10%" align="center"><a href="javascript:void(0);" onClick="confirmDelete('messages.php?page=<?=(int)$_GET['page']?>&action=delete&id=<?=$row['id']?>');">削除</a></td>-->

  </div>
        <?php $i++;$j++; }?>
	</div>

	</div>
</div>
<script>

function view_adminitem(id){
$("#viewadmin"+id).toggle("slow");

}
</script>

<?php
include_once("inc/left_nav.inc.php");
include_once("inc/new.footer.inc.php");
?>