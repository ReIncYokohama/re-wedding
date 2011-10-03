<?php
	require_once("../inc/class.dbo.php");
	include_once("../inc/checklogin.inc.php");

	$obj = new DBO();
	
	$row=$obj->GetSingleRow("spssp_guest",' id='.(int)$_POST['id']);
	//$comma_separated = implode("#", $data);
	$respects = $obj->GetAllRow("spssp_respect");
?>	
	
	<input type="hidden" id="id" name="id" value="<?=$row['id']?>">
			èµë“é“ñºëO<br>
			<input type="text" name="name" style="width:185px;" id="edit_name" value="<?=$row['name']?>"/> 
            <select name="respect_id">
            	<?php
                    	foreach($respects as $rsp)
						{
							if($rsp['id']==$row['respect_id'])
							{
								$sel = "Selected='Selected'";
							}
							else
							{
								$sel = "";
							}
							echo "<option value=".$rsp['id']." $sel>".$rsp['title']."</option>";
						}
					?>
           </select><br> 
           ì‡óe<br>
		   <input type="text" name="description" style="width:250px;" id="edit_description" value="<?=$row['description']?>"/><br>
	
