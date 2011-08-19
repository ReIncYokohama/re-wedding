<?php
require_once("../inc/class.dbo.php");
include_once("../inc/checklogin.inc.php");
$obj = new DBO();

$catid = (int)$_POST['catid'];
$user_id=(int)$_POST['user_id'];
$row = $obj->GetSingleRow("spssp_guest_category", " id= $catid");

$row['user_id']= $user_id;
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
<div class="box4">
          <table border="0" align="center" cellpadding="1" cellspacing="1">
            <tr align="center">
              <td>No.</td>
              <td><b>名前</b></td>           
              <td><b>内容</b></td>
              <td>添付</td>			  
            
            </tr>
          </table>
        </div>
<table width="100%" align="center" cellspacing="0" cellpadding="3" border="0" class="grid" >
	<?php
        $i=0;$j=$current_page*$data_per_page+1;	
        foreach($data_rows as $row)
        {			if($i%2==0)
			{
				$class = 'box5';
			}
			else
			{
				$class = 'box6';
			}	
    ?>
         <div class="<?=$class?>">
	 	<table border="0" align="center" cellpadding="1" cellspacing="1">
            <tr align="center">
              <td><?=$j?></td>
            <td><a href="guest_sub_cat.php?cat_id=<?php echo $row['id'];?>"><?php echo $row['name'];?></a></td>
            <td><?php echo $row['description'];?></td> 
            
<td><a href="#" onclick="edit_guest_cat(<?php echo $row['id'];?>);"><img src="img/common/btn_edit.gif" width="42" height="17" /></a></td>
			  <td> <a href="javascript:void(0);" onclick="confirmDelete('guest_gift.php?user_id=<?=$user_id?>&id=<?php echo $row['id'];?>&action=delete&page=<?=$current_page?>');"><img src="img/common/btn_deleate.gif" width="42" height="17" /></a>
            </td>
        </tr>
        
</table>
		</div>
		<?php $i++;$j++; }?>