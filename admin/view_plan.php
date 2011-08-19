<?php
session_start();

include_once("inc/dbcon.inc.php");
include_once("inc/class.dbo.php");
include_once("inc/checklogin.inc.php");
$obj = new DBO();
$get = $obj->protectXSS($_GET);

include_once("inc/new.header.inc.php");

$user_id = (int)$get['user_id'];
$cats =	$obj->GetAllRowsByCondition('spssp_guest_category',' user_id='.$user_id);

$layout = $obj->getSingleRow("spssp_table_layout", "user_id = ".(int)$user_id." limit 1");


if(empty($layout) )
{
	echo "<script type='text/javascript'> alert('卓レイアウトを選択してください。'); window.location='users.php'; </script>";
	
}

	$plan_id = (int)$get['plan_id'];

	$plan_row = $obj->GetSingleRow("spssp_plan", " id =".$plan_id);
	
	
	$room_rows = $plan_row['row_number'];

	$row_width = $row_width-6;
	
	$table_width = (int)($row_width/2);
	$table_width = $table_width-6;
	
	$room_tables = $plan_row['column_number'];
	
	$row_width = (int)(182*$room_tables);
	$content_width = ($row_width+235).'px';
	
	$room_seats = $plan_row['seat_number'];
	
	$num_tables = $room_rows * $room_tables;
	
	$tblrows = $obj->getRowsByQuery("select distinct row_order from spssp_table_layout where user_id = ".(int)$user_id);

	$itemids = array();
	if(isset($details_arr['cart']))
	{
		
	}
	else
	{
		$plan_details_row = $obj->GetAllRow("spssp_plan_details"," plan_id=".$plan_id);
		if(!empty($plan_details_row))
		{
			foreach($plan_details_row as $pdr)
			{
				$skey= $pdr['seat_id'].'_input';
				$sval = '#'.$pdr['seat_id'].'_'.$pdr['guest_id'];
				$details_arr['cart'][$skey]=$sval;
			}
		}
	}
	if(isset($details_arr['cart']))
	{
		foreach($details_arr['cart'] as $item)
		{
			if($item)
			{
				$itemArr = explode("_",$item);
				$itemids[] = $itemArr[1];
			}
		}

	}

	include("inc/main_dbcon.inc.php");
	$respects = $obj->GetAllRow("dev2_main.spssp_respect");
	include("inc/return_dbcon.inc.php");
?>
<script type="text/javascript">
$(function(){
$(".rows").filter(":first").css("border-top","1px solid #666666");

$.fx.speeds._default = 400;
});

</script>
<link href="../css/choose_plan.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">


function checkConfirm()
{
	var conf;
	conf = confirm('修正内容を保存しても宜しいですか？');
	if(conf)
	{
		document.insert_plan.submit();
	}
	
}

</script>

<style>

.rows
{
float:left;

clear:both;
width:100%;
}
.tables
{
float:left;
width:180px;
}
.tables p
{
margin-top:0;
margin-bottom:0;
vertical-align:middle;
}
#room
{

width:auto;

}
.droppable
{
width:80px;
}
#gallery
{
border:1px solid #3681CB;
}
#stuffname
{
border-bottom:1px solid #3681CB;
}
#tst
{
padding:0;
}
</style>
<div id="topnavi">
    <?php
include("inc/main_dbcon.inc.php");
$hcode="0001";
$hotel_name = $obj->GetSingleData(" dev2_main.super_spssp_hotel ", " hotel_name ", " hotel_code=".$hcode);
?>
<h1><?=$hotel_name?>　管理</h1>
<?
include("inc/return_dbcon.inc.php");
?>
 
    <div id="top_btn"> 
        <a href="login.html"><img src="img/common/btn_logout.jpg" alt="ログアウト" width="102" height="19" /></a>　
        <a href="javascript:;" onclick="MM_openBrWindow('../support/operation_h.html','','scrollbars=yes,width=620,height=600')"><img src="img/common/btn_help.jpg" alt="ヘルプ" width="82" height="19" /></a>
    </div>
</div>
<div id="container">
	<div id="contents">
		<div style="float:left; width:100%;">
    		        
        	<div id="room" style="float:left; width:100%; ">
            	<div id="toptst">     
					<?php
                    foreach($tblrows as $tblrow)
                    {
                        $ralign = $obj->GetSingleData("spssp_table_layout", "row_align"," row_order=".$tblrow['row_order']." and user_id=".$user_id." limit 1");
						if($ralign == 0)
						{
							$num_none = $obj->GetNumRows("spssp_table_layout","user_id=".$user_id." and row_order=".$tblrow['row_order']." and display=0");
							if($num_none>0)
							{
								$con_width = $row_width -((int)($num_none*180));
								$con_style = "width:".$con_width."px;margin:0 auto;";
							}
							else
							{
								$con_style="";
							}
						}
						
    
                    ?> 
                	<div class="rows" id="row_<?=$tblrow['row_order']?>"> 
                		<input type="hidden" id="rowcenter_<?=$tblrow['row_order']?>" value="<?=$ralign?>" />	
                		<div class="row_conatiner" id="rowcon_<?=$tblrow['row_order']?>" style="<?=$con_style;?>">
                    	<?php
                  		 	$table_rows = $obj->getRowsByQuery("select * from spssp_table_layout where user_id = ".(int)$user_id." and row_order=".$tblrow['row_order']." order by  column_order asc");
                  
							foreach($table_rows as $table_row)
							{
								$new_name_row = $obj->GetSingleRow("spssp_user_table", "user_id = ".(int)$user_id." and default_table_id=".$table_row['id']);
						
								if(isset($new_name_row) && $new_name_row['id'] !='')
								{
									$tblname_row = $obj->GetSingleRow("spssp_tables_name","id=".$new_name_row['table_name_id']);
									$tblname = $tblname_row['name'];
									$tblname .= " &nbsp; <span style = 'display:none;'>".$tblname_row['id']." </span>";
								}
								else
								{
									$tblname = $table_row['name'];
								}
								
								if($table_row['visibility']==1 && $table_row['display']==1)
								{
		
									$disp = 'display:block;';
									$class = 'droppable';
		
								}
								else if($table_row['visibility']==0 && $table_row['display']==1)
								{
									$disp = 'visibility:hidden;';
									 $class = 'seat_droppable';
								}
								else if($table_row['display']==0 && $table_row['visibility']==0)
								{
									$disp = 'display:none;';
									$class = 'seat_droppable';
								}                    
                    		?>												
                        	<div class="tables" id="tid_<?=$table_row['id']?>" style="<?=$disp?>">
                                <p align="center" style="text-align:center" id="table_<?=$table_row['id']?>">
        
                                    <b><a href="#" style="cursor:default; text-decoration:none"><?=$tblname?></a> </b>
                                </p>
                            	<?php
                                //echo $disp;
                                $seats = $obj->getRowsByQuery("select * from spssp_default_plan_seat where table_id =".$table_row['table_id']." order by id asc limit 0,$room_seats");
                                foreach($seats as $seat)
                                {
                                ?>
                                    <div id="<?=$seat['id']?>" class="<?=$class?>" >
                                        <?php
                                        $key = $seat['id']."_input";
                                        if(isset($details_arr['cart'][$key]) && $details_arr['cart'][$key] != '')
                                        {
                                            $itemArray = explode("_", $details_arr['cart'][$key]);
                                           
                                            $item = $itemArray[1];
                                            $item_info =  $obj->GetSingleRow("spssp_guest", " id=".$item);
                                            
											include("inc/main_dbcon.inc.php");
                                            $rspct = $obj->GetSingleData("dev2_main.spssp_respect", "title"," id=".$item_info['respect_id']);
											include("inc/return_dbcon.inc.php");
                                            //echo $item_info['id'].'<br>';
                                            $edited_nums = $obj->GetNumRows("spssp_guest", "edit_item_id=".$item_info['id']." and user_id=".(int)$user_id);
                                            //echo '<p>'.$edited_nums.'</p>';
                                            
                                  
                                            
                                            
                                        ?>
                                          <span style="border:0; color:#666666;">
                                               <?php echo $item_info['name']." ".$rspct;?> &nbsp; &nbsp;
                                                        
                                         </span>
     
                                        <?php
                                        }
                                    ?>    
                                    </div>
                                <?php
                                }
                            	?>
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
            
   
        	</div>
        
        
    	</div>

	</div>
</div>


<?php
	include_once('inc/left_nav.inc.php');
	include_once("inc/new.footer.inc.php");
?>

