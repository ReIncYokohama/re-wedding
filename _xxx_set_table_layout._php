<?php
include("admin/inc/dbcon.inc.php");
include("admin/inc/class.dbo.php");
$obj = new DBO();
$get = $obj->protectXSS($_GET);

include("inc/new_header.php");




$plan_row = $obj->GetSingleRow("spssp_plan", " user_id=".(int)$_SESSION['userid']);
if(!isset($plan_row['id']))
{
	echo "<script type='text/javascript'>alert('Please Define Plan Criteria First'); window.location='dashboard.php';</script>";
}
$room_rows = $plan_row['row_number'];
$row_width = (int)(900/$room_rows);
$room_tables = $plan_row['column_number'];
$room_seats = $plan_row['seat_number'];

$num_tables = $room_rows * $room_tables;

$table_rows = $obj->getRowsByQuery("select * from spssp_default_plan_table where room_id = ".$plan_row['room_id']." order by id asc limit 0, $num_tables");

$num_layouts = $obj->GetNumRows("spssp_table_layout","user_id= ".(int)$_SESSION['userid']);


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
			$lo_arr['user_id'] = (int)$_SESSION['userid'];
			$lo_arr['visibility'] = 1;
			$lo_arr['row_order'] = $i;
			$lo_arr['column_order'] = $j;
			$lo_arr['name'] = $tr['name'];
		
			$lid = $obj->InsertData("spssp_table_layout", $lo_arr);
		
		}
		
	}
}

$tblrows = $obj->getRowsByQuery("select distinct row_order from spssp_table_layout where user_id = ".(int)$_SESSION['userid']);


//$r = 1;

?>

<link href="css/choose_plan.css" rel="stylesheet" type="text/css" />

<script>
$(function(){
$("#top_text").html("<ul id='menu'><li><a href='dashboard.php'>Home</a></li><li><h2> > Your Table Lable Layout</h2></li> <li><a href='logout.php'>Log Out</a></li></ul>");
$(".rows").filter(":first").css("border-top","1px solid #666666");

var row_width =$(".rows").width();
var table_width = $(".tables").width();
var table_width = table_width+40;

$(".rows").each(function(){

	var rowid = $(this).attr('id');	
	var ralign = $("#"+rowid+" input").val();
	if(ralign != 1)
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
	
}

);

});
function checkGuest(tid,cid)
{
	$.post('ajax/check_plan_guest.php',{'tid':tid}, function(data){
		if(parseInt(data) > 0)
		{
			$("#"+cid).attr('checked','checked');
			alert('There are Guests on this Table. Please remove them first.');
		}
	});
}
</script>
<style>

	.tables
	{
		height:70px;
		width: 120px;
		float:left;
		
		background-color:#EBEBEB;
		border:1px solid #0099CC;
		-moz-border-radius-topleft:30px;
		-moz-border-radius-bottomleft:30px;
		-moz-border-radius-topright:30px;
		-moz-border-radius-bottomright:30px;	
	}

	.tables
	{

		margin:5px 20px;
	
	}

	.tables p a
	{
	text-decoration:none;
	cursor:default;
	}
</style>





<!--<form action="set_table_layout.php" method="post">-->
<div style="width:100%;float:left;">
    	<h3><a href="set_table_layout_edit.php">Edit Lay Out</a></h3>
 </div>
<div style="width:100%; float:left;" id="toptst">
	
    <?php
		foreach($tblrows as $tblrow)
		{
			$ralign = $obj->GetSingleData("spssp_table_layout", "row_align"," row_order=".$tblrow['row_order']." and user_id=".(int)$_SESSION['userid']." limit 1");

	?>
    		<div class="rows" style="float:left;width:100%" id="row_<?=$tblrow['row_order']?>"> 
            	<input type="hidden" id="rowcenter_<?=$tblrow['row_order']?>" value="<?=$ralign?>" />
            	
            	<div class="row_conatiner" id="rowcon_<?=$tblrow['row_order']?>">
				<?php
                
                    $table_rows = $obj->getRowsByQuery("select * from spssp_table_layout where user_id = ".(int)$_SESSION['userid']." and row_order=".$tblrow['row_order']." order by  column_order asc");
                    foreach($table_rows as $table_row)
                    {
                        
                            $new_name_row = $obj->GetSingleRow("spssp_user_table", "user_id = ".(int)$_SESSION['userid']." and default_table_id=".$table_row['id']);
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
                
    </div>        



<?php
include("inc/new_footer.php");
?>
