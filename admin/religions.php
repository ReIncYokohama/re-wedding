<?php
	require_once("inc/class.dbo.php");
	include_once("inc/checklogin.inc.php");
	include_once("inc/new.header.inc.php");
	$obj = new DBO();

	$get = $obj->protectXSS($_GET);
	$post = $obj->protectXSS($_POST);

	if(isset($post['title']) && $post['title'] != '')
	{
		$id = (int)$post['insert_edit'];
		unset($post['insert_edit']);
		if($id <= 0)
		{
			 $post['display_order']=time();
			$lid = $obj->InsertData("spssp_religion", $post);
			if($lid > 0)
			{
				$msg = 1;
			}
			else
			{
				$err = 1;
			}
		}
		else
		{
			$obj->UpdateData("spssp_religion", $post," id=".$id);
			$msg = 2;
		}

	}
	if(isset($get['action']) && $get['action'] == 'delete')
	{
		$id = (int)$get['id'];
		if($id > 0)
		{
			$obj->DeleteRow("spssp_religion", "id=".$id);
			$obj->DeleteRow("spssp_party_room", "religion_id=".$id);

			$msg = 3;
			$get['id'] = $get['edit_id'];
			//redirect('religions.php'); // UCHIDA EDIT 11/07/27
		}
		else
		{
			$err = 11;
		}
	}
	if(isset($get['action']) && $get['action']=='sort' && (int)$get['id'] > 0) {
		$table = 'spssp_religion';
		$id = $get['id'];
		$move = $get['move'];
		if($move=="down") $move="up"; else $move="down";
		$obj->sortItem($table,$id,$move);
		$get['id']=$get['edit_id'];
	}
	if(isset($get['action']) && $get['action']=='edit' && (int)$get['id'] > 0) {
		$get['name']="";
	}
?>
<script type="text/javascript">

var kindArray=new Array(); // UCHIDA EDIT 11/07/27

$(function(){

	var msg_html=$("#msg_rpt").html();

	if(msg_html!='')
	{
		$("#msg_rpt").fadeOut(5000);
	}
});

function new_table()
{
	$("#insert_edit").val("0");
	$("#title_bar").html("登録　挙式種類");
	$("#name").val("");
	$("#new_table").toggle("slow");
}
function cancel_new()
{
	window.location='religions.php';
	//$("#name").val("");
}

function check_name()
{
var kindname = document.getElementById('name').value; // UCHIDA EDIT 11/07/27
	if($("#name").val() == '')
	{
		alert("挙式種類を入力してください");
		$("#name").focus();
		return false;
	}
 // UCHIDA EDIT 11/07/27 ↓
	if($.inArray(kindname,kindArray)!=-1) {
		alert("同じ挙式種類が存在するため登録･更新ができませんでした");
		$("#name").focus();
		return false;
	}
 // UCHIDA EDIT 11/07/27 ↑
	document.new_name.submit();
}

function edit_name(id, name,adminType)
{
	window.location='religions.php?id='+id+"&action=edit";

	if(adminType==222)
	{
		alert("権限がありません");
	}
}

function confirmDeletePlus(urls, id)
{
	var edit_id="<?=$get['id']?>";
	var agree = confirm("挙式種類を削除してもよろしいですか？");
	if(agree)
	{
		if (edit_id != id) window.location = collecting_data(urls);
		else               window.location = urls;
	}
}

function orderAction(url) {
	window.location = collecting_data(url);
}

function collecting_data(url) {
var edit_data;
var urlPlus;
var edit_id="<?=$get['id']?>";
	edit_data  = "&name="+document.new_name.name.value;
	edit_data += "&edit_id="+edit_id;
	urlPlus = url+edit_data;
	return urlPlus;
}

window.onkeydown = function(event) {
    if(event.keyCode == 13)	return false;
    else 					return true;
}

</script>
<div id="topnavi">
    <?php
include("inc/main_dbcon.inc.php");
$hcode=$HOTELID;
$hotel_name = $obj->GetSingleData(" super_spssp_hotel ", " hotel_name ", " hotel_code=".$hcode);
include("inc/return_dbcon.inc.php");
?>
<h1><?=$hotel_name?></h1>

    <div id="top_btn">
        <a href="logout.php"><img src="img/common/btn_logout.jpg" alt="ログアウト" width="102" height="19" /></a>　
        <a href="javascript:;" onclick="MM_openBrWindow('../support/operation_h.html','','scrollbars=yes,width=620,height=600')"><img src="img/common/btn_help.jpg" alt="ヘルプ" width="82" height="19" /></a>
    </div>
</div>

<div id="container">

	<div style="clear:both;"></div>
	<div id="contents">
		<h4><div style="width:300px;"> 席次表・席札  &raquo; 挙式種類</div></h4>
		<!--<p class="txt3">
            <a href="default.php"><b>テーブル名</b></a>&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;
            <a href="respects.php"><b>敬称</b></a>&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;
            <a href="guest_types.php"> <b>区分</b></a>&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;
            <b>挙式種類</b>
      </p>-->
        <?php if(isset($err)){
        	echo "<script>
				 alert('".$obj->GetErrorMsgNew($err)."');
				 </script>";
        }?>

		<?php
		switch ($msg) {
		    case 1:
				echo "<script> alert('新しい挙式種類が登録されました'); </script>";
		        break;
		    case 2:
				echo "<script> alert('挙式種類が更新されました'); </script>";
		        break;
		    case 3: // アラーとなし
		    	break;
		    default:
		        break;
		}
		?>
<!-- UCHIDA EDIT 11/07/27　↑ -->
      <?php

      if($_SESSION['user_type'] != 222)
		{
		if($get['name']) {
			$getrow=$get['name'];
		}
		else if($get['id']) {
		  $getrow = $obj->GetSingleData('spssp_religion','title', 'id='.$get['id']);
		}

	  ?>

		<div  id="new_table" style="display:block; width:1035px;">
        	<h2 id="title_bar">挙式種類設定 </h2>
        	<form action="religions.php" method="post" name="new_name">
            	<input type="hidden" name="insert_edit" id="insert_edit" value="<?=$get['id']?>" />
                挙式種類 ：&nbsp;
<input type="text" name="title" id="name" style="border-style: inset;"  value="<?=$getrow?>" onkeydown="check_name(e)" /> &nbsp; &nbsp; &nbsp;
                 <a href="#"><img  onclick="check_name()" border="0" height="22" width="82" alt="登録・更新" src="img/common/btn_regist_update.jpg" /></a>
				 &nbsp;
               <a href="#"><img  onclick="cancel_new()" border="0" height="22" width="82" alt="クリア" src="img/common/btn_clear.jpg" /></a>
			     &nbsp;

            </form><br />

        </div>
		<!--<p class="txt3"><div style="width:100px">
        	<a href="javascript:void(0);" onclick="new_table()"> <b>新規登録</b></a>	</div>
        </p>-->

		<?php
		}
		?>

        <p>&nbsp;</p>
        <div class="box_table" style="width:700px;">
            <div class="box4">
                <table border="0" align="center" cellpadding="1" cellspacing="1">
                <tr align="center">
                  <td width="10%">No.</td>
                  <td>挙式種類</td>
                   <td>順序変更</td>
				   <td>編集</td>
                   <td>削除</td>
                </tr>
              </table>
            </div>
            <?php
            	$query_string="SELECT * FROM spssp_religion  ORDER BY display_order ASC ;";
				$data_rows = $obj->getRowsByQuery($query_string);

				$i=0;
				$j=1;

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
			?>
				<!-- UCHIDA EDIT 11/07/27 ↓ -->
				<script language="javascript" type="text/javascript">
				kindArray[<?=$i?>]="<?=$row['title']?>";
				</script>
				<!-- UCHIDA EDIT 11/07/27 ↑ -->
				<div class="<?=$class?>" id="boxid<?=$row['id']?>">
            		<table border="0" align="center" cellpadding="1" cellspacing="1">
            			<tr align="center">
                        	<td width="10%"><?=$j?></td>
                            <td><?=$row['title']?></td>
                            <!--<td><a href="party_rooms.php?religion_id=< ?=$row['id']?>">挙式会場</a></td>-->
                            <?php if($_SESSION['user_type']!="" && $_SESSION['user_type'] !="222"){?>
							<td>
							 <span class="txt1">
							 <a href="javascript:void(0);" onClick="orderAction('religions.php?action=sort&move=up&id=<?=$row['id']?>');">▲</a>
                             <a href="javascript:void(0);" onClick="orderAction('religions.php?action=sort&move=down&id=<?=$row['id']?>');">▼</a>
                             </span>
              				</td>
							<td>
                            	<a href="#" onclick="edit_name(<?=$row['id']?>,'<?=$row['title']?>',<?=$_SESSION['user_type']?>);">
                                	<img src="img/common/btn_edit.gif" width="42" height="17" />
                                </a>
                            </td>
              				<td>
                            	<a href="javascript:void(0);" onClick="<?php if($_SESSION['user_type']==222){?>alert('権限がありません');<?php }else{?>confirmDeletePlus('religions.php?action=delete&id=<?=$row['id']?>', <?=$row['id']?>);<?php }?>">
                                	<img src="img/common/btn_deleate.gif" width="42" height="17" />
                                </a>
                            </td>
							<?php } else {?>
							<td ><font color="gray">▲▼</font></td>
							<td><img src="img/common/btn_edit_greyed.gif" width="42" height="17" /></td>
							<td><img src="img/common/btn_deleate_greyed.gif" width="42" height="17" /></td>
							<?php } ?>
            			</tr>
                     </table>
        		</div>
             <?php
			 	$i++;
				$j++;
             	}
			 ?>
		<? if($get['id'] !='') {?>
			<script>
				$("#boxid<?=$get['id']?>").css({backgroundColor: "#FFF0FF", color: "#990000"});
			</script>
		<? } ?>
			</div>
        </div>
		<script type="text/javascript"> document.new_name.name.focus(); </script>
 </div>
<?php
	include_once("inc/left_nav.inc.php");
	include_once("inc/new.footer.inc.php");
?>
