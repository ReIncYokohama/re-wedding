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
	}*/
	if($_POST['sub']!='')
	{
		$whereCloser = array('confirm_day_num','default_layout_title','rename_table_view','limitation_ranking');
		for($i=0;$i<count($whereCloser);$i++)
		{

			$sql="Update spssp_options set option_value='".$_POST[$whereCloser[$i]]."' where option_name='".$whereCloser[$i]."'";

			mysql_query($sql);
		}

		$tablename['name'] = $_POST['name'];
        if($_POST['name'] !='')
		{
			if($_POST['edit_table_name'] !='')
			{

			   if(checkTableDuplicasy($tablename['name'],$_POST['edit_table_name']))
			   {
				   $sql="Update spssp_tables_name set name='".$_POST['name']."' where id=".$_POST['edit_table_name'];

				   mysql_query($sql);
				   redirect("default.php?meg=4");exit;

			   }
			}
			else
			{

				if(checkTableDuplicasy($tablename['name']))
				{
					$tablename['display_order'] = time();
					$lid = $obj->InsertData("spssp_tables_name", $tablename);
					if($lid)
					{
						echo "<script> alert('新しい卓名が登録されました'); </script>";
						redirect("default.php?meg=1");exit;
					}
				}
			}
		}


	}
	if(isset($post['name']) && $post['name'] != '')
	{
		$id = (int)$post['insert_edit'];

		unset($post['insert_edit']);


		if($id <= 0)
		{
			if(checkTableDuplicasy($post['name']))
			{
				$post['display_order'] = time();
				$lid = $obj->InsertData("spssp_tables_name", $post);
				if($lid > 0)
				{
					//$msg = 1;
					echo "<script> alert('新しい卓名が登録されました'); </script>";
					redirect("default.php?meg=1");exit;
				}
				else
				{
					$err = 1;
				}
			}
			else
			{
				$err = 19;
			}
		}
		else
		{

			if(checkTableDuplicasy($post['name'] , $id ))
			{
				$obj->UpdateData("spssp_tables_name", $post," id=".$id);

				$msg = 4;
			}
			else
			{
				$err = 19;
			}
		}

	}
	if($post['DefaultSettings']=="DefaultSettings1")
	{

		unset($post['DefaultSettings']);
		unset($value['option_value']);

		$value['option_value '] = $post['confirm_day_num'];
		$res = $obj->UpdateData("spssp_options", $value," option_name='confirm_day_num'");
		if($res ==1){echo'<script>alert("校了日が変更になりました");</script>';}
	}
	if($post['DefaultSettings']=="DefaultSettings2")
	{

		unset($post['DefaultSettings']);
		unset($value['option_value']);

		$value['option_value '] = $post['DefaultLayoutTitle'];
		$res = $obj->UpdateData("spssp_options", $value," option_name='default_layout_title'");
		if($res ==1){$msg = 4;}
	}
	if($post['DefaultSettings']=="DefaultSettings3")
	{

		unset($post['DefaultSettings']);
		unset($value['option_value']);

		$value['option_value '] = $post['table_view'];
		$res = $obj->UpdateData("spssp_options", $value," option_name='rename_table_view'");
		if($res ==1){$msg = 4;}
	}
	if($post['DefaultSettings']=="DefaultSettings6")
	{

		unset($post['DefaultSettings']);
		unset($value['option_value']);

		$value['option_value '] = $post['limitation_ranking'];
		$res = $obj->UpdateData("spssp_options", $value," option_name='limitation_ranking'");
		if($res ==1){
		echo'<script>alert("席次表編集利用制限日が変更になりました");</script>';

		}
	}

	if($_GET['action']=='sort' && (int)$_GET['id'] > 0)
	{
		$table = 'spssp_tables_name';

		$id = $get['id'];
		$move = $get['move'];
		$obj->sortItem($table,$id,$move);
	}
	if(isset($get['action']) && $get['action'] !== '' && $get['action'] == "delete")
	{

		$id = (int)$get['id'];
		if($id > 0)
		{
			$suc = $obj->DeleteRow("spssp_tables_name", "id=".$id);
			if($suc){$msg = 3;}
		}
		else
		{
			$err = 11;
		}
	}
	//SORING START
	if(isset($_GET['order_by']) && $_GET['order_by'] != '')
	{
		$orderby = mysql_real_escape_string($_GET['order_by']);
		$dir = mysql_real_escape_string($_GET['asc']);

		if($orderby=='name')
		{
			$order=" name ";

		}


		if($dir == 'true')
		{
			$order.=' asc';
		}
		else
		{
			$order.=' desc';
		}

	}
	else
	{
		$order="display_order ASC";
	}
	//SORING END


	//check DEFaULT table NAME DUPLICACY
	function checkTableDuplicasy( $tname, $tableid = false )
	{
		$obj = new DBO();

		if($tableid!="" && $tableid>0)
		{
			$where_check = " id!='".$tableid."' and name like '%".$tname."%'";
		}
		else
		{
			$where_check = "  name like '%".$tname."%'";
		}

		$nm = $obj->GetRowCount("spssp_tables_name",$where_check);

		if($nm){return false;}else{return true;}
	}
?>
<?php
			$default_raname_table_view = $obj->GetSingleData("spssp_options" ,"option_value" ," option_name='rename_table_view'");
			$confirm_day_num = $obj->GetSingleData("spssp_options" ,"option_value" ," option_name='confirm_day_num'");
			$limitation_ranking = $obj->GetSingleData("spssp_options" ,"option_value" ," option_name='limitation_ranking'");
			$default_layout_title = $obj->GetSingleData("spssp_options" ,"option_value" ," option_name='default_layout_title'");
		?>
<script type="text/javascript">
var tablename='';
var confirmdaynum='<?=$confirm_day_num?>';
var limitationranking='<?=$limitation_ranking?>';
var Defaultlayouttitle='<?=$default_layout_title?>';
$(function(){

	var msg_html=$("#msg_rpt").html();

	if(msg_html!='')
	{
		$("#msg_rpt").fadeOut(5000);
	}
});
$(document).ready(function(){

    $('#confirm_day_num').keyup(function(){
		var r=isInteger("confirm_day_num");
    });

});

$(document).ready(function(){

    $('#limitation_ranking').keyup(function(){
		var r=isInteger("limitation_ranking");
    });

});
function isInteger(id){

 var i;
 var s=$("#"+id).val();

	var set =/^([0-9])$/;
	for (i = 0; i < s.length; i++){
        // Check that current character is number.
        var c = s.charAt(i);

	    if (!set.test(c))
		{
			var msg=" 半角数字で入力してください";
			$('#'+id).attr("value","");
			 alert(msg);
			 break;
		}
		if(i==0 && c<=0 )
		{
			var msg="0を入力することはできません";
			$('#'+id).attr("value","");
			 alert(msg);
			 break;
		}
    }
    // All characters are numbers.
    return true;
}

function formvalid1()
{
	var confirm_day_num = $("#confirm_day_num").val();
	if(confirm_day_num =="")
	{
		alert("校了日設定を入力してください");//Unable to empty fields
		$("#confirm_day_num").val(confirmdaynum);
		document.getElementById('confirm_day_num').focus();
		return false;
	}
	else
	{
		isInteger("confirm_day_num");
	}
	document.aaa1.submit();
}

function formvalid6()
{

	var limitation_ranking1 = $("#limitation_ranking").val();
	if(limitation_ranking1 =="")
	{
		alert("席次表編集利用制限日を入力ください");//Unable to empty fields
		$("#limitation_ranking").val(limitationranking);
		document.getElementById('limitation_ranking').focus();
		return false;
	}
	else
	{
		isInteger("limitation_ranking");
	}
	document.aaa6.submit();
}
function formvalid2()
{
	var DefaultLayoutTitle=$("#DefaultLayoutTitle").val();
	if(DefaultLayoutTitle =="")
	{
		alert("「高砂名」を入力してください");//Unable to empty fields

		$("#DefaultLayoutTitle").val(Defaultlayouttitle);
		document.getElementById('DefaultLayoutTitle').focus();
		return false;
	}
	document.aaa2.submit();
}
function formvalid3()
{
	document.aaa3.submit();
}
function new_table()
{
	$("#insert_edit").val("0");
	$("#title_bar").html("登録　卓名");
	$("#name").val("");
	$("#new_table").toggle("slow");
}
function cancel_new()
{
	$("#name").val("");
	//$("#new_table").fadeOut(300);
}

function check_name(id)
{
	if($("#name").val() == '')
	{
		alert("「卓名」を入力してください");
		$("#name").val(tablename);
		$("#name").focus();
		return false;
	}
	document.new_name.submit();
}
function edit_name(id, name,adminType,boxid)
{
	tablename=name;
	$("#edit_table_name").val(id);
	$("#"+boxid).css({backgroundColor: "#FFF0FF", color: "#990000"});
	if(adminType==222)
	{
		alert("権限がありません");
	}
	else
	{
		$("#insert_edit").val(id);
		$("#name").val(name);


		//$("#title_bar").html("編集　卓名");
		//$("#new_table").fadeOut(100);
		//$("#new_table").fadeIn(500);
	}
}

function validForm()
{
	var confirm_day_num = $("#confirm_day_num").val();
	if(confirm_day_num =="")
	{
		alert("校了日設定を入力してください");//Unable to empty fields
		$("#confirm_day_num").val(confirmdaynum);
		document.getElementById('confirm_day_num').focus();
		return false;
	}
	else
	{
		isInteger("confirm_day_num");
	}

	var limitation_ranking1 = $("#limitation_ranking").val();
	if(limitation_ranking1 =="")
	{
		alert("席次表編集利用制限日を入力ください");//Unable to empty fields
		$("#limitation_ranking").val(limitationranking);
		document.getElementById('limitation_ranking').focus();
		return false;
	}
	else
	{
		isInteger("limitation_ranking");
	}

	//var DefaultLayoutTitle=$("#DefaultLayoutTitle").val();
//	if(DefaultLayoutTitle =="")
//	{
//		alert("「高砂名」を入力してください");//Unable to empty fields
//
//		$("#DefaultLayoutTitle").val(Defaultlayouttitle);
//		document.getElementById('DefaultLayoutTitle').focus();
//		return false;
//	}

	//if($("#name").val() == '')
//	{
//		alert("「卓名」を入力してください");
//		$("#name").val(tablename);
//		$("#name").focus();
//		return false;
//	}

	document.defaultForm.submit();

}
function clearForm()
{
	window.location="default.php";
	//$("#confirm_day_num").val("");
//	$("#limitation_ranking").val("");
//	$("#DefaultLayoutTitle").val("");
//	$("#name").val("");
}
</script>
<div id="topnavi">
    <?php
include("inc/main_dbcon.inc.php");
$hcode="0001";
$hotel_name = $obj->GetSingleData(" dev2_main.super_spssp_hotel ", " hotel_name ", " hotel_code=".$hcode);
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

	<div style="clear:both;"></div>
	<div id="contents">
	<!-- UCHIDA EDIT 11/07/26 -->
	<!-- <h2><div style="width:450px;"><a href="manage.php">席次表・席札 </a> &raquo; 基本設定</div></h2> -->
	<h2><div style="width:450px;">席次表・席札 &raquo; 基本設定</div></h2>
		<!--<p class="txt3">
            <b>卓名</b>&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;
            <a href="respects.php"><b>敬称</b></a>&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;
            <a href="guest_types.php"> <b>区分</b></a>&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;
            <a href="religions.php"><b>挙式種類</b></a>
        </p>-->

<!-- UCHIDA EDIT 11/08/09 スタッフの場合は表示のみに変更  -->
		<?php
		if ($_SESSION['user_type']!="" && $_SESSION['user_type'] !="222") {
			$InputArea = "";
		}
		else {
			$InputArea = " disabled='disabled'";
		}
		?>
        <?php
//		if($_SESSION['user_type']!="" && $_SESSION['user_type'] !="222")
//		{
		?>

	  <div style="width:600px; border:0px solid #fff" >
	  <form name="defaultForm" action="default.php" method="post">
	  <input type="hidden" name="sub" value="1" />
	  <input type="hidden" name="edit_table_name" id="edit_table_name" value="<?=$_GET[id]?>" />
	  <input type="hidden" name="insert_edit" value="<?=$_GET[id]?>"  />

	  	<table width="600" border="0" cellspacing="0" cellpadding="0">
<!-- UCHIDA -->
		  <tr>
			<td style="height:1px; overflow:hidden; border:none; border-top:1px solid #3681CB; text-align:left;height:20px;vertical-align:middle; background:#E1ECF7; color:#2252A3; width:200px; font-size:13px;">&nbsp;&nbsp;本発注締切日</td>
			<td style="height:1px; overflow:hidden; border:none; border-top:1px solid #3681CB; text-align:left;height:20px;vertical-align:middle; background:#E1ECF7; color:#2252A3; width:200px; font-size:13px;">　　　　　　&nbsp;&nbsp;席次表編集利用制限日</td>
 		  </tr>
		  <tr>
			<td valign="middle" style=" vertical-align:middle; text-align:left; height:40px;">　　　　　　　&nbsp;

<?php 		if ($InputArea=="") {?> <input type="text" style="text-align:right" name="confirm_day_num" id="confirm_day_num" size="5" maxlength="2" onblur="isInteger('confirm_day_num')" value="<?=$confirm_day_num?>" />&nbsp;日前 <?php } else  echo $confirm_day_num." 日前"  ?>
			</td>
			<td valign="middle" style=" vertical-align:middle; text-align:left; height:40px;">　　　　　　　&nbsp;&nbsp;

<?php 		if ($InputArea=="") {?> <input type="text" style="text-align:right" name="limitation_ranking" id="limitation_ranking" size="5" maxlength="2" onblur="isInteger('limitation_ranking')" value="<?=$limitation_ranking?>" />&nbsp;日前 <?php } else	echo $limitation_ranking." 日前" ?>
			</td>
		  </tr>
		  <tr>
		    <td colspan="2" align="center" style="height:1px; overflow:hidden; border:none; border-top:1px solid #3681CB; text-align:left;height:20px;vertical-align:middle; background:#E1ECF7; color:#2252A3; height:20px; font-size:13px;">&nbsp;&nbsp;高砂卓名設定 </td>
		  </tr>
		  <tr>
		    <td colspan="2" >
				<table width="100%" border="0" cellspacing="1" cellpadding="4">
				  <tr>
					<td width="83" align="right" valign="middle">高砂卓名:</td>
					<td width="191" align="left" valign="middle">
<?php 				if ($InputArea=="") {?> <input type="text" name="default_layout_title"  id="DefaultLayoutTitle" value="<?=$default_layout_title?>" /> <?php } else	echo $default_layout_title ?>
					</td>
                    <td>&nbsp;</td>
					<td height="40">&nbsp;</td>

				  </tr>


		  <tr>
		    <td colspan="4" align="center" style="height:1px; overflow:hidden; border:none; border-top:1px solid #3681CB; text-align:left;height:20px;vertical-align:middle; background:#E1ECF7; color:#2252A3; height:20px; font-size:13px;">&nbsp;&nbsp;卓名設定 </td>
		  </tr>


                  <tr>
<?php 				if ($InputArea=="") { ?> <td align="right"> 卓名 : <?php } else echo "<td align='right'>　　　" ?>
					</td>
					<td align="left">
<?php 				if ($InputArea=="") { ?> <input type="text" name="name" id="name"  value="<?=$_GET['name']?>"/> <?php } else echo $_GET['name'] ?>
					</td>

					<td width="83" align="right" valign="middle">卓名変更:</td>
					<td width="206" height="40" valign="middle" >&nbsp;<input type="radio" value="1" name="rename_table_view" <?php 	if($default_raname_table_view == 1){echo "checked='checked'";}?> <?=$InputArea ?> >&nbsp;可&nbsp;&nbsp;
					<input type="radio" value="0" name="rename_table_view" <?php 	if($default_raname_table_view == "0"){echo "checked='checked'";}?> <?=$InputArea ?> >&nbsp;不可&nbsp;&nbsp;</td>


				  </tr>
				  <tr>
				    <td align="right">&nbsp;</td>
				    <td align="left">&nbsp;</td>
				    <td>&nbsp;</td>
				    <td>&nbsp;</td>
			      </tr>
				  <tr>
<?php 				if ($InputArea=="") { ?>
					    <td colspan="4" align="center" valign="middle"><a href="#"><img  onclick="validForm();"; border="0" height="22" width="82" alt="登録・更新" src="img/common/btn_regist_update.jpg"></a>　　<a href="#"><img  onclick="clearForm()"; border="0" height="22" width="82" alt="クリア" src="img/common/btn_clear.jpg"></a></td> <?php } ?>
			      </tr>
				</table>
			</td>
		  </tr>
		</table>

	  </div>
	  <?php
//	  }
	  ?>
<p>&nbsp;</p>

        <?php
		if($_SESSION['user_type']!="" && $_SESSION['user_type'] !="222")
		{

		?>


		<!-- UCHIDA EDIT 11/07/27 -->
		<!-- <div id="message_BOX" style="height:450px; overflow:auto; width:950px;"> -->
        <div id="message_BOX" style="width:950px;">
            <div class="box4">
                <table border="0" align="center" cellpadding="1" cellspacing="1" style="width:90%;">
                <tr align="center">
                  <td width="10%">No.</td>
                  <td>卓名
				 <!-- <span class="txt1"><a href="default.php?order_by=name&asc=true">▲</a>
                        	<a href="default.php?order_by=name&asc=false">▼</a></span>-->

<!--//							 	<span style="color:gray;">▲▼</span>-->

				  </td>
             	  <td>順序変更</td>
                  <td>編集</td>
                  <td>削除</td>
                </tr>
              </table>
            </div>
            <?php
            	$query_string="SELECT * FROM spssp_tables_name  ORDER BY display_order DESC ;";
				//echo $query_string;
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
            	 <div class="<?=$class?>" id="boxid<?=$row['id']?>">
            		<table border="0" align="center" cellpadding="1" cellspacing="1" style="width:90%;">
            			<tr align="center">
                        	<td width="10%"><?=$j?></td>
                            <td><?=$row['name']?></td>
                             <td>
                               <?php
									if($_SESSION['user_type']!="" && $_SESSION['user_type'] !="222")
									{

									?>
							  <span class="txt1"><a href="default.php?page=<?=$current_page?>&action=sort&amp;move=up&amp;id=<?=$row['id']?>">▲</a>
                             <a href="default.php?page=<?=(int)$_GET['page']?>&action=sort&amp;move=down&amp;id=<?=$row['id']?>"> ▼</a></span>
							 <?php
							 }else
							 {?>
							 	<span style="color:gray;">▲▼</span>
							<?php }

							 ?>

                           </td>
							<td>
                            	<a href="javascript:void(0);" onClick="<?php if($_SESSION['user_type']==222){?>alert('権限がありません');<?php }else{?>window.location='default.php?id=<?=$row['id']?>&name=<?=$row['name']?>&userid=<?=$_SESSION['user_type']?>';<?php }?>" >
                                	<img src="img/common/btn_edit.gif" width="42" height="17" />
                                </a>
                            </td>
              				<td>
                            	<a href="javascript:void(0);" onClick="<?php if($_SESSION['user_type']==222){?>alert('権限がありません');<?php }else{?>confirmDelete('default.php?action=delete&id=<?=$row['id']?>');<?php }?>">
                                	<img src="img/common/btn_deleate.gif" width="42" height="17" />
                                </a>
                            </td>
            			</tr>
                     </table>
        		</div>
             <?php
			 	$i++;
				$j++;
             	}

		}else{
			 ?>


		<!-- UCHIDA EDIT 11/07/27 -->
		<!-- <div id="message_BOX" style="height:450px; overflow:auto; width:950px;"> -->
        <div id="message_BOX" style="width:400px;">
            <div class="box4">
                <table border="0" align="center" cellpadding="1" cellspacing="1" style="width:90%;">
                <tr align="center">
                  <td width="10%">No.</td>
                  <td>卓名
				  </td>
                </tr>
              </table>
            </div>
            <?php
            	$query_string="SELECT * FROM spssp_tables_name  ORDER BY display_order DESC ;";
				//echo $query_string;
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
            	 <div class="<?=$class?>" id="boxid<?=$row['id']?>">
            		<table border="0" align="center" cellpadding="1" cellspacing="1" style="width:90%;">
            			<tr align="center">
                        	<td width="10%"><?=$j?></td>
                            <td><?=$row['name']?></td>
            			</tr>
                     </table>
        		</div>
             <?php
			 	$i++;
				$j++;
             	}

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
<?php if(isset($err)){
		echo "<script>
			alert('".$obj->GetErrorMsgNew($err)."');
			</script>";
		}?>
		<?php if(isset($msg)){	echo "<script>
			alert('".$obj->GetSuccessMsgNew($msg)."');
			</script>"; } ?>