<?php
@session_start();
include_once("admin/inc/class.dbo.php");

$arr = $_SESSION['cart'];

foreach($arr as $key=>$val)
{
	if($val != '')
	{
		
		$val_arr = explode("_",$val);
	
		$guestid = $val_arr[1];
		$sql = "select edit_item_id from spssp_guest where id= $guestid and user_id=".(int)$_SESSION['userid'];
		$result = mysql_query($sql);
		$row = mysql_fetch_assoc($result);
		if( (int)$row['edit_item_id'] <= 0)
		{
			echo $val_arr[0];exit;
		}
		
	}
	
}

/*function checkGuset(&$value, &$key)
{
	$obj = new DBO();
	$value_arr = explode('_',$value);
	//echo $value.'sssssss';
	$guestid = $value_arr[1];
	//echo "select count(*) from spssp_guest where edit_item_id= $guestid and user_id=".(int)$_SESSION['userid'];exit;
	$sql = "select * from spssp_guest where edit_item_id= $guestid and user_id=".(int)$_SESSION['user_id']
	$numrow = $obj->GetNumRows("spssp_guest"," edit_item_id=".$guestid." and user_id=".(int)$_SESSION['userid']);
	
	if($numrow <=0)
	{
		echo $value_arr[0];
		exit;
	}

}
//array_walk_recursive($arr,'checkGuset');*/
?>