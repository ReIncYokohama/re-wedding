<?php
session_start();
include("admin/inc/dbcon.inc.php");
include("admin/inc/class.dbo.php");
$obj = new DBO();

$data_rows = $obj->GetAllRowsByCondition("spssp_guest_sub_category"," category_id = ".(int)$_POST['catid']." and  user_id=0  and id not in (select default_sub_cat_id from spssp_guest_category where user_id=".(int)$_SESSION['userid'].")");

?>

<table width="100%" align="center" cellspacing="0" cellpadding="3" border="0" class="grid" >
	<?php
        $i=1;
        foreach($data_rows as $row)
        {		
    ?>
        <tr class="table_row" <?php if($i%2==0){?>style="background:#FFFEEA"<?php }else{?>style="background:#E9E9E9"<?php }?>>
            <td width="10%"><span id="<?php echo $row['id'];?>"><?php echo $i;?></span></td>
            <td width="20%"><?php echo $row['name'];?></td>
            <td width="40%" align="center"><?php echo $row['description'];?></td> 
            <td width="30%" align="center">
                <a href="#" id="test2<?php echo $row['id'];?>" onclick="acceptDefaultSubCategory(<?php echo $row['id'];?>);">Accept</a>
            </td>

        </tr>
        

<?php
    $i++;
 }
?>
</table>