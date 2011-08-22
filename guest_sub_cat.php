<?php
include("admin/inc/dbcon.inc.php");
include("admin/inc/class.dbo.php");
$obj = new DBO();
$get = $obj->protectXSS($_GET);

include("inc/new_header.php");
$obj = new DBO();


$category_id=$_GET['cat_id'];
if(!$category_id)
{
$category_id=$_POST['cat_id'];
}
if($_POST{'Insert'})
	{
		$post['category_id']=$category_id;
		$post['user_id'] = (int)$_SESSION['userid'];
		$post['name']=$_POST['sub_cat_name'];
		$post['display_order']= time();
		$post['creation_date'] = date("Y-m-d H:i:s");
		$lastid = $obj->InsertData('spssp_guest_sub_category',$post);
		
	}
if($_POST{'Edit'})
	{
		
		$post['name']=$_POST['sub_cat_name'];
		$where=" id=".$_GET['sub_cat_id'];
		$lastid = $obj->UpdateData('spssp_guest_sub_category',$post,$where);
		
	}	
	if($_GET{'action'}=="delete")
	{
		echo $where=" id=".$_GET['sub_cat_id'];;
		$lastid = $obj->DeleteRow('spssp_guest_sub_category',$where);
		
	}
$default_cat_id = $obj->GetSingleData("spssp_guest_category", "default_cat_id"," id = $category_id");

?>
<link href="css/choose_plan.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
$(function(){
$("#top_text").html("<ul id='menu'><li><a href='dashboard.php'>Home</a></li><li><h2>My Guests Sub Categories</h2></li><li><a href='logout.php'>Log Out</a></li> </ul>");
});

$("button").click(function () {

$("p").toggle("slow");
});    
</script>
<?php

 $category_name = $obj->GetSingleData("spssp_guest_category","name","id=".$category_id);

?>
<div>
Category : <a href="my_guests.php#<?php echo $category_id;?>"><?php echo $category_name;?></a>
</div>

<style>
p { background:#dad;
font-weight:bold;
font-size:16px; }

</style>


<a href="#" id="test1">Add Sub-Category</a><br />

<a href="guest_sub_cat.php?cat_id=<?=(int)$category_id?>">My Guest Sub Categories</a> &nbsp; &nbsp; 
<a href="javascript:void(0);" onclick="viewDefaultSubCategories(<?=$default_cat_id?>);" >View Default Sub Categories</a>

<div id="test" style="display:none;border:1px solid #ccc;padding:20px;margin:20px 0;">

<form action="guest_sub_cat.php?cat_id=<?php echo $category_id;?>" method="post">
	
	Name:<input type="text" name="sub_cat_name" value="">
	<input type="submit" name="Insert" value="Insert">
</form>
</div>


<script type="text/javascript">

$("#test1").click(function () {
$("#test").toggle("slow");
}); 

function viewDefaultSubCategories(catid)
{

	$.post('view_default_sub_categories.php',{'catid':catid}, function(data){

		$("#guest_container").hide();
		$("#guest_container").html(data);
		$("#guest_container").fadeIn(1000);
	});
}

function acceptDefaultSubCategory(subcatid)
{
	$.post('accept_default_sub_categories.php',{'catid':subcatid}, function(data){
		if(parseInt(data)==0)
		{
			alert('Acception Failed');
			return;
		}
		else
		{
			$("#guest_container").hide();
			$("#guest_container").html(data);
			$("#guest_container").fadeIn(1000);
		}
	});
}

</script>
<div style="border:1px solid #ccc;padding:20px;margin:20px 0;">
<table width="100%" align="center" cellspacing="0" cellpadding="3" border="0" class="grid" >
    	<tr class="head">
     		<td width="10%"><b>#</b></td>
        	<td width="20%"><b>Name</b></td>
            <td width="30%"  align="center"><b>Action</b></td> 
            
		</tr>
		
		
		<tr>
		<td colspan="4">
        <?php
        			
		$query_string="SELECT * FROM spssp_guest_sub_category where category_id=".$category_id." and user_id=".(int)$_SESSION['userid']." ORDER BY display_order DESC";
		$data_rows = $obj->getRowsByQuery($query_string);
		?>
<div style="border:1px solid #ccc;height:400px;overflow: auto;" id="guest_container">
	<table width="100%" align="center" cellspacing="0" cellpadding="3" border="0" class="grid" >
		<?php	
		//echo "<pre>";
		//print_r($data_rows);exit;
		$i=1;
		foreach($data_rows as $row)
		{		
	?>
		<tr class="table_row" <?php if($i%2==0){?>style="background:#FFFEEA"<?php }else{?>style="background:#FFFFDA"<?php }?>>
			<td width="10%"><span id="<?php echo $row['id'];?>"><?php echo $i;?></td>
        	<td width="20%"><a href="guest_page.php?cat_id=<?php echo $category_id;?>&sub_cat_id=<?php echo $row['id'];?>"><?php echo $row['name'];?></a></td>
         
            <td width="30%" align="center"><a href="#" id="test2<?php echo $row['id'];?>" onclick="edit_item(<?php echo $row['id'];?>);">Edit</a>&nbsp;&nbsp;<a href="guest_sub_cat.php?cat_id=<?php echo $category_id;?>&sub_cat_id=<?php echo $row['id'];?>&action=delete">Delete</a></td>


		</tr>
		<tr>
			<td  colspan="4">
				<div id="edit<?php echo $row['id'];?>" style="display:none;border:1px solid #ccc;padding:20px;">
		
		<form action="guest_sub_cat.php?sub_cat_id=<?php echo $row['id'];?>" method="post">
		<input type="hidden" name="cat_id" value="<?php echo $category_id;?>">
	Name:<input type="text" name="sub_cat_name" value="<?php echo $row['name'];?>">
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

