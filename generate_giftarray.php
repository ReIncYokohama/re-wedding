<?php
include("admin/inc/dbcon.inc.php");
require_once("admin/inc/class.dbo.php");
$obj = new DBO();
$post = $obj->protectXSS($_POST);


$query_string="SELECT * FROM spssp_gift_group_relation where user_id=".(int)$_SESSION['userid']." and group_id=".(int)$post['group_id'];
$relation_rows = $obj->getRowsByQuery($query_string);
if($post['giftstringArray']!="")
{
 $gift_array=$post[giftstringArray];
}
else
{
	$gift_array=array();
}
echo "<table>";
foreach($relation_rows as $value)
{
	$gift_id=explode("|",$value['gift_id']);
	
	foreach($gift_id as $gift)
	{
		$gift_name = $obj->GetSingleData("spssp_admin_gift","name","id=".$gift);
		
		
			
			if(in_array($gift,$gift_array))
			{
			  $checked="checked";
			}
			else
			{
			  $checked="";
			}
		
		
		
		echo "<tr><td><input type='checkbox' value='".$gift."' name='gift_id[]' id='gift_id' ".$checked.">".$gift_name."</td></tr>";
	
	}
	

}
echo "</table>";
?>
