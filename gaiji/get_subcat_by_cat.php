<?php
include('gaijidmin/inc/dbcon.inc.php');


	$catId = $_REQUEST['catId'];
	
	$sql="SELECT gaiji_option.id, gaiji_option.g_image_name
	FROM gaiji_option
	JOIN gaiji_buso ON gaiji_option.buso_id = gaiji_buso.buso_id
	WHERE gaiji_buso.buso_id = '".$catId."'";
	
	$result=mysql_query($sql);
	$countSearchRes = mysql_num_rows($result);
	

?>
 
<?php  if((int)$countSearchRes>0) { ?> 
<table width="276" cellspacing="0" cellpadding="0" border="0" align="center">
	<tr>
		<td>
			<table>

	  					<tr>
						
						
	  <?php
	  	
	  		$count=0;
				
	  	    while($row=mysql_fetch_array($result)){
			
			if($count == 5)
			   {
				$count = 0;
				echo "</tr> <tr>";
				}
		
		 ?> 	
						
										
				<?php echo  '<td width="52" valign="middle" height="52" bgcolor="#FFFFFF" align="center">'; ?>
						<a href="javascript:void(0);" onclick="showImageBox(<?=$row['id']?>);">
						<img width="50" height="50" border="1" id="search_ans" name="検索結果" alt="検索結果" src="../../gaiji-image/img_ans/<?=$row['g_image_name']?>">
						</a>
			    <?php echo "</td>"; ?>
				

				<?php
		
				$count++;
				} //end while($row=mysql_fetch_assoc($result))
					?>						
				
						</tr>
  					
				
				
					
			</table>
		</td>
	</tr>
</table>
<?php  } //end of if($countSearchRes>0)?>
