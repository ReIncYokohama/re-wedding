<?php
include("admin/inc/dbcon.inc.php");
include("admin/inc/class.dbo.php");
$obj = new DBO();
$get = $obj->protectXSS($_GET);

include("inc/new_header.php");
$obj = new DBO();

$query_string="SELECT * FROM spssp_admin_gift  ORDER BY displayorder DESC";
$gift_rows = $obj->getRowsByQuery($query_string);
$query_string="SELECT * FROM spssp_admin_gift_group  ORDER BY displayorder DESC";
$data_rows = $obj->getRowsByQuery($query_string);

	
if($_POST['submitok']=='OK')
{
 $post = $obj->protectXSS($_POST);
 $sql = "delete from spssp_gift_group_relation where user_id=".(int)$_SESSION['userid'];
 mysql_query($sql);
 foreach($data_rows as $value)
	{
		$postvalue="group_".$value[id];
		$insert_string['gift_id']=implode("|",$post[$postvalue]);
		$insert_string['user_id']=(int)$_SESSION['userid'];
		$insert_string['group_id']=$value[id];
		$obj->InsertData("spssp_gift_group_relation",$insert_string);
	
	}
}

$query_string="SELECT * FROM spssp_gift_group_relation where user_id=".(int)$_SESSION['userid'];
$relation_rows = $obj->getRowsByQuery($query_string);
   
   if(count($relation_rows)>0)
   	{
		foreach($relation_rows as $value)
		{
			$realtion_array['group_'.$value['group_id']]=explode("|",$value['gift_id']);
		}
		 
		$query_string="SELECT * FROM spssp_guest_gift where user_id=".(int)$_SESSION['userid'];
		$relation_guest = $obj->getRowsByQuery($query_string);
		foreach($relation_guest as $value)
		{
			if(!in_array($value['gift_id'],$realtion_array['group_'.$value['group_id']]))
			{
				$delete_gift="DELETE from spssp_guest_gift where id=".(int)$value['id'];
				mysql_query($delete_gift);
				
			
			}	
		
		}
		
	}
	else
	{
			foreach($data_rows as $value)
			{
			  $realtion_array['group_'.$value['id']]=array();
			}
	}	
	

?>
<link href="css/choose_plan.css" rel="stylesheet" type="text/css" />
<h2>Gifts Count</h2>
<div style="border:1px solid #ccc;padding:20px;margin:20px 0;">
<h3>Gifts Group</h3>
<table width="100%" align="center" cellspacing="0" cellpadding="3" border="0" class="grid" >
    	<tr class="head">		
			<?php
            	$count=0;
			foreach($data_rows as $row)
				{
			?>
            <td align="center" style="text-align:center"><b><?=$row['name']?></b></td>
            <?php
				}
			?>
          </tr> 
          <tr style="background:#FFFEEA">
          <?php
            	
			foreach($data_rows as $row)
				{
					
					$query="select distinct(guest_id) from spssp_guest_gift where group_id='".$row[id]."'";
					$result=mysql_query($query);
					$count=mysql_num_rows($result);
			?>
            		<td align="center" style="text-align:center"><b><?=$count?></b></td>
            <?php
				}
			?>
          
          </tr>
  </table>
  <h3>Gifts Item</h3> 
  
  <table width="100%" align="center" cellspacing="0" cellpadding="3" border="0" class="grid" >
    	<tr class="head">		
			<?php
            	$count=0;
			foreach($gift_rows as $gift_name)
				{
			?>
            <td align="center" style="text-align:center"><b><?=$gift_name['name']?></b></td>
            <?php
				}
			?>
          </tr> 
          <tr style="background:#FFFEEA">
          <?php
            	
			foreach($gift_rows as $gift_name)
				{
					
					$query="select * from spssp_guest_gift where gift_id='".$gift_name[id]."'";
					$result=mysql_query($query);
					$count=mysql_num_rows($result);
			?>
            		<td align="center" style="text-align:center"><b><?=$count?></b></td>
            <?php
				}
			?>
          
          </tr>
  </table>
  </div>        


<h2>Gifts Groups</h2>


<script type="text/javascript">
$(function(){
$("#top_text").html("<ul id='menu'> <li><a href='dashboard.php'>Home</a></li>  <li><h2> > Guest Gifts</h2></li> <li><a href='logout.php'>Log Out</a></li>   </ul>");
});
</script>

<script type="text/javascript">
var count=<?=count($data_rows)?>;





function validForm()
{
	

	for(var i=0;i<count;i++)
	{
		if (!isCheckedById("group_"+i)) 
		{ 
			alert ("Please select at least one item for all group"); 
			return false; 
		} 
	}
	document.gift_form.submit();	
}

  function isCheckedById(id) 
    { 
        var checked = $("input[@id="+id+"]:checked").length; 
        if (checked == 0) 
        { 
            return false; 
        } 
        else 
        { 
            return true; 
        } 
    } 

</script>
<?php
if(count($relation_rows)>0)
{
?>
<div style="border:1px solid #ccc;padding:20px;margin:20px 0;">
<table width="100%" align="center" cellspacing="0" cellpadding="3" border="0" class="grid">
<?php
$i=0;
foreach($data_rows as $group)
{
?>
<tr <?php if($i%2==0){?>style="background:#FFFEEA"<?php }else{?>style="background:#FFFFDA"<?php }?>><td><?=$group['name'];?></td>
<td><table cellspacing="2" cellpadding="5" border="0"><tr>
<?php
$query_string="SELECT gift_id FROM spssp_gift_group_relation where user_id=".(int)$_SESSION['userid']." and group_id=".$group[id];
$result_gift= $obj->getRowsByQuery($query_string);


foreach($result_gift as $value)
{
 $gift_id=explode("|",$value['gift_id']);
 foreach($gift_id as $gift)
	{

$gift_name = $obj->GetSingleData("spssp_admin_gift","name","id=".$gift['gift_id']);
?>
<td><?=$gift_name?></td>
<?php
 }
}
?>
</tr></table></td>
</tr>
<?php
$i++;
}
?>
</table>
</div>
<?php
}
?>

<form action="gifts.php" method="post" name="gift_form">
<div style="border:1px solid #ccc;padding:20px;margin:20px 0;">
<table width="100%" align="center" cellspacing="0" cellpadding="3" border="0" class="grid" >
    	<tr class="head">
     		<td align="center" style="text-align:center"><b>Item Name</b></td>
            <?php
            	
			foreach($data_rows as $row)
				{
			?>
            
        	<td align="center" style="text-align:center"><b><?=$row['name']?></b></td>
            <?php
				}
			?>
            
		</tr>
		<tr>
		<?php
		
       
		$i=0;
		foreach($gift_rows as $gift)
				{
			?>
            <tr <?php if($i%2==0){?>style="background:#FFFEEA"<?php }else{?>style="background:#FFFFDA"<?php }?>>	
			<td align="center" style="text-align:center"><b><?=$gift['name']?></b></td>
            <?php
			$j=0;
            foreach($data_rows as $row)
				{
			?>
            <td align="center" style="text-align:center"><input type="checkbox" value="<?=$gift['id']?>" id="group_<?=$j?>" name="group_<?=$row['id']?>[]"<?php if(in_array($gift['id'],$realtion_array['group_'.$row['id']])) { ?> checked="checked" <?php } ?>  /></td>	
            <?php
				$j++;
                }
			?>
            
            </tr>	
			<?php
			$i++;
				}
			?>
            <tr>
            <td colspan="<?php echo count($data_rows)+1;?>">
            <input type="hidden" name="submitok" value="OK" />
            <input type="button" name="sub" value="Save" onclick="validForm();" /></td>
            </tr>		
		
		
</table>
</div>
</form>

<?php
include("inc/new_footer.php");
?>
