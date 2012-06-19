<?php
/*ini_set('display_errors', 1);
error_reporting(E_ALL);*/

require_once("inc/include_class_files.php");

$obj = new DBO();
$get = $obj->protectXSS($_GET);

require_once("inc/include_class_files.php");
$objInfo = new InformationClass();

include_once("inc/new.header.inc.php");
$default_plan_id = (int)$get['default_plan_id'];
$plan_id= (int)$get['plan_id'];
if($default_plan_id > 0)
{
	$plan_row = $obj->GetSingleRow("spssp_default_plan", " id=".(int)$default_plan_id);
}
if($plan_id > 0)
{
	$plan_row = $obj->GetSingleRow("spssp_plan", " id=".(int)$plan_id);
}


if(!isset($plan_row['id']))
{
	echo "<script type='text/javascript'>alert('Please Define Plan Criteria First'); window.location='dashboard.php';</script>";
}




$room_rows = $plan_row['row_number'];

$room_tables = $plan_row['column_number'];
$row_width = (int)((115)*$room_tables);
$room_seats = $plan_row['seat_number'];

$num_tables = $room_rows * $room_tables;
$table_rows = $obj->getRowsByQuery("select * from spssp_default_plan_table where room_id = ".$plan_row['room_id']." order by id asc limit 0, $num_tables");

if($default_plan_id >0)
{
	$num_layouts = $obj->GetNumRows("spssp_table_layout","default_plan_id= ".(int)$default_plan_id);
}
else if($plan_id > 0)
{
	$num_layouts = $obj->GetNumRows("spssp_table_layout","user_id= ".(int)$get['user_id']);
}




if($num_layouts <= 0)
{

	$row_ord = 1;
	$column_ord = 1;
	$i = 1;
	$j = 1;
	for($i = 1; $i<= (int)$room_rows; $i++)
	{

		for($j=1; $j<= (int)$room_tables; $j++)
		{
			$tr = array_shift($table_rows);

			$lo_arr['plan_id'] = $plan_row['id'];
			$lo_arr['table_id'] = $tr['id'];

			$lo_arr['visibility'] = 1;
			$lo_arr['row_order'] = $i;
			$lo_arr['column_order'] = $j;
			$lo_arr['name'] = $tr['name'];
			$lo_arr['default_plan_id'] = $default_plan_id;
			if($plan_id >0)
			{
				$lo_arr['user_id']= (int)$get['user_id'];
				unset($lo_arr['default_plan_id']);
			}

			$lid = $obj->InsertData("spssp_table_layout", $lo_arr);

		}

	}
}
if($default_plan_id >0)
{
	$tblrows = $obj->getRowsByQuery("select distinct row_order from spssp_table_layout where default_plan_id= ".(int)$default_plan_id);
}
else if($plan_id > 0)
{
	$tblrows = $obj->getRowsByQuery("select distinct row_order from spssp_table_layout where user_id= ".(int)$get['user_id']);
}

	if($_SESSION['user_type'] == 222)
	{
		$data = $obj->GetAllRowsByCondition("spssp_user"," stuff_id=".(int)$_SESSION['adminid']);
		foreach($data as $dt)
		{
			$staff_users[] = $dt['id'];
		}
		if(!empty($staff_users))
		{
			if(in_array((int)$get['user_id'],$staff_users))
			{
				$var = 1;
			}
			else
			{
				$var = 0;
			}
		}

	}
	else
	{
		$var = 1;
	}

?>

<link href="../css/choose_plan.css" rel="stylesheet" type="text/css" />

<script>
$(function(){

$(".rows").filter(":first").css("border-top","1px solid #666666");

var row_width =$(".rows").width();
var table_width = $(".tables").width();
var table_width = table_width+40;


$(".rows").each(function(){

	var rowid = $(this).attr('id');

	var ralign = $("#"+rowid+" input").val();


	if(ralign == 'C')
	{
		var count_none = 0;

		$("#"+rowid+" .tables").each(function(){



			if($(this).css('display') == 'none')
			{
				count_none++;
			}

		});

		if(count_none > 0)
		{
			var row_con_width = row_width -(table_width*count_none);

			$("#"+rowid+" .row_conatiner").css({'width':row_con_width+'px','margin':'0 auto'});
		}
	}
	if(ralign == 'L')
	{
		var count_none = 0;

		$("#"+rowid+" .tables").each(function(){



			if($(this).css('display') == 'none')
			{
				count_none++;
			}

		});

		if(count_none > 0)
		{
			var row_con_width = row_width -(table_width*count_none);

			$("#"+rowid+" .row_conatiner").css({'width':row_con_width+'px','float':'left'});
		}

	}
	if(ralign == 'R')
	{

		var count_none = 0;

		$("#"+rowid+" .tables").each(function(){



			if($(this).css('display') == 'none')
			{
				count_none++;
			}

		});

		if(count_none > 0)
		{
			var row_con_width = row_width -(table_width*count_none);

			$("#"+rowid+" .row_conatiner").css({'width':row_con_width+'px','float':'right'});
		}
	}

}

);

});
function checkGuest(tid,cid)
{
	$.post('ajax/check_plan_guest.php',{'tid':tid}, function(data){
		if(parseInt(data) > 0)
		{
			$("#"+cid).attr('checked','checked');
			alert('全て卓の招待者を削除してください');
		}
	});
}
</script>
<style>

	.tables
	{
		height:72px;
		width: 72px;
		float:left;

		background-image:url(img/circle_big.jpg);

	}

	.tables
	{

		margin:5px 20px;

	}
	.tables p
	{
		margin-top:27px;
	}

	.tables p a
	{
	text-decoration:none;
	cursor:default;

	}
</style>

<?php include_once("inc/topnavi.php");?>

<div id="container">
    <div id="contents">
    <div style="font-size:16;  width:300px;">
     	<?php
	$user_id0=$get['user_id'];
	$user_row = $obj->GetSingleRow("spssp_user"," id= $user_id0");	?>

  <?php  echo $objInfo->get_user_name_image_or_src($user_row['id'] ,$hotel_id=1, $name="man_lastname.png",$extra="thumb1",$height=20);?>
・
  <?php  echo $objInfo->get_user_name_image_or_src($user_row['id'] ,$hotel_id=1, $name="woman_lastname.png",$extra="thumb1",$width=20);?>
  様

    </div>
    <div style="width:1200px;"><h2>
            	 <a href="users.php">お客様一覧</a> &raquo; 卓レイアウト
        </h2>
	</div>
        <div class="box_table" style="width:800px;">
        	<div class="bottom_line_box">
        		<p class="txt3">会場名：<?=$plan_row['name']?>　　最大卓数：横<?=$room_rows?>列 × 縦<?=$room_tables?>段　　一卓人数：<?=$room_seats?>名</p>
        	</div>
        	<p class="txt3">会場別卓レイアウト設定：</p>
        </div>

        <div class="box_table" style="width:800px;">
        	<div class="bottom_line_box">
        		<p class="txt3"><font color="#1F52A3"><strong>卓レイアウト</strong></font></p>
        	</div>
        	<p class="txt3">卓レイアウト名:&nbsp; <?=$plan_row['name']?>
        		<br />
        		●会場の最大卓数が表示されます。不要な卓はチェックし「削除」ボタンを押してください。
            </p>
        </div>



        <div style="width:1000px; float:left; text-align:center; ">


        	<div align="center" style="width:<?=$row_width?>px; margin:0 auto;">
            	<?php
				foreach($tblrows as $tblrow)
				{
					if($default_plan_id >0)
					{
						$ralign = $obj->GetSingleData("spssp_table_layout", "align"," row_order=".$tblrow['row_order']." and default_plan_id=".(int)$default_plan_id." limit 1");
					}
					else if($plan_id > 0)
					{
						$ralign = $obj->GetSingleData("spssp_table_layout", "align"," row_order=".$tblrow['row_order']." and user_id=".(int)$get['user_id']." limit 1");
					}


				?>
    			<div class="rows" style="float:left;width:100%" id="row_<?=$tblrow['row_order']?>">
            	<input type="hidden" id="rowcenter_<?=$tblrow['row_order']?>" value="<?=$ralign?>" />

            		<div class="row_conatiner" id="rowcon_<?=$tblrow['row_order']?>">
				<?php
                	if($default_plan_id >0)
					{
                    	$table_rows = $obj->getRowsByQuery("select * from spssp_table_layout where default_plan_id = ".(int)$default_plan_id." and row_order=".$tblrow['row_order']." order by  column_order asc");
					}
					else if($plan_id > 0)
					{
						$table_rows = $obj->getRowsByQuery("select * from spssp_table_layout where user_id = ".(int)$get['user_id']." and row_order=".$tblrow['row_order']." order by  column_order asc");
					}
                    foreach($table_rows as $table_row)
                    {

                           if($default_plan_id >0)
						   {
						   		$new_name_row = $obj->GetSingleRow("spssp_user_table", "default_plan_id = ".(int)$default_plan_id." and default_table_id=".$table_row['id']);
							}
							else if($plan_id > 0)
							{
								$new_name_row = $obj->GetSingleRow("spssp_user_table", " user_id = ".(int)$get['user_id']." and default_table_id=".$table_row['id']);
							}

                            //print_r($new_name_row);//exit;
                            if(isset($new_name_row) && $new_name_row['id'] !='')
                            {
                                $tblname_row = $obj->GetSingleRow("spssp_tables_name","id=".$new_name_row['table_name_id']);
                                $tblname = $tblname_row['name'];
                            }
                            else
                            {
                                 $tblname = $table_row['name'];
                            }

                            if($table_row['visibility']==1 && $table_row['display']==1)
                            {

                                $disp = 'display:block;';

                            }
                            else if($table_row['visibility']==0 && $table_row['display']==1)
                            {
                                $disp = 'visibility:hidden;';
                            }
                            else if($table_row['display']==0 && $table_row['visibility']==0)
                            {
                                $disp = 'display:none;';
                            }
                    ?>
                    <div class="tables" style="<?=$disp?>">
                        <p align="center" style="text-align:center;" id="table_<?=$table_row['id']?>">

                            <a href="#"><b> <?=$tblname?></b> </a> &nbsp;

                        </p>

                    </div>
				<?php
                    }
                ?>
        			</div>
             	</div>
				<?php
                }
                ?>
                <div style="border:0; width:100%; text-align:left;">
        			<!--<a href="#"><img src="img/common/btn_regist.jpg" width="62" height="22" /></a>　
            		<a href="sekiji_plan.html"><img src="img/common/btn_backpage.jpg" width="107" height="22" /></a>-->
                    <?php if($default_plan_id > 0){?>
                    	<a href="set_table_layout_edit.php?default_plan=<?=$default_plan_id?>">レイアウト編集</a>&nbsp;
                    <?php
					}
					else
					{
						if($var == 1)
						{
					?>
                  		<a href="set_table_layout_edit.php?plan_id=<?=$plan_id?>&user_id=<?=$get['user_id']?>">レイアウト編集</a>&nbsp;
                    <?php
						}
                    }
					?>
                    <a href="javascript:void(0);" onclick="history.go(-1)">戻る</a>

        		</div>

            </div>


        </div>




    </div>
</div>

<?php
	include_once('inc/left_nav.inc.php');

	include_once("inc/new.footer.inc.php");
?>


