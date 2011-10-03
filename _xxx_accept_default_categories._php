<?php
session_start();
include("admin/inc/dbcon.inc.php");
include("admin/inc/class.dbo.php");
$obj = new DBO();

$catid = (int)$_POST['catid'];
$row = $obj->GetSingleRow("spssp_guest_category", " id= $catid");

$row['user_id']= (int)$_SESSION['userid'];
$row['default_cat_id'] = $row['id'];

unset($row['id']);

$lastid = $obj->InsertData("spssp_guest_category",$row);

if($lastid <= 0)
{
echo 0;
}
else
{
	$query_string="SELECT * FROM spssp_guest_category where user_id=".(int)$_SESSION['userid']."  ORDER BY display_order DESC";
	$data_rows = $obj->getRowsByQuery($query_string);
}
?>

<table width="100%" align="center" cellspacing="0" cellpadding="3" border="0" class="grid" >
	<?php
        $i=1;
        foreach($data_rows as $row)
        {		
    ?>
        <tr class="table_row" <?php if($i%2==0){?>style="background:#FFFEEA"<?php }else{?>style="background:#E9E9E9"<?php }?>>
            <td width="10%"><span id="<?php echo $row['id'];?>"><?php echo $i;?></span></td>
            <td width="20%"><a href="guest_sub_cat.php?cat_id=<?php echo $row['id'];?>"><?php echo $row['name'];?></a></td>
            <td width="40%" align="center"><?php echo $row['description'];?></td> 
            <td width="30%" align="center">
                <a href="#" id="test2<?php echo $row['id'];?>" onclick="edit_item(<?php echo $row['id'];?>);">Edit</a>	
                &nbsp;&nbsp;<a href="my_guests.php?cat_id=<?php echo $category_id;?>&action=delete">Delete</a>
            </td>

        </tr>
        
        <tr>
            <td  colspan="4">
                <div id="edit<?php echo $row['id'];?>" style="display:none;border:1px solid #ccc;padding:20px;">		
                    <form action="my_guests.php#<?php echo $row['id'];?>" method="post">
                        <input type="hidden" name="cat_id" value="<?php echo $row['id'];?>">
                        Name:<br /><input type="text" name="cat_name" value="<?php echo $row['name'];?>"><br />
                        Decription:<br /><textarea name="description" cols="50" rows="3"><?php echo $row['description'];?></textarea><br />
                        <input type="submit" name="Edit" value="Edit">
                    </form>
                </div>
            </td>
        </tr>
<?php
    $i++;
 }
?>
</table>
