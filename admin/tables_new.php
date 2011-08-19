<?php
	include_once("inc/dbcon.inc.php");
	
	include_once("inc/checklogin.inc.php");
	
	
	include_once("inc/header.inc.php");
	require_once("inc/class.dbo.php");
	$obj = new DBO();
	$get = $obj->protectXSS($_GET);
	$tableid = (int)$get['table_id'];
	
	
	
	$where="id=".$roomid;
	$table='spssp_tables_name';
	$page_where= " 1=1 ";
	$data_per_page=10;	
	$current_page=(int)$get['page'];
	$redirect_url = 'tables_new.php?table_id='.$tableid;
	

	//$pageination = $obj->pagination($table, $page_where, $data_per_page,$current_page,$redirect_url);
	
	
	
	if($_POST['edit_Done'])
	{
		if($_POST['name'])
		{	
			$sql_qry_up="update ".$table." set name='".$_POST['name']."' where id=".$tableid;
			echo $result=mysql_query($sql_qry_up);
			if($result)
			{
				$msg="Updated successfully.";
			}else
			{
				$err="Not Updated.Database Problem.";
			}
			
		}else{
		
			$err="Please Fill up all Fields.";
		}
	}
	if($_POST['add'])
	{
		
		if($_POST['name'])
		{
			$post['name']=$_POST['name'];
			$post['display_order']=time();	
			
			$row_count=$obj->GetRowCount($table,"name='".$post['name']."' or display_order='".$post['display_order']."'");
			if($row_count)
			{
				$err="Table name or Display order already exist.Please check and try another.";
			}else
			{				
				$lastid = $obj->InsertData($table,$post);
				if($lastid)
				{
					$msg="Inserted successfully.";
					redirect("tables_new.php?msg=".$msg."&page=".$current_page);
				}else{
				
					$err="Not Inserted.Database Problem.";
				}
			}
		}else{
		
			$err="Please Fill up all Fields.";
		}
	}
if($_GET['action']=='delete' && (int)$_GET['id'] > 0)
	{
		$sql = "delete from $table where id=".(int)$_GET['id'];
		echo $res=mysql_query($sql);
		if($res)
		{
			$msg="Deleted successfully.";
			redirect('tables_new.php?msg='.$msg.'&page='.$_GET['page']);
		}else{
		
			$err="Not Deleted.Database Problem.";
		}
		
	}
	else if($_GET['action']=='sort' && (int)$_GET['id'] > 0)
	{
				
		$id = $get['id'];
		$move = $get['move'];
		$redirect = 'tables_new.php?page='.(int)$get['page'];
		
		$obj->sortItem($table,$id,$move,$redirect);
	}
?>

<div id="nav">
	<a href="manage.php" >Home</a>  &raquo; <b>Tables</b>
</div>

<div style="float:left;width:100%;">
	<ul>
		<li style="float:left;list-style:none;padding-right:10px;"><a href='#' onclick="add_new_table();">Add New Table</a></li>
	</ul>

</div>
<div id="new_table" style="display:none;">
		<form action="tables_new.php" method="post">
		Table Name:<input type="text" name="name" value="">
		
		<input type="submit" name="add" value="Add">
		</form>
		</div>
<script>

function add_new_table(id){
$("#new_table").toggle("slow");

}  
function toggle(elementID){
var target1 = document.getElementById(elementID)
if (target1.style.display == 'none') {
target1.style.display = 'block'
} else {
target1.style.display = 'none'
}
}  
</script>	
<?php if($err){echo "<script>
			alert('".$err."');
			</script>";}?>
<?php if($_GET[msg]){echo "<script>
			alert('".$_GET['msg']."');
			</script>";}?>

   
	<div>
	<?php echo $pageination;?>
	</div>
	
<div style="clear:both;"><h2>Tables name</h2></div>
<div style="clear:both;">		
<table>
<tr class="head">
	<td>#</td>
	<td>Name</td>
	<td>順序変更</td>
	<td>Action</td>
</tr>
<?php

$query_string="SELECT * FROM spssp_tables_name order by display_order LIMIT ".($current_page*$data_per_page).",".((int)$data_per_page).";";
		$data_rows = $obj->getRowsByQuery($query_string);
		$j=1;
		foreach($data_rows as $row)
		{?>
		<tr>
		<td><?=$j;?></td>
		<td><?php echo $row['name'];?>
		<div id="<?php echo $row['id'];?>" style="display:none;">
			<form action="tables_new.php?table_id=<?php echo $row['id'];?>&page=<?php echo $current_page;?>" method="post">
				<input type="text"name="name" value="<?php echo $row['name'];?>">
				<input type="submit" name="edit_Done" value="Done">
			</form>
		</div>
		</td>
		<td>
            	<a href="tables_new.php?action=sort&amp;move=up&amp;id=<?=$row['id']?>">▼</a> &nbsp;
                <a href="tables_new.php?action=sort&amp;move=bottom&amp;id=<?=$row['id']?>">▲</a>
            </td>
		<td><a href="javascript: toggle('<?php echo $row['id'];?>');">編集</a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onClick="confirmDelete('tables_new.php?page=<?=(int)$_GET['page']?>&action=delete&id=<?=$row['id']?>');">削除</a></td>
		</tr>
		
		<?php 
	$j++;	}
?>

</table>
</div>
<?php	
	include_once("inc/footer.inc.php");
?>
