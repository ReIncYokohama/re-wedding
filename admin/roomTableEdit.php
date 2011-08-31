<?php
	require_once("inc/class.dbo.php");
	include_once("inc/checklogin.inc.php");
	include_once("inc/new.header.inc.php");
	$obj = new DBO();

	$table='spssp_room';
	$where = " 1=1";
	$data_per_page=10;
	$current_page=(int)$_GET['page'];


	$get = $obj->protectXSS($_GET);
	$default_id = $get['room_id'];

	if($_POST[submit])
	{
		$room_row = $obj->GetSingleRow("spssp_room", " id=".$default_id);
		$room_tables = $obj->GetAllRowsByCondition("spssp_default_plan_table"," room_id=".$room_row['id']);
		foreach($room_tables as $rt)
			{
					$tid  = $rt['id'];
					$arr['name'] =$_POST["table_name_".$tid];

					if(isset($tid) && $tid > 0 && $arr['name']!="")
					{

						$rrr=$obj->UpdateData("spssp_default_plan_table", $arr, " id = $tid");

					}
			}
		redirect("rooms.php");
	}

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
<script type="text/javascript">
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

				//alert(id);
				$("#table_"+id+ " a").html(name+"<span id = 'tnameid' style= 'display:none'>"+tnid+"</span>");

				var user_id = $("#user_id").val();

				$.post('ajax/roomTableNameEdit.php', {'tnane': name,'id':id}, function(data) {

					//alert(data);
					if(parseInt(data) > 0)
					{

						location.reload();
						/*$("#table_"+id+ " a").removeAttr('onclick');
						$("#table_"+id+ " a").click(function(){
							edit_table_name(id,name);
						});*/
					}
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

});
function edit_table_name(id)
{

	$("#table_id").val(id);

	var newname_id = $("#table_"+id+ " a span").html();
	//alert(newname_id);
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
// var title=$("title");
// $(title).html("会場レイアウト卓名編集");
</script>

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
    	 <h2> <div style="width:400px;">
            	席次表・席札 &gt;&gt; <a href="rooms.php">会場レイアウト</a> &gt;&gt; 会場レイアウト卓名</div>
        </h2>
		<h2><div style="width:350px">  会場レイアウト卓名設定</div></h2>


		<div style="font-size:12px; font-weight:bold;">
        		<?php
                $rooms = $obj->GetAllRow("spssp_room");

				if(is_array($rooms))
				{
					foreach($rooms as $room)
					{
						if($room['id']==$default_id)
						{
						   $room_name = $room['name'];
						   $roomName = $room['name'];
						}

					}
				}

			?>

       会場名：<?=$roomName;?>
        </div>


        <div class="sekiji_table2" id="table_preview">
		<form name="tableedit" action="" method="post" >
        	<div>
          <table width="100%" style = " text-align:center;" align="center" border="0" cellspacing="10" cellpadding="0">

            <?php
            	if(isset($default_id) && $default_id > 0)
				{
					$room_row = $obj->GetSingleRow("spssp_room", " id=".$default_id);

					$room_tables = $obj->GetAllRowsByCondition("spssp_default_plan_table"," room_id=".$room_row['id']);
					//echo "<pre>";
					//print_r($room_tables);
					$names = array();
					foreach($room_tables as $rt)
					{
						if(isset($rt['name']) && $rt['name'] !="")
						{
							$names[] = $rt['name'];
						}
						else
						{
							$names[] = "XXX";
						}
						$tID[] = $rt['id'];
					}
			?>
            	 <tr>
              		<td colspan="<?=$room_row['max_columns']?>" align="center" valign="middle">
					<?php
					$default_layout_title2 = $obj->GetSingleData("spssp_options" ,"option_value" ," option_name='default_layout_title'");
					if($default_layout_title2!="")
					{
						echo "<div id='default_layout_title' onclick='user_layout_title_input_show(\"default_layout_title\");' style='display:block;text-align:center;width:100px;margin:0 auto;border:1px solid gray;'>".$default_layout_title2."</div>";
					}
					?>
					</td>
            	</tr>
            <?php
				  $query_string="SELECT * FROM spssp_tables_name  ORDER BY display_order asc ;";
				//echo $query_string;
					$name_rows = $obj->getRowsByQuery($query_string);


					for($r = 1; $r <= $room_row['max_rows']; $r++)
					{


						echo "<tr>";
						for($col = 1; $col <= $room_row['max_columns']; $col++)
							{
								 $nameID=array_shift($names);
								 $TABLEID=array_shift($tID);
								//echo "<td>ahad</td>";
								echo "<td align='center' valign='middle'>";
								echo '<select id="table_name_'.$TABLEID.'" name="table_name_'.$TABLEID.'" >';
// UCHIDA EDIT 11/08/02
//								echo '<option value="-">-</option>';
								echo '<option value="-"> </option>';
								foreach($name_rows as $row)
									{
										if($row['name']==$nameID)
										$selected="SELECTED";
										else
										$selected="";
										echo "<option value='".$row['name']."' ".$selected.">".$row['name']."</option>";
									}

								echo '</select>';

								echo "</td>";
							}
						echo "</tr>";
					}

				}
			?>

          </table>
		  <table width="100%" border="0" cellspacing="2" cellpadding="2" align="center">
		  <tr>
		  <td>
		  	&nbsp;&nbsp;<input type="submit" value="保存" name="submit" />
			&nbsp;&nbsp;<input type="button" value="キャンセル" onclick="window.location='rooms.php'" /> <!-- UCHIDA EDIT 11/08/01 閉じる→キャンセル -->
		  </td>
		  </tr>
	</table>


          </div>
		</form>

    </div>
	 <br />
<div align="center">
卓名をクリックすると名前を選択変更できます
</div>
<br />
<!-- UCHIDA EDIT 11/08/03
<div align="center">
<a href="./rooms.php">会場レイアウト一覧に戻る</a>
</div>
 -->
    </div>

</div>
<?php
          $query_string="SELECT * FROM spssp_tables_name  ORDER BY display_order asc ;";
				//echo $query_string;
				$name_rows = $obj->getRowsByQuery($query_string);
		  // $name_rows = $obj->GetAllRow("spssp_tables_name");
			//$room_tables = $obj->GetAllRowsByCondition("spssp_default_plan_table"," room_id=".$room_row['id']);
        ?>
        <div id="table_edit_name" title="卓編集">
            <form action="" method="post" id="table_edit_form" name="table_edit_form">
            <fieldset style="height:100px;">

                <input type="hidden" name="table_id" value="" id="table_id" />



                <table align="center" border="0">

                    <tr>
                        <td> 卓名: </td>
                        <td>
                        <select id="table_name" >
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
include_once('inc/left_nav.inc.php');

	include_once("inc/new.footer.inc.php");
?>

