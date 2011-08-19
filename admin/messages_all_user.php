<?php
	include_once("inc/dbcon.inc.php");
	
	include_once("inc/checklogin.inc.php");
	
	
	include_once("inc/header.inc.php");
	require_once("inc/class.dbo.php");
	$obj = new DBO();
	$table='spssp_message';
	$where = " 1=1";
	$data_per_page=10;
	$current_page=(int)$_GET['page'];
	$redirect_url = 'messages.php';
	
	$pageination = $obj->pagination($table, $where, $data_per_page,$current_page,$redirect_url);
	
	
	if($_GET['action']=='delete' && (int)$_GET['id'] > 0)
	{
		$sql = "delete from spssp_message where id=".(int)$_GET['id'];
		mysql_query($sql);
		redirect('messages_all_user.php?page='.$_GET['page']);
	}
?>
<div id="nav">	
	<a href="manage.php" >Home</a>  &raquo; <a href="messages.php" >メッセージ</a> &raquo; <b>ユーザーメッセージ</b>
</div>


<div><h2>ユーザーメッセージ</h2></div>

<?php echo $pageination;?>
	<table width="100%" align="center" cellspacing="0" cellpadding="3" border="0" class="grid" >
    	<tr class="head">
     		<td width="5%"> #</td>
        	<td width="25%"> タイトル</td>
            <td width="35%" align="center">内容</td> 
            <td width="15%" align="center">Date</td>       
			<td width="10%" valign="middle" width="5%" nowrap="nowrap">Stutas</td>
			<td width="10%" valign="middle" width="5%" nowrap="nowrap"> View </td>
			<!--<td width="10%" valign="middle" width="5%" nowrap="nowrap"> 編集 </td>-->
			
			<td width="10%" valign="middle" width="5%" nowrap="nowrap"> 削除 </td>
		</tr>
<?php	
		$query_string="SELECT * FROM spssp_message ORDER BY display_order DESC LIMIT ".((int)($current_page)*$data_per_page).",".((int)$data_per_page).";";
		$data_rows = $obj->getRowsByQuery($query_string);
		//echo "<pre>";
		//print_r($data_rows);exit;
		$i=0;$j=1;
		foreach($data_rows as $row)
		{		
?>
		<tr <?php if($i%2==0){?>style="background:#FFFEEA"<?php }else{?>style="background:#FFFFDA"<?php }?>>
        	<td><?php echo $j;?></td>
			<td><?=$row['title']?></td>
            <td><?php echo decription_shorter(jp_decode($row['description']));?></td>            
            <td><?=$row['creation_date']?></td>
           	<td><?php if($row['admin_viewed']==1){echo "Viewed";}else{echo "Not viewed";}?></td>
			<td><a href="msg_view.php?id=<?=$row['id']?>&from=1&page=<?=(int)$_GET['page']?>">一覧</a></td>           
            <!--<td><a href="edit_messages.php?id=<?=$row['id']?>&page=<?=(int)$_GET['page']?>">編集</a></td>-->
            <td><a href="javascript:void(0);" onClick="confirmDelete('messages.php?page=<?=(int)$_GET['page']?>&action=delete&id=<?=$row['id']?>');">削除</a></td>
        </tr>
        <?php $i++;$j++; }?>
	</table>

<?php	
	include_once("inc/footer.inc.php");
?>

