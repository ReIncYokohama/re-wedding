<?php
	include_once("inc/dbcon.inc.php");
	
	include_once("inc/checklogin.inc.php");
	
	
	include_once("inc/header.inc.php");
	require_once("inc/class.dbo.php");
	$obj = new DBO();
	$table='spssp_guest_sub_category';
	$where = " 1=1";
	$data_per_page=10;
	$current_page=(int)$_GET['page'];
	$redirect_url = 'guest_sub_categories.php?page='.(int)$_GET['page'];
	
	$pageination = $obj->pagination($table, $where, $data_per_page,$current_page,$redirect_url);
	
	if($current_page>$total_page) $current_page=$total_page;
	if($current_page<=0) $current_page=1;
	$get = $obj->protectXSS($_GET);
	
?>
<div id="nav">
	<a href="manage.php" >Home</a>  &raquo; <a href="guest_categories.php?page=<?=(int)$_GET['page']?>">Guest Categories</a>  
    &raquo; Guest Sub Categories
</div>
<?php
	
	echo "<p style='text-align:left; text-indent:10px'><a href='guest_sub_category_new.php?page=".(int)$_GET['page']."&amp;catid=".$get['catid']."'> New Guest Sub Category</a>";		
	echo $pageination;
	
	
	
	if($_GET['action']=='delete' && (int)$_GET['id'] > 0)
	{
		$sql = "delete from $table where id=".(int)$get['id'];
		mysql_query($sql);
		redirect('guest_sub_categories.php?catid='.(int)$get['id'].'&amp;page='.$get['page']);
	}
	else if($_GET['action']=='sort' && (int)$_GET['id'] > 0)
	{		
		$id = $get['id'];
		$move = $get['move'];
		$redirect = 'guest_sub_categories.php?catid='.(int)$get['id'].'&amp;page='.(int)$get['page'];	
		
		$obj->sortItem($table,$id,$move,$redirect);
	}
	
?>

	<table width="100%" align="center" cellspacing="0" cellpadding="3" border="0" class="grid" >
    	<tr class="head">
     
        	<td width="150px"> Name</td>
            <td align="center">Category</td> 
            
            
            <td valign="middle" width="10%" nowrap="nowrap">順序変更</td>
			<td valign="middle" width="10%" nowrap="nowrap">編集</td>
			<td valign="middle" width="10%" nowrap="nowrap">削除</td>
		</tr>
    <?php	
		
		
		$query_string="SELECT lft.*, ct.name catname FROM $table lft left outer join spssp_guest_category ct on lft.category_id= ct.id where lft.category_id=".(int)$get['catid']."  ORDER BY lft.display_order DESC LIMIT ".((int)($current_page-1)*$data_per_page).",".((int)$data_per_page).";";
		//echo $query_string;exit;
		$data_rows = $obj->getRowsByQuery($query_string);
$i=0;
		foreach($data_rows as $row)
		{		
	?>
		<tr <?php if($i%2==0){?>style="background:#FFFEEA"<?php }else{?>style="background:#FFFFDA"<?php }?>>
        	<td><a href="guests.php?catid=<?=$row['category_id']?>&subcatid=<?=$row['id']?>"><?=$row['name']?></a></td>
            <td><?=$row['catname']?></td>
           
            
            <td valign="top" width="10%" nowrap="nowrap">
            	<a href="guest_sub_categories.php?action=sort&amp;move=up&amp;id=<?=$row['id']?>">▲</a> &nbsp;
                <a href="guest_sub_categories.php?action=sort&amp;move=bottom&amp;id=<?=$row['id']?>">▼</a>
            </td>
            <td><a href="guest_sub_category_new.php?id=<?=$row['id']?>&amp;catid=<?=(int)$get['catid']?>&amp;page=<?=(int)$_GET['page']?>">編集</a></td>
            <td><a href="javascript:void(0);" onClick="confirmDelete('guest_sub_categories.php?page=<?=(int)$_GET['page']?>&action=delete&id=<?=$row['id']?>');">削除</a></td>
        </tr>
       <?php $i++; }?>
	</table>
<?php
	
	include_once("inc/footer.inc.php");
?>
