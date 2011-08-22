<?php
	include_once("inc/dbcon.inc.php");
	
	include_once("inc/checklogin.inc.php");
	
	
	include_once("inc/header.inc.php");
	require_once("inc/class.dbo.php");
	$obj = new DBO();
	$get = $obj->protectXSS($_GET);
	
	$table='spssp_user';
	$where = " 1=1 ";
	$data_per_page=10;
	$current_page=(int)$_GET['page'];
	
	$redirect_url = 'users.php';	
	
	$pageination = $obj->pagination($table, $where, $data_per_page,$current_page,$redirect_url);
	
	
?>
<style type="text/css">
#page
{
width:950px;
}
</style>
<div id="nav">
	<a href="manage.php" >Home</a>  &raquo; Users
</div>
<?php	
	//echo "<p style='text-align:left; text-indent:10px'><a href='create_user.php?page=".$current_page."'>Create New User</a>";	
	if(isset($pageination))	
	echo $pageination;
	
	
	
?>
	<div style="float:left;width:98%; padding:1%; overflow:auto">
	<table width="150%" align="center" cellspacing="0" cellpadding="3" border="0" class="grid"  style="overflow:auto" >
    	<tr class="head">
     		<td width="5%">#</td>
			<td width="10%">User Name</td>
            <td width="10%">Password</td>
        	<td width="6%">Man Name</td>
            <td width="9%">Woman Name</td>
            
            <td  width="5%"> Marriage Day</td> 
            <td width="15%">Marriage day with time</td>  
            <td width="15%">Party day with time</td>
            <td width="7%">Room</td>
            <td width="8%">Party Room</td>  
            <td width="5%">Religion</td>  
            <td width="15%">Contact Name</td>  
            <td width="5%">Zip</td>  
            <td width="15%">Address</td>  
            <td width="5%">Fax</td>  
            <td width="5%">Phone</td>
            <td width="10%">Mail</td>
           
            <td width="5%">Stuff ID</td>
            <td width="5%">Registration Date</td>
            <td width="5%">Condition</td>
            <td width="5%">Status</td>
          	
			
			<td valign="middle" width="5%" nowrap="nowrap">削除</td>
		</tr>
    <?php	
		
		
		$query_string="SELECT * FROM spssp_user  ORDER BY creation_date DESC LIMIT ".((int)($current_page)*$data_per_page).",".((int)$data_per_page).";";
		$data_rows = $obj->getRowsByQuery($query_string);
$i=0;
$j=1;
		foreach($data_rows as $row)
		{	
			$roomname =  $obj->GetSingleData(" spssp_room", " name", " id=".(int)$data_rows['room_id']);
			$party_roomname = $obj->GetSingleData(" spssp_room", " name", " id=".(int)$data_rows['party_room_id']);
	
			include("inc/main_dbcon.inc.php");
			$man_respect = $obj->GetSingleData(" dev2_main.spssp_respect", " title", " id=".(int)$data_rows['man_respect_id']);
			$woman_respect = $obj->GetSingleData(" dev2_main.spssp_respect", " title", " id=".(int)$data_rows['woman_respect_id']);
			include("inc/return_dbcon.inc.php");	
	?>
		<tr <?php if($i%2==0){?>style="background:#FFFEEA"<?php }else{?>style="background:#FFFFDA"<?php }?>>
        	<td><?=$j;?></td>
			<td><a href="user_log.php?user_page=<?=(int)$_GET['page']?>&user_id=<?=$row['id']?>"><?=$row['user_id']?></a></td> 
            <td><?=$row['password']?></td> 
        	<td><?=$row['man_firstname'].' '.$row['man_lastname'].' '.$man_respect?></td>
            <td><?=$row['woman_firstname'].' '.$row['woman_lastname'].' '.$woman_respect?></td>
            <td><?=$row['marriage_day']?></td>  
            <td><?=$row['marriage_day_with_time']?></td>  
            <td><?=$row['party_day_with_time']?></td>
            <td><?=$row['roomname']?></td>
            <td><?=$row['party_roomname']?></td>
            <td><?=$row['religion']?></td>  
            <td><?=$row['contact_name']?></td>  
            <td><?=$row['zip']?></td>  
            <td><?=$row['address']?></td>  
            <td><?=$row['fax']?></td>  
            <td><?=$row['phone']?></td>  
            <td><?=$row['mail']?></td> 
          
            <td><?=$row['stuff_id']?></td> 
            <td><?=date("Y-m-d H:i:s",$row['creation_date'])?></td> 
            <td><?=$row['status']?></td> 
            <td><?php 	
					if($row['is_activated']==1)
						echo "Active";
					else
						echo "Inactive";
				?>
            </td> 
            <!--<td><a href="user_edit.php?id=<?=$row['id']?>">編集</a></td>-->
            <td><a href="javascript:void(0);" onClick="confirmDelete('frontusers.php?page=<?=(int)$_GET['page']?>&action=delete&id=<?=$row['id']?>');">削除</a></td>
        </tr>
        <?php
       $i++;$j++; }
		?>
	</table>
    </div>
<?php
	
	include_once("inc/footer.inc.php");
?>
