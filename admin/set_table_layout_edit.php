<?php
include("inc/dbcon.inc.php");
include("inc/class.dbo.php");
$obj = new DBO();
$get = $obj->protectXSS($_GET);
require_once("inc/include_class_files.php");
$objInfo = new InformationClass();

include_once("inc/new.header.inc.php");

$user_row = $obj->GetSingleRow("spssp_user"," id= ".(int)$get['user_id']);

$default_plan_id = (int)$get['default_plan'];
$plan_id = (int)$get['plan_id'];
$stuff_id= (int)$get['stuff_id'];

if($default_plan_id > 0)
{
	$plan_row = $obj->GetSingleRow("spssp_default_plan", " id=".(int)$default_plan_id);

}
else if($plan_id > 0)
{
	$plan_row = $obj->GetSingleRow("spssp_plan", " id=".(int)$plan_id);
	$user_id = (int)$get['user_id'];
}


if(!isset($plan_row['id']))
{
	echo "<script type='text/javascript'>alert('Please Define Plan Criteria First'); window.location='users.php';</script>";
}




$room_rows = $plan_row['row_number'];
//
$room_tables = $plan_row['column_number'];
$row_width = (int)((142)*$room_tables);
$room_seats = $plan_row['seat_number'];

$num_tables = $room_rows * $room_tables;

$table_rows = $obj->getRowsByQuery("select * from spssp_default_plan_table where room_id = ".$plan_row['room_id']." order by id asc limit 0, $num_tables");

//$num_layouts = $obj->GetNumRows("spssp_table_layout","default_plan_id= ".(int)$default_plan_id);


/*if($num_layouts <= 0)
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

			$lid = $obj->InsertData("spssp_table_layout", $lo_arr);

		}

	}
}*/
if($default_plan_id > 0)
{
	$tblrows = $obj->getRowsByQuery("select distinct row_order from spssp_table_layout where default_plan_id= ".(int)$default_plan_id);
}
else if($plan_id > 0)
{
	$tblrows = $obj->getRowsByQuery("select distinct row_order from spssp_table_layout where user_id= ".(int)$user_id);
}

//$r = 1;

?>

<link rel="stylesheet" type="text/css" href="../css/jquery.ui.all.css">

<link href="../css/choose_plan.css" rel="stylesheet" type="text/css" />

<script src="../js/jquery-1.4.2.js" type="text/javascript"></script>
<script src="../js/jquery.ui.position.js" type="text/javascript"></script>
<script src="../js/jquery.ui.core.js" type="text/javascript"></script>
<script src="../js/jquery.ui.widget.js" type="text/javascript"></script>
<script src="../js/jquery.ui.mouse.js" type="text/javascript"></script>
<script src="../js/jquery.ui.draggable.js" type="text/javascript"></script>
<script src="../js/jquery.ui.droppable.js" type="text/javascript"></script>
<script src="../js/jquery.ui.resizable.js" type="text/javascript"></script>
<script src="../js/jquery.ui.dialog.js" type="text/javascript"></script>
<link rel="stylesheet" href="../css/demos.css" type="text/css">

<script>
var dragged_id;
var is_guest_exist = 0;
$(function(){


$( "#table_edit_name" ).dialog({
	autoOpen: false,
	height: 200,
	width: 420,
	//show: "blind",
	//hide: "explode",
	modal: true,
	buttons: {
		"保存": function() {
				var tnid = $("#table_name").val();
				var name = $("#table_name :selected").text();
				var id = $("#table_id").val();


				$("#table_"+id+ " a").html(name+"<span id = 'tnameid' style= 'display:none'>"+tnid+"</span>");

				var user_id = $("#user_id").val();

				$.post('ajax/edit_table_name.php', {'tnid': tnid,'id':id,'user_id':user_id}, function(data) {

					//alert(data);
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


var flag = false;



$(".rows").filter(":first").css("border-top","1px solid #666666");

//$("div#toptst :first").css("border-left","solid 1px #666666");
/* Drag and Drop Starts Here//   Dragging */
/*var drop_divid = "";
$( ".tables").draggable({
			 cancel:'.drag_false',
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
				$(this).css("border","0");


			},
			drop: function( event, ui ) {
				$(this).css("border","0");
				dropTable( ui.draggable, drop_divid );
			}

		});*/

/*function dropTable($item, drop_divid)
{


	var drag_arr = $($item).attr('id').split("_");
	var drag_table_id ="#"+$($item).parent().parent().attr('id');
	var drag_con_arr = drag_table_id.split("_");
	var drag_row_order = $("#row_"+drag_con_arr[1]).val();
	var drag_column_order = $("#column_"+drag_con_arr[1]).val();
	var drag_table = drag_arr[1];
	var drag_table_display = 0;

	var drga_check_div_id = "#checkcontainer_"+drag_arr[1];



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

		var drop_check_div_id = "#checkcontainer_"+drop_id;

		var drag_check_html =  $(drga_check_div_id).html();
		var drop_check_html = $(drop_check_div_id).html();


		$(drga_check_div_id).html(drop_check_html);

		$(drop_check_div_id).html(drag_check_html);

		flag = true;



		$(drop_table_id+ " .tables").fadeOut(1);
		$(drop_table_id+ " .tables").fadeIn(100, function(){

		});
		$(drop_table_id+ " .tables").css("opacity",1);

		var drop_table_display = 1;
		if(tabledisplay !='none')
		{
			$(drag_table_id+" .tables").fadeIn(100);

			drag_table_display = 1;
		}
		else
		{
			drag_table_display= 0;
		}

		$.post('ajax/set_table_layout_session.php',{'drag_row_order':drag_row_order,'drag_column_order':drag_column_order,'drag_table':drag_table,'drop_row_order':drop_row_order,'drop_column_order':drop_column_order,'drop_table':drop_table,'drop_table_display':drop_table_display,'drag_table_display':drag_table_display},
		function (data){

		});

		if(flag== true)
		{
			var drag_check_id = $(drga_check_div_id+' input').attr('id');

			var drag_check_id_arr = drag_check_id.split("_");


			var drop_check_id = $(drop_check_div_id+' input').attr('id');
			var drop_check_id_arr = drop_check_id.split("_");

			$(drga_check_div_id).attr('id','testchk');

			$(drop_check_div_id).attr('id','checkcontainer_'+drop_check_id_arr[1]);
			$('testchk').attr('id','checkcontainer_'+drag_check_id_arr[1]);
		}



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
				$(this).css("border","0");


			},
			drop: function( event, ui ) {
				$(this).css("border","0");
				dropTable( ui.draggable, drop_divid );
			}

		});



	});

}*/

});


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
	$("#table_edit_name").dialog("open");

}
function viewTable(id)
{
	var tableid = 'tbl_'+id;
	$("#"+tableid).fadeIn(1000);
	$("#"+tableid).next().hide();
	$("#"+tableid+" .remove_option").show();
	$("#"+tableid).parent().css("border","0");
	$("#"+tableid+" .remove_option").html("<a href = '#' onclick='removeTable("+id+")'> 削除</a>");

	$.post('ajax/table_layout_visibility.php', {'id':id, 'visibility':1}, function(data) {

	});

}
function removeTable(id,dtid)
{
	var user_id = $("#user_id").val();

	$.post('ajax/check_plan_guest.php',{'tid':dtid,'user_id':user_id}, function(data){

		if(parseInt(data) > 0)
		{
			alert("全て卓の招待者を削除してください");
			$("#display_"+tid).attr("checked","checked");
		}
		else
		{
		var tableid = 'tbl_'+id;
			$("#"+tableid).fadeOut(1000, function(){
				$("#"+tableid).parent().css("background-image","url(img/circle_big1.jpg)");
				$("#"+tableid).next().html("<a href = '#' onclick='viewTable("+id+")'> 表示</a>");
				$("#"+tableid).next().show();
			});
			$.post('ajax/table_layout_visibility.php', {'id':id, 'visibility':0}, function(data) {

			});
		}
	});
}
function deleteTable(tid,dtid,ralign)
{
	var table_div = "#drop_"+tid;
	var user_id = $("#user_id").val();

	$.post('ajax/check_plan_guest.php',{'tid':dtid,'user_id':user_id}, function(data){

		if(parseInt(data) > 0)
		{
			alert("卓に招待者が設定されています。\n削除する前に、席次表編集にて卓を空にしてください");
			$("#display_"+tid).attr("checked","checked");
		}
		else
		{
			if($("#display_"+tid).is(':checked') == true)
			{
				$(table_div).css('visibility','visible');
				$(table_div).fadeIn(500,function(){});
				$.post('ajax/table_layout_visibility.php', {'id':tid, 'display':1,'ralign':ralign}, function(data) {

				});
			}
			else
			{
				$(table_div).fadeOut(500,function(){});
				$.post('ajax/table_layout_visibility.php', {'display':0,'id':tid,'ralign':ralign},function (data){

				});
			}
		}

	});





}
function change_align(order,default_plan_id,user_id,align)
{


		$.post('ajax/row_align.php', {'row_order':order, 'align':align,'default_plan_id':default_plan_id,'user_id':user_id}, function(data) {


		});


}
</script>
<style>
	.abc
	{
		width:100px;
	}

	.tables
	{
		height:72px;
		width: 72px;
		float:left;

		background-image:url(img/circle_big.jpg);

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
		width:72px;
		border:0;

	}
	.row_center
	{
	float:left;
	width:100%;
	text-align:left;
	}

</style>

<div id="topnavi">
    <?php
include("inc/main_dbcon.inc.php");
$hcode=$HOTELID;
$hotel_name = $obj->GetSingleData(" super_spssp_hotel ", " hotel_name ", " hotel_code=".$hcode);
?>
<h1><?=$hotel_name?></h1>
<?
include("inc/return_dbcon.inc.php");
?>

    <div id="top_btn">
        <a href="logout.php"><img src="img/common/btn_logout.jpg" alt="ログアウト" width="102" height="19" /></a>　
        <a href="javascript:;" onclick="MM_openBrWindow('../support/operation_h.html','','scrollbars=yes,width=620,height=600')"><img src="img/common/btn_help.jpg" alt="ヘルプ" width="82" height="19" /></a>
    </div>
</div>
<div id="container">
    <div id="contents">
   <div style="font-size:18; font-weight:bold; width:250px;">
 	<?php
	$user_id0=$get['user_id'];
	$user_row = $obj->GetSingleRow("spssp_user"," id= $user_id0");	?>


  <?php  echo $objInfo->get_user_name_image_or_src($user_row['id'] ,$hotel_id=1, $name="man_lastname.png",$extra="thumb1",$height=20);?>
・
  <?php  echo $objInfo->get_user_name_image_or_src($user_row['id'] ,$hotel_id=1, $name="woman_lastname.png",$extra="thumb1",$width=20);?>
  様

    </div>
   <div style="width:1035px; font-size:18; font-weight:bold;"> <h4>
<!-- UCHIDA EDIT 11/08/02
            	 <a href="users.php">お客様一覧</a> &raquo;<a href="set_table_layout.php?plan_id=<?=$plan_row['id']?>&user_id=<?=(int)$get['user_id']?>"> お客様挙式情報 </a> &raquo;  卓レイアウト設定　
-->
		<?php
		if($stuff_id==0) {?>
            <a href="manage.php">ＴＯＰ</a> &raquo; <a href="user_info.php?user_id=<?=$user_id?>&stuff_id=<?=$stuff_id?>">お客様挙式情報 </a> &raquo; 卓レイアウト
		<?php }
		else {?>
            <a href="users.php">管理者用お客様一覧</a> &raquo; <a href="user_info.php?user_id=<?=$user_id?>&stuff_id=<?=$stuff_id?>">お客様挙式情報 </a> &raquo; 卓レイアウト</h4>
		<?php }
		?>
		</h4></div>
        <div class="box_table" style="width:1035px;">
        	<div class="bottom_line_box">
        		<?php
                $rooms = $obj->GetAllRow("spssp_room");

				if(is_array($rooms))
				{
					foreach($rooms as $room)
					{
						if($room['id']==$user_row['room_id'])
						{
						  // echo $room['name'];
						   $room_name = $room['name'];
						   $roomName = $room['name'];
						}
						//else
						// echo "<option value ='".$room['id']."'> ".$room['name']."</option>";

					}
				}

			?>


                <p class="txt2">会場名：<?=$roomName;?>　　最大卓数：横<?=$room_tables?>列 × 縦<?=$room_rows?>段　　一卓人数：<?=$room_seats?>名まで</p>
        	</div>

        </div>

        <div class="box_table" style="width:1035px; font-size: 12px;">
        		<h2>卓レイアウト設定</h2>
        	  		<br />
        		●会場の最大卓数が表示されます。不要な卓はチェックを外して削除してください。
            </p>
        </div>



        <div style="width:1000px; float:left; text-align:center; font-size: 12px;">
        	<form action="set_table_layout_edit.php" method="post">
            <div style="width:<?=$row_width?>px; margin:0 auto;" id="toptstaa">
                <?php
                    foreach($tblrows as $tblrow)
                    {
                        if($default_plan_id > 0)
						{
                        	$ralign = $obj->GetSingleData("spssp_table_layout", "align"," row_order=".$tblrow['row_order']." and default_plan_id=".(int)$default_plan_id." limit 1");
						}
						else if($plan_id > 0)
						{
							$ralign = $obj->GetSingleData("spssp_table_layout", "align"," row_order=".$tblrow['row_order']." and user_id=".$user_id." limit 1");
						}
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
                        <!--<input type="radio" id="rowcenter_<?=$tblrow['row_order']?>" name="rowcenter_<?=$tblrow['row_order']?>" value="L" <?php if($ralign=='L' || $ralign=='') { ?> checked="checked" <?php } ?>  onchange="change_align(<?=$tblrow['row_order']?>,<?=(int)$default_plan_id?>,<?=$user_id?>,'L')" /> 左寄せ &nbsp;-->
                        	<input type="radio" id="rowcenter_<?=$tblrow['row_order']?>"  name="rowcenter_<?=$tblrow['row_order']?>" value="C"  <?php if($ralign=='C') { ?> checked="checked" <?php } ?>  onchange="change_align(<?=$tblrow['row_order']?>,<?=(int)$default_plan_id?>,<?=$user_id?>,'C')" /> 中央配置 &nbsp;
                            <!--<input type="radio" id="rowcenter_<?=$tblrow['row_order']?>"  name="rowcenter_<?=$tblrow['row_order']?>" value="R"  <?php if($ralign=='R') { ?> checked="checked" <?php } ?>  onchange="change_align(<?=$tblrow['row_order']?>,<?=(int)$default_plan_id?>,<?=$user_id?>,'R')" /> 右寄せ-->
							<input type="radio" id="rowcenter_<?=$tblrow['row_order']?>"  name="rowcenter_<?=$tblrow['row_order']?>" value="R"  <?php if($ralign=='N') { ?> checked="checked" <?php } ?>  onchange="change_align(<?=$tblrow['row_order']?>,<?=(int)$default_plan_id?>,<?=$user_id?>,'N')" /> そのまま


                    	</div>

                        <?php
                        	if($default_plan_id > 0)
							{
                            	$table_rows = $obj->getRowsByQuery("select * from spssp_table_layout where default_plan_id = ".(int)$default_plan_id." and row_order=".$tblrow['row_order']." order by  column_order asc");
							}
							else if($plan_id > 0)
							{
								$table_rows = $obj->getRowsByQuery("select * from spssp_table_layout where user_id = ".$user_id." and row_order=".$tblrow['row_order']." order by  column_order asc");
							}

                            foreach($table_rows as $table_row)
                            {

								if($default_plan_id > 0)
								{
									$new_name_row = $obj->GetSingleRow("spssp_user_table", "default_plan_id = ".(int)$default_plan_id." and default_table_id=".$table_row['id']);
								}
								else if($plan_id > 0)
								{
									$new_name_row = $obj->GetSingleRow("spssp_user_table", "user_id = ".(int)$user_id." and default_table_id=".$table_row['id']);
								}

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

								if($table_row['display']==1)
								{

									$disp = 'display:block;';
									$disp1 = 'display:block;';
								}
								else
								{
									$disp = 'display:none;';
									$disp1 = 'visibility:hidden;';
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
                                        <input type="checkbox" id= "display_<?=$table_row['id']?>" value="<?=$table_row['column_order']?>" <?=$chk?> onclick="deleteTable(<?=$table_row['id']?>,<?=$table_row['table_id']?>,'<?=$ralign?>')" />
                                    </div>
                                    <input type="hidden" id="row_<?=$table_row['id']?>" value="<?=$table_row['row_order']?>" />
                                    <input type="hidden" id="column_<?=$table_row['id']?>" value="<?=$table_row['column_order']?>" />



                                    <div id="tablecontainer_<?=$table_row['id']?>" style="float:left;">

                                        <div class="table_droppable" style="<?=$disp1?>" id="drop_<?=$table_row['id']?>">

                                            <div class="tables" id="tbl_<?=$table_row['id']?>" style="float:left;">

                                                <p align="center" style="text-align:center;font-size:120%;" id="table_<?=$table_row['id']?>">

                                                    <a href="#" onClick="edit_table_name(<?=$table_row['id']?>);"  class="drag_false" > <b>
                <?php
                if($tblname && $tblname != ""){
                  echo $tblname;
                }else{
                  echo "&nbsl;&nbsl;";
                }

                ?>
                                                   </b> </a> &nbsp;

                                                </p>


                                                <div class="remove_option"  style="position:relative;top:-4px;left:10px;">
                                                <?php
                                                    /*if($table_row['visibility']==1)
                                                    {
                                                        echo "<a href='#' onclick = 'removeTable(".$table_row['id'].",".$table_row['table_id'].")' class='drag_false'> 削除</a>";
                                                    }*/
                                                ?>
                                                </div>
                                             </div>
                                             <div class = "view_option"  style="position:relative;top:-75px;">
                                             <?php
                                                   /* if(($table_row['visibility']== 0))
                                                    {
                                                        echo "<a href='javascript:void()' onclick = 'viewTable(".$table_row['id'].")'  class='drag_false'> 表示</a>";
                                                    }*/

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
                    <div style="width:100%; text-align:left;">
                		<!--<input type="submit" value="Update" name="submit" />
               			 &nbsp;<input type="button" value="保存" onClick="javascript:history.go(-1)" />-->
          <img src="img/common/btn_save.jpg" onclick="javascript:window.location='user_info.php?user_id=<?=$user_id?>&stuff_id=<?=$stuff_id?>'">
          <!--					<input type="button" value="保存" onclick="javascript:window.location='user_info.php?user_id=<?=$user_id?>&stuff_id=<?=$stuff_id?>'"/>-->
                    &nbsp;&nbsp;
          <img src="img/common/btn_cancel.jpg" onclick="javascript:window.location='user_info.php?user_id=<?=$user_id?>&stuff_id=<?=$stuff_id?>'">
         <!--<input type="button" value="戻る" onClick="javascript:history.go(-1)" />-->
                    </div>
            </div>
            </form>



        </div>
		<input type="hidden" id="user_id" value="<?=$user_id?>" />


     </div>
 </div>
<?php
	include_once('inc/left_nav.inc.php');

	include_once("inc/new.footer.inc.php");
?>
<?php
//            $name_rows = $obj->GetAllRow("spssp_tables_name ORDER BY display_order asc ;");
          $query_string="SELECT * FROM spssp_tables_name  ORDER BY display_order asc ;";
		  $name_rows = $obj->getRowsByQuery($query_string);
?>

        <div id="table_edit_name" title="卓編集" style="font-size: 12px;">
            <form action="" method="post" id="table_edit_form" name="table_edit_form">
            <fieldset style="height:100px;">

                <input type="hidden" name="table_id" value="" id="table_id" />

                <table align="center" border="0">

                    <tr>
                        <td> 卓名: </td>
                        <td>
                        <select id="table_name">
                            <?php
                            	echo '<option value="-"> </option>';
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
