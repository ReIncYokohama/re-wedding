<?php
	require_once("inc/class.dbo.php");
	include_once("inc/checklogin.inc.php");
	include_once("inc/new.header.inc.php");
	$obj = new DBO();

	$table='spssp_room';
	$where = " 1=1";
	$data_per_page=5;
	$current_page=(int)$_GET['page'];
	$redirect_url = 'rooms.php';

	$pageination = $obj->pagination($table, $where, $data_per_page,$current_page,$redirect_url);

	$get = $obj->protectXSS($_GET);

	$room_id = $get['room_id'];
	unset($get['room_id']);

	if($_GET['action']=='delete' && (int)$_GET['id'] > 0)
	{

		$delete_query="Update spssp_room set status=0 where id=".(int)$_GET['id'];
		mysql_query($delete_query);

		//delete all information
		/*

		$tables= $obj->GetAllRowsByCondition("spssp_default_plan_table"," room_id=".(int)$_GET['id']);
		foreach($tables as $tbl)
		{
			$obj->DeleteRow("spssp_default_plan_seat"," table_id=".$tbl['id']);
		}

		$obj->DeleteRow("spssp_default_plan_table"," room_id=".(int)$_GET['id']);

		$obj->DeleteRow("spssp_room"," id=".(int)$_GET['id']);

		$user_rows = $obj->getRowsByQuery("select * from spssp_user where room_id = ".(int)$_GET['id']);

		foreach($user_rows as $user)
		{
			$usei_id=$user['id'];
			$obj->DeleteRow("spssp_table_layout","user_id= ".(int)$user_id);
			$user_plan =  $obj->GetSingleRow("spssp_plan"," user_id=".(int)$user_id);
			$obj->DeleteRow("spssp_plan_details"," plan_id='".(int)$user_plan['id']."'");

			$obj->DeleteRow("spssp_plan","user_id= ".(int)$user_id);
		}
		*/

	}
	else if($_GET['action']=='sort' && (int)$_GET['id'] > 0)
	{

		$id = $get['id'];
		$move = $get['move'];
		//$redirect = 'rooms.php?page='.(int)$get['page'];

		$obj->sortItem2($table,$id,$move);
	}

	//$query_string="SELECT * FROM spssp_room  ORDER BY display_order DESC LIMIT ".((int)($current_page)*$data_per_page).",".((int)$data_per_page).";";
	$query_string="SELECT * FROM spssp_room where status=1  ORDER BY display_order ASC ;";
	$data_rows = $obj->getRowsByQuery($query_string);

if($_SESSION["new_rooms"]=="success")
	{
		unset($_SESSION["new_rooms"]);
?>
<script type="text/javascript">
alert("新しい披露宴会場が登録されました");
</script>
<?php
	}
?>
<script type="text/javascript">
var roomsName=new Array(); // regular array


$(document).ready(function(){

    $('#max_rows').keyup(function(){
		var r=isInteger("max_rows");
    });
	$('#max_columns').keyup(function(){
		var r=isInteger("max_columns");
    });
	$('#max_seats').keyup(function(){
		var r=isInteger("max_seats");
    });

	var msg_html=$("#msg_rpt").html();

	if(msg_html!='')
	{
		$("#msg_rpt").fadeOut(5000);
	}
});
function isInteger(id){
 var i;
 var s=$("#"+id).val();
    for (i = 0; i < s.length; i++){
        // Check that current character is number.
        var c = s.charAt(i);
       if(i==0&&c==0)
	   {
	   		var msg="0の入力はできません。";
			$('#'+id).attr("value","");
			alert(msg);
	   }
	    if (((c < "0") || (c > "9")))
		{
			var msg="「最大卓数には半角数字で入力してください」";
			$('#'+id).attr("value","");
			 alert(msg);
		}
    }
    // All characters are numbers.
    return true;
}

function validForm()
{
	var name  = document.getElementById('name').value;
	var max_rows  = document.getElementById('max_rows').value;
	var max_columns  = document.getElementById('max_columns').value;
	var max_seats  = document.getElementById('max_seats').value;
	var flag = true;
	if(!name)
	{
		 alert("披露宴会場名を入力してください");
		 document.getElementById('name').focus();
		 return false;
	}
	if($.inArray(name,roomsName)!=-1)
	{
		alert("同じ名は登録できません。");
		document.getElementById('name').focus();
		return false;
	}
	if(!max_rows)
	{
		 alert("縦（段）を入力してください");
		 document.getElementById('max_rows').focus();
		 return false;
	}
	else if(isNaN(parseInt(max_rows)))
	{
		 alert("最大卓数は半角数字で入力してください");
		 document.getElementById('max_rows').focus();
		 return false;
	}


	if(!max_columns)
	{
		 alert("横（列）を入力してください");
		 document.getElementById('max_columns').focus();
		return false;
	}
	else if(isNaN(parseInt(max_columns)))
	{
		 alert("最大卓数は半角数字で入力してください");
		 document.getElementById('max_columns').focus();
		 return false;
	}

	if(!max_seats)
	{
		 alert("人数が未入力です");
		 document.getElementById('max_seats').focus();
		 return false;
	}
	else if(isNaN(parseInt(max_seats)))
	{
		 alert("最大卓数は半角数字で入力してください");
		 document.getElementById('max_seats').focus();
		 return false;
	}

	document.room_form.submit();
}


function isInteger(id){
 var i;
 var s=$("#"+id).val();
    for (i = 0; i < s.length; i++){
        // Check that current character is number.
        var c = s.charAt(i);
       if(i==0&&c==0)
	   {
	   		var msg="0の入力はできません。";
			$('#'+id).attr("value","");
			alert(msg);
	   }
	    if (((c < "0") || (c > "9")))
		{
			var msg="「最大卓数には半角数字で入力してください」";
			$('#'+id).attr("value","");
			 alert(msg);
		}
    }
    // All characters are numbers.
    return true;
}
function preview_room(room_id)
{
		document.room_form.room_id.value=room_id;

		$.post('ajax/room_preview.php',{'room_id':room_id}, function(data){
		$("#table_preview div").fadeOut(100);
		$("#table_preview div").html(data);
		$("#table_preview div").fadeIn(500);
		$(".box5").css({'background-color': '', 'color': ''});
		$(".box6").css({'background-color': '', 'color': ''});
		$("#boxid"+room_id).css({'background-color': '#FFF0FF', 'color': '#990000'});
	});

}
function cancel_new()
{
		$("#name").val('');
		$("#max_columns").val('1');
		$("#max_rows").val('1');
		$("#max_seats").val('4');

	//$("#new_table").fadeOut(300);
}
function confirmDeletePlus(urls)
{
   	var agree = confirm("会場名を削除しても宜しいですか？");
	if(agree)
	{
		var urlPlus = urls+"&room_id="+document.room_form.room_id.value;
		window.location = urlPlus;
	}
}
function sort_view(urls) {
	var urlPlus = urls+"&room_id="+document.room_form.room_id.value;
	window.location = urlPlus;
}
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

    <div id="top_btn" >
        <a href="logout.php"><img src="img/common/btn_logout.jpg" alt="ログアウト" width="102" height="19" /></a>　
        <a href="javascript:;" onclick="MM_openBrWindow('../support/operation_h.html','','scrollbars=yes,width=620,height=600')"><img src="img/common/btn_help.jpg" alt="ヘルプ" width="82" height="19" /></a>
    </div>
</div>
<div id="container">
    <div id="contents">
    	 <h4>  <div style="width:400px;">
            	 席次表・席札 &raquo; 会場レイアウト</div>
        </h4>
		<h2><div style="width:1035px;"><?php if ($_SESSION['user_type'] =="222") {echo '会場レイアウト';} else {echo '会場レイアウト設定';} ?></div></h2>
        
<!-- SEKIDUKA EDIT 11/10/22 会場レイアウトプレビューを横に表示する -->
      <table width="1035px" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="400" valign="top">
         <?php
        	if(isset($_GET['err']) && $_GET['err']!='')
			{
				echo "<script>
			alert('".$obj->GetErrorMsgNew((int)$_GET['err'])."');
			</script>";


			}
		?>
<!-- UCHIDA EDIT 11/08/08 横一列の表示を縦一列に変え、横にプレビューを表示する -->
		 <?php if($_SESSION['user_type']!="" && $_SESSION['user_type'] !="222"){ ?>
		<div style="width:400px;">
        <p class="txt3">
        	<form action="room_new.php?page=<?=$_GET['page']?>" method="post" name="room_form">
                披露宴会場名：<label for="textfield"></label>   <input name="name" type="text" id="name" size="40" />
				<br /><br />

         		最大卓数　　：横 <label for="textfield2"></label> <!--  <input name="max_columns" type="text" id="max_columns"  maxlength="1" size="1" />-->
                <select name="max_columns"  id="max_columns">
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
				<option value="6">6</option>
				<option value="7">7</option>
				<option value="8">8</option>
				<option value="9">9</option>
				</select>

          列×縦


                <label for="textfield3"></label><!-- <input name="max_rows" type="text" id="max_rows" maxlength="1" size="1" />-->
                <select name="max_rows"  id="max_rows">
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
				<option value="6">6</option>
				<option value="7">7</option>
				<option value="8">8</option>
				<option value="9">9</option>
				</select>
                  段　
				<br /><br />

                一卓人数　　：<label for="textfield4"></label>
				<select name="max_seats"  id="max_seats">
				<option value="4">4</option>
				<option value="6">6</option>
				<option value="8">8</option>
				<option value="10">10</option>
				<option value="12">12</option>
				</select>

				 <!--<input name="max_seats" type="text" id="max_seats" maxlength="2" size="1" />-->    名まで
				<br /><br />

        　　	<a href="#" onclick="validForm();">
                    <img src="img/common/btn_regist.jpg" alt="登録" width="82" height="22"  />

                </a>&nbsp;&nbsp;
					<a href="#"><img width="82" height="22" border="0" src="img/common/btn_clear.jpg" alt="クリア" onclick="cancel_new();""></a>
				<input type="hidden" name="room_id" value="0" />
             </form>
         </p></div>
         <?php } else {?>
         	<form method="post" name="room_form">
				<input type="hidden" name="room_id" value="0" />
            </form>
         <?php }?>
         
         <br /><br /><br /><br /><br /><br />
         
         </td>
    <td width="635" align="center" valign="middle">
<!-- UCHIDA EDIT 11/08/08 テーブルレイアウト表示を画面上に移動  -->
        <div class="sekiji_table" id="table_preview" >
        <div align="center">
			<table width="500px" style = " text-align:center;" align="center" border="0" cellspacing="10" cellpadding="0">

            <?php
            	if(isset($default_id) && $default_id > 0)
				{
					$room_row = $obj->GetSingleRow("spssp_room");

					$room_tables = $obj->GetAllRowsByCondition("spssp_default_plan_table"," room_id=".$room_row['id']);
					//echo "<pre>";
					//print_r($room_tables);
					$names = array();
					foreach($room_tables as $rt)
					{
						$names[] = $rt['name'];
					}

					$default_layout_title = $obj->GetSingleData("spssp_options" ,"option_value" ," option_name='default_layout_title'");
			?>
            	 <tr>
              		<td colspan="<?=$room_row['max_columns']?>" align="center" valign="middle">
					<?php
					if($default_layout_title!="")
					{
						echo "<div id='default_layout_title' onclick='user_layout_title_input_show(\"default_layout_title\");' style='display:block;text-align:center;width:100px;margin:0 auto;border:1px solid gray;'>".$default_layout_title."</div>";
					}
					else
					{
						echo "<img src='img/sakiji_icon/icon_takasago.gif' width='102' height='22' />";
					}
					?>
					</td>
            	</tr>
            <?php

					for($r = 1; $r <= $room_row['max_rows']; $r++)
					{
						echo "<tr>";
						for($col = 1; $col <= $room_row['max_columns']; $col++)
							{
								//echo "<td>ahad</td>";
								echo "<td align='center' valign='middle'><div style=\"width:31px; text-align:center; height:24px; padding-top:7px; background-image:url('img/circle_small.jpg')\"><span class='tbl_name'>".mb_substr (array_shift($names), 0,1,'UTF-8')."</span></div></td>";
							}
						echo "</tr>";
					}

				}
				else {
					echo "<div align='center'> プレビューボタンで会場のレイアウトが確認できます </div>";
				}
			?>
			</table>
		</div>
    </div></td>
  </tr>
</table>

<br />
<br />


<!-- テーブルレイアウト  -->

<div class="box_table" style="width:1000px; color: black;">
            <!--<div class="page_next"><?=$pageination?></div>-->
            <div class="box4">
                <table border="0" align="center" cellpadding="1" cellspacing="1">
                    <tr align="center">
                        <td width ="35" >披露宴会場名</td>
                        <td width ="20">最大卓数</td>
                        <td width ="20">一卓人数</td>
                       <?php if($_SESSION['user_type']!="" && $_SESSION['user_type'] !="222"){?> <td width ="20">順序変更</td><?php } ?>
                        <td width ="20">イメージ</td>
                        <?php if($_SESSION['user_type']!="" && $_SESSION['user_type'] !="222"){?> <td width ="20">編集</td><?php } ?>
                        <?php if($_SESSION['user_type']!="" && $_SESSION['user_type'] !="222"){?> <td width ="20">削除</td><?php } ?>
                    </tr>
                </table>
            </div>
            <?php
			$i=0;
			foreach($data_rows as $row)
			{
				if($i%2==0)
				{
					$class = 'box5';
				}
				else
				{
					$class = 'box6';
				}
				if($i==0)
				{
					$chk = "checked='checked'";
					$default_id = $row['id'];
				}
				else
				{
					$chk='';
				}

			?>
			<script language="javascript" type="text/javascript">
			roomsName[<?=$i?>]="<?=$row['name']?>";
			</script>
                <div class="<?=$class?>"  id="boxid<?=$row['id']?>">
                    <table border="0" align="center" cellpadding="1" cellspacing="1">
                        <tr align="center">
                            <td width ="35" ><?=$row['name']?></td>
                            <!--<td><a href="plans.php?room_id=<?=$row['id']?>"><?=$row['name']?></a></td>-->
                            <td width ="20" >横<?=$row['max_columns']?>列 × 縦<?=$row['max_rows']?>段</td>
                            <td width ="20" ><?=$row['max_seats']?>名</td>
							<?php if($_SESSION['user_type']!="" && $_SESSION['user_type'] !="222"){ ?>
                            <td width ="20" class="txt1">
    							<a href="javascript:void(0);" onClick="sort_view('rooms.php?page=<?=(int)$_GET['page']?>&action=sort&amp;move=up&amp;id=<?=$row['id']?>')">▲</a> &nbsp;
                				<a href="javascript:void(0);" onClick="sort_view('rooms.php?page=<?=(int)$_GET['page']?>&action=sort&amp;move=down&amp;id=<?=$row['id']?>')">▼</a>
                             </td>
							 <?php } ?>
                            <td width ="20" >
							  <a href="javascript:void(0);" onClick="preview_room(<?=$row['id']?>)"><img src="img/common/btn_preview.gif" id="room_<?=$row['id']?>"></a>
                            </td>
							<?php if($_SESSION['user_type']!="" && $_SESSION['user_type'] !="222"){?>
                           <td width ="20" >
                            	<a href="roomTableEdit.php?room_id=<?=$row['id']?>">
                            		<img src="img/common/btn_room_edit.gif" width="62" height="17" />
                                </a>
                            </td>
							<?php }	 ?>
							<?php if($_SESSION['user_type']!="" && $_SESSION['user_type'] !="222"){?>
                            <td width ="20" >
                            	<a href="javascript:void(0);" onClick="<?php if($_SESSION['user_type']!="" && $_SESSION['user_type'] =="222"){?>alert('権限がありません');<?php }else{?>confirmDeletePlus('rooms.php?page=<?=(int)$_GET['page']?>&action=delete&id=<?=$row['id']?>'); <?php }?>">
                            		<img src="img/common/btn_deleate.gif" width="42" height="17" />
                                </a>
                            </td>
							<?php }	 ?>
                        </tr>
                    </table>
                </div>
			<?php
            	$i++;
			}
			?>
      	</div>
      	<?php
		if ($room_id>0) {
			echo "<script> preview_room($room_id); </script>";
		}
		?>
    </div>

</div>
<?php
include_once('inc/left_nav.inc.php');

	include_once("inc/new.footer.inc.php");
?>

