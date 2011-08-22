<?php
@session_start();
include_once('../inc/dbcon.inc.php');
include_once('../inc/class.dbo.php');
$obj = new DBO();
$post = $obj->protectXSS($_POST);


$tname = $post['tnane'];
$tid  = $post['id'];
$arr['name'] =$tname;







if(isset($tid) && $tid > 0 && $tname!="")
{

echo $rrr=$obj->UpdateData("spssp_default_plan_table", $arr, " id = $tid");

}

?>

