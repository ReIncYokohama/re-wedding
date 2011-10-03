<?php
@session_start();
include("admin/inc/dbcon.inc.php");
include("admin/inc/class.dbo.php");
$obj = new DBO();
$get = $obj->protectXSS($_GET);

include("inc/new_header.php");
$obj = new DBO();


$cat_id=$_GET['cat_id'];
if(!$cat_id)
{
$cat_id=$_POST['cat_id'];
}
$sub_cat_id=$_GET['sub_cat_id'];
if(!$sub_cat_id)
{
$sub_cat_id=$_POST['sub_cat_id'];
}

$subcat_row = $obj->GetSingleRow("spssp_guest_sub_category", " id=".$sub_cat_id);
if($subcat_row['default_sub_cat_id'] > 0)
{
	$guest_row = $obj->GetSingleRow("spssp_guest", " sub_category_id=".$subcat_row['default_sub_cat_id']." and user_id=".(int)$_SESSION['userid']);
	if($guest_row['id'] > 0)
	{
		$arr['sub_category_id'] = $sub_cat_id;
		$obj->UpdateData("spssp_guest", $arr, " sub_category_id=".$subcat_row['default_sub_cat_id']." and user_id=".(int)$_SESSION['userid']);
	}
}

if($_POST{'Insert'})
	{
		$post['user_id']=$_SESSION['userid'];
		$post['sub_category_id']=$sub_cat_id;
		$post['name']=$_POST['sub_cat_name'];
		$post['description']=$_POST['description'];
		$post['display_order']= time();
		$post['creation_date'] = date("Y-m-d H:i:s");
		$post['self'] = 1;
		$lastid = $obj->InsertData('spssp_guest',$post);
		
		redirect("guest_page.php?cat_id=$cat_id&sub_cat_id=$sub_cat_id");
		
	}
	if($_POST{'Edit'})
	{
		$post['user_id']=$_SESSION['userid'];
		$post['name']=$_POST['sub_cat_name'];
		$post['description']=$_POST['description'];
		$where=" id=".$_POST['itemId'];
		$lastid = $obj->UpdateData('spssp_guest',$post,$where);
		redirect("guest_page.php?cat_id=$cat_id&sub_cat_id=$sub_cat_id");
		
	}
	if($_GET{'action'}=="delete")
	{
		$where=" id=".$_GET['itemId'];
		$lastid = $obj->DeleteRow('spssp_guest',$where);
		redirect("guest_page.php?cat_id=$cat_id&sub_cat_id=$sub_cat_id");
		
	}
	
	$menu_groups = $obj->GetAllRowsByCondition("spssp_menu_group"," user_id=".(int)$_SESSION['userid']);
	
	$gift_groups = $obj->GetAllRowsByCondition("spssp_gift_group"," user_id=".(int)$_SESSION['userid']);
	
	$planid = $obj->GetSingleData("spssp_plan", "id"," user_id=".(int)$_SESSION['userid']);
	
	$final_guests = $obj->getRowsByQuery("select guest_id from  spssp_plan_details where  plan_id=".$planid);
	
	$guest_ids = array();
	foreach($final_guests as $fg)
	{
		$guest_ids[] = $fg['guest_id'];
	}
	
	
	$query_string="SELECT * FROM spssp_admin_gift_group  ORDER BY displayorder DESC";
	$gift_group = $obj->getRowsByQuery($query_string);
	
	$query_string="SELECT * FROM spssp_gift_group_relation where user_id=".(int)$_SESSION['userid'];
    $relation_rows = $obj->getRowsByQuery($query_string);
	

?>
<script type="text/javascript">
$(function(){
$("#top_text").html("<ul id='menu'> <li><a href='dashboard.php'>Home</a></li>  <li><h2> > My Guests</h2></li> <li><a href='logout.php'>Log Out</a></li>   </ul>");
});
</script>
<link rel="stylesheet" href="css/base/jquery.ui.all.css">

<script src="js/jquery-1.4.2.js"></script>
<script src="js/external/jquery.bgiframe-2.1.1.js"></script>
<script src="js/ui/jquery.ui.core.js"></script>
<script src="js/ui/jquery.ui.widget.js"></script>
<script src="js/ui/jquery.ui.mouse.js"></script>
<script src="js/ui/jquery.ui.button.js"></script>
<script src="js/ui/jquery.ui.draggable.js"></script>
<script src="js/ui/jquery.ui.position.js"></script>
<script src="js/ui/jquery.ui.resizable.js"></script>
<script src="js/ui/jquery.ui.dialog.js"></script>
<script src="js/ui/jquery.effects.core.js"></script>
<script src="js/ui/jquery.effects.blind.js"></script>
<script src="js/ui/jquery.effects.fade.js"></script>

<link rel="stylesheet" href="css/jquery.treeview.css" />
<script src="js/jquery.treeview.js" type="text/javascript"></script>



<script src="js/guests.js" type="text/javascript"></script>
<link href="css/choose_plan.css" rel="stylesheet" type="text/css" />


<?php

$category_name = $obj->GetSingleData("spssp_guest_category","name","id=".$cat_id);
$sub_category_name = $obj->GetSingleData("spssp_guest_sub_category","name","id=".$sub_cat_id);

?>
<div>
Category : <a href="my_guests.php#<?php echo $cat_id;?>" > <?=$category_name;?> </a>&nbsp;&nbsp;&nbsp;Sub-Category :  <a href="guest_sub_cat.php?cat_id=<?php echo $cat_id;?>#<?php echo $sub_cat_id;?>" > <?=$sub_category_name;?> </a>
</div>

<style>
p { background:#dad;
font-weight:bold;
font-size:16px; }

</style>


<a href="#" id="test1">Add Guest</a>
<div id="test" style="display:none;border:1px solid #ccc;padding:20px;margin:20px 0;">

<form action="guest_page.php?sub_cat_id=<?php echo $sub_cat_id;?>" method="post">
	<input type="hidden" name="cat_id" value="<?php echo $cat_id;?>">
	Name:<br /><input type="text" name="sub_cat_name" value=""><br />
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
		
		
		$query_string="SELECT * FROM spssp_guest where sub_category_id=".$sub_cat_id." and user_id=".(int)$_SESSION['userid']." ORDER BY display_order DESC";
		$data_rows = $obj->getRowsByQuery($query_string);
		//echo "<pre>";
		//print_r($data_rows);exit;
		$i=1;
		foreach($data_rows as $row)
		{		
	?>
		<tr class="table_row" <?php if($i%2==0){?>style="background:#FFFEEA"<?php }else{?>style="background:#FFFFDA"<?php }?>>
			<td width="10%"><?php echo $i;?></td>
        	<td width="20%"><?php echo $row['name'];?></td>
         	<td width="40%" align="center"><?php echo $row['description'];?></td>
            <td width="30%" align="center">
            	<?php 
					if(in_array($row['id'],$guest_ids))
					{
				?> 
            	<a href="javascript:void(0);" onclick="guest_menus(<?php echo $row['id'];?>)">Menus</a> &nbsp; 
                <a href="javascript:void(0);" onclick="guest_gifts(<?php echo $row['id'];?>)">Gifts</a> &nbsp;
                <?php
					}
				?>
                <a href="#" id="test2<?php echo $row['id'];?>" onclick="edit_item(<?php echo $row['id'];?>);">Edit</a>&nbsp;&nbsp;
            	<a onclick="confirmDelete('guest_page.php?cat_id=<?php echo $cat_id;?>&sub_cat_id=<?php echo $sub_cat_id;?>&itemId=<?php echo $row['id'];?>&action=delete')" href="javascript:void(0);">Delete </a>
            </td>


		</tr>
		<tr>
			<td  colspan="4">
				<div id="edit<?php echo $row['id'];?>" style="display:none;border:1px solid #ccc;padding:20px;">
		
		<form action="guest_page.php?sub_cat_id=<?php echo $sub_cat_id;?>" method="post">
	<input type="hidden" name="itemId" value="<?php echo $row['id'];?>">
	<input type="hidden" name="cat_id" value="<?php echo $cat_id;?>">
	Name:<br /><input type="text" name="sub_cat_name" value="<?php echo $row['name'];?>"><br />
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

function generateGift(value)
{
	
	if(value!="")
	{
	
	
	    $.post('generate_giftarray.php',{'group_id':value}, function(data) {
	      
			$('#gift_array').html(data);
			
		});
	
	}
	else
	{
		$("#gift_array").html("None");
	}

} 
</script>
<?php
include("inc/new_footer.php");
?>

<div id="gift_dialog"  title="Please choose Menu for the Guset">
	<?php if(count($relation_rows)>0) {?>
	<form action="#" name="guest_form" id="guest_form">
    <input type="hidden" id="guest_id" value="" />
    <input type="hidden" id="gift_edit" value="0" />
    <table><tr>
    <td>Select Group&nbsp;:&nbsp;</td>
    <td><select name="group_id" id="group_id" onchange="generateGift(this.value)">
    <option value="">None</option>
    <?php 
		if(isset($gift_groups))
		{
			foreach($gift_group as $group)
			{
		?>
        		<option value="<?=$group['id']?>"><?=$group['name']?></option>
    <?php
			}
		}	
	?>    
    </select></td>
    </tr>
    <tr>
    <td colspan="2" id="gift_array">None</td>
    </tr>
    </table>
    </form>
    <?php } else { ?>
    
    <div style="background:#FF0033; color:#000000; font-weight:bold;">Please Manage Your Gift!!</div>
    <?php } ?>

</div>

<!-- Menu relationship-->
<div id="menu_dialog"  title="Please choose Menu for the Guset">
	<form action="#" name="guest_form_menu" id="guest_form_menu">
    <input type="hidden" id="guest_menu_id" value="" />
    <input type="hidden" id="guest_menu_edit" value="0" />
	<ul id="menu_menu">
    	<?php 
		if(isset($menu_groups))
		{
			foreach($menu_groups as $mg)
			{
		?>
        		<li><?=$mg['name']?>
                <?php
                	$menu_items = $obj->GetAllRowsByCondition("spssp_menu"," menu_group_id=".(int)$mg['id']);
					//print_r($gift_items);
					if(isset($menu_items))
					{
				?>
						<ul id="sub_menu_menu">
                        	<?php
								foreach($menu_items as $mt)
								{
								?>
                                	<li><input type="checkbox" name="menu_item_id" id="menu_item_<?=$mt['id']?>" value="<?=$mt['id']?>" /> &nbsp;<?=$mt['name']?></li>
                                <?php
								}
							?>
                        </ul>
				<?php
                	}
                ?>
                </li>
        <?php
			}
		}
		?>
    </ul>
    </form>

</div>
<!-- end of form-->
