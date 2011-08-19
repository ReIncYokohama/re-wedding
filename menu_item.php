<?php
include("admin/inc/dbcon.inc.php");
include("admin/inc/class.dbo.php");
$obj = new DBO();
$get = $obj->protectXSS($_GET);

include("inc/new_header.php");
$obj = new DBO();


$group_id=$_GET['group_id'];
if(!$group_id)
{
$group_id=$_POST['group_id'];
}
if($_POST{'Insert'})
	{
		
		$post['menu_group_id']=$group_id;
		$post['name']=$_POST['menu_name'];
		$post['remarks']=$_POST['remarks'];
		$post['price']=$_POST['price'];
		$lastid = $obj->InsertData('spssp_menu',$post);
		redirect("menu_item.php?group_id=$group_id&insert=success");
		
	}
if($_POST{'Edit'})
	{
		
		$post['name']=$_POST['menu_name'];
		$post['remarks']=$_POST['remarks'];
		$post['price']=$_POST['price'];
		$where=" id=".$_GET['id'];
		$lastid = $obj->UpdateData('spssp_menu',$post,$where);
		redirect("menu_item.php?group_id=$group_id&edit=success");
		
	}	
	if($_GET{'action'}=="delete")
	{
		echo $where=" id=".$_GET['id'];;
		$lastid = $obj->DeleteRow('spssp_menu',$where);
		redirect("menu_item.php?group_id=$group_id&delete=success");
		
	}

?>
<link href="css/choose_plan.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">
$(function(){
$("#top_text").html("<ul id='menu'> <li><a href='dashboard.php'>Home</a></li>  <li><h2> > Menu Items</h2></li> <li><a href='logout.php'>Log Out</a></li>   </ul>");
});
</script>
<?php

 $menu_group_name = $obj->GetSingleData("spssp_menu_group","name","id=".$group_id);

?>

<div>
<h2>Menu Group : <a href="menu_group.php#<?php echo $group_id;?>"><?php echo $menu_group_name;?></a></h2>
</div>

<a href="#" id="test1">Add Menu Item</a>
<div id="test" style="display:none;border:1px solid #ccc;padding:20px;margin:20px 0;">

<form action="menu_item.php?group_id=<?php echo $group_id;?>" method="post">
	
	Name:<br /><input type="text" name="menu_name" value=""><br />
	Price:<br /><input type="text" name="price" value=""><br />
	Remarks:<br /><textarea name="remarks" cols="50" rows="3"></textarea><br />
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
			<td width="20%"><b>Remarks</b></td>
            <td width="30%"  align="center"><b>Action</b></td> 
            
		</tr>
		
		
		<tr>
		<td colspan="4">
<div style="border:1px solid #ccc;height:400px;overflow: auto;">
	<table width="100%" align="center" cellspacing="0" cellpadding="3" border="0" class="grid" >
		<?php	
		
		
		$query_string="SELECT * FROM spssp_menu where menu_group_id=".$group_id." ORDER BY id DESC";
		$data_rows = $obj->getRowsByQuery($query_string);
		//echo "<pre>";
		//print_r($data_rows);exit;
		$i=1;
		foreach($data_rows as $row)
		{		
	?>
		<tr class="table_row" <?php if($i%2==0){?>style="background:#FFFEEA"<?php }else{?>style="background:#FFFFDA"<?php }?>>
			<td width="10%"><span id="<?php echo $row['id'];?>"><?php echo $i;?></td>
        	<td width="20%"><?php echo $row['name'];?></td>
         	<td width="40%" align="center"><?php echo $row['remarks'];?></td> 
            <td width="30%" align="center">
                <a href="#" id="test2<?php echo $row['id'];?>" onClick="edit_item(<?php echo $row['id'];?>);">Edit</a>&nbsp;&nbsp;
                <a href="javascript:void(0);" onClick="confirmDelete('menu_item.php?group_id=<?php echo $group_id;?>&id=<?php echo $row['id'];?>&action=delete');">Delete</a>
            </td>
		</tr>
		<tr>
			<td  colspan="4">
				<div id="edit<?php echo $row['id'];?>" style="display:none;border:1px solid #ccc;padding:20px;">
		
					<form action="menu_item.php?id=<?php echo $row['id'];?>" method="post">
						<input type="hidden" name="group_id" value="<?php echo $group_id;?>">
                        Name:<br><input type="text" name="menu_name" value="<?php echo $row['name'];?>"><br>
                        Price:<br><input type="text" name="price" value="<?php echo $row['price'];?>"><br>
                        Remarks:<br /><textarea name="remarks" cols="50" rows="3"><?php echo $row['remarks'];?></textarea><br />
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

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=shift_jis" />
<title>Untitled Document</title>
</head>

<body>
</body>
</html>
