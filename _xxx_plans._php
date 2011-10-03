<?php
	include_once("admin/inc/dbcon.inc.php");
	
	if(!isset($_SESSION['stuff_id']))
	{
		redirect('index.php');
	}
	
	include_once("inc/header.inc.php");
	require_once("admin/inc/class.dbo.php");
	$obj = new DBO();
	$get = $obj->protectXSS($_GET);
	$roomid = (int)$get['room_id'];
	if((int)$get['plan_id'] > 0)
	{
		unset($_SESSION['cart']);
	}
	
	$table='spssp_default_plan';
	$where = " room_id=".$roomid;
	$data_per_page=10;
	$current_page=(int)$get['page'];
	$redirect_url = 'plans.php?page='.(int)$get['page'];
	
	$pageination = $obj->pagination($table, $where, $data_per_page,$current_page,$redirect_url);
	
	if($current_page>$total_page) $current_page=$total_page;
	if($current_page<=0) $current_page=1;
?>
<script type="text/javascript">
	function checkGuests(urls)
	{
		$.post("check_guests.php",  function(data){
			var numrow = parseInt(data);
     		if(numrow <= 0)
			{
				var conf = confirm("You Don't have any Default Guest.Wanna Enter Guest Information");
				if(conf)
				{
					window.location = "guest_categories.php";
				}
			}
			else
			{
				window.location = urls;
			}
   		});
	}

	function edit_plan(plan_id, page, room_id)
	{
	
		$.post('set_session1.php', {'plan_id': plan_id,'page':page,'room_id':room_id}, function(data) {
			var urls = "default_plan.php?plan_id="+plan_id+"&room_id="+room_id+"&page="+page;
			window.location = urls;							
		});	
	}
</script>
<div id="nav">
	<a href="manage.php" >Home</a>  &raquo; <a href="rooms.php" >Rooms</a> &raquo;  Default Plans
</div>
<?php
	
	echo "<p style='text-align:left; text-indent:10px'><a href='javascript:void(0);' onclick = \"checkGuests('plan_new.php?page=".(int)$get['page']."&room_id=$roomid');\"> New Plan </a>";		
	echo $pageination;
	
	
	
	if($_GET['action']=='delete' && (int)$_GET['id'] > 0)
	{
		$sql = "delete from $table where id=".(int)$get['id'];
		mysql_query($sql);
		redirect('plans.php?page='.$get['page']);
	}
	else if($_GET['action']=='sort' && (int)$_GET['id'] > 0)
	{		
		$id = $get['id'];
		$move = $get['move'];
		$redirect = 'plans.php?room_id='.$roomid.'&page='.(int)$get['page'];
		
		$obj->sortItem($table,$id,$move,$redirect);
	}
	
?>

	<table align="center" cellspacing="0" cellpadding="3" border="0" class="grid" >
    	<tr class="head">
     
        	<td width="150px">Plan Name</td>
            <td align="center">Room Name</td> 
            <td align="center"> Row Number </td>
            <td align="center"> Table Number </td>
            <td align="center"> Seat Number </td>
            <td align="center"> Cration Date </td>
            
                       
            <td valign="middle" width="10%" nowrap="nowrap">順序変更</td>
            <td valign="middle"  width="13%"> View</td> 
			<td valign="middle" width="5%" nowrap="nowrap">編集</td>
			<td valign="middle" width="5%" nowrap="nowrap">削除</td>
		</tr>
    <?php	
		
		
		$query_string="SELECT t.*, rm.name as room FROM $table t left outer join spssp_room rm on t.room_id=rm.id where t.room_id=".$roomid." or 1=1 ORDER BY display_order DESC LIMIT ".((int)($current_page-1)*$data_per_page).",".((int)$data_per_page).";";
		$data_rows = $obj->getRowsByQuery($query_string);

		foreach($data_rows as $row)
		{
		$num_rows = $obj->GetNumRows("spssp_default_plan_details"," plan_id=".$row['id']);	
	?>
		<tr>
        	<td><?=$row['name']?></td>
            <td><?=$row['room']?></td>
            <td><?=$row['row_number']?></td>
            <td><?=$row['column_number']?></td>
            <td><?=$row['seat_number']?></td>
            <td><?=$row['creation_date']?></td>
            
            <td valign="middle" width="10%" nowrap="nowrap">
            	<a href="plans.php?action=sort&amp;move=up&amp;id=<?=$row['id']?>&amp;page=<?=(int)$get['page']?>&amp;room_id=<?=(int)$row['room_id']?>">
                	▲
                </a> &nbsp;
              	<a href="plans.php?action=sort&amp;move=bottom&amp;id=<?=$row['id']?>&amp;page=<?=(int)$get['page']?>&amp;room_id=<?=(int)$row['room_id']?>">
                	▼
                </a>
            </td>
            <td>
            	<?php
				if($num_rows > 0)
				{
				?>
            		<a href="view_default_plan.php?plan_id=<?=$row['id']?>&amp;page=<?=(int)$_GET['page']?>&amp;room_id=<?=(int)$row['room_id']?>">View</a>
                    &nbsp; <a href="javascript:void(0);" onclick="edit_plan(<?=$row['id']?>,<?=(int)$_GET['page']?>,<?=(int)$_GET['room_id']?>)">Edit plan</a>
                <?php
				}
				?>
            </td>
            <td>
            	<a href="plan_edit.php?id=<?=$row['id']?>&page=<?=(int)$_GET['page']?>&amp;room_id=<?=(int)$row['room_id']?>">
                	編集
                </a>
            </td>
            <td>
            	<a href="javascript:void(0);" onClick="confirmDelete('plans.php?page=<?=(int)$_GET['page']?>&action=delete&id=<?=$row['id']?>');">
                	削除
                </a>
            </td>
        </tr>
        <?php
        }
		?>
	</table>
<?php
	
	include_once("admin/inc/footer.inc.php");
?>
