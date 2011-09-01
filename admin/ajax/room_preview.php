<?php
@session_start();
include_once('../inc/dbcon.inc.php');
include_once('../inc/class.dbo.php');
$obj = new DBO();

$post = $obj->protectXSS($_POST);

if(isset($post['plan_id']) && $post['plan_id'] >0)
{
	$plan_row = $obj->GetSingleRow("spssp_default_plan", " id=".$post['plan_id']);
	$default_id = $plan_row['room_id'];

	$num_tables = (int) ($plan_row['row_number'] * $plan_row['column_number']);

	$num_rows = $plan_row['row_number'];
	$num_cols = $plan_row['column_number'];

	$where = " room_id=".$default_id." limit 0, $num_tables";
}
else
{
	$default_id = (int)$post['room_id'];
	$where = " room_id=".$default_id;

	$room_row = $obj->GetSingleRow("spssp_room", " id=".$default_id);
	$num_rows = $room_row['max_rows'];
	$num_cols = $room_row['max_columns'];

	$default_layout_title = $obj->GetSingleData("spssp_options" ,"option_value" ," option_name='default_layout_title'");

}
?>

 <table width="100%" style = " text-align:center; color: black;" align="center" border="0" cellspacing="10" cellpadding="0">

	<?php

            $room_tables = $obj->GetAllRowsByCondition("spssp_default_plan_table",$where);

            $names = array();
            foreach($room_tables as $rt)
            {
//                $names[] = $rt['name'];
                $names[] = mb_substr($rt['name'], 0,1,'UTF-8');// 先頭の1文字
            }


    ?>
         <tr>
<!--             <td colspan="<?=$num_cols?>" align="center" valign="middle"><img src="img/sakiji_icon/icon_takasago.gif" width="102" height="22" /></td> -->
             <td colspan="<?=$num_cols?>" align="center" valign="middle"><div style="width:80px; text-align:center; border:1px solid;"><?=$default_layout_title?></div></td>
        </tr>
    <?php

            for($r = 1; $r <= $num_rows; $r++)
            {
                echo "<tr>";
                for($col = 1; $col <= $num_cols; $col++)
                    {
                        //echo "<td>ahad</td>";
                        echo "<td align='center' valign='middle'><div style=\"width:31px; text-align:center; height:24px; padding-top:7px; background-image:url('img/circle_small.jpg')\"><span class='tbl_name'>".array_shift($names)."</span></div></td>";
                    }
                echo "</tr>";
            }
    ?>

</table>
