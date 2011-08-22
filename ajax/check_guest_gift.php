<?php
@session_start();
include_once('../admin/inc/dbcon.inc.php');
include_once('../admin/inc/class.dbo.php');
$obj = new DBO();
$post = $obj->protectXSS($_POST);

$guest_id = (int)$post['guest_id'];

$numgifts = $obj->GetNumRows("spssp_guest_gift","  guest_id= $guest_id and user_id=".(int)$_SESSION['userid']);
if($numgifts <= 0)
{
	echo "0";
}
else
{
	$rows = $obj->GetAllRowsByCondition("spssp_guest_gift","  guest_id= $guest_id and user_id=".(int)$_SESSION['userid']);
	$gift_ids = "";
	$n = count($rows);
	$i = 0;
	foreach($rows as $row)
	{
		$i++;
		if($i== $n)
		{
			$gift_ids .= $row['gift_id'];
		}
		else
		{
			$gift_ids .= $row['gift_id'].",";
		}
		if($i==1)
		{
			$group_id=$row['group_id'];
		}
	}
	echo $gift_ids."|".$group_id;

}

?>
