<?php
include("admin/inc/dbcon.inc.php");
include("admin/inc/class.dbo.php");
$obj = new DBO();
$get = $obj->protectXSS($_GET);

include("inc/new_header.php");
?>
<link href="css/choose_plan.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
$(function(){
	$("ul#menu li").removeClass();
	$("ul#menu li:nth-child(2)").addClass('active');
	$("ul#menu li:nth-child(2) a").attr("href",'registration_2.php?id=<?=$get['id']?>');
	$("div#df_plan :last").css("display","none");
});
	function viewDetail(planid)
	{
		$.post('view_deails.php',{planid:planid}, function (data){
			$("#df_plan").html(data);
			$("#df_plan").append("<p style='text-align:left'>&nbsp; <br /><a href = 'choosed_default.php?id="+planid+"&user_id=<?=$get['id']?>'><b>Choose This Plan</b></p>");
		});
	}
</script>
<div style="width:100%; text-align:left"><a href="my_guests.php" style="font-weight:bold">Don't Choose Default Plan</a></div>
<div style="width:100%; ">
<?php
$table = "spssp_user";
$row = $obj->GetSingleRow($table, " id=".$get['id']);

$room_id = $row['room_id'];

$plans = $obj->GetAllRowsByCondition("spssp_default_plan", " room_id=".$room_id);

$num_plan = count($plans);
$width = (int)(970/$num_plan);

foreach($plans as $plan)
{

?>
	<div class="df_plan" style="width:<?=$width - 8?>px"> <a href="javascript:void(0);" onMouseOver="viewDetail(<?=$plan['id']?>);"><?=$plan['name']?></a></div>
<?php
}
?>

<div id="df_plan"></div>
</div>
<?php
include("inc/new_footer.php");
?>
