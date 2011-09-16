<?php
	require_once("inc/class.dbo.php");
	include_once("inc/checklogin.inc.php");
	include_once("inc/new.header.inc.php");
	$obj = new DBO();

	$get = $obj->protectXSS($_GET);
	$post = $obj->protectXSS($_POST);

	/*if($_SESSION['user_type'] != 111)
	{
		redirect("manage.php");
	}
*/

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
	if(isset($get['action']) && $get['action'] !== '')
	{
		$id = (int)$get['id'];
		if($id > 0)
		{
			$obj->DeleteRow("spssp_religion", "id=".$id);
			$obj->DeleteRow("spssp_party_room", "religion_id=".$id);

			$msg = 3;
			redirect('religions.php'); // UCHIDA EDIT 11/07/27
		}
		else
		{
			$err = 11;
		}
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
	window.location='religions.php?id='+id;

	if(adminType==222)
	{
		alert("権限がありません");
	}
	//else
//	{
//		$("#insert_edit").val(id);
//		$("#name").val(name);
//		$("#title_bar").html("編集　挙式種類");
//		$("#new_table").fadeOut(100);
//		$("#new_table").fadeIn(500);
//	}
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
        <?php if(isset($err)){echo "<script>
			alert('".$obj->GetErrorMsgNew($err)."');
			</script>";}?>

<!-- UCHIDA EDIT 11/07/27　↓
		<?php
//		if(isset($msg)){echo "<script>
//			alert('".$obj->GetSuccessMsgNew($msg)."');
//			</script>";}
		?>
 -->
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
		if($_GET['id'])
		  $getrow = $obj->GetSingleData('spssp_religion','title', 'id='.$_GET['id']);

	  ?>

		<div  id="new_table" style="display:block; width:1035px;">
        	<h2 id="title_bar">挙式種類設定 </h2>
        	<form action="religions.php" method="post" name="new_name">
            	<input type="hidden" name="insert_edit" id="insert_edit" value="<?=$_GET['id']?>" />
                挙式種類 : &nbsp;<input type="text" name="title" id="name"  value="<?=$getrow?>" /> &nbsp; &nbsp; &nbsp;
                 <a href="#"><img  onclick="check_name()"; border="0" height="22" width="82" alt="登録・更新" src="img/common/btn_regist_update.jpg"></a>
				 &nbsp;
               <a href="#"><img  onclick="cancel_new()"; border="0" height="22" width="82" alt="クリア" src="img/common/btn_clear.jpg"></a>
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
			$orderItem = ($_GET['sort'] =='')?'ASC': $_GET['sort'];
            	$query_string="SELECT * FROM spssp_religion  ORDER BY display_order $orderItem ;";
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
							 <span class="txt1"><a href="sort.php?table=religion&sort=ASC&move=up&id=<?=$row['id']?>&pagename=religions">▲</a>
                             <a href="sort.php?table=religion&sort=ASC&move=down&id=<?=$row['id']?>&pagename=religions"> ▼</a></span>
              				</td>
							<td>
                            	<a href="#" onclick="edit_name(<?=$row['id']?>,'<?=$row['title']?>',<?=$_SESSION['user_type']?>);">
                                	<img src="img/common/btn_edit.gif" width="42" height="17" />
                                </a>
                            </td>
              				<td>
                            	<a href="javascript:void(0);" onClick="<?php if($_SESSION['user_type']==222){?>alert('権限がありません');<?php }else{?>confirmDelete('religions.php?action=delete&id=<?=$row['id']?>');<?php }?>">
                                	<img src="img/common/btn_deleate.gif" width="42" height="17" />
                                </a>
                            </td>
							<?php } else {?>
							<td></td>
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
       <? if($_GET['id'] !=''){
?>
<script>
$("#boxid<?=$_GET['id']?>").css({backgroundColor: "#FFF0FF", color: "#990000"});
</script>
<? }?>
			</div>
        </div>
 </div>
<?php
	include_once("inc/left_nav.inc.php");
	include_once("inc/new.footer.inc.php");
?>
