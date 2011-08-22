<?php
	require_once("../inc/class.dbo.php");
	include_once("../inc/checklogin.inc.php");
	$user_id=(int)$_POST['user_id'];
	
	$obj = new DBO();

$data_rows = $obj->GetAllRowsByCondition("spssp_guest_sub_category"," category_id = ".(int)$_POST['catid']." and  user_id=0  and id not in (select default_sub_cat_id from spssp_guest_sub_category where user_id=".$user_id.")");

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
	<?php
        $i=0;$j=$current_page*$data_per_page+1;	
        foreach($data_rows as $row)
        {	
			if($i%2==0)
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
            <td><?php echo $row['name'];?></td>
            <td><?php echo $row['description'];?></td> 
            <td>
                <a href="#" id="test2<?php echo $row['id'];?>" onclick="acceptDefaultSubCategory(<?php echo $row['id'];?>,<?=(int)$_POST['catid']?>,<?=$user_id?>);">Accept</a>
            </td>

        </tr>
</table>
		</div>
		<?php $i++;$j++; }?>
