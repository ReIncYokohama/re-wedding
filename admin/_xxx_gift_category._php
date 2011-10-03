<?php
	include_once("inc/dbcon.inc.php");
	
	include_once("inc/checklogin.inc.php");
	
	
	include_once("inc/header.inc.php");
	require_once("inc/class.dbo.php");
	$obj = new DBO();
	$table='spssp_admin_gift_group';
	$where = " 1=1";
	$data_per_page=10;
	$current_page=(int)$_GET['page'];
	$redirect_url = 'gift_category.php';
	
	$pageination = $obj->pagination($table, $where, $data_per_page,$current_page,$redirect_url);
	
	if($current_page>$total_page) $current_page=$total_page;
	if($current_page<=0) $current_page=1;
?>
<div id="nav">
	<a href="manage.php" >Home</a>  &raquo; 引出物グループ別総数  
</div>
<div style="float:left;width:100%;">
	<ul>
		
        <li style="float:left; list-style:none;padding-right:10px; font-size:20px;"><a href='gift.php'>Gift</a></li>
        
        <li style="float:left;clear:both;list-style:none;padding-right:10px;"><a href='gift_category_update.php?page=<?=(int)$_GET['page']?>'>Create New Category</a></li>
		
	</ul>

</div>

<h2>引出物グループ別総数</h2>
<?php		
	echo $pageination;
	$get = $obj->protectXSS($_GET);
	if($_GET['action']=='delete' && (int)$_GET['id'] > 0)
	{
		$sql = "delete from $table where id=".(int)$_GET['id'];
		mysql_query($sql);
		
	}
	else if($_GET['action']=='sort' && (int)$_GET['id'] > 0)
	{
				
		$id = $get['id'];
		$move = $get['move'];
		$redirect = 'gift_category.php?page='.(int)$get['page'];
		
		$obj->sortItem($table,$id,$move,$redirect);
	}
	
?>

	<table width="100%" align="center" cellspacing="0" cellpadding="3" border="0" class="grid" >
    	<tr class="head">
     
        	<td width="150px"> タイトル</td>
     
			<td valign="middle" width="10%" nowrap="nowrap">編集</td>
			<td valign="middle" width="10%" nowrap="nowrap">削除</td>
		</tr>
    <?php	
		
		
		$query_string="SELECT * FROM $table  ORDER BY displayorder DESC LIMIT ".((int)($current_page-1)*$data_per_page).",".((int)$data_per_page).";";
		$data_rows = $obj->getRowsByQuery($query_string);
		//echo "<pre>";
		//print_r($data_rows);exit;
		$i=1;
		foreach($data_rows as $row)
		{		
	?>
		<tr <?php if($i%2==0){?>style="background:#FFFEEA"<?php }else{?>style="background:#FFFFDA"<?php }?>>
        	<td><?=$row['name']?></td>
            
            <td><a href="gift_category_update.php?id=<?=$row['id']?>&page=<?=(int)$_GET['page']?>">編集</a></td>
            <td><a href="javascript:void(0);" onClick="confirmDelete('gift_category.php?page=<?=(int)$_GET['page']?>&action=delete&id=<?=$row['id']?>');">削除</a></td>
        </tr>
        <?php
         $i++; }
		?>
	</table>
<?php
	
	include_once("inc/footer.inc.php");
?>
