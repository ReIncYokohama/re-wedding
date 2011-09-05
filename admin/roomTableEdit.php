<?php
	require_once("inc/class.dbo.php");
	include_once("inc/checklogin.inc.php");
	include_once("inc/new.header.inc.php");
	include_once("inc/class_information.dbo.php");

	$obj = new DBO();
	$objInfo = new InformationClass(); // UCHIDA EDIT 11/09/02

	$table='spssp_room';
	$where = " 1=1";
	$data_per_page=10;
	$current_page=(int)$_GET['page'];

	$get = $obj->protectXSS($_GET);
	$default_id = $get['room_id'];
	if($_POST['save'])
	{
		$room_row = $obj->GetSingleRow("spssp_room", " id=".$default_id);
		$room_tables = $obj->GetAllRowsByCondition("spssp_default_plan_table"," room_id=".$room_row['id']);
		foreach($room_tables as $rt)
			{
					$tid  = $rt['id'];
					$arr['name'] =$_POST["table_name_".$tid];
					$arr2['id'] = $objInfo->get_table_id($arr['name']);
					if(isset($tid) && $tid > 0 && $arr['name']!="")
					{
						$sql="update spssp_default_plan_table set name='".$arr2['id']."' where id='".$tid."';";
						mysql_query($sql);
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
							$names[] = $objInfo->get_table_name($rt['name']);
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

					$_ii=1;
					for($r = 1; $r <= $room_row['max_rows']; $r++)
					{

						echo "<tr>";
						for($col = 1; $col <= $room_row['max_columns']; $col++)
							{
								 $nameID=array_shift($names);
								 $TABLEID=array_shift($tID);
								//echo "<td>ahad</td>";
								echo "<td align='center' valign='middle'>";
								echo '<select id="table_name_'.$TABLEID.'" name="table_name_'.$TABLEID.'" onChange="Multicheck();">';
								$table_id[$_ii] = $TABLEID; $_ii++;
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
			<input type="hidden" name="save" value="save">
		  	&nbsp;&nbsp;<input type="button" value="保存" name="save_table" onclick="form_tableedit();"/>
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

<script type="text/javascript">
function Multicheck() {
var cnt;
var arry;
arry = new Array();
cnt = "<?=count($table_id); ?>";
arry[1]   = "<?=$table_id[1]; ?>";
arry[2]   = "<?=$table_id[2]; ?>";
arry[3]   = "<?=$table_id[3]; ?>";
arry[4]   = "<?=$table_id[4]; ?>";
arry[5]   = "<?=$table_id[5]; ?>";
arry[6]   = "<?=$table_id[6]; ?>";
arry[7]   = "<?=$table_id[7]; ?>";
arry[8]   = "<?=$table_id[8]; ?>";
arry[9]   = "<?=$table_id[9]; ?>";
arry[10]  = "<?=$table_id[10]; ?>";
arry[11]  = "<?=$table_id[11]; ?>";
arry[12]  = "<?=$table_id[12]; ?>";
arry[13]  = "<?=$table_id[13]; ?>";
arry[14]  = "<?=$table_id[14]; ?>";
arry[15]  = "<?=$table_id[15]; ?>";
arry[16]  = "<?=$table_id[16]; ?>";
arry[17]  = "<?=$table_id[17]; ?>";
arry[18]  = "<?=$table_id[18]; ?>";
arry[19]  = "<?=$table_id[19]; ?>";
arry[20]  = "<?=$table_id[20]; ?>";
arry[21]  = "<?=$table_id[21]; ?>";
arry[22]  = "<?=$table_id[22]; ?>";
arry[23]  = "<?=$table_id[23]; ?>";
arry[24]  = "<?=$table_id[24]; ?>";
arry[25]  = "<?=$table_id[25]; ?>";
arry[26]  = "<?=$table_id[26]; ?>";
arry[27]  = "<?=$table_id[27]; ?>";
arry[28]  = "<?=$table_id[28]; ?>";
arry[29]  = "<?=$table_id[29]; ?>";
arry[30]  = "<?=$table_id[30]; ?>";
arry[31]  = "<?=$table_id[31]; ?>";
arry[32]  = "<?=$table_id[32]; ?>";
arry[33]  = "<?=$table_id[33]; ?>";
arry[34]  = "<?=$table_id[34]; ?>";
arry[35]  = "<?=$table_id[35]; ?>";
arry[36]  = "<?=$table_id[36]; ?>";
arry[37]  = "<?=$table_id[37]; ?>";
arry[38]  = "<?=$table_id[38]; ?>";
arry[39]  = "<?=$table_id[39]; ?>";
arry[40]  = "<?=$table_id[40]; ?>";
arry[41]  = "<?=$table_id[41]; ?>";
arry[42]  = "<?=$table_id[42]; ?>";
arry[43]  = "<?=$table_id[43]; ?>";
arry[44]  = "<?=$table_id[44]; ?>";
arry[45]  = "<?=$table_id[45]; ?>";
arry[46]  = "<?=$table_id[46]; ?>";
arry[47]  = "<?=$table_id[47]; ?>";
arry[48]  = "<?=$table_id[48]; ?>";
arry[49]  = "<?=$table_id[49]; ?>";
arry[50]  = "<?=$table_id[50]; ?>";
arry[51]  = "<?=$table_id[51]; ?>";
arry[52]  = "<?=$table_id[52]; ?>";
arry[53]  = "<?=$table_id[53]; ?>";
arry[54]  = "<?=$table_id[54]; ?>";
arry[55]  = "<?=$table_id[55]; ?>";
arry[56]  = "<?=$table_id[56]; ?>";
arry[57]  = "<?=$table_id[57]; ?>";
arry[58]  = "<?=$table_id[58]; ?>";
arry[59]  = "<?=$table_id[59]; ?>";
arry[60]  = "<?=$table_id[60]; ?>";
arry[61]  = "<?=$table_id[61]; ?>";
arry[62]  = "<?=$table_id[62]; ?>";
arry[63]  = "<?=$table_id[63]; ?>";
arry[64]  = "<?=$table_id[64]; ?>";
arry[65]  = "<?=$table_id[65]; ?>";
arry[66]  = "<?=$table_id[66]; ?>";
arry[67]  = "<?=$table_id[67]; ?>";
arry[68]  = "<?=$table_id[68]; ?>";
arry[69]  = "<?=$table_id[69]; ?>";
arry[70]  = "<?=$table_id[70]; ?>";
arry[71]  = "<?=$table_id[71]; ?>";
arry[72]  = "<?=$table_id[72]; ?>";
arry[73]  = "<?=$table_id[73]; ?>";
arry[74]  = "<?=$table_id[74]; ?>";
arry[75]  = "<?=$table_id[75]; ?>";
arry[76]  = "<?=$table_id[76]; ?>";
arry[77]  = "<?=$table_id[77]; ?>";
arry[78]  = "<?=$table_id[78]; ?>";
arry[79]  = "<?=$table_id[79]; ?>";
arry[80]  = "<?=$table_id[80]; ?>";
arry[81]  = "<?=$table_id[81]; ?>";

	for(var loop=1;loop<=cnt;loop++)
	{
		var tId="table_name_"+arry[loop];
		var table_name=	$("#"+tId).val();
		if(table_name!="" && table_name!="-") {
			for(var loop2 = loop+1; loop2<=cnt; loop2++) {
				var tId2="table_name_"+arry[loop2];
				var table_name2 =$("#"+tId2).val();
				if (table_name == table_name2) {
					alert("卓名 ["+table_name+"] が重複しています");
					document.getElementById(tId2).focus();
			 		return false;
				}
			}
		}
	}
	return true;
}
function form_tableedit() {
	if (Multicheck() == false) return false;
	document.tableedit.submit();
}
</script>

<?php
include_once('inc/left_nav.inc.php');
include_once("inc/new.footer.inc.php");
?>
