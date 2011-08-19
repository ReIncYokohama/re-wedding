<?php
	include("admin/inc/dbcon.inc.php");
	include("admin/inc/class.dbo.php");
	$obj = new DBO();
	$get = $obj->protectXSS($_GET);
	
	include("inc/new_header.php");
	
	$table='spssp_admin_faq';
	$where = " 1=1";
	$data_per_page=10;
	$current_page=(int)$_GET['page'];
	$redirect_url = 'faq.php';
	
	$pageination = $obj->pagination($table, $where, $data_per_page,$current_page,$redirect_url);

?>


<h2>FAQS</h2>

<?php echo $pageination;?>
	<table width="100%" align="center" cellspacing="0" cellpadding="3" border="0" class="grid" >
    	<tr class="head">
     
        	<td colspan="2">All Faqs</td>                            
			
		</tr>
   <?php	
		
		
		$query_string="SELECT * FROM spssp_admin_faq ORDER BY display_order DESC LIMIT ".((int)($current_page)*$data_per_page).",".((int)$data_per_page).";";
		$data_rows = $obj->getRowsByQuery($query_string);
		//echo "<pre>";
		//print_r($data_rows);exit;
		$i=0;
		foreach($data_rows as $row)
		{		
	?>
		<tr <?php if($i%2==0){?>style="background:#FFFEEA"<?php }else{?>style="background:#FFFFDA"<?php }?>>
        	<td>Q.</td>
			<td><b><?=$row['question']?></b></td>		
        </tr>
		<tr <?php if($i%2==0){?>style="background:#FFFEEA"<?php }else{?>style="background:#FFFFDA"<?php }?>>
        	
           <td>A.</td>
           <td><?=$row['answare']?></td>
                     
        </tr>
        <?php
        $i++;}
		?>
	</table>
<?php
	
	include_once("inc/footer.inc.php");
?>

