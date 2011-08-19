<?php
	require_once("../inc/class.dbo.php");
	include_once("../inc/checklogin.inc.php");
	$user_id=(int)$_POST['user_id'];
$obj = new DBO();

$subcat_id = (int)$_POST['subcat_id'];
$row = $obj->GetSingleRow("spssp_guest_sub_category", " id= ".$subcat_id);


$row['user_id']= $user_id;
$row['default_sub_cat_id'] = $row['id'];
$row['creation_date'] = date('Y-m-d H:i:s');
$row['display_order']= time();

unset($row['id']);

$lastid = $obj->InsertData("spssp_guest_sub_category",$row);

if($lastid <= 0)
{
echo 0;
}
else
{
	$query_string="SELECT * FROM spssp_guest_sub_category where user_id=".$user_id."  ORDER BY display_order DESC";
	$data_rows = $obj->getRowsByQuery($query_string);
}
?>

<table width="100%" align="center" cellspacing="0" cellpadding="3" border="0" class="grid" >
	<?php
        $i=1;
        foreach($data_rows as $row)
        {		
    ?>
       <tr class="table_row" <?php if($i%2==0){?>style="background:#FFFEEA"<?php }else{?>style="background:#FFFFDA"<?php }?>>
			<td width="10%"><span id="<?php echo $row['id'];?>"><?php echo $i;?></td>
        	<td width="20%"><a href="guest_page.php?cat_id=<?php echo $category_id;?>&sub_cat_id=<?php echo $row['id'];?>"><?php echo $row['name'];?></a></td>
         
            <td width="30%" align="center"><a href="#" id="test2<?php echo $row['id'];?>" onclick="edit_item(<?php echo $row['id'];?>);">Edit</a>&nbsp;&nbsp;<a href="guest_sub_cat.php?cat_id=<?php echo $category_id;?>&sub_cat_id=<?php echo $row['id'];?>&action=delete">Delete</a></td>


		</tr>
		
<?php
    $i++;
 }
?>
</table>
