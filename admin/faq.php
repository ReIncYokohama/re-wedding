<?php
	include_once("inc/dbcon.inc.php");
	
	include_once("inc/checklogin.inc.php");
	
	
	include_once("inc/header.inc.php");
	require_once("inc/class.dbo.php");
	$obj = new DBO();
	$table='spssp_admin_faq';
	$where = " 1=1";
	$data_per_page=10;
	$current_page=(int)$_GET['page'];
	$redirect_url = 'faq.php';
	
	$pageination = $obj->pagination($table, $where, $data_per_page,$current_page,$redirect_url);
	

	
	if($_GET['action']=='delete' && (int)$_GET['id'] > 0)
	{
		$sql = "delete from spssp_admin_faq where id=".(int)$_GET['id'];
		mysql_query($sql);
		redirect('faq.php?page='.$_GET['page']);
	}
?>
<div id="nav">
	<a href="manage.php" >Home</a>  &raquo; <a href="faq.php?page=<?=(int)$_GET['page']?>">Faqs</a>  
</div>
<div style="float:left;width:100%;">
	<ul>
		<li style="float:left;list-style:none;padding-right:10px;"><a href='faq_entry.php'>New Faq Entry</a></li>
		
	</ul>

</div>

<h2>FAQS</h2>

<?php echo $pageination;?>
	<table width="100%" align="center" cellspacing="0" cellpadding="3" border="0" class="grid" >
    	<tr class="head">
     
        	<td colspan="2"></td>
                              
			<td valign="middle" width="5%" nowrap="nowrap">編集</td>
			<td valign="middle" width="5%" nowrap="nowrap">削除</td>
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
           
           
            
           
            <td rowspan="2"><a href="faq_edit.php?id=<?=$row['id']?>&page=<?=(int)$_GET['page']?>">編集</a></td>
            <td rowspan="2"><a href="javascript:void(0);" onClick="confirmDelete('faq.php?page=<?=(int)$_GET['page']?>&action=delete&id=<?=$row['id']?>');">削除</a></td>
			
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

