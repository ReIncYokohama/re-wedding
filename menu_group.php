<?php
include("admin/inc/dbcon.inc.php");
include("admin/inc/class.dbo.php");
$obj = new DBO();
$get = $obj->protectXSS($_GET);

include("inc/new_header.php");
$obj = new DBO();

if(isset($_POST['Insert']))
	{
		$post['user_id']=$_SESSION['userid'];
		$post['name']=$_POST['menu_name'];
		$post['description']=$_POST['description'];
		
		$lastid = $obj->InsertData('spssp_menu_group',$post);
		unset($_POST['Insert']);
		redirect('menu_group.php?insert=success');
		
	}
if($_POST{'Edit'})
	{
		
		$post['name']=$_POST['menu_name'];
		$post['description']=$_POST['description'];
		$where=" id=".$_POST['id'];
		$lastid = $obj->UpdateData('spssp_menu_group',$post,$where);
		unset($_POST['Edit']);
		redirect('menu_group.php');		
	}
if($_GET{'action'}=="delete")
	{
		echo $where=" id=".$_GET['id'];;
		$lastid = $obj->DeleteRow('spssp_menu_group',$where);
		unset($_GET['action']);
		redirect('menu_group.php');		
	}
?>
<link href="css/choose_plan.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
$(function(){
$("#top_text").html("<ul id='menu'> <li><a href='dashboard.php'>Home</a></li>  <li><h2> > Guest Menus</h2></li> <li><a href='logout.php'>Log Out</a></li>   </ul>");
});
</script>

<a href="#" id="test1">Add Menu Group</a>
<div id="test" style="display:none;border:1px solid #ccc;padding:20px;margin:20px 0;">

<form action="menu_group.php" method="post">
	Name:<br /><input type="text" name="menu_name" value=""><br />
	Decription:<br /><textarea name="description" cols="50" rows="3"></textarea><br />
	<input type="submit" name="Insert" value="Insert">
</form>
</div>


<script>
$("#test1").click(function () {
$("#test").toggle("slow");
});    
</script>
<div style="border:1px solid #ccc;padding:20px;margin:20px 0;">
<table width="100%" align="center" cellspacing="0" cellpadding="3" border="0" class="grid" >
    	<tr class="head">
     		<td width="10%"><b>#</b></td>
        	<td width="20%"><b>Name</b></td>
            <td width="40%"  align="center"><b>Description</b></td> 
            <td width="30%"  align="center"><b>Action</b></td> 
            
		</tr>
		
		
		<tr>
		<td colspan="4">
<div style="border:1px solid #ccc;height:400px;overflow: auto;">
	<table width="100%" align="center" cellspacing="0" cellpadding="3" border="0" class="grid" >
		<?php	
		
		
		$query_string="SELECT * FROM spssp_menu_group where user_id =".(int)$_SESSION['userid']."  ORDER BY id DESC";
		$data_rows = $obj->getRowsByQuery($query_string);
		//echo "<pre>";
		//print_r($data_rows);exit;
		$i=1;
		foreach($data_rows as $row)
		{		
	?>
		<tr class="table_row" <?php if($i%2==0){?>style="background:#FFFEEA"<?php }else{?>style="background:#FFFFDA"<?php }?>>
			<td width="10%"><span id="<?php echo $row['id'];?>"><?php echo $i;?></span></td>
        	<td width="20%"><a href="menu_item.php?group_id=<?php echo $row['id'];?>"><?php echo $row['name'];?></a></td>
            <td width="40%" align="center"><?php echo $row['description'];?></td> 
            <td width="30%" align="center"><a href="#" id="test2<?php echo $row['id'];?>" onclick="edit_item(<?php echo $row['id'];?>);">Edit</a>&nbsp;&nbsp;
            <a href="javascript:void(0);" onclick="confirmDelete('menu_group.php?id=<?php echo $row['id'];?>&action=delete');">Delete</a>
            </td>

		</tr>
		<tr>
			<td  colspan="4">
				<div id="edit<?php echo $row['id'];?>" style="display:none;border:1px solid #ccc;padding:20px;">
		
					<form action="menu_group.php#<?php echo $row['id'];?>" method="post">
							<input type="hidden" name="id" value="<?php echo $row['id'];?>">
							Name:<br /><input type="text" name="menu_name" value="<?php echo $row['name'];?>"><br />
							Decription:<br /><textarea name="description" cols="50" rows="3"><?php echo $row['description'];?></textarea><br />
							<input type="submit" name="Edit" value="Edit">
					</form>
				</div>
			</td>
		</tr>
		<?php $i++; }?>
	</table>
</div>
</tr>
</table>
</div>
<script>
function edit_item(id){
$("#edit"+id).toggle("slow");

}   
</script>
<?php
include("inc/new_footer.php");
?>
