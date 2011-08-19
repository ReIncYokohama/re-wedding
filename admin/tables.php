<?php
	include_once("inc/dbcon.inc.php");
	
	include_once("inc/checklogin.inc.php");
	
	
	include_once("inc/header.inc.php");
	require_once("inc/class.dbo.php");
	$obj = new DBO();
	$get = $obj->protectXSS($_GET);
	$roomid = (int)$get['room_id'];
	$tableid = (int)$get['table_id'];
	
	
	$room_table='spssp_room';
	$where="room_id=".$roomid;
	
	
	
	$table='spssp_default_plan_table';
	$page_where= " 1=1 ";
	$data_per_page=10;	
	$current_page=(int)$get['page'];
	$redirect_url = 'tables.php?room_id='.$roomid;
	

	$pageination = $obj->pagination($table, $page_where, $data_per_page,$current_page,$redirect_url);
	
	
	
	if($_POST{'edit_Done'})
	{
		$sql_qry_up="update ".$table." set name='".$_POST['table_name']."' where id=".$_POST['tbl_name_id'];
		mysql_query($sql_qry_up);
		
	}
//if($current_page==null||$current_page==0){$current_page=1;}
?>

<script type="text/javascript">
function toggle(elementID){
var target1 = document.getElementById(elementID)
if (target1.style.display == 'none') {
target1.style.display = 'block'
} else {
target1.style.display = 'none'
}
} 
</script>

<div id="nav">
	<a href="manage.php" >Home</a>  &raquo; <a href="rooms.php" >Rooms</a> &raquo;  <a href="plans.php?room_id=<?php echo $roomid;?>&page=<?php echo (int)$get['page'];?>" >Default Plans</a> &raquo; <b>Tables</b>
</div>


	
    <?php	
		
		
		$room_name = $obj->GetSingleData($room_table,"name",$where);
		
		
	?>
	<div>
	<h3>&nbsp;&nbsp;<?php echo $room_name;?></h3>
	</div>
	<div>
	<?php echo $pageination;?>
	</div>
		
<?php

$query_string="SELECT * FROM spssp_default_plan_table where room_id=".$roomid." LIMIT ".($current_page*$data_per_page).",".((int)$data_per_page).";";
		$data_rows = $obj->getRowsByQuery($query_string);

		foreach($data_rows as $row)
		{?>
		<ul>
		<li><?php echo $row['name'];?>&nbsp;&nbsp;&nbsp;<a href="javascript: toggle('<?php echo $row['id'];?>');">Edit</a> 			        <div id="<?php echo $row['id'];?>" style="display:none;">
		<form action="tables.php?room_id=<?php echo $roomid;?>&page=<?php echo $current_page;?>" method="post">
		<input type="hidden" name="tbl_name_id" value="<?php echo $row['id'];?>">
		<input type="text"name="table_name" value="<?php echo $row['name'];?>">
		<input type="submit" name="edit_Done" value="Done">
		</form>
		</div>
		</li>
		</ul>
		
		
		
		
		<?php }
	
	include_once("inc/footer.inc.php");
?>
