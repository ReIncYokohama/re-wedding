<?php
@session_start();
include_once("../admin/inc/class.dbo.php");
$obj = new DBO();
$tid = (int)$_POST['tid'];
$sql = @"SELECT pt.id FROM `spssp_plan_details` pd
inner join spssp_plan pl on pd.plan_id = pl.id
inner join spssp_default_plan_seat st on pd.seat_id=st.id 
inner join spssp_default_plan_table pt on st.table_id = pt.id 
where pl.user_id = ".(int)$_SESSION['userid']." and pt.id=".$tid;
$result = mysql_query($sql);
$data = mysql_fetch_assoc($result);
if(isset($data) && $data['id'] > 0)
{
	echo 1;
}
else
{
	echo 0;
}
?>