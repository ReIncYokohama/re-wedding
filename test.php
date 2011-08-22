<?php
include_once("admin/inc/dbcon.inc.php");
include_once("admin/inc/class.dbo.php");
include_once("admin/inc/header.inc.php");	

	$obj = new DBO();
	$table='spssp_guest_category';
	$where = " 1=1";
	$data_per_page=10;
	$current_page=(int)$_GET['page'];
	$redirect_url = 'guest_categories.php?page='.(int)$_GET['page'];
	
	$pageination = $obj->pagination($table, $where, $data_per_page,$current_page,$redirect_url);
	
	if($current_page>$total_page) $current_page=$total_page;
	if($current_page<=0) $current_page=1;
?>
<div id="nav">
	<a href="manage.php" >Home</a>  &raquo; <a href="guest_categories.php?page=<?=(int)$_GET['page']?>">Guest Categories</a>  
</div>
<?php
	
	echo "<p style='text-align:left; text-indent:10px'><a href='guest_category_new.php?page=".(int)$_GET['page']."'>Create New Guest Category</a>";		
	echo $pageination;
	
	$get = $obj->protectXSS($_GET);
	
	if($_GET['action']=='delete' && (int)$_GET['id'] > 0)
	{
		$sql = "delete from $table where id=".(int)$_GET['id'];
		mysql_query($sql);
		redirect('guest_categories.php?page='.$_GET['page']);
	}
	else if($_GET['action']=='sort' && (int)$_GET['id'] > 0)
	{		
		$id = $get['id'];
		$move = $get['move'];
		$redirect = 'guest_categories.php?page='.(int)$get['page'];
		
		$obj->sortItem($table,$id,$move,$redirect);
	}
	
?>

	<table align="center" cellspacing="0" cellpadding="3" border="0" class="grid" >
    	<tr class="head">
     
        	<td width="150px"> Name</td>
            <td align="center">Description</td> 
            
            
            <td valign="middle" width="10%" nowrap="nowrap">‡˜•ÏX</td>
			<td valign="middle" width="10%" nowrap="nowrap">•ÒW</td>
			<td valign="middle" width="10%" nowrap="nowrap">íœ</td>
		</tr>
    <?php	
		
		
		$query_string="SELECT * FROM $table  ORDER BY display_order DESC LIMIT ".((int)($current_page-1)*$data_per_page).",".((int)$data_per_page).";";
		$data_rows = $obj->getRowsByQuery($query_string);

		foreach($data_rows as $row)
		{		
	?>
		<tr>
        	<td><a href="guest_sub_categories.php?catid=<?=$row['id']?>"><?=jp_decode($row['name'])?></a></td>
            <td><?=jp_decode($row['description'])?></td>
           
            
            <td valign="top" width="10%" nowrap="nowrap">
            	<a href="guest_categories.php?action=sort&amp;move=up&amp;id=<?=$row['id']?>">£</a> &nbsp;
                <a href="guest_categories.php?action=sort&amp;move=bottom&amp;id=<?=$row['id']?>">¥</a>
            </td>
            <td><a href="guest_category_new.php?id=<?=$row['id']?>&page=<?=(int)$_GET['page']?>">•ÒW</a></td>
            <td><a href="javascript:void(0);" onClick="confirmDelete('guest_categories.php?page=<?=(int)$_GET['page']?>&action=delete&id=<?=$row['id']?>');">íœ</a></td>
        </tr>
        <?php
        }
		?>
	</table>
<?php
	
	include_once("inc/footer.inc.php");
?>
