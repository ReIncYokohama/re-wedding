<?php
include("admin/inc/dbcon.inc.php");
require_once("admin/inc/class.dbo.php");
$obj = new DBO();
$post = $obj->protectXSS($_POST);
$mail = $post['email'];
$nm = $obj->GetNumRows("spssp_user"," mail='".$mail."'");

if($nm > 0)
{
	echo "1";
}
else
{
	echo "0";
}
?>
