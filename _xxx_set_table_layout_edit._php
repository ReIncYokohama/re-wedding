<?php
include_once("admin/inc/dbcon.inc.php");
include_once("admin/inc/class.dbo.php");
include_once("inc/checklogin.inc.php");
$obj = new DBO();
$get = $obj->protectXSS($_GET);

include("inc/new_header.php");

$plan_row = $obj->GetSingleRow("spssp_plan", " user_id=".(int)$_SESSION['userid']);
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






$tl_rows = $obj->GetAllRowsByCondition("spssp_table_layout"," user_id=".(int)$_SESSION['userid']." and visibility=1");
foreach($tl_rows as $tl_row)
{
	$table_arr[] = $tl_row['table_id'];
}


$tblrows = $obj->getRowsByQuery("select distinct row_order from spssp_table_layout where user_id = ".(int)$_SESSION['userid']);

?>
<link rel="stylesheet" type="text/css" href="css/jquery.ui.all.css">
<link href="css/choose_plan.css" rel="stylesheet" type="text/css" />
	<script src="js/jquery-1.4.2.js" type="text/javascript"></script>
	<script src="js/jquery.ui.position.js" type="text/javascript"></script>
    <script src="js/jquery.ui.core.js" type="text/javascript"></script>
	<script src="js/jquery.ui.widget.js" type="text/javascript"></script>
	<script src="js/jquery.ui.mouse.js" type="text/javascript"></script>
	<script src="js/jquery.ui.draggable.js" type="text/javascript"></script>
	<script src="js/jquery.ui.droppable.js" type="text/javascript"></script>
	<script src="js/jquery.ui.resizable.js" type="text/javascript"></script>    
	<script src="js/jquery.ui.dialog.js" type="text/javascript"></script>
<link rel="stylesheet" href="css/demos.css" type="text/css">

<script>
var dragged_id;
$(function(){
$("#top_text").html("<ul id='menu'><li><a href='dashboard.php'>Home</a></li><li><h2> >Edit Your Table Lable Layout</h2></li> <li><a href='logout.php'>Log Out</a></li></ul>"
);
$(".rows").filter(":first").css("border-top","1px solid #666666");

//$("div#toptst :first").css("border-left","solid 1px #666666");
/* Drag and Drop Starts Here//   Dragging */
var drop_divid = "";
$( ".tables").draggable({			 
			 cancel:'.dragfalse',
			revert: "invalid", // when not dropped, the item will revert back to its initial position
			//containment: $( "#demo-frame" ).length ? "#demo-frame" : "document", // stick to demo-frame if present
			helper: "clone",
			cursor: "move",
			opacity:.8
			
			});	
$(".table_droppable").droppable({
			activeClass: "ui-state-highlight",
			over: function (event, ui){
				//divid = "#"+this.id;
				$(this).css("border","solid 1px red");
				drop_divid = this.id;
			
				
			},
			out:function (event, ui){
				//divid = "#"+this.id;
				$(this).css("border","1px solid #0099CC");
			
				
			},
			drop: function( event, ui ) {
				$(this).css("border","1px solid #0099CC");
				dropTable( ui.draggable, drop_divid );
			}
			
		});

function dropTable($item, drop_divid)
{

	
	var drag_arr = $($item).attr('id').split("_");
	var drag_table_id ="#"+$($item).parent().parent().attr('id');
	var drag_con_arr = drag_table_id.split("_");
	var drag_row_order = $("#row_"+drag_con_arr[1]).val();
	var drag_column_order = $("#column_"+drag_con_arr[1]).val();
	var drag_table = drag_arr[1];
	var drag_table_display = 0;
	
	

	
	
	$item.fadeOut(function() {
		var drop_table_id = "#"+$("#"+drop_divid).parent().attr('id');	
		var drop_con_arr = 	drop_table_id.split("_");
		var arr = drop_divid.split("_");	
		
		var drop_row_order = $("#row_"+drop_con_arr[1]).val();
		var drop_column_order = $("#column_"+drop_con_arr[1]).val();
		var drop_table = arr[1];
		
		var drop_id = arr[1];
		
		var tabledisplay = $("#tbl_"+drop_id).css('display');
		
		var drag_html = $(drag_table_id).html();
		var drop_html = $(drop_table_id).html();
				
		$(drag_table_id).html(drop_html);		
			
		$(drop_table_id).html(drag_html);
		
		$(drop_table_id+ " .tables").fadeOut(100);
		$(drop_table_id+ " .tables").fadeIn(1000);
		$(drop_table_id+ " .tables").css("opacity",1);
		
		var drop_table_display = 1;	
		if(tabledisplay !='none')
		{		
			$(drag_table_id+" .tables").fadeIn(1000);	
				
			drag_table_display = 1;
		}
		else
		{
			drag_table_display= 0;
		}
		
		$.post('ajax/set_table_layout_session.php',{'drag_row_order':drag_row_order,'drag_column_order':drag_column_order,'drag_table':drag_table,'drop_row_order':drop_row_order,'drop_column_order':drop_column_order,'drop_table':drop_table,'drop_table_display':drop_table_display,'drag_table_display':drag_table_display}, 
		function (data){
		//alert(data);
		});
				
		$( ".tables").draggable({			 
			 cancel:'.dragfalse',
			revert: "invalid", // when not dropped, the item will revert back to its initial position
			//containment: $( "#demo-frame" ).length ? "#demo-frame" : "document", // stick to demo-frame if present
			helper: "clone",
			cursor: "move",
			opacity:.8
			
			});	
			
		$(".table_droppable").droppable({
			activeClass: "ui-state-highlight",
			over: function (event, ui){
				//divid = "#"+this.id;
				$(this).css("border","solid 1px red");
				drop_divid = this.id;
			
				
			},
			out:function (event, ui){
				//divid = "#"+this.id;
				$(this).css("border","1px solid #0099CC");
			
				
			},
			drop: function( event, ui ) {
				$(this).css("border","1px solid #0099CC");
				dropTable( ui.draggable, drop_divid );
			}
			
		});
		
	
		
	});

}

$( "#table_edit" ).dialog({
	autoOpen: false,
	height: 200,
	width: 420,
	//show: "blind",
	//hide: "explode",
	modal: true,
	buttons: {
		"送信": function() {
				var tnid = $("#table_name").val();
				var name = $("#table_name :selected").text();
				var id = $("#table_id").val();
		
				
				$("#table_"+id+ " a").html(name+"<span id = 'tnameid' style= 'display:none'>"+tnid+"</span>");
		
				
				$.post('ajax/edit_table_name.php', {'tnid': tnid,'id':id}, function(data) {
					/*if(parseInt(data) > 0)
					{
						$("#table_"+id+ " a").removeAttr('onclick');
						$("#table_"+id+ " a").click(function(){
							edit_table_name(id,name);								 
						});
					}*/
				});	
				
				$( this ).dialog( "close" );
				
			
		},
		"キャンセル": function() {

			$( this ).dialog( "close" );
		},
		"閉じる":function() {
	
			$( this ).dialog( "close" );
		}
	},
	close: function() {

	}
});


});

function checkGuest(tid)
{
	$.post('ajax/check_plan_guest.php',{'tid':tid}, function(data){
	
		if(parseInt(data) > 0)
		{
			
			alert('There are Guests on this Table. Please remove them first.');
			return false;
		}
	});
}
function edit_table_name(id)
{
	
	$("#table_id").val(id);
	var newname_id = $("#table_"+id+ " a span").html();
	
	if(newname_id > 0)
	{

		$("#table_edit option").each(function (){
			if(parseInt($(this).val()) == parseInt(newname_id))
			{
				$(this).attr('selected','selected');
			}
		});
		
		
	}
	$("#table_edit").dialog("open");	

}
function viewTable(id)
{
	var tableid = 'tbl_'+id;
	$("#"+tableid).fadeIn(1000);
	$("#"+tableid).next().hide();
	$("#"+tableid+" .remove_option").show();
	$("#"+tableid+" .remove_option").html("<a href = '#' onclick='removeTable("+id+")'> Remove</a>");
	
	$.post('ajax/table_layout_visibility.php', {'id':id, 'visibility':1}, function(data) {
	
	});

}
function removeTable(id)
{
	checkGuest(id);
	var tableid = 'tbl_'+id;
	$("#"+tableid).fadeOut(1000, function(){
	$("#"+tableid).parent().css("border-style","solid");
	$("#"+tableid).next().html("<a href = '#' onclick='viewTable("+id+")'> View</a>");
	$("#"+tableid).next().show();
	});
	$.post('ajax/table_layout_visibility.php', {'id':id, 'visibility':0}, function(data) {
	
	});
}
function deleteTable(tid)
{
	if($("#display_"+tid).is(':checked') == true)
	{
		$.post('ajax/table_layout_visibility.php', {'id':tid, 'display':1}, function(data) {
	
		});
	}
	else
	{
		$.post('ajax/table_layout_visibility.php', {'display':0,'id':tid},function (data){});
	}
}
function change_align(order)
{
	if($("#rowcenter_"+order).is(':checked')==true)
	{
		$.post('ajax/row_align.php', {'row_order':order, 'row_align':0}, function(data) {
	
		});
	}
	else
	{
		$.post('ajax/row_align.php', {'row_order':order, 'row_align':1}, function(data) {
	
		});
	}
}
</script>
<style>
	.abc
	{
	width:152px;
	}
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
	.display_chk
	{
	width:20px;
	text-align:center;
	float:left;
	height:70px;
	}
	.display_chk input
	{
	vertical-align:middle;
	width:15px;
	margin-top:30px;
	}
	.table_droppable
	{
	width:120px;
	}
	.row_center
	{
	float:left;
	width:100%;
	text-align:center;
	}
	
</style>





<form action="set_table_layout_edit.php" method="post">

<div style="width:100%; float:left; " id="toptst">
	<?php
		foreach($tblrows as $tblrow)
		{
			
			$ralign = $obj->GetSingleData("spssp_table_layout", "row_align"," row_order=".$tblrow['row_order']." and user_id=".(int)$_SESSION['userid']." limit 1");
			
			if($ralign==0)
			{
				$row_chk = "checked='checked'";
			}
			else
			{
				$row_chk = "";
			}
	?>
    	<div class="rows" style="float:left;width:100%;" id="row_<?=$tblrow['row_order']?>"> 
        <div class="row_center" >
        	<input type="checkbox" id="rowcenter_<?=$tblrow['row_order']?>" value="<?=$ralign?>" <?=$row_chk?> onchange="change_align(<?=$tblrow['row_order']?>)" /> Center Aligned
		</div>	
        
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
							$tblname .= " &nbsp; <span style = 'display:none;'>".$tblname_row['id']." </span>";
						}
						else
						{
							$tblname = $table_row['name'];
						}
						if($table_row['visibility']==1)
						{
							
							$disp = 'display:block;';
							$disp1 = 'border-style:hidden;';
						}
						else
						{
							$disp = 'display:none;';
							$disp1 = 'border-style:solid;';
						}
						if($table_row['display']==1)
						{
							$chk = 'checked="checked" ';
						}
						else
						{
							$chk = '';
						}
					?>
					<div class="abc" style="float:left;">
                    	<div id="checkcontainer_<?=$table_row['id']?>" class="display_chk">
                        	<input type="checkbox" id= "display_<?=$table_row['id']?>" value="<?=$table_row['column_order']?>" <?=$chk?> onchange="deleteTable(<?=$table_row['id']?>)" />
                        </div>
						<input type="hidden" id="row_<?=$table_row['id']?>" value="<?=$table_row['row_order']?>" />
						<input type="hidden" id="column_<?=$table_row['id']?>" value="<?=$table_row['column_order']?>" />
                        
                        
						
						<div id="tablecontainer_<?=$table_row['id']?>" style="float:left;">
						
							<div class="table_droppable" style="<?=$disp1?>" id="drop_<?=$table_row['id']?>">
								 
								<div class="tables" style="<?=$disp?>" id="tbl_<?=$table_row['id']?>" style="float:left;">
								
									<p align="center" style="text-align:center;" id="table_<?=$table_row['id']?>">
						
										<a href="#" onClick="edit_table_name(<?=$table_row['id']?>);" > <b> <?=$tblname?> </b> </a> &nbsp; 
			
									</p>
									
								   
									<div class="remove_option" style="width:100%; text-align:right">
									<?php
										if($table_row['visibility']==1)
										{
											echo "<a href='#' onclick = 'removeTable(".$table_row['id'].")'> Remove</a>";
										}                            
									?>                    	
									</div>                        
								 </div>
								 <div class = "view_option">
								 <?php
										if(($table_row['visibility']== 0))
										{
											echo "<a href='javascript:void()' onclick = 'viewTable(".$table_row['id'].")'> View</a>";
										}
										
									?>
								 </div>                  
							</div>
						</div>
					</div> 
              <?php
			  		
              	}
			  ?>  
    	</div>  
        <?php
        }
		?>      
</div>
<div style="width:100%; text-align:left;">
	<!--<input type="submit" value="Update" name="submit" />
    &nbsp;--> <input type="button" value="Back" onClick="javascript:history.go(-1)" />
</div>
</form>


<?php
	$name_rows = $obj->GetAllRow("spssp_tables_name");
?>
<div id="table_edit" title="ユーザー情報">	
	<form action="" method="post" id="table_edit_form" name="table_edit_form">
	<fieldset style="height:85px;">

        <input type="hidden" name="table_id" value="" id="table_id" />

   
        
        <table align="center" border="0">

        	<tr>
            	<td> Name: </td> 
                <td>
                <select id="table_name">
                	<?php 
						foreach($name_rows as $row)
						{
							echo "<option value='".$row['id']."'>".$row['name']."</option>";
						}
					?>
                </select>
                
                
                <!--<input type="text" id="table_name" name="table_name" />--></td>
            </tr>

        </table> 
	</fieldset>
	</form>
</div>
<?php
include("inc/new_footer.php");
?>
