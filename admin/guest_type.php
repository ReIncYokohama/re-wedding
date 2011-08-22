<?php
	include_once("inc/dbcon.inc.php");
	
	include_once("inc/checklogin.inc.php");
	
	
	include_once("inc/header.inc.php");
	require_once("inc/class.dbo.php");
	$obj = new DBO();
	$get = $obj->protectXSS($_GET);
	$typeid = (int)$get['type_id'];
	
	
	
	$where="id=".$typeid;
	$table='dev2_main.spssp_guest_type';
	$page_where= " 1=1 ";
	$data_per_page=10;	
	$current_page=(int)$get['page'];
	$redirect_url = 'guest_type.php?type_id='.$tableid;
	

	//$pageination = $obj->pagination($table, $page_where, $data_per_page,$current_page,$redirect_url);
	
	
	
	if($_POST['edit_Done'])
	{
		if($_POST['name'])
		{	
			$sql_qry_up="update ".$table." set name='".$_POST['name']."' where id=".$typeid;
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
			$post['name']=jp_encode($_POST['name']);
					
			$row_count=$obj->GetRowCount($table,"name='".$post['name']."'");
			if($row_count)
			{
				$err="Guest type already exist.Please check and try another.";
			}else
			{				
				$lastid = $obj->InsertData($table,$post);
				if($lastid)
				{
					$msg="Inserted successfully.";
					redirect("guest_type.php?msg=".$msg."&page=".$current_page);
				}else{
				
					$err="Not Inserted.Database Problem.";
				}
			}
		}else{
		
			$err="Please Fill up the Field.";
		}
	}
echo $msg;
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
		<form action="guest_type.php" method="post">
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
			alert('".$obj->GetErrorMsgNew($err)."');
			</script>";}?>
<?php if($_GET[msg]){echo "<script>
			alert('".$obj->GetSuccessMsgNew($_GET['msg'])."');
			</script>";}?>

   
	<div>
	<?php echo $pageination;?>
	</div>
	
<div style="clear:both;"><h2>Guest Type</h2></div>
<div style="clear:both;">		
<?php

$query_string="SELECT * FROM dev2_main.spssp_guest_type LIMIT ".($current_page*$data_per_page).",".((int)$data_per_page).";";
		$data_rows = $obj->getRowsByQuery($query_string);
		$j=1;
		foreach($data_rows as $row)
		{?>
		<ul style="list-style:none;">
		<li><?php echo $j.".&nbsp;".jp_decode($row['name']);?>&nbsp;&nbsp;&nbsp;<a href="javascript: toggle('<?php echo $row['id'];?>');">編集</a> 			        <div id="<?php echo $row['id'];?>" style="display:none;">
		<form action="guest_type.php?type_id=<?php echo $row['id'];?>&page=<?php echo $current_page;?>" method="post">
		
		Table Name:<input type="text"name="name" value="<?php echo $row['name'];?>">
		<input type="submit" name="edit_Done" value="Done">
		</form>
		</div>
		</li>
		</ul>
		
		<?php 
	$j++;	}
?>

</div>
<?php	
	include_once("inc/footer.inc.php");
?>
