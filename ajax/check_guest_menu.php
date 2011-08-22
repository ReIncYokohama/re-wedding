<?php
@session_start();
include_once('../admin/inc/dbcon.inc.php');
include_once('../admin/inc/class.dbo.php');
$obj = new DBO();
$post = $obj->protectXSS($_POST);

$guest_id = (int)$post['guest_id'];

$nummenus = $obj->GetNumRows("spssp_guest_menu","  guest_id= $guest_id and user_id=".(int)$_SESSION['userid']);
if($nummenus <= 0)
{
	echo "0";
}
else
{
	$rows = $obj->GetAllRowsByCondition("spssp_guest_menu","  guest_id= $guest_id and user_id=".(int)$_SESSION['userid']);
	$menu_ids = "";
	$n = count($rows);
	$i = 0;
	foreach($rows as $row)
	{
		$i++;
		if($i== $n)
		{
			$menu_ids .= $row['menu_id'];
		}
		else
		{
			$menu_ids .= $row['menu_id'].",";
		}
	}
	echo $menu_ids;

}

?>
